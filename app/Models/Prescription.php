<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'appointment_id', 
        'general_note', 
        'status',
        'send_to_pharmacy',
        'pharmacy_status'
        ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    
    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }
}
