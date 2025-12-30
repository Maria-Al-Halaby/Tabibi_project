<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Appointments;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
    $center = auth()->user()->clinic_center;

    $appointments = Appointment::where('clinic_center_id', $center->id)
        ->where('status', 'pending')
        ->latest() 
        ->get();

    return view('Admin.Appointment.index', compact('appointments'));
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointments)
    {
        
    }

    public function cancel(Appointment $appointments) {
        $appointments->status = "canceled";

        $appointments->update([
            "status" => $appointments->status
        ]);


        return redirect()->route("Admin.Appointment.index")->with("message" , "appointment canceled successfully!!");

    }
}
