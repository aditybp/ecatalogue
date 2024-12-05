<?php

namespace App\Services;

use App\Models\Pengawas;
use App\Models\PengolahData;
use App\Models\PerencanaanData;
use App\Models\PetugasLapangan;
use App\Models\TeamTeknisBalai;
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
}
