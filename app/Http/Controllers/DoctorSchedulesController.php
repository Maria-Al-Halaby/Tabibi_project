<?php

namespace App\Http\Controllers;

use App\Models\ClinicCenterDoctor;
use App\Models\Doctor;
use App\Models\DoctorSchedules;
use Illuminate\Http\Request;

class DoctorSchedulesController extends Controller
{
    public function index()
    {
    }

    public function create(Doctor $doctor)
    {
        return view("Admin.doctor_schedule.create", compact("doctor"));
    }

    public function store(Doctor $doctor, Request $request)
    {
        $schedules = $request->input('schedules', []);
        foreach ($schedules as $index => $schedule) {
            if (!empty($schedule['start_time'])) {
                $parsed = date('H:i', strtotime($schedule['start_time']));
                $schedules[$index]['start_time'] = $parsed ?: $schedule['start_time'];
            }
            if (!empty($schedule['end_time'])) {
                $parsed = date('H:i', strtotime($schedule['end_time']));
                $schedules[$index]['end_time'] = $parsed ?: $schedule['end_time'];
            }
        }
        $request->merge(['schedules' => $schedules]);

        $data = $request->validate([
            'schedules'               => ['required', 'array'],
            'schedules.*.day_of_week' => ['required', 'integer', 'between:0,6'],
            'schedules.*.start_time'  => ['nullable', 'date_format:H:i', 'required_with:schedules.*.end_time'],
            'schedules.*.end_time'    => ['nullable', 'date_format:H:i', 'required_with:schedules.*.start_time'],
            'price'                   => ['required'],
        ]);

        $center = auth()->user()->clinic_center;

        $pivot = ClinicCenterDoctor::firstOrNew([
            'clinic_center_id' => $center->id,
            'doctor_id'        => $doctor->id,
        ]);

        $pivot->price = $data['price'];
        $pivot->save();

        foreach ($data['schedules'] as $schedule) {
            DoctorSchedules::where('clinic_center_doctor_id', $pivot->id)
                ->where('day_of_week', $schedule['day_of_week'])
                ->delete();

            if (empty($schedule['start_time']) || empty($schedule['end_time'])) {
                continue;
            }

            DoctorSchedules::create([
                'clinic_center_doctor_id' => $pivot->id,
                'day_of_week'             => $schedule['day_of_week'],
                'start_time'              => $schedule['start_time'],
                'end_time'                => $schedule['end_time'],
                'doctor_id'               => $doctor->id,
            ]);
        }

        return redirect()
            ->route("Admin.DoctorSchedule.show", compact("doctor"))
            ->with("message", "Doctor schedule added successfully");
    }

    public function show(Doctor $doctor)
    {
        $user   = auth()->user();
        $center = $user->clinic_center;

        $pivot = ClinicCenterDoctor::where("clinic_center_id", $center->id)
            ->where("doctor_id", $doctor->id)
            ->first();

        $schedules = $pivot
            ? $pivot->schedules()->orderBy('day_of_week')->get()
            : collect();

        $price = $pivot?->price ?? '-';

        return view("Admin.doctor_schedule.show", compact("schedules", "doctor", "price"));
    }

    public function edit(Doctor $doctor)
    {
        return view("Admin.doctor_schedule.update", compact("doctor"));
    }

    public function update(Request $request, Doctor $doctor, DoctorSchedules $doctorSchedules)
    {
        // ✅ تحويل الأوقات لصيغة H:i قبل الـ validation
        $schedules = $request->input('schedules', []);
        foreach ($schedules as $index => $schedule) {
            if (!empty($schedule['start_time'])) {
                $parsed = date('H:i', strtotime($schedule['start_time']));
                $schedules[$index]['start_time'] = $parsed ?: $schedule['start_time'];
            }
            if (!empty($schedule['end_time'])) {
                $parsed = date('H:i', strtotime($schedule['end_time']));
                $schedules[$index]['end_time'] = $parsed ?: $schedule['end_time'];
            }
        }
        $request->merge(['schedules' => $schedules]);

        $data = $request->validate([
            'schedules'                  => ['required', 'array'],
            'schedules.*.day_of_week'    => ['required', 'integer', 'between:0,6'],
            'schedules.*.start_time'     => ['nullable', 'date_format:H:i', 'required_with:schedules.*.end_time'],
            'schedules.*.end_time'       => ['nullable', 'date_format:H:i', 'required_with:schedules.*.start_time'],
            'price'                      => ['required'],
        ]);

        $user   = auth()->user();
        $center = $user->clinic_center;

        $pivot = ClinicCenterDoctor::where("clinic_center_id", $center->id)
            ->where("doctor_id", $doctor->id)
            ->first();

        if (!$pivot) {
            return redirect()->back()->withErrors(['error' => 'Schedule not found.']);
        }

        $pivot->update(['price' => $data['price']]);

        foreach ($data['schedules'] as $schedule) {
            DoctorSchedules::where('clinic_center_doctor_id', $pivot->id)
                ->where('day_of_week', $schedule['day_of_week'])
                ->delete();

            if (empty($schedule['start_time']) || empty($schedule['end_time'])) {
                continue;
            }

            DoctorSchedules::create([
                'clinic_center_doctor_id' => $pivot->id,
                'day_of_week'             => $schedule['day_of_week'],
                'start_time'              => $schedule['start_time'],
                'end_time'                => $schedule['end_time'],
                'doctor_id'               => $doctor->id,
            ]);
        }

        return redirect()
            ->route("Admin.DoctorSchedule.show", compact("doctor"))
            ->with("message", "Doctor schedule updated successfully");
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

}    