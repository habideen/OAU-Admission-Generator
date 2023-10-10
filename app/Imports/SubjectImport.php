<?php

namespace App\Imports;

use App\Models\Subject;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubjectImport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements ToModel, WithHeadingRow
{
    private function successSession()
    {
        Session::flash(
            'success_count',
            (Session::has('success_count') ? Session::get('success_count') + 1 : 1)
        );
    } //successSession


    private function failedSession($msg)
    {
        Session::flash(
            'report_failed',
            (Session::get('report_failed')
                ? Session::get('report_failed') . '<br>' . $msg
                : $msg
            )
        );
    } //failedSession


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
        $isError = false;

        if (!isset($row['subject_code']) || !preg_match('/^[a-zA-Z]{3,3}$/', $row['subject_code'])) {
            $this->failedSession('Invalid subject code: ' . $row['subject_code']);
            $isError = true;
        }

        if (!isset($row['subject']) || !preg_match('/^[a-zA-Z\-\(\) ]{3,100}$/', $row['subject'])) {
            $this->failedSession('Invalid subject: ' . $row['subject']);
            $isError = true;
        }


        // the record is clean to upload
        if ($isError) {
            $this->failedCount();
        } else {
            $save = Subject::updateOrCreate(
                [
                    'subject_code' => strtoupper($row['subject_code']),
                ],
                [
                    'subject' => $row['subject']
                ]
            );

            if (!$save) {
                $this->failedSession($row['subject_code'] . ' is not uploaded.');
            }

            $this->successSession();
        } // subject uploaded
    }
}
