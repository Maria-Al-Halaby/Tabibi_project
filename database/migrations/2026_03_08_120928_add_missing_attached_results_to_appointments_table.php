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
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('attached_radiology_result_id')
                ->nullable()
                ->after('note')
                ->constrained('radiology_results')
                ->nullOnDelete();

            $table->foreignId('attached_lab_result_id')
                ->nullable()
                ->after('attached_radiology_result_id')
                ->constrained('lab_results')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['attached_radiology_result_id']);
            $table->dropForeign(['attached_lab_result_id']);

            $table->dropColumn([
                'attached_radiology_result_id',
                'attached_lab_result_id'
            ]);
        });
    }
};
