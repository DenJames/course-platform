<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CourseCategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', CourseController::class)->name('welcome');

Route::get('/courses/{category:slug}', CourseCategoryController::class)->name('courses.category');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart');


Route::prefix('checkout')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout');

    Route::post('/user/create', [CheckoutController::class, 'store'])->name('user.store');
    Route::post('/user/existence', [UserController::class, 'exists'])->name('check.user.existence');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/password/create/{user}', [PasswordController::class, 'create'])
    ->name('password.create')
    ->middleware('signed'); // This ensures the URL can only be accessed if it's signed

Route::post('/password/store/{user}', [PasswordController::class, 'store'])
    ->name('store.password');

require __DIR__.'/auth.php';
