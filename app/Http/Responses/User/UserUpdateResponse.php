<?php

namespace App\Http\Responses\User;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Responsable;

class UserUpdateResponse implements Responsable
{
    public function toResponse($request)
    {
        try {
            $valid = $this->validate($request);
            if (!$valid->status) return $this->invalid($valid->message, 400);

            $update = $this->process($request);
            return $this->success($update);
        } catch (\Exception $error) {
            return $this->invalid($error->getMessage(), 500);
        }
    }

    protected function validate($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:225',
            'birthday' => 'required|date',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'photo_profile' => 'sometimes|nullable|url',
            'phone' => [
                'required',
                'min:10',
                'max:15',
                'unique:users,phone,' . $request->phone . ',phone'
            ],

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
        $token = JWT::decode($request->bearerToken(), env('JWT_SECRET'), ['HS256']);
        $user = User::select(
            'full_name',
            'email',
            'phone',
            'birthday',
            'gender',
            'photo_profile',
            'is_verified'
        )->where('user_id', $token->data->id)->first();

        $user->update([
            'full_name' => $request->name,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
            'photo_profile' => $request->photo_profile ?? ''
        ]);

        unset($user->updated_at);
        return $user;
    }

    protected function invalid($message, $code)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => null,
        ], $code);
    }

    protected function success($param)
    {
        return response()->json([
            'code' => 200,
            'message' => 'OK',
            'data' => $param,
        ], 200);
    }
}
