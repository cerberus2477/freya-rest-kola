<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPlant extends Model
{
    use HasFactory;

    protected $table = 'user_plants';

    protected $fillable = ['user_id', 'plant_id', 'stage_id', 'count'];

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
        return $this->hasOne(Listing::class, 'user_plants_id');
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }
}
