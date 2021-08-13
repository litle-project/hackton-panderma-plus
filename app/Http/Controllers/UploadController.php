<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// response
use App\Http\Responses\Upload\UploadResponse;

class UploadController extends Controller
{
    public function __invoke(Request $request)
    {
        return new UploadResponse;
    }
}
