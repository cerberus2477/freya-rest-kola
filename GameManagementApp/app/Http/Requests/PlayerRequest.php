<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlayerRequest extends FormRequest
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
            'username' => 'required|string|max:255|unique:players,username',
            'password' => 'required|string|min:8|max:255',
            'email' => 'required|string|email|max:255|unique:players,email',
            'joinDate' => 'required|date|before_or_equal:today',
            'age' => 'nullable|integer|min:13',
            'occupation' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:50|in:Male,Female,Non-binary,Other',
            'city' => 'nullable|string|max:255',
        ];
    }

}
