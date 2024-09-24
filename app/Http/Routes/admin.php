<?php

$web_admin_path = config('app.project.admin_panel_slug');
$route_slug = 'admin_';

Route::group(array('prefix' => $web_admin_path), function () use($route_slug)
{

	$module_controller = "Admin\AuthController@";

	Route::get('/',['as' =>$route_slug.'login', 'uses' => $module_controller.'login']);
	Route::get('/login',['as' =>$route_slug.'login', 'uses' => $module_controller.'login']);
	Route::post('/validate_login',['as' =>$route_slug.'validate_login', 'uses' => $module_controller.'validate_login']);
	Route::get('/logout',['as' =>$route_slug.'validate_login', 'uses' => $module_controller.'logout']);

	$module_controller = "Admin\PasswordController@";

	Route::post('/forgot_password',['as' =>$route_slug.'forgot_password', 'uses' => $module_controller.'postEmail']);
	Route::post('/password_reset',['as' =>$route_slug.'password_reset', 'uses' => $module_controller.'postReset']);
	Route::get('/password_reset/{token?}',['as' =>$route_slug.'password_reset', 'uses' => $module_controller.'getReset']);

	Route::group(array('middleware' =>'admin_auth' ), function () use($route_slug)
	{
		Route::group(array('prefix' => 'dashboard'), function () use($route_slug)
		{	
			$module_controller = "Admin\DashboardController@";
			Route::get('/',['as' => $route_slug.'login', 'uses' => $module_controller.'index']);
		});

		Route::group(array('prefix' => 'site_settings'), function () use($route_slug)
		{	
			$module_controller = "Admin\SiteSettingController@";
			Route::get('/',['as' =>$route_slug.'login', 'uses' => $module_controller.'index']);
			Route::post('update_site_setting', ['as' => $route_slug.'dashboard', 'uses' => $module_controller.'update']);
		});

		Route::group(array('prefix' => 'admin_commission'), function () use($route_slug)
		{	
			$module_controller = "Admin\AdminCommissionController@";
			Route::get('/',['as' =>$route_slug.'admin_commission', 'uses' => $module_controller.'index']);
			Route::post('update', ['as' => $route_slug.'dashboard', 'uses' => $module_controller.'update']);
		});

		Route::group(array('prefix' => 'profile'), function () use($route_slug)
		{	
			$module_controller = "Admin\ProfileController@";

			Route::get('/',['as' =>$route_slug.'profile_setting', 'uses' => $module_controller.'index']);
			Route::post('/update/{enc_id}',['as' =>$route_slug.'update', 'uses' => $module_controller.'update']);
			Route::get('/change_password',['as' =>$route_slug.'change_password', 'uses' => $module_controller.'change_password']);
			Route::post('/update_password',['as' =>$route_slug.'update_password', 'uses' => $module_controller.'update_password']);
		});

		Route::group(array('prefix' => 'categories'), function () use($route_slug)
		{
			$module_controller = "Admin\CategoryController@";

			Route::get('/',['as' =>$route_slug.'categories', 'uses' => $module_controller.'index']);
			Route::get('/create',['as' =>$route_slug.'categories', 'uses' => $module_controller.'create']);
			Route::post('/store',['as' =>$route_slug.'categories', 'uses' => $module_controller.'store']);
			Route::get('/edit/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'edit']);
			Route::post('/update/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'update']);
			Route::get('/delete/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',['as' =>$route_slug.'categories', 'uses' => $module_controller.'multi_action']);

		});


		Route::group(array('prefix' => 'propertytype'), function () use($route_slug)
		{
			$module_controller = "Admin\PropertytypeController@";

			Route::get('/',['as' =>$route_slug.'propertytype', 'uses' => $module_controller.'index']);
			Route::get('/create',['as' =>$route_slug.'propertytype', 'uses' => $module_controller.'create']);
			Route::post('/store',['as' =>$route_slug.'propertytype', 'uses' => $module_controller.'store']);
			Route::get('/edit/{id?}',['as' =>$route_slug.'propertytype', 'uses' => $module_controller.'edit']);
			Route::post('/update/{id?}',['as' =>$route_slug.'propertytype', 'uses' => $module_controller.'update']);
			Route::get('/delete/{id?}',['as' =>$route_slug.'propertytype', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',['as' =>$route_slug.'propertytype', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',['as' =>$route_slug.'propertytype', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',['as' =>$route_slug.'propertytype', 'uses' => $module_controller.'multi_action']);

		});

		Route::group(array('prefix' => 'amenities'), function () use($route_slug)
		{
			$module_controller = "Admin\AmenitiesController@";

			Route::get('/',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'index']);
			Route::get('/create',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'create']);
			Route::post('/store',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'store']);
			Route::get('/edit/{id?}',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'edit']);
			Route::post('/update/{id?}',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'update']);
			Route::get('/delete/{id?}',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'multi_action']);

		});

		Route::group(array('prefix' => 'api_credentials'), function () use($route_slug)
		{
			$module_controller = 'Admin\ApiCredentialController@';

			Route::get('/', ['as' => $route_slug.'api_credentials', 'uses' => $module_controller.'index']);
			Route::post('update/{id?}', ['as' => $route_slug.'edit_api_credential', 'uses' => $module_controller.'update']);

		});	

		Route::group(array('prefix' => 'other_services'), function () use($route_slug)
		{
			$module_controller = "Admin\OtherServicesController@";

			Route::get('/',['as' =>$route_slug.'other_services', 'uses' => $module_controller.'index']);
			Route::get('/create',['as' =>$route_slug.'other_services', 'uses' => $module_controller.'create']);
			Route::post('/store',['as' =>$route_slug.'other_services', 'uses' => $module_controller.'store']);
			Route::get('/edit/{id?}',['as' =>$route_slug.'other_services', 'uses' => $module_controller.'edit']);
			Route::post('/update/{id?}',['as' =>$route_slug.'other_services', 'uses' => $module_controller.'update']);
			Route::get('/delete/{id?}',['as' =>$route_slug.'other_services', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',['as' =>$route_slug.'other_services', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',['as' =>$route_slug.'other_services', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',['as' =>$route_slug.'other_services', 'uses' => $module_controller.'multi_action']);
		});

		Route::group(array('prefix' => 'dashboard'), function () use($route_slug)
		{	
			$module_controller = "Admin\DashboardController@";

			Route::get('/',['as' =>$route_slug.'login', 'uses' => $module_controller.'index']);

		});

		Route::group(array('prefix' => 'site_settings'), function () use($route_slug)
		{	
			$module_controller = "Admin\SiteSettingController@";
			Route::get('/',['as' =>$route_slug.'login', 'uses' => $module_controller.'index']);
			Route::post('update_site_setting', ['as' => $route_slug.'dashboard', 'uses' => $module_controller.'update']);
		});

		Route::group(array('prefix' => 'profile'), function () use($route_slug)
		{	
			$module_controller = "Admin\ProfileController@";

			Route::get('/',['as' =>$route_slug.'profile_setting', 'uses' => $module_controller.'index']);
			Route::post('/update/{enc_id}',['as' =>$route_slug.'update', 'uses' => $module_controller.'update']);
			Route::get('/change_password',['as' =>$route_slug.'change_password', 'uses' => $module_controller.'change_password']);
			Route::post('/update_password',['as' =>$route_slug.'update_password', 'uses' => $module_controller.'update_password']);
		});

		Route::group(array('prefix' => 'categories'), function () use($route_slug)
		{
			$module_controller = "Admin\CategoryController@";

			Route::get('/',['as' =>$route_slug.'categories', 'uses' => $module_controller.'index']);
			Route::get('/create',['as' =>$route_slug.'categories', 'uses' => $module_controller.'create']);
			Route::post('/store',['as' =>$route_slug.'categories', 'uses' => $module_controller.'store']);
			Route::get('/edit/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'edit']);
			Route::post('/update/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'update']);
			Route::get('/delete/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',['as' =>$route_slug.'categories', 'uses' => $module_controller.'multi_action']);

		});

		Route::group(array('prefix' => 'amenities'), function () use($route_slug)
		{
			$module_controller = "Admin\AmenitiesController@";

			Route::get('/',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'index']);
			Route::get('/create',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'create']);
			Route::post('/store',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'store']);
			Route::get('/edit/{id?}',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'edit']);
			Route::post('/update/{id?}',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'update']);
			Route::get('/delete/{id?}',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',['as' =>$route_slug.'amenities', 'uses' => $module_controller.'multi_action']);

		});

		Route::group(array('prefix' => 'coupon'), function () use($route_slug)
		{
			$module_controller = "Admin\CouponController@";

			Route::get('/',['as' =>$route_slug.'coupon', 'uses' => $module_controller.'index']);
			Route::get('/create',['as' =>$route_slug.'coupon', 'uses' => $module_controller.'create']);
			Route::post('/store',['as' =>$route_slug.'coupon', 'uses' => $module_controller.'store']);
			Route::get('/edit/{id?}',['as' =>$route_slug.'coupon', 'uses' => $module_controller.'edit']);
			Route::get('/load_data',['as' =>$route_slug.'coupon', 'uses' => $module_controller.'load_data']);

			Route::post('/update/{id?}',['as' =>$route_slug.'coupon', 'uses' => $module_controller.'update']);
			Route::get('/delete/{id?}',['as' =>$route_slug.'coupon', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',['as' =>$route_slug.'coupon', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',['as' =>$route_slug.'coupon', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',['as' =>$route_slug.'coupon', 'uses' => $module_controller.'multi_action']);			
		});

		Route::group(array('prefix' => 'email_template'), function () use($route_slug)
		{
			
			$module_controller = "Admin\EmailTemplateController@";
			
			Route::get('/',['as' =>$route_slug.'email_template', 'uses' => $module_controller.'index']);
			Route::get('/preview/{id?}',['as' =>$route_slug.'email_template', 'uses' => $module_controller.'view']);
			Route::get('/edit/{id?}',['as' =>$route_slug.'email_template', 'uses' => $module_controller.'edit']);
			Route::post('/update/{id?}',['as' =>$route_slug.'email_template', 'uses' => $module_controller.'update']);
			Route::get('/delete/{id?}',['as' =>$route_slug.'email_template', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',['as' =>$route_slug.'email_template', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',['as' =>$route_slug.'email_template', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',['as' =>$route_slug.'email_template', 'uses' => $module_controller.'multi_action']);

		});	

		Route::group(array('prefix' => 'query_type'), function () use($route_slug)
		{
			$module_controller = "Admin\QueryTypeController@";

			Route::get('/',                             ['as' =>$route_slug.'query_type', 'uses' => $module_controller.'index']);
			Route::get('/create',                       ['as' =>$route_slug.'query_type', 'uses' => $module_controller.'create']);
			Route::post('/store',                       ['as' =>$route_slug.'query_type', 'uses' => $module_controller.'store']);
			Route::get('/edit/{id?}',                   ['as' =>$route_slug.'query_type', 'uses' => $module_controller.'edit']);
			Route::post('/update/{id?}',                ['as' =>$route_slug.'query_type', 'uses' => $module_controller.'update']);
			Route::get('/delete/{id?}',                 ['as' =>$route_slug.'query_type', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',                  ['as' =>$route_slug.'query_type', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',                ['as' =>$route_slug.'query_type', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',                ['as' =>$route_slug.'query_type', 'uses' => $module_controller.'multi_action']);
			
		});

		Route::group(array('prefix' => 'guest'), function () use($route_slug)
		{
			$module_controller = "Admin\GuestController@";
			$booking_controller = "Admin\BookingController@";

			Route::get('/',						['as' =>$route_slug.'guest', 'uses' => $module_controller.'index']);
			Route::get('/load_data',            ['as' =>$route_slug.'guest', 'uses' => $module_controller.'load_data']);
			Route::get('/view/{id?}',	        ['as' =>$route_slug.'guest', 'uses' => $module_controller.'view']);
			Route::get('/bank_details/{id?}',	['as' =>$route_slug.'guest', 'uses' => $module_controller.'bank_details']);
			Route::get('/delete/{id?}',	        ['as' =>$route_slug.'guest', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',	        ['as' =>$route_slug.'guest', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',        ['as' =>$route_slug.'guest', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',        ['as' =>$route_slug.'guest', 'uses' => $module_controller.'multi_action']);
			Route::get('/review-ratings/{id?}',	['as' =>$route_slug.'guest', 'uses' => $module_controller.'review_ratings']);
			Route::post('/check_review_action' ,['as' =>$route_slug.'guest', 'uses' => $module_controller.'check_review_action']);
			Route::get('/load_review_ratings',  ['as' =>$route_slug.'guest', 'uses' => $module_controller.'load_review_ratings']);

			Route::get('verify/{enc_id}',	    ['as' => $route_slug.'verify','uses' => $module_controller.'verify']);	
		    Route::get('unverify/{enc_id}',	    ['as' => $route_slug.'unverify','uses' => $module_controller.'unverify']);
		    Route::get('verify_mobile/{enc_id}',	    ['as' => $route_slug.'verify_mobile','uses' => $module_controller.'verify_mobile']);	
		    Route::get('unverify_mobile/{enc_id}',	    ['as' => $route_slug.'unverify_mobile','uses' => $module_controller.'unverify_mobile']);	
			
		});

		Route::group(array('prefix' => 'host'), function () use($route_slug)
		{
			$module_controller = "Admin\HostController@";
			$review_controller = "Admin\ReviewRatingController@";

			Route::get('/',						         ['as' =>$route_slug.'host',   'uses' => $module_controller.'index']);
			Route::get('/load_data',  			         ['as' =>$route_slug.'host',   'uses' => $module_controller.'load_data']);
			Route::get('/view/{id?}',			         ['as' =>$route_slug.'host',   'uses' => $module_controller.'view']);
			Route::get('/bank_details/{id?}',	         ['as' =>$route_slug.'host',   'uses' => $module_controller.'bank_details']);
			Route::get('/delete/{id?}',	        ['as' =>$route_slug.'host', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',			         ['as' =>$route_slug.'host',   'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',	             ['as' =>$route_slug.'host',   'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',		         ['as' =>$route_slug.'host',   'uses' => $module_controller.'multi_action']);
			Route::get('/review-rating/{id?}',	         ['as' =>$route_slug.'guest',  'uses' => $module_controller.'review_rating']);
			Route::get('/load_review_ratings',           ['as' =>$route_slug.'host',   'uses' => $module_controller.'load_review_ratings']);
			Route::get('/property_review_block/{id?}',   ['as' =>$route_slug.'block',  'uses' => $module_controller.'property_review_block']);
			Route::get('/property_review_unblock/{id?}', ['as' =>$route_slug.'unblock','uses' => $module_controller.'property_review_unblock']);
			Route::post('/property_multi_action',        ['as' =>$route_slug.'guest',  'uses' => $module_controller.'property_multi_action']);
		    Route::get('/review-rating/view/{id?}',	     ['as' =>$route_slug.'guest',  'uses' => $review_controller.'view']);

		    Route::get('verify/{enc_id}',	    ['as' => $route_slug.'verify','uses' => $module_controller.'verify']);	
		    Route::get('unverify/{enc_id}',	    ['as' => $route_slug.'unverify','uses' => $module_controller.'unverify']);
		    Route::get('verify_mobile/{enc_id}',	    ['as' => $route_slug.'verify_mobile','uses' => $module_controller.'verify_mobile']);	
		    Route::get('unverify_mobile/{enc_id}',	    ['as' => $route_slug.'unverify_mobile','uses' => $module_controller.'unverify_mobile']);
		});

		Route::group(array('prefix' => 'review-rating'), function () use($route_slug)
		{
			$module_controller = "Admin\ReviewRatingController@";

			Route::get('/'         ,                     ['as' =>$route_slug.'review_rating',          'uses' => $module_controller.'index']);
			Route::get('/load_data',                     ['as' =>$route_slug.'review',                 'uses' => $module_controller.'load_data']);
			Route::get('/property_review_block/{id?}',	 ['as' =>$route_slug.'block',                  'uses' => $module_controller.'property_review_block']);
			Route::get('/property_review_unblock/{id?}', ['as' =>$route_slug.'unblock',                'uses' => $module_controller.'property_review_unblock']);
			Route::post('/property_multi_action',        ['as' =>$route_slug.'guest',                  'uses' => $module_controller.'property_multi_action']);
			
			Route::get('/block/{id?}',	                 ['as' =>$route_slug.'host',                   'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',                 ['as' =>$route_slug.'host',                   'uses' => $module_controller.'unblock']);
			Route::get('/view/{id?}',	                 ['as' =>$route_slug.'guest',                  'uses' => $module_controller.'view']);
			Route::get('/load_individual_rating',        ['as' =>$route_slug.'load_individual_rating', 'uses' => $module_controller.'load_individual_rating']);
			Route::get('/delete/{id?}',	                 ['as' =>$route_slug.'delete',                 'uses' => $module_controller.'delete']);
			Route::post('/multi_action',                 ['as' =>$route_slug.'guest',                  'uses' => $module_controller.'multi_action']);
			
		});

		Route::group(array('prefix' => 'contact'), function () use($route_slug)
		{
			$module_controller = "Admin\ContactController@";

			Route::get('/',				['as' => $route_slug.'contact', 'uses' => $module_controller.'index']);
			Route::get('/view/{id?}',	['as' => $route_slug.'contact', 'uses' => $module_controller.'view']);
			Route::get('/delete/{id?}',	['as' => $route_slug.'contact', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',	['as' => $route_slug.'contact', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',['as' => $route_slug.'contact', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',['as' => $route_slug.'contact', 'uses' => $module_controller.'multi_action']);
			Route::get('/reply/{id?}',	['as' => $route_slug.'contact', 'uses' => $module_controller.'reply']);
			Route::post('/send/{id?}',	['as' => $route_slug.'contact', 'uses' => $module_controller.'send']);
		});

		Route::group(array('prefix' => 'transaction'), function () use($route_slug)
		{
			$module_controller = "Admin\TransactionController@";

			Route::get('/',				['as' => $route_slug.'transaction', 'uses' => $module_controller.'index']);
			Route::get('/load_data',    ['as' => $route_slug.'guest',       'uses' => $module_controller.'load_data']);
			Route::get('/export',       ['as' => $route_slug.'guest',       'uses' => $module_controller.'export']);
		});

		Route::group(array('prefix' => 'booking'), function () use($route_slug)
		{
			$module_controller = "Admin\BookingController@";

			Route::get('/all',       ['as' => $route_slug.'booking', 'uses' => $module_controller.'all']);
			Route::get('/load_data', ['as' => $route_slug.'booking', 'uses' => $module_controller.'load_data']);
			Route::get('/new',       ['as' => $route_slug.'booking', 'uses' => $module_controller.'newbooking']);
			Route::get('/confirmed', ['as' => $route_slug.'booking', 'uses' => $module_controller.'confirmed']);
			Route::get('/completed', ['as' => $route_slug.'booking', 'uses' => $module_controller.'completed']);
			Route::get('/cancel',    ['as' => $route_slug.'booking', 'uses' => $module_controller.'cancel']);
			Route::any('/view/{id}', ['as' => $route_slug.'booking', 'uses' => $module_controller.'view']);

			/*Route::get('/search',['as' =>$route_slug.'property', 'uses' => $module_controller.'search']);
			Route::get('/pending',['as' =>$route_slug.'property', 'uses' => $module_controller.'pending']);
			Route::get('/confirmed',['as' =>$route_slug.'property', 'uses' => $module_controller.'confirmed']);
			Route::get('activate/{enc_id}', ['as' => $route_slug.'activate','uses'=> $module_controller.'activate']);
			Route::get('deactivate/{enc_id}',['as'=> $route_slug.'deactivate','uses'=> $module_controller.'deactivate']);
			Route::get('/rejected',['as' =>$route_slug.'property', 'uses' => $module_controller.'rejected']);
			Route::get('/reject_permanant',['as' =>$route_slug.'property', 'uses' => $module_controller.'reject_permanant']);
			Route::get('change_status/{enc_property_id}/{status}',['as' =>$route_slug.'property', 'uses' => $module_controller.'change_property_status']);
			Route::any('change_status/{enc_property_id}/{status}/comment',['as' =>$route_slug.'property', 'uses' => $module_controller.'change_property_reject_status']);

			Route::get('/view/{enc_property_id}',['as'=>$route_slug.'property','uses'=>$module_controller.'view']);

			Route::get('/load_data',['as'=>$route_slug.'property','uses'=>$module_controller.'load_data']);
			
			Route::get('/delete/{id?}',['as' =>$route_slug.'property', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',['as' =>$route_slug.'property', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',['as' =>$route_slug.'property', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',['as' =>$route_slug.'property', 'uses' => $module_controller.'multi_action']);
			Route::any('/add-featured/{id?}',['as' =>$route_slug.'support_team', 'uses' => $module_controller.'add_featured']);
			Route::any('/remove-featured/{id?}',['as' =>$route_slug.'support_team', 'uses' => $module_controller.'remove_featured']);*/
		});

		Route::group(array('prefix' => 'my-earning'), function () use($route_slug)
		{
			$module_controller = "Admin\MyEarningController@";

			Route::get('/',                       ['as' => $route_slug.'earning', 'uses' => $module_controller.'index']);
			Route::get('/load_data',              ['as' => $route_slug.'earning', 'uses' => $module_controller.'load_data']);
			Route::get('/host-request',           ['as' => $route_slug.'earning', 'uses' => $module_controller.'host_request']);
			Route::get('/load_host_request_data', ['as' => $route_slug.'earning', 'uses' => $module_controller.'load_host_request_data']);
			Route::get('/paid/{id}',              ['as' => $route_slug.'earning', 'uses' => $module_controller.'paid']);
			Route::get('/export',                 ['as' => $route_slug.'export',  'uses' => $module_controller.'export']);
		});


		Route::group(array('prefix' => 'support_team'), function () use($route_slug)
		{
			$module_controller = "Admin\SupportTeamController@";

			Route::get('/',['as' =>$route_slug.'support_team', 'uses' => $module_controller.'index']);
			Route::get('/create',['as' =>$route_slug.'support_team', 'uses' => $module_controller.'create']);
			Route::post('/store',['as' =>$route_slug.'support_team', 'uses' => $module_controller.'store']);
			Route::get('/edit/{id?}',['as' =>$route_slug.'support_team', 'uses' => $module_controller.'edit']);
			Route::post('/update/{id?}',['as' =>$route_slug.'support_team', 'uses' => $module_controller.'update']);
			Route::get('/delete/{id?}',['as' =>$route_slug.'support_team', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',['as' =>$route_slug.'support_team', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',['as' =>$route_slug.'support_team', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',['as' =>$route_slug.'support_team', 'uses' => $module_controller.'multi_action']);
		});

		Route::group(array('prefix' => 'generate_ticket'), function () use($route_slug)
		{
			$module_controller = "Admin\GenerateTicketController@";
			Route::get('/',              ['as' => $route_slug.'generate_ticket', 'uses' => $module_controller.'index']);
			Route::any('/get_user_type', ['as' => $route_slug.'generate_ticket', 'uses' => $module_controller.'get_user_type']);
			Route::any('/store_ticket',  ['as' => $route_slug.'generate_ticket', 'uses' => $module_controller.'store_ticket']);
		});


		Route::group(array('prefix' => 'property'), function () use($route_slug)
		{
			$module_controller = "Admin\PropertyController@";

			Route::get('/all',['as' =>$route_slug.'property', 'uses' => $module_controller.'all']);
			Route::get('/search',['as' =>$route_slug.'property', 'uses' => $module_controller.'search']);
			Route::get('/pending',['as' =>$route_slug.'property', 'uses' => $module_controller.'pending']);
			Route::get('/confirmed',['as' =>$route_slug.'property', 'uses' => $module_controller.'confirmed']);
			Route::get('activate/{enc_id}', ['as' => $route_slug.'activate','uses'=> $module_controller.'activate']);
			Route::get('deactivate/{enc_id}',['as'=> $route_slug.'deactivate','uses'=> $module_controller.'deactivate']);
			Route::get('/rejected',['as' =>$route_slug.'property', 'uses' => $module_controller.'rejected']);
			Route::get('/reject_permanant',['as' =>$route_slug.'property', 'uses' => $module_controller.'reject_permanant']);
			Route::get('change_status/{enc_property_id}/{status}',['as' =>$route_slug.'property', 'uses' => $module_controller.'change_property_status']);
			Route::any('change_status/{enc_property_id}/{status}/comment',['as' =>$route_slug.'property', 'uses' => $module_controller.'change_property_reject_status']);

			Route::get('/view/{enc_property_id}',['as'=>$route_slug.'property','uses'=>$module_controller.'view']);

			Route::get('/load_data',['as'=>$route_slug.'property','uses'=>$module_controller.'load_data']);
			
			Route::get('/delete/{id?}',['as' =>$route_slug.'property', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',['as' =>$route_slug.'property', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',['as' =>$route_slug.'property', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',['as' =>$route_slug.'property', 'uses' => $module_controller.'multi_action']);
			Route::any('/add-featured/{id?}',['as' =>$route_slug.'support_team', 'uses' => $module_controller.'add_featured']);
			Route::any('/remove-featured/{id?}',['as' =>$route_slug.'support_team', 'uses' => $module_controller.'remove_featured']);
		});


		
		Route::group(array('prefix' => 'report'), function () use($route_slug)
		{
			$module_controller = "Admin\ReportController@";

			Route::get('/booking',                  ['as'=>$route_slug.'booking','uses'=>$module_controller.'booking']);	
			Route::get('/booking/load_booking_data',['as'=>$route_slug.'booking','uses'=>$module_controller.'load_booking_data']);	
			Route::get('/booking/export',           ['as'=>$route_slug.'booking','uses'=>$module_controller.'booking_export']);	
			Route::get('/cancellation',             ['as'=>$route_slug.'cancellation','uses'=>$module_controller.'cancellation']);	
			Route::get('/load_cancellation_data',   ['as'=>$route_slug.'cancellation','uses'=>$module_controller.'load_cancellation_data']);
			Route::get('/cancellation_export',      ['as'=>$route_slug.'cancellation','uses'=>$module_controller.'cancellation_export']);	
			
			Route::get('/ticket',                   ['as'=>$route_slug.'refund','uses'=>$module_controller.'ticket']);	
			Route::get('/ticket_statistics',        ['as'=>$route_slug.'refund','uses'=>$module_controller.'ticket_statistics']);	

			Route::get('/ticket_export',            ['as'=>$route_slug.'refund','uses'=>$module_controller.'ticket_export']);	
			Route::get('/load_ticket_data',         ['as'=>$route_slug.'ticket','uses'=>$module_controller.'load_ticket_data']);	

			Route::get('/load_register_data',       ['as'=>$route_slug.'registration','uses'=>$module_controller.'load_register_data']);
			Route::get('/export',                   ['as'=>$route_slug.'export','uses'=>$module_controller.'register_export']);
			Route::post('download',                 ['as'=> $route_slug.'report' ,'uses' => $module_controller.'download']);	
			Route::any('/{report_type}',            ['as'=> $route_slug.'report' ,'uses' => $module_controller.'index']);
			Route::get('/form/generate_report',     ['as'=> $route_slug.'report' ,'uses' => $module_controller.'reports']);
			
		});
		Route::group(array('prefix' => 'blog'), function () use($route_slug)
		{
			$module_controller = "Admin\BlogController@";

			Route::get('/', ['as' => $route_slug.'blog', 'uses' => $module_controller.'index']);
			Route::get('/create', ['as' => $route_slug.'blog', 'uses' => $module_controller.'create']);
			Route::post('/store', ['as' => $route_slug.'blog', 'uses' => $module_controller.'store']);
			Route::get('/edit/{id?}', ['as' => $route_slug.'blog', 'uses' => $module_controller.'edit']);
			Route::post('/update/{id?}', ['as' => $route_slug.'blog', 'uses' => $module_controller.'update']);
			Route::get('/delete/{id?}', ['as' => $route_slug.'blog', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}', ['as' => $route_slug.'blog', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}', ['as' => $route_slug.'blog', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action', ['as' => $route_slug.'blog', 'uses' => $module_controller.'multi_action']);
			Route::get('/view/{id?}', ['as' => $route_slug.'blog', 'uses' => $module_controller.'view']);
			Route::get('/view/block/{id?}', ['as' => $route_slug.'blog', 'uses' => $module_controller.'block_view']);
			Route::get('/view/unblock/{id?}', ['as' => $route_slug.'blog', 'uses' => $module_controller.'unblock_view']);
			Route::get('/view/delete/{id?}', ['as' => $route_slug.'blog', 'uses' => $module_controller.'delete_view']);
		});

		Route::group(array('prefix' => 'blog_category'), function () use($route_slug)
		{
			$module_controller = "Admin\BlogCategoryController@";

			Route::get('/',['as' =>$route_slug.'blog_category', 'uses' => $module_controller.'index']);
			Route::get('/create',['as' =>$route_slug.'blog_category', 'uses' => $module_controller.'create']);
			Route::post('/store',['as' =>$route_slug.'blog_category', 'uses' => $module_controller.'store']);
			Route::get('/edit/{id?}',['as' =>$route_slug.'blog_category', 'uses' => $module_controller.'edit']);
			Route::post('/update/{id?}',['as' =>$route_slug.'blog_category', 'uses' => $module_controller.'update']);
			Route::get('/delete/{id?}',['as' =>$route_slug.'blog_category', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',['as' =>$route_slug.'blog_category', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',['as' =>$route_slug.'blog_category', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',['as' =>$route_slug.'blog_category', 'uses' => $module_controller.'multi_action']);
		});


		Route::group(array('prefix' => 'front_pages'), function () use($route_slug)
		{
			$module_controller = "Admin\FrontPagesController@";

			Route::get('/',['as' =>$route_slug.'front_pages_category', 'uses' => $module_controller.'index']);
			Route::get('/create',['as' =>$route_slug.'front_pages_category', 'uses' => $module_controller.'create']);
			Route::post('/store',['as' =>$route_slug.'front_pages_category', 'uses' => $module_controller.'store']);
			Route::get('/edit/{id?}',['as' =>$route_slug.'front_pages_category', 'uses' => $module_controller.'edit']);
			Route::post('/update/{id?}',['as' =>$route_slug.'front_pages_category', 'uses' => $module_controller.'update']);
			Route::get('/delete/{id?}',['as' =>$route_slug.'front_pages_category', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',['as' =>$route_slug.'front_pages_category', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',['as' =>$route_slug.'front_pages_category', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',['as' =>$route_slug.'front_pages_category', 'uses' => $module_controller.'multi_action']);
		});

		Route::group(array('prefix' => 'notifications'), function () use($route_slug)
		{
			$module_controller = "Admin\NotificationTemplateController@";

			Route::get('/',['as' =>$route_slug.'notifications', 'uses' => $module_controller.'index']);
			Route::get('/preview',['as' =>$route_slug.'notifications', 'uses' => $module_controller.'view']);
			Route::get('/create',['as' =>$route_slug.'notifications', 'uses' => $module_controller.'create']);
			Route::post('/store',['as' =>$route_slug.'notifications', 'uses' => $module_controller.'store']);
			Route::get('/edit/{id?}',['as' =>$route_slug.'notifications', 'uses' => $module_controller.'edit']);
			Route::post('/update/{id?}',['as' =>$route_slug.'notifications', 'uses' => $module_controller.'update']);
			Route::get('/delete/{id?}',['as' =>$route_slug.'notifications', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',['as' =>$route_slug.'notifications', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',['as' =>$route_slug.'notifications', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',['as' =>$route_slug.'notifications', 'uses' => $module_controller.'multi_action']);
		});

		Route::group(array('prefix' => 'faq'), function () use($route_slug)
		{
			$module_controller = "Admin\FaqController@";
			Route::get('/',['as' =>$route_slug.'faq', 'uses' => $module_controller.'index']);
			Route::get('/create',['as' =>$route_slug.'faq', 'uses' => $module_controller.'create']);
			Route::post('/store',['as' =>$route_slug.'faq', 'uses' => $module_controller.'store']);
			Route::get('/edit/{id?}',['as' =>$route_slug.'faq', 'uses' => $module_controller.'edit']);
			Route::post('/update/{id?}',['as' =>$route_slug.'faq', 'uses' => $module_controller.'update']);
			Route::get('/delete/{id?}',['as' =>$route_slug.'faq', 'uses' => $module_controller.'delete']);
			Route::get('/block/{id?}',['as' =>$route_slug.'faq', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',['as' =>$route_slug.'faq', 'uses' => $module_controller.'unblock']);
			Route::post('/multi_action',['as' =>$route_slug.'faq', 'uses' => $module_controller.'multi_action']);
		});



		Route::group(array('prefix' => 'newsletter_subscriber'), function () use($route_slug)
		{
			$module_controller = "Admin\NewsLetterController@";
			Route::get('/',['as' =>$route_slug.'newsletter_subscriber_list', 'uses' => $module_controller.'index']);
			Route::get('/delete/{id}',['as' =>$route_slug.'newsletter_subscriber_unsubscrib', 'uses' => $module_controller.'delete']);
		});


		Route::group(array('prefix' =>'social_credentials'), function () use($route_slug)
		{	
			$module_controller = "Admin\SocialCredentialController@";
			Route::get('/',['as' =>$route_slug.'login', 'uses' => $module_controller.'index']);
			Route::post('/update/{id?}', ['as' => $route_slug.'dashboard', 'uses' => $module_controller.'update']);
		});

		Route::group(array('prefix' =>'notification'), function () use($route_slug)
		{	
			$module_controller = "Admin\NotificationController@";
			
			Route::get('/',['as' =>$route_slug.'login', 'uses' => $module_controller.'index']);
			Route::post('/get_notifications_count',['as' =>$route_slug.'login', 'uses' => $module_controller.'get_count']);
			Route::get('/delete/{id?}',['as' =>$route_slug.'login', 'uses' => $module_controller.'delete']);
		});

		Route::group(array('prefix' =>'testimonials'), function () use($route_slug)
		{	
			$module_controller = "Admin\TestimonialsController@";
			
			Route::get('/',			['as'    => $route_slug.'manage', 'uses' => $module_controller.'index']);
			Route::get('create',	['as'    => $route_slug.'create', 'uses'   => $module_controller.'create']);
			Route::any('store',		['as'  	 => $route_slug.'store', 'uses' 	 => $module_controller.'store']);
			Route::any('duplicate',	['as'  	 => $route_slug.'duplicate', 'uses' 	 => $module_controller.'duplicate']);
			Route::post('multi_action',['as' => $route_slug.'multi_action', 'uses'=> $module_controller.'multi_action']);
			Route::get('activate/{enc_id}',	['as' => $route_slug.'activate', 'uses'=> $module_controller.'activate']);
			Route::get('deactivate/{enc_id}',['as' => $route_slug.'deactivate', 'uses' => $module_controller.'deactivate']);
		   	Route::get('view/{enc_id}',	['as'	=> $route_slug.'view', 'uses'	=> $module_controller.'view']);
		   	Route::get('edit/{enc_id}',	['as'	=> $route_slug.'edit', 'uses'	=> $module_controller.'edit']);
		    Route::post('update/{enc_id}', ['as'  	=> $route_slug.'update', 'uses' 	=> $module_controller.'update']);
		    Route::get('delete/{enc_id}',  ['as' 	=> $route_slug.'delete', 'uses'	=> $module_controller.'delete']);
			Route::get('/block/{id?}',['as' =>$route_slug.'property', 'uses' => $module_controller.'block']);
			Route::get('/unblock/{id?}',['as' =>$route_slug.'property', 'uses' => $module_controller.'unblock']);
			
		});


	});	

});