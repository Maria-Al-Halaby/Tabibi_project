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
        Schema::create('radiology_appointments', function (Blueprint $table) {
            
            $table->id();
            
            $table->foreignId('appointment_id')
                ->constrained('appointments')
                ->onDelete('cascade');

            $table->foreignId('type_of_medical_image_id')
                ->constrained('type_of_medical_images')
                ->onDelete('cascade'); 

            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiology_appointments');
    }
};
