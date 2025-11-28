<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected $fillable = [
        "name"
    ];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function centers()
    {
    return $this->belongsToMany(ClinicCenter::class, 'center_specialization');
    }
    
}
