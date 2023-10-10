<?php

namespace App\Imports;

use App\Models\Faculty;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FacultyImport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements ToModel, WithHeadingRow
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

        if (!$row['faculty']) { //null entries 
            return;
        }

        if (!isset($row['faculty']) || !preg_match('/^[a-zA-Z0-9\-\(\) ]{3,255}$/', $row['faculty'])) {
            $this->failedSession('Invalid faculty: ' . $row['faculty']);
            $isError = true;
        }


        // the record is clean to upload
        if ($isError) {
            $this->failedCount();
        } else {
            $save = Faculty::updateOrCreate(
                [
                    'faculty' => strtoupper($row['faculty']),
                ],
                []
            );

            if (!$save) {
                $this->failedSession($row['faculty'] . ' is not uploaded.');
            }

            $this->successSession();
        } // subject uploaded
    }
}
