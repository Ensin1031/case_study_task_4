<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shop extends Model
{
    protected $table = 'shops';
    protected $guarded = [];

    const STATUS_BASKET = 1;  // В корзине
    const STATUS_PAID = 2;  // Оплачено
    const STATUS_BUY = 3;  // Продано

    const BASKET_STATUSES = [
        [
            'id' => Shop::STATUS_BASKET,
            'title' => 'В корзине',
        ],
        [
            'id' => Shop::STATUS_PAID,
            'title' => 'Оплачено',
        ],
    ];

    const BUY_STATUSES = [
        [
            'id' => Shop::STATUS_BUY,
            'title' => 'Продано',
        ],
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }

}
