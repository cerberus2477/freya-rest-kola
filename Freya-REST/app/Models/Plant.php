<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    protected $fillable = ['name', 'latin_name'];
    public $timestamps=false;

    public function article()
    {
        return $this->hasMany(Article::class);
    }

    public function userPlant()
    {
        return $this->belongsToMany(User::class, 'user_plants');
    }
}
