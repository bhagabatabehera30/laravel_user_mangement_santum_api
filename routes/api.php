<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;

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

// Admin
Route::group(['prefix' => 'v2'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/register', [UserController::class, 'register']);   
        Route::post('/login/email', [UserController::class, 'loginWithEmail']); 
        Route::post('/login/mobile', [UserController::class, 'loginWithMobile']); 

        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::get('/user/profile', [UserController::class, 'UserProfile']); 
            Route::get('/user/logout', [UserController::class, 'logOut']);
        });
    });
    
});

