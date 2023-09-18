<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rg_num',
        'fullname',
        'rg_sex',
        'state_name',
        'subject_code_1',
        'subject_code_2',
        'subject_code_3',
        'course',
        'utme_score',
        'olevel_score',
        'putme_score',
        'putme_screening',
        'aggregate',
        'session_updated',
        'created_at',
        'updated_at'
    ];
}
