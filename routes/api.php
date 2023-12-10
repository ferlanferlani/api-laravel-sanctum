<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/randomwkwkw', function () {
    return response()->json([
        'message' => 'Unauthenticated.',
    ], 401);
})->name('login');

// user Controller
Route::get('getalluser', [UserController::class, 'index'])->middleware('auth:sanctum');
// end user controller

// Auth Controller
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
// End Auth Controller

// product controller
Route::post('addproduct', [ProductController::class, 'store'])->middleware('auth:sanctum');
Route::get('getallproduct', [ProductController::class, 'index'])->middleware('auth:sanctum');
// end product controller


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
