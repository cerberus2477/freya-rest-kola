<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPlant extends Model
{
    protected $table = 'user_plants';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function listing()
    {
        return $this->hasMany(Listing::class, 'id');
    }
}
