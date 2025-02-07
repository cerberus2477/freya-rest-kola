<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $fillable = ['stage_name'];
    public $timestamps=false;

    public function userPlants()
    {
        return $this->hasMany(UserPlant::class);
    }
}
