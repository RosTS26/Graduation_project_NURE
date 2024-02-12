<?php

namespace App\Http\Requests\Games\SeaBattle;

use Illuminate\Foundation\Http\FormRequest;

class ReadyGameRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'field' => 'required|array|size:10',
            'field.*' => 'required|array|size:10',
            'ships' => 'array|nullable',
        ];
    }
}
