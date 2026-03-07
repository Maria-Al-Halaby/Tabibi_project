<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\ClinicCenter;
use App\Models\ClinicCenterDoctor;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // specialization
        $specialization = Specialization::firstOrCreate(
            ['name' => 'General Medicine'],
            ['image' => null]
        );

        // doctor user
        $doctorUser = User::firstOrCreate(
            ['email' => 'doctor1@gmail.com'],
            [
                'name' => 'Test',
                'last_name' => 'Doctor',
                'password' => Hash::make('password'),
                'phone' => '0999999999',
            ]
        );

        if (!$doctorUser->hasRole('doctor')) {
            $doctorUser->assignRole('doctor');
        }

        // doctor
        $doctor = Doctor::firstOrCreate(
            ['user_id' => $doctorUser->id],
            [
                'specialization_id' => $specialization->id,
                'bio' => 'Test doctor',
                'is_active' => 1,
                'experience_years' => 5,
            ]
        );

        // center (خذ أول مركز موجود أو أنشئ واحد)
        $adminUser = User::firstOrCreate(
            ['email' => 'centeradmin@gmail.com'],
            [
                'name' => 'Center',
                'last_name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }

        $center = ClinicCenter::firstOrCreate(
            ['user_id' => $adminUser->id],
            [
                'name' => 'Test Medical Center',
                'address' => 'Damascus/Syria',
                'is_active' => 1,
                'bio' => 'Center for testing'
            ]
        );

        // link doctor to center
        ClinicCenterDoctor::firstOrCreate(
            [
                'clinic_center_id' => $center->id,
                'doctor_id' => $doctor->id,
            ],
            [
                'price' => 100.00
            ]
        );

        // patient user
        $patientUser = User::firstOrCreate(
            ['email' => 'patient1@gmail.com'],
            [
                'name' => 'Test',
                'last_name' => 'Patient',
                'password' => Hash::make('password'),
                'phone' => '0988888888',
            ]
        );

        if (!$patientUser->hasRole('patient')) {
            $patientUser->assignRole('patient');
        }

        // patient
        $patient = Patient::firstOrCreate(
            ['user_id' => $patientUser->id],
            [
                'gender' => 'female',
                'weight' => 60,
                'height' => 165,
                'marital_status' => 'single',
                'has_children' => 0,
                'address' => 'Damascus',
                'is_smoke' => 0,
                'birth_date' => '2000-01-01',
            ]
        );

        // appointments for testing
        Appointment::firstOrCreate(
            [
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'clinic_center_id' => $center->id,
                'start_at' => now()->addDay(),
            ],
            [
                'status' => 'pending',
                'type' => 'doctor',
                'note' => 'Test appointment 1',
                'emergency' => 0,
            ]
        );

        Appointment::firstOrCreate(
            [
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'clinic_center_id' => $center->id,
                'start_at' => now()->addDays(2),
            ],
            [
                'status' => 'pending',
                'type' => 'doctor',
                'note' => 'Test appointment 2',
                'emergency' => 0,
            ]
        );
    }
}