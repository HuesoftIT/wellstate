<?php

use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\EmployeeWorkingShiftController;

header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');


Route::auth();

Route::get('ajax/{action}', 'AjaxController@index');

Route::post('ajaxPost/{action}', 'AjaxPostController@index');

Route::get('/ajax/branch/{id}/employees', [EmployeeWorkingShiftController::class, 'employeesByBranch']);
Route::get('/ajax/branch/{id}/working-shifts', [EmployeeWorkingShiftController::class, 'workingShiftsByBranch']);


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'locale']], function () {
	Route::get('/', 'HomeController@index');

	Route::resource('roles', 'Admin\RolesController');
	Route::resource('permissions', 'Admin\PermissionsController');
	Route::resource('users', 'Admin\UsersController');

	Route::resource('news', 'Admin\NewsController');
	Route::resource('categories', 'Admin\CategoryController');
	Route::resource('provinces', 'Admin\ProvinceController');
	Route::resource('districts', 'Admin\DistrictController');
	Route::resource('wards', 'Admin\WardController');

	// Route::resource('agents', 'Admin\AgentController');
	Route::get('profile', 'Admin\ProfileController@getProfile');
	Route::post('profile', 'Admin\ProfileController@postProfile');
	Route::get('company-settings', 'CompanySettingsController@edit');
	Route::patch('company-settings', 'CompanySettingsController@update');
	Route::get('settings', 'SettingController@index');
	Route::patch('settings', 'SettingController@update');
	Route::post('change_locale', 'LocaleController@changeLocale');

	// Wellbe below here
	Route::resource('promotions', 'Admin\PromotionController');
	Route::resource('service-categories', 'Admin\ServiceCategoryController');
	Route::resource('services', 'Admin\ServiceController');
	Route::resource('post-categories', 'Admin\PostCategoryController');
	Route::resource('posts', 'Admin\PostController');
	Route::resource('post-comments', 'Admin\PostCommentController')
		->only(['index', 'show', 'destroy']);
	Route::prefix('post-comments')->group(function () {
		Route::patch('{id}/approve', 'Admin\PostCommentController@approve')
			->name('post-comments.approve');

		Route::patch('{id}/spam', 'Admin\PostCommentController@spam')
			->name('post-comments.spam');
	});
	Route::resource('memberships', 'Admin\MembershipController');
	Route::resource('branches', 'Admin\BranchController');
	Route::resource('images', 'Admin\ImageController');
	Route::resource('image-categories', 'Admin\ImageCategoryController');
	Route::resource('room-types', 'Admin\RoomTypeController');
	Route::resource('branch-room-types', 'Admin\BranchRoomTypeController');
	Route::resource('employees', 'Admin\EmployeeController');
	Route::resource('employee-services', 'Admin\EmployeeServiceController');
	Route::resource('working-shifts', 'Admin\WorkingShiftController');

	Route::get(
		'employee-working-shifts/calendar',
		[EmployeeWorkingShiftController::class, 'calendar']
	)->name('employee-working-shifts.calendar');

	Route::get(
		'employee-working-shifts/calendar/events',
		[EmployeeWorkingShiftController::class, 'calendarEvents']
	)->name('employee-working-shifts.calendar.events');
	Route::resource('employee-working-shifts', 'Admin\EmployeeWorkingShiftController');
	Route::resource('bookings', 'Admin\BookingController');
	Route::patch('bookings/{id}/cancel', [BookingController::class, 'cancel'])
		->name('bookings.cancel');

	Route::patch('bookings/{id}/confirm', [BookingController::class, 'confirm'])
		->name('bookings.confirm');
	Route::patch('/bookings/{id}/confirm-payment', [
		BookingController::class,
		'confirmPayment'
	])->name('bookings.confirm-payment');
	Route::patch(
		'/admin/bookings/{booking}/complete',
		[BookingController::class, 'complete']
	)->name('bookings.complete');
});
