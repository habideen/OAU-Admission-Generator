<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Session as ModelsSession;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class Session extends Controller
{
    private function loadSession(Request $request)
    {
        return ModelsSession::orderBy('is_active', 'DESC')
            ->orderBy('session', 'DESC')
            ->paginate(PAGINATION);
    } //loadSession



    public function getAll(Request $request)
    {
        return response([
            'status' => 'success',
            'message' => 'Retrieved successfully',
            'sessions' => $this->loadSession($request)
        ], Response::HTTP_CREATED);
    } //getAll



    public function getActive()
    {
        return response([
            'status' => 'success',
            'message' => 'Retrieved successfully',
            'sessions' => ModelsSession::whereNotNull('is_active')->first()
        ], Response::HTTP_CREATED);
    } //getActive



    public function set(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session' => [
                'required',
                'regex:/^[2-9][0-9]{3,3}[\/][2-9][0-9]{3,3}$/',
                function (string $attribute, mixed $value, Closure $fail) {
                    $years = explode('/', $value);

                    if (
                        count($years) !== 2
                        || intval($years[0]) >= intval($years[1])
                        || intval($years[0]) + 1 != intval($years[1])
                        || intval($years[0]) >= date('Y') + 10
                    ) {
                        $fail("The {$attribute} is invalid.");
                    }
                },
            ]
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid input submitted',
                'errors' => $validator->errors(),
            ], Response::HTTP_EXPECTATION_FAILED);
        }


        $save = null;

        if (ModelsSession::where('created_at', '!=', null)->update(['is_active' => null])) {
            $isSession = ModelsSession::where('session', $request->session)->first();
            if ($isSession) {
                $save = ModelsSession::where('session', $request->session)->update([
                    'is_active' => 1
                ]);
            } else {
                $save = new ModelsSession;
                $save->session = $request->session;
                $save->is_active = 1;
                $save->save();
            }
        }

        if (!$save) {
            return response([
                'status' => 'failed',
                'message' => 'Server error!'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response([
            'status' => 'success',
            'message' => 'Session updated successfully',
            'sessions' => $this->loadSession($request)
        ], Response::HTTP_CREATED);
    } //set
}