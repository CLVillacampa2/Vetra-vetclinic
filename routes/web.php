<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClinicalController;

// Main Page
Route::get('/', function () { return view('welcome'); });

// Auth Routes
Route::post('/api/login', [ClinicalController::class, 'login']);
Route::post('/api/register', [ClinicalController::class, 'register']);
Route::post('/api/logout', [ClinicalController::class, 'logout']);

// Data Routes (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/api/data', [ClinicalController::class, 'getAllData']);
    Route::post('/api/save', [ClinicalController::class, 'saveRecord']);
    Route::post('/api/delete', [ClinicalController::class, 'deleteData']);
    Route::post('/api/medical-records', [ClinicalController::class, 'saveMedicalRecord']);
    Route::post('/api/medical-records/delete', [ClinicalController::class, 'deleteMedicalRecord']);
    Route::post('/api/profile/update', [ClinicalController::class, 'updateProfile']);
});