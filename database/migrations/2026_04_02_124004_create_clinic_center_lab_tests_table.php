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
        Schema::create('clinic_center_lab_tests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('clinic_center_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lab_test_id')->constrained()->cascadeOnDelete();

            $table->decimal('price', 8, 2);

            $table->timestamps();

            $table->unique(['clinic_center_id', 'lab_test_id']);
  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_center_lab_tests');
    }
};
