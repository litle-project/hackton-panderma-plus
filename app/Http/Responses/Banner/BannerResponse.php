<?php

namespace App\Http\Responses\Banner;

use App\Models\Banner;
use Illuminate\Contracts\Support\Responsable;

class BannerResponse implements Responsable
{
    public function toResponse($request)
    {
        try {
            $data = $this->process();

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

    protected function process()
    {
        return Banner::query()
            ->select('banner_id', 'image')
            ->get()
            ->map(function($item) {
                $item->image = url('/banner/' . $item->image);
                return $item;
            })->values();
    }
}
