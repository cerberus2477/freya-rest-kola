<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = ['user_plants_id', 'city', 'title', 'description', 'media', 'sell', 'price'];

    protected $casts = [
        'media' => 'array',
    ];

    // public function userPlant()
    // {
    //     return $this->belongsTo(UserPlant::class, 'user_plants_id');
    // }

    public function userPlant()
    {
        return $this->hasMany(UserPlant::class, 'listing_id');
    }
}