<?php

namespace App\Models\Ticket\Mutators;

use Stevebauman\Purify\Facades\Purify;

trait ItTicketMutators
{
    /**
     * Mutator for the 'description' attribute.
     * 
     * Sanitizes the HTML content before storing it in the database.
     *
     * @param string $value The raw HTML string to be sanitized.
     * @return void
     */
    public function setDescriptionAttribute(string $value): void
    {
        $this->attributes['description'] = Purify::clean($value);
    }
    
    /**
     * Mutator for the 'solver_note' attribute.
     * 
     * Sanitizes the HTML content before storing it in the database.
     *
     * @param string $value The raw HTML string to be sanitized.
     * @return void
     */
    public function setSolverNoteAttribute(string $value): void
    {
        $this->attributes['solver_note'] = Purify::clean($value);
    }

    /**
     * Mutator for the 'seen_by' attribute.
     * 
     * This method encodes the 'seen_by' data into a JSON string before 
     * saving it to the database. If the input value is null or empty, 
     * the database value will be set to null.
     *
     * @param array|null $value The array of 'seen_by' data to encode.
     * @return void
     */
    public function setSeenByAttribute(?array $value): void
    {
        $this->attributes['seen_by'] = $value ? json_encode($value) : null;
    }
}
