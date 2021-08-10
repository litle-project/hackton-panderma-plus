<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// response
use App\Http\Responses\Auth\RegisterResponse;
// use App\Http\Responses\Auth\LoginResponse;
// use App\Http\Responses\Auth\LogoutResponse;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        return new RegisterResponse;
    }

    public function login(Request $request)
    {
        // dd('masuk');
        // return new LoginResponse;
    }

    public function logout()
    {
        // return new LogoutResponse;
    }

}
