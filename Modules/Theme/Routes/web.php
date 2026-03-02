<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\BookingController;
use Modules\Theme\Http\Controllers\FrontendController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Mail;

Route::get('/test-mail', function () {
    Mail::raw('Test gửi mail từ Laravel', function ($message) {
        $message->to('lephuocthanhnhat0409@gmail.com')
            ->subject('Test Mail');
    });

    return 'Mail sent!';
});

Route::get('/', 'FrontendController@index')->name('page.home');

Route::get('ajaxFE/{action}', 'AjaxFrontEndController@index');

Route::get("/bai-viet/{slug}", "FrontendController@getDetailPost")->name('detail.post');
Route::get('/gioi-thieu', 'FrontendController@getIntroduce')->name('page.introduce');
Route::get('/lien-he', 'FrontendController@contact')->name('page.contact');
Route::get('/dich-vu', 'FrontendController@listServices')->name('page.service');
Route::get('/san-pham/{slug}', 'FrontendController@renderService');

Route::get('/dat-lich', [FrontendController::class, 'showBookingPage'])
    ->name('page.booking');
Route::post('/dat-lich', [BookingController::class, 'booking'])
    ->name('post.booking');


Route::get('/{slug}.html', 'FrontendController@getPage');


Route::group(['prefix' => '{slugParent}'], function () {
    Route::get('/', 'FrontendController@getListParents')->name('slugParent.getListParents');
    Route::get('/{slugDetail}', 'FrontendController@getDetail')->name('slugDetail.getDetail');
});
