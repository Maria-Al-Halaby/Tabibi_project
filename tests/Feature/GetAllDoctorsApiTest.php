<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\ClinicCenter;
use App\Models\Doctor;
use App\Models\DoctorRating;
use App\Models\Patient;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetAllDoctorsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_doctors_orders_by_rate_then_booked_appointments_count(): void
    {
        Role::findOrCreate('patient', 'web');

        $authenticatedPatient = $this->createPatient('Authenticated Patient');
        $authenticatedPatient->user->assignRole('patient');
        Sanctum::actingAs($authenticatedPatient->user);

        $specialization = Specialization::create([
            'name' => 'Cardiology',
        ]);

        $center = ClinicCenter::create([
            'user_id' => User::factory()->create()->id,
            'name' => 'Center One',
            'address' => 'Damascus',
        ]);

        $doctorWithMoreBookings = $this->createDoctor('Doctor More Bookings', $specialization->id);
        $doctorWithFewerBookings = $this->createDoctor('Doctor Fewer Bookings', $specialization->id);
        $doctorWithLowerRate = $this->createDoctor('Doctor Lower Rate', $specialization->id);

        $center->doctors()->attach($doctorWithMoreBookings->id, ['price' => 100]);
        $center->doctors()->attach($doctorWithFewerBookings->id, ['price' => 100]);
        $center->doctors()->attach($doctorWithLowerRate->id, ['price' => 100]);

        $this->createAppointment($authenticatedPatient, $doctorWithMoreBookings, $center, 'pending', now()->addDay()->setTime(9, 0));
        $ratingAppointmentForTopDoctor = $this->createAppointment($this->createPatient('Rate Top Doctor'), $doctorWithMoreBookings, $center, 'completed', now()->addDays(2)->setTime(9, 0));
        $this->createAppointment($this->createPatient('Extra Booking One'), $doctorWithMoreBookings, $center, 'pending', now()->addDays(3)->setTime(9, 0));
        $this->createAppointment($this->createPatient('Extra Booking Two'), $doctorWithMoreBookings, $center, 'pending', now()->addDays(4)->setTime(9, 0));

        $ratingAppointmentForSecondDoctor = $this->createAppointment($this->createPatient('Rate Second Doctor'), $doctorWithFewerBookings, $center, 'completed', now()->addDay()->setTime(10, 0));
        $this->createAppointment($authenticatedPatient, $doctorWithFewerBookings, $center, 'canceled', now()->addDays(2)->setTime(10, 0));

        $ratingAppointmentForThirdDoctor = $this->createAppointment($this->createPatient('Rate Third Doctor'), $doctorWithLowerRate, $center, 'completed', now()->addDay()->setTime(11, 0));

        DoctorRating::create([
            'appointment_id' => $ratingAppointmentForTopDoctor->id,
            'doctor_id' => $doctorWithMoreBookings->id,
            'patient_id' => $ratingAppointmentForTopDoctor->patient_id,
            'rating' => 5,
        ]);

        DoctorRating::create([
            'appointment_id' => $ratingAppointmentForSecondDoctor->id,
            'doctor_id' => $doctorWithFewerBookings->id,
            'patient_id' => $ratingAppointmentForSecondDoctor->patient_id,
            'rating' => 5,
        ]);

        DoctorRating::create([
            'appointment_id' => $ratingAppointmentForThirdDoctor->id,
            'doctor_id' => $doctorWithLowerRate->id,
            'patient_id' => $ratingAppointmentForThirdDoctor->patient_id,
            'rating' => 4,
        ]);

        $response = $this->getJson('/api/get_all_doctors');

        $response->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.doctors.0.id', $doctorWithMoreBookings->id)
            ->assertJsonPath('data.doctors.0.rate', 5.0)
            ->assertJsonPath('data.doctors.0.booked_appointments_count', 4)
            ->assertJsonPath('data.doctors.1.id', $doctorWithFewerBookings->id)
            ->assertJsonPath('data.doctors.1.rate', 5.0)
            ->assertJsonPath('data.doctors.1.booked_appointments_count', 1)
            ->assertJsonPath('data.doctors.2.id', $doctorWithLowerRate->id)
            ->assertJsonPath('data.doctors.2.rate', 4.0)
            ->assertJsonPath('data.doctors.2.booked_appointments_count', 1);
    }

    private function createDoctor(string $name, int $specializationId): Doctor
    {
        $user = User::factory()->create([
            'name' => $name,
        ]);

        return Doctor::create([
            'user_id' => $user->id,
            'specialization_id' => $specializationId,
            'experience_years' => 5,
            'doctor_type' => 'doctor',
        ]);
    }

    private function createPatient(string $name): Patient
    {
        $user = User::factory()->create([
            'name' => $name,
        ]);

        return Patient::create([
            'user_id' => $user->id,
            'gender' => 'female',
            'marital_status' => 'single',
            'birth_date' => '1995-01-01',
        ]);
    }

    private function createAppointment(
        Patient $patient,
        Doctor $doctor,
        ClinicCenter $center,
        string $status,
        $startAt
    ): Appointment {
        return Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'clinic_center_id' => $center->id,
            'type' => 'doctor',
            'start_at' => $startAt,
            'status' => $status,
        ]);
    }
}
