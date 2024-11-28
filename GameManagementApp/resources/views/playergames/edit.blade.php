@extends('layout')

@section('content')
<h1>Játékos-Játék módosítása</h1>
@include('error')
{{-- <form action="{{ route('playergames.update', $playerGame) }}" method="POST"> --}}
<form action="{{ route('playergames.update', $playerGame) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Player -->
    <p>Players name: {{ $player->username }} (ID: {{ $player->playerID }})</p>

    <!-- Game -->
    <p>Game: {{ $game->name }} (ID: {{ $game->gameID }})</p>

    <!-- Include hidden inputs for playerID and gameID -->
<input type="hidden" name="playerID" value="{{ $playerGame->playerID }}">
<input type="hidden" name="gameID" value="{{ $playerGame->gameID }}">

    <!-- GamerTag -->
    <label for="gamerTag">Gamer Tag (Játékosnév játékonként): *</label>
    <input type="text" name="gamerTag" value="{{$playerGame->gamerTag}}" required><br>

    <!-- Hours Played -->
    <label for="hoursPlayed">Hours played:</label>
    <input type="number" name="hoursPlayed" value="{{$playerGame->hoursPlayed}}"><br>

    <!-- Last Played Date -->
    <label for="lastPlayedDate">Last Played Date:</label>
    <input type="date" name="lastPlayedDate" value="{{$playerGame->lastPlayedDate}}"><br>

    <!-- Join Date -->
    <label for="joinDate">Join Date: *</label>
    <input type="date" name="joinDate" value="{{$playerGame->joinDate}}" required><br>

    <!-- Current Level -->
    <label for="currentLevel">Current Level: </label>
    <input type="number" name="currentLevel" value="{{$playerGame->currentLevel}}"><br>

    <button type="submit">Módosítás</button>
</form>
@endsection
