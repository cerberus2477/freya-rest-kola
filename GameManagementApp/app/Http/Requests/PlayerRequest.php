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
            'username' => 'required|string|max:255|min:4|unique:players,username',
            'password' => 'required|string|min:8|max:255',
            'email' => 'required|string|email|max:255|unique:players,email',
            'joinDate' => 'required|date|before_or_equal:today',
            'age' => 'nullable|integer|min:13',
            'occupation' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:50|in:Male,Female,Non-binary,Other',
            'city' => 'nullable|string|max:255',
        ];
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


    public function messages(): array
    {
        return [
            'playerID.required' => 'A játékos kiválasztása kötelező.',
            'playerID.integer' => 'A játékos azonosítójának egész számnak kell lennie.',
            'playerID.exists' => 'A kiválasztott játékos nem létezik.',
            'gameID.required' => 'A játék kiválasztása kötelező.',
            'gameID.integer' => 'A játék azonosítójának egész számnak kell lennie.',
            'gameID.exists' => 'A kiválasztott játék nem létezik.',
            'gamerTag.required' => 'A gamer tag megadása kötelező.',
            'gamerTag.string' => 'A gamer tagnak szövegnek kell lennie.',
            'gamerTag.max' => 'A gamer tag túl hosszú, maximum 255 karakter.',
            'hoursPlayed.required' => 'Az órák száma megadása kötelező.',
            'hoursPlayed.integer' => 'Az órák száma csak egész szám lehet.',
            'hoursPlayed.min' => 'Az órák száma nem lehet negatív.',
            'lastPlayedDate.required' => 'A legutóbbi játék dátumának megadása kötelező.',
            'lastPlayedDate.date' => 'A legutóbbi játék dátuma érvénytelen.',
            'joinDate.required' => 'A csatlakozási dátum megadása kötelező.',
            'joinDate.date' => 'A csatlakozási dátum érvénytelen.',
            'currentLevel.integer' => 'A szintnek egész számnak kell lennie.',
            'currentLevel.min' => 'A szint nem lehet negatív.',
        ];
    }


}
