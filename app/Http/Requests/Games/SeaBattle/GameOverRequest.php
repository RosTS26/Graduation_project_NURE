<?php

namespace App\Http\Requests\Games\SeaBattle;

use Illuminate\Foundation\Http\FormRequest;

class GameOverRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'check' => 'required|string|in:qwerty',
        ];
    }
}
