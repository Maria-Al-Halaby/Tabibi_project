@extends('layouts.app')

@section('title', 'add new specialization')


@section('content')
    <form action="{{ route('SuperAdmin.specialization.store') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="enter specialization name">
        <input type="submit" value="send">
    </form>
@endsection
