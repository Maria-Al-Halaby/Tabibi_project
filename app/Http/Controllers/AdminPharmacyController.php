<?php

namespace App\Http\Controllers;

use App\Models\ClinicCenter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AdminPharmacyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $center = $user->clinic_center;

        if (!$center) {
            abort(404, 'Center not found for this admin');
        }

        $pharmacists = DB::table('clinic_center_pharmacists')
            ->join('users', 'clinic_center_pharmacists.user_id', '=', 'users.id')
            ->where('clinic_center_pharmacists.clinic_center_id', $center->id)
            ->select(
                'users.id',
                'users.name',
                'users.last_name',
                'users.email',
                'users.phone',
                'users.profile_image'
            )
            ->get();

        return view('Admin.pharmacy.index', compact('center', 'pharmacists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'last_name' => 'nullable|string|max:150',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15|unique:users,phone',
            'password' => 'required|string|min:6',
        ]);

        $admin = Auth::user();
        $center = $admin->clinic_center;

        if (!$center) {
            return back()->withErrors(['message' => 'Center not found for this admin']);
        }

        DB::transaction(function () use ($request, $center) {
            $user = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            $user->syncRoles(['pharmacist']);

            DB::table('clinic_center_pharmacists')->insert([
                'clinic_center_id' => $center->id,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()
            ->route('Admin.Pharmacy.index')
            ->with('success', 'Pharmacist added successfully.');
    }

    public function edit(User $user)
    {
        $admin = Auth::user();
        $center = $admin->clinic_center;

        $belongsToCenter = DB::table('clinic_center_pharmacists')
            ->where('clinic_center_id', $center->id)
            ->where('user_id', $user->id)
            ->exists();

        if (!$belongsToCenter || !$user->hasRole('pharmacist')) {
            abort(403);
        }

        return view('Admin.pharmacy.edit', compact('user', 'center'));
    }

    public function update(Request $request, User $user)
    {
        $admin = Auth::user();
        $center = $admin->clinic_center;

        $belongsToCenter = DB::table('clinic_center_pharmacists')
            ->where('clinic_center_id', $center->id)
            ->where('user_id', $user->id)
            ->exists();

        if (!$belongsToCenter || !$user->hasRole('pharmacist')) {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:150',
            'last_name' => 'nullable|string|max:150',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:15|unique:users,phone,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        $user->update([
            'name' => $data['name'],
            'last_name' => $data['last_name'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => !empty($data['password']) ? Hash::make($data['password']) : $user->password,
        ]);

        return redirect()
            ->route('Admin.Pharmacy.index')
            ->with('success', 'Pharmacist updated successfully.');
}

    public function destroy(User $user)
    {
        $admin = Auth::user();
        $center = $admin->clinic_center;

        $belongsToCenter = DB::table('clinic_center_pharmacists')
            ->where('clinic_center_id', $center->id)
            ->where('user_id', $user->id)
            ->exists();

        if (!$belongsToCenter || !$user->hasRole('pharmacist')) {
            abort(403);
        }

        DB::transaction(function () use ($center, $user) {
            DB::table('clinic_center_pharmacists')
                ->where('clinic_center_id', $center->id)
                ->where('user_id', $user->id)
                ->delete();

            $user->delete();
        });

        return redirect()
            ->route('Admin.Pharmacy.index')
            ->with('success', 'Pharmacist deleted successfully.');
    }
}
