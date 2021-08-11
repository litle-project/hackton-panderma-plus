<?php

namespace App\Http\Responses\User;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Responsable;

class UserResponse implements Responsable
{
    public function toResponse($request)
    {
        try {
            $data = $this->process($request);

            if ($data) {
                return response()->json([
                    'code' => 200,
                    'message' => 'OK',
                    'data' => $data,
                ], 200);
            } else {
                return response()->json([
                    'code' => 400,
                    'message' => 'You does not have permission for this user',
                    'data' => null,
                ], 404);
            }
        } catch (\Exception $error) {
            return response()->json([
                'code' => 500,
                'message' => $error->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    protected function process($request)
    {
        $token = JWT::decode($request->bearerToken(), env('JWT_SECRET'), ['HS256']);
        return User::query()
            ->select(
                'full_name',
                'email',
                'phone',
                'birthday',
                'gender',
                'photo_profile',
                'is_verified'
            )
            ->limit(1)
            ->where('user_id', $token->data->id)
            ->get()
            ->map(function($item) {
                $item->photo_profile = $item->photo_profile != '' ? url('/profile/' . $item->photo_profile) : '';
                return $item;
            })->first();
    }
}
