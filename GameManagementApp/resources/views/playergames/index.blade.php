@extends('layout')

@section('title', 'Player-Games')

@section('content')

<div class="title-add">
    <h1>Player-Games</h1>
    <a href="{{ route('playergames.create') }}" class="btn btn-add">Új Player-Game rekord hozzáadása <i
            class="fa-solid fa-plus"></i></a>
</div>
@include('success')
@include('error')
<table>
    <thead>
        <tr>
            <th>Player ID</th>
            <th>Player Name</th>
            <th>Game ID</th>
            <th>Game Name</th>
            <th>Gamer Tag</th>
            <th>Hours Played</th>
            <th>Last Played</th>
            <th>Join Date</th>
            <th>Current Level</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($playerGames as $playerGame)
            <tr>
                <td>{{ $playerGame->playerID }}</td>
                <td>{{ $playerGame->player->username }}</td> <!-- Accessing related player -->
                <td>{{ $playerGame->gameID }}</td>
                <td>{{ $playerGame->game->name }}</td> <!-- Accessing related game -->
                <td>{{ $playerGame->gamerTag }}</td>
                <td>{{ $playerGame->hoursPlayed }}</td>
                <td>{{ $playerGame->lastPlayedDate }}</td>
                <td>{{ $playerGame->joinDate }}</td>
                <td>{{ $playerGame->currentLevel }}</td>
                <td class="actions">
                    <a href="{{ route('playergames.edit', [$playerGame->playerID, $playerGame->gameID]) }}"
                        class="btn btn-edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    <form action="{{ route('playergames.destroy', [$playerGame->playerID, $playerGame->gameID]) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete"><i class="fa-solid fa-trash-can"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection