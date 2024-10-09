<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\users;
use App\Services\UserService;
use App\Services\LoginService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth; 
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Contracts\Providers\Auth as ProvidersAuth;
use PHPOpenSourceSaver\JWTAuth\Http\Parser\AuthHeaders;
use Throwable;

class LoginController extends Controller
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

    public function login(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'validasi error',
                'error' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('username', 'password');
        try {

            if (!auth('api')->attempt($credentials)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal login!',    
                    'error' => 'Invalid credentials'
                ]);
            }

            $account = auth('api')->user();
            $token = JWTAuth::fromUser($account);

            return response()->json([
                'status' => 'success',
                'message' => 'berhasil login!',
                'token' => $token,
                'data' => $account
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal login!',    
                'error' => $th->getMessage()
            ]);
        }
        
    }

    public function logout()
    {
        auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Sukses Logout!'
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'berhasil refresh!',
            'token' => Auth::refresh(),
            'data' => Auth::user()
        ]);
    }
    
}
