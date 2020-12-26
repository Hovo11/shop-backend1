<?php

use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\CarsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController as authUser;
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
    Route::post("signUp",[AuthController::class,"signUp"]);
    Route::post("checkEmailCode",[AuthController::class,"checkEmailCode"]);
    Route::post("forgotPassword",[AuthController::class,"forgotPassword"]);
    Route::post("checkCode",[AuthController::class,"checkCode"]);
    Route::post("changePassword",[AuthController::class,"changePassword"]);

});
Route::prefix('/admin')->middleware(["auth","admin"])->group(function () {
    Route::post("/image", [UsersController::class, 'image']);
    Route::post("/", [UsersController::class, 'index']);
    Route::prefix('/users')->group(function () {
        Route::post("/", [UsersController::class, 'getAll']);
        Route::post("/edit{id}", [UsersController::class, 'edit']);
        Route::post("/save{id}", [UsersController::class, 'save']);
        Route::post("/delete{id}", [UsersController::class, 'delete']);
        Route::post("/userInfo", [UsersController::class, 'userInfo']);

    });
});

Route::prefix('/car')->middleware('auth')->group(function () {
    Route::post("/add", [CarsController::class, 'AddCar']);
    Route::post("/get", [CarsController::class, 'getCars'])->withoutMiddleware('auth');
    Route::post("/save", [CarsController::class, 'save']);
    Route::post("/delete", [CarsController::class, 'delete']);
    Route::post("/take", [CarsController::class, 'take']);
    Route::post("/decline", [CarsController::class, 'decline']);
    Route::post("/getToDo", [CarsController::class, 'getToDo']);

});

Route::prefix('/user')->middleware(['auth','seller'])->group(function () {
    Route::post("/save", [authUser::class, 'save'])->withoutMiddleware('seller');
    Route::post("/getMyCars", [CarsController::class, 'getMyCars']);
});
