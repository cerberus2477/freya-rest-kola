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
        $data['password'] = Hash::make($data['password']); // Hash the password
        return redirect()->route('players.index')->with('success', "$player->name játékos sikeresen létrehozva!");
    }

    public function edit(Player $player)
    {
        return view('players.edit', compact('player'));
    }

    public function update(PlayerRequest $request, Player $player)
    {
        // Only hash the password if it's being updated
        $data = $request->validated();
        
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']); // Hash the password
        }

        $player->update($data);
        return redirect()->route('players.index')->with('success', "Player successfully updated!");
    }
    
    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route('players.index')->with('success', "$player->name játékos sikeresen törölve!");
    }
}

