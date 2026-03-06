<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
