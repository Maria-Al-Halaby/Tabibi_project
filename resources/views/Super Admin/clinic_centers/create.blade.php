@extends('layouts.app')


@section('title', 'add new clinic center')

@section('content')
    <form action="{{ route('SuperAdmin.clinic_center.store') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="enter clinic center name">
        <input type="text" name="phone" placeholder="enter clinic center phone">
        <input type="email" name="email" placeholder="enter clinic center email">
        <input type="password" name="password" placeholder="enter clinic center password">
        <input type="text" name="address" placeholder="enter clinic center address">
        <input type="submit" value="send">
    </form>

@endsection
