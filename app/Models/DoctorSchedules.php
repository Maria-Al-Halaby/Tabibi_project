<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedules extends Model
{
    protected $fillable  = [
        "clinic_center_doctor_id", 
        "day_of_week",
        "start_time", 
        "end_time",
    ];


    public function doctor()  
    {
        $this->belongsTo(Doctor::class);
    }
}
