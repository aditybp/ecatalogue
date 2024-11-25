<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamPengumpulanData extends Model
{
    use HasFactory;
    protected $table = 'team_pengumpulan_data';
    protected $fillable = [
        'nama_team',
        'nama_ketua',
        'nama_sekretaris',
        'nama_anggota',
        'url_sk_penugasan'
    ];
}
