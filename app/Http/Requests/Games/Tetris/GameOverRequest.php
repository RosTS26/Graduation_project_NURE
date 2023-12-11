<?php

namespace App\Http\Requests\Games\Tetris;

use Illuminate\Foundation\Http\FormRequest;

class GameOverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'string',
            'score' => 'int',
        ];
    }
}
