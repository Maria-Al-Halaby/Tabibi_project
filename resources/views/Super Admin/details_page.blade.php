@extends('layouts.app')

@section('title', 'Main Page')

@section('content')
    <h1>Clinic Centers Count: {{ $ClinicCount }}</h1>
    <h1>Doctors Count : {{ $DoctorCount }}</h1>
    <h1>Patients Count : {{ $PatientCount }}</h1>
    <h1>Appointments Count : {{ $AppointmentCount }}</h1>

@endsection
