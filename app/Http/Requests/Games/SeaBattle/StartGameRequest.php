<?php

namespace App\Http\Requests\Games\SeaBattle;

use Illuminate\Foundation\Http\FormRequest;

class StartGameRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user1_id' => 'required|int|min:1',
            'user2_id' => 'required|int|min:1',
        ];
    }
}
