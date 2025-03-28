<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'latin_name', 'type_id'];
    public $timestamps=false;
    protected $hidden = ['deleted_at'];
    public function article()
    {
        return $this->hasMany(Article::class);
    }

    public function userPlant()
    {
        return $this->belongsToMany(User::class, 'user_plants');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
}