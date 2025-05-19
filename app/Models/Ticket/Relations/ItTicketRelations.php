<?php

namespace App\Models\Ticket\Relations;

use App\Models\User;
use App\Models\Comment\Comment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait ItTicketRelations
{
    /**
     * Get the creator for the it_ticket.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the solver for the it_ticket.
     */
    public function solver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'solved_by');
    }

    /**
     * Get the comments for the it_ticket
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
