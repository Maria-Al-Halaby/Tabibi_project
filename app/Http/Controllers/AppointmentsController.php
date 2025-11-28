<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Appointments;
use Illuminate\Http\Request;

class AppointmentsController extends Controller
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
    public function show(Appointments $appointments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointments $appointments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointments $appointments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointments $appointments)
    {
        //
    }
}
