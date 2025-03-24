<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListingRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return $this->rulesForCreate();
            case 'PATCH':
                return $this->rulesForUpdate();
            default:
                return [];
        }
    }
    public function rulesForCreate(): array
{
    return [
        'user_plants_id' => 'required|integer|exists:user_plants,id',
        'title' => 'required|string|max:255|min:2',
        'description' => 'required|string|max:1000',
        'city' => 'required|string|max:100',
        'media.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        //there should be 1-10 img
        'price' => 'required|integer|min:0',
    ];
}

public function rulesForUpdate(): array
{
    return [
        'user_plants_id' => 'sometimes|integer|exists:user_plants,id',
        'title' => 'sometimes|string|max:255|min:2',
        'description' => 'sometimes|string|max:1000',
        'city' => 'sometimes|string|max:100',
        'media.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        //there should be 1-10 img
        'price' => 'sometimes|integer|min:0',
    ];
}

public function messages(): array
{
    return [
        // User Plants ID
        //TODO
        'user_plants_id.required' => 'A növény azonosítójának megadása kötelező.',
        'user_plants_id.integer' => 'A növény azonosítójának egész számnak kell lennie.',
        'user_plants_id.exists' => 'A kiválasztott növény nem létezik.',

        // Title
        'title.required' => 'A cím megadása kötelező.',
        'title.string' => 'A címnek szövegnek kell lennie.',
        'title.max' => 'A cím legfeljebb 255 karakter hosszú lehet.',
        'title.min' => 'A címnek legalább 2 karakter hosszúnak kell lennie.',

        // Description
        'description.required' => 'A leírás megadása kötelező.',
        'description.string' => 'A leírásnak szövegnek kell lennie.',
        'description.max' => 'A leírás legfeljebb 1000 karakter hosszú lehet.',

        // City
        'city.required' => 'A város megadása kötelező.',
        'city.string' => 'A városnak szövegnek kell lennie.',
        'city.max' => 'A város neve legfeljebb 100 karakter hosszú lehet.',

        // Media
        'media.required' => 'A médiafájl megadása kötelező.',
        'media.image.*' => 'A médiafájlnak képnek kell lennie.',
        'media.mimes.*' => 'A médiafájl csak jpeg, png, jpg, gif vagy svg formátumú lehet.',
        'media.max.*' => 'A médiafájl legfeljebb 2048 kilobájt méretű lehet.',

        // Price
        'price.required' => 'Az ár megadása kötelező.',
        'price.integer' => 'Az árnak egész számnak kell lennie.',
        'price.min' => 'Az árnak legalább 0-nak kell lennie.',
    ];
}

}
