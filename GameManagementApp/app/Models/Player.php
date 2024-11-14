<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    public $timestamps = false;

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
