<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = ['type_name'];
    public $timestamps=false;

    public function plants()
    {
        return $this->hasMany(Plant::class);
    }
}
