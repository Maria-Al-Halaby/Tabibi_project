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
        Schema::create('question_relations', function (Blueprint $table) {
            $table->id();

            $table->foreignId("paraent_question_id")->
            constrained("questions")->
            cascadeOnDelete()->
            cascadeOnUpdate();

            $table->foreignId("child_question_id")->
            constrained("questions")->
            cascadeOnDelete()->
            cascadeOnUpdate();

            $table->boolean("targget_answer_value");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_relations');
    }
};
