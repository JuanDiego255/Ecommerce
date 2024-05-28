<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportCategoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',    
            'slug' => 'required|string',           
            'description' => 'required|string',
            'department_id' => 'required|string'          
        ];
    }
}
