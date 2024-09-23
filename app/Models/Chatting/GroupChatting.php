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
    ];
    
    protected $casts = [
        'message' => PurifyHtmlOnGet::class,
    ];
}
