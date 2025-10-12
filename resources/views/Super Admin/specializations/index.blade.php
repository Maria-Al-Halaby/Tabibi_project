@extends('layouts.app')

@section('title', 'specializations')


@section('content')

    @if (session('message'))
        <p style="color: red;">{{ session('message') }}</p>
    @endif
    <a href="{{ route('SuperAdmin.specialization.create') }}">add new specialization</a>
    @forelse ($specializations as $specialization)
        <div>
            <h2>{{ $specialization->name }}</h2>
            <a href="{{ route('SuperAdmin.specialization.edit', $specialization->id) }}">edit specialization</a>
            <form action="{{ route('SuperAdmin.specialization.destroy', $specialization->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="submit" value="delete">
            </form>
        </div>
    @empty
        <h1>there is'nt any specializtion yet!!</h1>
    @endforelse


@endsection
