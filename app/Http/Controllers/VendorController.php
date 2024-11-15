<?php

namespace App\Http\Controllers;

use App\Models\DataVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{

    public function allVendor()
    {
        return DataVendor::all();
    }

    public function inputVendor(Request $request)
    {

        try {
            $vendor = new DataVendor();
            $vendor->nama_vendor = $request->nama_vendor;
            $vendor->jenis_vendor_id = json_decode($request->jenis_vendor_id, true);
            $vendor->kategori_vendor_id = json_decode($request->kategori_vendor_id, true);
            $vendor->alamat = $request->alamat;
            $vendor->no_telepon = $request->no_telepon;
            $vendor->no_hp = $request->no_hp;
            $vendor->nama_pic = $request->nama_pic;
            $vendor->provinsi_id = $request->provinsi_id;
            $vendor->kota_id = $request->kota_id;
            $vendor->koordinat = $request->koordinat;
            $vendor->logo_url = $request->logo_url;
            $vendor->dok_pendukung_url = $request->dok_pendukung_url;
            $vendor->sumber_daya = $request->sumber_daya;
            $vendor->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Vendor berhasil disimpan',
                'data' => $vendor
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data vendor',
                'error' => $th->getMessage()
            ]);
        }
    }
}
