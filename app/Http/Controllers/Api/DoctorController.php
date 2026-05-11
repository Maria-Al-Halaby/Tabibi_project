<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Carbon\Carbon;

class DoctorController extends Controller
{
    public function get_doctor($doctor_id)
    {
        $doctor = Doctor::with([
            'user',
            'specialization',
            'clinic_center.user',
            'schedules.clinicCenterDoctor.clinic_center',
        ])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->findOrFail($doctor_id);

        $groupedSchedules = $doctor->schedules
            ->filter(function ($schedule) {
                return $schedule->clinicCenterDoctor && $schedule->clinicCenterDoctor->clinic_center_id;
            })
            ->groupBy(function ($schedule) {
                return $schedule->clinicCenterDoctor->clinic_center_id;
            });

        $centers = $doctor->clinic_center->map(function ($center) use ($groupedSchedules) {
            $schedules = $groupedSchedules
                ->get($center->id, collect())
                ->sortBy([
                    ['day_of_week', 'asc'],
                    ['start_time', 'asc'],
                ])
                ->values();

            $days = $schedules
                ->map(fn ($schedule) => [
                    'day_of_week' => $schedule->day_of_week,
                    'time_from' => $this->formatScheduleTime($schedule->start_time),
                    'time_to' => $this->formatScheduleTime($schedule->end_time),
                    'appointment_duration_minutes' => $center->pivot->appointment_duration_minutes ?? 30,
                ])
                ->values();

            return [
                'id' => $center->id,
                'name' => $center->name,
                'image' => $center->user?->profile_image,
                'price' => $center->pivot->price ?? null,
                'appointment_duration_minutes' => $center->pivot->appointment_duration_minutes ?? 30,
                'days' => $days,
                'day_numbers' => $days->pluck('day_of_week')->unique()->values(),
            ];
        });

        $data = [
            'id' => $doctor->id,
            'image' => $doctor->user->profile_image,
            'name' => trim($doctor->user->name.' '.$doctor->user->last_name),
            'rate' => $doctor->ratings_avg_rating
                                    ? round($doctor->ratings_avg_rating, 1)
                                    : 0,
            'experience_years' => $doctor->experience_years ?? null,
            'is_active' => $doctor->clinic_center->isNotEmpty() ? 1 : 0,
            'doctor_type' => $doctor->doctor_type,
            'specialty' => [
                'id' => $doctor->specialization?->id,
                'name' => $doctor->specialization?->name,
            ],
            'bio' => $doctor->bio ?? null,
            'centers' => $centers,
        ];

        return response()->json([
            'message' => 'doctor information',
            'status' => true,
            'data' => $data,
        ], 200);
    }

    private function formatScheduleTime(?string $time): ?string
    {
        if (! $time) {
            return null;
        }

        if (strlen($time) === 5) {
            $time .= ':00';
        }

        return Carbon::createFromFormat('H:i:s', $time)->format('H:i');
    }
}
