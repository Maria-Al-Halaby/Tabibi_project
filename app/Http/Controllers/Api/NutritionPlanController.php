<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NutritionPlan;
use Illuminate\Http\Request;

class NutritionPlanController extends Controller
{
     public function store(Request $request)
    {
        $data = $request->validate([
            'diet_type' => 'nullable|string|max:255',
            'macros' => 'nullable|array',
            'goal_note' => 'nullable|string',
            'generation_inputs' => 'nullable|array',

            'saturday_plan' => 'nullable|array',
            'sunday_plan' => 'nullable|array',
            'monday_plan' => 'nullable|array',
            'tuesday_plan' => 'nullable|array',
            'wednesday_plan' => 'nullable|array',
            'thursday_plan' => 'nullable|array',
            'friday_plan' => 'nullable|array',
        ]);

        $user = auth()->user();

        $plan = NutritionPlan::create([
            'user_id' => $user->id,
            'diet_type' => $data['diet_type'] ?? null,
            'macros' => $data['macros'] ?? null,
            'goal_note' => $data['goal_note'] ?? null,
            'generation_inputs' => $data['generation_inputs'] ?? null,

            'saturday_plan' => $data['saturday_plan'] ?? null,
            'sunday_plan' => $data['sunday_plan'] ?? null,
            'monday_plan' => $data['monday_plan'] ?? null,
            'tuesday_plan' => $data['tuesday_plan'] ?? null,
            'wednesday_plan' => $data['wednesday_plan'] ?? null,
            'thursday_plan' => $data['thursday_plan'] ?? null,
            'friday_plan' => $data['friday_plan'] ?? null,
        ]);

        return response()->json([
            'message' => 'Nutrition plan saved successfully',
            'status' => true,
            'data' => [
                'nutrition_plan_id' => $plan->id,
            ]
        ], 201);
    }

    public function latest()
    {
        $user = auth()->user();

        $plan = NutritionPlan::where('user_id', $user->id)
            ->latest()
            ->first();

        if (!$plan) {
            return response()->json([
                'message' => 'No nutrition plan found',
                'status' => false,
            ], 404);
        }

        return response()->json([
            'message' => 'Nutrition plan fetched successfully',
            'status' => true,
            'data' => $plan,
        ], 200);
    }
}
