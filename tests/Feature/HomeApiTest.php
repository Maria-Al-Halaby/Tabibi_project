<?php

namespace Tests\Feature;

use App\Models\ClinicCenter;
use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class HomeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_doctor_is_active_updates_after_doctor_is_assigned_to_center(): void
    {
        Cache::forget('home_doctors');
        Role::findOrCreate('patient', 'web');

        $patientUser = User::factory()->create();
        $patientUser->assignRole('patient');
        Sanctum::actingAs($patientUser);

        $specialization = Specialization::create([
            'name' => 'Cardiology',
        ]);

        $doctor = Doctor::create([
            'user_id' => User::factory()->create([
                'name' => 'Home Doctor',
            ])->id,
            'specialization_id' => $specialization->id,
            'experience_years' => 7,
            'doctor_type' => 'doctor',
        ]);

        $this->getJson('/api/home')
            ->assertOk()
            ->assertJsonPath('data.doctors.0.id', $doctor->id)
            ->assertJsonPath('data.doctors.0.is_active', 0);

        $center = ClinicCenter::create([
            'user_id' => User::factory()->create()->id,
            'name' => 'Center One',
            'address' => 'Damascus',
        ]);

        $center->doctors()->attach($doctor->id, ['price' => 100]);

        $this->getJson('/api/home')
            ->assertOk()
            ->assertJsonPath('data.doctors.0.id', $doctor->id)
            ->assertJsonPath('data.doctors.0.is_active', 1);
    }
}
