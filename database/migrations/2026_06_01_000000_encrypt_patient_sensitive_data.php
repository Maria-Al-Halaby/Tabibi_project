<?php

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $appointmentColumns = [
        'temp_patient_name' => 'string',
        'temp_patient_phone' => 'string',
        'temp_patient_gender' => 'string',
        'temp_patient_age' => 'integer',
        'result_ratio' => 'float',
        'expected_disease' => 'string',
        'doctor_note' => 'string',
        'note' => 'string',
    ];

    private array $nutritionPlanColumns = [
        'diet_type' => 'string',
        'macros' => 'array',
        'goal_note' => 'string',
        'generation_inputs' => 'array',
        'summary' => 'string',
        'daily_calories_target' => 'integer',
        'daily_water_liters' => 'float',
        'week_plan' => 'array',
        'saturday_plan' => 'array',
        'sunday_plan' => 'array',
        'monday_plan' => 'array',
        'tuesday_plan' => 'array',
        'wednesday_plan' => 'array',
        'thursday_plan' => 'array',
        'friday_plan' => 'array',
    ];

    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->text('temp_patient_name')->nullable()->change();
            $table->text('temp_patient_phone')->nullable()->change();
            $table->text('temp_patient_gender')->nullable()->change();
            $table->text('temp_patient_age')->nullable()->change();
            $table->text('result_ratio')->nullable()->change();
            $table->text('expected_disease')->nullable()->change();
        });

        Schema::table('nutrition_plans', function (Blueprint $table) {
            $table->text('diet_type')->nullable()->change();
            $table->longText('macros')->nullable()->change();
            $table->longText('generation_inputs')->nullable()->change();
            $table->longText('saturday_plan')->nullable()->change();
            $table->longText('sunday_plan')->nullable()->change();
            $table->longText('monday_plan')->nullable()->change();
            $table->longText('tuesday_plan')->nullable()->change();
            $table->longText('wednesday_plan')->nullable()->change();
            $table->longText('thursday_plan')->nullable()->change();
            $table->longText('friday_plan')->nullable()->change();
            $table->text('daily_calories_target')->nullable()->change();
            $table->text('daily_water_liters')->nullable()->change();
            $table->longText('week_plan')->nullable()->change();
        });

        $this->encryptTable('appointments', $this->appointmentColumns);
        $this->encryptTable('nutrition_plans', $this->nutritionPlanColumns);
    }

    public function down(): void
    {
        $this->decryptTable('appointments', $this->appointmentColumns);
        $this->decryptTable('nutrition_plans', $this->nutritionPlanColumns);

        Schema::table('appointments', function (Blueprint $table) {
            $table->string('temp_patient_name')->nullable()->change();
            $table->string('temp_patient_phone')->nullable()->change();
            $table->enum('temp_patient_gender', ['male', 'female'])->nullable()->change();
            $table->unsignedTinyInteger('temp_patient_age')->nullable()->change();
            $table->float('result_ratio')->nullable()->change();
            $table->string('expected_disease')->nullable()->change();
        });

        Schema::table('nutrition_plans', function (Blueprint $table) {
            $table->string('diet_type')->nullable()->change();
            $table->json('macros')->nullable()->change();
            $table->json('generation_inputs')->nullable()->change();
            $table->json('saturday_plan')->nullable()->change();
            $table->json('sunday_plan')->nullable()->change();
            $table->json('monday_plan')->nullable()->change();
            $table->json('tuesday_plan')->nullable()->change();
            $table->json('wednesday_plan')->nullable()->change();
            $table->json('thursday_plan')->nullable()->change();
            $table->json('friday_plan')->nullable()->change();
            $table->unsignedInteger('daily_calories_target')->nullable()->change();
            $table->decimal('daily_water_liters', 5, 2)->nullable()->change();
            $table->json('week_plan')->nullable()->change();
        });
    }

    private function encryptTable(string $table, array $columns): void
    {
        DB::table($table)
            ->select(array_merge(['id'], array_keys($columns)))
            ->orderBy('id')
            ->chunkById(100, function ($rows) use ($table, $columns): void {
                foreach ($rows as $row) {
                    $updates = [];

                    foreach ($columns as $column => $type) {
                        $value = $row->{$column};

                        if ($value === null || $this->isEncrypted($value)) {
                            continue;
                        }

                        $updates[$column] = Crypt::encryptString($this->normalizeForDatabase($value, $type));
                    }

                    if ($updates !== []) {
                        DB::table($table)->where('id', $row->id)->update($updates);
                    }
                }
            });
    }

    private function decryptTable(string $table, array $columns): void
    {
        DB::table($table)
            ->select(array_merge(['id'], array_keys($columns)))
            ->orderBy('id')
            ->chunkById(100, function ($rows) use ($table, $columns): void {
                foreach ($rows as $row) {
                    $updates = [];

                    foreach ($columns as $column => $type) {
                        $value = $row->{$column};

                        if ($value === null) {
                            continue;
                        }

                        $updates[$column] = $this->normalizeForDatabase($this->decryptIfPossible($value), $type);
                    }

                    if ($updates !== []) {
                        DB::table($table)->where('id', $row->id)->update($updates);
                    }
                }
            });
    }

    private function isEncrypted(mixed $value): bool
    {
        try {
            Crypt::decryptString((string) $value);

            return true;
        } catch (DecryptException) {
            return false;
        }
    }

    private function decryptIfPossible(mixed $value): string
    {
        try {
            return Crypt::decryptString((string) $value);
        } catch (DecryptException) {
            return (string) $value;
        }
    }

    private function normalizeForDatabase(mixed $value, string $type): string
    {
        return match ($type) {
            'integer' => (string) (int) $value,
            'float' => (string) (float) $value,
            default => (string) $value,
        };
    }
};
