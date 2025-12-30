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
        Schema::table('doctor_ratings', function (Blueprint $table) {
            //do this for 1 step 
            //$table->dropForeign(['doctor_id']);
            //$table->dropForeign(['patient_id']);


            //this to second step
            //$table->dropUnique('doctor_patient_unique');


            //this for third step
            $table->foreign('doctor_id')->references('id')->on('doctors')->cascadeOnDelete();
            $table->foreign('patient_id')->references('id')->on('patients')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_ratings', function (Blueprint $table) {
            //
        });
    }
};
