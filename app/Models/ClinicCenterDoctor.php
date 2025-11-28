<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicCenterDoctor extends Model
{
    protected $table = "clinic_center_doctor";

    protected $guarded = [];


    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function clinic_center()
    {
        return $this->belongsTo(ClinicCenter::class);
    }

    public function schedules()  
    {
        return $this->hasMany(DoctorSchedules::class, 'clinic_center_doctor_id');
    }
}
