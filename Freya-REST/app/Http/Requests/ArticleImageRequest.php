<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class ArticleImageRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'media.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:51200',
        ];
    }

    public function messages(): array
    {
        return [
            'media.required' => 'A médiafájl megadása kötelező.',
            'media.*.image' => 'A médiafájlnak képnek kell lennie.',
            'media.*.mimes' => 'A médiafájl csak jpeg, png, jpg, gif, svg vagy webp formátumú lehet.',
            'media.*.max' => 'A médiafájl legfeljebb 51200 kilobájt méretű lehet.',
        ];
    }
}

