@extends('layouts.app')

@section('title', 'update specialization')


@section('content')
    <form action="{{ route('SuperAdmin.specialization.update', $specialization->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="name" placeholder="enter specialization name" value="{{ $specialization->name }}">
        <input type="submit" value="send">
    </form>

@endsection
