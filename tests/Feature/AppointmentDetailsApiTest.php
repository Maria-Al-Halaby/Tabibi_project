<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\ClinicCenter;
use App\Models\Doctor;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\RadiologyAppointment;
use App\Models\Specialization;
use App\Models\TypeOfMedicalImage;
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

    public function test_patient_sees_selected_radiology_image_type_in_appointment_details(): void
    {
        Role::findOrCreate('patient', 'web');
        Role::findOrCreate('doctor', 'web');

        [$patientUser, $patient] = $this->createPatient();
        $doctor = $this->createDoctor();
        $center = $this->createCenter();
        $imageType = TypeOfMedicalImage::create(['name' => 'Chest X-Ray']);

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'clinic_center_id' => $center->id,
            'type' => 'radiology',
            'start_at' => now()->addDay()->setTime(10, 0),
            'status' => 'pending',
            'price' => 45,
        ]);

        RadiologyAppointment::create([
            'appointment_id' => $appointment->id,
            'type_of_medical_image_id' => $imageType->id,
        ]);

        Sanctum::actingAs($patientUser);

        $this->getJson("/api/appointment_details/{$appointment->id}")
            ->assertOk()
            ->assertJsonPath('data.appointment.selected_radiology_image_type.type_of_medical_image_id', $imageType->id)
            ->assertJsonPath('data.appointment.selected_radiology_image_type.type_name', 'Chest X-Ray')
            ->assertJsonPath('data.appointment.selected_lab_tests', []);
    }

    public function test_patient_sees_selected_lab_tests_in_appointment_details(): void
    {
        Role::findOrCreate('patient', 'web');
        Role::findOrCreate('doctor', 'web');

        [$patientUser, $patient] = $this->createPatient();
        $doctor = $this->createDoctor('Lab');
        $center = $this->createCenter();
        $cbc = LabTest::create(['name' => 'CBC']);
        $glucose = LabTest::create(['name' => 'Blood Glucose']);

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'clinic_center_id' => $center->id,
            'type' => 'lab',
            'start_at' => now()->addDay()->setTime(11, 0),
            'status' => 'pending',
            'price' => 35,
        ]);

        $appointment->labTests()->attach([$cbc->id, $glucose->id]);

        Sanctum::actingAs($patientUser);

        $this->getJson("/api/appointment_details/{$appointment->id}")
            ->assertOk()
            ->assertJsonPath('data.appointment.selected_radiology_image_type', null)
            ->assertJsonPath('data.appointment.selected_lab_tests.0.lab_test_id', $cbc->id)
            ->assertJsonPath('data.appointment.selected_lab_tests.0.name', 'CBC')
            ->assertJsonPath('data.appointment.selected_lab_tests.1.lab_test_id', $glucose->id)
            ->assertJsonPath('data.appointment.selected_lab_tests.1.name', 'Blood Glucose');
    }

    private function createPatient(): array
    {
        $user = User::factory()->create([
            'name' => 'Patient',
            'last_name' => 'User',
        ]);
        $user->assignRole('patient');

        $patient = Patient::create([
            'user_id' => $user->id,
            'address' => 'Damascus',
            'gender' => 'female',
            'weight' => 64,
            'height' => 168,
            'marital_status' => 'single',
            'has_children' => false,
            'birth_date' => '1995-01-01',
            'is_smoke' => false,
            'chronic_diseases' => 'none',
            'permanent_medications' => 'none',
            'favorite_foods' => 'rice',
            'disliked_foods' => 'none',
            'food_allergies' => 'none',
            'blood_type' => 'O+',
        ]);

        return [$user, $patient];
    }

    private function createDoctor(string $specializationName = 'Radiology'): Doctor
    {
        $doctorUser = User::factory()->create([
            'name' => $specializationName,
            'last_name' => 'Doctor',
        ]);
        $doctorUser->assignRole('doctor');

        $specialization = Specialization::create([
            'name' => $specializationName,
        ]);

        return Doctor::create([
            'user_id' => $doctorUser->id,
            'specialization_id' => $specialization->id,
            'experience_years' => 7,
            'doctor_type' => strtolower($specializationName) === 'lab' ? 'lab' : 'radiology',
        ]);
    }

    private function createCenter(): ClinicCenter
    {
        return ClinicCenter::create([
            'user_id' => User::factory()->create()->id,
            'name' => 'Center One',
            'address' => 'Damascus',
        ]);
    }
}
