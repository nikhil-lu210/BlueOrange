<?php

namespace App\Models\Booking\DiningRoomBooking;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Booking\DiningRoomBooking\Mutators\DiningRoomBookingMutators;
use App\Models\Booking\DiningRoomBooking\Accessors\DiningRoomBookingAccessors;
use App\Models\Booking\DiningRoomBooking\Relations\DiningRoomBookingRelations;

class DiningRoomBooking extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations
    use DiningRoomBookingRelations;

    // Accessors & Mutators
    use DiningRoomBookingAccessors, DiningRoomBookingMutators;

    protected $cascadeDeletes = [];

    protected $casts = [];

    protected $fillable = [
        'user_id',
        'employee_shift_id',
        'booking_date',
        'booking_time',
        'status'
    ];
}
