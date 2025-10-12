@extends('layouts.app')

@section('title', 'clinic centers')

@section('content')
    @if (session('message'))
        <h3 style="color: red;">{{ session('message') }}</h3>
    @endif
    <a href="{{ route('SuperAdmin.clinicCenter.create') }}">add new clinic center</a>
    @forelse ($clinicCenters as $clinic_center)
        <p>name : {{ $clinic_center->name }}</p>
        <p>eamil : {{ $clinic_center->user->email }}</p>
        <p>phone : {{ $clinic_center->user->phone }}</p>
        <p>address : {{ $clinic_center->address }}</p>
        <p>is active : {{ $clinic_center->is_active }}</p>
        <a href="{{ route('SuperAdmin.clinic_center.edit', $clinic_center->id) }}">update </a>
        <form action="{{ route('SuperAdmin.clinic_center.destroy', $clinic_center->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="submit" value="delete">
        </form>
    @empty
        <h2>there is'nt clinic centers yet!!</h2>
    @endforelse

@endsection
