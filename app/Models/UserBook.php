<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBook extends Model
{
    protected $table = 'user_books';
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }

}
