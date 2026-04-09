<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\ClinicCenter;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SecretaryWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_secretary_for_their_center(): void
    {
        $this->createRole('admin');
        $this->createRole('secretary');

        [$admin, $center] = $this->createAdminWithCenter();

        $response = $this->actingAs($admin)->post(route('Admin.Secretary.store'), [
            'name' => 'Maya',
            'last_name' => 'Desk',
            'email' => 'maya.secretary@example.com',
            'phone' => '0999000001',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('Admin.Secretary.index'));

        $secretary = User::where('email', 'maya.secretary@example.com')->firstOrFail();

        $this->assertTrue($secretary->hasRole('secretary'));
        $this->assertDatabaseHas('clinic_center_secretaries', [
            'clinic_center_id' => $center->id,
            'user_id' => $secretary->id,
        ]);
    }

    public function test_secretary_can_log_in_through_secretary_login_page(): void
    {
        $this->createRole('secretary');

        $secretary = User::factory()->create([
            'email' => 'secretary@example.com',
        ]);
        $secretary->assignRole('secretary');

        $centerOwner = User::factory()->create();
        $center = ClinicCenter::create([
            'user_id' => $centerOwner->id,
            'name' => 'Desk Center',
            'address' => 'Damascus',
        ]);

        $center->secretaries()->attach($secretary->id);

        $response = $this->post(route('secretary.login.submit'), [
            'email' => 'secretary@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('secretary.dashboard'));
        $this->assertAuthenticatedAs($secretary);
    }

    public function test_secretary_dashboard_filters_pending_appointments_by_specialty(): void
    {
        $this->createRole('secretary');

        [$secretary, $center] = $this->createSecretaryWithCenter();

        $cardiology = Specialization::create(['name' => 'Cardiology']);
        $dermatology = Specialization::create(['name' => 'Dermatology']);

        $cardioDoctor = $this->createDoctor($cardiology->id, 'Cardio');
        $dermaDoctor = $this->createDoctor($dermatology->id, 'Derma');
        $patient = $this->createPatient('patient.one@example.com');

        $cardioAppointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $cardioDoctor->id,
            'clinic_center_id' => $center->id,
            'type' => 'doctor',
            'start_at' => now()->addDay(),
            'status' => 'pending',
        ]);

        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $dermaDoctor->id,
            'clinic_center_id' => $center->id,
            'type' => 'doctor',
            'start_at' => now()->addDays(2),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($secretary)->get(route('secretary.dashboard', [
            'specialization_id' => $cardiology->id,
        ]));

        $response->assertOk();
        $response->assertViewHas('selectedSpecializationId', $cardiology->id);
        $response->assertViewHas('appointments', function ($appointments) use ($cardioAppointment) {
            return $appointments->count() === 1
                && (int) $appointments->first()->id === $cardioAppointment->id;
        });
    }

    public function test_secretary_can_cancel_appointment_for_their_center(): void
    {
        $this->createRole('secretary');

        [$secretary, $center] = $this->createSecretaryWithCenter();
        $specialization = Specialization::create(['name' => 'Neurology']);
        $doctor = $this->createDoctor($specialization->id, 'Neuro');
        $patient = $this->createPatient('patient.two@example.com');

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'clinic_center_id' => $center->id,
            'type' => 'doctor',
            'start_at' => now()->addDay(),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($secretary)
            ->get(route('secretary.appointments.cancel', ['appointments' => $appointment->id]));

        $response->assertRedirect(route('secretary.dashboard'));
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'canceled',
        ]);
    }

    private function createRole(string $name): void
    {
        Role::findOrCreate($name, 'web');
    }

    private function createAdminWithCenter(): array
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'phone' => '0999000000',
        ]);
        $admin->assignRole('admin');

        $center = ClinicCenter::create([
            'user_id' => $admin->id,
            'name' => 'Admin Center',
            'address' => 'Damascus',
        ]);

        return [$admin, $center];
    }

    private function createSecretaryWithCenter(): array
    {
        $secretary = User::factory()->create([
            'email' => 'desk@example.com',
            'phone' => '0999000010',
        ]);
        $secretary->assignRole('secretary');

        $centerOwner = User::factory()->create([
            'email' => 'owner@example.com',
            'phone' => '0999000011',
        ]);

        $center = ClinicCenter::create([
            'user_id' => $centerOwner->id,
            'name' => 'Secretary Center',
            'address' => 'Damascus',
        ]);

        $center->secretaries()->attach($secretary->id);

        return [$secretary, $center];
    }

    private function createDoctor(int $specializationId, string $emailPrefix): Doctor
    {
        $user = User::factory()->create([
            'email' => strtolower($emailPrefix) . '@example.com',
            'phone' => '09' . fake()->unique()->numerify('########'),
        ]);

        return Doctor::create([
            'user_id' => $user->id,
            'specialization_id' => $specializationId,
            'experience_years' => 5,
            'doctor_type' => 'doctor',
        ]);
    }

    private function createPatient(string $email): Patient
    {
        $user = User::factory()->create([
            'email' => $email,
            'phone' => '09' . fake()->unique()->numerify('########'),
        ]);

        return Patient::create([
            'user_id' => $user->id,
            'gender' => 'female',
            'weight' => 60,
            'height' => 165,
            'marital_status' => 'single',
            'has_children' => false,
            'number_of_children' => null,
            'birth_date' => '1999-01-01',
            'address' => 'Damascus',
            'is_smoke' => false,
            'chronic_diseases' => 'none',
            'permanent_medications' => 'none',
            'favorite_foods' => 'fruit',
            'disliked_foods' => 'none',
            'food_allergies' => 'none',
            'blood_type' => 'A+',
        ]);
    }
}
