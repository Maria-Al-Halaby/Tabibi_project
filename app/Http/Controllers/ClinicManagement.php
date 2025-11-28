<?php

namespace App\Http\Controllers;

use App\Models\ClinicCenter;
use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Symfony\Component\CssSelector\Node\Specificity;

class ClinicManagement extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $center = $user->clinic_center;

        $doctors = Doctor::query()
        ->join('clinic_center_doctor', 'doctors.id', '=', 'clinic_center_doctor.doctor_id')
        ->where('clinic_center_doctor.clinic_center_id', $center->id)
        ->with(['user:id,name,profile_image','specialization:id,name'])
        ->select('doctors.*')
        ->paginate(12);

        $specializations = Specialization::all();

        return view("Admin.clinic_centers.index" , compact("doctors" , "specializations"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        

        $request->validate(['specialization_id' => "required"]);

        $specialization_id = $request->query('specialization_id');


        $doctors = Doctor::where("specialization_id" , "=" , $specialization_id)->get();

        return view("Admin.clinic_centers.create" , compact("doctors"));

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
    public function show(Request $request)
    {
        $request->validate(["specialization_id" => "required|integer"]);

        $doctrs = Doctor::where("specialization_id" , $request->specialization_id)->get();

        return redirect()->route("Admin.ClinicManagement.create" , compact("docotrs"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
