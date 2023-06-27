<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;

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

Route::get('/', [TasksController::class, 'index']);

//Route::get('/dashboard', [TasksController::class, 'show'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [TasksController::class, 'index'])->name('dashboard');
    Route::resource('tasks', TasksController::class);
});

require __DIR__.'/auth.php';

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
})->name('fallback.404');