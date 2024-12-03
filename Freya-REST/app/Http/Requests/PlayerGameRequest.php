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
            'gamerTag' => 'required|string|max:255|min:2',
            'hoursPlayed' => 'required|integer|min:0|max:2000',
            'lastPlayedDate' => [
                'required',
                'date',
                'after_or_equal:joinDate', // Ensure lastPlayedDate >= joinDate
                'after_or_equal:1900-01-01', // Minimum valid date
                'before_or_equal:9999-12-31', // Maximum valid date
            ],
            'joinDate' => [
                'required',
                'date',
                'after_or_equal:1900-01-01', // Minimum valid date
                'before_or_equal:9999-12-31', // Maximum valid date
            ],
            'currentLevel' => 'nullable|integer|min:0',
        ];
    }

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
            'gamerTag.min' => 'A gamer tag túl rövid, legalább 2 karakter szükséges.',
            'hoursPlayed.required' => 'Az órák száma megadása kötelező.',
            'hoursPlayed.integer' => 'Az órák száma csak egész szám lehet.',
            'hoursPlayed.min' => 'Az órák száma nem lehet negatív.',
            'hoursPlayed.max' => 'Az órák száma maximum 2000 lehet.',
            'lastPlayedDate.required' => 'A legutóbbi játék dátumának megadása kötelező.',
            'lastPlayedDate.date' => 'A legutóbbi játék dátuma érvénytelen.',
            'lastPlayedDate.after_or_equal' => 'A legutóbbi játék dátuma nem lehet korábbi a csatlakozási dátumnál.',
            'lastPlayedDate.after_or_equal:1900-01-01' => 'A legutóbbi játék dátuma nem lehet 1900-nál korábbi.',
            'lastPlayedDate.before_or_equal:9999-12-31' => 'A legutóbbi játék dátuma nem lehet 9999-nél későbbi.',
            'joinDate.required' => 'A csatlakozási dátum megadása kötelező.',
            'joinDate.date' => 'A csatlakozási dátum érvénytelen.',
            'joinDate.after_or_equal:1900-01-01' => 'A csatlakozási dátum nem lehet 1900-nál korábbi.',
            'joinDate.before_or_equal:9999-12-31' => 'A csatlakozási dátum nem lehet 9999-nél későbbi.',
            'currentLevel.integer' => 'A szintnek egész számnak kell lennie.',
            'currentLevel.min' => 'A szint nem lehet negatív.',
        ];
    }
}