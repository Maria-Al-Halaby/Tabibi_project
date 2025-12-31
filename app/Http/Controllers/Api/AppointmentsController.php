<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ClinicCenter;
use App\Models\ClinicCenterDoctor;
use App\Models\Doctor;
use App\Models\DoctorSchedules;
use Illuminate\Http\Request;
//use Illuminate\Support\Carbon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppointmentsController extends Controller
{
    
    function index() 
    {
        $patientId = Auth::user()->patient->id ?? null;

    if (!$patientId) {
        return response()->json(['message' => 'Patient not found' ,
        "status" => false], 422);
        }

    $appointments = Appointment::with([
            'doctor.user',
            'doctor.specialization',
            'doctor.ratings',
            'prescriptions'
        ])
        ->where('patient_id', $patientId)
        ->orderByDesc('start_at')
        ->get();

    $grouped = [
        //'finished' => [],
        'pending'  => [],
        'canceled' => [],
        'completed' => []
    ];

    foreach ($appointments as $appointment) {

        $doctor = $appointment->doctor;

        $base = [
            'id'   => $appointment->id,
            'date' => Carbon::parse($appointment->start_at)->toDateString(),
            'time' => Carbon::parse($appointment->start_at)->format('H:i'),

            'doctor' => [
                'id'   => $doctor->id,
                'name' => $doctor->user->name ?? '',
                'rate' => round($doctor->ratings->avg('rating'), 1),
                'specialty' => [
                    'id'   => $doctor->specialization->id ?? null,
                    'name' => $doctor->specialization->name ?? null,
                ],
            ],
        ];

        // completed 
        if ($appointment->status === 'completed') {
            $base['doctor_notes'] = [
                'note' => $appointment->doctor_note ?? null, 
                'prescription' => $appointment->prescriptions->first()->prescriptions_note  ?? '', 
                /*    'prescription' => $appointment->prescriptions->map(fn($p) => [
                    // 'id' => $p->id,
                    'note' => $p->prescriptions_note, //
                ])->values(), */

            ];
        }

        $grouped[$appointment->status][] = $base;
    }

    return response()->json([
        'appointment' => $grouped
    ]);
    }
    function get_doctor_centers(Doctor $doctor)
    {
        $centers = $doctor->clinic_center()
        ->select('clinic_centers.id', 'clinic_centers.name', 'clinic_centers.address')
        ->withPivot('price')  
        ->get()
        ->map(fn($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'address' => $c->address,
            'price' => $c->pivot->price ?? null,
        ]);

        return response()->json(["message" => "get doctor clinic centers",
        "status" => $centers!=null ? true : false ,
        "data" => [ "centers" => $centers]
        ]);
    }


    public function getAtLeast30DaysAfterTodayForTheDoctorInThisCenter(Request $request, Doctor $doctor, ClinicCenter $center)
    {
    $wanted = (int) $request->query('days', 30); 
    $maxLookAhead = 365;

    $pivot = ClinicCenterDoctor::where('doctor_id', $doctor->id)
        ->where('clinic_center_id', $center->id)
        ->first();

    if (!$pivot) {
        return response()->json(['days' => []]);
    }

    $availableWeekdays = DoctorSchedules::where('doctor_id', $doctor->id)
        ->where('clinic_center_doctor_id', $pivot->id)
        ->pluck('day_of_week')
        ->unique()
        ->values()
        ->toArray();

    if (empty($availableWeekdays)) {
        return response()->json(['days' => []]);
    }

    $start = Carbon::today();
    $days = [];
    $checked = 0;
    $i = 0;

    while (count($days) < $wanted && $checked < $maxLookAhead) {
        $date = $start->copy()->addDays($i);

        if (in_array($date->dayOfWeek, $availableWeekdays, true)) {
            $days[] = ['date' => $date->toDateString()];
        }

        $i++;
        $checked++;
    }

    return response()->json(["message" => "Get at least 30 days after today for the doctor for this center", 
    "status" => $days!=null ? true :false ,
    "data" => [
            "days" => [
                "date" => $days
            ]
            ]
    ]);
    }


public function getAvailableTimes(Doctor $doctor, ClinicCenter $center, string $date)
{
    try {
        $dateObj = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
    } catch (\Exception $e) {
        return response()->json(['message' => 'Invalid date format. Use Y-m-d'], 422);
    }

    if ($dateObj->lt(Carbon::today())) {
        return response()->json(['message' => 'Date must be today or later'], 422);
    }

    $step = 30;

    $pivot = ClinicCenterDoctor::where('doctor_id', $doctor->id)
        ->where('clinic_center_id', $center->id)
        ->first();

    if (!$pivot) {
        return response()->json([
            'date' => $dateObj->toDateString(),
            'periods' => ['morning'=>[], 'afternoon'=>[], 'evening'=>[]],
        ]);
    }

    $schedules = DoctorSchedules::where('doctor_id', $doctor->id)
        ->where('clinic_center_doctor_id', $pivot->id)
        ->where('day_of_week', $dateObj->dayOfWeek) // 0..6
        ->get();

    if ($schedules->isEmpty()) {
        return response()->json([
            'date' => $dateObj->toDateString(),
            'periods' => ['morning'=>[], 'afternoon'=>[], 'evening'=>[]],
        ]);
    }

    $bookedTimes = Appointment::where('doctor_id', $doctor->id)
        ->where('clinic_center_id', $center->id)
        ->whereDate('start_at', $dateObj->toDateString())
        ->where('status', '!=', 'canceled')
        ->get()
        ->map(fn($a) => Carbon::parse($a->start_at)->format('H:i'))
        ->toArray();

    $allSlots = [];
    foreach ($schedules as $sch) {
        $allSlots = array_merge(
            $allSlots,
            $this->buildSlots($sch->start_time, $sch->end_time, $step)
        );
    }

    $allSlots = array_values(array_unique($allSlots));
    sort($allSlots);

    $availableSlots = array_values(array_diff($allSlots, $bookedTimes));

    if ($dateObj->isSameDay(Carbon::today())) {
        $now = Carbon::now();
        $availableSlots = array_values(array_filter($availableSlots, function ($time) use ($dateObj, $now) {
            $slotDT = Carbon::parse($dateObj->toDateString().' '.$time.':00');
            return $slotDT->greaterThan($now);
        }));
    }

    $periods = ['morning'=>[], 'afternoon'=>[], 'evening'=>[]];

    foreach ($availableSlots as $t) {
        $hour = (int) substr($t, 0, 2);

        if ($hour < 12)      $periods['morning'][]   = ['time' => $t];
        elseif ($hour < 17)  $periods['afternoon'][] = ['time' => $t];
        else                 $periods['evening'][]   = ['time' => $t];
    }

    return response()->json([
        'date' => $dateObj->toDateString(),
        'periods' => $periods,
    ]);
}

private function buildSlots(?string $startTime, ?string $endTime, int $stepMinutes = 30): array
{
    if (!$startTime || !$endTime) return [];

    if (strlen($startTime) === 5) $startTime .= ':00';
    if (strlen($endTime) === 5) $endTime .= ':00';

    $start = Carbon::createFromFormat('H:i:s', $startTime);
    $end   = Carbon::createFromFormat('H:i:s', $endTime);

    $slots = [];
    while ($start->lt($end)) {
        $slots[] = $start->format('H:i');
        $start->addMinutes($stepMinutes);
    }

    return $slots;
}


public function storeAppointment(Request $request,Doctor $doctor,ClinicCenter $center,string $date,string $period) {
    $allowedPeriods = ['morning', 'afternoon', 'evening'];
    if (!in_array($period, $allowedPeriods, true)) {
        return response()->json(['message' => 'Invalid period'], 422);
    }

    try {
        $dateObj = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
    } catch (\Exception $e) {
        return response()->json(['message' => 'Invalid date format'], 422);
    }

    if ($dateObj->lt(Carbon::today())) {
        return response()->json(['message' => 'Date must be today or later'], 422);
    }

    $data = $request->validate([
        'time' => 'required|date_format:H:i',
        'note' => 'nullable|string|max:500',

        'diagnosis' => 'nullable|array',
        'diagnosis.diagnosis_ratio' => 'nullable|numeric|min:0|max:1',
        'diagnosis.diagnosis_name' => 'nullable|string|max:255',
        'diagnosis.is_emergency' => 'nullable|boolean',
        //'diagnose.answers' => 'nullable|array',
        //'diagnose.answers.*.question_id' => 'required_with:diagnose.answers|exists:questions,id',
        //'diagnose.answers.*.answer' => 'required_with:diagnose.answers|string|max:255',
    ]);

    $patientId = auth()->user()->patient->id ?? null;
    if (!$patientId) {
        return response()->json(['message' => 'Patient not found'], 422);
    }

    $startAt = Carbon::createFromFormat('Y-m-d H:i', $dateObj->toDateString().' '.$data['time']);

    if ($startAt->lt(Carbon::now())) {
        return response()->json(['message' => 'Cannot book past time'], 422);
    }

    $hour = (int) $startAt->format('H');

    $periodOk = match ($period) {
        'morning'   => $hour < 12,
        'afternoon' => $hour >= 12 && $hour < 17,
        'evening'   => $hour >= 17,
        default     => false,
    };

    if (!$periodOk) {
        return response()->json(['message' => 'Time does not match selected period',
    "status" => false ], 422);
    }

    return DB::transaction(function () use ($doctor, $center, $dateObj, $startAt, $patientId, $data, $period) {

        $pivot = ClinicCenterDoctor::where('doctor_id', $doctor->id)
            ->where('clinic_center_id', $center->id)
            ->first();

        if (!$pivot) {
            return response()->json(['message' => 'Doctor not available in this center'], 422);
        }

        $weekday = $dateObj->dayOfWeek;

        $inSchedule = DoctorSchedules::where('doctor_id', $doctor->id)
            ->where('clinic_center_doctor_id', $pivot->id)
            ->where('day_of_week', $weekday)
            ->whereTime('start_time', '<=', $startAt->format('H:i:s'))
            ->whereTime('end_time',   '>',  $startAt->format('H:i:s'))
            ->exists();

        if (!$inSchedule) {
            return response()->json(['message' => 'Selected time is outside doctor schedule'], 422);
        }

        $exists = Appointment::where('doctor_id', $doctor->id)
            ->where('clinic_center_id', $center->id)
            ->where('start_at', $startAt->toDateTimeString())
            ->where('status', '!=', 'canceled')
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'This time is already booked' , 
        "status" => false ], 409);
        }

        $appointment = Appointment::create([
            'patient_id'       => $patientId,
            'doctor_id'        => $doctor->id,
            'clinic_center_id' => $center->id,
            'start_at'         => $startAt,
            'note' => $data["note"],
            'status'           => 'pending',
        ]);

        return response()->json([
            'message' => 'Appointment booked successfully',
            'status'  => true,
            'data' => [
                'appointment' => [
                    //'id'        => $appointment->id,
                    //'doctor_id' => $doctor->id,
                    'center_id' => $center->id,
                    'date'      => $startAt->toDateString(),
                    'time'      => $startAt->format('H:i'),
                    //'period'    => $period,
                    //'note'      => $data['note'] ?? null,
                    //'status'    => $appointment->status,
                    'diagnosis'  => $data['diagnosis'] ?? null,
                ]
            ]
        ], 201);
    });
}

    public function  appointment_details(Appointment $appointment)
    {
        $user = auth()->user();

        $isPatientOwner = $user->hasRole('patient') && optional($user->patient)->id === $appointment->patient_id;
        $isDoctorOwner  = $user->hasRole('doctor') && optional($user->doctor)->id === $appointment->doctor_id;

    if (!$isPatientOwner && !$isDoctorOwner) {
        return response()->json(['message' => 'Unauthorized' , 
    "status" => false 
        ],
    403);
    }

    $appointment->load([
        'patient.user',
        'answers.question',  
    ]);

    $patient = $appointment->patient;
    $patientUser = $patient?->user;

    $diagnose = null;

    $hasDiagnoseColumns =
        isset($appointment->result_ratio) ||
        isset($appointment->expected_disease) ||
        isset($appointment->is_risk);

    if ($hasDiagnoseColumns || ($appointment->relationLoaded('answers') && $appointment->answers->isNotEmpty())) {
        $diagnose = [
            'diagnosis.diagnosis_ratio'      => $appointment->diagnosis_ratio ?? null,
            'diagnosis_name'  => $appointment->diagnosis_name ?? null,
            'is_emergency'           => $appointment->is_emergency ?? null,
            'answers' => $appointment->answers?->map(function ($a) {
                return [
                    'question' => $a->question?->question ?? $a->question?->title ?? null,
                    'answer'   => $a->answer ?? null,
                ];
            })->values() ?? [],
        ];
    }

    return response()->json([
        'appointment' => [
            'status' => $appointment->status,
            'patient' => [
                'img'       => $patientUser?->profile_image ?? null,
                'full_name' => $patientUser?->name  . " " . $patientUser?->last_name ?? '',
                'gender'    => $patient?->gender ?? null,
                //'age'       => $patient?->age ?? null,
                'height'    => $patient?->height ?? null,
                'weight'    => $patient?->weight ?? null,
                'has_children' => $patient->has_children ?? null ,
                'number_of_children' => $patient->number_of_children ?? null , 
                'birth_date' => $patient->birth_date ?? null ,
                'smoker'    => $patient?->smoker ?? null,
                'marital_status' => $patient?->marital_status ?? null,
            ],
            'note' => $appointment->note ?? null, 
            'diagnosis' => $diagnose, // nullable

            'date' => Carbon::parse($appointment->start_at)->toDateString(),
            'time' => Carbon::parse($appointment->start_at)->format('H:i'),
        ]
    ]);
    }

    public function cancelAppointment(Request $request)
    {
        $data = $request->validate([
        'appointment_id' => 'required|exists:appointments,id',
    ]);

    $appointment = Appointment::findOrFail($data['appointment_id']);
    $user = auth()->user();

    $isPatientOwner = $user->hasRole('patient') && optional($user->patient)->id === $appointment->patient_id;
    $isDoctorOwner  = $user->hasRole('doctor') && optional($user->doctor)->id === $appointment->doctor_id;

    if (!$isPatientOwner && !$isDoctorOwner) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    if ($appointment->status === 'finished') {
        return response()->json(['message' => 'Cannot cancel a finished appointment',
    "status" => false
    ], 422);
    }

    $appointment->update(['status' => 'canceled']);

    return response()->json([
        'message' => 'Appointment canceled',
        'status' => true,
        'data' => [
            'appointment_id' => $appointment->id,
            'new_status' => $appointment->status,
        ]
    ]);
    }


}
