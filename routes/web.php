<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


Route::group(['middleware' => ['auth', 'role:admin']], function() {
    Route::resource('doctors', DoctorController::class);
});

Route::group(['middleware' => ['auth', 'role:manager']], function() {
    Route::resource('appointments', AppointmentController::class);
});


Route::get('appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
Route::post('appointments/new', [AppointmentController::class, 'store'])->name('appointments.new');

Route::get('/patients/search', [PatientController::class, 'search']);


require __DIR__.'/auth.php';
