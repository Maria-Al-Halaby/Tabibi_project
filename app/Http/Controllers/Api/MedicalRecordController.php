<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LabResult;
use App\Models\RadiologyResult;
use App\Models\PatientMedicalRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $patient = $request->user()->patient;

        if (!$patient) {
            return response()->json([
                'status' => false,
                'message' => 'Patient not found'
            ], 404);
        }

        $patientUploads = PatientMedicalRecord::where('patient_id', $patient->id)
            ->get()
            ->map(function ($record) {
                return [
                    'record_source' => 'patient_medical_record',
                    'record_id' => $record->id,
                    'type' => $record->type,
                    'title' => $record->title,
                    'record_date' => $record->record_date,
                    'file_path' => $record->file_path,
                    'file_url' => url(Storage::url($record->file_path)),
                    'source_label' => 'Patient Upload',
                ];
            });

        $labResults = LabResult::whereHas('appointment', function ($query) use ($patient) {
                $query->where('patient_id', $patient->id);
            })
            ->get()
            ->map(function ($record) {
                return [
                    'record_source' => 'lab_result',
                    'record_id' => $record->id,
                    'type' => 'lab',
                    'title' => 'Lab Result #' . $record->id,
                    'record_date' => optional($record->created_at)->toDateString(),
                    'file_path' => $record->result_file,
                    'file_url' => url(Storage::url($record->result_file)),
                    'source_label' => 'Appointment Result',
                ];
            });

        $radiologyResults = RadiologyResult::whereHas('appointment', function ($query) use ($patient) {
                $query->where('patient_id', $patient->id);
            })
            ->get()
            ->map(function ($record) {
                return [
                    'record_source' => 'radiology_result',
                    'record_id' => $record->id,
                    'type' => 'radiology',
                    'title' => 'Radiology Result #' . $record->id,
                    'record_date' => optional($record->created_at)->toDateString(),
                    'file_path' => $record->image_path,
                    'file_url' => url(Storage::url($record->image_path)),
                    'source_label' => 'Appointment Result',
                ];
            });

        $records = $patientUploads
            ->concat($labResults)
            ->concat($radiologyResults)
            ->sortByDesc('record_date')
            ->values();

        return response()->json([
            'status' => true,
            'data' => [
                'records' => $records
            ]
        ]);
    }
}
