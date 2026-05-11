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
        Schema::create('ai_feature_limits', function (Blueprint $table) {
            $table->id();
            $table->string('feature_type')->unique();
            $table->unsignedInteger('limit')->default(0);
            $table->enum('period', ['day', 'week', 'month'])->default('week');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_feature_limits');
    }
};
