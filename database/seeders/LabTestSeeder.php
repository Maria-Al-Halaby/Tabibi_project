<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tests = [
            ['name' => 'CBC'],
            ['name' => 'Blood Sugar'],
            ['name' => 'Vitamin D'],
            ['name' => 'Iron'],
            ['name' => 'Cholesterol'],
            ['name' => 'Liver Function Test'],
            ['name' => 'Kidney Function Test'],
            ['name' => 'Thyroid Test'],
        ];

        foreach ($tests as $test) {
            LabTest::updateOrCreate(
                ['name' => $test['name']],
                $test
            );
        }
    }
}
