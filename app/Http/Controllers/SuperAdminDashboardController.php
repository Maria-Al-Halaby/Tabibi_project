<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ClinicCenter;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $ClinicCount = ClinicCenter::count();
        $DoctorCount = Doctor::count();
        $PatientCount = Patient::count();
        $AppointmentCount = Appointment::count();
        return view("Super Admin.details_page" , compact("ClinicCount" ,
        "DoctorCount",
        "PatientCount",
        "AppointmentCount") );
    }
}
