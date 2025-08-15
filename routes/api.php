<?php

use App\Http\Controllers\BetController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::apiResource('events', EventController::class)->only(['index', 'show']);
Route::post('bets', [BetController::class, 'calculate']);
