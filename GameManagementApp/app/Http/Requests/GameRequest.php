<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get custom attribute names for validator errors in Hungarian.
     *
     * @return array
     */
    // public function attributes()
    // {
    //     return [
    //         'name' => 'Játék neve',
    //         'type' => 'Típus',
    //         'levelCount' => 'Szintek száma',
    //         'description' => 'Leírás',
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
            'name' => 'required|string|max:255|min:4',
            'type' => 'nullable|string|max:100|min:4',
            'levelCount' => 'required|integer|min:1|max:2000',
            'description' => 'nullable|string|max:1000',
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'A játék neve megadása kötelező.',
            'name.min' => 'A játék neve túl rövid, legalább 4 karakter szükséges.',
            'name.max' => 'A játék neve túl hosszú, maximum 255 karakter.',
            'type.string' => 'A típusnak szövegnek kell lennie.',
            'type.min' => 'A típus túl rövid, legalább 4 karakter szükséges.',
            'type.max' => 'A típus túl hosszú, maximum 100 karakter.',
            'levelCount.required' => 'A szintek számának megadása kötelező.',
            'levelCount.integer' => 'A szintek száma csak egész szám lehet.',
            'levelCount.min' => 'A szintek száma legalább 1 kell legyen.',
            'levelCount.max' => 'A szintek száma nem lehet több, mint 2000.',
            'description.string' => 'A leírásnak szövegnek kell lennie.',
            'description.max' => 'A leírás túl hosszú, maximum 1000 karakter.',
        ];
    }
    

}

