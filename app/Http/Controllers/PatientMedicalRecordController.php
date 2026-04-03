<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientMedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientMedicalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'type' => 'required|in:lab,radiology',
        'title' => 'required|string|max:255',
        'record_date' => 'required|date',
        'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
    ]);

    $user = Auth::user();
    $patient = Patient::where('user_id', $user->id)->first();

    if (!$patient) {
        return response()->json([
            'message' => 'Patient not found.',
            'status' => false,
        ], 404);
    }

    $path = $request->file('file')->store('patient_medical_records', 'public');

    $record = PatientMedicalRecord::create([
        'patient_id' => $patient->id,
        'type' => $request->type,
        'title' => $request->title,
        'record_date' => $request->record_date,
        'file_path' => $path,
    ]);

    return response()->json([
        'message' => 'Medical record uploaded successfully.',
        'status' => true,
        'data' => [
            'id' => $record->id,
            'type' => $record->type,
            'title' => $record->title,
            'record_date' => $record->record_date,
            'file_url' => asset('storage/' . $record->file_path) ,
            'created_at' => $record->created_at,
        ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PatientMedicalRecord $patientMedicalRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PatientMedicalRecord $patientMedicalRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PatientMedicalRecord $patientMedicalRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatientMedicalRecord $patientMedicalRecord)
    {
        //
    }

    public function getMedicalRecords(Request $request)
    {
        $request->validate([
        'type' => 'nullable|in:lab,radiology',
        ]);

    $user = Auth::user();
    $patient = Patient::where('user_id', $user->id)->first();

    if (!$patient) {
        return response()->json([
            'message' => 'Patient not found.',
            'status' => false,
        ], 404);
    }

    $query = PatientMedicalRecord::where('patient_id', $patient->id);

    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    $records = $query->latest()->get()->map(function ($record) {
        return [
            'id' => $record->id,
            'type' => $record->type,
            'title' => $record->title,
            'record_date' => $record->record_date,
            'file_url' => asset('storage/' . $record->file_path),
            'created_at' => $record->created_at,
        ];
    });

    return response()->json([
        'message' => 'Medical records fetched successfully.',
        'status' => true,
        'data' => $records,
    ], 200);
    }
}
