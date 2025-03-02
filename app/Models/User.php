<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sub_books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'user_books', 'user_id', 'book_id');
    }

    public function is_book_sub(Book $book): bool
    {
        return $this->sub_books()->where('book_id', $book->id)->exists();
    }

    public function is_superuser(): bool
    {
        return $this->is_admin === 1;
    }

    public function is_active_user(): bool
    {
        return $this->is_active === 1;
    }

    public function rents(): HasMany  // все записи аренды
    {
        return $this->hasMany(Rent::class, 'user_id', 'id');
    }

    public function active_rents(): HasMany  // активные записи аренды
    {
        return $this->rents()->where('was_closed', 0);
    }

    public function archive_rents(): HasMany  // архивные записи аренды
    {
        return $this->rents()->where('was_closed', 1);
    }

    public function has_overdue_rent(): bool
    {
        return $this->active_rents()->where('end_date', '<', now())->exists();
    }

    public function purchases(): HasMany  // все покупки
    {
        return $this->hasMany(Shop::class, 'user_id', 'id');
    }

    public function basket_purchases(): HasMany  // покупки в корзине (в статусах "В корзине" и "Оплачено")
    {
        return $this->purchases()->whereIn('status', [Shop::STATUS_BASKET, Shop::STATUS_PAID]);
    }

    public function purchased_books(): HasMany  // закрытые покупки (в статусе "Продано")
    {
        return $this->purchases()->where('status', Shop::STATUS_BUY);
    }

}
