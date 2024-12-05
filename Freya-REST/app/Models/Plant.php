<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    protected $fillable = ['name', 'latin_name'];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function userPlants()
    {
        return $this->belongsToMany(User::class, 'user_plants');
    }
}
