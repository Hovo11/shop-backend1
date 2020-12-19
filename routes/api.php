<?php

use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class,"login"]);
    Route::post('logout', [AuthController::class,"logout"]);
    Route::post('refresh', [AuthController::class,"refresh"]);
    Route::post('me',  [AuthController::class,"me"]);
    Route::post("miban/{id}",[AuthController::class,"log"]);
    Route::post("signUp",[AuthController::class,"signUp"]);

});
Route::prefix('/admin')->middleware(["auth","admin"])->group(function () {
    Route::post("/", [UsersController::class, 'index']);
    Route::prefix('/users')->group(function () {
        Route::post("/", [UsersController::class, 'getAll']);
        Route::post("/edit{id}", [UsersController::class, 'edit']);
        Route::post("/save{id}", [UsersController::class, 'save']);
        Route::post("/delete{id}", [UsersController::class, 'delete']);
    });
});

