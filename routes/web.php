<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
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

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/reports/print', [DashboardController::class, 'printReport'])->middleware(['auth', 'verified'])->name('reports.print');
Route::get('/reports/excel', [DashboardController::class, 'exportExcel'])->middleware(['auth', 'verified'])->name('reports.excel');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Simulations
    Route::get('/simulasi', [\App\Http\Controllers\SimulationController::class, 'index'])->name('simulasi.index');
    Route::post('/simulasi/calculate', [\App\Http\Controllers\SimulationController::class, 'calculate'])->name('simulasi.calculate');
    Route::post('/simulasi/store', [\App\Http\Controllers\SimulationController::class, 'store'])->name('simulasi.store');
});

use App\Http\Controllers\EquipmentTypeController;
use App\Http\Controllers\JobPlanController;
use App\Http\Controllers\SiteClassController;
use App\Http\Controllers\NonTechnicalPositionController;
use App\Http\Controllers\NonTechnicalRequirementController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\EquipmentCategoryBaselineController;

Route::middleware(['auth', 'verified', 'role:Super Admin'])->group(function () {
    Route::post('equipment-types/import', [EquipmentTypeController::class, 'import'])->name('equipment-types.import');
    Route::get('equipment-types/download-template', [EquipmentTypeController::class, 'downloadTemplate'])->name('equipment-types.download-template');
    Route::resource('equipment-types', EquipmentTypeController::class)->except(['create', 'show', 'edit']);
    Route::resource('job-plans', JobPlanController::class)->except(['create', 'show', 'edit']);
    Route::resource('site-classes', SiteClassController::class)->except(['create', 'show', 'edit']);
    Route::resource('non-technical-positions', NonTechnicalPositionController::class)->except(['create', 'show', 'edit']);
    Route::get('non-technical-requirements', [NonTechnicalRequirementController::class, 'index'])->name('non-technical-requirements.index');
    Route::post('non-technical-requirements', [NonTechnicalRequirementController::class, 'store'])->name('non-technical-requirements.store');
    Route::resource('equipment-category-baselines', EquipmentCategoryBaselineController::class)->except(['create', 'show', 'edit']);
    Route::post('sites/import', [SiteController::class, 'import'])->name('sites.import');
    Route::get('sites/download-template', [SiteController::class, 'downloadTemplate'])->name('sites.download-template');
    Route::get('sites/export', [SiteController::class, 'export'])->name('sites.export');
    Route::resource('sites', SiteController::class)->except(['create', 'show', 'edit']);

    Route::get('settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
