@extends('layout')

@section('content')
<h1>{{ isset($player) ? 'Játékos-Játék módosítása' : 'Új játékos-játék hozzáadása' }}</h1>

<form
    action="{{ isset($player) ? route('playergames.update', $idontknowwhattowritehere) : route('playergames.store') }}"
    method="POST">
    @csrf
    @if (isset($player))
        @method('PUT')
    @endif


    <!-- Player  -->
    <label for="playerID">Players name: *</label>
    <select name="playerID" id="playerID" required>
        <option value="">Válassz játékost!</option>
        @foreach ($players as $player)
            <option value="{{ $player->playerID }}" {{ (isset($playerGame) && $playerGame->playerID == $player->playerID) ? 'selected' : '' }}>
                {{ $player->username }} (ID: {{ $player->playerID }})
            </option>
        @endforeach
    </select><br>

    <!-- Game -->
    <label for="gameID">Game: *</label>
    <select name="gameID" id="gameID" required>
        <option value="">Válassz játékot!</option>
        @foreach ($games as $game)
            <option value="{{ $game->gameID }}" {{ (isset($playerGame) && $playerGame->gameID == $game->gameID) ? 'selected' : '' }}>
                {{ $game->name }} (ID: {{ $game->gameID }})
            </option>
        @endforeach
    </select><br>

    <!-- GamerTag -->
    <label for="gamerTag">Gamer Tag (Játékosnév játékonként): *</label>
    <input type="text" name="gamerTag" value="{{ $playerGame->gamerTag ?? old('gamerTag') }}" required><br>

    <!-- Hours Played -->
    <label for="hoursPlayed">Hours played:</label>
    <input type="number" name="hoursPlayed" value="{{ $playerGame->hoursPlayed ?? old('hoursPlayed') }}"><br>

    <!-- Last Played Date -->
    <label for="lastPlayedDate">Last Played Date:</label>
    <input type="date" name="lastPlayedDate" value="{{ $playerGame->lastPlayedDate ?? old('lastPlayedDate') }}"><br>

    <!-- Join Date -->
    <label for="joinDate">Join Date: *</label>
    <input type="date" name="joinDate" value="{{ $playerGame->joinDate ?? old('joinDate') }}" required><br>

    <!-- Current Level -->
    <label for="currentLevel">Current Level: </label>
    <input type="number" name="currentLevel" value="{{ $playerGame->currentLevel ?? old('currentLevel') }}"><br>


    <button type="submit">{{ isset($player) ? 'Módosítás' : 'Mentés' }}</button>
</form>
@endsection