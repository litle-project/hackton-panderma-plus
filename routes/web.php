<?php

/** @var \Laravel\Lumen\Routing\Router $router */
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => '/api'], function () use ($router) {
    $router->group(['prefix' => '/auth'], function () use ($router) {
        $router->post('/register', 'AuthController@register');
        $router->post('/login', 'AuthController@login');
    });

    $router->group(['prefix' => '/general'], function () use ($router) {
        $router->get('/banner', 'BannerController');
        $router->get('/category', 'CategoryController');
    });

    $router->group(['prefix' => '/user'], function () use ($router) {
        $router->get('/profile', 'UserController@profile');
        $router->post('/update', 'UserController@update');
    });
});
