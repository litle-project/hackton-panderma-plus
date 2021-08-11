<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

// response
use App\Http\Responses\Category\CategoryResponse;

class CategoryController extends Controller
{
    public function __invoke()
    {
        return new CategoryResponse;
    }
}
