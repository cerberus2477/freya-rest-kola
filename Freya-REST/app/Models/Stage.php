<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $fillable = ['name'];
    public $timestamps=false;
    protected $hidden = ['deleted_at'];

    public function userPlant()
    {
        return $this->hasMany(UserPlant::class);
    }
}