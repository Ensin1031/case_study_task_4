<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rent extends Model
{
    protected $table = 'rents';
    protected $fillable = [
        'rent_period_days',
        'end_date',
        'was_closed',
        'user_id',
        'book_id',
    ];

    const STATUS_ACTIVE = 1;  // В аренде. Не просрочено
    const STATUS_OVERDUE = 2;  // В аренде. Просрочено
    const STATUS_ARCHIVE = 3;  // Архивная

    const ACTIVE_STATUSES = [
        [
            'id' => Rent::STATUS_ACTIVE,
            'title' => 'В аренде. Не просрочено',
        ],
        [
            'id' => Rent::STATUS_OVERDUE,
            'title' => 'В аренде. Просрочено',
        ],
    ];

    const ARCHIVE_STATUSES = [
        [
            'id' => Rent::STATUS_ARCHIVE,
            'title' => 'Архивная',
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
