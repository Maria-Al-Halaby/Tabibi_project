<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PharmacyDashboardController extends Controller
{
    public function index(Request $request)
    {
        
        $user = auth()->user();

        $centerIds = DB::table('clinic_center_pharmacists')
            ->where('user_id', $user->id)
            ->pluck('clinic_center_id');

        $selectedStatus = $request->get('status', 'pending');

        $allowedStatuses = ['pending', 'ready', 'dispensed'];
        if (!in_array($selectedStatus, $allowedStatuses, true)) {
            $selectedStatus = 'pending';
        }

        $pendingCount = Prescription::where('send_to_pharmacy', true)
            ->where('pharmacy_status', 'pending')
            ->whereHas('appointment', function ($q) use ($centerIds) {
                $q->whereIn('clinic_center_id', $centerIds);
            })
            ->count();

        $prescriptions = Prescription::with([
                'appointment.patient.user',
                'appointment.doctor.user',
                'items',
            ])
            ->where('send_to_pharmacy', true)
            ->where('pharmacy_status', $selectedStatus)
            ->whereHas('appointment', function ($q) use ($centerIds) {
                $q->whereIn('clinic_center_id', $centerIds);
            })
            ->latest()
            ->get();

        return view('pharmacy.index', compact(
            'prescriptions',
            'selectedStatus',
            'pendingCount'
        ));

    }

    public function show(Prescription $prescription)
    {
        $user = auth()->user();

        $centerIds = DB::table('clinic_center_pharmacists')
            ->where('user_id', $user->id)
            ->pluck('clinic_center_id');

        if (
            !$prescription->send_to_pharmacy ||
            !in_array($prescription->appointment->clinic_center_id, $centerIds->toArray())
        ) {
            abort(403);
        }

        $prescription->load([
            'appointment.patient.user',
            'appointment.doctor.user',
            'items',
        ]);

        return view('pharmacy.show', compact('prescription'));
    }

    public function updateStatus(Request $request, Prescription $prescription)
    {
        $user = auth()->user();

        $centerIds = DB::table('clinic_center_pharmacists')
            ->where('user_id', $user->id)
            ->pluck('clinic_center_id');

        if (
            !$prescription->send_to_pharmacy ||
            !in_array($prescription->appointment->clinic_center_id, $centerIds->toArray())
        ) {
            abort(403);
        }

        $data = $request->validate([
            'pharmacy_status' => 'required|in:pending,ready,dispensed',
        ]);

        $prescription->update([
            'pharmacy_status' => $data['pharmacy_status'],
        ]);

        return redirect()
            ->route('pharmacy.prescriptions.show', $prescription->id)
            ->with('success', 'Prescription status updated successfully.');
    }
}
