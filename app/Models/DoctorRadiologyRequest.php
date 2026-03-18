<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorRadiologyRequest extends Model
{
    protected $fillable = [
        "appointment_id",
        "type_of_medical_image_id",
        "notes"
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function type()
    {
        return $this->belongsTo(TypeOfMedicalImage::class,'type_of_medical_image_id');
    }
}