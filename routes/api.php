<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use Tymon\JWTAuth\Http\Middleware\Authenticate as JWTAuthenticate; // Import JWT Middleware Class

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::middleware(JWTAuthenticate::class)->group(function () {

// Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tickets', TicketController::class);
});
