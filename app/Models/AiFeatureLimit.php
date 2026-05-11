<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiFeatureLimit extends Model
{
    protected $fillable = [
        'feature_type',
        'limit',
        'period',
    ];
}
