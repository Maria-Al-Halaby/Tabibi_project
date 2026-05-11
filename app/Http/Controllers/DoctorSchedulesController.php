<?php

namespace App\Http\Controllers;

use App\Models\ClinicCenterDoctor;
use App\Models\Doctor;
use App\Models\DoctorSchedules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DoctorSchedulesController extends Controller
{
    public function index() {}

    public function create(Doctor $doctor)
    {
        $center = auth()->user()->clinic_center;

        $pivot = ClinicCenterDoctor::where('clinic_center_id', $center->id)
            ->where('doctor_id', $doctor->id)
            ->first();

        $currentSchedules = $pivot
            ? $pivot->schedules()->orderBy('day_of_week')->orderBy('start_time')->get()
            : collect();

        $currentPrice = $pivot?->price ?? '';
        $currentAppointmentDuration = $pivot?->appointment_duration_minutes ?? 30;

        return view('Admin.doctor_schedule.create', compact('doctor', 'currentSchedules', 'currentPrice', 'currentAppointmentDuration'));
    }

    public function store(Doctor $doctor, Request $request)
    {
        $request->merge(['schedules' => $this->normalizeScheduleInput($request->input('schedules', []))]);

        $data = $request->validate([
            'schedules' => ['required', 'array', 'min:1'],
            'schedules.*.day_of_week' => ['required', 'integer', 'between:0,6', 'distinct'],
            'schedules.*.start_time' => ['required', 'date_format:H:i'],
            'schedules.*.end_time' => ['required', 'date_format:H:i'],
            'price' => ['required', 'numeric', 'min:0'],
            'appointment_duration_minutes' => ['required', 'integer', 'min:5', 'max:240'],
        ]);

        $center = auth()->user()->clinic_center;
        $this->assertScheduleTimes($data['schedules']);

        DB::transaction(function () use ($center, $doctor, $data) {
            $pivot = ClinicCenterDoctor::firstOrNew([
                'clinic_center_id' => $center->id,
                'doctor_id' => $doctor->id,
            ]);

            $pivot->price = $data['price'];
            $pivot->appointment_duration_minutes = $data['appointment_duration_minutes'];
            $pivot->save();

            $this->syncSchedules($doctor, $pivot, $data['schedules']);
        });

        return redirect()
            ->route('Admin.DoctorSchedule.show', compact('doctor'))
            ->with('message', 'Doctor schedule added successfully');
    }

    public function show(Doctor $doctor)
    {
        $user = auth()->user();
        $center = $user->clinic_center;

        $pivot = ClinicCenterDoctor::where('clinic_center_id', $center->id)
            ->where('doctor_id', $doctor->id)
            ->first();

        $schedules = $pivot
            ? $pivot->schedules()->orderBy('day_of_week')->get()
            : collect();

        $price = $pivot?->price ?? '-';
        $appointmentDuration = $pivot?->appointment_duration_minutes ?? 30;

        return view('Admin.doctor_schedule.show', compact('schedules', 'doctor', 'price', 'appointmentDuration'));
    }

    public function edit(Doctor $doctor)
    {
        $user = auth()->user();
        $center = $user->clinic_center;

        $pivot = ClinicCenterDoctor::where('clinic_center_id', $center->id)
            ->where('doctor_id', $doctor->id)
            ->first();

        $currentSchedules = $pivot
            ? $pivot->schedules()->orderBy('day_of_week')->orderBy('start_time')->get()
            : collect();

        $currentPrice = $pivot?->price ?? '';
        $currentAppointmentDuration = $pivot?->appointment_duration_minutes ?? 30;

        return view('Admin.doctor_schedule.update', compact('doctor', 'currentSchedules', 'currentPrice', 'currentAppointmentDuration'));
    }

    public function update(Request $request, Doctor $doctor, DoctorSchedules $doctorSchedules)
    {
        $request->merge(['schedules' => $this->normalizeScheduleInput($request->input('schedules', []))]);

        $data = $request->validate([
            'schedules' => ['required', 'array', 'min:1'],
            'schedules.*.day_of_week' => ['required', 'integer', 'between:0,6', 'distinct'],
            'schedules.*.start_time' => ['required', 'date_format:H:i'],
            'schedules.*.end_time' => ['required', 'date_format:H:i'],
            'price' => ['required', 'numeric', 'min:0'],
            'appointment_duration_minutes' => ['required', 'integer', 'min:5', 'max:240'],
        ]);

        $user = auth()->user();
        $center = $user->clinic_center;

        $pivot = ClinicCenterDoctor::where('clinic_center_id', $center->id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (! $pivot) {
            return redirect()->back()->withErrors(['error' => 'Schedule not found.']);
        }

        $this->assertScheduleTimes($data['schedules']);

        DB::transaction(function () use ($doctor, $pivot, $data) {
            $pivot->update([
                'price' => $data['price'],
                'appointment_duration_minutes' => $data['appointment_duration_minutes'],
            ]);

            $this->syncSchedules($doctor, $pivot, $data['schedules']);
        });

        return redirect()
            ->route('Admin.DoctorSchedule.show', compact('doctor'))
            ->with('message', 'Doctor schedule updated successfully');
    }

    public function destroy(Doctor $doctor)
    {
        $center = auth()->user()->clinic_center;

        $pivot = ClinicCenterDoctor::where('clinic_center_id', $center->id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (! $pivot) {
            return back()->with('message', 'This doctor is not linked to your center.');
        }

        DoctorSchedules::where('clinic_center_doctor_id', $pivot->id)->delete();

        $pivot->delete();

        return redirect()
            ->route('Admin.ClinicManagement.index')
            ->with('message', 'Doctor removed from your center successfully.');
    }

    private function normalizeScheduleInput(array $schedules): array
    {
        $normalized = [];

        foreach ($schedules as $schedule) {
            if (! is_array($schedule) || ! array_key_exists('day_of_week', $schedule)) {
                continue;
            }

            $startTime = $this->normalizeTime($schedule['start_time'] ?? null);
            $endTime = $this->normalizeTime($schedule['end_time'] ?? null);

            if (! $startTime && ! $endTime) {
                continue;
            }

            $normalized[(int) $schedule['day_of_week']] = [
                'day_of_week' => $schedule['day_of_week'],
                'start_time' => $startTime,
                'end_time' => $endTime,
            ];
        }

        return $normalized;
    }

    private function normalizeTime(?string $time): ?string
    {
        if (! $time) {
            return null;
        }

        $timestamp = strtotime($time);

        return $timestamp ? date('H:i', $timestamp) : $time;
    }

    private function assertScheduleTimes(array $schedules): void
    {
        foreach ($schedules as $index => $schedule) {
            if ($schedule['end_time'] <= $schedule['start_time']) {
                throw ValidationException::withMessages([
                    "schedules.$index.end_time" => 'End time must be after start time.',
                ]);
            }
        }
    }

    private function syncSchedules(Doctor $doctor, ClinicCenterDoctor $pivot, array $schedules): void
    {
        DoctorSchedules::where('clinic_center_doctor_id', $pivot->id)->delete();

        foreach ($schedules as $schedule) {
            DoctorSchedules::create([
                'clinic_center_doctor_id' => $pivot->id,
                'day_of_week' => $schedule['day_of_week'],
                'start_time' => $schedule['start_time'],
                'end_time' => $schedule['end_time'],
                'doctor_id' => $doctor->id,
            ]);
        }
    }
}
