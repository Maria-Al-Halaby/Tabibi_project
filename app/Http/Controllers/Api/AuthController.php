<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function  register(Request $request)  
    {
        $request->validate([
            "first_name" => "required|string|max:100", 
            "last_name" => "required|string|max:100" , 
            "phone" => "required|string|max:15|unique:users,phone" ,
            "password" => "required|string|min:7" , 
            "address" => "required|string" , 
            "gender" => "required|string|in:male,female" , 
            "weight" => "required|numeric" ,
            "height" => "required|numeric", 
            "marital_status" => "required|string|in:single,married,divorced,widowed", 
            "has_children" => "required|boolean" , 
            "number_of_children" => "nullable|integer|min:0" , 
            "birth_date" => "required|date", 
            "is_smoke" => "required|boolean"
        ]);

        $user = User::create([
            "name" => $request->first_name , 
            "last_name" => $request->last_name , 
            "phone" => $request->phone , 
            "password" => Hash::make($request->password) , 
        ]);

        $user->assignRole("patient");

        $patient =  Patient::create([
            "address" => $request->address , 
            "gender" => $request->gender , 
            "weight" => $request->weight , 
            "height" => $request->height , 
            "marital_status" => $request->marital_status , 
            "has_children" => $request->has_children , 
            "number_of_children" => ($request->number_of_children) ?? null ,
            "birth_date" => $request->birth_date ,
            "user_id" => $user->id , 
            "is_smoke" => $request->is_smoke
        ]);

        $token = $user->createToken("api-token")->plainTextToken;

        return response()->json(["message"=> "Patient registered successfully." , 
        "patient_basic_info" => [
            "id" => $user->id , 
            "first_name" =>  $user->name , 
            "last_name" => $user->last_name , 
            "phone" => $user->phone , 
            "role" => "patient"
        ] , 
        "patient_more_info" =>  $patient,
        "token" => $token] , 201);
        
        
    }

    public function login(Request $request)
    {
        $request->validate([
            "phone" => "required|string|max:15" , 
            "password" => "required|string"
        ]);

        $user = User::where("phone" , $request->phone)->first();

        if(empty($user) || !Hash::check(   $request->password , $user->password))
        {
            return response()->json(["error" => "Invalid phone/password!!"] , 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken("api-token")->plainTextToken;
        
        if($user->hasRole("doctor"))
        {
            $docotr = Doctor::where("user_id" , $user->id)->first();

            return response()->json([ "message" =>"welcome back doctor ,$user->name" , 
            "role" => "doctor",
            "token" => $token] , 200);
        }
        elseif($user->hasRole("patient"))
        {
            $patient = Patient::where("user_id" , $user->id)->first();
            return response(["message" =>"welcome back $user->name" ,
            "token" => $token ,
            "role" => "patient"] , 200);
        }
        else
        {
            return response()->json(["error" => "Invalid login!!" ] , 403);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if (!$user) 
        {
        return response()->json(["error" => "Unauthenticated."], 401);
        }


        $request->user()->currentAccessToken()->delete();

        return response()->json(["message" => "logged out successfully!"], 200);
    }
}
