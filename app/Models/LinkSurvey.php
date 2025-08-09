<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkSurvey extends Model
{
    use HasFactory;

    protected $table = 'link_survey';
    protected $fillable = [
        'shortlist_vendor_id',
        'link_survey',
    ];
}
