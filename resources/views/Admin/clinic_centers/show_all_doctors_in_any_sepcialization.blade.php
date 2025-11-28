@extends('layouts.admin_app')


@section('title', 'All Doctors in Any Specialization')


@section('content')
    
    <h2 class="mb-4">
        <i class="fas fa-users tabibi-text-primary me-2"></i> Comprehensive Doctors Directory
    </h2>
    <hr>
    
    <div class="row g-4">
        
        @forelse ($doctors as $doctor)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        
                        <img src="{{ $doctor->user->profile_image }}" 
                             alt="Doctor Profile Image" 
                             class="rounded-circle mb-3 border border-4 border-light shadow-sm" 
                             style="width: 80px; height: 80px; object-fit: cover;">
                        
                        <h6 class="card-title fw-bold mb-1">{{ $doctor->user->name }}</h6>
                        
                        <p class="text-muted small mb-0">
                            <i class="fas fa-envelope me-1"></i> 
                            {{ $doctor->user->email }}
                        </p>
                        
                    </div>
                    
                    <div class="card-footer bg-light border-0 text-center">
                        <a href="#" class="btn btn-sm btn-outline-secondary rounded-pill w-75">
                            View Profile
                        </a>
                    </div>
                </div>
            </div>
            
        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white shadow-sm rounded-4 border">
                    <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                    <h1 class="h3">There are no doctors registered yet!</h1>
                </div>
            </div>
        @endforelse
    </div>

@endsection