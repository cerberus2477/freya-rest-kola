<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'playerID';
    protected $fillable = [
        'username',
        'password',
        'email',
        'joinDate',
        'age',
        'occupation',
        'gender',
        'city'
    ];

}
