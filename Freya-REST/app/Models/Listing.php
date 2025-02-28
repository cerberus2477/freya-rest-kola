<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = ['user_plants_id', 'city', 'title', 'description', 'media', 'sell', 'price'];

    public function userPlant()
    {
        return $this->belongsTo(UserPlant::class);
    }
}