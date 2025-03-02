<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $table = 'books';
    protected $fillable = [
        'title',
        'description',
        'status',
        'price',
        'published_year',
        'image',
        'category_id',
        'author_id',
    ];

    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_CLOSE = 3;

    public function sub_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_books', 'book_id', 'user_id');
    }

    public function is_user_sub(User $user): bool
    {
        return $this->sub_users()->where('user_id', $user->id)->exists();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function rents(): HasMany  // все записи аренды
    {
        return $this->hasMany(Rent::class, 'book_id', 'id');
    }

    public function purchases(): HasMany  // все покупки
    {
        return $this->hasMany(Shop::class, 'book_id', 'id');
    }

    public function is_user_purchase(User $user): bool
    {
        return $this->purchases()->where('user_id', $user->id)->whereIn('status', [Shop::STATUS_BASKET])->exists();
    }

    public function is_user_rent(User $user): bool
    {
        return $this->rents()->where('was_closed', 0)->exists();
    }

    public function can_buy_book(): bool
    {
        return $this->status == Book::STATUS_PUBLISHED;
    }

    public function can_rent_book(): bool
    {
        return $this->status == Book::STATUS_PUBLISHED;
    }

    public function next_status(): array
    {
        switch ($this->status) {
            case 3:
            case 1: {
                return [
                    'id' => Book::STATUS_PUBLISHED,
                    'title' => 'Опубликовано',
                ];
            }
            case 2: {
                return [
                    'id' => Book::STATUS_CLOSE,
                    'title' => 'Снято с публикации',
                ];
            }
            default: {
                return [
                    'id' => Book::STATUS_DRAFT,
                    'title' => 'Черновик',
                ];
            }
        }
    }

}
