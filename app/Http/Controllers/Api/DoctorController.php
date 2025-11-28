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
            'clinic_centers',
            'schedules' 
        ])
        ->withAvg('ratings', 'rating')
        ->withCount('ratings')
        ->findOrFail($doctor_id);

    $data = [
        'id'         => $doctor->id,
        'first_name' => $doctor->user->name,
        'last_name'  => $doctor->user->last_name,
        'phone'      => $doctor->user->phone,
        'email'      => $doctor->user->email,
        'img'        => $doctor->user->profile_image,
        'rate'       => $doctor->ratings_avg_rating
                            ? round($doctor->ratings_avg_rating, 1)
                            : 0,
        'rate_count' => $doctor->ratings_count,

        'specialty' => [
            'id'   => $doctor->specialization?->id,
            'name' => $doctor->specialization?->name,
        ],

        'centers' => $doctor->centers->map(function ($center) {
            return [
                'id'      => $center->id,
                'name'    => $center->name,
                'address' => $center->address,
            ];
        }),

        'working_times' => $doctor->schedules->map(function ($schedule) {
            return [
                'day'        => $schedule->day_of_week,       
                'start_time' => $schedule->start_time, 
                'end_time'   => $schedule->end_time,   
                'center_id'  => $schedule->clinic_center_doctor_id,  
            ];
        }),
    ];

    return response()->json([
        'doctor' => $data
    ], 200);
}
}