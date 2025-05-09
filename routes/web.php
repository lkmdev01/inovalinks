<?php

use App\Http\Controllers\LinkController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/login', function () {
    return Inertia::render('auth/Login');
})->middleware(['guest'])->name('login');

Route::get('/register', function () {
    return Inertia::render('auth/Register');
})->middleware(['guest'])->name('register');

// Rotas para recuperação de senha
Route::middleware(['guest'])->group(function () {
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Links routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('links', LinkController::class);
    Route::post('links/order', [LinkController::class, 'updateOrder'])->name('links.order');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('linktree.profile.edit');
    Route::post('profile', [ProfileController::class, 'update'])->name('linktree.profile.update');
});

// Public profile route
Route::get('/{slug}', [ProfileController::class, 'show'])->name('profile.show');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
