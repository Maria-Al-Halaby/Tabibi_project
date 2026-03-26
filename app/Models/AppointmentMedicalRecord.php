<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentMedicalRecord extends Model
{
    protected $fillable = [
        'appointment_id',
        'record_source',
        'record_id',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    
}
