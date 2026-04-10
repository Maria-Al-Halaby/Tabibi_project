<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\ClinicCenter;
use App\Models\Doctor;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Specialization;
use App\Models\TypeOfMedicalImage;
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

    public function test_secretary_available_days_only_include_days_with_open_slots(): void
    {
        $this->createRole('secretary');

        [$secretary, $center] = $this->createSecretaryWithCenter();
        $specialization = Specialization::create(['name' => 'Cardiology']);
        $doctor = $this->createDoctor($specialization->id, 'AvailabilityDoctor');
        $scheduledDate = now()->addDay()->startOfDay();

        $pivot = \App\Models\ClinicCenterDoctor::create([
            'clinic_center_id' => $center->id,
            'doctor_id' => $doctor->id,
            'price' => 50,
        ]);

        \App\Models\DoctorSchedules::create([
            'clinic_center_doctor_id' => $pivot->id,
            'doctor_id' => $doctor->id,
            'day_of_week' => $scheduledDate->dayOfWeek,
            'start_time' => '09:00',
            'end_time' => '10:00',
        ]);

        Appointment::create([
            'patient_id' => null,
            'temp_patient_name' => 'Walk In One',
            'temp_patient_phone' => '0999000101',
            'doctor_id' => $doctor->id,
            'clinic_center_id' => $center->id,
            'type' => 'doctor',
            'start_at' => $scheduledDate->copy()->setTime(9, 0),
            'status' => 'pending',
        ]);

        Appointment::create([
            'patient_id' => null,
            'temp_patient_name' => 'Walk In Two',
            'temp_patient_phone' => '0999000102',
            'doctor_id' => $doctor->id,
            'clinic_center_id' => $center->id,
            'type' => 'doctor',
            'start_at' => $scheduledDate->copy()->setTime(9, 30),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($secretary)->get(route('secretary.doctors.availableDays', [
            'doctor' => $doctor->id,
            'days' => 1,
        ]));

        $response->assertOk();
        $response->assertJsonPath('status', true);
        $response->assertJsonPath('data.days.0.date', $scheduledDate->copy()->addWeek()->toDateString());

        $dayLabel = $response->json('data.days.0.label');

        $this->assertStringContainsString('09:00 - 10:00', $dayLabel);
    }

    public function test_secretary_available_times_show_only_open_slots_for_selected_doctor(): void
    {
        $this->createRole('secretary');

        [$secretary, $center] = $this->createSecretaryWithCenter();
        $specialization = Specialization::create(['name' => 'Dermatology']);
        $doctor = $this->createDoctor($specialization->id, 'TimeDoctor');
        $scheduledDate = now()->addDay()->startOfDay();

        $pivot = \App\Models\ClinicCenterDoctor::create([
            'clinic_center_id' => $center->id,
            'doctor_id' => $doctor->id,
            'price' => 40,
        ]);

        \App\Models\DoctorSchedules::create([
            'clinic_center_doctor_id' => $pivot->id,
            'doctor_id' => $doctor->id,
            'day_of_week' => $scheduledDate->dayOfWeek,
            'start_time' => '09:00',
            'end_time' => '10:00',
        ]);

        Appointment::create([
            'patient_id' => null,
            'temp_patient_name' => 'Booked Slot',
            'temp_patient_phone' => '0999000103',
            'doctor_id' => $doctor->id,
            'clinic_center_id' => $center->id,
            'type' => 'doctor',
            'start_at' => $scheduledDate->copy()->setTime(9, 0),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($secretary)->get(route('secretary.doctors.availableTimes', [
            'doctor' => $doctor->id,
            'date' => $scheduledDate->toDateString(),
        ]));

        $response->assertOk();
        $response->assertJsonPath('status', true);

        $times = collect($response->json('data.times'))->pluck('time')->all();

        $this->assertNotContains('09:00', $times);
        $this->assertContains('09:30', $times);
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

    public function test_secretary_can_schedule_walk_in_appointment_and_type_is_derived_from_doctor_type(): void
    {
        $this->createRole('secretary');

        [$secretary, $center] = $this->createSecretaryWithCenter();
        $specialization = Specialization::create(['name' => 'Radiology']);
        $doctor = $this->createDoctor($specialization->id, 'RadiologyDesk', 'radiology');
        $imageType = TypeOfMedicalImage::create(['name' => 'Chest X-Ray']);

        \App\Models\ClinicCenterDoctor::create([
            'clinic_center_id' => $center->id,
            'doctor_id' => $doctor->id,
            'price' => 45,
        ]);

        $center->medicalImages()->attach($imageType->id, ['price' => 85]);

        \App\Models\DoctorSchedules::create([
            'clinic_center_doctor_id' => \App\Models\ClinicCenterDoctor::where('clinic_center_id', $center->id)
                ->where('doctor_id', $doctor->id)
                ->firstOrFail()
                ->id,
            'doctor_id' => $doctor->id,
            'day_of_week' => now()->addDay()->dayOfWeek,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->actingAs($secretary)->post(route('secretary.appointments.store'), [
            'patient_name' => 'Walk In Patient',
            'patient_phone' => '0999555123',
            'patient_gender' => 'female',
            'patient_age' => 31,
            'specialization_id' => $specialization->id,
            'doctor_id' => $doctor->id,
            'type_of_medical_image_id' => $imageType->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '10:30',
            'note' => 'Booked at the front desk',
        ]);

        $response->assertRedirect(route('secretary.dashboard'));

        $this->assertDatabaseHas('appointments', [
            'doctor_id' => $doctor->id,
            'clinic_center_id' => $center->id,
            'patient_id' => null,
            'temp_patient_name' => 'Walk In Patient',
            'temp_patient_phone' => '0999555123',
            'temp_patient_gender' => 'female',
            'temp_patient_age' => 31,
            'type' => 'radiology',
            'status' => 'pending',
            'price' => 85,
        ]);

        $appointment = Appointment::where('temp_patient_name', 'Walk In Patient')->firstOrFail();

        $this->assertDatabaseHas('radiology_appointments', [
            'appointment_id' => $appointment->id,
            'type_of_medical_image_id' => $imageType->id,
        ]);
    }

    public function test_secretary_cannot_schedule_lab_walk_in_without_lab_tests(): void
    {
        $this->createRole('secretary');

        [$secretary, $center] = $this->createSecretaryWithCenter();
        $specialization = Specialization::create(['name' => 'Lab']);
        $doctor = $this->createDoctor($specialization->id, 'LabDesk', 'lab');

        \App\Models\ClinicCenterDoctor::create([
            'clinic_center_id' => $center->id,
            'doctor_id' => $doctor->id,
            'price' => 30,
        ]);

        \App\Models\DoctorSchedules::create([
            'clinic_center_doctor_id' => \App\Models\ClinicCenterDoctor::where('clinic_center_id', $center->id)
                ->where('doctor_id', $doctor->id)
                ->firstOrFail()
                ->id,
            'doctor_id' => $doctor->id,
            'day_of_week' => now()->addDay()->dayOfWeek,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->from(route('secretary.dashboard'))->actingAs($secretary)->post(route('secretary.appointments.store'), [
            'patient_name' => 'Mismatch Patient',
            'patient_phone' => '0999555999',
            'specialization_id' => $specialization->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '11:00',
        ]);

        $response->assertRedirect(route('secretary.dashboard'));
        $response->assertSessionHasErrors('lab_tests');

        $this->assertDatabaseMissing('appointments', [
            'temp_patient_name' => 'Mismatch Patient',
        ]);
    }

    public function test_secretary_can_schedule_lab_walk_in_with_selected_tests(): void
    {
        $this->createRole('secretary');

        [$secretary, $center] = $this->createSecretaryWithCenter();
        $specialization = Specialization::create(['name' => 'Lab']);
        $doctor = $this->createDoctor($specialization->id, 'LabBooking', 'lab');
        $cbc = LabTest::create(['name' => 'CBC']);
        $glucose = LabTest::create(['name' => 'Glucose']);

        \App\Models\ClinicCenterDoctor::create([
            'clinic_center_id' => $center->id,
            'doctor_id' => $doctor->id,
            'price' => 30,
        ]);

        $center->labTests()->attach($cbc->id, ['price' => 20]);
        $center->labTests()->attach($glucose->id, ['price' => 15]);

        \App\Models\DoctorSchedules::create([
            'clinic_center_doctor_id' => \App\Models\ClinicCenterDoctor::where('clinic_center_id', $center->id)
                ->where('doctor_id', $doctor->id)
                ->firstOrFail()
                ->id,
            'doctor_id' => $doctor->id,
            'day_of_week' => now()->addDay()->dayOfWeek,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->actingAs($secretary)->post(route('secretary.appointments.store'), [
            'patient_name' => 'Lab Walk In',
            'patient_phone' => '0999555001',
            'specialization_id' => $specialization->id,
            'doctor_id' => $doctor->id,
            'lab_tests' => [$cbc->id, $glucose->id],
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '12:00',
        ]);

        $response->assertRedirect(route('secretary.dashboard'));

        $this->assertDatabaseHas('appointments', [
            'doctor_id' => $doctor->id,
            'temp_patient_name' => 'Lab Walk In',
            'type' => 'lab',
            'price' => 35,
        ]);

        $appointment = Appointment::where('temp_patient_name', 'Lab Walk In')->firstOrFail();

        $this->assertDatabaseHas('appointment_lab_tests', [
            'appointment_id' => $appointment->id,
            'lab_test_id' => $cbc->id,
        ]);

        $this->assertDatabaseHas('appointment_lab_tests', [
            'appointment_id' => $appointment->id,
            'lab_test_id' => $glucose->id,
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

    private function createDoctor(int $specializationId, string $emailPrefix, string $doctorType = 'doctor'): Doctor
    {
        $user = User::factory()->create([
            'email' => strtolower($emailPrefix) . '@example.com',
            'phone' => '09' . fake()->unique()->numerify('########'),
        ]);

        return Doctor::create([
            'user_id' => $user->id,
            'specialization_id' => $specializationId,
            'experience_years' => 5,
            'doctor_type' => $doctorType,
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
