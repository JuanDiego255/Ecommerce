<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'code' => 'required|string',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'quantity' => 'required|string',
            'price' => 'required|string',
            'mayor_price' => 'required|string',
            'trending' => 'nullable|string',
            'discount' => 'required|string',
        ];
    }
}
