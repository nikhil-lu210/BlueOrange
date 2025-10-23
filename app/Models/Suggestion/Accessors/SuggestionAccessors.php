<?php

namespace App\Models\Suggestion\Accessors;

trait SuggestionAccessors
{
    protected function typeName(): array
    {
        return [
            'bug' => 'Bug Report',
            'feature' => 'Feature Suggestion',
            'ui' => 'UI/UX Issue',
            'performance' => 'Performance Problem',
            'other' => 'Other'
        ];
    }

    public function getTypeNameAttribute(): string
    {
        return $this->typeName()[$this->type] ?? ucfirst($this->type ?? 'Unknown');
    }
    protected function tpyeColors(): array
    {
        return [
            'bug'      => ['light' => 'bg-label-primary', 'dark' => 'bg-primary'],
            'feature'  => ['light' => 'bg-label-success', 'dark' => 'bg-success'],
            'ui'    => ['light' => 'bg-label-info', 'dark' => 'bg-info'],
            'performance'       => ['light' => 'bg-label-warning', 'dark' => 'bg-warning'],
            'other'    => ['light' => 'bg-label-danger', 'dark' => 'bg-secondary'],
        ];
    }

    /**
     * Get the tpye badge color class based on tpye name.
     */
    public function getTypeBadgeColorAttribute(): string
    {
        return $this->tpyeColors()[$this->tpye]['dark'] ?? 'bg-primary';
    }

    public function getTypeBadgeColorLightAttribute(): string
    {
        return $this->tpyeColors()[$this->tpye]['light'] ?? 'bg-label-primary';
    }

    protected function moduleName(): array
    {
        return [
            'dashboard' => 'Dashboard',
            'attendance' => 'Attendance',
            'daily_break' => 'Daily Break',
            'work_update' => 'Daily Work Update',
            'task' => 'Task',
            'leave' => 'Leave',
            'announcement' => 'Announcement',
            'recognition' => 'Recognition',
            'learning_hub' => 'Learning Hub',
            'it_ticket' => 'IT Ticket',
            'booking' => 'Booking',
            'other' => 'Other',
        ];
    }

    public function getModuleNameAttribute(): string
    {
        return $this->moduleName()[$this->module] ?? ucfirst($this->module ?? 'Unknown');
    }

    protected function moduleColors(): array
    {
        return [
            'dashboard'     => ['light' => 'bg-label-primary',     'dark' => 'bg-primary'],
            'attendance'    => ['light' => 'bg-label-success',     'dark' => 'bg-success'],
            'daily_break'   => ['light' => 'bg-label-info',        'dark' => 'bg-info'],
            'work_update'   => ['light' => 'bg-label-warning',     'dark' => 'bg-warning'],
            'task'          => ['light' => 'bg-label-danger',      'dark' => 'bg-danger'],
            'leave'         => ['light' => 'bg-label-secondary',   'dark' => 'bg-secondary'],
            'announcement'  => ['light' => 'bg-label-dark',        'dark' => 'bg-dark'],
            'recognition'   => ['light' => 'bg-label-pink',        'dark' => 'bg-pink'],
            'learning_hub'  => ['light' => 'bg-label-indigo',      'dark' => 'bg-indigo'],
            'it_ticket'     => ['light' => 'bg-label-teal',        'dark' => 'bg-teal'],
            'booking'       => ['light' => 'bg-label-cyan',        'dark' => 'bg-cyan'],
            'other'         => ['light' => 'bg-label-primary',       'dark' => 'bg-primary'],
        ];
    }

    /**
     * Get the module badge color class based on module name.
     */
    public function getModuleBadgeColorAttribute(): string
    {
        return $this->moduleColors()[$this->module]['dark'] ?? 'bg-primary';
    }

    public function getModuleBadgeColorLightAttribute(): string
    {
        return $this->moduleColors()[$this->module]['light'] ?? 'bg-label-primary';
    }
}