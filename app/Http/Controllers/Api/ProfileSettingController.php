<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileSettingController extends Controller
{
    public function update(Request $request ) 
    {
        //dd($request->file('profile_image'), $request->hasFile('profile_image'));

        $User = $request->user();
        $patient = $User->patient;

        $userId = $patient->user_id;

        /*  Log::info('ALL REQUEST DATA', $request->all());
        Log::info('PROFILE IMAGE OBJECT', ['file' => $request->file('profile_image')]);
        Log::info('HAS FILE?', ['hasFile' => $request->hasFile('profile_image')]); */

        $request->validate([
            "profile_image" => "nullable|image" , 
            "first_name" => "required|string|max:100" , 
            "last_name" => "required|string|max:100" , 
            "email" => "required|email|unique:users,email," . $userId,
            "phone" => "required|string|max:15|unique:users,phone," . $userId,
            "password" => "nullable|string|min:7", 
            "address" => "required|string" , 
            "weight" => "required|numeric" ,
            "height" => "required|numeric" , 
            "marital_status" => "required|string|in:single,married,divorced,widowed", 
            "is_smoke" => "required|boolean"
        ]);


        $user = User::where("id" , $userId)->first();


        if($request->hasFile("profile_image"))
        {
            $path = $request->file("profile_image")->store("patients/patients_profile_image" , "public");
            $image_path = "storage/" . $path;


        }else
        {
            $image_path = $user->profile_image;
        }


        $user->update([
            "name" => $request->first_name , 
            "last_name" => $request->last_name , 
            "phone" => $request->phone , 
            "email" => $request->email , 
            "password" => $request->filled("password") ? Hash::make($request->password) : $user->password, 
            "profile_image" => $image_path
        ]);

        $patient->update([
            "address" => $request->address ,
            "weight" => $request->weight , 
            "height" => $request->height , 
            "marital_status" => $request->marital_status , 
            "is_smoke" => $request->is_smoke , 
        ]);
        

        return response()->json(["message" => "information updated successfully!!" , 
        "patient_basic_info_after_update" => [
            "id" => $user->id , 
            "first_name" =>  $user->name , 
            "last_name" => $user->last_name , 
            "phone" => $user->phone , 
            "profile_image" => $user->profile_image,  
            "role" => "patient"
        ] , 
        "patient_more_info_after_update" =>  $patient] , 200);
    }


    public function get_profile(Request $request) 
    {
        $user = $request->user();

        $patient = $user->patient;

        return response()->json(["message" => "profile information" , 
        "patient_basic_info" => [
            "id" => $user->id , 
            "first_name" =>  $user->name , 
            "last_name" => $user->last_name , 
            "phone" => $user->phone , 
            "profile_image" => $user->profile_image,  
            "role" => "patient"
        ] , 
        "patient_more_info" =>  $patient] , 200);
    }

    public function delete_account(Request $request)
    {

        $user = $request->user(); 

        $patient = $user->patient;

        $user->tokens()->delete();

        if ($patient) 
        {
            $patient->delete();
        }

        $user->delete();

        return response()->json([
        "message" => "Account deleted successfully"
        ], 200);
    }
}


