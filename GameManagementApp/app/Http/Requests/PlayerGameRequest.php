<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlayerGameRequest extends FormRequest
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
            'playerID' => 'required|integer|exists:players,playerID',
            'gameID' => 'required|integer|exists:games,gameID',
            'gamerTag' => 'required|string|max:255',
            'hoursPlayed' => 'required|integer|min:0',
            'lastPlayedDate' => 'required|date',
            'joinDate' => 'required|date',
            'currentLevel' => 'nullable|integer|min:0',
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
    //         'playerID' => 'Játékos ID',
    //         'gameID' => 'Játék ID',
    //         'gamerTag' => 'Játékosnév (a játékban)',
    //         'hoursPlayed' => 'Játszott órák',
    //         'lastPlayedDate' => 'Legutóbb elérhető',
    //         'joinDate' => 'Csatlakozás dátuma',
    //         'currentLevel' => 'Aktuális szint',
    //     ];
    // }

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
            'joinDate.before_or_equal' => 'A csatlakozási dátumnak ma vagy korábbi dátumnak kell lennie.',
            'age.integer' => 'A kor egész szám kell legyen.',
            'age.min' => 'A kor nem lehet kevesebb, mint 13.',
            'occupation.string' => 'A foglalkozás szövegnek kell lennie.',
            'occupation.max' => 'A foglalkozás túl hosszú, maximum 255 karakter.',
            'gender.string' => 'A nem szövegnek kell lennie.',
            'gender.max' => 'A nem túl hosszú, maximum 50 karakter.',
            'gender.in' => 'A nem csak a következő értékek lehetnek: Férfi, Nő, Nem-binaris, Egyéb.',
            'city.string' => 'A város szövegnek kell lennie.',
            'city.max' => 'A város neve túl hosszú, maximum 255 karakter.',
        ];
    }

}
