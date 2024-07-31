<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
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
    Route::resource('/doctors', DoctorController::class);
});

Route::group(['middleware' => ['auth', 'role:admin,manager']], function() {
    Route::get('/appointments/create', [App\Http\Controllers\AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments/new', [App\Http\Controllers\AppointmentController::class, 'new'])->name('appointments.new');
    Route::get('/appointments/all', [App\Http\Controllers\AppointmentController::class, 'appointments'])->name('appointments.all');
    Route::resource('appointments', App\Http\Controllers\AppointmentController::class);

    Route::get('/patients/search', [PatientController::class, 'search']);
    Route::get('/patients/all', [PatientController::class, 'all'])->name('patients.all');
    Route::get('/patients/patient/{id}', [PatientController::class, 'patient'])->name('patients.patient');
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('/patients/new', [PatientController::class, 'new'])->name('patients.new');

});


Route::middleware('auth')->group(function () {
    Route::get('/user/role', [UserController::class, 'getUserRole']);
});



require __DIR__.'/auth.php';
