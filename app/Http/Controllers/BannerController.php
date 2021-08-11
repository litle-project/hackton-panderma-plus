<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

// response
use App\Http\Responses\Banner\BannerResponse;

class BannerController extends Controller
{
    public function __invoke()
    {
        return new BannerResponse;
    }
}
