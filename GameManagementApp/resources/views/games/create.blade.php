@extends('layout')

@section('title')
{{ isset($game) ? 'Játék módosítása' : 'Új játék hozzáadása' }}
@endsection

@section('content')
<h1>{{ isset($game) ? 'Játék módosítása' : 'Új játék hozzáadása' }}</h1>
@include('error')
<form action="{{ isset($game) ? route('games.update', $game->gameID) : route('games.store') }}" method="POST">
    @csrf
    @if (isset($game))
        @method('PUT')
    @endif

    <label>Game Name: *</label>
    <input type="text" name="name" value="{{ $game->name ?? old('name') }}" required><br>

    <label>Type:</label>
    <input type="text" name="type" value="{{ $game->type ?? old('type') }}"><br>

    <label>Level Count: *</label>
    <input type="number" name="levelCount" value="{{ $game->levelCount ?? old('levelCount') }}" required><br>

    <label>Description:</label>
    <textarea name="description">{{ $game->description ?? old('description') }}</textarea><br>

    <button type="submit">{{ isset($game) ? 'Módosítás' : 'Mentés' }}</button>
</form>
@endsection