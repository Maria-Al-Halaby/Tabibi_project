<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicCenter;
use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class GetAllController extends Controller
{
    public function get_all_specialties()  
    {
        $specialties = Specialization::select('id', 'name', 'image')->get();

        return response()->json([ "message" => "get all specialties" , 
                "status" => true , 
                "data" => [
                    "specialties" => $specialties 
                ]
        ], 200);
    }

/*
    public function get_all_doctor($specialization_id=0 , $center_id =0) 
    {

    //$specialtyId = $request->query('specialty_id');  
    //$centerId    = $request->query('center_id');    

    $query = Doctor::with(['user', 'specialization', 'clinic_centers'])
    ->withAvg('ratings', 'rating')   
    ->withCount('ratings');

    if ($specialization_id !=0) {
        $query->where('specialization_id', $specialization_id);
    }

    if($center_id != 0) {
        $query->whereHas('clinic_centers', function ($q) use ($center_id) {
            $q->where('clinic_centers.id', $center_id);
        });
    }

    $doctors = $query->get();

    $data = $doctors->map(function ($doctor) {
        return [
            'id'    => $doctor->id,
            'first_name' => $doctor->user->name,
            'last_name'  => $doctor->user->last_name,
            'phone'      => $doctor->user->phone,
            'email'      => $doctor->user->email,
            'img'        => $doctor->user->profile_image, 
            'rate'       =>  $doctor->ratings_avg_rating ? round($doctor->ratings_avg_rating, 1) : 0 ,        
            'specialty'  => [
                'id'   => $doctor->specialization?->id,
                'name' => $doctor->specialization?->name,
            ],
            'center'     => [
                'id'      => $doctor->center?->id,
                'name'    => $doctor->center?->name,
                'address' => $doctor->center?->address,
            ],
        ];
    });

    return response()->json([
        'doctors' => $data,
    ], 200);

    }

*/

    public function get_all_doctor(Request $request, $specialization_id = 0, $center_id = 0)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'center_name' => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'min:0'],
            'min_experience' => ['nullable', 'integer', 'min:0'],
            'max_experience' => ['nullable', 'integer', 'min:0', 'gte:min_experience'],
        ]);

        $query = Doctor::with(['user', 'specialization', 'clinic_center'])
            ->withAvg('ratings', 'rating')
            ->withCount([
                'ratings',
                'appointments as booked_appointments_count' => function ($query) {
                    $query->where('status', '!=', 'canceled');
                },
            ])
            ->orderByDesc('ratings_avg_rating')
            ->orderByDesc('booked_appointments_count');

        $this->applyDoctorFilters($query, $filters);

        if ($specialization_id != 0) {
            $query->where('specialization_id', $specialization_id);
        }

        if ($center_id != 0) {
            $query->whereHas('clinic_center', function ($q) use ($center_id) {
                $q->where('clinic_centers.id', $center_id);
            });
        }

        $doctors = $query->paginate(10);

        $data = $doctors->getCollection()->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->user->name,
                'image' => $doctor->user->profile_image,
                'rate' => $doctor->ratings_avg_rating ? round($doctor->ratings_avg_rating, 1) : 0,
                'experience_years' => $doctor->experience_years,
                'booked_appointments_count' => $doctor->booked_appointments_count,
                'is_active' => $doctor->clinic_center->isNotEmpty() ? 1 : 0,
                'specialty' => [
                    'name' => $doctor->specialization?->name,
                ],
            ];
        })->values();

        return response()->json([
            "message" => "get all doctors",
            "status" => true,
            "data" => [
                "page_info" => [
                    'current_page' => $doctors->currentPage(),
                    'per_page' => $doctors->perPage(),
                    'last_page' => $doctors->lastPage(),
                    'total' => $doctors->total(),
                ],
                'doctors' => $data,
            ]
        ], 200);
    }


    

    /*    public function get_all_centers()
    {
        $query = ClinicCenter::with(['user']);


        $clinic_centers = $query->get();
    
        return response()->json([
                "center" => $clinic_centers 
        ], 200);
    } */


    public function get_all_centers(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $query = ClinicCenter::with('user');

        $this->applyCenterFilters($query, $filters);

        $centers = $query->paginate(10);

        $data = $centers->getCollection()->map(function ($center) {
            return [
                'id' => $center->id,
                'image' => $center->user->profile_image ?? null,
                'name' => $center->name,
                'address' => $center->address,
            ];
        })->values();

        return response()->json([
            "message" => "get all centers",
            "status" => true,
            "data" => [
                "page_info" => [
                    'current_page' => $centers->currentPage(),
                    'per_page' => $centers->perPage(),
                    'last_page' => $centers->lastPage(),
                    'total' => $centers->total(),
                ],
                'centers' => $data,
            ],
        ], 200);
    }

    private function applyDoctorFilters(Builder $query, array $filters): void
    {
        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function ($doctorQuery) use ($search) {
                $doctorQuery->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                })->orWhereHas('clinic_center', function ($centerQuery) use ($search) {
                    $centerQuery->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        if (!empty($filters['center_name'])) {
            $centerName = trim($filters['center_name']);

            $query->whereHas('clinic_center', function ($centerQuery) use ($centerName) {
                $centerQuery->where('name', 'like', '%' . $centerName . '%');
            });
        }

        if (isset($filters['experience_years'])) {
            $query->where('experience_years', $filters['experience_years']);

            return;
        }

        if (isset($filters['min_experience'])) {
            $query->where('experience_years', '>=', $filters['min_experience']);
        }

        if (isset($filters['max_experience'])) {
            $query->where('experience_years', '<=', $filters['max_experience']);
        }
    }

    private function applyCenterFilters(Builder $query, array $filters): void
    {
        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where('name', 'like', '%' . $search . '%');
        }
    }



}
