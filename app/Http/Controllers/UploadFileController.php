<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

// response
use App\Http\Responses\Upload\UploadResponse;

class UploadController extends Controller
{
    public function __invoke()
    {
        return new UploadResponse;
    }
}
