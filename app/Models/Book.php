<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'category_id', 'title', 'author', 'price', 'stock', 'description', 'isbn',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}