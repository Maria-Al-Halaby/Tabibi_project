<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequesst;
use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    private $otp ;

    public function __construct()
    {
        $this->otp = new Otp();
    }


    public function resetPassword(ResetPasswordRequesst $request)
    {
        $otp2 = $this->otp->validate($request->email , $request->otp);

        if(!$otp2->status)
        {
            return response()->json(["message" => $otp2 , 
            "status" => false] , 401);
        }
        $user = User::where("email" , "=" , $request->email)->first();

        $user->update([
            "password" => Hash::make($request->password)
        ]);

        return response()->json(["message" => "success" ,
        "status" => true] , 200);
    }
}
