<?php

namespace App\Http\Controllers;

use App\Models\TypeOfMedicalImage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TypeOfMedicalImageController extends Controller
{
    public function index()
    {
        $typeOfMedicalImages = TypeOfMedicalImage::withCount('clinicCenters')
            ->orderBy('name')
            ->get();

        return view('Super Admin.medical_image_types.index', compact('typeOfMedicalImages'));
    }

    public function create()
    {
        return view('Super Admin.medical_image_types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:type_of_medical_images,name'],
        ]);

        TypeOfMedicalImage::create($data);

        return redirect()
            ->route('SuperAdmin.medicalImageType.index')
            ->with('message', 'Medical image type created successfully.');
    }

    public function edit(TypeOfMedicalImage $typeOfMedicalImage)
    {
        return view('Super Admin.medical_image_types.edit', compact('typeOfMedicalImage'));
    }

    public function update(Request $request, TypeOfMedicalImage $typeOfMedicalImage)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('type_of_medical_images', 'name')->ignore($typeOfMedicalImage->id),
            ],
        ]);

        $typeOfMedicalImage->update($data);

        return redirect()
            ->route('SuperAdmin.medicalImageType.index')
            ->with('message', 'Medical image type updated successfully.');
    }

    public function destroy(TypeOfMedicalImage $typeOfMedicalImage)
    {
        $typeOfMedicalImage->delete();

        return redirect()
            ->route('SuperAdmin.medicalImageType.index')
            ->with('message', 'Medical image type deleted successfully.');
    }
}
