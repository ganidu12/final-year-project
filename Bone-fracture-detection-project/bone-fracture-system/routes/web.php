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
    return view('landing');
});

Route::post('/predict/{type}', [PredictionController::class, 'predict'])->name('predict');;
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register-user', [RegisterController::class, 'register'])->name('register.submit');
Route::post('/login-user', [LoginController::class, 'login'])->name('login.submit');
Route::get('/analyze-fracture', function () {
    return view('analyze');
})->name('analyze-fracture');
Route::get('/check-history', function () {
    return view('check-history');
})->name('check-history');

Route::get('/edit-profile', function () {
    return view('profile');
})->name('edit-profile');

