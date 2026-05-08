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
            $table->dropForeign(['patient_id']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('patient_id')->nullable()->change();
            $table->string('temp_patient_name')->nullable()->after('patient_id');
            $table->string('temp_patient_phone')->nullable()->after('temp_patient_name');
            $table->enum('temp_patient_gender', ['male', 'female'])->nullable()->after('temp_patient_phone');
            $table->unsignedTinyInteger('temp_patient_age')->nullable()->after('temp_patient_gender');
            $table->foreign('patient_id')
                ->references('id')
                ->on('patients')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'temp_patient_name',
                'temp_patient_phone',
                'temp_patient_gender',
                'temp_patient_age',
            ]);

            $table->unsignedBigInteger('patient_id')->nullable(false)->change();
            $table->foreign('patient_id')
                ->references('id')
                ->on('patients')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }
};
