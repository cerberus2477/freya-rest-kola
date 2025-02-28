<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'plant_id', 'author_id', 'category_id', 'source', 'description', 'content'];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
}