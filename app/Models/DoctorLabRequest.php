<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorLabRequest extends Model
{
    protected $fillable = [
        "appointment_id",
        "notes"
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function tests()
    {
        return $this->belongsToMany(LabTest::class,
            'doctor_lab_request_tests',
            'doctor_lab_request_id',
            'lab_test_id'
        );
    }
}
