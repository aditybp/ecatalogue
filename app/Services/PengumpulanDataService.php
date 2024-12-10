<?php

namespace App\Services;

use App\Models\KategoriVendor;
use App\Models\Material;
use App\Models\Pengawas;
use App\Models\PengolahData;
use App\Models\Peralatan;
use App\Models\PerencanaanData;
use App\Models\PetugasLapangan;
use App\Models\ShortlistVendor;
use App\Models\TeamTeknisBalai;
use App\Models\TenagaKerja;
use App\Models\Users;
use Illuminate\Support\Facades\Storage;

class PengumpulanDataService
{
    public function storeTeamPengumpulanData($data)
    {
        $teamPengumpulanData = new TeamTeknisBalai();
        $teamPengumpulanData->nama_team = $data['nama_team'];
        $teamPengumpulanData->user_id_ketua = $data['ketua_team'];
        $teamPengumpulanData->user_id_sekretaris = $data['sekretaris_team'];
        $teamPengumpulanData->user_id_anggota = $data['anggota'];
        $teamPengumpulanData->url_sk_penugasan = $data['sk_penugasan'];
        $teamPengumpulanData->save();

        return $teamPengumpulanData;
    }

    public function getAllTeamPengumpulanData()
    {
        return TeamTeknisBalai::select('id', 'nama_team')->get();
    }

    public function assignTeamPengumpulanData($data)
    {
        return PerencanaanData::updateOrCreate(
            [
                'id' => $data['id_pengumpulan_data'],
            ],
            [
                'team_pengumpulan_data_id' => $data['id_team_pengumpulan_data'],
            ]
        );
    }

    public function listUserPengumpulan()
    {
        return Users::select('id AS user_id', 'nama_lengkap')
            ->where('status', 'active')
            ->whereNotNull('email_verified_at')
            ->whereNot('id_roles', 1)->get();
    }

    public function listPenugasan($table)
    {
        if ($table == 'pengawas') {
            $data = Pengawas::select(
                'pengawas.id as pengawas_id',
                'pengawas.sk_penugasan',
                'users.nama_lengkap',
                'users.id as id_user',
                'users.nrp',
                'satuan_kerja.nama as satuan_kerja_nama',
            )
                ->join('users', 'pengawas.user_id', '=', 'users.id')
                ->join('satuan_kerja', 'users.satuan_kerja_id', '=', 'satuan_kerja.id')
                ->get();

            $data->transform(function ($item) {
                $exists = PerencanaanData::where('pengawas_id', $item->id_user)
                    ->exists();

                $item->status = $exists ? 'ditugaskan' : 'belum ditugaskan';
                $item->url_sk_penugasan = Storage::url($item->sk_penugasan);
                unset($item->sk_penugasan);
                return $item;
            });

            return $data;
        } elseif ($table == 'pengolah data') {
            $data = PengolahData::select(
                'pengolah_data.id as pengolah_data_id',
                'pengolah_data.sk_penugasan',
                'users.nama_lengkap',
                'users.id as id_user',
                'users.nrp',
                'satuan_kerja.nama as satuan_kerja_nama',
            )
                ->join('users', 'pengolah_data.user_id', '=', 'users.id')
                ->join('satuan_kerja', 'users.satuan_kerja_id', '=', 'satuan_kerja.id')
                ->get();

            $data->transform(function ($item) {
                $exists = PerencanaanData::where('pengolah_data_id', $item->id_user)
                    ->exists();

                $item->status = $exists ? 'ditugaskan' : 'belum ditugaskan';
                $item->url_sk_penugasan = Storage::url($item->sk_penugasan);
                unset($item->sk_penugasan);
                return $item;
            });

            return $data;
        } elseif ($table == 'petugas lapangan') {
            $data = PetugasLapangan::select(
                'petugas_lapangan.id as petugas_lapangan_id',
                'petugas_lapangan.sk_penugasan',
                'users.nama_lengkap',
                'users.id as id_user',
                'users.nrp',
                'satuan_kerja.nama as satuan_kerja_nama',
            )
                ->join('users', 'petugas_lapangan.user_id', '=', 'users.id')
                ->join('satuan_kerja', 'users.satuan_kerja_id', '=', 'satuan_kerja.id')
                ->get();

            $data->transform(function ($item) {
                $exists = PerencanaanData::where('petugas_lapangan_id', $item->id_user)
                    ->exists();

                $item->status = $exists ? 'ditugaskan' : 'belum ditugaskan';
                $item->url_sk_penugasan = Storage::url($item->sk_penugasan);
                unset($item->sk_penugasan);
                return $item;
            });

            return $data;
        }
    }

