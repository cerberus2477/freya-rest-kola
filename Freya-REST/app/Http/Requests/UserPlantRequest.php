<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPlantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust authorization logic as needed
    }

    public function rules(): array
    {
        // return [
        //     'user_id' => 'required|exists:users,id',
        //     'plant_id' => 'required|exists:plants,id',
        // ];

        return [
            'user_id' => 'required|exists:users,id',
            'plant_id' => [
                'required',
                'exists:plants,id',
                function ($attribute, $value, $fail) {
                    if (
                        \DB::table('user_plants')
                            ->where('user_id', $this->user_id)
                            ->where('plant_id', $value)
                            ->exists()
                    ) {
                        $fail('Ez a felhasználó és növény páros már létezik.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'A felhasználó azonosító megadása kötelező.',
            'user_id.exists' => 'A megadott felhasználó nem létezik.',
            'plant_id.required' => 'A növény azonosító megadása kötelező.',
            'plant_id.exists' => 'A megadott növény nem létezik.',
        ];

    }
}
