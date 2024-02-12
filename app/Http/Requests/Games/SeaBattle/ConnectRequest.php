<?php

namespace App\Http\Requests\Games\SeaBattle;

use Illuminate\Foundation\Http\FormRequest;

class ConnectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'data' => 'required|array',
            'data.id' => 'required|int',
            'data.name' => 'required|string',
        ];
    }
}
