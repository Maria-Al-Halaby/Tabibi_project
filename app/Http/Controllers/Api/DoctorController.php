<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function get_doctor($doctor_id)
    {
    $doctor = Doctor::with([
            'user',
            'specialization',
            'clinic_center',                           
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
        $schedules = $groupedSchedules->get($center->id, collect());

        $days = $schedules->pluck('day_of_week')->unique()->values()->all();

        $firstSchedule = $schedules->first();

        return [
            'id'        => $center->id,
            'name'      => $center->name,
            'price'     => $center->pivot->price ?? null,         
            'days'      => $days,                                 
            'time_from' => optional($firstSchedule)->start_time,  
            'time_to'   => optional($firstSchedule)->end_time,    
        ];
    });

    $data = [
        'id'               => $doctor->id,
        'img'              => $doctor->user->profile_image,
        'name'             => trim($doctor->user->name . ' ' . $doctor->user->last_name),
        'rate'             => $doctor->ratings_avg_rating
                                ? round($doctor->ratings_avg_rating, 1)
                                : 0,
        'experience_years' => $doctor->experience_years ?? null,
        'specialty'        => [
            'id'   => $doctor->specialization?->id,
            'name' => $doctor->specialization?->name,
        ],
        'bio'              => $doctor->bio ?? null,
        'centers'          => $centers,
    ];

    return response()->json([
        "message" => "doctor information" , 
        "status" => true ,
        "data" => $data
    ], 200);
    }


}