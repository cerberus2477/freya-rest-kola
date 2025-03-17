<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];
    public $timestamps=false;
    protected $hidden = ['deleted_at'];

    public function article()
    {
        return $this->hasMany(Article::class);
    }
}