<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorRating;
use Illuminate\Http\Request;

class DoctorRatingController extends Controller
{
    public function index(Request $request)
    {
        $negativeMaxStars = (int) ($request->get('negative_max', 2)); // 1-2 سلبي افتراضيًا
        $minNegativeCount = (int) ($request->get('min_negative', 3)); // 3+ تنبيه افتراضيًا

        $doctors = Doctor::query()
            ->with('user:id,name') 
            ->withCount([
                'ratings as ratings_count',
                'ratings as negative_ratings_count' => function ($q) use ($negativeMaxStars) {
                    $q->where('rating', '<=', $negativeMaxStars);
                },
            ])
            ->withAvg('ratings as avg_rating', 'rating')
            ->having('ratings_count', '>', 0)
            ->orderByDesc('negative_ratings_count')
            ->orderBy('avg_rating')
            ->paginate(20)
            ->withQueryString();

        $ratings = DoctorRating::query()
            ->with([
                'doctor.user:id,name',
                'patient.user:id,name',
            ])
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view("Super Admin.doctor_ratings.index", compact(
            'doctors',
            'ratings',
            'negativeMaxStars',
            'minNegativeCount'
        ));
    }

    public function deactivateDoctor(Doctor $doctor)
    {
        $doctor->update(['is_active' => false]); 

        return back()->with('success', 'doctor not active from now!!');
    }

    public function destroyDoctor(Doctor $doctor)
    {
        $doctor->delete();

        return back()->with('success', 'doctor deleted successfully from our system!!');
    }
}

//test 
