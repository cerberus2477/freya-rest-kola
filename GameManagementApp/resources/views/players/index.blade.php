@extends('layout')
@section('title', 'Player tábla')

@section('content')
<div class="title-add">
    <h1>Players</h1>
    <a href="{{ route('players.create') }}" class="btn btn-add">Új rekord hozzáadása <i
            class="fa-solid fa-plus"></i></a>
</div>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Join Date</th>
            <th>Age</th>
            <th>Occupation</th>
            <th>Gender</th>
            <th>City</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($players as $player)
            <tr>
                <td>{{ $player->playerID }}</td>
                <td>{{ $player->username }}</td>
                <td>{{ $player->email }}</td>
                <td>{{ $player->joinDate }}</td>
                <td>{{ $player->age }}</td>
                <td>{{ $player->occupation }}</td>
                <td>{{ $player->gender }}</td>
                <td>{{ $player->city }}</td>
                <td class="actions">
                    <a href="{{ route('players.edit', $player->playerID) }}" class="btn btn-edit"><i
                            class="fa-solid fa-pen-to-square"></i></a>
                    <form action="{{ route('players.destroy', $player->playerID) }}" method="POST">
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