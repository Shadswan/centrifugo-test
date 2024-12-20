<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatControllerTwo;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/send-message', [ChatController::class, 'sendMessage']);
Route::get('/test', function (){
    return view('test');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/chat/{id}', [ChatControllerTwo::class, 'join'])->name('chat.join');
    Route::get('/chat/{id}', [ChatControllerTwo::class, 'show'])->name('chat.show');
    Route::get('api/token', [ChatControllerTwo::class, 'TokenGenerate']);
    Route::get('/canals', [ChatControllerTwo::class, 'canalsShow'])->name('canals');
    Route::post('/canals/send', [ChatControllerTwo::class, 'send'])->name('send.message');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/api.php';