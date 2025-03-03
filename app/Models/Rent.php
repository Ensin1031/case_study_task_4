<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rent extends Model
{
    protected $table = 'rents';
    protected $fillable = [
        'rent_period_days',
        'end_at',
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

    public function status(): int
    {
        switch (true) {
            case $this->was_closed === 1: {
                return Rent::STATUS_ARCHIVE;
            }
            case (!($this->was_closed === 1) && date_create($this->end_at) < now()): {
                return Rent::STATUS_OVERDUE;
            }
            default: {
                return Rent::STATUS_ACTIVE;
            }
        }
    }

    public function days_to_end(): string
    {
        $today_at = date_create();
        $end_rent_at = date_create($this->end_at);
        if ($today_at <= $end_rent_at) {
            return $end_rent_at->diff($today_at)->days;
        }
        return '0';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }

    public function in_active_status(): bool
    {
        return $this->status() == Rent::STATUS_ACTIVE;
    }

    public function in_overdue_status(): bool
    {
        return $this->status() == Rent::STATUS_OVERDUE;
    }

    public function in_archive_status(): bool
    {
        return $this->status() == Rent::STATUS_ARCHIVE;
    }

}
