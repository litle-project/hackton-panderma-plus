<?php

namespace App\Http\Responses\Donor;

use Carbon\Carbon;
use App\Models\Donor;
use Illuminate\Contracts\Support\Responsable;

class DonorResponse implements Responsable
{
    public function toResponse($request)
    {
        try {
            $data = $this->process($request);

            if (count($data) > 0) {
                return response()->json([
                    'code' => 200,
                    'message' => 'OK',
                    'data' => $data,
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'message' => 'No Content',
                    'data' => [],
                ], 404);
            }
        } catch (\Exception $error) {
            return response()->json([
                'code' => 500,
                'message' => $error->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    protected function process($request)
    {
        $donor = Donor::query()
            ->select('donor_id', 'title', 'address', 'deadline', 'cover')
            ->addSelect('category_id', 'type')
            ->when(!empty($request->category_id), function($query) use($request) {
                $query->where('category_id', $request->category_id);
            })
            ->when(!empty($request->keyword), function($query) use($request) {
                $query->where('title', 'like', '%'.$request->keyword.'%')
                    ->orWhere('address', 'like', '%'.$request->keyword.'%');
            })
            ->when(!empty($request->type), function($query) use($request) {
                $query->where('type', $request->type);
            })
            ->when($request->type == 'GIVER', function($query) use($request) {
                $query->leftJoin('users', 'users.user_id', '=', 'donors.user_id')
                    ->addSelect('users.full_name');
            })
            ->simplePaginate(10);

        $donor->each(function($item) {
            $item->deadline_date = $item->deadline;
            $item->deadline = Carbon::now()->diffForHumans($item->deadline);
            if ($item->type === 'GIVER') {
                $item->full_name = $item->full_name ?? 'Pendonor';
            }
        });

        return $donor;
    }
}
