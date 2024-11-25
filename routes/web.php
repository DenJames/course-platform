<?php

use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Course;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'courses' => Course::with(['author'])->get(),
    ]);
})->name('welcome');

// Checkout
Route::get('/checkout', function () {
    return Inertia::render('Checkout');
})->name('checkout');

Route::get('/dashboard', function () {
    return to_route('welcome');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/user/create', [UserController::class, 'store'])->name('user.store');
Route::post('/check/user/existence', [UserController::class, 'check'])->name('check.user.existence');

Route::get('/password/create/{user}', [PasswordController::class, 'create'])
    ->name('password.create')
    ->middleware('signed'); // This ensures the URL can only be accessed if it's signed

Route::post('/password/store/{user}', [PasswordController::class, 'store'])
    ->name('password.custom.store');

require __DIR__.'/auth.php';
