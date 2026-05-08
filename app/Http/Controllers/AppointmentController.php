<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ClinicCenterDoctor;
use App\Models\Doctor;
use App\Models\DoctorSchedules;
use App\Models\RadiologyAppointment;
use App\Models\Specialization;
use App\Notifications\AppointmentAlertNotification;
use App\Traits\PushNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    use PushNotification;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $center = $this->resolveCenterForUser();

        if (!$center) {
            abort(404, 'Center not found for this account');
        }

        $selectedSpecializationId = $request->integer('specialization_id') ?: null;

        $appointments = Appointment::with([
                'patient.user',
                'doctor.user',
                'doctor.specialization',
            ])
            ->where('clinic_center_id', $center->id)
            ->where('status', 'pending')
            ->when($selectedSpecializationId, function ($query) use ($selectedSpecializationId) {
                $query->whereHas('doctor', function ($doctorQuery) use ($selectedSpecializationId) {
                    $doctorQuery->where('specialization_id', $selectedSpecializationId);
                });
            })
            ->orderBy('start_at')
            ->get();

        $specializations = Specialization::whereHas('doctors', function ($doctorQuery) use ($center) {
                $doctorQuery->whereHas('appointments', function ($appointmentQuery) use ($center) {
                    $appointmentQuery->where('clinic_center_id', $center->id)
                        ->where('status', 'pending');
                });
            })
            ->orderBy('name')
            ->get();

        $dashboardMode = $user->hasRole('secretary') ? 'secretary' : 'admin';
        $centerDoctors = $dashboardMode === 'secretary' ? $this->getCenterDoctors($center) : collect();
        $centerDoctorSpecializations = $dashboardMode === 'secretary'
            ? $centerDoctors
                ->pluck('specialization')
                ->filter()
                ->unique('id')
                ->sortBy('name')
                ->values()
            : collect();
        $centerLabTests = $dashboardMode === 'secretary'
            ? $center->labTests()->select('lab_tests.id', 'lab_tests.name', 'clinic_center_lab_tests.price')->orderBy('lab_tests.name')->get()
            : collect();
        $centerMedicalImageTypes = $dashboardMode === 'secretary'
            ? $center->medicalImages()->select('type_of_medical_images.id', 'type_of_medical_images.name', 'clinic_center_medical_images.price')->orderBy('type_of_medical_images.name')->get()
            : collect();

        return view('Admin.Appointment.index', compact(
            'appointments',
            'specializations',
            'selectedSpecializationId',
            'dashboardMode',
            'center',
            'centerDoctors',
            'centerDoctorSpecializations',
            'centerLabTests',
            'centerMedicalImageTypes'
        ));
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->hasRole('secretary')) {
            abort(403);
        }

        $center = $this->resolveCenterForUser();

        if (!$center) {
            return back()->withErrors(['message' => 'Center not found for this account.'])->withInput();
        }

        $data = $request->validate([
            'patient_name' => 'required|string|max:150',
            'patient_phone' => 'required|string|max:20',
            'patient_gender' => 'nullable|in:male,female',
            'patient_age' => 'nullable|integer|min:0|max:120',
            'specialization_id' => 'nullable|exists:specializations,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date_format:Y-m-d',
            'appointment_time' => 'required|date_format:H:i',
            'note' => 'nullable|string|max:500',
            'lab_tests' => 'nullable|array',
            'lab_tests.*' => 'integer|exists:lab_tests,id',
            'type_of_medical_image_id' => 'nullable|integer|exists:type_of_medical_images,id',
        ]);

        $doctor = Doctor::with(['user', 'specialization'])->findOrFail($data['doctor_id']);

        $pivot = ClinicCenterDoctor::where('clinic_center_id', $center->id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$pivot) {
            return back()->withErrors(['doctor_id' => 'Selected doctor does not belong to this center.'])->withInput();
        }

        if (!empty($data['specialization_id']) && (int) $doctor->specialization_id !== (int) $data['specialization_id']) {
            return back()->withErrors(['specialization_id' => 'Selected doctor does not belong to the selected specialty.'])->withInput();
        }

        if ($doctor->doctor_type === 'lab' && empty($data['lab_tests'])) {
            return back()->withErrors(['lab_tests' => 'Please select at least one lab test for lab appointments.'])->withInput();
        }

        if ($doctor->doctor_type === 'radiology' && empty($data['type_of_medical_image_id'])) {
            return back()->withErrors(['type_of_medical_image_id' => 'Please select the image type for radiology appointments.'])->withInput();
        }

        if ($doctor->doctor_type !== 'lab' && !empty($data['lab_tests'])) {
            return back()->withErrors(['lab_tests' => 'Lab tests can only be selected for lab doctors.'])->withInput();
        }

        if ($doctor->doctor_type !== 'radiology' && !empty($data['type_of_medical_image_id'])) {
            return back()->withErrors(['type_of_medical_image_id' => 'Image type can only be selected for radiology doctors.'])->withInput();
        }

        if ($doctor->doctor_type === 'lab' && !empty($data['lab_tests'])) {
            $centerLabTestCount = DB::table('clinic_center_lab_tests')
                ->where('clinic_center_id', $center->id)
                ->whereIn('lab_test_id', $data['lab_tests'])
                ->count();

            if ($centerLabTestCount !== count($data['lab_tests'])) {
                return back()->withErrors(['lab_tests' => 'One or more selected lab tests are not available in this center.'])->withInput();
            }
        }

        if ($doctor->doctor_type === 'radiology' && !empty($data['type_of_medical_image_id'])) {
            $imageTypeExists = DB::table('clinic_center_medical_images')
                ->where('clinic_center_id', $center->id)
                ->where('type_of_medical_image_id', $data['type_of_medical_image_id'])
                ->exists();

            if (!$imageTypeExists) {
                return back()->withErrors(['type_of_medical_image_id' => 'The selected image type is not available in this center.'])->withInput();
            }
        }

        try {
            $dateObj = Carbon::createFromFormat('Y-m-d', $data['appointment_date'])->startOfDay();
            $startAt = Carbon::createFromFormat('Y-m-d H:i', $data['appointment_date'] . ' ' . $data['appointment_time']);
        } catch (\Throwable $e) {
            return back()->withErrors(['appointment_date' => 'Invalid appointment date or time.'])->withInput();
        }

        if ($dateObj->lt(Carbon::today())) {
            return back()->withErrors(['appointment_date' => 'Appointment date must be today or later.'])->withInput();
        }

        if ($startAt->lt(Carbon::now())) {
            return back()->withErrors(['appointment_time' => 'Cannot book an appointment in the past.'])->withInput();
        }

        $inSchedule = DoctorSchedules::where('doctor_id', $doctor->id)
            ->where('clinic_center_doctor_id', $pivot->id)
            ->where('day_of_week', $dateObj->dayOfWeek)
            ->whereTime('start_time', '<=', $startAt->format('H:i:s'))
            ->whereTime('end_time', '>', $startAt->format('H:i:s'))
            ->exists();

        if (!$inSchedule) {
            return back()->withErrors(['appointment_time' => 'Selected time is outside the doctor schedule.'])->withInput();
        }

        $isBooked = Appointment::where('doctor_id', $doctor->id)
            ->where('clinic_center_id', $center->id)
            ->where('start_at', $startAt->toDateTimeString())
            ->where('status', '!=', 'canceled')
            ->exists();

        if ($isBooked) {
            return back()->withErrors(['appointment_time' => 'This time is already booked.'])->withInput();
        }

        $price = (float) ($pivot->price ?? 0);

        if ($doctor->doctor_type === 'lab') {
            $price = (float) DB::table('clinic_center_lab_tests')
                ->where('clinic_center_id', $center->id)
                ->whereIn('lab_test_id', $data['lab_tests'])
                ->sum('price');
        }

        if ($doctor->doctor_type === 'radiology') {
            $price = (float) (DB::table('clinic_center_medical_images')
                ->where('clinic_center_id', $center->id)
                ->where('type_of_medical_image_id', $data['type_of_medical_image_id'])
                ->value('price') ?? 0);
        }

        DB::transaction(function () use ($data, $doctor, $center, $startAt, $price) {
            $appointment = Appointment::create([
                'patient_id' => null,
                'temp_patient_name' => $data['patient_name'],
                'temp_patient_phone' => $data['patient_phone'],
                'temp_patient_gender' => $data['patient_gender'] ?? null,
                'temp_patient_age' => $data['patient_age'] ?? null,
                'doctor_id' => $doctor->id,
                'clinic_center_id' => $center->id,
                'type' => $doctor->doctor_type,
                'start_at' => $startAt,
                'note' => $data['note'] ?? null,
                'status' => 'pending',
                'price' => $price,
            ]);

            if ($doctor->doctor_type === 'lab' && !empty($data['lab_tests'])) {
                $appointment->labTests()->attach($data['lab_tests']);
            }

            if ($doctor->doctor_type === 'radiology' && !empty($data['type_of_medical_image_id'])) {
                RadiologyAppointment::create([
                    'appointment_id' => $appointment->id,
                    'type_of_medical_image_id' => $data['type_of_medical_image_id'],
                ]);
            }
        });

        return redirect()
            ->route('secretary.dashboard')
            ->with('message', 'Walk-in appointment scheduled successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointments)
    {
        
    }

    public function cancel(Appointment $appointments)
    {
        $center = $this->resolveCenterForUser();

        if (!$center || $appointments->clinic_center_id !== $center->id) {
            abort(403);
        }

        if ($appointments->status === 'completed') {
            return redirect()
                ->route($this->appointmentRouteName(), request()->only('specialization_id'))
                ->withErrors(['message' => 'Completed appointments cannot be canceled.']);
        }

        if ($appointments->status !== 'canceled') {
            $appointments->update([
                'status' => 'canceled',
            ]);

            $this->notifyPatientAppointmentCancelledByStaff($appointments);
        }

        return redirect()
            ->route($this->appointmentRouteName(), request()->only('specialization_id'))
            ->with('message', 'appointment canceled successfully!!');
    }

    public function availableDays(Request $request, Doctor $doctor)
    {
        $center = $this->resolveCenterForUser();

        if (!$center || !auth()->user()?->hasRole('secretary')) {
            abort(403);
        }

        $pivot = ClinicCenterDoctor::where('clinic_center_id', $center->id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$pivot) {
            return response()->json([
                'status' => false,
                'message' => 'Doctor does not belong to this center.',
                'data' => ['days' => []],
            ], 404);
        }

        $wanted = (int) $request->query('days', 30);
        $maxLookAhead = 365;

        $availableWeekdays = DoctorSchedules::where('doctor_id', $doctor->id)
            ->where('clinic_center_doctor_id', $pivot->id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        if ($availableWeekdays->isEmpty()) {
            return response()->json([
                'status' => true,
                'data' => ['days' => []],
            ]);
        }

        $start = Carbon::today();
        $days = [];
        $checked = 0;
        $i = 0;

        while (count($days) < $wanted && $checked < $maxLookAhead) {
            $date = $start->copy()->addDays($i);
            $daySchedules = $availableWeekdays->where('day_of_week', $date->dayOfWeek)->values();

            if ($daySchedules->isNotEmpty()) {
                $availableSlots = $this->buildAvailableSlotsForDate($doctor, $center->id, $date, $daySchedules);

                if (!empty($availableSlots)) {
                    $timeWindows = $daySchedules
                        ->map(function ($schedule) {
                            $start = Carbon::createFromFormat('H:i:s', strlen($schedule->start_time) === 5 ? $schedule->start_time . ':00' : $schedule->start_time);
                            $end = Carbon::createFromFormat('H:i:s', strlen($schedule->end_time) === 5 ? $schedule->end_time . ':00' : $schedule->end_time);

                            return $start->format('H:i') . ' - ' . $end->format('H:i');
                        })
                        ->unique()
                        ->values()
                        ->implode(', ');

                    $label = $date->format('l, M d Y');

                    if ($timeWindows !== '') {
                        $label .= ' | ' . $timeWindows;
                    }

                    $days[] = [
                        'date' => $date->toDateString(),
                        'label' => $label,
                    ];
                }
            }

            $i++;
            $checked++;
        }

        return response()->json([
            'status' => true,
            'data' => ['days' => $days],
        ]);
    }

    public function availableTimes(Doctor $doctor, string $date)
    {
        $center = $this->resolveCenterForUser();

        if (!$center || !auth()->user()?->hasRole('secretary')) {
            abort(403);
        }

        try {
            $dateObj = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid date format. Use Y-m-d.',
                'data' => ['times' => []],
            ], 422);
        }

        $pivot = ClinicCenterDoctor::where('doctor_id', $doctor->id)
            ->where('clinic_center_id', $center->id)
            ->first();

        if (!$pivot) {
            return response()->json([
                'status' => false,
                'message' => 'Doctor does not belong to this center.',
                'data' => ['times' => []],
            ], 404);
        }

        $schedules = DoctorSchedules::where('doctor_id', $doctor->id)
            ->where('clinic_center_doctor_id', $pivot->id)
            ->where('day_of_week', $dateObj->dayOfWeek)
            ->get();

        if ($schedules->isEmpty()) {
            return response()->json([
                'status' => true,
                'data' => ['times' => []],
            ]);
        }

        $availableSlots = $this->buildAvailableSlotsForDate($doctor, $center->id, $dateObj, $schedules);

        return response()->json([
            'status' => true,
            'data' => [
                'times' => array_map(fn ($time) => [
                    'time' => $time,
                    'label' => Carbon::createFromFormat('H:i', $time)->format('H:i'),
                ], $availableSlots),
            ],
        ]);
    }

    private function resolveCenterForUser()
    {
        return auth()->user()?->dashboardCenter();
    }

    private function getCenterDoctors($center)
    {
        if (!$center) {
            return collect();
        }

        return Doctor::query()
            ->join('clinic_center_doctor', 'doctors.id', '=', 'clinic_center_doctor.doctor_id')
            ->where('clinic_center_doctor.clinic_center_id', $center->id)
            ->with(['user:id,name,last_name', 'specialization:id,name'])
            ->select('doctors.*', 'clinic_center_doctor.price as center_price')
            ->orderBy('specialization_id')
            ->orderBy('doctors.id')
            ->get();
    }

    private function buildAvailableSlotsForDate(Doctor $doctor, int $centerId, Carbon $dateObj, $schedules): array
    {
        $bookedTimes = Appointment::where('doctor_id', $doctor->id)
            ->where('clinic_center_id', $centerId)
            ->whereDate('start_at', $dateObj->toDateString())
            ->where('status', '!=', 'canceled')
            ->get()
            ->map(fn ($appointment) => Carbon::parse($appointment->start_at)->format('H:i'))
            ->toArray();

        $allSlots = [];

        foreach ($schedules as $schedule) {
            $allSlots = array_merge(
                $allSlots,
                $this->buildSlots($schedule->start_time, $schedule->end_time, 30)
            );
        }

        $allSlots = array_values(array_unique($allSlots));
        sort($allSlots);

        $availableSlots = array_values(array_diff($allSlots, $bookedTimes));

        if ($dateObj->isSameDay(Carbon::today())) {
            $now = Carbon::now();
            $availableSlots = array_values(array_filter($availableSlots, function ($time) use ($dateObj, $now) {
                $slotDateTime = Carbon::parse($dateObj->toDateString() . ' ' . $time . ':00');

                return $slotDateTime->greaterThan($now);
            }));
        }

        return $availableSlots;
    }

    private function buildSlots(?string $startTime, ?string $endTime, int $stepMinutes = 30): array
    {
        if (!$startTime || !$endTime) {
            return [];
        }

        if (strlen($startTime) === 5) {
            $startTime .= ':00';
        }

        if (strlen($endTime) === 5) {
            $endTime .= ':00';
        }

        $start = Carbon::createFromFormat('H:i:s', $startTime);
        $end = Carbon::createFromFormat('H:i:s', $endTime);
        $slots = [];

        while ($start->lt($end)) {
            $slots[] = $start->format('H:i');
            $start->addMinutes($stepMinutes);
        }

        return $slots;
    }

    private function appointmentRouteName(): string
    {
        return auth()->user()?->hasRole('secretary') ? 'secretary.dashboard' : 'Admin.Appointment.index';
    }

    private function notifyPatientAppointmentCancelledByStaff(Appointment $appointment): void
    {
        $user = $appointment->registeredPatientUser();

        if (!$user) {
            return;
        }

        $title = 'Appointment Cancelled';
        $body = 'Your appointment scheduled on '
            . $appointment->start_at->format('Y-m-d H:i')
            . ' has been cancelled by the clinic staff.';

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
