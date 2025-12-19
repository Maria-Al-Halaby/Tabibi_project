<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequesst;
use App\Models\User;
use App\Notifications\ResetPasswordVerificationNotification;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    public function forgetPassword(ForgetPasswordRequesst $request)  
    {
        $input = $request->input('email');
        $user = User::where("email" , "=" , $input)->first();
        if(!$user)
        {
            return response()->json(["message" => "User not found" , 
            "status" => false] , 401);
        }
        $user->notify(new ResetPasswordVerificationNotification());
        return response()->json(["message" => "success" , 
        "status" => true] , 200);
    }
}
