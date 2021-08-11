<?php

namespace App\Http\Responses\Auth;

use App\Models\User;
use Firebase\JWT\JWT;
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
            if (!$check->match) return $this->invalid('Email or Password is Invalid', 400);
            if (!$check->verified) return $this->invalid('Your Account is Not Verified', 400);

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
        $password_match = Hash::check($request->password, $user->password);
        $is_verified = $user->is_verified;

        return (object) [
            'match' => $password_match,
            'verified' => $is_verified == 'false',
        ];
    }

    protected function process($request)
    {
        $user = User::where('email', $request->email)->first();
        $token = $this->generateToken($user);
        //
        // Redis::set('token: '. $user->email, $token);
        // Redis::expire('token: '. $user->email, 86400);

        return [ 'token' => $token ];
    }

    private function generateToken($user)
    {
        $key = env('JWT_SECRET', 'default-value');
        $generate_time = time();
        $expired_time = $generate_time + (60 * 60 * 24); //60 seconds * 60 minutes * 72 hours (3 days)

        $payload = [
            'iat' => $generate_time,
            'exp' => $expired_time,
            'data' => [
                'id' => $user->user_id,
                'email' => $user->email,
            ]
        ];

        return JWT::encode($payload, $key);
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
