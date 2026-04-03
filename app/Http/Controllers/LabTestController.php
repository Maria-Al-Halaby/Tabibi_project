<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LabTestController extends Controller
{
    public function index()
    {
        $labTests = LabTest::withCount(['clinicCenters', 'appointments'])
            ->orderBy('name')
            ->get();

        return view('Super Admin.lab_tests.index', compact('labTests'));
    }

    public function create()
    {
        return view('Super Admin.lab_tests.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:lab_tests,name'],
        ]);

        LabTest::create($data);

        return redirect()
            ->route('SuperAdmin.labTest.index')
            ->with('message', 'Lab test created successfully.');
    }

    public function edit(LabTest $labTest)
    {
        return view('Super Admin.lab_tests.edit', compact('labTest'));
    }

    public function update(Request $request, LabTest $labTest)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('lab_tests', 'name')->ignore($labTest->id)],
        ]);

        $labTest->update($data);

        return redirect()
            ->route('SuperAdmin.labTest.index')
            ->with('message', 'Lab test updated successfully.');
    }

    public function destroy(LabTest $labTest)
    {
        $labTest->delete();

        return redirect()
            ->route('SuperAdmin.labTest.index')
            ->with('message', 'Lab test deleted successfully.');
    }
}
