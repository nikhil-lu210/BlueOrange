<?php

namespace App\Models\Booking\DiningRoomBooking\Relations;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait DiningRoomBookingRelations
{
    /**
     * Get the user for the vault.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
