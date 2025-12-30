<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DoctorRating;
use App\Models\Patient;
use Illuminate\Http\Request;

class DoctorRatingController extends Controller
{
/*     public function store(Request $request)
    {
    $data = $request->validate([
        'appointment_id' => 'required|exists:appointments,id',
        'rating'         => 'required|integer|min:1|max:5',
        'comment'        => 'required|string|max:2000',
    ]);

    $patient = Patient::where('user_id', auth()->id())->firstOrFail();

    $appointment = Appointment::where('id', $data['appointment_id'])
        ->where('patient_id', $patient->id)
        ->firstOrFail();

    if ($appointment->status !== 'completed') {
        return response()->json([
            'message' => 'You can rate only after appointment is finished',
            'status'  => false,
        ], 422);
    }

    $exists = DoctorRating::where('appointment_id', $appointment->id)->exists();
    if ($exists) {
        return response()->json([
            'message' => 'Appointment already rated',
            'status'  => false,
        ], 422);
    }

    $rating = DoctorRating::create([
        'appointment_id' => $appointment->id,
        'doctor_id'      => $appointment->doctor_id,
        'patient_id'     => $patient->id,
        'rating'         => $data['rating'],
        'comment'        => $data['comment'] ?? null,
    ]);

    return response()->json([
        'message' => 'Rating submitted',
        'status'  => true,
        'data'    => $rating,
    ], 201);
    } */

/*     public function store(Request $request)
    {
        $data = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'rating'         => 'required|integer|min:1|max:5',
            'comment'        => 'required|string|max:2000',
        ]);

        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        $appointment = Appointment::where('id', $data['appointment_id'])
            ->where('patient_id', $patient->id)
            ->firstOrFail();

        if ($appointment->status !== 'completed') {
            return response()->json([
                'message' => 'You can rate only after appointment is finished',
                'status'  => false,
                'has_rated' => false,
            ], 422);
        }

        $existing = DoctorRating::where('doctor_id', $appointment->doctor_id)
            ->where('patient_id', $patient->id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'You already rated this doctor',
                'status'  => false,
                'has_rated' => true,
                'data' => [
                    'rating_id' => $existing->id,
                    'doctor_id' => $existing->doctor_id,
                    'rating'    => $existing->rating,
                    'comment'   => $existing->comment,
                ]
            ], 409);
        }

        $rating = DoctorRating::create([
            'appointment_id' => $appointment->id,        
            'doctor_id'      => $appointment->doctor_id,
            'patient_id'     => $patient->id,
            'rating'         => $data['rating'],
            'comment'        => $data['comment'] ?? null,
        ]);

        return response()->json([
            'message' => 'Rating submitted',
            'status'  => true,
            'has_rated' => true,
            'data'    => $rating,
        ], 201);
    } */

/*     public function hasRated($doctorId)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        $exists = DoctorRating::where('doctor_id', $doctorId)
            ->where('patient_id', $patient->id)
            ->exists();

        return response()->json([
            'status' => true,
            'has_rated' => $exists,
        ]);
    } */


    public function rateAppointment(Request $request)
    {
    $data = $request->validate([
        'appointment_id' => 'required|exists:appointments,id',
        'rating'         => 'required|integer|min:1|max:5',
        'comment'        => 'required|string|max:2000',
    ]);

    $patient = Patient::where('user_id', auth()->id())->firstOrFail();

    $appointment = Appointment::with('rating')
        ->where('id', $data['appointment_id'])
        ->where('patient_id', $patient->id)
        ->firstOrFail();

    if ($appointment->status !== 'completed') {
        return response()->json([
            'message'   => 'You can rate only after appointment is completed!!',
            'status'    => false,
            'can_rate'  => false,
            'has_rated' => (bool) $appointment->rating,
        ], 422);
    }

    if ($appointment->rating) {
        return response()->json([
            'message'   => 'Appointment already rated',
            'status'    => false,
            'can_rate'  => false,
            'has_rated' => true,
            'data'      => [
                'rating'  => $appointment->rating->rating,
                'comment' => $appointment->rating->comment,
            ]
        ], 409);
    }

    $rating = DoctorRating::create([
        'appointment_id' => $appointment->id,
        'doctor_id'      => $appointment->doctor_id,
        'patient_id'     => $patient->id,
        'rating'         => $data['rating'],
        'comment'        => $data['comment'],
    ]);

    return response()->json([
        'message'   => 'Rating submitted',
        'status'    => true,
        'can_rate'  => false,
        'has_rated' => true,
        'data'      => $rating,
    ], 201);
    }

    public function showAppointmentRating($appointmentId)
    {
    $patient = Patient::where('user_id', auth()->id())->firstOrFail();

    $appointment = Appointment::with('rating')
        ->where('id', $appointmentId)
        ->where('patient_id', $patient->id)
        ->firstOrFail();

    return response()->json([
        'status' => true,
        'data' => [
            'appointment_id' => $appointment->id,
            'has_rated' => (bool) $appointment->rating,
            'can_rate'  => $appointment->status === 'finished' && !$appointment->rating,
            'rating'    => $appointment->rating ? [
                'rating'  => $appointment->rating->rating,
                'comment' => $appointment->rating->comment,
            ] : null,
        ]
    ]);
    }


}
