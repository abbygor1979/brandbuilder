<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\SocialShareController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', [BrandController::class, 'index'])->name('home');
Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
Route::post('/brands/preview', [BrandController::class, 'preview'])->name('brands.preview');
Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
Route::get('/brands/{brand}', [BrandController::class, 'show'])->name('brands.show');
Route::get('/brands/{landingPage}/download', [BrandController::class, 'download'])->name('brands.download');
Route::get('/analytics', [BrandController::class, 'analytics'])->name('analytics');
Route::get('/dashboard', [BrandController::class, 'dashboard'])->name('dashboard');
Route::post('/brands/{brand}/deploy', [BrandController::class, 'deploy'])->name('brands.deploy');
Route::post('/brands/{brand}/share', [SocialShareController::class, 'share'])->name('brands.share');
Route::get('/subscriptions', [SubscriptionsController::class, 'index'])->name('subscriptions.index');
Route::post('/subscriptions', [SubscriptionsController::class, 'store'])->name('subscriptions.store');

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/niches', [AdminController::class, 'storeNiche'])->name('admin.niches.store');
    Route::delete('/niches/{niche}', [AdminController::class, 'destroyNiche'])->name('admin.niches.destroy');
    Route::post('/domain-zones', [AdminController::class, 'storeDomainZone'])->name('admin.domain_zones.store');
    Route::delete('/domain-zones/{domainZone}', [AdminController::class, 'destroyDomainZone'])->name('admin.domain_zones.destroy');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
});