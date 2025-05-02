<?php

namespace App\Http\Requests\Administration\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItTicketRequest extends FormRequest
{
    public function authorize()
    {
        $ticket = $this->route('it_ticket');
        return $this->user()->can('IT Ticket Update') && $ticket->status === 'Pending';
    }

    public function rules()
    {
        return [
            'title' => ['sometimes', 'string', 'min:5', 'max:200'],
            'description' => ['sometimes', 'string', 'min:10'],
        ];
    }
}
