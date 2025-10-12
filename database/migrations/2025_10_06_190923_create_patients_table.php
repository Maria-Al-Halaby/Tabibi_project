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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            $table->foreignId("user_id")->
            unique()->
            constrained("users")->
            cascadeOnDelete()->
            cascadeOnUpdate();

            $table->string("gender");
            $table->smallInteger("weight")->nullable();
            $table->smallInteger("height")->nullable();
            $table->string("marital_status");
            $table->boolean("has_children")->default(false);
            $table->tinyInteger("number_of_children")->nullable();
            $table->string("type_of_birth")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
