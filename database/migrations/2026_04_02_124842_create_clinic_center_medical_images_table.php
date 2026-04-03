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
        Schema::create('clinic_center_medical_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('clinic_center_id')->constrained()->cascadeOnDelete();
            $table->foreignId('type_of_medical_image_id')->constrained()->cascadeOnDelete();

            $table->decimal('price', 8, 2);

            $table->timestamps();

            $table->unique(
                ['clinic_center_id', 'type_of_medical_image_id'],
                'cc_med_img_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_center_medical_images');
    }
};
