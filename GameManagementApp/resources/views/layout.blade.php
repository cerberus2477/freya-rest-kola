<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameManagementApp Kezelőfelület</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <nav>
        <ul>
            <li><a href="{{ route('players.index') }}">Players</a></li>
            <li><a href="#">Games</a></li>
            <li><a href="#">Player Games</a></li>
        </ul>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2024.11.14 GameManagementApp Tábor Tünde </p>
        <p>A projekt github oldala itt érhető el: <a
                href="https://github.com/cerberus2477/GameManagamentApp">https://github.com/cerberus2477/GameManagamentApp</a>
        </p>
    </footer>
</body>

</html>