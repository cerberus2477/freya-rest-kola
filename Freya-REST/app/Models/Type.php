<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = ['name'];
    public $timestamps=false;

    protected $hidden = ['deleted_at'];

    public function plant()
    {
        return $this->hasMany(Plant::class);
    }
}