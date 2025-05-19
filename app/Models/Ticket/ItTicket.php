<?php

namespace App\Models\Ticket;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use App\Models\Ticket\Mutators\ItTicketMutators;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\Ticket\Accessors\ItTicketAccessors;
use App\Models\Ticket\Relations\ItTicketRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItTicket extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations
    use ItTicketRelations;

    // Accessors & Mutators
    use ItTicketAccessors, ItTicketMutators;

    protected $cascadeDeletes = ['comments'];

    protected $casts = [
        'description' => PurifyHtmlOnGet::class,
        'solved_at' => 'datetime',
        'seen_by' => 'array',
        'solver_note' => PurifyHtmlOnGet::class,
    ];

    protected $fillable = [
        'creator_id',
        'title',
        'description',
        'seen_by',
        'solved_by',
        'solved_at',
        'status',
        'solver_note',
    ];
}
