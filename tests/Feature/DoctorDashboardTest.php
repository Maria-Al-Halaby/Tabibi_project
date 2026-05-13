<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\AppointmentMedicalRecord;
use App\Models\ClinicCenter;
use App\Models\Doctor;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\PatientMedicalRecord;
use App\Models\Specialization;
use App\Models\TypeOfMedicalImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DoctorDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_general_doctor_can_view_dashboard_and_filter_appointments(): void
    {
        [$doctorUser, $doctor, $center] = $this->createDoctorContext();
        $patient = $this->createPatient();

        $todayAppointment = $this->createAppointment($doctor, $center, $patient, now()->setTime(9, 0));
        $this->createAppointment($doctor, $center, $patient, now()->addDay()->setTime(9, 0));
        $pastAppointment = $this->createAppointment($doctor, $center, $patient, now()->subDay()->setTime(9, 0));

        $this->actingAs($doctorUser)
            ->get(route('doctor.dashboard', ['date_filter' => 'today', 'center_id' => $center->id]))
            ->assertOk()
            ->assertSee('Doctor Dashboard')
            ->assertSee('Dr. General Doctor')
            ->assertSee('Clinical appointment #'.$todayAppointment->id)
            ->assertDontSee('Clinical appointment #'.($todayAppointment->id + 1))
            ->assertDontSee('Clinical appointment #'.$pastAppointment->id);

        $this->actingAs($doctorUser)
            ->get(route('doctor.dashboard', [
                'date_filter' => 'specific_day',
                'specific_date' => now()->subDay()->toDateString(),
            ]))
            ->assertOk()
            ->assertDontSee('Clinical appointment #'.$pastAppointment->id);
    }

    public function test_general_doctor_can_complete_appointment_with_prescription_and_requests(): void
    {
        [$doctorUser, $doctor, $center] = $this->createDoctorContext();
        $patient = $this->createPatient();
        $appointment = $this->createAppointment($doctor, $center, $patient, now()->setTime(10, 0));
        $labTest = LabTest::create(['name' => 'CBC']);
        $imageType = TypeOfMedicalImage::create(['name' => 'Chest X-Ray']);
        $record = PatientMedicalRecord::create([
            'patient_id' => $patient->id,
            'type' => 'lab',
            'title' => 'Previous CBC',
            'record_date' => now()->subMonth()->toDateString(),
            'file_path' => 'patient_medical_records/previous-cbc.pdf',
        ]);
        AppointmentMedicalRecord::create([
            'appointment_id' => $appointment->id,
            'record_source' => 'patient_medical_record',
            'record_id' => $record->id,
        ]);

        $this->actingAs($doctorUser)
            ->get(route('doctor.appointments.complete.form', $appointment))
            ->assertOk()
            ->assertSee('Complete Clinical Appointment')
            ->assertSee('Previous CBC')
            ->assertSee('CBC')
            ->assertSee('Chest X-Ray');

        $this->actingAs($doctorUser)
            ->post(route('doctor.appointments.complete'), [
                'appointment_id' => $appointment->id,
                'note' => 'Patient is stable.',
                'prescription_note' => 'Take medicines after food.',
                'prescription_items' => [
                    [
                        'medicine_name' => 'Paracetamol',
                        'dose' => '500mg',
                        'frequency' => 'Twice daily',
                        'start_date' => now()->toDateString(),
                        'end_date' => now()->addDays(3)->toDateString(),
                        'instructions' => 'After food',
                    ],
                ],
                'lab_request_note' => 'Check baseline results.',
                'lab_tests' => [$labTest->id],
                'radiology_requests' => [
                    [
                        'type_of_medical_image_id' => $imageType->id,
                        'notes' => 'Chest pain follow-up',
                    ],
                ],
            ])
            ->assertRedirect(route('doctor.dashboard'));

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'completed',
            'doctor_note' => 'Patient is stable.',
        ]);

        $this->assertNotNull($appointment->fresh()->end_at);

        $this->assertDatabaseHas('prescriptions', [
            'appointment_id' => $appointment->id,
            'general_note' => 'Take medicines after food.',
        ]);

        $this->assertDatabaseHas('prescription_items', [
            'medicine_name' => 'Paracetamol',
            'dose' => '500mg',
        ]);

        $this->assertDatabaseHas('doctor_lab_requests', [
            'appointment_id' => $appointment->id,
            'notes' => 'Check baseline results.',
        ]);

        $this->assertDatabaseHas('doctor_radiology_requests', [
            'appointment_id' => $appointment->id,
            'type_of_medical_image_id' => $imageType->id,
            'notes' => 'Chest pain follow-up',
        ]);
    }

    public function test_general_doctor_can_cancel_own_pending_appointment(): void
    {
        [$doctorUser, $doctor, $center] = $this->createDoctorContext();
        $appointment = $this->createAppointment($doctor, $center, $this->createPatient(), now()->setTime(11, 0));

        $this->actingAs($doctorUser)
            ->post(route('doctor.appointments.cancel', $appointment))
            ->assertRedirect();

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'canceled',
        ]);
    }

    private function createDoctorContext(): array
    {
        Role::findOrCreate('doctor', 'web');

        $doctorUser = User::factory()->create([
            'name' => 'General',
            'last_name' => 'Doctor',
        ]);
        $doctorUser->assignRole('doctor');

        $specialization = Specialization::create(['name' => 'Internal Medicine']);

        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'specialization_id' => $specialization->id,
            'doctor_type' => 'doctor',
            'experience_years' => 8,
        ]);

        $center = ClinicCenter::create([
            'user_id' => User::factory()->create()->id,
            'name' => 'Main Center',
            'address' => 'Damascus',
        ]);

        $center->doctors()->attach($doctor->id, ['price' => 100]);

        return [$doctorUser, $doctor, $center];
    }

    private function createPatient(): Patient
    {
        $user = User::factory()->create([
            'name' => 'Patient',
            'last_name' => 'User',
        ]);

        return Patient::create([
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
    }

    private function createAppointment(Doctor $doctor, ClinicCenter $center, Patient $patient, $startAt): Appointment
    {
        return Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'clinic_center_id' => $center->id,
            'type' => 'doctor',
            'start_at' => $startAt,
            'status' => 'pending',
            'price' => 100,
        ]);
    }
}
