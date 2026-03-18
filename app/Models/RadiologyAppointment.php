<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RadiologyAppointment extends Model
{
    protected $fillable = [
        'appointment_id',
        'type_of_medical_image_id',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function type()
    {
        return $this->belongsTo(TypeOfMedicalImage::class, 'type_of_medical_image_id');
    }
}
