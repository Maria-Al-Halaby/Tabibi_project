@extends('layouts.app')

@section('title', 'update doctor information')


@section('content')

    <form action="{{ route('SuperAdmin.doctor.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="text" name="name" placeholder="enter doctor name" value="{{ $doctor->user->name }}">
        <input type="email" name="email" placeholder="enter doctor email" value="{{ $doctor->user->email }}">
        <input type="text" name="phone" placeholder="enter doctor phone" value="{{ $doctor->user->phone }}">

        <input type="password" name="password" placeholder="enter doctor password">
        <label for="chageDoctorImage">change doctor image:
            <input type="file" name="profile_image" style="display: none" id="chageDoctorImage">
            <img src="{{ $doctor->user->profile_image }}" alt="doctor profile image">


            <select name="specialization_id">
                @foreach ($specializations as $specialization)
                    <option value="{{ $specialization->id }}"
                        {{ $specialization->id == $doctor->specialization_id ? 'selected' : '' }}>
                        {{ $specialization->name }}</option>
                @endforeach
            </select>

        </label>

        <input type="submit" value="update information">


    </form>

@endsection
