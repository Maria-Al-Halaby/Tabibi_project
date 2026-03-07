<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeOfMedicalImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [
            ['name' => 'Chest X-Ray'],
            ['name' => 'Hand X-Ray'],
            ['name' => 'Leg X-Ray'],
            ['name' => 'Dental X-Ray'],
            ['name' => 'CT Scan'],
            ['name' => 'MRI'],
            ['name' => 'Ultrasound'],
        ];

        foreach ($images as $image) {
            DB::table('type_of_medical_images')->updateOrInsert(
                ['name' => $image['name']],
                $image
            );
        }
    }
}
