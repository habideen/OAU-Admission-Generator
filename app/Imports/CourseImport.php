<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\SubjectCombination;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CourseImport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements ToModel, WithHeadingRow
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
        $subjects = [];
        $isError = false;

        if (!isset($row['faculty_id']) || !Faculty::select('id')->where('id', $row['faculty_id'])->first()) {
            $this->failedSession('Invalid faculty id detected for course = ' . $row['course']);
            $isError = true;
        }

        if (!isset($row['course']) || !preg_match('/^[a-zA-Z0-9\-\#\/ ]{2,255}$/', $row['course'])) {
            $this->failedSession('Invalid course: ' . $row['course']);
            $isError = true;
        }

        if (!isset($row['capacity']) || !preg_match('/^[1-9][0-9]{1,4}$/', (int)$row['capacity'])) {
            $this->failedSession('Invalid capacity for course: ' . $row['capacity']);
            $isError = true;
        }

        if (
            !array_key_exists('subject_code_1', $row) || !array_key_exists('subject_code_2', $row)
            || !array_key_exists('subject_code_3', $row) || !array_key_exists('subject_code_4', $row)
            || !array_key_exists('subject_code_5', $row) || !array_key_exists('subject_code_6', $row)
            || !array_key_exists('subject_code_7', $row) || !array_key_exists('subject_code_8', $row)
        ) {
            $this->failedSession('The subject code must be 8 columns. Only 4 or more are required.');
            $isError = true;
        } else {
            for ($i = 1; $i <= 8; $i++) {
                if (!$row['subject_code_' . ($i)]) continue;

                if (!Subject::select('subject_code')
                    ->where(
                        'subject_code',
                        strtoupper(trim($row['subject_code_' . ($i)]))
                    )->first()) {
                    continue;
                }

                array_push($subjects, $row['subject_code_' . ($i)]);
            }

            array_unique($subjects);

            if (count($subjects) < 4) {
                $this->failedSession('Invalid subjects or subject is less that 4 for course: ' . $row['course']);
                $isError = true;
            }
        }

        // the record is clean to upload
        if (!$isError) {
            $save = Course::updateOrCreate(
                [
                    'course' => $row['course'],
                ],
                [
                    'faculty_id' => $row['faculty_id'],
                    'capacity' => $row['capacity'],
                    'session_updated' => activeSession()
                ]
            );

            if (!$save) {
                $this->failedSession($row['course'] . ' is not uploaded.');
            }

            $this->successSession();


            if ($save) {
                $insert = [];
                $i = 0;
                $subjects_len = count($subjects);
                while ($i++ < $subjects_len) {
                    $insert['subject_code_' . $i] = $subjects[$i - 1];
                }

                unset($subjects, $i, $subjects_len);

                $insert['course_id'] = $save->id;
                $insert['session_updated'] = activeSession();
                $insert['created_at'] = now();
                $insert['updated_at'] = now();

                $save = SubjectCombination::insert($insert);

                if (!$save) {
                    $this->failedSession($row['course'] . ' was uploaded but failed to add its subject combinations.');
                }
            } // course uploaded
        } else {
            $this->failedCount();
        }
    }
}
