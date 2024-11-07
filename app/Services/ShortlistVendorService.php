<?php

namespace App\Services;

use App\Models\DataVendor;
use App\Models\PerencanaanData;
use App\Models\ShortlistVendor;
use Illuminate\Support\Facades\DB;

class ShortlistVendorService
{
    private function getIdentifikasiKebutuhanByIdentifikasiId($id)
    {
        $getDataIdentifikasi = PerencanaanData::with([
            'material:id,identifikasi_kebutuhan_id,nama_material',
            'peralatan:id,identifikasi_kebutuhan_id,nama_peralatan',
            'tenagaKerja:id,identifikasi_kebutuhan_id,jenis_tenaga_kerja'
        ])->select('identifikasi_kebutuhan_id')->where('identifikasi_kebutuhan_id', $id)
            ->get();


        $identifikasikebutuhan = $getDataIdentifikasi->flatMap(function ($item) {

            $materials = $item->material->pluck('nama_material')->toArray();
            $peralatans = $item->peralatan->pluck('nama_peralatan')->toArray();
            $tenagaKerjas = $item->tenagaKerja->pluck('jenis_tenaga_kerja')->toArray();

            return array_merge($materials, $peralatans, $tenagaKerjas);
        });

        return $identifikasikebutuhan->toArray();
    }

    public function getDataVendor($id)
    {
        $resultArray = $this->getIdentifikasiKebutuhanByIdentifikasiId($id);

        $queryDataVendors = DataVendor::all();


        $dataVendors = [];
        foreach ($queryDataVendors as $value) {
            $sumberDayaArray = explode(',', $value->sumber_daya);

            $resultElemination = $this->eleminationArray($resultArray, $sumberDayaArray);
            if (!empty($resultElemination)) {
                $dataVendors[] = $value;
            }
        }

        $result = [];
        foreach ($dataVendors as $item) {
            $jenisVendorIdArray = $jenisVendorIdArray = $item->jenis_vendor_id;
            foreach ($jenisVendorIdArray as $value) {
                $key = match ($value) {
                    1 => 'material',
                    2 => 'peralatan',
                    3 => 'tenaga_kerja'
                };
                $result[$key][] = [
                    'id' => $item->id,
                    'nama_vendor' => $item->nama_vendor,
                    'pemilik_vendor' => $item->nama_pic,
                    'alamat' => $item->alamat,
                    'kontak' => $item->no_telepon,
                    'sumber_daya' => $item->sumber_daya,
                    'material_id' => $item->material_id,
                    'peralatan_id' => $item->peralatan_id,
                    'tenaga_kerja_id' => $item->tenaga_kerja_id
                ];
            }
        }
        return $result;
    }

    public function storeShortlistVendor($data, $shortlistVendorId)
    {
        //$makeKuisioner = app(GeneratePdfService::class)->generatePdfMaterialNatural($data['data_vendor_id']);

        $shortlistVendor = new ShortlistVendor();
        $shortlistVendor->data_vendor_id = $data['data_vendor_id'];
        $shortlistVendor->shortlist_vendor_id = $shortlistVendorId;
        $shortlistVendor->nama_vendor = $data['nama_vendor'];
        $shortlistVendor->pemilik_vendor = $data['pemilik_vendor'];
        $shortlistVendor->alamat = $data['alamat'];
        $shortlistVendor->kontak = $data['kontak'];
        $shortlistVendor->sumber_daya = $data['sumber_daya'];
        $shortlistVendor->save();
        return $shortlistVendor->toArray();
    }

    public function getShortlistVendorResult($id)
    {
        return ShortlistVendor::where('shortlist_vendor_id', $id)->get;
    }

    private function eleminationArray(array $array1, array $array2)
    {
        $matches = [];

        $lowercasedArray1 = array_map('strtolower', $array1);
        $lowercasedArray2 = array_map('strtolower', $array2);

        foreach ($lowercasedArray1 as $value1) {
            foreach ($lowercasedArray2 as $value2) {
                if (strpos($value1, $value2) !== false) {
                    $matches[] = $value1;
                }
            }
        }

        return array_values(array_unique($matches));
    }

    public function getIdentifikasiByShortlist($id, $idShortlistVendor)
    {
        $query = ShortlistVendor::with([
            'material' => function ($subQuery) {
                $subQuery->select('id', 'identifikasi_kebutuhan_id', 'nama_material', 'satuan', 'spesifikasi', 'merk');
            },
            'peralatan' => function ($subQuery) {
                $subQuery->select('id', 'identifikasi_kebutuhan_id', 'nama_peralatan', 'satuan', 'spesifikasi', 'merk');
            },
            'tenaga_kerja' => function ($subQuery) {
                $subQuery->select('id', 'identifikasi_kebutuhan_id', 'jenis_tenaga_kerja', 'satuan');
            }
        ])
            ->where('shortlist_vendor.id', $id)
            ->where(function ($query) use ($idShortlistVendor) {
                $query->whereHas('material', function ($subQuery) use ($idShortlistVendor) {
                    $subQuery->where('identifikasi_kebutuhan_id', $idShortlistVendor);
                })
                    ->orWhereHas('peralatan', function ($subQuery) use ($idShortlistVendor) {
                        $subQuery->where('identifikasi_kebutuhan_id', $idShortlistVendor);
                    })
                    ->orWhereHas('tenaga_kerja', function ($subQuery) use ($idShortlistVendor) {
                        $subQuery->where('identifikasi_kebutuhan_id', $idShortlistVendor);
                    });
            })
            ->select('id', 'data_vendor_id', 'shortlist_vendor_id', 'nama_vendor', 'pemilik_vendor', 'alamat', 'kontak', 'sumber_daya')
            ->first();

        $identifikasi = [
            'material' => [],
            'peralatan' => [],
            'tenaga_kerja' => []
        ];

        $sumberDaya = explode(',', $query['sumber_daya']);

        foreach ($sumberDaya as $value) {
            if (count($query['material'])) {
                foreach ($query['material'] as $data) {
                    if (strpos(strtolower($data['nama_material']), strtolower($value)) !== false) {
                        $identifikasi['material'][] = $data;
                    }
                }
            }

            if (count($query['peralatan'])) {
                foreach ($query['peralatan'] as $data) {
                    if (strpos(strtolower($data['nama_peralatan']), strtolower($value)) !== false) {
                        $identifikasi['peralatan'][] = $data;
                    }
                }
            }

            if (count($query['tenaga_kerja'])) {
                foreach ($query['tenaga_kerja'] as $data) {
                    if (strpos(strtolower($data['jenis_tenaga_kerja']), strtolower($value)) !== false) {
                        $identifikasi['tenaga_kerja'][] = $data;
                    }
                }
            }
        }

        return $identifikasi;
    }
}
