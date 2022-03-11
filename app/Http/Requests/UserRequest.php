<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:5', 'max:25', 'unique:users', 'regex:/^[a-zA-ZÁÉÍÓÖŐÚÜŰáéíóöőúüű]+$/u'],
            'email' => ['required', 'email', 'min:6', 'max:50', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase(), 'regex:/(\W|\d)+/u'],
        ];
    }
}
