<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = ['user_plants_id', 'city', 'title', 'description', 'media', 'price'];

    // Laravel’s $casts feature tells Eloquent how to interpret and serialize values, including when converting to JSON for API responses.
    protected $casts = [
        'media' => 'array',
        'price' => 'integer'
    ];

    public function userPlant()
    {
        return $this->belongsTo(UserPlant::class, 'user_plants_id');
    }
}