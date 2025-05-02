<?php

namespace App\Http\Requests\Administration\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItTicketStatusRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('IT Ticket Update');
    }

    public function rules()
    {
        return [
            'status' => ['required', 'string', 'in:Solved,Canceled'],
            'solver_note' => ['required', 'string', 'min:2'],
        ];
    }
}
