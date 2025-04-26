<?php

namespace App\Http\Requests;

use App\Models\Plant;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UserPlantRequest extends BaseRequest
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
                'integer',
                'exists:plants,id',
                $this->uniquePlantStageRule()
            ],
            'stage_id' => 'required|integer|exists:stages,id',
            'count' => 'nullable|integer|min:1'
        ];
    }
    public function rulesForUpdate(){
        return[
            'plant_id' => [
                'sometimes',
                'integer',
                'exists:plants,id',
                $this->uniquePlantStageRule()
            ],
            'stage_id' => 'sometimes|integer|exists:stages,id',
            'count' => 'nullable|integer|min:1'
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
            'plant_id.integer' => 'A növény azonosítójának egész számnak kell lennie.',
            'stage_id.required' => 'A szakasz azonosító megadása kötelező.',
            'stage_id.exists' => 'A megadott szakasz nem létezik.',
            'stage_id.integer' => 'A szakasz azonosítójának egész számnak kell lennie.',
            'count.integer' => 'A mennyiségnek egész számnak kell lennie.',
            'count.min' => 'A mennyiségnek legalább 1-nek kell lennie.'
        ];
    }
}
