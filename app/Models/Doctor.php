<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable  = [
        "user_id",
        "specialization_id", 
        "bio" , 
        "is_active" , 
        "profile_image" 
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
 /*    public function specializtion()  
    {
        return $this->hasOne(Specialization::class);
    } */

        public function specialization()
        {
            return $this->belongsTo(Specialization::class);
        }


    public function clinic_centers()
    {
        return $this->belongsToMany(ClinicCenter::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }


}
