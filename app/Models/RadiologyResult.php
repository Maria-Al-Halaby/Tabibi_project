<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RadiologyResult extends Model
{
    protected $fillable = [
        "appointment_id",
        "image_path",
        "ai_diagnosis",
        "notes"
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path
            ? url(Storage::url($this->image_path))
            : null;
    }
}
