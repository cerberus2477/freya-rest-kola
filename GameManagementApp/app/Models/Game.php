<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'gameID';

    protected $fillable = [
        'name',
        'type',
        'levelCount',
        'description'
    ];
}
