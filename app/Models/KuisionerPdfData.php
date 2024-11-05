<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuisionerPdfData extends Model
{
    use HasFactory;

    protected $fillable = ['material', 'peralatan', 'tenaga_kerja', 'shortlist_id', 'vendor_id'];
}
