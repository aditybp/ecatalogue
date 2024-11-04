<?php

namespace App\Services;

use App\Models\DataVendor;
use App\Models\PerencanaanData;
use App\Models\ShortlistVendor;

class ShortlistVendorService
{
    public function getDataVendor($id)
    {
        $getDataIdentifikasi = PerencanaanData::with([
            'material:id,identifikasi_kebutuhan_id,nama_material', 
            'peralatan:id,identifikasi_kebutuhan_id,nama_peralatan', 
            'tenagaKerja:id,identifikasi_kebutuhan_id,jenis_tenaga_kerja'
        ])->select('identifikasi_kebutuhan_id')->where('identifikasi_kebutuhan_id', $id)
        ->get();

        $identifikasikebutuhan = $getDataIdentifikasi->flatMap(function($item) {

            $materials = $item->material->pluck('nama_material')->toArray();
            $peralatans = $item->peralatan->pluck('nama_peralatan')->toArray();
            $tenagaKerjas = $item->tenagaKerja->pluck('jenis_tenaga_kerja')->toArray();
        
            return array_merge($materials, $peralatans, $tenagaKerjas);
        });

        $resultArray = $identifikasikebutuhan->toArray();
        
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
                $key = match($value){
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
                    'sumber_daya' => $item->sumber_daya
                ];
            }
        }
        return $result;
    }   

    public function storeShortlistVendor($data, $shortlistVendorId)
    {
        $makeKuisioner = app(GeneratePdfService::class)->generatePdfMaterialNatural($data['data_vendor_id']);

        $shortlistVendor = new ShortlistVendor();
        $shortlistVendor->data_vendor_id = $data['data_vendor_id'];
        $shortlistVendor->shortlist_vendor_id = $shortlistVendorId;
        $shortlistVendor->nama_vendor = $data['nama_vendor'];
        $shortlistVendor->pemilik_vendor = $data['pemilik_vendor'];
        $shortlistVendor->alamat = $data['alamat'];
        $shortlistVendor->kontak = $data['kontak'];
        $shortlistVendor->url_kuisioner = $makeKuisioner;
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
            // Loop through each item in the second array
            foreach ($lowercasedArray2 as $value2) {
                // Check if the second value is a substring of the first value
                if (strpos($value1, $value2) !== false) {
                    // If it matches, add the original value from array1 (not lowercased)
                    $matches[] = $value1; // Add the full value from $array1
                    break; // No need to check further for this value1
                }
            }
        } 

        return array_values(array_unique($matches));
    }

}
