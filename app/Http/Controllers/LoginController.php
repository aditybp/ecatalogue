<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\UserService;
use App\Services\LoginService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $userService;
    protected $loginService;

    public function __construct(
        UserService $userService,
        LoginService $loginService
    ) {
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

            $check = $this->loginService->getUserFromId($account['user_id']);
            $token = JWTAuth::customClaims(['role' => $check['role_name']])->fromUser($account);

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

    public function checkRole(Request $request)
    {
        try {
            $token = JWTAuth::parseToken();
            $payload = $token->getPayload();
            $role = $payload->get('role');
            if ($role) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Role retrieved successfully.',
                    'data' => $role,
                ]);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Role retrieved successfully.',
                    'data' => [],
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token is invalid or expired.',
                'error' => $e->getMessage()
            ], 401);
        }
    }
}
