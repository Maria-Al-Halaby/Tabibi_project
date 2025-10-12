<?php

namespace App\Http\Controllers;

use App\Models\ClinicCenter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClinicCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clinicCenters= ClinicCenter::all();

        return view("Super Admin.clinic_centers.index" , compact("clinicCenters"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("Super Admin.clinic_centers.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255", 
            "email" => "required|email|unique:users,email", 
            "phone" => "required|string|unique:users,phone", 
            "password" => "required|string|min:7" , 
            "address" => "required|string"
        ]);

        $user = User::create([
            "name" => $request->name , 
            "email" => $request->email , 
            "phone" => $request->phone , 
            "password" => Hash::make($request->password) 
        ]);

        $user->assignRole("admin");

        ClinicCenter::create([
            "user_id" => $user->id , 
            "name" => $request->name , 
            "address" => $request->address
            
        ]);

        return redirect()->route("SuperAdmin.ClinicCenter.index")->with("message" , "clinic center created successfully!!");
    }

    /**
     * Display the specified resource.
     */
    public function show(ClinicCenter $clinicCenter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClinicCenter $clinicCenter)
    {
        return view("Super Admin.clinic_centers.update" , compact("clinicCenter"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClinicCenter $clinicCenter)
    {
        $userId = $clinicCenter->user_id ; 
        $validateData = $request->validate([
            "name" => "required|string|max:255" , 
            'email'    => ['required','email', Rule::unique('users','email')->ignore($userId)],
            'phone'    => ['required','string', Rule::unique('users','phone')->ignore($userId)],
            "password" => "nullable|min:7" , 
            "address" => "required|string"
        ]);

        $clinicCenter->user->update([
            "name" => $validateData["name"] , 
            "email" => $validateData["email"], 
            "phone" => $validateData["phone"], 
            "password" => empty($validateData["password"]) ? $clinicCenter->user->password 
            : Hash::make($validateData["password"]) 
        ]);

        $clinicCenter->update([
            "address" => $validateData["address"]
        ]);

        return redirect()->route("SuperAdmin.ClinicCenter.index")->with("message" , "clinic center updated successfully!!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClinicCenter $clinicCenter)
    {
        $clinicCenter->delete();

        return redirect()->route("SuperAdmin.ClinicCenter.index")->with("message" , "clinic center deleted successfully");
    }
}
