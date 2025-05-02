<?php

namespace App\Http\Requests\Administration\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class StoreItTicketRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('IT Ticket Create');
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:200'],
            'description' => ['required', 'string', 'min:10'],
        ];
    }
}
