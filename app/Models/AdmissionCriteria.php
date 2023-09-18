<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionCriteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'merit',
        'catchment',
        'elds',
        'discretion',
    ];
}
