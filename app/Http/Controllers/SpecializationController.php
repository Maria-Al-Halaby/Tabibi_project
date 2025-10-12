<?php

namespace App\Http\Controllers;

use App\Models\Specialization;
use Illuminate\Http\Request;
use Symfony\Component\CssSelector\Node\Specificity;

class SpecializationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specializations = Specialization::all();

        return view("Super Admin.specializations.index" , compact("specializations"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("Super Admin.specializations.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(["name" => "required|string"]);

        Specialization::create([
            "name" => $request->name
        ]);

        return redirect()->route("SuperAdmin.specialization.index")->with("message" , "specialization created successfully!!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Specialization $specialization)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Specialization $specialization)
    {
        return view("Super Admin.specializations.edit" , compact("specialization"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Specialization $specialization)
    {
        $request->validate(["name" => "required|string"]);

        $specialization->update(["name" => $request->name]);

        return redirect()->route("SuperAdmin.specialization.index")->with("message" , "specializtion updated successfully!!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialization $specialization)
    {
        $specialization->delete();

        return redirect()->route("SuperAdmin.specialization.index")->with("message" , "specialization deleted successfully!!");
    }
}
