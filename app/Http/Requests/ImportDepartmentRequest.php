<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportDepartmentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'department' => 'required|string'         
        ];
    }
}
