<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    protected $table = 'books';
    protected $fillable = [
        'title',
        'description',
        'status',
        'price',
        'published_year',
        'category_id',
        'author_id',
    ];

    public function sub_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_books', 'book_id', 'user_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

}
