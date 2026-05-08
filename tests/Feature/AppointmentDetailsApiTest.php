<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\ClinicCenter;
use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AppointmentDetailsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_sees_temporary_patient_data_in_appointment_details(): void
    {
        Role::findOrCreate('doctor', 'web');

        $doctorUser = User::factory()->create([
            'name' => 'Radiology',
            'last_name' => 'Doctor',
        ]);
        $doctorUser->assignRole('doctor');

        $specialization = Specialization::create([
            'name' => 'Radiology',
        ]);

        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'specialization_id' => $specialization->id,
            'experience_years' => 7,
            'doctor_type' => 'radiology',
        ]);

        $center = ClinicCenter::create([
            'user_id' => User::factory()->create()->id,
            'name' => 'Center One',
            'address' => 'Damascus',
        ]);

        $appointment = Appointment::create([
            'patient_id' => null,
            'temp_patient_name' => 'Walk In Patient',
            'temp_patient_phone' => '0999555123',
            'temp_patient_gender' => 'female',
            'temp_patient_age' => 31,
            'doctor_id' => $doctor->id,
            'clinic_center_id' => $center->id,
            'type' => 'radiology',
            'start_at' => now()->addDay()->setTime(7, 30),
            'status' => 'pending',
            'price' => 85,
            'note' => 'test message',
        ]);

        Sanctum::actingAs($doctorUser);

        $this->getJson("/api/appointment_details/{$appointment->id}")
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.appointment.patient.full_name', 'Walk In Patient')
            ->assertJsonPath('data.appointment.patient.phone', '0999555123')
            ->assertJsonPath('data.appointment.patient.gender', 'female')
            ->assertJsonPath('data.appointment.patient.age', 31)
            ->assertJsonPath('data.appointment.patient.is_temporary', true);
    }
}
