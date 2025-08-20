<?php

namespace App\Exports\Administration\Event;

use App\Exports\Global\BaseExportSettings;
use App\Models\Event\Event;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

class EventExport extends BaseExportSettings implements FromCollection, WithMapping
{
    protected $events;

    public function __construct($events = null)
    {
        $this->events = $events ?? Event::with(['organizer.employee', 'participants.user.employee'])->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->events;
    }

    /**
     * Define the headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Description',
            'Start Date',
            'End Date',
            'Start Time',
            'End Time',
            'Location',
            'Event Type',
            'Status',
            'Organizer',
            'Is All Day',
            'Color',
            'Max Participants',
            'Current Participants',
            'Is Public',
            'Reminder Before',
            'Reminder Unit',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * @param mixed $event
     * @return array
     */
    public function map($event): array
    {
        return [
            $event->id,
            $event->title,
            $event->description,
            $event->start_date ? $event->start_date->format('Y-m-d') : '',
            $event->end_date ? $event->end_date->format('Y-m-d') : '',
            $event->start_time ? $event->start_time->format('H:i') : '',
            $event->end_time ? $event->end_time->format('H:i') : '',
            $event->location,
            $event->event_type_label,
            $event->status_label,
            $event->organizer ? get_employee_name($event->organizer) : 'Unknown',
            $event->is_all_day ? 'Yes' : 'No',
            $event->color,
            $event->max_participants,
            $event->current_participants,
            $event->is_public ? 'Yes' : 'No',
            $event->reminder_before,
            $event->reminder_unit_label,
            $event->created_at ? $event->created_at->format('Y-m-d H:i:s') : '',
            $event->updated_at ? $event->updated_at->format('Y-m-d H:i:s') : ''
        ];
    }
}
