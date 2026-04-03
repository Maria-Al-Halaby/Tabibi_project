<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        "patient_id",
        "doctor_id",
        "clinic_center_id", 
        "type",
        "start_at" , 
        "status" , 
        "result_ratio" , 
        "expected_disease" , 
        "emergency" , 
        "doctor_note" , 
        "note",
        'price',
        "attached_radiology_result_id",
        "attached_lab_result_id"
    ];

    protected $casts = [
        "start_at" => "datetime" , 
        "end_at" => "datetime" ,
        'price' => "float"
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
        return $this->hasOne(Prescription::class , 'appointment_id');
    }

    public function radiologyResult()
    {
        return $this->hasOne(RadiologyResult::class);
    }

    public function labResult()
    {
        return $this->hasOne(LabResult::class);
    }

    public function radiologyRequests()
    {
        return $this->hasMany(DoctorRadiologyRequest::class);
    }

    public function labRequests()
    {
        return $this->hasMany(DoctorLabRequest::class);
    }

    public function radiologyAppointment()
    {
        return $this->hasOne(RadiologyAppointment::class);
    }

    public function labTests()
    {
        return $this->belongsToMany(
            LabTest::class,
            'appointment_lab_tests',
            'appointment_id',
            'lab_test_id'
        );
    }    

    public function attachedRadiologyResult()
    {
        return $this->belongsTo(RadiologyResult::class,'attached_radiology_result_id');
    }

    public function attachedLabResult() {
        return $this->belongsTo(LabResult::class,'attached_lab_result_id');
    }

    public function attachedMedicalRecords()
    {
        return $this->hasMany(AppointmentMedicalRecord::class);
    }

    public function rating()
    {
        return $this->hasOne(DoctorRating::class, 'appointment_id');
    }


}
