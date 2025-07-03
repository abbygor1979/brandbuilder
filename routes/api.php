<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AIChatController;
use App\Http\Controllers\Api\BrandStatusController;
use App\Http\Controllers\Api\ComponentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::post('/components/order', [ComponentController::class, 'updateOrder'])->name('components.order');
    Route::post('/components/update', [ComponentController::class, 'update'])->name('components.update');
    Route::post('/landing-page/ai-chat', [AIChatController::class, 'handle'])->name('landing-page.ai-chat');
    Route::get('/brands/{brand}/status', [BrandStatusController::class, 'status'])->name('brands.status');
});
