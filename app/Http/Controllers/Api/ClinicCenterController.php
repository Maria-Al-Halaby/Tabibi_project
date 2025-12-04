<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicCenter;
use Illuminate\Http\Request;

class ClinicCenterController extends Controller
{
    public function show($center_id)
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
    }
}
