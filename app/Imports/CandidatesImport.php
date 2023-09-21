<?php

namespace App\Imports;

use App\Models\Candidate;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Maatwebsite\Excel\Concerns\WithMultipleSheets;

// class CandidatesImport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithMultipleSheets, ToModel, WithHeadingRow
class CandidatesImport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $subjects = explode(',', $row['subject_combo']);
        if (
            count($subjects) != 3
            || !ctype_alpha($subjects[0]) || strlen($subjects[0]) != 3
            || !ctype_alpha($subjects[1]) || strlen($subjects[1]) != 3
            || !ctype_alpha($subjects[2]) || strlen($subjects[2]) != 3
        ) {
            return false;
        }


        return Candidate::updateOrCreate(
            [
                'rg_num' => strtoupper($row['rg_num']),
            ],
            [
                'fullname' => trim($row['fullname']),
                'rg_sex' => trim($row['rg_sex']),
                'state_name' => trim($row['state_name']),
                'subject_code_1' => ucwords($subjects[0]),
                'subject_code_2' => ucwords($subjects[1]),
                'subject_code_3' => ucwords($subjects[2]),
                'course' => $row['course'],
                'utme_score' => (int) trim($row['utme_score']),
                'olevel_score' => floor((float) trim($row['olevel_score']) * 100) / 100,
                'putme_score' => floor((float) trim($row['putme_score']) * 100) / 100,
                'putme_screening' => floor((float) trim($row['putme_screening']) * 100) / 100,
                'aggregate' => floor((float) trim($row['aggregate']) * 100) / 100,
                'session_updated' => Session::get('active_session')
            ]
        );
    }




    // public function sheets(): array
    // {
    //     return [
    //         'Candidates' => $this
    //     ];
    // }
}
