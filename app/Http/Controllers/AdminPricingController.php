<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClinicCenter;
use App\Models\LabTest;
use App\Models\TypeOfMedicalImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminPricingController extends Controller
{
    
    public function index()
    {
        $center = Auth::user()->clinic_center;

        $labTests = LabTest::all()->map(function ($test) use ($center) {
            $pivot = DB::table('clinic_center_lab_tests')
                ->where('clinic_center_id', $center->id)
                ->where('lab_test_id', $test->id)
                ->first();

            return [
                'id' => $test->id,
                'name' => $test->name,
                'price' => $pivot->price ?? null,
            ];
        });

        $images = TypeOfMedicalImage::all()->map(function ($img) use ($center) {
            $pivot = DB::table('clinic_center_medical_images')
                ->where('clinic_center_id', $center->id)
                ->where('type_of_medical_image_id', $img->id)
                ->first();

            return [
                'id' => $img->id,
                'name' => $img->name,
                'price' => $pivot->price ?? null,
            ];
        });

        return view('Admin.pricing', compact('labTests', 'images'));
    }


    public function updateLabPrice(Request $request)
    {
        $data = $request->validate([
            'lab_test_id' => 'required|exists:lab_tests,id',
            'price' => 'required|numeric|min:0'
        ]);

        $center = Auth::user()->clinic_center;

        DB::table('clinic_center_lab_tests')->updateOrInsert(
            [
                'clinic_center_id' => $center->id,
                'lab_test_id' => $data['lab_test_id']
            ],
            [
                'price' => $data['price']
            ]
        );

        return back()->with('success', 'Lab price updated');
    }


    public function updateRadiologyPrice(Request $request)
    {
        $data = $request->validate([
            'type_of_medical_image_id' => 'required|exists:type_of_medical_images,id',
            'price' => 'required|numeric|min:0'
        ]);

        $center = Auth::user()->clinic_center;

        DB::table('clinic_center_medical_images')->updateOrInsert(
            [
                'clinic_center_id' => $center->id,
                'type_of_medical_image_id' => $data['type_of_medical_image_id']
            ],
            [
                'price' => $data['price']
            ]
        );

        return back()->with('success', 'Radiology price updated');
    }
}
