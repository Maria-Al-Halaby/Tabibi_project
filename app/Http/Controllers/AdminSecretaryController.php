<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSecretaryController extends Controller
{
    public function index()
    {
        $center = Auth::user()->clinic_center;

        if (!$center) {
            abort(404, 'Center not found for this admin');
        }

        $secretaries = DB::table('clinic_center_secretaries')
            ->join('users', 'clinic_center_secretaries.user_id', '=', 'users.id')
            ->where('clinic_center_secretaries.clinic_center_id', $center->id)
            ->select(
                'users.id',
                'users.name',
                'users.last_name',
                'users.email',
                'users.phone',
                'users.profile_image'
            )
            ->get();

        return view('Admin.secretaries.index', compact('center', 'secretaries'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'last_name' => 'nullable|string|max:150',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15|unique:users,phone',
            'password' => 'required|string|min:6',
        ]);

        $center = Auth::user()->clinic_center;

        if (!$center) {
            return back()->withErrors(['message' => 'Center not found for this admin']);
        }

        DB::transaction(function () use ($data, $center) {
            $user = User::create([
                'name' => $data['name'],
                'last_name' => $data['last_name'] ?? null,
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
            ]);

            $user->syncRoles(['secretary']);

            DB::table('clinic_center_secretaries')->insert([
                'clinic_center_id' => $center->id,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()
            ->route('Admin.Secretary.index')
            ->with('success', 'Secretary added successfully.');
    }

    public function edit(User $user)
    {
        $center = Auth::user()->clinic_center;

        $belongsToCenter = DB::table('clinic_center_secretaries')
            ->where('clinic_center_id', $center->id)
            ->where('user_id', $user->id)
            ->exists();

        if (!$belongsToCenter || !$user->hasRole('secretary')) {
            abort(403);
        }

        return view('Admin.secretaries.edit', compact('user', 'center'));
    }

    public function update(Request $request, User $user)
    {
        $center = Auth::user()->clinic_center;

        $belongsToCenter = DB::table('clinic_center_secretaries')
            ->where('clinic_center_id', $center->id)
            ->where('user_id', $user->id)
            ->exists();

        if (!$belongsToCenter || !$user->hasRole('secretary')) {
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
            ->route('Admin.Secretary.index')
            ->with('success', 'Secretary updated successfully.');
    }

    public function destroy(User $user)
    {
        $center = Auth::user()->clinic_center;

        $belongsToCenter = DB::table('clinic_center_secretaries')
            ->where('clinic_center_id', $center->id)
            ->where('user_id', $user->id)
            ->exists();

        if (!$belongsToCenter || !$user->hasRole('secretary')) {
            abort(403);
        }

        DB::transaction(function () use ($center, $user) {
            DB::table('clinic_center_secretaries')
                ->where('clinic_center_id', $center->id)
                ->where('user_id', $user->id)
                ->delete();

            $user->delete();
        });

        return redirect()
            ->route('Admin.Secretary.index')
            ->with('success', 'Secretary deleted successfully.');
    }
}
