<?php

use App\Http\Controllers\Schedule\EventController;
use App\Http\Controllers\Schedule\WeekTemplateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function(){
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::prefix('events/week')->group(function () {
        Route::get('/', [EventController::class, 'getWeek']);
        Route::post('/', [EventController::class, 'updateWeek']);
        Route::delete('/', [EventController::class, 'deleteWeek']);
    });

    // События
    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index']);
        Route::post('/', [EventController::class, 'store']);
        Route::patch('/{id}', [EventController::class, 'update']);
        Route::delete('/{id}', [EventController::class, 'destroy']);
    });

    // Шаблоны
    Route::prefix('week-templates')->group(function () {
        Route::get('/', [WeekTemplateController::class, 'index']);
        Route::post('/', [WeekTemplateController::class, 'store']);
        Route::post('/{id}/apply', [WeekTemplateController::class, 'apply']);
        Route::patch('/{id}', [WeekTemplateController::class, 'update']);
        Route::delete('/{id}', [WeekTemplateController::class, 'destroy']);
    });
});