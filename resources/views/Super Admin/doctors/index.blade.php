@extends('layouts.app')

@section('doctors')

@section('content')
    @if (session('message'))
        <h1>{{ session('message') }}</h1>
    @endif
    <a href="{{ route('SuperAdmin.doctor.create') }}">add new doctor</a>
    @forelse ($doctors as $doctor)
        <p>{{ $doctor->user->name }}</p>
        <p>{{ $doctor->user->email }}</p>
        <p>{{ $doctor->user->phone }}</p>
        <p>{{ $doctor->specialization->name }}</p>
        <img src="{{ asset($doctor->user->profile_image) }}" alt="doctor profile image">
        <a href="{{ route('SuperAdmin.doctor.edit', $doctor->id) }}">update</a>
        <form action="{{ route('SuperAdmin.doctor.destroy', $doctor->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">delete</button>
        </form>
    @empty
        <h1>there is'nt any doctors yet!!</h1>
    @endforelse

@endsection
