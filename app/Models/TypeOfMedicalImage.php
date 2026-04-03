<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LabTest;
use App\Models\TypeOfMedicalImage;

class TypeOfMedicalImage extends Model
{
    protected $fillable = [
        "name"
    ];

    public function clinicCenters()
    {
        return $this->belongsToMany(
            ClinicCenter::class,
            'clinic_center_medical_images',
            'type_of_medical_image_id',
            'clinic_center_id'
        )->withPivot('price')->withTimestamps();
    }
}
