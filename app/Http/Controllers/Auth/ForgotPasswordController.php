<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:home_owners,email',
    ]);

    $response = Password::broker('home_owners')->sendResetLink(
        $request->only('email')
    );

    if ($response === Password::RESET_LINK_SENT) {
        return response()->json([
            'success' => true,
            'message' => 'Password reset link has been sent to your email address.'
        ], 200);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send the reset link. Please try again later.'
        ], 400);
    }
}
}
