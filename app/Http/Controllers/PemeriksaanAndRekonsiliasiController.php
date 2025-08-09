<?php

namespace App\Http\Controllers;

use App\Services\PengumpulanDataService;
use App\Services\PerencanaanDataService;
use Illuminate\Http\Request;

class PemeriksaanAndRekonsiliasiController extends Controller
{
    protected $perencanaanDataService;
    protected $pengumpulanDataService;

    public function __construct(
        PerencanaanDataService $perencanaanDataService,
        PengumpulanDataService $pengumpulanDataService
    ) {
        $this->perencanaanDataService = $perencanaanDataService;
        $this->pengumpulanDataService = $pengumpulanDataService;
    }

    public function getAllDataPemeriksaanRekonsiliasi()
    {
        $status = [
            config('constants.STATUS_PEMERIKSAAN'),
            config('constants.STATUS_REKONSILIASI'),
            config('constants.STATUS_PENYEBARLUASAN_DATA'),
            config('constants.STATUS_NOT_QUALIFIED_C'),
            config('constants.STATUS_NOT_QUALIFIED_D'),
        ];

        $listData = $this->perencanaanDataService->tableListPerencanaanData($status);

        if ($listData) {
            return response()->json([
                'status' => 'success',
                'message' => config('constants.SUCCESS_MESSAGE_GET'),
                'data' => $listData
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => config('constants.ERROR_MESSAGE_GET'),
                'data' => []
            ]);
        }
    }

    public function getDataPemeriksaanRekonsiliasi($shortlistId)
    {
        $getData = $this->pengumpulanDataService->getEntriData($shortlistId);
        $pemeriksaanData = $this->pengumpulanDataService->getPemeriksaanDataList($getData['data_vendor_id'], $getData['identifikasi_kebutuhan_id']);

        $responseData = [
            'data' => $getData,
            'pemeriksaan_data' => $pemeriksaanData
        ];

        if ($getData && $pemeriksaanData) {
            return response()->json([
                'status' => 'success',
                'message' => config('constants.SUCCESS_MESSAGE_GET'),
                'data' => $responseData
            ]);
        }
    }

    public function storePemeriksaanRekonsiliasi(Request $request)
    {
        try {
            if ($request->hasFile('berita_acara_validasi')) {
                $filePath = $request->file('berita_acara_validasi')->store('sk_penugasan');
            } else {
                $filePath = "-";
            }

            $blok_2_and_3 = json_decode($request['blok_2_and_3'], true);
            $blok_4 = json_decode($request['blok_4'], true);

            $storeDataValidasi = $this->pengumpulanDataService->pemeriksaanDataList($request);

            $materialResult = [];

            foreach ($blok_4[0]['material'] as $material) {
                $materialResult[] = $this->pengumpulanDataService->updateIdentifikasiPemeriksaanUpdate('material', $material['id'], $material);
            }

            $peralatanResult = [];
            foreach ($blok_4[0]['peralatan'] as $peralatan) {
                $peralatanResult[] = $this->pengumpulanDataService->updateIdentifikasiPemeriksaanUpdate('peralatan', $peralatan['id'], $peralatan);
            }

            $tenagaKerjaResult = [];
            foreach ($blok_4[0]['tenaga_kerja'] as $tenaga_kerja) {
                $tenagaKerjaResult[] = $this->pengumpulanDataService->updateIdentifikasiPemeriksaanUpdate('tenaga_kerja', $tenaga_kerja['id'], $tenaga_kerja);
            }

            $this->pengumpulanDataService->updateShortlistVendorVerifikasiValidasi($request['identifikasi_kebutuhan_id'], $request['data_vendor_id'], $blok_2_and_3, $request['catatan_blok_v']);

            foreach ($storeDataValidasi as $value) {
                if (strtolower($value['status_pemeriksaan']) == "tidak memenuhi") {
                    if (
                        $value['item_number'] == 'C1' ||
                        $value['item_number'] == 'C2' ||
                        $value['item_number'] == 'C3' ||
                        $value['item_number'] == 'C4'
                    ) {
                        $statusValidation = config('constants.STATUS_NOT_QUALIFIED_C');
                    } elseif (
                        $value['item_number'] == 'D1' ||
                        $value['item_number'] == 'D2'
                    ) {
                        $statusValidation = config('constants.STATUS_NOT_QUALIFIED_D');
                    }

                    $this->pengumpulanDataService->changeStatusValidation($request['identifikasi_kebutuhan_id'], $filePath, $statusValidation);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data berhasil disimpan',
                        'data' => $storeDataValidasi
                    ]);
                }
            }

            if ($storeDataValidasi) {
                $this->pengumpulanDataService->changeStatusValidation($request['identifikasi_kebutuhan_id'], $filePath, config('constants.STATUS_PENYEBARLUASAN_DATA'));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil disimpan',
                    'data' => $storeDataValidasi
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
}
