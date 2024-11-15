@extends('layout')

@section('content')
<h1>{{ isset($player) ? 'Játékos módosítása' : 'Új játékos hozzáadása' }}</h1>

<form action="{{ isset($player) ? route('players.update', $player->playerID) : route('players.store') }}" method="POST">
    @csrf
    @if (isset($player))
        @method('PUT')
    @endif

    <label>Username: *</label>
    <input type="text" name="username" value="{{ $player->username ?? old('username') }}" required><br>

    <label>Password: *</label>
    <input type="password" name="password" required><br>

    <label>Email: *</label>
    <input type="email" name="email" value="{{ $player->email ?? old('email') }}" required><br>

    <label>Join Date: *</label>
    <input type="date" name="joinDate" value="{{ $player->joinDate ?? old('joinDate', date('Y-m-d')) }}" required><br>

    <label>Age:</label>
    <input type="number" name="age" value="{{ $player->age ?? "" }}"><br>

    <label>Occupation:</label>
    <input type="text" name="occupation" value="{{ $player->occupation ?? "" }}"><br>

    <label>Gender:</label>
    <input type="text" name="gender" value="{{ $player->gender ?? "" }}"><br>

    <label>City:</label>
    <input type="text" name="city" value="{{ $player->city ?? "" }}"><br>

    <button type="submit">{{ isset($player) ? 'Módosítás' : 'Mentés' }}</button>
</form>
@endsection