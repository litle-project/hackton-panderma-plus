<?php

namespace App\Http\Responses\Auth;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Responsable;

class RegisterResponse implements Responsable
{
    public function toResponse($request)
    {
        try {
            $valid = $this->validate($request);
            if (!$valid->status) return $this->invalid($valid->message, 400);

            $this->process($request);
            return $this->success($request);
        } catch (\Exception $error) {
            return $this->invalid($error->getMessage(), 500);
        }
    }

    protected function validate($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:225|',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'phone' => 'required|min:10|max:15',
        ]);

        if ($validator->fails()) {
            return (object) [
                'status' => false,
                'message' => $validator->errors()->first(),
            ];
        }

        return (object) [ 'status' => true ];
    }

    protected function process($request)
    {
        Redis::connection('default')->set('token', 'Taylor');
    }

    protected function invalid($message, $code)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => null,
        ], $code);
    }

    protected function success($request)
    {
        return response()->json([
            'code' => 200,
            'message' => 'OK',
            'data' => $request->all(),
        ], 200);
    }
}
