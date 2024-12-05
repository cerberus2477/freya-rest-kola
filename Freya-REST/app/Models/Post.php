<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['user_id', 'city', 'title', 'plant', 'description', 'media', 'sell'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
