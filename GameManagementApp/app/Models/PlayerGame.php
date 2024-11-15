<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerGame extends Model
{
    public $timestamps = false;

    protected $table = 'PlayerGames';

    protected $fillable = [
        'playerID',
        'gameID',
        'gamerTag',
        'hoursPlayed',
        'lastPlayedDate',
        'joinDate',
        'currentLevel',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class, 'playerID');
    }

    // Relationship to the Game model
    public function game()
    {
        return $this->belongsTo(Game::class, 'gameID');
    }
}
