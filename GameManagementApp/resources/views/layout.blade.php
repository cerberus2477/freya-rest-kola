<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kezelőfelület</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.css') }}">
</head>

<body>
    <nav>
        <ul>
            <li class="nav-left"><a href="{{route('home')}}"><i class="fa-solid fa-house"></i> Home</a></li>
            <li><a href="{{ route('players.index') }}"><i class="fa-solid fa-user"></i> Players</a></li>
            <li><a href="{{ route('games.index') }}"><i class="fa-solid fa-gamepad"></i> Games</a></li>
            <li><a href="{{ route('playergames.index') }}"><i class="fa-solid fa-arrows-left-right"></i> Player
                    Games</a>
            </li>
        </ul>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2024.11.14 GameManagementApp Tábor Tünde </p>
        <p>A projekt github oldala itt érhető el: <a target="_blank"
                href="https://github.com/cerberus2477/GameManagamentApp">https://github.com/cerberus2477/GameManagamentApp</a>
        </p>
    </footer>
</body>

</html>