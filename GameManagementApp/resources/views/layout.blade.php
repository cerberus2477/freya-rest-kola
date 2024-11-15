<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameManagementApp Kezelőfelület</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
</head>

<body>
    <nav>
        <ul>
            <li><a href="{{ route('players.index') }}">Players</a></li>
            <li><a href="{{ route('games.index') }}">Games</a></li>
            <li><a href="{{ route('playergames.index') }}">Player Games</a></li>
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