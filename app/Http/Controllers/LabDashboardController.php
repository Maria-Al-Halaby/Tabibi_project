<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LabDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $doctor = auth()->user()?->doctor;

            if (!$doctor || $doctor->doctor_type !== 'lab') {
                abort(403, 'Unauthorized');
            }

            return $next($request);
        });
    }

    public function index()
    {
        $doctor = auth()->user()->doctor;

        $appointments = Appointment::with([
            'patient.user',
            'clinic_center',
            'labTests',
        ])
        ->where('doctor_id', $doctor->id)
        ->where('type', 'lab')
        ->where('status', 'pending')
        ->orderBy('start_at', 'asc')
        ->get();

        return view('lab.index', compact('appointments'));
    }

    public function showCompleteForm(Appointment $appointment)
    {
        $doctor = auth()->user()->doctor;

        if ($appointment->type !== 'lab' || $appointment->doctor_id !== $doctor->id) {
            abort(404);
        }

        $appointment->load([
            'patient.user',
            'clinic_center',
            'labTests',
        ]);

        return view('lab.complete', compact('appointment'));
    }

    public function complete(Request $request)
    {
        $doctor = auth()->user()->doctor;

        $data = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'result_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:6000',
            'notes' => 'nullable|string|max:2000',
        ]);

        $appointment = Appointment::where('id', $data['appointment_id'])
            ->where('doctor_id', $doctor->id)
            ->where('type', 'lab')
            ->firstOrFail();

        if ($appointment->status === 'canceled') {
            return back()->with('error', 'Cannot complete a canceled appointment.');
        }

        if ($appointment->status === 'completed') {
            return back()->with('error', 'Appointment already finished.');
        }

        if ($appointment->labResult) {
            return back()->with('error', 'Lab result already uploaded.');
        }

        DB::transaction(function () use ($appointment, $request, $data) {
            $filePath = $request->file('result_file')->store('lab_results', 'public');

            $appointment->update([
                'status' => 'completed',
                'end_at' => now(),
            ]);

            $appointment->labResult()->create([
                'result_file' => $filePath,
                'notes' => $data['notes'] ?? null,
            ]);
        });

        return redirect()
            ->route('lab.dashboard')
            ->with('success', 'Lab appointment completed successfully.');
    }
}
