<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends BaseController
{
    public function sendResetLinkEmail(Request $request)
    {
        // Send the password reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Return a JSON response based on the status
        return $status === Password::RESET_LINK_SENT
            ? $this->jsonResponse(200, $status)
            : $this->jsonResponse(400, $status);
    }
}
