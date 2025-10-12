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
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId("clinic_center_doctor_id")->
            constrained("clinic_center_doctor")->
            cascadeOnDelete()->
            cascadeOnUpdate();

            $table->unsignedTinyInteger("day_of_week");

            $table->time("start_time");

            $table->time("end_time");


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
