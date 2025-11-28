<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function ShowLoginPage()  {
        return view("Auth.login");
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email" , 
            "password" => "required|string"
        ]);

        if(Auth::attempt(["email" => $request->email , "password" => $request->password]))
        {
            $request->session()->regenerate();

            /* return redirect()->route("SuperAdmin.Detials.index"); */
            
            if(auth()->user()->hasRole("super admin"))
            {
                return redirect()->route("SuperAdmin.Detials.index");
            }
            elseif(auth()->user()->hasRole("admin") || auth()->user()->hasRole("secretary"))
            {
                return redirect()->route("Admin.index");
            }
        }

        return back()->withErrors(["message" => "Invalid email/password"]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route("login")->with("message" , "logged out!!");
    }
}
