<?php

use App\Jobs\SendEventReminders;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ImportEventController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [EventController::class, 'dashboard'])->name('dashboard');

    Route::get('/events/upcoming', [EventController::class, 'upcoming'])->name('events.upcoming');
    Route::get('/events/completed', [EventController::class, 'completed'])->name('events.completed');
    Route::post('/events/{event}/complete', [EventController::class, 'markComplete'])->name('events.complete');
    Route::post('/events/{event}/remind', [EventController::class, 'sendReminder'])->name('events.remind');
    Route::get('/events/import', [ImportEventController::class, 'showImportForm'])->name('events.import');
    Route::post('/events/import', [ImportEventController::class, 'import']);
    Route::resource('events', EventController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('/test-reminders', function () {
    dispatch(new SendEventReminders());
    return "Reminder job dispatched! Check your Mailtrap inbox.";
});

require __DIR__ . '/auth.php';
