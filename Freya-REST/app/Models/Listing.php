<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = ['user_plants_id', 'city', 'title', 'description', 'media', 'price'];

    protected $casts = [
        'media' => 'array',
    ];

    public function userPlant()
    {
        return $this->belongsTo(UserPlant::class, 'user_plants_id');
    }
}