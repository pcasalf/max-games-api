<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditProductRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
            ],
            'cover' => [
                'required',
                'string',
                'url'
            ],
            'description' => [
                'required',
                'string'
            ],
            'price' => [
                'required',
                'numeric',
                'min:1'
            ],
            'featured' => [
                'boolean',
            ],
            'online' => [
                'boolean',
            ]
        ];
    }
}
