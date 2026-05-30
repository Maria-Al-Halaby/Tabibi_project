<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'table_name',
        'model_type',
        'model_id',
        'status',
        'message',
        'parameters',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'parameters' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
