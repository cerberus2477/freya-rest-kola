<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Http\Requests\GameRequest;


class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $games = Game::all();
        return view('games.index', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('games.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GameRequest $request)
    {

        //debugging ------------------------------------------------------

        // $validatedData = $request->validated();

        // dd($validatedData); // Ensure validation works and data is correct

        // try {
        // $game = Game::create($validatedData);
        //     dd($game); // Check if the record is saved correctly
        // } catch (\Exception $e) {
        //     dd($e->getMessage()); // Catch any error during save
        // }


        Game::create($request->validated());

        return redirect()->route('games.index')->with('success', "$game->name játék sikeresen létrehozva!");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        return view('games.edit', compact('game'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GameRequest $request, Game $game)
    {
        $game->update($request->validated());
        return redirect()->route('games.index')->with('success', "$game->name játék sikeresen módosítva!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        $game->delete();
        return redirect()->route('games.index')->with('success', "$game->name játék sikeresen törölve!");
    }
}
