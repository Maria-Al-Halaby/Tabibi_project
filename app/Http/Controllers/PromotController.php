<?php

namespace App\Http\Controllers;

use App\Models\Promot;
use Illuminate\Http\Request;

class PromotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promots = Promot::all();

        return view("Super Admin.promot.index" , compact("promots"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("Super Admin.promot.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'information' => ["required" , 'string'] , 
            'image' => ["required" , 'file']
        ]);

        $image = $request->file("image")->store("Promot/Images" , "public");

        $imagePath = "storage/" . $image ;

        $imageCompletePath = url($imagePath);

        Promot::create([
            "information" => $request->information , 
            "image" => $imageCompletePath
        ]);

        return redirect()->route("SuperAdmin.Promot.index")->with("message" , "promot added successfully!!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Promot $promot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promot $promot)
    {
        return view("Super Admin.promot.edit" , compact('promot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promot $promot)
    {
        $request->validate([
            "information" => ["required" , "string"], 
            "image" => ["nullable" , 'file']
        ]);

        $imageCompletePath = null;

        if($request->hasFile("image"))
        {
            $image = $request->file("image")->store("Promot/Images" , "public");

            $imagePath = "storage/" . $image ;

            $imageCompletePath = url($imagePath);
        }
        else
        {
            $imageCompletePath = $promot->image;
        }


        $promot->update([
            'information' => $request->information , 
            "imgage" => $imageCompletePath
        ]);

        return redirect()->route("SuperAdmin.Promot.index")->with("message" , "promot updated successfully!!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promot $promot)
    {
        $promot->delete();

        return redirect()->route("SuperAdmin.Promot.index")->with("message" , "promot deleted successfully!!");
    }
}
