<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends StoreEventRequest
{
    public function authorize(): bool
    {
        $event = $this->route('event');
        return $this->user()?->can('update', $event) ?? false;
    }
}
