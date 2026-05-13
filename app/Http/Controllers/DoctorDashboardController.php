<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\DoctorLabRequest;
use App\Models\DoctorRadiologyRequest;
use App\Models\LabTest;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\TypeOfMedicalImage;
use App\Notifications\AppointmentAlertNotification;
use App\Support\AppointmentMedicalRecordPresenter;
use App\Traits\PushNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorDashboardController extends Controller
{
    use PushNotification;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $doctor = auth()->user()?->doctor;

            if (! $doctor || $doctor->doctor_type !== 'doctor') {
                abort(403, 'Unauthorized');
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $doctor = auth()->user()->doctor;
        $centerId = $request->integer('center_id') ?: null;
        $dateFilter = $request->query('date_filter', 'today');
        $specificDate = $request->query('specific_date');

        $centers = $doctor->clinic_center()
            ->orderBy('clinic_centers.name')
            ->get(['clinic_centers.id', 'clinic_centers.name']);

        $query = Appointment::with([
            'patient.user',
            'clinic_center',
            'prescriptions.items',
            'labRequests.tests',
            'radiologyRequests.type',
            'attachedMedicalRecords',
        ])
            ->where('doctor_id', $doctor->id)
            ->where('type', 'doctor')
            ->where('start_at', '>=', Carbon::today()->startOfDay());

        if ($centerId && $centers->contains('id', $centerId)) {
            $query->where('clinic_center_id', $centerId);
        }

        $this->applyDateFilter($query, $dateFilter, $specificDate);

        $appointments = $query
            ->orderBy('start_at')
            ->get();

        return view('doctor.index', [
            'appointments' => $appointments,
            'centers' => $centers,
            'doctorName' => trim((auth()->user()->name ?? '').' '.(auth()->user()->last_name ?? '')),
            'filters' => [
                'center_id' => $centerId,
                'date_filter' => $dateFilter,
                'specific_date' => $specificDate,
            ],
        ]);
    }

    public function showCompleteForm(Appointment $appointment)
    {
        $doctor = auth()->user()->doctor;

        if ($appointment->type !== 'doctor' || $appointment->doctor_id !== $doctor->id) {
            abort(404);
        }

        $appointment->load([
            'patient.user',
            'clinic_center',
            'prescriptions.items',
            'labRequests.tests',
            'radiologyRequests.type',
            'attachedMedicalRecords',
        ]);

        return view('doctor.complete', [
            'appointment' => $appointment,
            'attachedMedicalRecords' => AppointmentMedicalRecordPresenter::forAppointment($appointment),
            'labTests' => LabTest::orderBy('name')->get(['id', 'name']),
            'medicalImageTypes' => TypeOfMedicalImage::orderBy('name')->get(['id', 'name']),
            'hasPharmacist' => DB::table('clinic_center_pharmacists')
                ->where('clinic_center_id', $appointment->clinic_center_id)
                ->exists(),
        ]);
    }

    public function complete(Request $request)
    {
        $request->merge([
            'prescription_items' => $this->filledPrescriptionItems($request->input('prescription_items', [])),
            'radiology_requests' => $this->filledRadiologyRequests($request->input('radiology_requests', [])),
        ]);

        $data = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'note' => 'required|string|max:2000',
            'prescription_note' => 'nullable|string|max:5000',
            'prescription_items' => 'nullable|array',
            'prescription_items.*.medicine_name' => 'required_with:prescription_items|string|max:255',
            'prescription_items.*.dose' => 'required_with:prescription_items|string|max:255',
            'prescription_items.*.frequency' => 'required_with:prescription_items|string|max:255',
            'prescription_items.*.start_date' => 'required_with:prescription_items|date',
            'prescription_items.*.end_date' => 'required_with:prescription_items|date',
            'prescription_items.*.instructions' => 'nullable|string|max:2000',
            'send_to_pharmacy' => 'nullable|boolean',
            'lab_request_note' => 'nullable|string|max:2000',
            'lab_tests' => 'nullable|array',
            'lab_tests.*' => 'exists:lab_tests,id',
            'radiology_requests' => 'nullable|array',
            'radiology_requests.*.type_of_medical_image_id' => 'required_with:radiology_requests|exists:type_of_medical_images,id',
            'radiology_requests.*.notes' => 'nullable|string|max:2000',
        ]);

        $doctor = auth()->user()->doctor;
        $appointment = Appointment::where('id', $data['appointment_id'])
            ->where('doctor_id', $doctor->id)
            ->where('type', 'doctor')
            ->firstOrFail();

        if ($appointment->status === 'canceled') {
            return back()->with('error', 'Cannot complete a canceled appointment.')->withInput();
        }

        if ($appointment->status === 'completed') {
            return back()->with('error', 'Appointment already completed.')->withInput();
        }

        $sendToPharmacy = $request->boolean('send_to_pharmacy', false);

        if ($sendToPharmacy && ! $this->centerHasPharmacist((int) $appointment->clinic_center_id)) {
            return back()->with('error', 'This center does not have a pharmacist.')->withInput();
        }

        DB::transaction(function () use ($appointment, $data, $sendToPharmacy) {
            $appointment->update([
                'status' => 'completed',
                'end_at' => now(),
                'doctor_note' => $data['note'],
            ]);

            if (! empty($data['prescription_note']) || ! empty($data['prescription_items'])) {
                $prescription = Prescription::create([
                    'appointment_id' => $appointment->id,
                    'general_note' => $data['prescription_note'] ?? null,
                    'status' => 'pending',
                    'send_to_pharmacy' => $sendToPharmacy,
                    'pharmacy_status' => $sendToPharmacy ? 'pending' : 'not_sent',
                ]);

                foreach ($data['prescription_items'] ?? [] as $item) {
                    PrescriptionItem::create([
                        'prescription_id' => $prescription->id,
                        'medicine_name' => $item['medicine_name'],
                        'dose' => $item['dose'],
                        'frequency' => $item['frequency'],
                        'start_date' => $item['start_date'],
                        'end_date' => $item['end_date'],
                        'instructions' => $item['instructions'] ?? null,
                    ]);
                }
            }

            if (! empty($data['lab_tests'])) {
                $labRequest = DoctorLabRequest::create([
                    'appointment_id' => $appointment->id,
                    'notes' => $data['lab_request_note'] ?? null,
                ]);

                $labRequest->tests()->attach($data['lab_tests']);
            }

            foreach ($data['radiology_requests'] ?? [] as $request) {
                DoctorRadiologyRequest::create([
                    'appointment_id' => $appointment->id,
                    'type_of_medical_image_id' => $request['type_of_medical_image_id'],
                    'notes' => $request['notes'] ?? null,
                ]);
            }
        });

        $appointment->refresh();
        $this->notifyPatientAppointmentCompleted($appointment);

        return redirect()
            ->route('doctor.dashboard')
            ->with('success', 'Appointment completed successfully.');
    }

    public function cancel(Appointment $appointment)
    {
        $doctor = auth()->user()->doctor;

        if ($appointment->type !== 'doctor' || $appointment->doctor_id !== $doctor->id) {
            abort(404);
        }

        if ($appointment->status === 'completed') {
            return back()->with('error', 'Cannot cancel a completed appointment.');
        }

        if ($appointment->status !== 'canceled') {
            $appointment->update(['status' => 'canceled']);
            $this->notifyPatientAppointmentCancelled($appointment);
        }

        return back()->with('success', 'Appointment canceled successfully.');
    }

    private function applyDateFilter($query, ?string $dateFilter, ?string $specificDate): void
    {
        if ($dateFilter === 'tomorrow') {
            $query->whereBetween('start_at', [Carbon::tomorrow()->startOfDay(), Carbon::tomorrow()->endOfDay()]);

            return;
        }

        if ($dateFilter === 'this_week') {
            $query->whereBetween('start_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfWeek()->endOfDay()]);

            return;
        }

        if ($dateFilter === 'specific_day' && $specificDate) {
            try {
                $day = Carbon::createFromFormat('Y-m-d', $specificDate);

                if ($day->lt(Carbon::today())) {
                    $query->whereRaw('1 = 0');

                    return;
                }

                $query->whereBetween('start_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()]);

                return;
            } catch (\Throwable) {
                $query->whereRaw('1 = 0');

                return;
            }
        }

        $query->whereBetween('start_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()]);
    }

    private function filledPrescriptionItems(array $items): array
    {
        return array_values(array_filter($items, function ($item) {
            return collect($item)->filter(fn ($value) => filled($value))->isNotEmpty();
        }));
    }

    private function filledRadiologyRequests(array $requests): array
    {
        return array_values(array_filter($requests, function ($request) {
            return filled($request['type_of_medical_image_id'] ?? null) || filled($request['notes'] ?? null);
        }));
    }

    private function centerHasPharmacist(int $centerId): bool
    {
        return DB::table('clinic_center_pharmacists')
            ->where('clinic_center_id', $centerId)
            ->exists();
    }

    private function notifyPatientAppointmentCompleted(Appointment $appointment): void
    {
        $user = $appointment->registeredPatientUser();

        if (! $user) {
            return;
        }

        $title = 'Appointment Completed';
        $body = 'Your appointment on '.$appointment->start_at->format('Y-m-d H:i').' has been completed. We hope you are feeling better.';
        $data = [
            'type' => 'appointment_completed',
            'appointment_id' => (string) $appointment->id,
        ];

        $user->notify(new AppointmentAlertNotification(
            title: $title,
            body: $body,
            type: 'appointment_completed',
            appointmentId: $appointment->id,
        ));

        if ($user->fcm_token) {
            $this->sendNotification($user->fcm_token, $title, $body, $data);
        }
    }

    private function notifyPatientAppointmentCancelled(Appointment $appointment): void
    {
        $user = $appointment->registeredPatientUser();

        if (! $user) {
            return;
        }

        $title = 'Appointment Cancelled';
        $body = 'Your appointment scheduled on '.$appointment->start_at->format('Y-m-d H:i').' has been cancelled by the doctor.';
        $data = [
            'type' => 'appointment_cancelled',
            'appointment_id' => (string) $appointment->id,
        ];

        $user->notify(new AppointmentAlertNotification(
            title: $title,
            body: $body,
            type: 'appointment_cancelled',
            appointmentId: $appointment->id,
        ));

        if ($user->fcm_token) {
            $this->sendNotification($user->fcm_token, $title, $body, $data);
        }
    }
}
