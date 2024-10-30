<?php

namespace App\Http\Controllers;

use App\Models\DataVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{

    public function allVendor(){
        return DataVendor::all();
    }

    public function inputVendor(Request $request){

        $validator = Validator::make($request->all(), [
            'nama_vendor' => 'required|string|max:255', 
            'jenis_vendor_id' => 'required', 
            'kategori_vendor_id' => 'required', 
            'alamat' => 'required|string|max:255', 
            'no_telepon' => 'required|integer',
            'no_hp' => 'required|integer',
            'provinsi_id' => 'required',
            'kota_id' => 'required',
            'koordinat' => 'required|string',
            'sumber_daya' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'validasi gagal!',
                'data' => []
            ]);
        }

        try{
            $vendor = new DataVendor();
            $vendor->nama_vendor = $request->nama_vendor;
            $vendor->jenis_vendor_id = $request->jenis_vendor_id;
            $vendor->kategori_vendor_id = $request->kategori_vendor_id;
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

        }catch(\Throwable $th){
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data vendor',
                'error' => $th->getMessage()
            ]);
        }
        
    }
}
