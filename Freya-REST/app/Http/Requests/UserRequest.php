<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

//All the posible fields with most likely     
// return [
//     'username' => 'required|string|unique:users,username|max:255',
//     'email' => 'required|email|unique:users,email|max:255',
//     'city' => 'required|string|max:255',
//     'birthdate' => 'required|date|before:today',
//     'password' => 'required|string|min:8|confirmed',
//     'role_id' => 'required|exists:roles,id',
//     'active' => 'boolean',
// ];

public function rules(): array
{
    // Determine which rules to use based on the route or request purpose
    if ($this->routeIs('register')) {
        return $this->rulesForRegister();
    } elseif ($this->routeIs('login')) {
        return $this->rulesForLogin();
    } elseif ($this->routeIs('role-update')){
        return $this->rulesForRolesUpdate();
    }elseif ($this->isMethod('patch')) {
        return $this->rulesForUpdate();
    }elseif($this->routeIs('forgot-password')){
        return $this->rulesForForgotPassword();
    }elseif($this->routeIs('password-reset')){
        return $this->rulesForPassworReset();
    }

    return [];
}

public function rulesForRegister(): array
{
    return [
        'username' => 'required|string|unique:users,username|max:255|min:4',
        'email' => 'required|email|unique:users,email|max:255',
        'password' => 'required|string|min:8|confirmed',
    ];
}

public function rulesForLogin(): array
{
    return [
        'email' => 'required|email|max:255',
        'password' => 'required|string|min:8',
    ];
}

public function rulesForUpdate(): array
{
    return [
        'username' => 'sometimes|string|unique:users,username|max:255',
        'email' => 'sometimes|email|unique:users,email|max:255',
        'city' => 'sometimes|string|max:255',
        'birthdate' => 'sometimes|date|before:today',
        'active' => 'sometimes|boolean',
    ];
}

public function rulesForRolesUpdate(): array
{
    return [
        'role_id' => 'required|exists:roles,id',
    ];
}

public function rulesForForgotPassword(): array
{
    return [
        'email' => 'required|email|exists:users,email',
    ];
}

public function rulesForPasswordReset(): array
{
    return [
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
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
            'amail.exists' => 'A megott email címmel nem létezik fehasználónk',
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
