<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LabTest;
use App\Models\TypeOfMedicalImage;

class ClinicCenter extends Model
{
    protected $fillable = [
        "user_id", 
        "name", 
        "address" , 
        "is_active"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctors()
    {
            return $this->belongsToMany(
            Doctor::class,
            'clinic_center_doctor',  
            'clinic_center_id',     
            'doctor_id'            
        );

    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function specialties()
    {
    return $this->belongsToMany(Specialization::class, 'center_specialization');
    }

    public function labTests()
    {
        return $this->belongsToMany(
            LabTest::class,
            'clinic_center_lab_tests',
            'clinic_center_id',
            'lab_test_id'
        )->withPivot('price')->withTimestamps();
    }

    public function medicalImages()
    {
        return $this->belongsToMany(
            TypeOfMedicalImage::class,
            'clinic_center_medical_images',
            'clinic_center_id',
            'type_of_medical_image_id'
        )->withPivot('price')->withTimestamps();
    }
}
