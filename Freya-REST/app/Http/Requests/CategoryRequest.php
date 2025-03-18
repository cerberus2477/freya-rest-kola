<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:categories,name|max:255|min:3',
        ];
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'A kategória nevének megadása kötelező.',
            'name.string' => 'A kategória nevének szöveg típusú kell legyen.',
            'name.unique' => 'A kategória nevének egyedinek kell lennie.',
            'name.max' => 'A kategória neve nem lehet hosszabb, mint 255 karakter.',
            'name.min' => 'A kategória neve nem lehet rövidebb, mint 3 karakter.',
        ];
    }
}
