<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicCenter;
use Illuminate\Http\Request;

class CenterServicesController extends Controller
{
     public function index($centerId)
    {
        $center = ClinicCenter::with([
            'labTests',
            'medicalImages'
        ])->findOrFail($centerId);

        $labTests = $center->labTests->map(function ($test) {
            return [
                'id' => $test->id,
                'name' => $test->name,
                'price' => $test->pivot->price,
            ];
        })->values();
      
        $medicalImages = $center->medicalImages->map(function ($image) {
            return [
                'id' => $image->id,
                'name' => $image->name,
                'price' => $image->pivot->price,
            ];
        })->values();

        return response()->json([
            'message' => 'Center services fetched successfully',
            'status' => true,
            'data' => [
                'center_id' => $center->id,
                'lab_tests' => $labTests,
                'medical_images' => $medicalImages,
            ]
        ]);
    }
}
