<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\CustomerController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/services/by-category/{category}', [ServiceController::class, 'getByCategory']);
Route::get('/customers/search', [CustomerController::class, 'search']);

Route::get('/branches/{id}/room-types', [BookingController::class, 'getRoomTypesByBranch']);
Route::get('/services', [ServiceController::class, 'byCategory']);
