<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabResult extends Model
{
    protected $fillable = [
        "appointment_id",
        "result_file",
        "ai_diagnosis",
        "notes"
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

}
