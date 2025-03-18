<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StageRequest extends FormRequest
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
            'name' => 'required|string|unique:stages,name|max:255|min:2',
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
            'name.required' => 'A fázis nevének megadása kötelező.',
            'name.string' => 'A fázis nevének szöveg típusú kell legyen.',
            'name.unique' => 'A fázis nevének egyedinek kell lennie.',
            'name.max' => 'A fázis neve nem lehet hosszabb, mint 255 karakter.',
            'name.min' => 'A fázis neve nem lehet rövidebb, mint 2 karakter.',
        ];
    }
}
