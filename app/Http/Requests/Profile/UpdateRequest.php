<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|max:255|min:4|unique:users|sometimes|nullable',
            'email' => 'string|max:255|email|unique:users|sometimes|nullable',
            'avatar' => 'image|mimes:jpeg,png,jpg|max:2048|sometimes|nullable',
        ];
    }
}
