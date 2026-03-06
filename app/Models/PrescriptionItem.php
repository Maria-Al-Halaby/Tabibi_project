<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
