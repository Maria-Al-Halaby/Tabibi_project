<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'appointment_id', 
        'medicine', 
        'dose', 
        'duration'
        ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
