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
            "email" => "required|email",
            "password" => "required|string"
        ]);

        if (Auth::attempt(["email" => $request->email, "password" => $request->password])) {
            $request->session()->regenerate();

            $user = auth()->user();

            if ($user->hasRole("super admin")) {
                return redirect()->route("SuperAdmin.Detials.index");
            }

            if ($user->hasRole("admin") || $user->hasRole("secretary")) {
                return redirect()->route("Admin.index");
            }

            if ($user->hasRole("pharmacist")) {
                return redirect()->route("pharmacy.dashboard");
            }

            if ($user->hasRole("doctor")) {
                $doctor = $user->doctor;

                if (!$doctor) {
                    Auth::logout();
                    return back()->withErrors(["message" => "Doctor profile not found"]);
                }

                if ($doctor->doctor_type === "radiology") {
                    return redirect()->route("radiology.dashboard");
                }

                if ($doctor->doctor_type === "lab") {
                    return redirect()->route("lab.dashboard");
                }

                Auth::logout();
                return back()->withErrors(["message" => "This account is available only through the mobile app"]);
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
