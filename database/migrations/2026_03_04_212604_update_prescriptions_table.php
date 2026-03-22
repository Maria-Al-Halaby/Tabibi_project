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
        Schema::table('prescriptions', function (Blueprint $table) {

            $table->dropColumn(['medicine', 'dose', 'duration']);

            $table->text('general_note')->nullable()->after('appointment_id');

            $table->enum('status', ['pending', 'ready', 'dispensed'])
                ->default('pending')
                ->after('general_note');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
