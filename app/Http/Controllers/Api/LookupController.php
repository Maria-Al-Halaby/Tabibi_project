<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use App\Models\TypeOfMedicalImage;
use Illuminate\Http\Request;

class LookupController extends Controller
{
    public function labTests()
    {
        $tests = LabTest::select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $tests
        ]);
    }

    public function medicalImageTypes()
    {
        $types = TypeOfMedicalImage::select('id', 'name')
            ->orderBy('name')
            ->get();

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