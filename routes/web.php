<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DateController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\CoupleSetupController;
use App\Http\Controllers\CoupleInvitationController;
use App\Http\Controllers\ProfileController;

// Public welcome page
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes (Laravel Breeze)
require __DIR__.'/auth.php';

// Protected Routes (require authentication)
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Couple Setup Routes
    Route::get('/couple/setup', [CoupleSetupController::class, 'index'])->name('couple.setup');
    Route::post('/couple/create', [CoupleSetupController::class, 'create'])->name('couple.create');
    Route::get('/couple/profile', [CoupleSetupController::class, 'profile'])->name('couple.profile');
    Route::patch('/couple/profile', [CoupleSetupController::class, 'update'])->name('couple.update');
    Route::post('/couple/milestone', [CoupleSetupController::class, 'addMilestone'])->name('couple.milestone');

    // Couple Invitation Routes
    Route::get('/couple/invite', [CoupleInvitationController::class, 'show'])->name('couple.invite');
    Route::get('/couple/join', [CoupleInvitationController::class, 'showJoin'])->name('couple.join.form');
    Route::post('/couple/join', [CoupleInvitationController::class, 'join'])->name('couple.join');
    Route::get('/couple/invitation-code', [CoupleInvitationController::class, 'showInvitationCode'])->name('couple.invitation-code');

    // Date Planning Routes (require couple membership)
    Route::middleware(['couple.member'])->group(function () {
        Route::get('/dates', [DateController::class, 'index'])->name('dates.index');
        Route::get('/dates/create', [DateController::class, 'create'])->name('dates.create');
        Route::post('/dates', [DateController::class, 'store'])->name('dates.store');
    });

    // Trip Planning Routes (require couple membership)
    Route::middleware(['couple.member'])->group(function () {
        Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
        Route::get('/trips/create', [TripController::class, 'create'])->name('trips.create');
        Route::post('/trips', [TripController::class, 'store'])->name('trips.store');
        Route::get('/trips/{trip}', [TripController::class, 'show'])->name('trips.show');
    });
});
