<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlantRequest extends FormRequest
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
            'name' => 'required|string|max:255|min:4',
            'latin_name' => 'nullable|string|max:100|min:4',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'A növény neve megadása kötelező.',
            'name.min' => 'A növény neve túl rövid, legalább 4 karakter szükséges.',
            'name.max' => 'A növény neve túl hosszú, maximum 255 karakter.',
            'latin_name.required' => 'A növény latin neve megadása kötelező.',
            'latin_name.min' => 'A növény latin neve túl rövid, legalább 4 karakter szükséges.',
            'latin_name.max' => 'A növény latin neve túl hosszú, maximum 255 karakter.',
        ];
    }


}

