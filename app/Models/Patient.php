<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        "user_id" , 
        "gender" , 
        "weight" , 
        "height" , 
        "marital_status" , 
        "has_children" , 
        "number_of_children" , 
        "type_of_birth", 
        "profile_image"
    ]; 
    
    protected $casts = [
        "has_children" => "boolean"
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
