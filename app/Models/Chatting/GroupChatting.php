<?php

namespace App\Models\Chatting;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\Chatting\Traits\GroupChattingRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupChatting extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, GroupChattingRelations, HasCustomRouteId;

    protected $cascadeDeletes = ['group_users'];

    protected $fillable = [
        'chatting_group_id',
        'sender_id',
        'message',
        'reply_to_id',
    ];

    protected $casts = [
        'message' => PurifyHtmlOnGet::class,
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($groupChatting) {
            // Check if this is a file-only message (message can be null if there's a file)
            if (empty($groupChatting->message) && !$groupChatting->files()->exists()) {
                // If no file is attached and message is empty, throw an exception
                if (!request()->hasFile('file') && !request()->file) {
                    throw new \Exception('Message cannot be empty if no file is attached.');
                }
            }
        });
    }
}
