<?php

use App\Http\Controllers\Admin\BranchRoomTypeController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\PromotionController;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/theme', function (Request $request) {
//    return $request->user();
//});


Route::get(
    '/ajax/branches/{id}/room-types',
    [BranchRoomTypeController::class, 'getRoomTypesById']
)->name('ajax.branch.room-types');
Route::post('/ajax/promotions/apply', [PromotionController::class, 'apply']);
Route::get('/ajax/branch-available-times', [BranchController::class, 'getAvailableTimes']);
