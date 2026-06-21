<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

use App\Http\Controllers\EquipmentTypeController;
use App\Http\Controllers\JobPlanController;
use App\Http\Controllers\SiteClassController;
use App\Http\Controllers\NonTechnicalPositionController;
use App\Http\Controllers\NonTechnicalRequirementController;

Route::middleware(['auth', 'verified', 'role:Super Admin'])->group(function () {
    Route::post('equipment-types/import', [EquipmentTypeController::class, 'import'])->name('equipment-types.import');
    Route::get('equipment-types/download-template', [EquipmentTypeController::class, 'downloadTemplate'])->name('equipment-types.download-template');
    Route::resource('equipment-types', EquipmentTypeController::class)->except(['create', 'show', 'edit']);
    Route::resource('job-plans', JobPlanController::class)->except(['create', 'show', 'edit']);
    Route::resource('site-classes', SiteClassController::class)->except(['create', 'show', 'edit']);
    Route::resource('non-technical-positions', NonTechnicalPositionController::class)->except(['create', 'show', 'edit']);
    Route::get('non-technical-requirements', [NonTechnicalRequirementController::class, 'index'])->name('non-technical-requirements.index');
    Route::post('non-technical-requirements', [NonTechnicalRequirementController::class, 'store'])->name('non-technical-requirements.store');
});

require __DIR__.'/auth.php';
