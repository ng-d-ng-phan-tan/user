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


Route::post('/test', function () {
    $response = new ResponseMsg("200", "test ok", "");
    return response()->json(($response));
});

Route::post('/crud', [UserInfoController::class, 'index']);
Route::post('/crud/add', [UserInfoController::class, 'create']);
Route::post('/crud/update', [UserInfoController::class, 'update']);
Route::post('/crud/delete', [UserInfoController::class, 'delete']);
Route::post('/crud/getUser', [UserInfoController::class, 'getUser']);
