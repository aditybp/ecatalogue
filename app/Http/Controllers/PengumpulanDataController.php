<?php

namespace App\Http\Controllers;

use App\Models\Pengawas;
use App\Models\PengolahData;
use App\Models\PetugasLapangan;
use App\Services\PengumpulanDataService;
use App\Services\PerencanaanDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengumpulanDataController extends Controller
{
    protected $pengumpulanDataService;
    protected $perencanaanDataService;

    public function __construct(
        PengumpulanDataService $pengumpulanDataService,
        PerencanaanDataService $perencanaanDataService
    ) {
        $this->pengumpulanDataService = $pengumpulanDataService;
        $this->perencanaanDataService = $perencanaanDataService;
    }

    public function storeTeamTeknisBalai(Request $request)
    {
        $rules = [
            'nama_team' => 'required',
            'ketua_team' => 'required',
            'sekretaris_team' => 'required',
            'anggota' => 'required',
            'sk_penugasan' => 'required|file|mimes:pdf,doc,docx|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'validasi gagal!',
                'data' => []
            ]);
        }

        try {
            if ($request->hasFile('sk_penugasan')) {
                $filePath = $request->file('sk_penugasan')->store('sk_penugasan');
            }

            $arrayAnggota = explode(',', $request->input('anggota'));
            $arrayAnggota = array_map('intval', $arrayAnggota);

            $data = [
                'nama_team' => $request->input('nama_team'),
                'ketua_team' => $request->input('ketua_team'),
                'sekretaris_team' => $request->input('sekretaris_team'),
                'anggota' => $arrayAnggota,
                'sk_penugasan' => $filePath
            ];

            $saveData = $this->pengumpulanDataService->storeTeamPengumpulanData($data);
            if ($saveData) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil disimpan',
                    'data' => $saveData
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getTeamPengumpulanData()
    {
        $data = $this->pengumpulanDataService->getAllTeamPengumpulanData();
        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil didapat',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Mendapatkan Data',
                'data' => []
            ]);
        }
    }

    public function assignTeamPengumpulanData(Request $request)
    {
        $rules = [
            'id_pengumpulan_data' => 'required',
            'id_team_pengumpulan_data' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'validasi gagal!',
                'error' => $validator->errors()
            ]);
        }

        try {
            $assignTeam = $this->pengumpulanDataService->assignTeamPengumpulanData($request);
            if ($assignTeam) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil disimpan',
                    'data' => $assignTeam
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function listPengumpulanData()
    {
        $data = $this->perencanaanDataService->tableListPerencanaanData(config('constants.STATUS_PENGUMPULAN'));
        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => config('constants.SUCCESS_MESSAGE_GET'),
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => config('constants.ERROR_MESSAGE_GET'),
                'data' => []
            ]);
        }
    }

    public function storePengawas(Request $request)
    {
        $rules = [
            'sk_penugasan' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'user_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'validasi gagal!',
                'error' => $validator->errors()
            ]);
        }

        $array = explode(',', $request['user_id']);
        try {
            $data = [];

            if ($request->hasFile('sk_penugasan')) {
                $filePath = $request->file('sk_penugasan')->store('public/sk_penugasan');
            }

            foreach (collect($array) as $value) {
                $data[] = [
                    'user_id' => $value,
                    'sk_penugasan' => $filePath
                ];
            }

            $save = Pengawas::insert($data);
            if ($save) {
                return response()->json([
                    'status' => 'success',
                    'message' => config('constants.SUCCESS_MESSAGE_SAVE'),
                    'data' => $data
                ]);
            }
        } catch (\Exception $th) {
            return response()->json([
                'status' => 'error',
                'message' => config('constants.ERROR_MESSAGE_SAVE'),
                'error' => $th->getMessage()
            ]);
        }
    }

    public function listUser()
    {
        $getData = $this->pengumpulanDataService->listUserPengumpulan();
        if ($getData) {
            return response()->json([
                'status' => 'success',
                'message' => config('constants.SUCCESS_MESSAGE_GET'),
                'data' => $getData
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => config('constants.ERROR_MESSAGE_GET'),
                'data' => []
            ]);
        }
    }

    public function storePetugasLapangan(Request $request)
    {
        $rules = [
            'sk_penugasan' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'user_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'validasi gagal!',
                'error' => $validator->errors()
            ]);
        }

        $array = explode(',', $request['user_id']);
        try {
            $data = [];

            if ($request->hasFile('sk_penugasan')) {
                $filePath = $request->file('sk_penugasan')->store('sk_penugasan');
            }

            foreach (collect($array) as $value) {
                $data[] = [
                    'user_id' => $value,
                    'sk_penugasan' => $filePath
                ];
            }

            $save = PetugasLapangan::insert($data);
            if ($save) {
                return response()->json([
                    'status' => 'success',
                    'message' => config('constants.SUCCESS_MESSAGE_SAVE'),
                    'data' => $data
                ]);
            }
        } catch (\Exception $th) {
            return response()->json([
                'status' => 'error',
                'message' => config('constants.ERROR_MESSAGE_SAVE'),
                'error' => $th->getMessage()
            ]);
        }
    }

    public function storepengolahData(Request $request)
    {
        $rules = [
            'sk_penugasan' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'user_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'validasi gagal!',
                'error' => $validator->errors()
            ]);
        }

        $array = explode(',', $request['user_id']);
        try {
            $data = [];

            if ($request->hasFile('sk_penugasan')) {
                $filePath = $request->file('sk_penugasan')->store('sk_penugasan');
            }

            foreach (collect($array) as $value) {
                $data[] = [
                    'user_id' => $value,
                    'sk_penugasan' => $filePath
                ];
            }

            $save = PengolahData::insert($data);
            if ($save) {
                return response()->json([
                    'status' => 'success',
                    'message' => config('constants.SUCCESS_MESSAGE_SAVE'),
                    'data' => $data
                ]);
            }
        } catch (\Exception $th) {
            return response()->json([
                'status' => 'error',
                'message' => config('constants.ERROR_MESSAGE_SAVE'),
                'error' => $th->getMessage()
            ]);
        }
    }

    public function listPengawas()
    {
        $data = $this->pengumpulanDataService->listPenugasan('pengawas');

        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => config('constants.SUCCESS_MESSAGE_GET'),
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => config('constants.ERROR_MESSAGE_GET'),
                'data' => []
            ]);
        }
    }

    public function listPengolahData()
    {
        $data = $this->pengumpulanDataService->listPenugasan('pengolah data');

        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => config('constants.SUCCESS_MESSAGE_GET'),
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => config('constants.ERROR_MESSAGE_GET'),
                'data' => []
            ]);
        }
    }

    public function listPetugasLapangan()
    {
        $data = $this->pengumpulanDataService->listPenugasan('petugas lapangan');

        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => config('constants.SUCCESS_MESSAGE_GET'),
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => config('constants.ERROR_MESSAGE_GET'),
                'data' => []
            ]);
        }
    }

    public function assignPengawas(Request $request)
    {
        $rules = [
            'id_user' => 'required',
            'pengumpulan_data_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'validasi gagal!',
                'error' => $validator->errors()
            ]);
        }

        try {
            $assign = $this->pengumpulanDataService->assignPenugasan('pengawas', $request->id_user, $request->pengumpulan_data_id);
            if ($assign) {
                return response()->json([
                    'status' => 'success',
                    'message' => config('constants.SUCCESS_MESSAGE_SAVE'),
                    'data' => $assign
                ]);
            }
        } catch (\Exception $th) {
            return response()->json([
                'status' => 'error',
                'message' => config('constants.ERROR_MESSAGE_SAVE'),
                'error' => $th->getMessage()
            ]);
        }
    }

    public function assignPengolahData(Request $request)
    {
        $rules = [
            'id_user' => 'required',
            'pengolah_data_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'validasi gagal!',
                'error' => $validator->errors()
            ]);
        }

        try {
            $assign = $this->pengumpulanDataService->assignPenugasan('pengolah data', $request->id_user, $request->pengolah_data_id);
            if ($assign) {
                return response()->json([
                    'status' => 'success',
                    'message' => config('constants.SUCCESS_MESSAGE_SAVE'),
                    'data' => $assign
                ]);
            }
        } catch (\Exception $th) {
            return response()->json([
                'status' => 'error',
                'message' => config('constants.ERROR_MESSAGE_SAVE'),
                'error' => $th->getMessage()
            ]);
        }
    }

    public function assignPetugasLapangan(Request $request)
    {
        $rules = [
            'id_user' => 'required',
            'pengumpulan_data_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'validasi gagal!',
                'error' => $validator->errors()
            ]);
        }

        try {
            $assign = $this->pengumpulanDataService->assignPenugasan('petugas lapangan', $request->id_user, $request->pengumpulan_data_id);
            if ($assign) {
                return response()->json([
                    'status' => 'success',
                    'message' => config('constants.SUCCESS_MESSAGE_SAVE'),
                    'data' => $assign
                ]);
            }
        } catch (\Exception $th) {
            return response()->json([
                'status' => 'error',
                'message' => config('constants.ERROR_MESSAGE_SAVE'),
                'error' => $th->getMessage()
            ]);
        }
    }
}
