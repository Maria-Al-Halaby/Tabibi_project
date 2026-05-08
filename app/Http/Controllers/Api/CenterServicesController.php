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

        $labTests = $center->labTests->map(function ($test) use ($center) {
            $price = is_null($test->pivot->price) ? null : (float) $test->pivot->price;

            return [
                'id' => $test->id,
                'name' => $test->name,
                'price' => $price,
                'selected_center_price' => $price,
                'center_id' => $center->id,
            ];
        })->values();
      
        $medicalImages = $center->medicalImages->map(function ($image) use ($center) {
            $price = is_null($image->pivot->price) ? null : (float) $image->pivot->price;

            return [
                'id' => $image->id,
                'name' => $image->name,
                'price' => $price,
                'selected_center_price' => $price,
                'center_id' => $center->id,
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
