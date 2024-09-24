<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Clear all cache
Route::get('cache_clear', function () {
	\Artisan::call('cache:clear');
	//  Clears route cache
	\Artisan::call('route:clear');
	\Cache::flush();
	\Artisan::call('optimize');
	exec('composer dump-autoload');

	dd("Cache cleared!");
});


include_once(app_path('Http/Routes/admin.php'));
include_once(app_path('Http/Routes/support.php'));
include_once(app_path('Http/Routes/front.php'));
include_once(app_path('Http/Routes/api.php'));