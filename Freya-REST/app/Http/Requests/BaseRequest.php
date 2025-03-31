<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Controllers\BaseController;

abstract class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $response = BaseController::jsonResponse(
            422,
            "Nem megfelelő adatok",
            ['errors' => $validator->errors()]
        );

        throw new HttpResponseException($response);
    }
}