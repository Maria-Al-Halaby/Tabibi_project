<?php

namespace App\Models;

use App\Casts\EncryptedValue;
use Illuminate\Database\Eloquent\Model;

class NutritionPlan extends Model
{
    protected $fillable = [
        'user_id',
        'diet_type',
        'macros',
        'goal_note',
        'generation_inputs',
        'summary',
        'daily_calories_target',
        'daily_water_liters',
        'week_plan',
        'saturday_plan',
        'sunday_plan',
        'monday_plan',
        'tuesday_plan',
        'wednesday_plan',
        'thursday_plan',
        'friday_plan',
    ];

    protected $casts = [
        'diet_type' => EncryptedValue::class,
        'macros' => EncryptedValue::class . ':array',
        'goal_note' => EncryptedValue::class,
        'generation_inputs' => EncryptedValue::class . ':array',
        'summary' => EncryptedValue::class,
        'daily_calories_target' => EncryptedValue::class . ':integer',
        'daily_water_liters' => EncryptedValue::class . ':float',
        'week_plan' => EncryptedValue::class . ':array',
        'saturday_plan' => EncryptedValue::class . ':array',
        'sunday_plan' => EncryptedValue::class . ':array',
        'monday_plan' => EncryptedValue::class . ':array',
        'tuesday_plan' => EncryptedValue::class . ':array',
        'wednesday_plan' => EncryptedValue::class . ':array',
        'thursday_plan' => EncryptedValue::class . ':array',
        'friday_plan' => EncryptedValue::class . ':array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
