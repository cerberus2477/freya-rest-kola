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
     * Get custom attribute names for validator errors.
     *
     * @return array
     */
    // public function attributes()
    // {
    //     return [
    //         'username' => 'Felhasználónév',
    //         'password' => 'Jelszó',
    //         'email' => 'Email cím',
    //         'joinDate' => 'Csatlakozás dátuma',
    //         'age' => 'Életkor',
    //         'occupation' => 'Foglalkozás',
    //         'gender' => 'Nem',
    //         'city' => 'Város',
    //     ];
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255|min:4|unique:players,username',
            'password' => 'required|string|min:8|max:255',
            'email' => 'required|string|email|max:255|unique:players,email',
            'joinDate' => 'required|date|before_or_equal:today|after_or_equal:1900-01-01',
            'age' => 'nullable|integer|min:13|max:120',
            'occupation' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:255',
        ];
    }
    
    public function messages(): array
    {
        return [
            'username.required' => 'A felhasználónév megadása kötelező.',
            'username.string' => 'A felhasználónévnek szövegnek kell lennie.',
            'username.min' => 'A felhasználónév túl rövid, legalább 4 karakter szükséges.',
            'username.max' => 'A felhasználónév túl hosszú, maximum 255 karakter.',
            'username.unique' => 'A felhasználónév már létezik.',
            'password.required' => 'A jelszó megadása kötelező.',
            'password.string' => 'A jelszónak szövegnek kell lennie.',
            'password.min' => 'A jelszónak legalább 8 karakterből kell állnia.',
            'password.max' => 'A jelszó túl hosszú, maximum 255 karakter.',
            'email.required' => 'Az email megadása kötelező.',
            'email.string' => 'Az emailnek szövegnek kell lennie.',
            'email.email' => 'Az email formátuma érvénytelen.',
            'email.max' => 'Az email túl hosszú, maximum 255 karakter.',
            'email.unique' => 'Ez az email már regisztrálva van.',
            'joinDate.required' => 'A csatlakozási dátum megadása kötelező.',
            'joinDate.date' => 'A csatlakozási dátum érvénytelen.',
            'joinDate.before_or_equal' => 'A csatlakozási dátum nem lehet későbbi a mai dátumnál.',
            'joinDate.after_or_equal:1900-01-01' => 'A csatlakozási dátum nem lehet 1900-nál korábbi.',
            'age.integer' => 'A kor csak egész szám lehet.',
            'age.min' => 'A kor nem lehet kevesebb, mint 13 év.',
            'age.max' => 'A kor nem lehet több, mint 120 év.',
            'occupation.string' => 'A foglalkozás szövegnek kell lennie.',
            'occupation.max' => 'A foglalkozás túl hosszú, maximum 255 karakter.',
            'gender.string' => 'A nem szövegnek kell lennie.',
            'gender.max' => 'A nem túl hosszú, maximum 50 karakter.',
            'city.string' => 'A város szövegnek kell lennie.',
            'city.max' => 'A város neve túl hosszú, maximum 255 karakter.',
        ];
    }
    


}
