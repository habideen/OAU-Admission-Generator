<?php

namespace App\Imports;

use App\Models\AdmissionList;
use App\Models\Candidate;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
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
                ? Session::get('report_failed') . '<br>' .
                'Failed: either registration number or course does not exist for these records' . strtoupper($row['rg_num'])
                : 'Failed: either registration number or course does not exist for these records' . strtoupper($row['rg_num']))
        );
    } //failedSession


    private function failedMsg($row)
    {
        Session::flash(
            'report_failed',
            (Session::get('report_failed')
                ? Session::get('report_failed') . '<br>' . 'Admission reached before: ' . strtoupper($row['rg_num'])
                : 'Admission reached before: ' . strtoupper($row['rg_num']))
        );
    } //failedMsg


    private function failedCount()
    {
        Session::flash(
            'failed_count',
            (Session::has('failed_count') ? Session::get('failed_count') + 1 : 1)
        );
    } //failedCount


    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $capacity = Course::select('capacity')->where('course', $row['course'])->first();

        $admitted = AdmissionList::select(DB::raw('COUNT(rg_num) AS num'))
            ->where('course', $row['course'])->first();

        if (
            !Candidate::select('rg_num')->where('rg_num', $row['rg_num'])->first()
            || !Course::select('course')->where('course', $row['course'])->first()
        ) {
            $this->failedSession($row);
            $this->failedCount();
        } elseif ($capacity->capacity == $admitted->num) {
            $this->failedMsg($row);
            $this->failedCount();
        } else {

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
        }
    }
}
