<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorRating extends Model
{
    protected $table = 'doctor_ratings';

    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'patient_id',
        'rating',
        'comment',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
