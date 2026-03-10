<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\ClinicCenter;
use App\Models\ClinicCenterDoctor;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;
use App\Models\User;
use App\Models\RadiologyAppointment;
use App\Models\TypeOfMedicalImage;
use App\Models\LabTest;
use Illuminate\Support\Facades\DB;
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

        // =========================
// Radiology appointments
// =========================

// هات نوعي أشعة موجودين
$chestXray = TypeOfMedicalImage::where('name', 'Chest X-Ray')->first();
$ctScan = TypeOfMedicalImage::where('name', 'CT Scan')->first();

// موعد أشعة 1
$radiologyAppointment1 = Appointment::firstOrCreate(
    [
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'clinic_center_id' => $center->id,
        'start_at' => now()->addDays(3),
    ],
    [
        'status' => 'pending',
        'type' => 'radiology',
        'note' => 'Radiology test appointment 1',
        'emergency' => 0,
    ]
);

RadiologyAppointment::firstOrCreate(
    ['appointment_id' => $radiologyAppointment1->id],
    ['type_of_medical_image_id' => $chestXray?->id]
);

// موعد أشعة 2
$radiologyAppointment2 = Appointment::firstOrCreate(
    [
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'clinic_center_id' => $center->id,
        'start_at' => now()->addDays(4),
    ],
    [
        'status' => 'pending',
        'type' => 'radiology',
        'note' => 'Radiology test appointment 2',
        'emergency' => 0,
    ]
);

RadiologyAppointment::firstOrCreate(
    ['appointment_id' => $radiologyAppointment2->id],
    ['type_of_medical_image_id' => $ctScan?->id]
);


// =========================
// Lab appointments
// =========================

// هات بعض التحاليل الموجودة
$cbc = LabTest::where('name', 'CBC')->first();
$vitaminD = LabTest::where('name', 'Vitamin D')->first();
$iron = LabTest::where('name', 'Iron')->first();
$bloodSugar = LabTest::where('name', 'Blood Sugar')->first();

// موعد تحليل 1
$labAppointment1 = Appointment::firstOrCreate(
    [
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'clinic_center_id' => $center->id,
        'start_at' => now()->addDays(5),
    ],
    [
        'status' => 'pending',
        'type' => 'lab',
        'note' => 'Lab test appointment 1',
        'emergency' => 0,
    ]
);

// ربط التحاليل بالموعد الأول
if ($cbc && $vitaminD) {
    DB::table('appointment_lab_tests')->updateOrInsert(
        [
            'appointment_id' => $labAppointment1->id,
            'lab_test_id' => $cbc->id,
        ],
        [
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );

    DB::table('appointment_lab_tests')->updateOrInsert(
        [
            'appointment_id' => $labAppointment1->id,
            'lab_test_id' => $vitaminD->id,
        ],
        [
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );
}

// موعد تحليل 2
$labAppointment2 = Appointment::firstOrCreate(
    [
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'clinic_center_id' => $center->id,
        'start_at' => now()->addDays(6),
    ],
    [
        'status' => 'pending',
        'type' => 'lab',
        'note' => 'Lab test appointment 2',
        'emergency' => 0,
    ]
);

// ربط التحاليل بالموعد الثاني
if ($iron && $bloodSugar) {
    DB::table('appointment_lab_tests')->updateOrInsert(
        [
            'appointment_id' => $labAppointment2->id,
            'lab_test_id' => $iron->id,
        ],
        [
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );

    DB::table('appointment_lab_tests')->updateOrInsert(
        [
            'appointment_id' => $labAppointment2->id,
            'lab_test_id' => $bloodSugar->id,
        ],
        [
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );
}
    }
}