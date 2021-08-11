<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// response
use App\Http\Responses\User\UserResponse;
use App\Http\Responses\User\UserUpdateResponse;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        return new UserResponse;
    }

    public function update(Request $request)
    {
        return new UserUpdateResponse;
    }
}
