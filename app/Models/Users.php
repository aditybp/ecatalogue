<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    protected $fillable = ['id_roles', 'nama_lengkap', 'no_handphone', 'nik', 'nip', 'satuan_kerja_id', 'balai_kerja_id', 'status'];
}
