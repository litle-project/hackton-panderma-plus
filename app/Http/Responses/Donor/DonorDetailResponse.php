<?php

namespace App\Http\Responses\Donor;

use Carbon\Carbon;
use App\Models\Donor;
use Illuminate\Contracts\Support\Responsable;

class DonorDetailResponse implements Responsable
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
                    'code' => 404,
                    'message' => 'No Content',
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
        $donor = Donor::query()
            ->select('donor_id', 'title', 'address', 'deadline', 'cover')
            ->addSelect('categories.category_id', 'type', 'users.full_name')
            ->addSelect('donors.phone', 'users.user_id')
            ->addSelect('description', 'total_need', 'categories.name as category_name')
            ->leftJoin('categories', 'categories.category_id', '=', 'donors.category_id')
            ->leftJoin('users', 'users.user_id', '=', 'donors.user_id')
            ->where('donor_id', $request->get('donor_id'))
            ->first();

        if ($donor) {
            $donor->deadline_date = $donor->deadline;
            $donor->deadline = Carbon::now()->diffForHumans($donor->deadline);
            if ($donor->type === 'GIVER') {
                $donor->full_name = $donor->full_name ?? 'Pendonor';
            } else {
                unset($donor['full_name']);
            }
        }

        return $donor;
    }
}
