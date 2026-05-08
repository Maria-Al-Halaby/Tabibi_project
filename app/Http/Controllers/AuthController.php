<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function ShowLoginPage()  {
        return view("Auth.login");
    }

    public function ShowDoctorLoginPage()  {
        return view("Auth.doctor-login");
    }

    public function ShowSecretaryLoginPage()
    {
        return view('Auth.secretary-login');
    }

    public function doctorLogin(Request $request)
    {
        $request->validate([
            "phone" => "required|string",
            "password" => "required|string"
        ]);

        $identifier = trim((string) $request->phone);

        $user = User::where("phone", $identifier)
            ->orWhere("email", $identifier)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(["message" => "Invalid phone/password"]);
        }

        if (!$user->hasRole("doctor")) {
            return back()->withErrors(["message" => "This account is not a doctor account"]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        $dashboardRoute = $this->resolveDoctorDashboardRoute($user->doctor?->doctor_type);

        if (!$dashboardRoute) {
            Auth::logout();
            return back()->withErrors(["message" => "This account is not configured for the doctor dashboard"]);
        }

        return redirect()->route($dashboardRoute);
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

            if ($user->hasRole("admin")) {
                return redirect()->route("Admin.index");
            }

            if ($user->hasRole("secretary")) {
                return redirect()->route("secretary.dashboard");
            }

            if ($user->hasRole("pharmacist")) {
                return redirect()->route("pharmacy.dashboard");
            }

            if ($user->hasRole("doctor")) {
                $dashboardRoute = $this->resolveDoctorDashboardRoute($user->doctor?->doctor_type);

                if (!$dashboardRoute) {
                    Auth::logout();
                    return back()->withErrors(["message" => "This account is available only through the mobile app"]);
                }

                return redirect()->route($dashboardRoute);
            }
        }

        return back()->withErrors(["message" => "Invalid email/password"]);
    }

    public function secretaryLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return back()->withErrors(['message' => 'Invalid email/password'])->withInput();
        }

        $request->session()->regenerate();

        $user = auth()->user();

        if (!$user->hasRole('secretary')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['message' => 'This account is not a secretary account'])->withInput();
        }

        return redirect()->route('secretary.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route("login")->with("message" , "logged out!!");
    }

    private function resolveDoctorDashboardRoute(?string $doctorType): ?string
    {
        return match ($doctorType) {
            'radiology' => 'radiology.dashboard',
            'lab' => 'lab.dashboard',
            default => null,
        };
    }
}
