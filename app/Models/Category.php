<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';
    protected $guarded = [];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'category_id', 'id');
    }

}
