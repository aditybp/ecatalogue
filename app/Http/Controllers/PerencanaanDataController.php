<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InformasiUmumService;
use App\Services\IdentifikasiKebutuhanService;
use Illuminate\Support\Facades\Validator;

class PerencanaanDataController extends Controller
{
    protected $informasiUmumService;
    protected $IdentifikasiKebutuhanService;

    public function __construct(
        InformasiUmumService $informasiUmumService, 
        IdentifikasiKebutuhanService $IdentifikasiKebutuhanService
        ) 
    {
        $this->informasiUmumService = $informasiUmumService;
        $this->IdentifikasiKebutuhanService = $IdentifikasiKebutuhanService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataInformasiUmumById($id)
    {
        try {
            $getDataInformasiUmum = $this->informasiUmumService->getDataInformasiUmumById($id);
            if (!$getDataInformasiUmum) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data informasi umum id '. $id . ' ditemukan!',
                    'data' => $getDataInformasiUmum
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data informasi umum id '. $id . ' tidak ditemukan!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function storeInformasiUmumData(Request $request)
    {
        $rules = [
            'tipe_informasi_umum' => 'required',
            'kode_rup' => 'required',
            'nama_paket' => 'required',
            'nama_ppk' => 'required',
            'jabatan_ppk' => 'required',
        ];
        if ($request->tipe_informasi_umum == 'manual') {
            $rules = array_merge($rules, [
                'nama_balai' => 'required',
                'tipologi' => 'required',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'validasi gagal!',
                'data' => []
            ]);
        } 

        $checkNamaPaket = $this->informasiUmumService->checkNamaPaket($request->nama_paket);
        if ($checkNamaPaket) {
            return response()->json([
                'status' => 'error',
                'message' => 'paket ' .$request->nama_paket. ' sudah / sedang diproses!',
                'data' => []
            ]);
        }

        try {
            $saveInformasiUmum = $this->informasiUmumService->saveInformasiUmum($request);
            if (!$saveInformasiUmum) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan data!',
                    'data' => []
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'data' => $saveInformasiUmum
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan pengguna',    
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getInformasiUmumByPerencanaanId($id) 
    {
        try {
            $perencanaanData = $this->informasiUmumService->getInformasiUmumByPerencanaanId($id);
            if (!$perencanaanData) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mendapatkan data dengan id '.$id,    
                    'data' => []
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil didapat',
                'data' => $perencanaanData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan pengguna',    
                'error' => $e->getMessage()
            ]);
        }
    }

    public function listAllPerencanaanData()
    {
        
    }

    public function getInformasiUmumFromSipasti(Request $request) 
    {
        
    }

    public function getTipologiFromSipasti(Request $request)
    {
        
    }

    public function storeIdentifikasiKebutuhan(Request $request)
    {
        $rules = [
            'material' => 'required|array',
                'material.*.nama_material' => 'required',
                'material.*.satuan' => 'required',
                'material.*.spesifikasi' => 'required',
                'material.*.ukuran' => 'required',
                'material.*.kodefikasi' => 'required',
                'material.*.kelompok_material' => 'required',
                'material.*.jumlah_kebutuhan' => 'required',
                'material.*.merk' => 'required',
                'material.*.provincies_id' => 'required',
                'material.*.cities_id' => 'required',
            'peralatan' => 'required|array',
                'peralatan.*.nama_peralatan' => 'required',
                'peralatan.*.satuan' => 'required',
                'peralatan.*.spesifikasi' => 'required',
                'peralatan.*.kapasitas' => 'required',
                'peralatan.*.kodefikasi' => 'required',
                'peralatan.*.kelompok_peralatan' => 'required',
                'peralatan.*.jumlah_kebutuhan' => 'required',
                'peralatan.*.merk' => 'required',
                'peralatan.*.provincies_id' => 'required',
                'peralatan.*.cities_id' => 'required',
            'tenaga_kerja' => 'required|array',
                'tenaga_kerja.*.jenis_tenaga_kerja' => 'required',
                'tenaga_kerja.*.satuan' => 'required',
                'tenaga_kerja.*.jumlah_kebutuhan' => 'required',
                'tenaga_kerja.*.kodefikasi' => 'required',
                'tenaga_kerja.*.provincies_id' => 'required',
                'tenaga_kerja.*.cities_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed!',
                'errors' => $validator->errors()
            ]);
        }
        try {
            $materialResult = [];
            foreach ($request->material as $material) {
                $this->IdentifikasiKebutuhanService->storeMaterial($material);
            }

            $peralatanResult = [];
            foreach ($request->peralatan as $peralatan) {
                $this->IdentifikasiKebutuhanService->storePeralatan($peralatan);
            }

            $tenagaKerjaResult = [];
            foreach ($request->tenaga_kerja as $tenagaKerja) {
                $this->IdentifikasiKebutuhanService->storeTenagaKerja($tenagaKerja);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan!',
                'data' => [
                    'material' => $materialResult,
                    'peralatan' => $peralatanResult,
                    'tenaga_kerja' => $tenagaKerjaResult,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data!',
                'error' => $e->getMessage()
            ]);
        }
    }

}
