<?php

namespace App\Models;

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
        'macros' => 'array',
        'generation_inputs' => 'array',
        'daily_calories_target' => 'integer',
        'daily_water_liters' => 'float',
        'week_plan' => 'array',
        'saturday_plan' => 'array',
        'sunday_plan' => 'array',
        'monday_plan' => 'array',
        'tuesday_plan' => 'array',
        'wednesday_plan' => 'array',
        'thursday_plan' => 'array',
        'friday_plan' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
