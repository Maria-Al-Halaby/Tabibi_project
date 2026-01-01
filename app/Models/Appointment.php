<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        "patient_id",
        "doctor_id",
        "clinic_center_id", 
        "start_at" , 
        "status" , 
        "result_ratio" , 
        "expected_disease" , 
        "emergency" , 
        "doctor_note" , 
        "note"
    ];

    protected $casts = [
        "start_at" => "datetime" , 
        "end_at" => "datetime"
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor() {
        return $this->belongsTo(Doctor::class);
    }

    public function clinic_center()
    {
        return $this->belongsTo(ClinicCenter::class);
    }

    public function answers()  
    {
        return $this->hasMany(Answer::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class , 'appointment_id');
    }

    public function rating()
    {
        return $this->hasOne(DoctorRating::class, 'appointment_id');
    }

}
