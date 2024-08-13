<?php

namespace App\Models\Chatting;

use Illuminate\Database\Eloquent\Model;
use App\Models\Chatting\Traits\Relations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chatting extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, Relations;
    
    protected $cascadeDeletes = [];

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'file',
        'seen_at'
    ];

    protected $casts = [
        'seen_at' => 'datetime',
    ];
}
