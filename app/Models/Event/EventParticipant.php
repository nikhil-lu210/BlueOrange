<?php

namespace App\Models\Event;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventParticipant extends Model
{
    use HasFactory;

    protected $table = 'event_participant';

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'joined_at',
        'left_at',
        'notes'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
    ];

    // Participant status options
    const STATUS_OPTIONS = [
        'invited' => 'Invited',
        'accepted' => 'Accepted',
        'declined' => 'Declined',
        'maybe' => 'Maybe',
        'attended' => 'Attended',
        'no_show' => 'No Show'
    ];

    /**
     * Get the event that the participant belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who is participating.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        $key = strtolower($this->status);
        return self::STATUS_OPTIONS[$key] ?? ucfirst($key ?? '');
    }

    /**
     * Check if participant has accepted the invitation.
     */
    public function getHasAcceptedAttribute()
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if participant has declined the invitation.
     */
    public function getHasDeclinedAttribute()
    {
        return $this->status === 'declined';
    }

    /**
     * Check if participant is attending.
     */
    public function getIsAttendingAttribute()
    {
        return in_array($this->status, ['accepted', 'attended']);
    }

    /**
     * Scope for accepted participants.
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope for declined participants.
     */
    public function scopeDeclined($query)
    {
        return $query->where('status', 'declined');
    }

    /**
     * Scope for participants by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
