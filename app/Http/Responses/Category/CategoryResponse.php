<?php

namespace App\Http\Responses\Category;

use App\Models\Category;
use Illuminate\Contracts\Support\Responsable;

class CategoryResponse implements Responsable
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
        return Category::query()
            ->select('category_id', 'icon')
            ->get()
            ->map(function($item) {
                $item->icon = url('/icon/' . $item->icon);
                return $item;
            })->values();
    }
}
