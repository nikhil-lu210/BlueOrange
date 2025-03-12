<?php

namespace App\Models\Chatting;

use App\Models\User;
use App\Models\Chatting\GroupChatting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupChatRead extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_chatting_id',
        'user_id',
        'read_at',
    ];

    public function groupChatting()
    {
        return $this->belongsTo(GroupChatting::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
