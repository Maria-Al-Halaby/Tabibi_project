@extends('layouts.admin_app')

@section('title', 'Add Doctor Schedule')


@section('content')

    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body p-4">

            <h3 class="mb-1">
                <i class="fas fa-calendar-plus tabibi-text-primary me-2"></i> Add Work Schedule
            </h3>
            <p class="text-muted border-bottom pb-3 mb-4">
                Setting schedule for doctor: <span class="fw-bold tabibi-text-primary">{{ $doctor->user->name }}</span>
                at clinic center: <span class="fw-bold text-success">{{ auth()->user()->name }}</span>
            </p>

            @php
                $days = [
                    0 => 'Sunday',
                    1 => 'Monday',
                    2 => 'Tuesday',
                    3 => 'Wednesday',
                    4 => 'Thursday',
                    5 => 'Friday',
                    6 => 'Saturday',
                ];
                $selectDays = (array) old('day_of_week', []);
            @endphp

            <form action="{{ route('Admin.DoctorSchedule.store', $doctor->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-bold">Select Week Days:</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($days as $key => $value)
                            <input type="checkbox" class="btn-check" name="day_of_week[]" id="day-{{ $key }}"
                                value="{{ $key }}" @checked(in_array($key, $selectDays))>
                            <label class="btn btn-outline-secondary rounded-pill" for="day-{{ $key }}">
                                {{ $value }}
                            </label>
                        @endforeach
                    </div>
                    @error('day_of_week')
                        <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="row mb-4">

                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="start_time" class="form-label fw-bold">Start Time:</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}"
                            class="form-control form-control-lg rounded-pill @error('start_time') is-invalid @enderror">
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="end_time" class="form-label fw-bold">End Time:</label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}"
                            class="form-control form-control-lg rounded-pill @error('end_time') is-invalid @enderror">
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="row mb-4">

                    <div class="col-md-6">
                        <label for="priceInput" class="form-label fw-bold">Price:</label>
                        <input type="number" class="form-control price-input-custom rounded-pill size"  id="priceInput" name="price" placeholder="Enter Price for Appointment" value="{{ old('price') }}"
                            class="form-control form-control-lg rounded-pill @error('price') is-invalid @enderror">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
            

                </div>

                <hr>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-tabibi btn-lg shadow-sm">
                        <i class="fas fa-save me-2"></i> Save Schedule
                    </button>
                </div>





            </form>
        </div>
    </div>

    <style>
        .btn-check:checked+.btn-outline-secondary {
            background-color: var(--tabibi-primary-color);
            border-color: var(--tabibi-primary-color);
            color: white;
            font-weight: bold;
        }

        .btn-check:focus+.btn-outline-secondary {
            box-shadow: 0 0 0 0.25rem rgba(32, 178, 170, 0.25);
            /* ظل بلون Tabibi */
        }

        .size {
            height: 48px;
        }
    </style>



@endsection
