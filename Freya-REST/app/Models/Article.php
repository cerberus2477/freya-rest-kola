<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'plant_id', 'author_id', 'category_id', 'source', 'description', 'content'];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id'); // Assuming there's a 'user_id' foreign key
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}