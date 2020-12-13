<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
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
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('dashboard');

    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('projects');
        Route::get('/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/{project}/update', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/{project}/destroy', [ProjectController::class, 'destroy'])->name('projects.destroy');

        Route::post('/reorder', [ProjectController::class, 'reorder'])->name('projects.reorder');
    });

    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('tasks');
        Route::get('/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
        Route::put('/{task}/update', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/{task}/destroy', [TaskController::class, 'destroy'])->name('tasks.destroy');

        Route::post('/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
        Route::post('/search', [TaskController::class, 'search'])->name('tasks.search');
        Route::post('/update-select-priority', [TaskController::class, 'updateSelectPriority'])->name('tasks.updateSelectPriority');
    });
});

require __DIR__.'/auth.php';
