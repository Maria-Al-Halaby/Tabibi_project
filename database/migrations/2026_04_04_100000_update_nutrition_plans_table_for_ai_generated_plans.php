<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nutrition_plans', function (Blueprint $table) {
            $table->text('summary')->nullable()->after('generation_inputs');
            $table->unsignedInteger('daily_calories_target')->nullable()->after('summary');
            $table->decimal('daily_water_liters', 5, 2)->nullable()->after('daily_calories_target');
            $table->json('week_plan')->nullable()->after('daily_water_liters');
        });

        DB::table('nutrition_plans')
            ->select([
                'id',
                'goal_note',
                'saturday_plan',
                'sunday_plan',
                'monday_plan',
                'tuesday_plan',
                'wednesday_plan',
                'thursday_plan',
                'friday_plan',
            ])
            ->orderBy('id')
            ->chunkById(100, function ($plans): void {
                foreach ($plans as $plan) {
                    $weekPlan = array_filter([
                        'Saturday' => $plan->saturday_plan ? json_decode($plan->saturday_plan, true) : null,
                        'Sunday' => $plan->sunday_plan ? json_decode($plan->sunday_plan, true) : null,
                        'Monday' => $plan->monday_plan ? json_decode($plan->monday_plan, true) : null,
                        'Tuesday' => $plan->tuesday_plan ? json_decode($plan->tuesday_plan, true) : null,
                        'Wednesday' => $plan->wednesday_plan ? json_decode($plan->wednesday_plan, true) : null,
                        'Thursday' => $plan->thursday_plan ? json_decode($plan->thursday_plan, true) : null,
                        'Friday' => $plan->friday_plan ? json_decode($plan->friday_plan, true) : null,
                    ], fn ($value) => ! is_null($value));

                    $updates = [];

                    if (! empty($plan->goal_note)) {
                        $updates['summary'] = $plan->goal_note;
                    }

                    if ($weekPlan !== []) {
                        $updates['week_plan'] = json_encode($weekPlan, JSON_UNESCAPED_UNICODE);
                    }

                    if ($updates !== []) {
                        DB::table('nutrition_plans')
                            ->where('id', $plan->id)
                            ->update($updates);
                    }
                }
            });
    }

    public function down(): void
    {
        Schema::table('nutrition_plans', function (Blueprint $table) {
            $table->dropColumn([
                'summary',
                'daily_calories_target',
                'daily_water_liters',
                'week_plan',
            ]);
        });
    }
};
