<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
        'title' => 'required|string|unique:articles,title|max:255|min:2',
        'plant_id' => 'nullable|integer|exists:plants,id',
        'author_id' => 'required|integer|exists:users,id',
        'category_id' => 'nullable|integer|exists:categories,id',
        'description' => 'required|string|max:300',
        'content' => 'required|string|max:50000',
        'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'source' => 'nullable|string|max:1000',
    ];
}

public function rulesForUpdate(): array
{
    return [
        'title' => 'sometimes|string|unique:articles,title',
        'plant_id' => 'nullable|integer|exists:plants,id',
        'author_id' => 'sometimes|integer|exists:users,id',
        'category_id' => 'nullable|integer|exists:categories,id',
        'description' => 'sometimes|string|max:300',
        'content' => 'sometimes|string|max:50000',
        'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'source' => 'nullable|string|max:1000',
    ];
}
public function messages(): array
{
    return [
        // Title
        'title.required' => 'A cím megadása kötelező.',
        'title.string' => 'A címnek szövegnek kell lennie.',
        'title.unique' => 'Ez a cím már foglalt.',
        'title.max' => 'A cím legfeljebb 255 karakter hosszú lehet.',
        'title.min' => 'A címnek legalább 2 karakter hosszúnak kell lennie.',

        // Plant ID
        'plant_id.exists' => 'A kiválasztott növény nem létezik.',

        // Author ID
        'author_id.required' => 'A szerző megadása kötelező.',
        'author_id.exists' => 'A kiválasztott szerző nem létezik.',

        // Category ID
        'category_id.exists' => 'A kiválasztott kategória nem létezik.',

        // Description
        'description.required' => 'A leírás megadása kötelező.',
        'description.string' => 'A leírásnak szövegnek kell lennie.',
        'description.max' => 'A leírás legfeljebb 300 karakter hosszú lehet.',

        // Content
        'content.required' => 'A tartalom megadása kötelező.',
        'content.max' => 'A tartalom legfeljebb 50000 karakter hosszú lehet.',

        // Source
        'source.string' => 'A forrásnak szövegnek kell lennie.',
        'source.max' => 'A forrásjegyzék legfeljebb 1000 karakter hosszú lehet.',
    ];
}

    
}
