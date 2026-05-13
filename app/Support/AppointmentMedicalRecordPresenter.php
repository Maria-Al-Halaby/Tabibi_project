<?php

namespace App\Support;

use App\Models\Appointment;
use App\Models\LabResult;
use App\Models\PatientMedicalRecord;
use App\Models\RadiologyResult;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class AppointmentMedicalRecordPresenter
{
    public static function forAppointment(Appointment $appointment): Collection
    {
        return $appointment->attachedMedicalRecords
            ->map(fn ($record) => self::serialize($record, $appointment->patient_id))
            ->filter()
            ->values();
    }

    private static function serialize($record, ?int $patientId): ?array
    {
        if (! $patientId) {
            return null;
        }

        return match ($record->record_source) {
            'patient_medical_record' => self::patientRecord($record->record_id, $patientId),
            'lab_result' => self::labResult($record->record_id, $patientId),
            'radiology_result' => self::radiologyResult($record->record_id, $patientId),
            default => null,
        };
    }

    private static function patientRecord(int $recordId, int $patientId): ?array
    {
        $record = PatientMedicalRecord::where('id', $recordId)
            ->where('patient_id', $patientId)
            ->first();

        if (! $record) {
            return null;
        }

        return [
            'source_label' => 'Patient Upload',
            'type' => $record->type,
            'title' => $record->title,
            'record_date' => $record->record_date,
            'file_url' => url(Storage::url($record->file_path)),
        ];
    }

    private static function labResult(int $recordId, int $patientId): ?array
    {
        $record = LabResult::where('id', $recordId)
            ->whereHas('appointment', fn ($query) => $query->where('patient_id', $patientId))
            ->first();

        if (! $record) {
            return null;
        }

        return [
            'source_label' => 'Appointment Result',
            'type' => 'lab',
            'title' => 'Lab Result #'.$record->id,
            'record_date' => optional($record->created_at)->toDateString(),
            'file_url' => url(Storage::url($record->result_file)),
        ];
    }

    private static function radiologyResult(int $recordId, int $patientId): ?array
    {
        $record = RadiologyResult::where('id', $recordId)
            ->whereHas('appointment', fn ($query) => $query->where('patient_id', $patientId))
            ->first();

        if (! $record) {
            return null;
        }

        return [
            'source_label' => 'Appointment Result',
            'type' => 'radiology',
            'title' => 'Radiology Result #'.$record->id,
            'record_date' => optional($record->created_at)->toDateString(),
            'file_url' => url(Storage::url($record->image_path)),
        ];
    }
}
