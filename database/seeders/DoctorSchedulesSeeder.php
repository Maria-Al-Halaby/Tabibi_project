<?php

namespace Database\Seeders;

use App\Models\ClinicCenterDoctor;
use App\Models\Doctor;
use App\Models\DoctorSchedules;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSchedulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctor = Doctor::first();

        if (!$doctor) {
            return;
        }

        // هات الربط بين الطبيب والمركز
        $pivot = ClinicCenterDoctor::where('doctor_id', $doctor->id)->first();

        if (!$pivot) {
            return;
        }

        // نضيف دوام من الأحد إلى الخميس
        $schedules = [
            ['day_of_week' => 0, 'start_time' => '09:00:00', 'end_time' => '17:00:00'], // Sunday
            ['day_of_week' => 1, 'start_time' => '09:00:00', 'end_time' => '17:00:00'], // Monday
            ['day_of_week' => 2, 'start_time' => '09:00:00', 'end_time' => '17:00:00'], // Tuesday
            ['day_of_week' => 3, 'start_time' => '09:00:00', 'end_time' => '17:00:00'], // Wednesday
            ['day_of_week' => 4, 'start_time' => '09:00:00', 'end_time' => '17:00:00'], // Thursday
        ];

        foreach ($schedules as $schedule) {
            DoctorSchedules::updateOrCreate(
                [
                    'doctor_id' => $doctor->id,
                    'clinic_center_doctor_id' => $pivot->id,
                    'day_of_week' => $schedule['day_of_week'],
                ],
                [
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                ]
            );
        }
    }
}
