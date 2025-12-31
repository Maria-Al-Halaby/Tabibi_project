<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicCenter;
use Illuminate\Http\Request;

class ClinicCenterController extends Controller
{
/*     public function show($center_id)
    {
        $center = ClinicCenter::with('specialties' , "user")->findOrFail($center_id);

        $data = [
            'id'      => $center->id,
            'img'     => $center->user->profile_image,   
            'address' => $center->address,
            'bio'     => $center->bio,  

            'specialties' => $center->specialties->map(function ($sp) {
                return [
                    'id'   => $sp->id,
                    'name' => $sp->name,
                    "img" => $sp->image
                ];
            }),
        ];

        return response()->json([
            "message" => "get clinic center information" , 
            "status" => true , 
            "data" => [
                'center' => $data,
            ]
        ], 200);
    } */


/*         public function show($center_id)
{
    // نجلب المركز مع المستخدم والاختصاصات المرتبطة به
    $center = ClinicCenter::with(['specialties', 'user'])->findOrFail($center_id);

    $data = [
        'id'      => $center->id,
        'img'     => $center->user->profile_image,
        'address' => $center->address,
        'bio'     => $center->bio,

        // كل "عيادة" هي اختصاص موجود في هذا المركز
        'clinics' => $center->specialties->map(function ($sp) {
            return [
                'id'   => $sp->id,
                'name' => $sp->name,
                'img'  => $sp->image,
            ];
        })->values(),
    ];

    return response()->json([
        "message" => "get clinic center information",
        "status"  => true,
        "data"    => [
            'center' => $data,
        ]
    ], 200);
}
 */


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

}
