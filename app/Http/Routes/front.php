<?php
$route_slug = 'front_';

Route::any('/generateInvoice',['uses' => 'Front\MyBookingController@generateInvoice']);
Route::any('/walletinvoice',['uses' => 'Front\WalletController@generateInvoice']);

Route::group(['prefix' => 'common'],function ()
{
	$module_controller = "Common\CommonAjaxController@";
	Route::any('check_username_duplication', $module_controller.'check_username_duplication');
});

Route::group(array('middleware' => 'front_general'), function () use($route_slug)
{
	$module_controller = "Front\HomeController@";
	Route::get('/',['as' =>$route_slug.'home', 'uses' => $module_controller.'index']);
	Route::get('/checklogin',['as' =>$route_slug.'home', 'uses' => $module_controller.'checklogin']);
	Route::get('/set_currency/{currency}', ['as'=> $route_slug.'set_currency', 'uses'=>$module_controller.'set_currency']);

	$module_controller = "Front\AuthController@";
	Route::get('/login',['as' =>$route_slug.'login', 'uses' => $module_controller.'login']);
	Route::post('/process_login',['as' =>$route_slug.'process_login', 'uses' => $module_controller.'process_login']);
	Route::any('/verify_otp/{enc_user_id}',['as' =>$route_slug.'verify_otp', 'uses' => $module_controller.'verify_otp']);
	Route::post('/process_verify_otp',['as' =>$route_slug.'process_verify_otp', 'uses' => $module_controller.'process_verify_otp']);
	Route::post('/resend_otp', ['as' =>$route_slug.'resend_otp', 'uses' => $module_controller.'resend_otp']);

	Route::post('fblogin',	$module_controller.'fblogin');
	Route::post('/gplogin',	$module_controller.'gplogin');	
	Route::post('/twitterCallback',	$module_controller.'twitterlogin');	



    Route::group(['prefix'=>'social_auth'],function()
	{
		Route::group(['prefix'=>'twitter'],function()
		{
			Route::get('init',function()
			{
				// For errors
				try{
					return \Socialize::with('twitter')->redirect();
				}
				catch(\Exception $e) {
					//Session::flash('error', $e->getMessage());
					Session::flash('error', "Something went wrong with twitter login. Try again or contact Admin");
					return redirect(url('/').'/login');
				}
			});
			Route::get('callback',['as' => 'social_auth_twitter_callback', 'uses' => 'Front\AuthController@twitterlogin']);
		});
	});


	Route::any('/get_aminities_arr/{property_id}',['uses' => 'Front\PropertyController@get_aminities_arr']);

	Route::get('/logout',['as' => $route_slug.'logout', 'uses' => $module_controller.'logout']);

	Route::get('/signup',['as' => $route_slug.'login', 'uses' => $module_controller.'signup']);

	Route::post('/process_signup',['as' => $route_slug.'login', 'uses' => $module_controller.'process_signup']);

	Route::get('/verify_account/{enc_id?}/{token?}',['as' => $route_slug.'login', 'uses' => $module_controller.'verify_account']);

	// Cronjob
	/*$module_controller = "Front\CronController@";
	Route::get('/review-notification', ['as' => 'review_notification','uses' => $module_controller.'review_notification']);
	Route::get('/send_booking_reminder', ['as' => 'send_booking_reminder','uses' => $module_controller.'send_booking_reminder']);
	Route::get('store_currency_conversion', ['as' => 'store_currency_conversion','uses' => $module_controller.'@store_currency_conversion']);
	Route::get('release_allocated_slots', ['as' => 'release_allocated_slots','uses' => $module_controller.'@release_allocated_slots']);*/

	Route::any('/review-notification', function () {
    	$exitCode = \Artisan::call('review-notification:schedule');
   	});
   	Route::any('/send_booking_reminder', function () {
    	$exitCode = \Artisan::call('send_booking_reminder:schedule');
   	});
   	Route::any('/store_currency_conversion', function () {
    	$exitCode = \Artisan::call('store_currency_conversion:schedule');
   	});
   	Route::any('/release_allocated_slots', function () {
    	$exitCode = \Artisan::call('release_allocated_slots:schedule');
   	});
   	/*Route::any('/cancel_unpaided_booking', function () {
    	$exitCode = \Artisan::call('cancel_unpaided_booking:schedule');
   	});*/
   	Route::any('/general_cron', function () {
    	$exitCode = \Artisan::call('general_cron:schedule');
   	});

	
	/*Front Newsletter section*/
	$module_controller = "Front\NewsLetterController@";
	Route::post('/subscribe_newsletter',['as' =>$route_slug.'subscribe', 'uses' => $module_controller.'subscribe']);

	Route::group(array('prefix' => 'contact_us'), function () use($route_slug)
	{
		$module_controller = "Front\ContactUsController@";
		Route::get('/',['as' => $route_slug.'contact', 'uses' => $module_controller.'index']);
		Route::post('/store',['as' =>$route_slug.'contact', 'uses' => $module_controller.'store']);
	});

	Route::group(array('prefix' => 'faq'), function () use($route_slug)
	{
		$module_controller = "Front\FaqController@";
		Route::get('/',['as' =>$route_slug.'faq', 'uses' => $module_controller.'index']);
		Route::get('/search',['as' =>$route_slug.'faq', 'uses' => $module_controller.'index']);
	});	


	Route::group(array('prefix' => 'profile', 'middleware'=>'user_auth'), function () use($route_slug)
	{
		$module_controller = "Front\User\ProfileController@";
		
		Route::get('/',['as' =>$route_slug.'profile', 'uses' => $module_controller.'index']);
		Route::post('/update/{id?}',['as' =>$route_slug.'profile', 'uses' => $module_controller.'update']);
		Route::get('/change_password',['as' =>$route_slug.'profile', 'uses' => $module_controller.'change_password']);
		Route::post('/update_password',['as' =>$route_slug.'update_password', 'uses' => $module_controller.'update_password']);
		Route::post('/switch_user_type',['as' =>$route_slug.'switch_user_type', 'uses' => $module_controller.'switch_user_type']);
		Route::post('/check_username',['as' =>$route_slug.'check_username', 'uses' => $module_controller.'check_username']);
		Route::post('/check_email',['as' =>$route_slug.'check_email', 'uses' => $module_controller.'check_email']);
		Route::get('/my_documents',['as' =>$route_slug.'my_documents', 'uses' => $module_controller.'my_documents']);
		Route::post('/check_mobile_number',['as' =>$route_slug.'check_mobile_number', 'uses' => $module_controller.'check_mobile_number']);
		
		Route::post('/verify_email',['as' =>$route_slug.'verify_email', 'uses' => $module_controller.'verify_email']);

		Route::post('/verify_otp',['as' =>$route_slug.'verify_otp', 'uses' => $module_controller.'verify_otp']);
		Route::post('/generate_otp',['as' => $route_slug.'generate_otp', 'uses' => $module_controller.'generate_otp']);
		Route::post('/resend_otp',['as' => $route_slug.'resend_otp', 'uses' => $module_controller.'generate_otp']);
	});

	Route::group(array('prefix' => 'bank_details', 'middleware'=>'user_auth'), function () use($route_slug)
	{
		$module_controller = "Front\BankDetailsController@";
		Route::get('/',['as' =>$route_slug.'bank_details', 'uses' => $module_controller.'index']);		
		Route::post('store',['as' =>$route_slug.'bank_details', 'uses' => $module_controller.'store']);
		Route::get('/delete/{id?}',['as' =>$route_slug.'bank_details', 'uses' => $module_controller.'delete']);
		Route::get('/get_data/{id?}',['as' =>$route_slug.'bank_details', 'uses' => $module_controller.'get_data']);
		Route::post('update',['as' =>$route_slug.'bank_details', 'uses' => $module_controller.'update']);
	});	

	Route::group(array('prefix' => 'verification', 'middleware'=>'user_auth'), function () use($route_slug)
	{
		$module_controller = "Front\VerificationController@";
		Route::post('/post_documets',['as' =>$route_slug.'post_documets', 'uses' => $module_controller.'post_documets']);		
	});

	Route::group(array('prefix' => 'notifications', 'middleware'=>'user_auth'), function () use($route_slug)
	{
		$module_controller = "Front\NotificationsController@";
		Route::get('/',['as' =>$route_slug.'notifications', 'uses' => $module_controller.'index']);	
		Route::get('/notification_type/{type}',['as' =>$route_slug.'notifications', 'uses' => $module_controller.'notification_type']);		
		Route::get('/delete/{enc_id}',['as' =>$route_slug.'notifications', 'uses' => $module_controller.'delete']);	
		Route::post('/get_notifications_count',['as' =>$route_slug.'notifications', 'uses' => $module_controller.'get_notification_count']);
	});

	$module_controller = "Front\BlogController@";
	Route::get('/blog/{category?}',['as' =>$route_slug.'blog', 'uses' => $module_controller.'index']);
	Route::get('blog/details/{id}',['as' =>$route_slug.'blog', 'uses' => $module_controller.'blog_details']);		
	Route::post('blog/details/add_review/',['as' =>$route_slug.'blog', 'uses' => $module_controller.'add_review']);		
	Route::post('blog/details/search_category/{$category}',['as' =>$route_slug.'blog', 'uses' => $module_controller.'search_category']);		

	$module_controller = "Front\BlogCategoryController@";
	Route::get('/blog_category',['as' =>$route_slug.'blog_category', 'uses' => $module_controller.'index']);

	$module_controller = "Front\PasswordController@";
	Route::get('/forgot_password',['as' =>$route_slug.'blog_category', 'uses' => $module_controller.'index']);
	Route::post('/forgot_password_email',['as' =>$route_slug.'forgot_password', 'uses' => $module_controller.'postEmail']);
	Route::get('/password_reset/{token?}',['as' =>$route_slug.'password_reset', 'uses' => $module_controller.'getResetPassword']);
	Route::post('/password_reset',['as' =>$route_slug.'password_reset', 'uses' => $module_controller.'postResetPassword']);				

	/*property routes start from here*/
	Route::group(array('prefix' => 'property'), function () use($route_slug)
	{
		$module_controller = "Front\ListingController@";
		
		/*Route::get('/',       ['as' => $route_slug.'ajax_listing', 'uses' => $module_controller.'ajax_listing']);
		Route::get('/search', ['as' => $route_slug.'ajax_search',  'uses' => $module_controller.'ajax_search']);*/

		Route::get('/',			['as' =>$route_slug.'listing', 'uses' =>$module_controller.'index']);
		Route::post('/submit-rating-review/{id}',['as' =>$route_slug.'listing', 'uses' =>$module_controller.'submit_rating_review']);
		Route::get('/view/{slug}',['as' =>$route_slug.'listing', 'uses' =>$module_controller.'view']);

		Route::get('/view/{slug}/{checkin?}/{checkout?}/{no_of_guests?}',['as' =>$route_slug.'listing', 'uses' =>$module_controller.'view']);

		Route::any('/listing', ['as' => $route_slug.'listing', 'uses' => $module_controller.'host_property_listing']);
		Route::any('/getaminities/{id}', ['as' => $route_slug.'listing', 'uses' => $module_controller.'getaminities']);

		/*booking routes*/
		$module_controller = "Front\BookingController@";	
		Route::post('book_property',['as' =>$route_slug.'listing', 'uses' =>$module_controller.'store_booking_details']);

		Route::any('/booking/calculate_rate_for_selected_dates',['as' =>$route_slug.'calculate_rate_for_selected_dates', 'uses' =>$module_controller.'calculate_rate_for_selected_dates']);
		Route::any('/booking/change_status/{status}/{enc_id}',['as' =>$route_slug.'change_status', 'uses' =>$module_controller.'change_status']);
		Route::any('/booking/reject',  ['as' =>$route_slug.'reject_booking', 'uses' =>$module_controller.'reject_booking']);
		Route::any('/booking/session_store',  ['as' =>$route_slug.'session_booking_store', 'uses' =>$module_controller.'session_booking_store']);

		Route::post('/booking/get_available_slots',  ['as' => $route_slug.'get_available_slots', 'uses' => $module_controller.'get_available_slots']);
		Route::post('/booking/get_available_office',  ['as' => $route_slug.'get_available_office', 'uses' => $module_controller.'get_available_office']);
	});

	/*property booking routes start from here*/
	Route::group(array('prefix' => 'booking','middleware'=>'user_auth'), function () use($route_slug)
	{
		$module_controller = "Front\BookingController@";	
		Route::get('/',['as' =>$route_slug.'booking', 'uses' =>'Front\BookingController@index']);
		
	});

	/*property booking routes start from here*/
	Route::group(array('prefix' => 'my-booking','middleware'=>'user_auth'), function () use($route_slug)
	{
		$module_controller = "Front\MyBookingController@";

		Route::get('/new',			['as' =>$route_slug.'new_booking', 			'uses' =>$module_controller.'new_booking']);
		Route::get('/confirmed',	['as' =>$route_slug.'confirmed_booking', 	'uses' =>$module_controller.'confirmed_booking']);
		Route::get('/completed',	['as' =>$route_slug.'completed_booking', 	'uses' =>$module_controller.'completed_booking']);
		Route::get('/cancelled',	['as' =>$route_slug.'cancelled_booking', 	'uses' =>$module_controller.'cancelled_booking']);

		Route::get('new/booking-details/{id}',      ['as' =>$route_slug.'new_booking-details',       'uses' => $module_controller.'booking_details']);
		Route::get('confirmed/booking-details/{id}',['as' =>$route_slug.'confirmed_booking-details', 'uses' => $module_controller.'booking_details']);
		Route::get('completed/booking-details/{id}',['as' =>$route_slug.'confirmed_booking-details', 'uses' => $module_controller.'booking_details']);
		Route::get('cancelled/booking-details/{id}',['as' =>$route_slug.'confirmed_booking-details', 'uses' => $module_controller.'booking_details']);	
		Route::any('/search_status',   ['as' =>$route_slug.'search_status',  'uses' =>$module_controller.'search_status']);
		Route::any('/cancel/process',  ['as' =>$route_slug.'process_cancel', 'uses' =>$module_controller.'process_cancel']);
		Route::any('/cancel/{enc_id}', ['as' =>$route_slug.'cancel_booking', 'uses' =>$module_controller.'cancel_booking']);

		Route::any('/payment', 		   ['as' =>$route_slug.'payment_store',  'uses' =>$module_controller.'payment_store']);
		Route::any('/payment/wallet',  ['as' =>$route_slug.'wallet_payment', 'uses' =>$module_controller.'wallet_payment']);
		Route::any('/new/reject',      ['as' =>$route_slug.'reject_booking', 'uses' =>$module_controller.'reject_booking']);
		Route::any('/new/accept-booking/{status}/{enc_id}',['as' =>$route_slug.'accept_booking', 'uses' => $module_controller.'accept_booking']);	
	});

	/*property routes start from here*/
	Route::group(array('prefix' => 'property','middleware'=>'user_auth'), function () use($route_slug)
	{
		$module_controller = "Front\FavouriteController@";	
		Route::get('/favourite',['as' =>$route_slug.'favourite', 'uses' =>'Front\FavouriteController@index']);
		Route::get('/favourite/view/{id}',['as' =>$route_slug.'favourite', 'uses' =>$module_controller.	'view']);
		Route::get('/check_user_login',['as' =>$route_slug.'favourite', 'uses' =>$module_controller.	'check_user_login']);
		Route::get('/favourite/delete/{id}',['as' =>$route_slug.'favourite', 'uses' =>$module_controller.'delete']);
	});

	Route::group(array('prefix' => 'property','middleware'=>'user_auth'), function () use($route_slug)
	{
		$module_controller = "Front\PropertyController@";	
		Route::post('/get_sleeping_arrangement',['as' =>$route_slug.'get_sleeping_arrangement', 'uses' =>$module_controller.'get_sleeping_arrangement']);
		Route::any('create_step1',['as' =>$route_slug.'post', 'uses' => $module_controller.'create_property_step1']);
		Route::get('create_step2/{enc_property_id}',['as' =>$route_slug.'post', 'uses' => $module_controller.'create_property_step2']);
		Route::get('get_aminities/{category_id}',['as' =>$route_slug.'aminities', 'uses' => $module_controller.'get_aminities']);
		Route::post('store_step1',['as' =>$route_slug.'store_step1', 'uses' => $module_controller.'store_step1']);
		Route::post('store_step2',['as' =>$route_slug.'store_step1', 'uses' => $module_controller.'store_step2']);
		Route::any('/favourite/{property_id}',['as' =>$route_slug.'favourite', 'uses' => $module_controller.'favourite']);		


		/*routes related to rules*/
		Route::post('add_rules',['as' =>$route_slug.'add_rules', 'uses' => $module_controller.'add_rules']);
		Route::post('update_rules',['as' =>$route_slug.'add_rules', 'uses' => $module_controller.'update_rules']);
		Route::get('delete_rules/{enc_rule_id}',['as' =>$route_slug.'add_rules', 'uses' => $module_controller.'delete_rules']);
		Route::get('edit_rules/{enc_rule_id}',['as' =>$route_slug.'add_rules', 'uses' => $module_controller.'edit_rules']);

		/*update property routes*/
		Route::get('edit_step1/{enc_property_id}',['as' =>$route_slug.'edit', 'uses' => $module_controller.'edit_property_step1']);
		Route::get('edit_step2/{enc_property_id}',['as' =>$route_slug.'edit', 'uses' => $module_controller.'edit_property_step2']);
		Route::post('update_step1',['as' =>$route_slug.'update', 'uses' => $module_controller.'update_property_step1']);
		Route::post('update_step2',['as' =>$route_slug.'update', 'uses' => $module_controller.'update_property_step2']);
		Route::post('upload_property_image',['as' =>$route_slug.'update', 'uses' => $module_controller.'upload_property_image']);
		Route::post('remove_property_image',['as' =>$route_slug.'remove_property_image', 'uses' => $module_controller.'remove_property_image']);
		
		Route::get('delete_property_image/{enc_property_id}',['as' =>$route_slug.'edit', 'uses' => $module_controller.'delete_property_image']);
		Route::get('delete_unavailbility/{enc_rule_id}',['as' =>$route_slug.'add_rules', 'uses' => $module_controller.'delete_unavailbility']);

		/*booking routes*/
		$module_controller = "Front\BookingController@";	
		Route::post('book_property',['as' =>$route_slug.'listing', 'uses' =>$module_controller.'store_booking_details']);		

		$module_controller = "Front\ListingController@";
		Route::get('/listing',	['as' =>$route_slug.'listing', 'uses' => $module_controller.'host_property_listing']);
		Route::get('/delete/{enc_id}',	['as' =>$route_slug.'delete', 'uses' => $module_controller.'delete']);
	});	

	
	Route::group(array('prefix' => 'ticket','middleware'=>'user_auth'), function () use($route_slug)
	{
		$module_controller = "Front\TicketController@";
		Route::get('/',              ['as' => $route_slug.'ticket',  'uses' => $module_controller.'index']);        	
		Route::post('/ticket_store', ['as' => $route_slug.'profile', 'uses' => $module_controller.'ticket_store']);
	});

	Route::group(array('prefix' => 'ticket-listing','middleware'=>'user_auth'), function () use($route_slug)
	{
		$module_controller = "Front\TicketListingController@";
		Route::any('/',                     ['as' => $route_slug.'ticket',          'uses' => $module_controller.'index']);
		Route::get('/ticket-details/{id}',  ['as' => $route_slug.'ticket_details',  'uses' => $module_controller.'ticket_details']);
		Route::get('/ticket-download/{id}', ['as' => $route_slug.'ticket_download', 'uses' => $module_controller.'ticket_download']);
		
		Route::post('/store_chat', ['as' => $route_slug.'store_chat', 'uses' => $module_controller.'store_chat']);
		Route::get('/get_current_chat_messages', ['as' => $route_slug.'get_current_chat_messages', 'uses' => $module_controller.'get_current_chat_messages']);
	});


	Route::group(array('prefix' => 'query','middleware'=>'user_auth'), function () use($route_slug)
	{
		$module_controller      = 'Front\QueryController@';
		Route::get('/',['as' => $route_slug.'ticket', 'uses' => $module_controller.'index']);	
		Route::get('/view/{id}',['as' =>$route_slug.'ticket', 'uses' => $module_controller.'view']);
		Route::post('/store_query',['as' =>$route_slug.'ticket', 'uses' => $module_controller.'store_query']);
		
	});

	Route::group(array('prefix' => 'review-rating', 'middleware'=>'user_auth'), function () use($route_slug)
	{
		$module_controller = "Front\ReviewRatingController@";
		Route::get('/',['as' =>$route_slug.'review', 'uses' => $module_controller.'index']);	
		Route::get('/store',['as' =>$route_slug.'review', 'uses' => $module_controller.'store']);
		
	});

	Route::group(array('prefix' => 'property-review-rating', 'middleware'=>'user_auth'), function () use($route_slug)
	{
		$module_controller = "Front\ReviewRatingController@";
		Route::get('/',['as' =>$route_slug.'review', 'uses' => $module_controller.'host_review_rating']);	
	});

	Route::group(array('prefix' => 'wallet', 'middleware'=>'user_auth'), function () use($route_slug)
	{
		$module_controller = "Front\WalletController@";
		Route::get('/',				 ['as' =>$route_slug.'wallet',        'uses' => $module_controller.'index']);
		Route::post('/payment/store',['as' =>$route_slug.'payment_store', 'uses' => $module_controller.'payment_store']);
	});

	Route::group(array('prefix' => 'transactions', 'middleware'=>'user_auth'), function () use($route_slug)
	{
		$module_controller = "Front\TransactionController@";
		Route::get('/',				['as' =>$route_slug.'transactions', 'uses' => $module_controller.'index']);
		Route::get('/search',		['as' =>$route_slug.'search', 'uses' => $module_controller.'index']);
		Route::get('/export',		['as' =>$route_slug.'export', 'uses' => $module_controller.'export']);
		Route::get('/transaction-details/{id}',		['as' =>$route_slug.'export', 'uses' => $module_controller.'transaction_details']);

	});

	Route::group(array('prefix' => 'my-earning', 'middleware'=>'user_auth'), function () use($route_slug)
	{
		$module_controller = "Front\MyEarningController@";
		Route::get('/',				['as' =>$route_slug.'transactions', 'uses' => $module_controller.'index']);
		Route::get('/search',		['as' =>$route_slug.'search', 'uses' => $module_controller.'index']);
		Route::get('/export',		['as' =>$route_slug.'export', 'uses' => $module_controller.'export']);
		Route::get('/request_to_admin',		['as' =>$route_slug.'request_to_admin', 'uses' => $module_controller.'request_to_admin']);
		Route::get('/transaction-details/{id}',		['as' =>$route_slug.'export', 'uses' => $module_controller.'transaction_details']);

	});

	/*Route::group(array('prefix' => 'my-request', 'middleware'=>'user_auth'), function () use($route_slug)
	{
		$module_controller = "Front\MyRequestController@";
		Route::get('/',				['as' =>$route_slug.'request', 'uses' => $module_controller.'index']);

	});*/
	
	$module_controller = "Front\FrontPagesController@";
	Route::get('/{slug}',['as' =>$route_slug.'front_pages', 'uses' => $module_controller.'index']);

});

