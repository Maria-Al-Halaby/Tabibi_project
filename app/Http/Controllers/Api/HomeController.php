<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ClinicCenter;
use App\Models\Doctor;
use App\Models\Promot;
use App\Models\Specialization;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {
        if(auth()->user()->hasRole("patient"))
        {
            $promot = [];

            $randomPromot = Promot::inRandomOrder()->first(['image', 'information']);

            if ($randomPromot) {
                $promot[] = [
                    'image'         => $randomPromot->image,
                    'information' => $randomPromot->information,
                ];
            }

            $specialties = Specialization::select('id', 'name' , 'image')->get();

            $doctors = Doctor::with(['specialization', 'user'])
                ->withAvg('ratings', 'rating')
                ->withCount('clinic_center')
                ->orderByDesc('ratings_avg_rating')
                ->inRandomOrder()
                ->take(10)
                ->get()
                ->map(function ($doctor) {
                    return [
                        'id'    => $doctor->id,
                        'name'  => $doctor->user->name,
                        'image'   => $doctor->user->profile_image,
                        'rate'  => $doctor->ratings_avg_rating ? round($doctor->ratings_avg_rating, 1) : 0,
                        'is_active'  => $doctor->clinic_center_count > 0 ? 1 : 0,

                        'specialty' => [
                            'id'   => $doctor->specialization?->id,
                            'name' => $doctor->specialization?->name,
                            //'img' => $doctor->specialization?->image

                        ],
                    ];
                });

    /*        $centersQuery = ClinicCenter::inRandomOrder();
            */

        $centersQuery= ClinicCenter::with('user')->inRandomOrder();

        $centers = $centersQuery
            ->take(10)
            ->get(); 



        $dataCenters = $centers->map(function ($center) {
        return [
            'id'      => $center->id,
            'image'     => $center->user->profile_image ?? null, 
            'name'    => $center->user->name,
            'address' => $center->address,
            //'bio'     => $center->bio ?? null   
        ];
        })->values(); 

        return response()->json([
            "message" => "get home screen data" , 
            "status" => true , 
            "data" => [
                'promot'      => $promot,
                'specialties' => $specialties,
                'doctors'     => $doctors,
                'centers'     => $dataCenters,
            ]
        ], 200);
    }
    elseif(auth()->user()->hasRole( "doctor"))
    {
        $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();

        $centers = $doctor->clinic_center()
            ->select('clinic_centers.id', 'clinic_centers.name')
            ->get();

        $appointments = Appointment::with(['patient.user'])
            ->where('doctor_id', $doctor->id)
            ->orderBy('start_at')
            ->get();

        $data = $centers->map(function ($center) use ($appointments) {

            $centerAppointments = $appointments
                ->where('clinic_center_id', $center->id)
                ->values()
                ->map(function ($a) {
                    return [
                        'id'           => $a->id,
                        'patient_name' => $a->patient_display_name,
                        'status'       => $a->status,
                        'date'         => Carbon::parse($a->start_at)->toDateString(),
                        'time'         => Carbon::parse($a->start_at)->format('H:i'),
                    ];
                });

            return [
                'center_name'  => $center->name,
                'appointments' => $centerAppointments,
            ];
        });

        return response()->json([
            "message" => "home screen for doctor" , 
            "status" => true ,
            "data" => [
                'centers' => $data
            ]
        ]);
    }

    }


}
