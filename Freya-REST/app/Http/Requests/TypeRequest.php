<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class TypeRequest extends BaseRequest
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
        return $this->storeRules();
    }

    /**
     * Get the validation rules that apply to the store request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    protected function storeRules(): array
    {
        return [
            'name' => 'required|string|unique:types,name|max:255|min:3',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'A típusnév megadása kötelező.',
            'name.unique' => 'A megadott típus már létezik.',
            'name.max' => 'A típus maximum 255 karakter hosszú lehet.',
            'name.min' => 'A típus minimum 3 karakter hosszú kell legyen.',
        ];
    }
}
