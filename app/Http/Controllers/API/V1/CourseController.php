<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\SubjectCombination;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Return the latest subject combinations for this course
     */
    private function courseLatestSubjects($course_id)
    {
        return SubjectCombination::select(
            'subject_code_1',
            'subject_code_2',
            'subject_code_3',
            'subject_code_4',
            'subject_code_5',
            'subject_code_6',
            'subject_code_7',
            'subject_code_8'
        )
            ->where('course_id', $course_id)
            ->latest()
            ->first();
    }


    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'faculty_id' => ['required', 'integer', 'exists:faculties,id'],
            'course' => [
                'required', 'string', 'min:2', 'max:255',
                'regex:/^[a-zA-Z0-9\-\# ]{2,255}$/', 'unique:courses'
            ],
            'subject_code_1' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_2', 'different:subject_code_3',
                'different:subject_code_4', 'different:subject_code_5',
                'different:subject_code_6', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_2' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_3',
                'different:subject_code_4', 'different:subject_code_5',
                'different:subject_code_6', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_3' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_4', 'different:subject_code_5',
                'different:subject_code_6', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_4' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_3', 'different:subject_code_5',
                'different:subject_code_6', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_5' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_3', 'different:subject_code_4',
                'different:subject_code_6', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_6' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_3', 'different:subject_code_4',
                'different:subject_code_5', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_7' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_3', 'different:subject_code_4',
                'different:subject_code_5', 'different:subject_code_6',
                'different:subject_code_8',
            ],
            'subject_code_8' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_3', 'different:subject_code_4',
                'different:subject_code_5', 'different:subject_code_6',
                'different:subject_code_7',
            ]
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        $save = new Course;
        $save->faculty_id = $request->faculty_id;
        $save->course = $request->course;
        $save->session_updated = activeSession();
        $save->save();

        if (!$save) {
            return response([
                'status' => 'failed',
                'message' => 'Server error! We couldn\'t add the course.'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        $subjects = [];
        for ($i = 1; $i <= 8; $i++) {
            if (!$request->get('subject_code_' . ($i))) continue;

            array_push($subjects, $request->get('subject_code_' . ($i)));
        }

        if (count($subjects) < 4) {
            return response([
                'status' => 'failed',
                'message' => 'Please select at least 4 subjects.'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        sort($subjects);

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
            return response([
                'status' => 'failed',
                'message' => 'Server error! We couldn\'t create the subject combinations. Please edit the course to continue.'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'Course created'
        ], Response::HTTP_CREATED);
    } //add




    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'faculty_id' => ['required', 'integer', 'exists:faculties,id'],
            'course' => [
                'required', 'string', 'min:2', 'max:255',
                'regex:/^[a-zA-Z0-9\-\# ]{2,255}$/',
                Rule::unique('courses')->ignore($request->course_id)
            ],
            'subject_code_1' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_2', 'different:subject_code_3',
                'different:subject_code_4', 'different:subject_code_5',
                'different:subject_code_6', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_2' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_3',
                'different:subject_code_4', 'different:subject_code_5',
                'different:subject_code_6', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_3' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_4', 'different:subject_code_5',
                'different:subject_code_6', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_4' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_3', 'different:subject_code_5',
                'different:subject_code_6', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_5' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_3', 'different:subject_code_4',
                'different:subject_code_6', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_6' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_3', 'different:subject_code_4',
                'different:subject_code_5', 'different:subject_code_7',
                'different:subject_code_8',
            ],
            'subject_code_7' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_3', 'different:subject_code_4',
                'different:subject_code_5', 'different:subject_code_6',
                'different:subject_code_8',
            ],
            'subject_code_8' => [
                'nullable',
                'regex:/^[a-zA-Z]{3,3}$/',
                'regex:/^[a-zA-Z]{3,3}$/',
                'exists:subjects,subject_code',
                'different:subject_code_1', 'different:subject_code_2',
                'different:subject_code_3', 'different:subject_code_4',
                'different:subject_code_5', 'different:subject_code_6',
                'different:subject_code_7',
            ]
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }

        $save = Course::where('id', $request->course_id)->update([
            'faculty_id' => $request->faculty_id,
            'course' => $request->course,
            'session_updated' => activeSession()
        ]);

        if (!$save) {
            return response([
                'status' => 'failed',
                'message' => 'Server error! We couldn\'t update the course.'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }


        $_last_subjects = $this->courseLatestSubjects($request->course_id);

        $_last_subjects = $_last_subjects ? $_last_subjects->toArray() : null;
        $last_subjects = []; //accumulate new records here

        if ($_last_subjects && count($_last_subjects) > 0) {
            foreach ($_last_subjects as $key => $val) {
                if (!$val) continue;

                $last_subjects[$key] = $val;
            }
        }
        unset($_last_subjects);

        sort($last_subjects);


        $subjects = [];
        for ($i = 1; $i <= 8; $i++) {
            if (!$request->get('subject_code_' . ($i))) continue;

            array_push($subjects, $request->get('subject_code_' . ($i)));
        }

        sort($subjects);

        if ($last_subjects == $subjects) {
            //no need to update subject combination because they are equal
            return response([
                'status' => 'success',
                'message' => 'Course updated successfully'
            ]);
        } elseif (count($subjects) < 4) {
            return response([
                'status' => 'failed',
                'message' => 'Selected subjects must be greater than four'
            ]);
        }


        $insert = [];
        $i = 0;
        $subjects_len = count($subjects);
        while ($i++ < $subjects_len) {
            $insert['subject_code_' . $i] = $subjects[$i - 1];
        }

        unset($subjects, $i, $subjects_len);

        $insert['course_id'] = $request->course_id;
        $insert['session_updated'] = activeSession();
        $insert['created_at'] = now();
        $insert['updated_at'] = now();
        $save = SubjectCombination::insert($insert);


        if (!$save) {
            return response([
                'status' => 'failed',
                'message' => 'Server error! We couldn\'t create the subject combinations. Please edit the course to continue.'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'Course updated successfully'
        ], Response::HTTP_CREATED);
    } //edit



    public function list(Request $request)
    {
        $pag = $request->pagination && ctype_digit($request->pagination) ? $request->pagination : PAGINATION;

        $courses = DB::table('courses')
            ->select(
                'courses.id AS course_id',
                'courses.course',
                'faculties.faculty',
                'faculties.id AS faculty_id',
                'sub_1.subject AS subject_1',
                'sub_2.subject AS subject_2',
                'sub_3.subject AS subject_3',
                'sub_4.subject AS subject_4',
                'sub_5.subject AS subject_5',
                'sub_6.subject AS subject_6',
                'sub_7.subject AS subject_7',
                'sub_8.subject AS subject_8',
                'subject_combinations.subject_code_1',
                'subject_combinations.subject_code_2',
                'subject_combinations.subject_code_3',
                'subject_combinations.subject_code_4',
                'subject_combinations.subject_code_5',
                'subject_combinations.subject_code_6',
                'subject_combinations.subject_code_7',
                'subject_combinations.subject_code_8',
                'subject_combinations.session_updated',
                'courses.created_at',
                'subject_combinations.updated_at'
            )
            ->join('faculties', 'faculties.id', '=', 'courses.faculty_id')
            ->join('subject_combinations', function ($join) {
                $join->on('courses.id', '=', 'subject_combinations.course_id')
                    ->whereRaw(
                        'subject_combinations.created_at = (select MAX(created_at) 
                        FROM 
                            subject_combinations AS sc 
                        WHERE 
                            sc.course_id = subject_combinations.course_id)'
                    );
            })
            ->leftJoin('subjects AS sub_1', 'sub_1.subject_code', '=', 'subject_combinations.subject_code_1')
            ->leftJoin('subjects AS sub_2', 'sub_2.subject_code', '=', 'subject_combinations.subject_code_2')
            ->leftJoin('subjects AS sub_3', 'sub_3.subject_code', '=', 'subject_combinations.subject_code_3')
            ->leftJoin('subjects AS sub_4', 'sub_4.subject_code', '=', 'subject_combinations.subject_code_4')
            ->leftJoin('subjects AS sub_5', 'sub_5.subject_code', '=', 'subject_combinations.subject_code_5')
            ->leftJoin('subjects AS sub_6', 'sub_6.subject_code', '=', 'subject_combinations.subject_code_6')
            ->leftJoin('subjects AS sub_7', 'sub_7.subject_code', '=', 'subject_combinations.subject_code_7')
            ->leftJoin('subjects AS sub_8', 'sub_8.subject_code', '=', 'subject_combinations.subject_code_8')
            ->paginate($pag);

        return response([
            'status' => 'success',
            'message' => 'Retrieved successfully',
            'courses' => $courses
        ]);
    } //list




    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => ['required', 'integer', 'exists:courses,id']
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }
        /**
         * TODO
         * check if a course has selected it before deleting
         */

        $delete = SubjectCombination::where('course_id', $request->course_id)->delete();
        if (!$delete) {
            return response([
                'status' => 'failed',
                'message' => 'Server error!'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        $delete = Course::where('id', $request->course_id)->delete();

        if (!$delete) {
            return response([
                'status' => 'failed',
                'message' => 'Server error! Partially deleted course. Please try again.'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'Course deleted successfully'
        ], Response::HTTP_CREATED);
    } //delete
}
