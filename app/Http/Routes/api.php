<?php
$api_path = 'api';

Route::group(array('prefix' => $api_path), function () use($route_slug)
{
	$route_slug			= "user_";
	$module_controller	= "Api\AuthController@";

	Route::post('login', 			   [ 'as'		=>  $route_slug.'login',  	      
									    'uses'  	=>  $module_controller.'login']);
	
	Route::post('registration', 	   [ 'as'		=>  $route_slug.'registration',    
									    'uses'  	=>  $module_controller.'registration']); 

	Route::post('/verify_otp', 		   [ 'as'		=>  $route_slug.'verify_otp',      
									    'uses'  	=>  $module_controller.'verify_otp']);	

	Route::any('/resend_otp', 		   [ 'as'		=>  $route_slug.'resend_otp',      
									    'uses'  	=>  $module_controller.'resend_otp']);

	/*Route::post('/forgot_password',    [ 'as'  		=>  $route_slug.'forgot_password', 
									     'uses'  	=>  $module_controller.'forgot_password']);	*/
	
	Route::get('/terms_and_condition', [ 'as'   	=>  $route_slug.'terms_and_condition', 
									 	'uses'  	=>  $module_controller.'terms_and_condition']);	
	
	Route::get('/refund_policy',       [ 'as'   	=>  $route_slug.'refund_policy', 
									 	'uses'  	=>  $module_controller.'refund_policy']);

	Route::post('/fblogin', 		   [ 'as'   	=>  $route_slug.'fblogin', 
									 	'uses'  	=>  $module_controller.'fblogin']);

	Route::post('/gplogin', 		   [ 'as'   	=>  $route_slug.'gplogin', 
									 	'uses'  	=>  $module_controller.'gplogin']);		

	Route::post('/twitterlogin', 	   ['as'   	    =>  $route_slug.'twitterlogin', 
								 		'uses'  	=>  $module_controller.'twitterlogin'
								 		]);

	$route_slug			= "user_";
	$module_controller  = "Api\PasswordController@";

	Route::post('/forgot_password',    [ 'as'  		=>  $route_slug.'forgot_password',
									     'uses'  	=>  $module_controller.'postEmail']);
								
	$route_slug			= "user_";
	$module_controller  = "Api\CommonController@";

	Route::get('generate_token',        		[ 'as'	=> $route_slug.'generate_token',       
												'uses'  => $module_controller.'generate_token']);

	Route::get('auth_token', 	        		[ 'as'	=> $route_slug.'auth_token',           
												'uses'  => $module_controller.'auth_token', 'middleware' => 'api_authenticate']);

	Route::post('profile', 		        		[ 'as'	=> $route_slug.'profile',              
												'uses'  => $module_controller.'profile']);

	Route::post('update-profile',       		[ 'as'	=> $route_slug.'update_profile',       
												'uses'  => $module_controller.'update_profile']);

	Route::post('change_password',      		[ 'as'	=> $route_slug.'change_password',      
												'uses'  => $module_controller.'change_password']);

	Route::post('update_notification_setting',	[ 'as'	=> $route_slug.'update_notification_setting',      
												'uses'  => $module_controller.'update_notification_setting']);

	Route::get('notification-listing',  		[ 'as'	=> $route_slug.'notification_listing', 
												'uses'  => $module_controller.'notification_listing']);

	Route::any('delete_notification',   		[ 'as'	=> $route_slug.'delete_notification',  
												'uses'  => $module_controller.'delete_notification']);

	Route::get('my-query',              		[ 'as'	=> $route_slug.'my_query',             
												'uses'  => $module_controller.'my_query']);

	Route::get('view_my_query',         		[ 'as'	=> $route_slug.'view_my_query',           
												'uses'  => $module_controller.'view_my_query']);

	Route::get('home_property_listing', 		[ 'as'	=> $route_slug.'home_property_listing',           
												'uses'  => $module_controller.'home_property_listing']);

	Route::any('property_listing',				[ 'as'	=> $route_slug.'property_listing',           
												'uses'  => $module_controller.'property_listing']);

	Route::get('get_property_type', 			[ 'as'	=> $route_slug.'get_property_type',           
												'uses'  => $module_controller.'get_property_type']);

	Route::get('get_all_property_type', 		[ 'as'	=> $route_slug.'get_all_property_type',           
												'uses'  => $module_controller.'get_all_property_type']);

	Route::get('get_amenities', 				[ 'as'	=> $route_slug.'get_amenities',           
												'uses'  => $module_controller.'get_amenities']);

	Route::get('home_listing', 					[ 'as'	=> $route_slug.'home_listing',           
												'uses'  => $module_controller.'home_listing']);

	Route::get('view_all_listing', 				[ 'as' 	=> $route_slug.'view_all_listing',           
												'uses' 	=> $module_controller.'view_all_listing']);

	Route::get('read_notification', 			[ 'as' 	=> $route_slug.'read_notification',           
												'uses' 	=> $module_controller.'read_notification']);

	Route::get('property-details',      		[ 'as' 	=> $route_slug.'listing',     
												'uses' 	=> $module_controller.'property_details']);

	Route::get('booking-details',      			[ 'as' 	=> $route_slug.'booking_details',
												'uses' 	=> $module_controller.'booking_details']);

	Route::get('get_currency_list',     		[ 'as' 	=> $route_slug.'currency_listing',     
												'uses' 	=> $module_controller.'get_currency_list']);


	Route::post('/store_chat', 					[ 'as' 	=> $route_slug.'store_chat',
												'uses' 	=> $module_controller.'store_chat']);
	
	Route::get('/get_current_chat_messages',	[ 'as' 	=> $route_slug.'get_current_chat_messages',
												'uses' 	=> $module_controller.'get_current_chat_messages']);


	/*=================================== Strat Guest ============================================*/
	$route_slug			= "guest_";
	$module_controller  = "Api\GuestController@";

	Route::any('become_host',   	    [ 'as'	=> $route_slug.'become_host', 
										 'uses'	=> $module_controller.'become_host']);

	Route::any('get_host_process',   	[ 'as'	=> $route_slug.'get_host_process', 
										 'uses'	=> $module_controller.'get_host_process']);

	Route::get('my-favourite-listing',  [ 'as'	=> $route_slug.'listing',     
										 'uses'	=> $module_controller.'my_favourite_listing']);

	Route::any('add_to_favourite',  	[ 'as'	=> $route_slug.'add',     
										 'uses'	=> $module_controller.'add_to_favourite']);
	
	Route::get('ticket_listing',        [ 'as'	=> $route_slug.'ticket_listing', 
										 'uses'	=> $module_controller.'ticket_listing']);

	Route::get('my_review_ratings',     [ 'as'	=> $route_slug.'my_review_ratings', 
										 'uses'	=> $module_controller.'my_review_ratings']);

	Route::get('view_review',           [ 'as'	=> $route_slug.'view_review', 
										 'uses'	=> $module_controller.'view_review']);

	Route::get('transaction_listing',   [ 'as'	=> $route_slug.'transaction', 
										 'uses'	=> $module_controller.'transaction_listing']);

	Route::get('transaction_details',   [ 'as'	=> $route_slug.'transaction', 
										 'uses'	=> $module_controller.'transaction_details']);

	Route::get('get_query_type',        [ 'as'	=> $route_slug.'get_query_type', 
										 'uses'	=> $module_controller.'get_query_type']);

	Route::post('generate_ticket',      [ 'as'	=> $route_slug.'generate_ticket', 
										 'uses'	=> $module_controller.'generate_ticket']);

	Route::post('add_money', 			[ 'as'	=> $route_slug.'add_money',           
										'uses'	=> $module_controller.'add_money']);

	Route::get('wallet_balance', 		[ 'as'	=> $route_slug.'wallet_balance',
										'uses'	=> $module_controller.'wallet_balance']);


	/*=================================Start Host============================================*/
	$route_slug			= "host_";
	$module_controller  = "Api\HostController@";


	Route::get('get_sleeping_arrangement', 	[ 'as'	=> 	$route_slug.'get_sleeping_arrangement',
										    'uses'  =>  $module_controller.'get_sleeping_arrangement']);

	Route::get('create_property_step1',		[ 'as'	=> 	$route_slug.'create_property_step1',
											'uses'  => 	$module_controller.'create_property_step1']);

	Route::get('create_property_step1',		[ 'as'	=> 	$route_slug.'create_property_step1',
											'uses'  => 	$module_controller.'create_property_step1']);

	Route::post('store_property_step1',		[ 'as'	=> 	$route_slug.'store_property_step1',
											'uses'  => 	$module_controller.'store_property_step1']);

	Route::get('get_aminities',				[ 'as'	=> 	$route_slug.'get_aminities',
											'uses'  => 	$module_controller.'get_aminities']);

	Route::post('add_rules',				[ 'as'	=> 	$route_slug.'add_rules',
										  	'uses'  => 	$module_controller.'add_rules']);

	Route::post('store_property_step2',		[ 'as'	=> 	$route_slug.'store_property_step2',
											'uses'  => 	$module_controller.'store_property_step2']);

	Route::get('edit_rules',				[ 'as'	=> 	$route_slug.'edit_rules',  
											'uses'  => 	$module_controller.'edit_rules']);

	Route::post('update_rules',				[ 'as'	=> 	$route_slug.'update_rules',
											'uses'  => 	$module_controller.'update_rules']);

	Route::any('delete_rules',				[ 'as'	=> 	$route_slug.'delete_rules',
											'uses'  => 	$module_controller.'delete_rules']);

	Route::get('edit_property_step1',		[ 'as'	=> 	$route_slug.'edit_property_step1', 
											'uses'  => 	$module_controller.'edit_property_step1']);

	Route::get('edit_property_step2',		[ 'as'	=> 	$route_slug.'edit_property_step2', 
											'uses'  => 	$module_controller.'edit_property_step2']);

	Route::post('update_property_step1',	[ 'as'	=> 	$route_slug.'update_property_step1', 
										    'uses'  => 	$module_controller.'update_property_step1']);

	Route::post('update_property_step2',	[ 'as'	=> 	$route_slug.'update_property_step2', 
										    'uses'  => 	$module_controller.'update_property_step2']);

	Route::get('delete_unavailbility',		[ 'as'	=> 	$route_slug.'delete_unavailbility', 
											'uses'  => 	$module_controller.'delete_unavailbility']);

	Route::get('delete_property_image',		[ 'as'	=> 	$route_slug.'delete_property_image', 
											'uses'  => 	$module_controller.'delete_property_image']);

	Route::get('my_property_listing',		[ 'as'	=> 	$route_slug.'my_property_listing', 
											'uses'  => 	$module_controller.'my_property_listing']);

	Route::get('delete_property',			[ 'as'	=> 	$route_slug.'delete_property', 
											'uses'  => 	$module_controller.'delete_property']);


	Route::post('add_bank_account',			[ 'as'	=>  $route_slug.'add_bank_account',
										   'uses'  	=>  $module_controller.'add_bank_account']);

	Route::get('bank_account_list',			[ 'as'	=>  $route_slug.'bank_account_list',
										   'uses'  	=>  $module_controller.'bank_account_list']);

	Route::any('update_bank_account',		[ 'as'	=>  $route_slug.'update_bank_account',
										   'uses'  	=>  $module_controller.'update_bank_account']);

	Route::get('delete_bank_account',		[ 'as'	=>  $route_slug.'delete_bank_account',
										   'uses'  	=>  $module_controller.'delete_bank_account']);
	
	Route::get('my_earning_listing', 		[ 'as'	=>  $route_slug.'my_earning_listing',     
										   'uses'  	=>  $module_controller.'my_earning_listing']);

	Route::get('my_documents',        		[ 'as'	=>  $route_slug.'my_documents',     
										     'uses' =>  $module_controller.'my_documents']);


/*=================================Start Booking============================================*/
	$route_slug			= "booking_";
	$module_controller  = "Api\BookingController@";


	Route::get('get_login_user_details',    [ 'as'	 =>  $route_slug.'get_login_user_details', 
										     'uses'  =>  $module_controller.'get_login_user_details']);

	Route::get('calculate_dates_rate',    	[ 'as'	 =>  $route_slug.'calculate_dates_rate', 
										     'uses'  =>  $module_controller.'calculate_dates_rate']);

	Route::any('get_available_slots',    	[ 'as'	 =>  $route_slug.'get_available_slots', 
										     'uses'  =>  $module_controller.'get_available_slots']);

	Route::any('get_available_office',    	[ 'as'	 =>  $route_slug.'get_available_office', 
										     'uses'  =>  $module_controller.'get_available_office']);

	Route::post('store_booking_details',    [ 'as'	 =>  $route_slug.'store_booking_details', 
										     'uses'  =>  $module_controller.'store_booking_details']);

	Route::post('submit_review',    		[ 'as'	 =>  $route_slug.'submit_review', 
										     'uses'  =>  $module_controller.'submit_review']);

	Route::get('new_booking',    			[ 'as'	 =>  $route_slug.'new_booking', 
										     'uses'  =>  $module_controller.'new_booking']);

	Route::get('booking_details',    		[ 'as'	 =>  $route_slug.'booking_details', 
										     'uses'  =>  $module_controller.'booking_details']);

	Route::get('confirmed_booking',    		[ 'as'	 =>  $route_slug.'confirmed_booking', 
										     'uses'  =>  $module_controller.'confirmed_booking']);

	Route::get('completed_booking',    		[ 'as'	 =>  $route_slug.'completed_booking', 
										     'uses'  =>  $module_controller.'completed_booking']);

	Route::get('cancelled_booking',    		[ 'as'	 =>  $route_slug.'cancelled_booking', 
										     'uses'  =>  $module_controller.'cancelled_booking']);

	Route::post('accept_booking',    		[ 'as'	 =>  $route_slug.'accept_booking', 
										     'uses'  =>  $module_controller.'accept_booking']);

	Route::any('cancel_booking',    		[ 'as'	 =>  $route_slug.'cancel_booking', 
										     'uses'  =>  $module_controller.'cancel_booking']);
	
	Route::post('pay_booking', 				['as'	 => $route_slug.'pay_booking',
											 'uses'  => $module_controller.'pay_booking']);
	
});

