<?php

namespace App\Http\Responses\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Responsable;

class LoginResponse implements Responsable
{
    public function toResponse($request)
    {
        try {
            $valid = $this->validate($request);
            if (!$valid->status) return $this->invalid($valid->message, 400);

            $check = $this->credential_checker($request);
            if (!$check) return $this->invalid('Email or Password is Invalid', 400);

            $process = $this->process($request);
            return $this->success($process);
        } catch (\Exception $error) {
            return $this->invalid($error->getMessage(), 500);
        }
    }

    protected function validate($request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|max:10',
        ]);

        if ($validator->fails()) {
            return (object) [
                'status' => false,
                'message' => $validator->errors()->first(),
            ];
        }

        return (object) [ 'status' => true ];
    }

    protected function credential_checker($request)
    {
        $user = User::where('email', $request->email)->first();
        return Hash::check($request->password, $user->password);
    }

    protected function process($request)
    {
        $user = User::where('email', $request->email)->first();
        $token = Str::random(10);
        return [ 'token' => Str::uuid($user->password).$token.Str::uuid($user->email) ];
    }

    protected function invalid($message, $code)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => null,
        ], $code);
    }

    protected function success($payload)
    {
        return response()->json([
            'code' => 200,
            'message' => 'OK',
            'data' => $payload,
        ], 200);
    }
}
