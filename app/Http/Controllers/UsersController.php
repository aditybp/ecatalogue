<?php

namespace App\Http\Controllers;

use App\Models\users;
use App\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|integer',
            'email' => 'required|string|email|max:255',
            'nrp' => 'required|string|max:255',
            'satuan_kerja_id' => 'required',
            'balai_kerja_id' => 'required',
            'no_handphone' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'validasi gagal!',
                'data' => []
            ]);
        }

        $checkNik = $this->userService->checkNik($request->nik);
        if ($checkNik) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nik sudah terdaftar!',
                'data' => []
            ]);
        }

        try {
            $user = new users();
            $user->nama_lengkap = $request->nama_lengkap;
            $user->no_handphone = $request->no_handphone;
            $user->nik = $request->nik;
            $user->email = $request->email;
            $user->nrp = $request->nrp;
            $user->surat_penugasan_url = $request->surat_penugasan_url;
            $user->satuan_kerja_id = $request->satuan_kerja_id;
            $user->balai_kerja_id = $request->balai_kerja_id;
            $user->status = 'register';
            $user->id_roles = 1; //menyusul tergantung ntarnya
            $user->save();

            event(new Registered($user)); //send email verification

            return response()->json([
                'status' => 'success',
                'message' => 'Pengguna berhasil disimpan',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan pengguna',    
                'error' => $th->getMessage()
            ]);
        }
        
    }

    public function getUserById($id) 
    {
        try {

            $getUser = $this->userService->checkUserIfExist($id);
            if (is_null($getUser)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'data dengan id '. $id .' tidak ditemukan!',
                    'data' => []
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'berhasil menampilkan data',
                'data' => $getUser
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'gagal mendaptakan data',
                'data' => []
            ]);
        }
    }
}
