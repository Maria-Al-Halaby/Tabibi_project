<?php

namespace App\Http\Controllers;

use App\Models\ClinicCenterDoctor;
use App\Models\Doctor;
use App\Models\DoctorSchedules;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class DoctorSchedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Doctor $doctor)
    {
        return view("Admin.doctor_schedule.create" , compact("doctor"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Doctor $doctor , Request $request)
    {
        var_dump($request->day_of_week);

        $data = $request->validate([
        'day_of_week'   => ['required','array','min:1'],
        'day_of_week.*' => ['integer','between:0,6'],
        'start_time'    => ['required','date_format:H:i'],
        'end_time'      => ['required','date_format:H:i','after:start_time'],
        "price" => ["required" ]
        ]);

        $center = auth()->user()->clinic_center;

        $pivot = ClinicCenterDoctor::firstOrCreate([
        'clinic_center_id' => $center->id,
        'doctor_id' => $doctor->id , 
        "price" => $data["price"]
        ]);

        DoctorSchedules::where('clinic_center_doctor_id', $pivot->id)
        ->whereIn('day_of_week', $data['day_of_week'])
        ->delete();

        foreach ($data['day_of_week'] as $day) {
        DoctorSchedules::create([
        'clinic_center_doctor_id' => $pivot->id,
        'day_of_week' => $day,
        'start_time'  => $data['start_time'],
        'end_time'    => $data['end_time'],
        'doctor_id' => $doctor->id
        ]);
        }

        return redirect()->route("Admin.DoctorSchedule.show" , compact("doctor"))->with("message" , "doctor schedule added successfully");

    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $user = auth()->user();

        $center = $user->clinic_center;

        $pivot = ClinicCenterDoctor::where("clinic_center_id", "=" , $center->id )->where("doctor_id" , "=" , $doctor->id)->first();

        $schedules = $pivot ? $pivot->schedules()->orderBy('day_of_week')->get()
            : collect();

        return view("Admin.doctor_schedule.show" , compact("schedules" , "doctor"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        return view("Admin.doctor_schedule.update" , compact("doctor"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor, DoctorSchedules $doctorSchedules)
    {
        $data = $request->validate([
            'day_of_week'   => ['required','array','min:1'],
            'day_of_week.*' => ['integer','between:0,6'],
            'start_time'    => ['required','date_format:H:i'],
            'end_time'      => ['required','date_format:H:i','after:start_time'],
            "price" => ["required"]
        ]);

        $user = auth()->user();
        $center = $user->clinic_center;

    FacadesDB::transaction(function () use ($center, $doctor, $data) {
            $pivot = ClinicCenterDoctor::firstOrCreate(
                ['clinic_center_id' => $center->id, 'doctor_id' => $doctor->id],
                ['is_active' => true, 'hired_at' => now()]
            );

            DoctorSchedules::where('clinic_center_doctor_id', $pivot->id)->delete();

            $rows = [];
            foreach ($data['day_of_week'] as $day) {
                $rows[] = [
                    'clinic_center_doctor_id' => $pivot->id,
                    'day_of_week' => $day,
                    'start_time'  => $data['start_time'],
                    'end_time'    => $data['end_time'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
            DoctorSchedules::insert($rows);
        });


        $pivot = ClinicCenterDoctor::where('clinic_center_id', $center->id)
        ->where('doctor_id', $doctor->id)->first();

        $currentDays = $pivot ? $pivot->schedules()->pluck('day_of_week')->all() : [];

        $firstSchedule = $pivot
        ? $pivot->schedules()->first(['start_time', 'end_time'])
        : null;

        $oldSchedule = $firstSchedule
        ? $firstSchedule->toArray()
        : ['start_time' => '09:00', 'end_time' => '17:00'];

        return redirect()
            ->route('Admin.DoctorSchedule.show', compact("doctor" , "doctorSchedules" , "currentDays"))
            ->with('message', 'All schedules updated successfully.');
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        $center = auth()->user()->clinic_center;

    $pivot = ClinicCenterDoctor::where('clinic_center_id', $center->id)
        ->where('doctor_id', $doctor->id)
        ->first();

    if (! $pivot) {
        return back()->with('message', 'This doctor is not linked to your center.');
    }

    DoctorSchedules::where('clinic_center_doctor_id', $pivot->id)->delete();

    $pivot->delete();

    return redirect()
        ->route('Admin.ClinicManagement.index')
        ->with('message', 'Doctor removed from your center successfully.');
    }
}
