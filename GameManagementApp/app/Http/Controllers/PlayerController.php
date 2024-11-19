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

        //debugging ------------------------------------------------------

        // $validatedData = $request->validated();

        // dd($validatedData); // Ensure validation works and data is correct

        // try {
        //     $game = Player::create($validatedData);
        //     dd($game); // Check if the record is saved correctly
        // } catch (\Exception $e) {
        //     dd($e->getMessage()); // Catch any error during save
        // }


        Player::create($request->validated());
        return redirect()->route('players.index')->with('success', 'Player created successfully!');
    }

    public function edit(Player $player)
    {
        return view('players.edit', compact('player'));
    }

    public function update(PlayerRequest $request, Player $player)
    {
        $player->update($request->validated());
        return redirect()->route('players.index')->with('success', 'Player created successfully!');
    }

    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route('players.index')->with('success', 'Player deleted successfully!');
    }
}

