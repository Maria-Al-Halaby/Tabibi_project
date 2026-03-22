<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientMedicalRecord extends Model
{
    protected $fillable = [
        'patient_id',
        'type',
        'title',
        'record_date',
        'file_path'
        
    ];


        public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
