<?php

use App\Http\Controllers\Admin\IndexController;
use App\Http\Controllers\Admin\QuizController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::prefix('admin')->middleware(['auth'])->group(function(){
    Route::resource('/home',IndexController::class);
    Route::resource('/quiz',QuizController::class);
});