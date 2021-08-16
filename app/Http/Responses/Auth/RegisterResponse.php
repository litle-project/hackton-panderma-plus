<?php

namespace App\Http\Responses\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
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
            'name' => 'required|max:225',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|max:10',
            'password_confirmation' => 'required|same:password',
            'phone' => 'required|min:10|max:15|unique:users,phone',
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
        $response = Http::withHeaders([
            'Authorization' => 'Basic c2VhZ2xlYXg3MDBAZ21haWwuY29tOldpbmRvd3MgOCQ=',
            'Content-Type' => 'application/json'
        ])->post('https://email.ocatelkom.co.id/api/v1/send-single', [
            'email' => 'litle.project@gmail.com',
            'message' => Str::random(5),
            'sender_email' => 'oca.telekomunikasi@gmail.com',
            'sender_name' => 'panderma-plus',
            'subject' => 'email verification',
        ]);

        User::create([
            'full_name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'verification_code' => Str::random(5),
            'is_verified' => 'false',
        ]);
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
