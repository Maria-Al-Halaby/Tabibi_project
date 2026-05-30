<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ClinicCenter;
use App\Models\Doctor;
use App\Models\EventLog;
use App\Models\Patient;
use Illuminate\Http\Request;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $ClinicCount = ClinicCenter::count();
        $DoctorCount = Doctor::count();
        $PatientCount = Patient::count();
        $AppointmentCount = Appointment::count();
        $ActiveClinicCount = ClinicCenter::where('is_active', true)->count();
        $ActiveDoctorCount = Doctor::where('is_active', true)->count();

        return view('Super Admin.details_page', compact(
            'ClinicCount',
            'DoctorCount',
            'PatientCount',
            'AppointmentCount',
            'ActiveClinicCount',
            'ActiveDoctorCount'
        ));
    }

    public function eventLogs(Request $request)
    {
        $query = EventLog::query()
            ->with('user')
            ->latest();

        if ($request->filled('status') && in_array($request->status, ['add', 'delete'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('table_name')) {
            $query->where('table_name', $request->table_name);
        }

        if ($request->filled('user_role')) {
            $query->where('user_role', $request->user_role);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($builder) use ($search) {
                $builder->where('message', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%")
                    ->orWhere('model_id', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        $tables = EventLog::query()
            ->select('table_name')
            ->distinct()
            ->orderBy('table_name')
            ->pluck('table_name');

        $roles = EventLog::query()
            ->whereNotNull('user_role')
            ->select('user_role')
            ->distinct()
            ->orderBy('user_role')
            ->pluck('user_role');

        $totalLogs = EventLog::count();
        $addLogs = EventLog::where('status', 'add')->count();
        $deleteLogs = EventLog::where('status', 'delete')->count();
        $actorCount = EventLog::whereNotNull('user_id')->distinct('user_id')->count('user_id');

        return view('Super Admin.event_logs.index', compact(
            'logs',
            'tables',
            'roles',
            'totalLogs',
            'addLogs',
            'deleteLogs',
            'actorCount'
        ));
    }
}
