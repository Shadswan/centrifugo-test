<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CentrifugoProxyController;
use App\Http\Controllers\RoomController;
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

Route::group([
    'middleware' => 'auth',
], function () {
    Route::post('/centrifugo/connect', [CentrifugoProxyController::class, 'connect'])->name('centrifugo.connect');
    Route::post('/rooms/{id}/publish', [RoomController::class, 'publish'])->name('rooms.publish');
});