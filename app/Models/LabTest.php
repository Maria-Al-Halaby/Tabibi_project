<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LabTest;
use App\Models\TypeOfMedicalImage;

class LabTest extends Model
{
    protected $fillable = [
        "name"
    ];

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class,
            'appointment_lab_tests',
            'lab_test_id',
            'appointment_id'
        );
    }

    public function doctorLabRequests()
    {
        return $this->belongsToMany(DoctorLabRequest::class,
            'doctor_lab_request_tests',
            'lab_test_id',
            'doctor_lab_request_id'
        );
    }

    public function clinicCenters()
    {
        return $this->belongsToMany(
            ClinicCenter::class,
            'clinic_center_lab_tests',
            'lab_test_id',
            'clinic_center_id'
        )->withPivot('price')->withTimestamps();
    }
}
