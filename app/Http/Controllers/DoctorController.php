<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Contracts\Role;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors= Doctor::all();

        return view("Super Admin.doctors.index" , compact("doctors"));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $specializations = Specialization::all();
        return view("Super Admin.doctors.create" , compact("specializations"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([ "name" => "required|string|max:150",
        "email" => "required|email|unique:users,email",
        "password" => "required|min:7",
        "specialization_id" => "required|exists:specializations,id",
        "profile_image" => "nullable|image"
    ]);

    $imagePath = "";

    if ($request->hasFile('profile_image')) {
        $imagePath = $request->file('profile_image')
        ->store('doctors/doctors_profile_images', 'public'); 
    }
    else
    {
        $imagePath = url('storage/doctors/default_image/doctor.png');
    }


        $user = User::create([
            "name" => $request->name , 
            "email" => $request->email , 
            "password" => Hash::make($request->password), 
            "profile_image" => $imagePath
        ]);

        $user->assignRole("doctor");

        Doctor::create([
            "user_id" => $user->id, 
            "specialization_id" => $request->specialization_id
        ]);

        return redirect()->route("SuperAdmin.doctor.index")->with('message', 'Doctor added successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        $specializations = Specialization::all();
        return view("Super Admin.doctors.update" , compact("doctor" , "specializations"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            "name" => "required|string|max:150",
            "email" => "required|email|unique:users,email," . $doctor->user_id,
            "specialization_id" => "required|exists:specializations,id",
            "password" => "nullable|min:7",
            "phone" => "required|string", 
            "profile_image" => "nullable|image"
        ]);

        $user = $doctor->user;

        $imagePath = $user->profile_image;

        if($request->hasFile("profile_image"))
        {
            $path = $request->file("profile_image")->store('doctors/doctors_profile_images', 'public');

            $imagePath = "storage/". $path;
        }

        $user->update([
            "name" => $data["name"], 
            "email" => $data["email"],
            "phone" => $data["phone"],
            "password" => empty($data["password"]) ? $user->password : Hash::make($data["password"]),
            "profile_image" => $imagePath
        ]);

        $doctor->update([
            "specialization_id" => $data["specialization_id"]
        ]);

        return redirect()->route("SuperAdmin.doctor.index")->with("message" , "doctor updated successfully!!");




    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return redirect()->route("SuperAdmin.doctor.index")->with("message" , "doctor deleted successfully!!");
    }
}
