<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\BookController;




Route::post('/login', [AuthController::class,'login']);
Route::post('/register', [AuthController::class,'register']);

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    // Route::post('/logout', [AuthController::class,'logout']); 

    Route::post('/logout',[AuthController::class,'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Book APIs
    Route::post('/books', [BookController::class, 'store']);
    Route::get('/books/{id}', [BookController::class, 'show']);
    Route::get('/books', [BookController::class, 'index']);
    Route::put('/books/{id}', [BookController::class, 'update']);
    Route::patch('/books/{id}', [BookController::class, 'updatepartial']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);
});
