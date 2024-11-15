<?php
namespace App\Http\Controllers;

use App\Models\Player;
use App\Http\Requests\PlayerRequest;

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::all();
        return view('players.index', compact('players'));
    }

    public function create()
    {
        return view('players.create');
    }

    public function store(PlayerRequest $request)
    {
        Player::create($request->validated());
        return redirect()->route('players.index');
    }

    public function edit(Player $player)
    {
        return view('players.edit', compact('player'));
    }

    public function update(PlayerRequest $request, Player $player)
    {
        $player->update($request->validated());
        return redirect()->route('players.index');
    }

    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route('players.index');
    }
}

