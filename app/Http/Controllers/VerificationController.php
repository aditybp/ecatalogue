<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\Users;
use App\Models\Users as ModelsUsers;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    public function verify($id, $hash)
    {
        $user = ModelsUsers::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'invalid verification link.', 403]);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(['message' => 'Email successfully verified.']);
    }
}
