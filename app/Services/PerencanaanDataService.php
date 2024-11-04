<?php

namespace App\Services;

use App\Models\PerencanaanData;

class PerencanaanDataService
{
    public function listAllPerencanaanData($id)
    {
        $query = PerencanaanData::with(['informasiUmum', 'material', 'peralatan', 'tenagaKerja', 'shortlistVendor'])
        ->where('informasi_umum_id', $id)->first();
        return $query;
    }

    public function updatePerencanaanData($informasiUmumId, $field, $value)
    {
        $perencanaanData = PerencanaanData::where('informasi_umum_id', $informasiUmumId)->first();
        if (!$perencanaanData) {
            return false;
        }

        if ($field == 'identifikasi_kebutuhan') {
            $perencanaanData->identifikasi_kebutuhan_id = $value;
        } elseif ($field == 'shortlist_vendor') {
            $perencanaanData->shortlist_vendor_id = $value;
        }

        $perencanaanData->save();
        return true;
    }

}
