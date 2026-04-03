<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nutrition_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('diet_type')->nullable();
            $table->json('macros')->nullable();
            $table->text('goal_note')->nullable();
            $table->json('generation_inputs')->nullable();

            $table->json('saturday_plan')->nullable();
            $table->json('sunday_plan')->nullable();
            $table->json('monday_plan')->nullable();
            $table->json('tuesday_plan')->nullable();
            $table->json('wednesday_plan')->nullable();
            $table->json('thursday_plan')->nullable();
            $table->json('friday_plan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutrition_plans');
    }
};
