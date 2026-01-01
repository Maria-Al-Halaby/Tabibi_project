<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ClinicCenter;
use App\Models\Doctor;
use App\Traits\PushNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorAppointmentController extends Controller
{
    use PushNotification;
    public function index($center = null, $date = null)
    {
        $doctor = Doctor::where('user_id', auth()->id())->firstOrFail();

        $centerId = ($center === null || $center === '0') ? null : (int) $center;
        $date = $date ?? 'today';

        $query = Appointment::with(['patient.user' , 'clinic_center:id,name'])
            ->where('doctor_id', $doctor->id);

        if ($centerId) {
            $query->where('clinic_center_id', $centerId);
        }

        if ($date === 'today') {
            //$query->whereDate('start_at', Carbon::today());
            $query->whereBetween('start_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()]);

        } elseif ($date === 'tomorrow') {
            //$query->whereDate('start_at', Carbon::tomorrow());
            $query->whereBetween('start_at', [Carbon::tomorrow()->startOfDay(), Carbon::tomorrow()->endOfDay()]);

        } elseif ($date === 'this_week') {
            /* $query->whereBetween('start_at', [
                Carbon::today()->startOfDay(),
                Carbon::today()->endOfWeek()->endOfDay(),
            ]); */
            $query->whereBetween('start_at', 
            [Carbon::today()->startOfWeek()->startOfDay(), 
            Carbon::today()->endOfWeek()->endOfDay()
            ]);

        } else {
            // YYYY-MM-DD
            try {
                $day = Carbon::createFromFormat('Y-m-d', $date);
                //$query->whereDate('start_at', $day);
                $query->whereBetween('start_at', 
        [
                    $day->startOfDay(), 
                    $day->endOfDay()
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Invalid date. Use today|tomorrow|this_week|YYYY-MM-DD'
                ], 422);
            }
        }

        $appointments = $query->orderBy('start_at')->get();

        $data = $appointments->map(function ($a) {
            return [
                'id' => $a->id,
                'patient_name' => $a->patient?->user?->name ?? '',
                'patient_profile' => $a->patient?->user?->profile_image ?? null , 
                'status' => $a->status,
                'date' => Carbon::parse($a->start_at)->toDateString(),
                'time' => Carbon::parse($a->start_at)->format('H:i'),
                'clinic_center_name' => $a->clinic_center?->name
            ];
        })->values();

        return response()->json([
            'message' => 'doctor appointments',
            'status'  => true,
            'data'    => $data,
        ]);
    }

/*     public function end_appointment(Request $request)
    {
        $data = $request->validate([
        'appointment_id' => 'required|exists:appointments,id',
        'note' => 'nullable|string|max:2000',
        'prescription' => 'nullable|array',
        'prescription.*.medicine' => 'required_with:prescription|string|max:255',
        'prescription.*.dose' => 'nullable|string|max:255',
        'prescription.*.duration' => 'nullable|string|max:255',
    ]);

    $doctor = Doctor::where('user_id', auth()->id())->firstOrFail();

    $appointment = Appointment::where('id', $data['appointment_id'])
        ->where('doctor_id', $doctor->id)
        ->firstOrFail();

    if ($appointment->status === 'canceled') {
        return response()->json(['message' => 'Cannot end a canceled appointment' , 
    "status" => false 
    ], 422);
    }

    if ($appointment->status === 'finished') {
        return response()->json(['message' => 'Appointment already finished', 
    "status" => false 
    ], 422);
    }

    return DB::transaction(function () use ($appointment, $data) {

        $appointment->update([
            'status' => 'finished',
            'end_at' => Carbon::now(),
            'doctor_note' => $data['note'] ?? null,
        ]);

        $appointment->prescriptions()->delete();

        if (!empty($data['prescription'])) {
            foreach ($data['prescription'] as $p) {
                $appointment->prescriptions()->create([
                    'medicine' => $p['medicine'],
                    'dose' => $p['dose'] ?? null,
                    'duration' => $p['duration'] ?? null,
                ]);
            }
        }

        // Response
        $appointment->load('prescriptions');

        return response()->json([
            'message' => 'Appointment completed',
            'status' => true,
            'data' => [
                'appointment_id' => $appointment->id,
                'new_status' => $appointment->status,
                'doctor_note' => $appointment->doctor_note,
                'prescription' => $appointment->prescriptions->map(fn($p) => [
                    'medicine' => $p->medicine,
                    'dose' => $p->dose,
                    'duration' => $p->duration,
                ])->values(),
            ]
        ], 200);
    });
    } */


    public function end_appointment(Request $request)
    {
    $data = $request->validate([
        'appointment_id'     => 'required|exists:appointments,id',
        'note'               => 'required|string|max:2000',
        'prescription_note'  => 'required|string|max:5000',
    ]);

    $doctor = Doctor::where('user_id', auth()->id())->firstOrFail();

    $appointment = Appointment::where('id', $data['appointment_id'])
        ->where('doctor_id', $doctor->id)
        ->firstOrFail();

    if ($appointment->status === 'canceled') {
        return response()->json([
            'message' => 'Cannot end a canceled appointment',
            'status' => false
        ], 422);
    }

    if ($appointment->status === 'completed') {
        return response()->json([
            'message' => 'Appointment already finished',
            'status' => false
        ], 422);
    }

    DB::transaction(function () use ($appointment, $data) {

        $appointment->update([
            'status'      => 'completed',
            'end_at'      => now(),
            'doctor_note' => $data['note'] ?? null,
        ]);

        $appointment->prescriptions()->delete();

        if (!empty($data['prescription_note'])) {
            $appointment->prescriptions()->create([
                'prescriptions_note' => $data['prescription_note'], 
            ]);
        }
    });

    //send notification
    $this->notifyPatientAppointmentCompleted($appointment);

    return response()->json([
        'message' => 'Appointment completed successfully',
        'status'  => true,
    ], 200);
    }   


    private function notifyPatientAppointmentCompleted(Appointment $appointment): void
    {
        $user  = $appointment->patient->user;
        $token = $user->fcm_token;

        if (!$token) {
            return;
        }

        $title = 'Appointment Completed';

        $body = 'Your appointment on '
            . $appointment->start_at->format('Y-m-d H:i')
            . ' has been completed. We hope you are feeling better.';

        $data = [
            'type' => 'appointment_completed',
            'appointment_id' => (string) $appointment->id,
        ];

        $this->sendNotification($token, $title, $body, $data);
    }

}

