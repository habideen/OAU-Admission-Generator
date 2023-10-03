<?php

namespace App\Imports;

use App\Models\AdmissionList;
use App\Models\Candidate;
use App\Models\Course;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DiscretionImport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements ToModel, WithHeadingRow
{
    private function successSession($row)
    {
        Session::flash(
            'report_success',
            (Session::get('report_success')
                ? Session::get('report_success') . '<br>' . strtoupper($row['rg_num'])
                : strtoupper($row['rg_num']))
        );
        Session::flash(
            'success_count',
            (Session::has('success_count') ? Session::get('success_count') + 1 : 1)
        );
    } //successSession


    private function failedSession($row)
    {
        Session::flash(
            'report_failed',
            (Session::get('report_failed')
                ? Session::get('report_failed') . '<br>' . strtoupper($row['rg_num'])
                : 'Failed: either registration number or course does not exist for these records'
                . '<br>' . strtoupper($row['rg_num']))
        );
        Session::flash(
            'failed_count',
            (Session::has('failed_count') ? Session::get('failed_count') + 1 : 1)
        );
    } //failedSession


    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (
            Candidate::select('rg_num')->where('rg_num', $row['rg_num'])->first()
            && Course::select('course')->where('course', $row['course'])->first()
        ) {
            $this->successSession($row);

            return AdmissionList::updateOrCreate(
                [
                    'rg_num' => strtoupper($row['rg_num']),
                ],
                [
                    'course' => $row['course'],
                    'category' => 'Discretion'
                ]
            );
        } else {
            $this->failedSession($row);
        }
    }
}
