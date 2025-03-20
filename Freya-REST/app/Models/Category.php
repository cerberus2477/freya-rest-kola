<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    public $timestamps=false;
    protected $hidden = ['deleted_at'];

    public function article()
    {
        return $this->hasMany(Article::class);
    }
}