<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin/login', function () {
    return view('admin.auth.login');
});

AdvancedRoute::controller("admin", 'Admin\MainController');

Route::group(['prefix' => 'admin'], function () {

    AdvancedRoute::controller("activities", 'Admin\ActivityController');

    AdvancedRoute::controller("amo-configs", 'Admin\AmoConfigController');

    AdvancedRoute::controller("/", 'Admin\MainController');
});

AdvancedRoute::controller("/", "PageController");

