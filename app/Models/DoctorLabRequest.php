<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorLabRequest extends Model
{
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
