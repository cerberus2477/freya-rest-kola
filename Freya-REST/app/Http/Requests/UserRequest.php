<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust authorization logic as needed
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|unique:users,username|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'city' => 'required|string|max:255',
            'birthdate' => 'required|date|before:today',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'A felhasználónév megadása kötelező.',
            'username.unique' => 'A felhasználónév már foglalt.',
            'username.max' => 'A felhasználónév legfeljebb 255 karakter hosszú lehet.',
            'email.required' => 'Az email megadása kötelező.',
            'email.email' => 'Az email formátuma érvénytelen.',
            'email.unique' => 'Ez az email már használatban van.',
            'email.max' => 'Az email legfeljebb 255 karakter hosszú lehet.',
            'city.required' => 'A város megadása kötelező.',
            'city.max' => 'A város neve legfeljebb 255 karakter hosszú lehet.',
            'birthdate.required' => 'A születési dátum megadása kötelező.',
            'birthdate.date' => 'A születési dátum formátuma érvénytelen.',
            'birthdate.before' => 'A születési dátumnak a mai nap előtt kell lennie.',
            'password.required' => 'A jelszó megadása kötelező.',
            'password.min' => 'A jelszónak legalább 8 karakter hosszúnak kell lennie.',
            'password.confirmed' => 'A jelszavak nem egyeznek.',
            'role_id.required' => 'A szerepkör kiválasztása kötelező.',
            'role_id.exists' => 'A megadott szerepkör nem létezik.',
            'active.boolean' => 'Az aktív mező értéke csak igaz vagy hamis lehet.',
        ];
    }
}
