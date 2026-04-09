<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Specialization;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $center = $this->resolveCenterForUser();

        if (!$center) {
            abort(404, 'Center not found for this account');
        }

        $selectedSpecializationId = $request->integer('specialization_id') ?: null;

        $appointments = Appointment::with([
                'patient.user',
                'doctor.user',
                'doctor.specialization',
            ])
            ->where('clinic_center_id', $center->id)
            ->where('status', 'pending')
            ->when($selectedSpecializationId, function ($query) use ($selectedSpecializationId) {
                $query->whereHas('doctor', function ($doctorQuery) use ($selectedSpecializationId) {
                    $doctorQuery->where('specialization_id', $selectedSpecializationId);
                });
            })
            ->orderBy('start_at')
            ->get();

        $specializations = Specialization::whereHas('doctors', function ($doctorQuery) use ($center) {
                $doctorQuery->whereHas('appointments', function ($appointmentQuery) use ($center) {
                    $appointmentQuery->where('clinic_center_id', $center->id)
                        ->where('status', 'pending');
                });
            })
            ->orderBy('name')
            ->get();

        $dashboardMode = $user->hasRole('secretary') ? 'secretary' : 'admin';

        return view('Admin.Appointment.index', compact(
            'appointments',
            'specializations',
            'selectedSpecializationId',
            'dashboardMode',
            'center'
        ));
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

    public function cancel(Appointment $appointments)
    {
        $center = $this->resolveCenterForUser();

        if (!$center || $appointments->clinic_center_id !== $center->id) {
            abort(403);
        }

        if ($appointments->status === 'completed') {
            return redirect()
                ->route($this->appointmentRouteName(), request()->only('specialization_id'))
                ->withErrors(['message' => 'Completed appointments cannot be canceled.']);
        }

        if ($appointments->status !== 'canceled') {
            $appointments->update([
                'status' => 'canceled',
            ]);
        }

        return redirect()
            ->route($this->appointmentRouteName(), request()->only('specialization_id'))
            ->with('message', 'appointment canceled successfully!!');
    }

    private function resolveCenterForUser()
    {
        return auth()->user()?->dashboardCenter();
    }

    private function appointmentRouteName(): string
    {
        return auth()->user()?->hasRole('secretary') ? 'secretary.dashboard' : 'Admin.Appointment.index';
    }
}
