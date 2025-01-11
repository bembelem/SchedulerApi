<?php

use App\Http\Controllers\Schedule\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function(){
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('events', [EventController::class, 'index']);
    Route::post('events', [EventController::class, 'store']);
    Route::delete('events/{id}', [EventController::class, 'destroy']);
    Route::get('/events/week', [EventController::class, 'getWeek']);
    Route::patch('/events/{id}', [EventController::class, 'update']);
});