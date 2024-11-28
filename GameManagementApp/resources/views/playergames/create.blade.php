@extends('layout')

@section('content')
<h1>{{ isset($playerGame) ? 'Játékos-Játék módosítása' : 'Új játékos-játék hozzáadása' }}</h1>
@include('error')
<form
    action="{{ isset($playerGame) ? route('playergames.update', $playerGame) : route('playergames.store') }}"
    method="POST">
    @csrf
    @if (isset($playerGame))
        @method('PUT')
    @endif

    <!-- Player -->
    <label for="playerID">Players name: *</label>
    <select name="playerID" id="playerID" required>
        <option value="">Válassz játékost!</option>
        @foreach ($players as $player)
            <option value="{{ $player->playerID }}" {{ (old('playerID', $playerGame->playerID ?? '') == $player->playerID) ? 'selected' : '' }}>
                {{ $player->username }} (ID: {{ $player->playerID }})
            </option>
        @endforeach
    </select><br>

    <!-- Game -->
    <label for="gameID">Game: *</label>
    <select name="gameID" id="gameID" required>
        <option value="">Válassz játékot!</option>
        @foreach ($games as $game)
            <option value="{{ $game->gameID }}" {{ (old('gameID', $playerGame->gameID ?? '') == $game->gameID) ? 'selected' : '' }}>
                {{ $game->name }} (ID: {{ $game->gameID }})
            </option>
        @endforeach
    </select><br>

    <!-- GamerTag -->
    <label for="gamerTag">Gamer Tag (Játékosnév játékonként): *</label>
    <input type="text" name="gamerTag" value="{{ old('gamerTag', $playerGame->gamerTag ?? '') }}" required><br>

    <!-- Hours Played -->
    <label for="hoursPlayed">Hours played:</label>
    <input type="number" name="hoursPlayed" value="{{ old('hoursPlayed', $playerGame->hoursPlayed ?? '') }}"><br>

    <!-- Last Played Date -->
    <label for="lastPlayedDate">Last Played Date:</label>
    <input type="date" name="lastPlayedDate" value="{{ old('lastPlayedDate', $playerGame->lastPlayedDate ?? '') }}"><br>

    <!-- Join Date -->
    <label for="joinDate">Join Date: *</label>
    <input type="date" name="joinDate" value="{{ old('joinDate', $playerGame->joinDate ?? '') }}" required><br>

    <!-- Current Level -->
    <label for="currentLevel">Current Level: </label>
    <input type="number" name="currentLevel" value="{{ old('currentLevel', $playerGame->currentLevel ?? '') }}"><br>

    <button type="submit">{{ isset($playerGame) ? 'Módosítás' : 'Mentés' }}</button>
</form>
@endsection
