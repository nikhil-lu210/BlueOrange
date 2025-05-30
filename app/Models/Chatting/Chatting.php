<?php

namespace App\Models\Chatting;


use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chatting\Traits\Relations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chatting extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, Relations, HasCustomRouteId;

    protected $cascadeDeletes = [];

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'seen_at',
        'reply_to_id'
    ];

    protected $casts = [
        'message' => PurifyHtmlOnGet::class,
        'seen_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Removed WebSocket broadcasting
    }
}
