<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = ['user_plant_id', 'city', 'title', 'plant', 'description', 'media', 'sell'];

    public function userPlant()
    {
        return $this->belongsTo(UserPlant::class);
    }
}