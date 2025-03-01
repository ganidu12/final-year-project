<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PatientHistoryController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
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

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register-user', [RegisterController::class, 'register'])->name('register.submit');
    Route::post('/login-user', [LoginController::class, 'login'])->name('login.submit');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/view-report', function () {
        return view('report-generation.report'); // Make sure this matches the actual path of your Blade file
    });
    Route::post('/predict', [PredictionController::class, 'predict'])->name('predict');
    Route::get('/analyze-fracture', [PredictionController::class, 'index'])->name('analyze-fracture');
    Route::get('/check-history', [PatientHistoryController::class, 'getHistory'])->name('getHistory');
    Route::put('/feedback', [PatientHistoryController::class, 'addFeedback'])->name('addFeedback');
    Route::delete('/delete-history', [PatientHistoryController::class, 'deleteHistory'])->name('deleteHistory');
    Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('updateProfile');
    Route::get('/edit-profile', [UserController::class, 'profileIndex'])->name('edit-profile');
    Route::post('/fetch-patient-details-email', [UserController::class, 'fetchPatientDetailsWithEmail'])->name('fetchPatientDetailsEmail');
    Route::post('/fetch-patient-details-name', [UserController::class, 'fetchPatientDetailsWithName'])->name('fetchPatientDetailsName');
    Route::post('/logout', [LoginController::class, 'logoutUser'])->name('logout');
    Route::post('/download-pdf', [ReportController::class, 'generatePDF'])->name('download.pdf');
});



