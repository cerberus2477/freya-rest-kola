<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{

    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'gameID';

    protected $fillable = [
        'name',
        'type',
        'levelCount',
        'description'
    ];
}