    public function assignPenugasan($table, $idTable, $idPerencanaan)
    {
        $array = explode(',', $idTable);
        $array = array_map('intval', $array);

        if ($table == 'pengawas') {
            return PerencanaanData::updateOrCreate(
                [
                    'id' => $idPerencanaan,
                ],
                [
                    'pengawas_id' => $array,
                ]
            );
        } elseif ($table == 'pengolah data') {
            return PerencanaanData::updateOrCreate(
                [
                    'id' => $idPerencanaan,
                ],
                [
                    'pengolah_data_id' => $array,
                ]
            );
        } elseif ($table == 'petugas lapangan') {
            return PerencanaanData::updateOrCreate(
                [
                    'id' => $idPerencanaan,
                ],
                [
                    'petugas_lapangan_id' => $array,
                ]
            );
        }
    }

    public function listVendorByPerencanaanId($perencanaanId)
    {
        return ShortlistVendor::select(
            'id As shortlist_id',
            'shortlist_vendor_id As informasi_umum_id',
            'nama_vendor',
            'pemilik_vendor As pic',
            'alamat As alamat_vendor'
        )
            ->where('shortlist_vendor_id', $perencanaanId)
            ->get();
    }

    public function showKuisioner($shortlistId)
    {
        return ShortlistVendor::select(
            'url_kuisioner'
        )
            ->where('id', $shortlistId)
            ->first();
    }

    public function generateLinkKuisioner() {}

    public function getPemeriksaanData($id) {}

    public function getEntriData($shortlistId)
    {
        $vendor = ShortlistVendor::select(
            'data_vendors.nama_vendor',
            'data_vendors.kategori_vendor_id',
            'data_vendors.alamat',
            'data_vendors.no_telepon',
            'data_vendors.provinsi_id',
            'data_vendors.kota_id',
            'provinces.nama_provinsi',
            'cities.nama_kota',
            'shortlist_vendor.shortlist_vendor_id As identifikasi_kebutuhan_id',
            'data_vendors.id As vendor_id'
        )
            ->join('data_vendors', 'shortlist_vendor.data_vendor_id', '=', 'data_vendors.id')
            ->join('provinces', 'data_vendors.provinsi_id', '=', 'provinces.kode_provinsi')
            ->join('cities', 'data_vendors.kota_id', '=', 'cities.kode_kota')
            ->where('shortlist_vendor.id', $shortlistId)
            ->first();

        $material = Material::where('identifikasi_kebutuhan_id', $vendor['identifikasi_kebutuhan_id'])->get();
        $peralatan = Peralatan::where('identifikasi_kebutuhan_id', $vendor['identifikasi_kebutuhan_id'])->get();
        $tenagaKerja = TenagaKerja::where('identifikasi_kebutuhan_id', $vendor['identifikasi_kebutuhan_id'])->get();

        $kategoriVendor = KategoriVendor::whereIn('id', json_decode($vendor['kategori_vendor_id'], true))
            ->select('nama_kategori_vendor as name')
            ->get();
        $stringKategoriVendor = $kategoriVendor->pluck('name')->implode(', ');

        $response = [
            'provinsi' => $vendor['nama_provinsi'],
            'kota' => $vendor['nama_kota'],
            'nama_responden' => $vendor['nama_vendor'],
            'alamat' => $vendor['alamat'],
            'no_telepon' => $vendor['no_telepon'],
            'kategori_responden' => $stringKategoriVendor,
            'material' => $material,
            'peralatan' => $peralatan,
            'tenaga_kerja' => $tenagaKerja,
        ];
        return $response;
    }
}
