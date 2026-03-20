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
        Schema::table('patients', function (Blueprint $table) {
            $table->text("chronic_diseases")->nullable();
            $table->text("permanent_medications")->nullable();
            $table->text("favorite_foods")->nullable();
            $table->text("disliked_foods")->nullable();
            $table->text("food_allergies")->nullable();
            $table->enum("blood_type" , ["O+" , "O-" , "A+" , "A-" , "B+" , "B-" , "AB+" , "AB-"])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
        $table->dropColumn([
            "chronic_diseases",
            "permanent_medications",
            "favorite_foods",
            "disliked_foods",
            "food_allergies",
            "blood_type"
        ]);
    });
    }
};
