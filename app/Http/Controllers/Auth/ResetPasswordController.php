<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        // Return a view for the reset password form
        return view('auth.passwordreset', [
            'token' => $token,
            'email' => $request->email, // Email passed as query param in the reset link
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:home_owners,email',
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $response = Password::broker('home_owners')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($homeOwner, $password) {
                $homeOwner->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $response == Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password has been reset successfully.'], 200)
            : response()->json(['message' => 'Failed to reset password.'], 400);
    }
}
