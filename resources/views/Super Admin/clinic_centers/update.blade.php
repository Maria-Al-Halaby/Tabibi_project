@extends('layouts.app')

@section('title', 'update clinic center')


@section('content')
    <form action="{{ route('SuperAdmin.clinic_center.update', $clinicCenter->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="name" placeholder="enter clinic center name" value="{{ $clinicCenter->name }}">
        <input type="email" name="email" placeholder="enter clinic center email" value="{{ $clinicCenter->user->email }}">
        <input type="text" name="phone" placeholder="enter clinic center phone"
            value="{{ $clinicCenter->user->phone }}">
        <input type="text" name="address" placeholder="enter clinic center address" value="{{ $clinicCenter->address }}">
        <input type="password" name="password" placeholder="enter clinic center password">
        <input type="submit" value="update">
    </form>

@endsection
