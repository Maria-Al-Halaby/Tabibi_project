<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabResult extends Model
{
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function tests()
    {
        return $this->belongsToMany(LabTest::class);
    }
}
