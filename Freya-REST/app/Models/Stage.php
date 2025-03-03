<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $fillable = ['name'];
    public $timestamps=false;

    public function userPlant()
    {
        return $this->hasMany(UserPlant::class);
    }
}