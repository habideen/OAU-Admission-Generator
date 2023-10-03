<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionList extends Model
{
    use HasFactory;

    protected $primaryKey = 'rg_num';
    public $incrementing = false;

    protected $fillable = [
        'rg_num',
        'course',
        'category'
    ];
}
