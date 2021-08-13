<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function() {
  return 'Hello World, Keep learn!';
});

$router->group(['prefix' => '/api'], function () use ($router) {
    $router->group(['prefix' => '/auth'], function () use ($router) {
        $router->post('/register', 'AuthController@register');
        $router->post('/login', 'AuthController@login');
    });

    $router->group(['prefix' => '/general'], function () use ($router) {
        $router->get('/banner', 'BannerController');
        $router->get('/category', 'CategoryController');
    });

    $router->group(['prefix' => '/donor'], function () use ($router) {
        $router->get('/', 'DonorController@index');
        $router->get('/detail', 'DonorController@detail');
        $router->post('/create', [
            'middleware' => 'auth',
            'uses' => 'DonorController@create',
        ]);
    });

    $router->group(['prefix' => '/user', 'middleware' => 'auth'], function () use ($router) {
        $router->get('/profile', 'UserController@profile');
        $router->post('/update', 'UserController@update');
    });
});
