<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
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
    Route::get('/forgot-password', [ForgotPasswordController::class, 'index'])->name('forgot-password');
    Route::post('/send-reset-code', [ForgotPasswordController::class, 'sendResetCode'])->name('send-reset-code');
    Route::post('/verify-reset-code', [ForgotPasswordController::class, 'verifyResetCode']);
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);
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
    Route::get('/preview-email', function () {
        $patientData = [
            'diagnosis' => 'Fracture Detected',
            'doctorName' => 'Dr. Smith',
            'image_url' => url('http://127.0.0.1:8000/storage/temp/0kfNAnxv1t_output.jpg'), // Change this to a real image path
            'fracture_size' => 12.5, // Example size in mm
            'healing_time' => '6-8 weeks'
        ];

        return view('emails.patient_report', ['data' => $patientData]);
    });
});



