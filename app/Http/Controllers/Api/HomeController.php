<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicCenter;
use App\Models\Doctor;
use App\Models\Promot;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache as FacadesCache;
use Symfony\Component\HttpKernel\Attribute\Cache;

class HomeController extends Controller
{
    public function home()
    {
        $promot = [];

        $randomPromot = Promot::inRandomOrder()->first(['image', 'information']);

        if ($randomPromot) {
            $promot[] = [
                'img'         => $randomPromot->img,
                'information' => $randomPromot->information,
            ];
        }

        $specialties = Specialization::select('id', 'name')->get();

        $doctors = FacadesCache::remember('home_doctors', 60 * 60, function () {
            return Doctor::with(['specialization'])
                ->withAvg('ratings', 'rating')   
                ->orderByDesc('ratings_avg_rating')
                ->inRandomOrder()
                ->take(10)
                ->get()
                ->map(function ($doctor) {
                    return [
                        'id'    => $doctor->id,
                        'name'  => $doctor->user->name,          
                        'img'   => $doctor->user->profile_image, 
                        'rate'  => round($doctor->ratings_avg_rating, 1),  
                        'specialties' => [
                            'id'   => $doctor->specialization?->id,
                            'name' => $doctor->specialization?->name,
                        ],
                    ];
                });
        });

        $centersQuery = ClinicCenter::inRandomOrder();

        $centers = $centersQuery
            ->take(10)
            ->get(['id', 'name', 'address']);

        return response()->json([
            "message" => "get home screen data" , 
            "status" => true , 
            "data" => [
                'promot'      => $promot,
                'specialties' => $specialties,
                'doctors'     => $doctors,
                'centers'     => $centers,
            ]
        ], 200);
    }
}
