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
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('prescription_id')
                  ->constrained('prescriptions')
                  ->onDelete('cascade');

            $table->string('medicine_name');
            $table->string('dose');
            $table->string('frequency');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('instructions')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
