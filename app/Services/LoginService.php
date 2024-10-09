<?php

namespace App\Services;

use App\Models\Accounts;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginService
{
    public function checkUsernameAndPassword($username, $password) 
    {
        return Accounts::where('username', $username)
                        ->where('password', $password)
                        ->first();   
    }

    public function saveToken($idAccounts, $token) 
    {
        $account = Accounts::findOrFail($idAccounts);
        
        if ($token && $account) {
            $update = $account->update([
                'remember_token' => $token
            ]);
    
            return $update;
        }
    
        return $token;
        
    }
}
