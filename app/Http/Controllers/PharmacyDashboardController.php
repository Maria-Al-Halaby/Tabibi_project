<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;

class PharmacyDashboardController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::with([
            'appointment.patient.user',
            'appointment.doctor.user',
            'items',
        ])
        ->latest()
        ->get();

        return view('pharmacy.index', compact('prescriptions'));
    }

    public function show(Prescription $prescription)
    {
        $prescription->load([
            'appointment.patient.user',
            'appointment.doctor.user',
            'items',
        ]);

        return view('pharmacy.show', compact('prescription'));
    }

    public function updateStatus(Request $request, Prescription $prescription)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,ready,dispensed',
        ]);

        $prescription->update([
            'status' => $data['status'],
        ]);

        return redirect()
            ->route('pharmacy.prescriptions.show', $prescription->id)
            ->with('success', 'Prescription status updated successfully.');
    }
}
