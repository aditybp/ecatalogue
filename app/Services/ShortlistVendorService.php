<?php

namespace App\Services;

use App\Models\DataVendor;
use App\Models\ShortlistVendor;

class ShortlistVendorService
{
    public function getDataVendor()
    {
        $dataVendors = DataVendor::all();

        $result = [];
        foreach ($dataVendors as $item) {
            $jenisVendorIdArray = json_decode($item->jenis_vendor_id, true);
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
                    'kontak' => $item->no_telepon
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

    // public function taggingInfoToPdf($dataVendorId) 
    // {
    //     $generatePdf = 
    // }

}
