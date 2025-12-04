<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    /* public function  register(Request $request)  
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
        "status" => true ,
        "patient information : " => [
            "id" => $user->id , 
            "first_name" =>  $user->name , 
            "last_name" => $user->last_name , 
            "phone" => $user->phone , 
            "role" => "patient" , 
            "more info" => $patient , 
            "token" => $token
        ]], 201);
        
        
    } */


    public function register(Request $request)  
{
    $request->validate([
        "first_name"        => "required|string|max:100", 
        "last_name"         => "required|string|max:100" , 
        "phone"             => "required|string|max:15|unique:users,phone" ,
        "password"          => "required|string|min:7" , 
        "address"           => "required|string" , 
        "gender"            => "required|string|in:male,female" , 
        "weight"            => "required|numeric" ,
        "height"            => "required|numeric", 
        "marital_status"    => "required|string|in:single,married,divorced,widowed", 
        "has_children"      => "required|boolean" , 
        "number_of_children"=> "nullable|integer|min:0" , 
        "birth_date"        => "required|date", 
        "is_smoke"          => "required|boolean"
    ]);

    DB::beginTransaction();

    try {
        $user = User::create([
            "name"     => $request->first_name, 
            "last_name"=> $request->last_name, 
            "phone"    => $request->phone, 
            "password" => Hash::make($request->password), 
        ]);

        $user->assignRole("patient");

        $patient = Patient::create([
            "address"           => $request->address, 
            "gender"            => $request->gender, 
            "weight"            => $request->weight, 
            "height"            => $request->height, 
            "marital_status"    => $request->marital_status, 
            "has_children"      => $request->has_children, 
            "number_of_children"=> $request->number_of_children ?? null,
            "birth_date"        => $request->birth_date,
            "user_id"           => $user->id, 
            "is_smoke"          => $request->is_smoke,
        ]);

        $token = $user->createToken("api-token")->plainTextToken;

        DB::commit();

        return response()->json([
            "message"  => "Patient registered successfully.",
            "status"   => true,
            "user"  => [
                "main_data " => [
                        "id"         => $user->id, 
                        "first_name" => $user->name, 
                        "last_name"  => $user->last_name, 
                        "phone"      => $user->phone, 
                        "role"       => "patient",
                        "token" => $token
                ],
                "more_data" => $patient
            ]
        ], 201);

    } catch (\Throwable $e) {
        DB::rollBack();

        return response()->json([
            "message" => "Registration failed.",
            "status"  => false,
            "error"   => $e->getMessage()       
        ], 500);
    }
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
            return response()->json(["error" => "Invalid phone/password!!" ,
            "status" => false] , 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken("api-token")->plainTextToken;
        
        if($user->hasRole("doctor"))
        {
            $docotr = Doctor::where("user_id" , $user->id)->first();

            return response()->json([ "message" =>"welcome back doctor ,$user->name" , 
            "status" => true , 
            "user" => [
                "main_data" => [
                    "role" => "doctor",
                    "token" => $token
                ],
                "more_data" =>  null
                ]
            ] , 200);
        }
        elseif($user->hasRole("patient"))
        {
            $patient = Patient::where("user_id" , $user->id)->first();
            return response(["message" =>"welcome back $user->name" ,
            "status" => true , 
            "user" => [
                "main_data" => [
                "token" => $token ,
                "role" => "patient"
                ],
                "more_data" => null
            ]
                ] , 200);
        }
        else
        {
            return response()->json(["message" => "Invalid login!!",
                                            "status" => false 
                                        ] , 403);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if (!$user) 
        {
        return response()->json(["message" => "Unauthenticated." , 
        "status" => false ], 401);
        }


        $request->user()->currentAccessToken()->delete();

        return response()->json(["message" => "logged out successfully!" , 
        "status" =>true], 200);
    }
}
