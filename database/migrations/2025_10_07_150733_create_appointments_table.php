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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->foreignId("patient_id")->
            constrained("patients")->
            cascadeOnDelete()->
            cascadeOnUpdate();

            $table->foreignId("doctor_id")->
            constrained("doctors")->
            cascadeOnDelete()->
            cascadeOnUpdate();

            $table->foreignId("clinic_center_id")->
            constrained("clinic_centers")->
            cascadeOnDelete()->
            cascadeOnUpdate();

            $table->dateTime("start_at");

            $table->dateTime("end_at")->nullable();

           // $table->flaot("score")->nullable();

           // $table->boolean("emergency")->default(false);

            $table->enum('status', ['pending','canceled','completed'])->default('pending');




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
