<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicCenter;
use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Http\Request;

class GetAllController extends Controller
{
    public function get_all_specialties()  
    {
        $specialties = Specialization::select('id', 'name', 'image as img')->get();

        return response()->json([
                "specialties" => $specialties 
        ], 200);
    }

    public function get_all_doctor($specialization_id=0 , $center_id =0) 
    {
    /*  $specialtyId = $request->query('specialty_id');  
    $centerId    = $request->query('center_id');    */ 

    $query = Doctor::with(['user', 'specialization', 'clinic_centers'])
    ->withAvg('ratings', 'rating')   
    ->withCount('ratings');

    if ($specialization_id !=0) {
        $query->where('specialization_id', $specialization_id);
    }

    if($center_id != 0) {
        $query->whereHas('clinic_centers', function ($q) use ($center_id) {
            $q->where('clinic_centers.id', $center_id);
        });
    }

    $doctors = $query->get();

    $data = $doctors->map(function ($doctor) {
        return [
            'id'    => $doctor->id,
            'first_name' => $doctor->user->name,
            'last_name'  => $doctor->user->last_name,
            'phone'      => $doctor->user->phone,
            'email'      => $doctor->user->email,
            'img'        => $doctor->user->profile_image, 
            'rate'       =>  $doctor->ratings_avg_rating ? round($doctor->ratings_avg_rating, 1) : 0 ,        
            'specialty'  => [
                'id'   => $doctor->specialization?->id,
                'name' => $doctor->specialization?->name,
            ],
            'center'     => [
                'id'      => $doctor->center?->id,
                'name'    => $doctor->center?->name,
                'address' => $doctor->center?->address,
            ],
        ];
    });

    return response()->json([
        'doctors' => $data,
    ], 200);

    }

    

    

    public function get_all_centers()
    {
        $query = ClinicCenter::with(['user']);


        $clinic_centers = $query->get();
    
        return response()->json([
                "center" => $clinic_centers 
        ], 200);
    }


}