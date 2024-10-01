<?php

namespace App\Http\Controllers;

use App\Models\users;
use App\Http\Requests\StoreusersRequest;
use App\Http\Requests\UpdateusersRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|integer',
            'email' => 'required|string|max:255',
            'nrp' => 'required|string|max:255',
            'satuan_kerja_id' => 'required',
            'balai_kerja_id' => 'required',
            'no_handphone' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'validasi gagal!',
                'errors' => $validator->errors()
            ], 422);
        }

        //tambah kondisi pengecekan nrp, nik, dan no hp tidak boleh sama di database if status = active

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
            $user->id_roles = 1;
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Pengguna berhasil disimpan',
                'data' => $user
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan pengguna',
                'error' => $th->getMessage()
            ], 500);
        }
        
    }

    function getUserById($id) 
    {
        try {
            $user = users::find($id);

            //tambahkan konsisi cek if data found dan if not found

            return response()->json([
                'status' => 'success',
                'message' => 'berhasil menampilkan data',
                'data' => $user
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil menampilkan data',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
