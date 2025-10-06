<?php

namespace App\Models\Event;

use App\Models\User;
use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasCustomRouteId;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'event_type',
        'status',
        'organizer_id',
        'is_all_day',
        'color',
        'max_participants',
        'current_participants',
        'is_public',
        'reminder_before',
        'reminder_unit',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_all_day' => 'boolean',
        'is_public' => 'boolean',
        'max_participants' => 'integer',
        'current_participants' => 'integer',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Event types
    const EVENT_TYPES = [
        'meeting' => 'Meeting',
        'training' => 'Training',
        'celebration' => 'Celebration',
        'conference' => 'Conference',
        'workshop' => 'Workshop',
        'other' => 'Other'
    ];

    // Reminder units
    const REMINDER_UNITS = [
        'minutes' => 'Minutes',
        'hours' => 'Hours',
        'days' => 'Days'
    ];

    /**
     * Get the organizer of the event.
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Get participant link records (pivot model rows).
     */
    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    /**
     * Get participant users via pivot table.
     */
    public function participant_users()
    {
        return $this->belongsToMany(User::class, 'event_participant', 'event_id', 'user_id')
                    ->withPivot('status', 'joined_at', 'left_at')
                    ->withTimestamps();
    }

    /**
     * Get the event type label.
     */
    public function getEventTypeLabelAttribute()
    {
        return self::EVENT_TYPES[$this->event_type] ?? 'Unknown';
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        return $this->status ?? 'Unknown';
    }

    /**
     * Get the reminder unit label.
     */
    public function getReminderUnitLabelAttribute()
    {
        return self::REMINDER_UNITS[$this->reminder_unit] ?? 'Unknown';
    }

    /**
     * Check if event is happening today.
     */
    public function getIsTodayAttribute()
    {
        return $this->start_date->isToday();
    }

    /**
     * Check if event is happening this week.
     */
    public function getIsThisWeekAttribute()
    {
        return $this->start_date->isCurrentWeek();
    }

    /**
     * Check if event is happening this month.
     */
    public function getIsThisMonthAttribute()
    {
        return $this->start_date->isCurrentMonth();
    }

    /**
     * Get formatted start date and time.
     */
    public function getFormattedStartDateTimeAttribute()
    {
        if ($this->is_all_day) {
            return $this->start_date->format('M d, Y');
        }
        return $this->start_date->format('M d, Y') . ' ' . $this->start_time->format('g:i A');
    }

    /**
     * Get formatted end date and time.
     */
    public function getFormattedEndDateTimeAttribute()
    {
        if ($this->is_all_day) {
            return $this->end_date->format('M d, Y');
        }
        return $this->end_date->format('M d, Y') . ' ' . $this->end_time->format('g:i A');
    }

    /**
     * Scope for published events.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'Published');
    }

    /**
     * Scope for upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now()->startOfDay());
    }

    /**
     * Scope for past events.
     */
    public function scopePast($query)
    {
        return $query->where('end_date', '<', now()->startOfDay());
    }

    /**
     * Scope for events by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    /**
     * Scope for events by organizer.
     */
    public function scopeByOrganizer($query, $organizerId)
    {
        return $query->where('organizer_id', $organizerId);
    }

    /**
     * Scope for public events.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
