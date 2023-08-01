<?php

use App\Http\Controllers\UserInfoController;
use App\Models\ResponseMsg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/test', function () {
    return "ok";
});

Route::get('', function () {
    return view('crud');
});

Route::post('/add', [UserInfoController::class, 'create']);

Route::post('/register', [UserInfoController::class, 'register']);


Route::post('/update', [UserInfoController::class, 'update']);
Route::post('/delete', [UserInfoController::class, 'delete']);
Route::post('/getUser', [UserInfoController::class, 'getUser']);
