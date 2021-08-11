<?php

namespace App\Http\Responses\Donor;

use Carbon\Carbon;
use App\Models\Donor;
use Firebase\JWT\JWT;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Responsable;

class DonorCreateResponse implements Responsable
{
    public function toResponse($request)
    {
        try {
            $valid = $this->validate($request);
            if (!$valid->status) return $this->invalid($valid->message, 400);

             $this->process($request);
            return $this->success();
        } catch (\Exception $error) {
            return $this->invalid($error->getMessage(), 500);
        }
    }

    protected function validate($request)
    {
        $validator = Validator::make($request->all(), [
            'cover' => 'sometimes|nullable|url',
            'title' => 'required|max:225',
            'category_id' => 'required|exists:categories,category_id|numeric',
            'type' => 'required|in:SEEKER,GIVER',
            'deadline' => 'required_if:type,==,SEEKER|numeric|min:0',
            'total_need' => 'required_if:type,==,SEEKER|numeric',
            'phone' => 'required|min:10|max:15',
            'address' => 'required|max:225',
            'description' => 'required|max:1000',
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
        $date = Carbon::now()->addDays($request->deadline)->toDateString();;
        $token = JWT::decode($request->bearerToken(), env('JWT_SECRET'), ['HS256']);

        return Donor::create([
            'type' => $request->type,
            'title' => $request->title,
            'cover' => $request->cover ?? '',
            'deadline' => $request->deadline === 0 ? null : $date,
            'total_need' => $request->total_need,
            'phone' => $request->phone,
            'address' => $request->address,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'user_id' => $token->data->id,
            'category_id' => $request->category_id,
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

    protected function success()
    {
        return response()->json([
            'code' => 200,
            'message' => 'OK',
            'data' => true,
        ], 200);
    }
}
