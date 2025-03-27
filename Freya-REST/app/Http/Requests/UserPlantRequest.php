<?php

namespace App\Http\Requests;

use App\Models\Plant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserPlantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust authorization logic as needed
    }

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


    public function rulesForCreate(){
        return [
            'plant_id' => [
                'required',
                'numeric',
                'exists:plants,id',
                $this->uniquePlantStageRule()
            ],
            'stage_id' => 'required|numeric|exists:stage, id',
            'count' => 'nullable|numeric|min:1'
        ];
    }
    public function rulesForUpdate(){
        return[
            'plant_id' => [
                'sometimes',
                'numeric',
                'exists:plants,id',
                $this->uniquePlantStageRule()
            ],
            'stage_id' => 'sometimes|numeric|exists:stages, id',
            'count' => 'nullable|numeric|min:1'
        ];
    }
    protected function uniquePlantStageRule()
    {
        return Rule::unique('user_plants')
            ->where('user_id', $this->user()->id)
            ->where('plant_id', $this->input('plant_id'))
            ->where('stage_id', $this->input('stage_id'))
            ->ignore($this->route('userPlant')?->id); // For update operations
    }


    public function messages(): array
    {
        return [
            'plant_id.required' => 'A növény azonosító megadása kötelező.',
            'plant_id.exists' => 'A megadott növény nem létezik.',
            'plant_id.unique' => 'Ez a felhasználó már rendelkezik ezzel a növénnyel ebben az élet szakaszban.',
            'stage_id.required' => 'A szakasz azonosító megadása kötelező.',
            'stage_id.exists' => 'A megadott szakasz nem létezik.',
            'count.numeric' => 'A mennyiségnek számnak kell lennie.',
            'count.min' => 'A mennyiségnek legalább 1-nek kell lennie.'
        ];

    }
}
