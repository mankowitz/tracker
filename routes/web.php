<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EncounterController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', [EncounterController::class, 'index'])->name('dashboard');
    Route::get('/patient/search', [PatientController::class, 'search']);
    Route::get('/print-dashboard', [EncounterController::class, 'print'])->name('print');
    Route::resource('user', UserController::class);
    Route::resource('encounter', EncounterController::class);
    Route::resource('patient', PatientController::class);
});
