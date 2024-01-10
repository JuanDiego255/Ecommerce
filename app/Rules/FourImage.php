<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FourImage implements Rule
{
    public function passes($attribute, $value)
    {
        // Contar la cantidad de imágenes
        return count($value) <= 4;
    }

    public function message()
    {
        return 'No se pueden subir más de 4 imágenes.';
    }
}
