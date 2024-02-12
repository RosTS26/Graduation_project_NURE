<?php

namespace App\Http\Requests\Games\SeaBattle;

use Illuminate\Foundation\Http\FormRequest;

class ShotRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'posx' => 'required|integer|between:1,10',
            'posy' => 'required|integer|between:1,10',
        ];
    }
}
