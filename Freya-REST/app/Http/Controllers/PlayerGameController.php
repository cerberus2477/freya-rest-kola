<?php

namespace App\Http\Controllers;

use App\Models\PlayerGame;
use App\Http\Requests\PlayerGameRequest;
use App\Models\Player;
use App\Models\Game;

class PlayerGameController extends Controller
{
    public function index()
    {
        $playerGames = PlayerGame::with(['player', 'game'])->get();
        return view('playergames.index', compact('playerGames'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $players = Player::all();
        $games = Game::all();
        return view('playergames.create', compact('players', 'games'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlayerGameRequest $request)
    {
        PlayerGame::create($request->validated());

        $playerName = $playergame->player->username;
        $gameName = $playergame->game->name;

        return redirect()->route('playergames.index')->with('success', "$playerName - $gameName játékos-játék sikeresen létrehozva!");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlayerGame $playerGame)
    {
        $players = Player::all();
        $games = Game::all();

        return view('playergames.edit', compact('playerGame', 'players', 'games'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(PlayerGameRequest $request, PlayerGame $playerGame)
    {
        $playerGame->update($request->validated());
        $playerName = $playergame->player->username;
        $gameName = $playergame->game->name;
        return redirect()->route('playergames.index')->with('success', "$playerName - $gameName játékos-játék sikeresen módosítva!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlayerGame $playerGame)
    {
        $playerGame->delete();
        $playerName = $playergame->player->username;
        $gameName = $playergame->game->name;
        return redirect()->route('playergames.index')->with('success', "$playerName - $gameName játékos-játék sikeresen törölve!");
    }
}
