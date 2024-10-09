<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\LoginService;
use App\Models\Accounts;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AccountController extends Controller
{
    protected $userService;
    protected $loginService;
    
    public function __construct(
        UserService $userService, 
        LoginService $loginService
    )
    {
        $this->userService = $userService;
        $this->loginService = $loginService;
    }

    public function sendUsernameAndEmail($userId)
    {
        $checkUserId = $this->userService->checkUserIfExist($userId);
        
        if (!$checkUserId) {
            return response()->json([
                'status' => 'error',
                'message' => 'user id '. $userId .' tidak dapat ditemukan!',
                'data' => []
            ]);
        }

        try {
            $generatePassword = (string) Str::random(5);
            
            $accounts = Accounts::create([
                'user_id' => $checkUserId['id'],
                'username' => $checkUserId['email'],
                'password' =>  Hash::make($generatePassword),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Accounts berhasil disimpan',
                'data' => $accounts,
                'debug' => $generatePassword
            ]);

        } catch (Exception $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan accounts!',
                'error' => $th
            ]);
        }
    }
}
