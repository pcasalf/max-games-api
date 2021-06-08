<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class AuthRegisterRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
                'unique:App\Models\User,email'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],
            'name' => [
                'required',
                'string'
            ],
            'last_name' => [
                'required',
                'string'
            ],
            'birthday' => [
                'required',
                'date',
                'before_or_equal:'.Carbon::now()->subYears(18)->format('Y-m-d')
            ]
        ];
    }

    /**
     * Messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'birthday.before_or_equal' => 'Debes ser mayor de edad para registrarte',
            'email.unique' => 'Este email ya ha sido utilizado',
            'password.confirmed' => 'La confirmación del password no coincide'
        ];
    }
}
