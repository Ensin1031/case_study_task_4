<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    protected $table = 'authors';
    protected $guarded = [];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'author_id', 'id');
    }

}
