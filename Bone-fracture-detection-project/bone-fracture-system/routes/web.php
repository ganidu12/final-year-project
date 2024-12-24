<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PredictionController;
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
    return view('welcome');
});

Route::post('/predict', [PredictionController::class, 'predict']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register-user', [RegisterController::class, 'register'])->name('register.submit');
Route::post('/login-user', [LoginController::class, 'login'])->name('login.submit');
Route::get('/home-page', function () {
    return view('home');
})->name('home');

