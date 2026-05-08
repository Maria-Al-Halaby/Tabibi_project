<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicCenter;
use Illuminate\Http\Request;

class ClinicCenterController extends Controller
{
 public function show($center_id)
    {
        $center = ClinicCenter::with([
                'user',
                'doctors.specialization',
            ])->findOrFail($center_id);

        $clinics = $center->doctors
            ->pluck('specialization')    
            ->filter()                    
            ->unique('id')                
            ->values()
            ->map(function ($sp) {
                return [
                    'id'   => $sp->id,
                    'name' => $sp->name,
                    'image'  => $sp->image ?? null,
                ];
            });

        $data = [
            'id'      => $center->id,
            'name' => $center->user->name ,
            'image'     => $center->user->profile_image,
            'address' => $center->address,
            'bio'     => $center->bio,

            'clinics' => $clinics,
        ];

        return response()->json([
            "message" => "get clinic center information",
            "status"  => true,
            "data"    => [
                'center' => $data,
            ]
        ], 200);
    }

    public function doctors($center_id)
    {
        $center = ClinicCenter::with(['doctors.user', 'doctors.specialization'])
            ->findOrFail($center_id);

        $doctors = $center->doctors()
        ->with(['user', 'specialization'])
        ->get()
        ->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->user?->name,
                'specialization' => $doctor->specialization?->name,
            ];
        });

        return response()->json([
            "message" => "get doctors by center",
            "status" => true,
            "data" => [
                "doctors" => $doctors
            ]
        ]);
    }


}
