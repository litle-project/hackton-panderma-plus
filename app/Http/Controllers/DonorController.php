<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// response
use App\Http\Responses\Donor\DonorResponse;
use App\Http\Responses\Donor\DonorCreateResponse;
use App\Http\Responses\Donor\DonorDetailResponse;
use App\Http\Responses\Donor\DonorUpdateResponse;
use App\Http\Responses\Donor\DonorUrgentResponse;

class DonorController extends Controller
{
    public function index(Request $request)
    {
        return new DonorResponse;
    }

    public function create(Request $request)
    {
        return new DonorCreateResponse;
    }

    public function detail(Request $request)
    {
        return new DonorDetailResponse;
    }

    public function update(Request $request)
    {
        return new DonorUpdateResponse;
    }

    public function urgent(Request $request)
    {
        return new DonorUrgentResponse;
    }
}
