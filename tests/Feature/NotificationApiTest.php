<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\ClinicCenter;
use App\Models\ClinicCenterDoctor;
use App\Models\Doctor;
use App\Models\DoctorSchedules;
use App\Models\Patient;
use App\Models\User;
use App\Notifications\AppointmentAlertNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class NotificationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_notifications(): void
    {
        $user = User::factory()->create();

        $user->notify(new AppointmentAlertNotification(
            title: 'Appointment Completed',
            body: 'Your appointment has been completed.',
            type: 'appointment_completed',
            appointmentId: 15,
        ));

        $notification = $user->notifications()->firstOrFail();

        Sanctum::actingAs($user);

        $this->getJson('/api/notifications')
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.unread_count', 1)
            ->assertJsonPath('data.items.0.id', $notification->id)
            ->assertJsonPath('data.items.0.title', 'Appointment Completed')
            ->assertJsonPath('data.items.0.type', 'appointment_completed')
            ->assertJsonPath('data.items.0.appointment_id', 15)
            ->assertJsonPath('data.items.0.is_read', false);
    }

    public function test_authenticated_user_can_mark_notification_as_read(): void
    {
        $user = User::factory()->create();

        $user->notify(new AppointmentAlertNotification(
            title: 'Appointment Cancelled',
            body: 'The doctor cancelled your appointment.',
            type: 'appointment_cancelled',
            appointmentId: 21,
        ));

        $notification = $user->notifications()->firstOrFail();

        Sanctum::actingAs($user);

        $this->postJson("/api/notifications/{$notification->id}/read")
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.notification.id', $notification->id)
            ->assertJsonPath('data.notification.is_read', true);

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_doctor_cancel_creates_patient_notification(): void
    {
        $doctorUser = User::factory()->create();
        $patientUser = User::factory()->create();

        $this->createRole('doctor');
        $this->createRole('patient');

        $doctorUser->assignRole('doctor');
        $patientUser->assignRole('patient');

        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'experience_years' => 5,
        ]);

        $center = ClinicCenter::create([
            'user_id' => User::factory()->create()->id,
            'name' => 'Main Center',
            'address' => 'Damascus',
        ]);

        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'gender' => 'male',
            'weight' => 70,
            'height' => 175,
            'marital_status' => 'single',
            'has_children' => false,
            'birth_date' => '1998-01-01',
            'address' => 'Damascus',
            'is_smoke' => false,
            'chronic_diseases' => 'none',
            'permanent_medications' => 'none',
            'favorite_foods' => 'rice',
            'disliked_foods' => 'none',
            'food_allergies' => 'none',
            'blood_type' => 'O+',
        ]);

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'clinic_center_id' => $center->id,
            'type' => 'doctor',
            'start_at' => now()->addDay(),
            'status' => 'pending',
        ]);

        Sanctum::actingAs($doctorUser);

        $this->postJson('/api/appointments/cancel', [
            'appointment_id' => $appointment->id,
        ])
            ->assertOk()
            ->assertJsonPath('status', true);

        $notification = $patientUser->notifications()->first();

        $this->assertNotNull($notification);
        $this->assertSame('appointment_cancelled', $notification->data['type']);
        $this->assertSame($appointment->id, $notification->data['appointment_id']);
    }

    public function test_patient_booking_creates_doctor_notification(): void
    {
        $doctorUser = User::factory()->create();
        $patientUser = User::factory()->create();

        $this->createRole('doctor');
        $this->createRole('patient');

        $doctorUser->assignRole('doctor');
        $patientUser->assignRole('patient');

        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'experience_years' => 5,
            'doctor_type' => 'doctor',
        ]);

        $center = ClinicCenter::create([
            'user_id' => User::factory()->create()->id,
            'name' => 'Booking Center',
            'address' => 'Damascus',
        ]);

        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'gender' => 'male',
            'weight' => 70,
            'height' => 175,
            'marital_status' => 'single',
            'has_children' => false,
            'birth_date' => '1998-01-01',
            'address' => 'Damascus',
            'is_smoke' => false,
            'chronic_diseases' => 'none',
            'permanent_medications' => 'none',
            'favorite_foods' => 'rice',
            'disliked_foods' => 'none',
            'food_allergies' => 'none',
            'blood_type' => 'O+',
        ]);

        $scheduleDate = now()->addDay()->startOfDay();

        $pivot = ClinicCenterDoctor::create([
            'clinic_center_id' => $center->id,
            'doctor_id' => $doctor->id,
            'price' => 50,
        ]);

        DoctorSchedules::create([
            'clinic_center_doctor_id' => $pivot->id,
            'doctor_id' => $doctor->id,
            'day_of_week' => $scheduleDate->dayOfWeek,
            'start_time' => '09:00',
            'end_time' => '10:00',
        ]);

        Sanctum::actingAs($patientUser);

        $this->postJson("/api/appointment/{$doctor->id}/{$center->id}/{$scheduleDate->toDateString()}/morning", [
            'type' => 'doctor',
            'time' => '09:00',
        ])
            ->assertCreated()
            ->assertJsonPath('status', true);

        $appointment = Appointment::query()
            ->where('doctor_id', $doctor->id)
            ->where('patient_id', $patient->id)
            ->firstOrFail();

        $notification = $doctorUser->notifications()->first();

        $this->assertNotNull($notification);
        $this->assertSame('New Appointment Booked', $notification->data['title']);
        $this->assertSame('new_appointment', $notification->data['type']);
        $this->assertSame($appointment->id, $notification->data['appointment_id']);
    }

    public function test_doctor_completion_creates_patient_notification(): void
    {
        $doctorUser = User::factory()->create();
        $patientUser = User::factory()->create();

        $this->createRole('doctor');
        $this->createRole('patient');

        $doctorUser->assignRole('doctor');
        $patientUser->assignRole('patient');

        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'experience_years' => 5,
            'doctor_type' => 'doctor',
        ]);

        $center = ClinicCenter::create([
            'user_id' => User::factory()->create()->id,
            'name' => 'Completion Center',
            'address' => 'Damascus',
        ]);

        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'gender' => 'female',
            'weight' => 60,
            'height' => 165,
            'marital_status' => 'single',
            'has_children' => false,
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

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'clinic_center_id' => $center->id,
            'type' => 'doctor',
            'start_at' => now()->addDay(),
            'status' => 'pending',
        ]);

        Sanctum::actingAs($doctorUser);

        $this->postJson('/api/doctor/appointments/end', [
            'appointment_id' => $appointment->id,
            'note' => 'Patient is stable.',
        ])
            ->assertOk()
            ->assertJsonPath('status', true);

        $notification = $patientUser->notifications()->first();

        $this->assertNotNull($notification);
        $this->assertSame('appointment_completed', $notification->data['type']);
        $this->assertSame($appointment->id, $notification->data['appointment_id']);
    }

    public function test_patient_cancel_does_not_create_self_notification(): void
    {
        $patientUser = User::factory()->create();
        $doctorUser = User::factory()->create();

        $this->createRole('patient');
        $this->createRole('doctor');

        $patientUser->assignRole('patient');
        $doctorUser->assignRole('doctor');

        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'gender' => 'female',
            'weight' => 60,
            'height' => 165,
            'marital_status' => 'single',
            'has_children' => false,
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

        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'experience_years' => 5,
        ]);

        $center = ClinicCenter::create([
            'user_id' => User::factory()->create()->id,
            'name' => 'Main Center',
            'address' => 'Damascus',
        ]);

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'clinic_center_id' => $center->id,
            'type' => 'doctor',
            'start_at' => now()->addDay(),
            'status' => 'pending',
        ]);

        Sanctum::actingAs($patientUser);

        $this->postJson('/api/appointments/cancel', [
            'appointment_id' => $appointment->id,
        ])
            ->assertOk()
            ->assertJsonPath('status', true);

        $this->assertCount(0, $patientUser->notifications);
    }

    private function createRole(string $name): void
    {
        Role::findOrCreate($name, 'web');
    }
}
