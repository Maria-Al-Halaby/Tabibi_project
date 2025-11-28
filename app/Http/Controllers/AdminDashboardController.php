<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $center = $user->clinic_center;

        $doctorCount = $center->doctors()->count();

        $appointmentsCount = Appointment::where("clinic_center_id" , $center->id)->count();

        $patientsCount = Patient::whereHas('appointments', function($q) use ($center) {
        $q->where('clinic_center_id', $center->id);})
        ->distinct('patients.id')->count('patients.id');

        $clinicCount = $center->doctors()->distinct('specialization_id')->count('specialization_id');

        return view("Admin.index" , compact(
            "doctorCount" , 
            "appointmentsCount" , 
            "patientsCount" , 
            "clinicCount"
        ));
    }
}
