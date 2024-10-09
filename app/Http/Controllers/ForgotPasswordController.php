<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Notifications\ResetPasswordNorification;
use Illuminate\Support\Facades\Mail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Auth\Notifications\ResetPassword;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        
    }

    public function sendResetLinkEmail(Request $request) 
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $users = Accounts::where('username', $request->email)->first();
        //dd($users);

        $token = JWTAuth::fromUser($users);
        //dd($token);

        $users->notify(new ResetPasswordNorification($token));

        return response()->json(['message' => 'Reset password link sent']);
    }

    public function resetPassword(Request $request) 
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required',
        ]);

        try {
            $users = JWTAuth::authenticate($request->token);

            if (!$users || $users->email !== $request->email) {
                return response()->json(['message' => 'Invalid token or email'], 400);
            }

            $users->password = bcrypt($request->password);
            $users->save();

            return response()->json(['message' => 'Password successfully reset']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Could not reset password'], 500);
        }
    }
}
