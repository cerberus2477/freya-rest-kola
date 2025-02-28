<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = ['name'];
    public $timestamps=false;

    public function plant()
    {
        return $this->hasMany(Plant::class);
    }
}