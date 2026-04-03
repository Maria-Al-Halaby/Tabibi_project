<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicCenter;
use App\Models\LabTest;
use App\Models\TypeOfMedicalImage;
use Illuminate\Http\Request;

class LookupController extends Controller
{
    public function labTests(Request $request)
    {
        $centerId = $request->query('center_id');

        if ($centerId) {
            ClinicCenter::findOrFail($centerId);

            $tests = LabTest::query()
                ->join('clinic_center_lab_tests', 'lab_tests.id', '=', 'clinic_center_lab_tests.lab_test_id')
                ->where('clinic_center_lab_tests.clinic_center_id', $centerId)
                ->orderBy('lab_tests.name')
                ->get([
                    'lab_tests.id',
                    'lab_tests.name',
                    'clinic_center_lab_tests.price as price',
                ])
                ->map(function ($test) use ($centerId) {
                    return [
                        'id' => $test->id,
                        'name' => $test->name,
                        'price' => is_null($test->price) ? null : (float) $test->price,
                        'selected_center_price' => is_null($test->price) ? null : (float) $test->price,
                        'center_id' => (int) $centerId,
                    ];
                })
                ->values();
        } else {
            $tests = LabTest::select('id', 'name')
                ->orderBy('name')
                ->get()
                ->map(function ($test) {
                    return [
                        'id' => $test->id,
                        'name' => $test->name,
                        'price' => null,
                        'selected_center_price' => null,
                        'center_id' => null,
                    ];
                })
                ->values();
        }

        return response()->json([
            'status' => true,
            'data' => $tests
        ]);
    }

    public function medicalImageTypes(Request $request)
    {
        $centerId = $request->query('center_id');

        if ($centerId) {
            ClinicCenter::findOrFail($centerId);

            $types = TypeOfMedicalImage::query()
                ->join('clinic_center_medical_images', 'type_of_medical_images.id', '=', 'clinic_center_medical_images.type_of_medical_image_id')
                ->where('clinic_center_medical_images.clinic_center_id', $centerId)
                ->orderBy('type_of_medical_images.name')
                ->get([
                    'type_of_medical_images.id',
                    'type_of_medical_images.name',
                    'clinic_center_medical_images.price as price',
                ])
                ->map(function ($type) use ($centerId) {
                    return [
                        'id' => $type->id,
                        'name' => $type->name,
                        'price' => is_null($type->price) ? null : (float) $type->price,
                        'selected_center_price' => is_null($type->price) ? null : (float) $type->price,
                        'center_id' => (int) $centerId,
                    ];
                })
                ->values();
        } else {
            $types = TypeOfMedicalImage::select('id', 'name')
                ->orderBy('name')
                ->get()
                ->map(function ($type) {
                    return [
                        'id' => $type->id,
                        'name' => $type->name,
                        'price' => null,
                        'selected_center_price' => null,
                        'center_id' => null,
                    ];
                })
                ->values();
        }

        return response()->json([
            'status' => true,
            'data' => $types
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $user->id,
                'name' => trim(($user->name ?? '') . ' ' . ($user->last_name ?? '')),
                'email' => $user->email,
                'role' => $user->getRoleNames()->first(),
            ]
        ]);
    }
}
