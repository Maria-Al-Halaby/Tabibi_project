<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiFeatureUsage extends Model
{
    protected $fillable = [
        'patient_id',
        'feature_type',
        'used_count',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'period_start' => 'datetime',
        'period_end' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
