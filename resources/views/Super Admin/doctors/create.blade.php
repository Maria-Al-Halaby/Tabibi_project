@extends('layouts.app')


@section('title', 'add new doctor')


@section('content')

    <form action="{{ route('SuperAdmin.doctor.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="name" placeholder="enter doctor name">
        <input type="email" name="email" placeholder="enter doctor email">
        <input type="text" name="phone" placeholder="enter doctor phone">
        <input type="password" name="password" placeholder="enter password">
        <input type="file" name="profile_image">
        <select name="specialization_id">
            @foreach ($specializations as $specialization)
                <option value="{{ $specialization->id }}">{{ $specialization->name }}</option>
            @endforeach
        </select>
        <input type="submit" value="send">
    </form>
@endsection
