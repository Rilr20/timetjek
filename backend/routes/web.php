<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TimeRegistrationController;
use App\Http\Middleware\Authenticate;

Route::get('/', function () {
    return "helo";
});

Route::get('/token', function () {
    $token = csrf_token();
    return response($token, 218)
        ->header('Content-Type', 'application/json')
        ->header('Access-Control-Allow-Origin', '*');
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class,"register"]);
Route::apiResource('users', UserController::class)->middleware(Authenticate::class);
Route::post('time_registration/table', [TimeRegistrationController::class, 'table'])->middleware(Authenticate::class);
Route::apiResource('time_registration', TimeRegistrationController::class)->middleware(Authenticate::class);
