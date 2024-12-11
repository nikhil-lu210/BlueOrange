<?php

namespace App\Models\Ticket\Accessors;

trait ItTicketAccessors
{
    /**
     * Accessor for the 'seen_by' attribute.
     * 
     * This method ensures the 'seen_by' JSON data is decoded into an array 
     * when retrieved from the database. If the value is null or empty, it 
     * returns an empty array.
     *
     * @param string|null $value The raw JSON string from the database.
     * @return array The decoded array or an empty array if the value is null.
     */
    public function getSeenByAttribute(?string $value): array
    {
        return $value ? json_decode($value, true) : [];
    }
}
