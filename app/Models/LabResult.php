<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    public function getResultFileUrlAttribute()
    {
        return $this->result_file
            ? url(Storage::url($this->result_file))
            : null;
    }

}
