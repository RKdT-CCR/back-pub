<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
$request_data = [
    'method' => app('request')->method(),
    'body' => app('request')->all()
];
Log::notice(url()->current()." ".print_r($request_data,true));


Route::post('login',[UserController::class, 'login']);

Route::get('index',[UserController::class, 'index'])->middleware('auth:api');

Route::prefix('company')->group(function () {
    Route::post('register',[UserController::class, 'registerCompany']);
});

Route::prefix('person')->group(function () {
    Route::post('register',[UserController::class, 'registerPerson']);
});

Route::middleware('auth:api')->get('/test-auth', function (Request $request) {
    return response()->json(['msg'=>'ok'],420);
});
