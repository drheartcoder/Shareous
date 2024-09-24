<?php

$web_support_path = config('app.project.support_panel_slug');
$route_slug = 'support_';

Route::group(array('prefix' => $web_support_path), function () use($route_slug)
{
	$module_controller = "Support\AuthController@";
	Route::get('/',                ['as' => $route_slug.'login',          'uses' => $module_controller.'login']);
	Route::get('/login',           ['as' => $route_slug.'login',          'uses' => $module_controller.'login']);
	Route::post('/validate_login', ['as' => $route_slug.'validate_login', 'uses' => $module_controller.'validate_login']);
	Route::get('/logout',          ['as' => $route_slug.'validate_login', 'uses' => $module_controller.'logout']);

	$module_controller = "Support\PasswordController@";
	Route::post('/forgot_password',        ['as' => $route_slug.'forgot_password', 'uses' => $module_controller.'postEmail']);
	Route::get('/password_reset/{token?}', ['as' => $route_slug.'password_reset',  'uses' => $module_controller.'getReset']);
	Route::post('/password_reset',         ['as' => $route_slug.'password_reset',  'uses' => $module_controller.'postReset']);

	Route::post('notification/get_notifications_count', ['as' => $route_slug.'notification', 'uses' => 'Support\NotificationController@get_count']);

	Route::group(array('middleware'=>'support_auth'), function () use($route_slug)
	{
		Route::group(array('prefix' => 'dashboard'), function () use($route_slug)
		{	
			$module_controller = "Support\DashboardController@";
			Route::get('/',                         ['as' => $route_slug.'login',             'uses' => $module_controller.'index']);
			Route::get('/verification',             ['as' => $route_slug.'verification_list', 'uses' => $module_controller.'verification']);
			Route::get('/load_ticket_data',         ['as' => $route_slug.'ticket',            'uses' => $module_controller.'load_ticket_data']);
			Route::get('/assign_ticket/{enc_id?}',  ['as' => $route_slug.'ticket',            'uses' => $module_controller.'assign_ticket']);
			Route::get('/load_request_data',        ['as' => $route_slug.'request',           'uses' => $module_controller.'load_request_data']);
			Route::get('/assign_request/{enc_id?}', ['as' => $route_slug.'request',           'uses' => $module_controller.'assign_request']);
		});

		Route::group(array('prefix' => 'profile'), function () use($route_slug)
		{	
			$module_controller = "Support\ProfileController@";
			Route::get('/',                 ['as' => $route_slug.'profile_setting', 'uses' => $module_controller.'index']);
			Route::post('/update/{enc_id}', ['as' => $route_slug.'update',          'uses' => $module_controller.'update']);
			Route::get('/change_password',  ['as' => $route_slug.'change_password', 'uses' => $module_controller.'change_password']);
			Route::post('/update_password', ['as' => $route_slug.'update_password', 'uses' => $module_controller.'update_password']);
		});
		
		Route::group(array('prefix' => 'verification', 'middleware'=>'support_auth'), function () use($route_slug)
		{
			$module_controller = "Support\Verification\PendingVerificationController@";
			Route::get('/',                 ['as'   => $route_slug.'verification', 'uses' => $module_controller.'index']);
			Route::get('/load_data',        ['as'   => $route_slug.'verification', 'uses' => $module_controller.'load_data']);
			Route::get('/view/{enc_id?}',   ['view' => $route_slug.'verification', 'uses' => $module_controller.'view']);
			Route::get('/accept/{enc_id?}', ['view' => $route_slug.'verification', 'uses' => $module_controller.'accept']);
			Route::get('/reject/{enc_id?}', ['view' => $route_slug.'verification', 'uses' => $module_controller.'reject']);

			$module_controller = "Support\Verification\ApproveVerificationController@";
			Route::get('/approve',                     ['as'   => $route_slug.'verification', 'uses' => $module_controller.'index']);
			Route::get('/load_approve_data',           ['as'   => $route_slug.'verification', 'uses' => $module_controller.'load_approve_data']);
			Route::get('/view_approve_data/{enc_id?}', ['view' => $route_slug.'verification', 'uses' => $module_controller.'view_approve_data']);

			$module_controller = "Support\Verification\RejectVerificationController@";
			Route::get('/reject_request',             ['as'   => $route_slug.'verification', 'uses' => $module_controller.'index']);
			Route::get('/load_reject_data',           ['as'   => $route_slug.'verification', 'uses' => $module_controller.'load_reject_data']);
			Route::get('/view_reject_data/{enc_id?}', ['view' => $route_slug.'verification', 'uses' => $module_controller.'view_reject_data']);

		});

		Route::group(array('prefix' =>'notification'), function () use($route_slug)
		{	
			$module_controller = "Support\NotificationController@";
			Route::get('/',             ['as' => $route_slug.'notification', 'uses' => $module_controller.'index']);
			Route::get('/delete/{id?}', ['as' => $route_slug.'notification', 'uses' => $module_controller.'delete']);
		});

		Route::group(array('prefix' =>'ticket'), function () use($route_slug)
		{	
			$module_controller = "Support\Ticket\TicketController@";
			Route::get('/',                       ['as'   => $route_slug.'ticket',  'uses' => $module_controller.'index']);
			Route::get('/load_data',              ['as'   => $route_slug.'ticket',  'uses' => $module_controller.'load_data']);
			Route::get('/view/{enc_id?}',         ['view' => $route_slug.'ticket',  'uses' => $module_controller.'view']);
			Route::get('/reply/{id?}',            ['as'   => $route_slug.'contact', 'uses' => $module_controller.'reply']);
			Route::post('/send/{id?}',            ['as'   => $route_slug.'contact', 'uses' => $module_controller.'send']);
			Route::post('/reject/{id?}',          ['as'   => $route_slug.'contact', 'uses' => $module_controller.'reject']);
			Route::get('/close/{enc_id?}',        ['view' => $route_slug.'ticket',  'uses' => $module_controller.'close_ticket']);
			Route::get('/change_level/{enc_id?}', ['view' => $route_slug.'ticket',  'uses' => $module_controller.'change_level']);
			Route::any('/refund',                 ['view' => $route_slug.'ticket',  'uses' => $module_controller.'refund_process']);

			Route::post('/store_chat', ['as' => $route_slug.'store_chat', 'uses' => $module_controller.'store_chat']);
			Route::get('/get_current_chat_messages', ['as' => $route_slug.'get_current_chat_messages', 'uses' => $module_controller.'get_current_chat_messages']);
			
			$module_controller = "Support\Ticket\TicketClosedController@";
			Route::get('/closed_ticket',                ['as'   => $route_slug.'ticket', 'uses' => $module_controller.'closed_ticket']);
			Route::get('/load_closed_ticket',           ['as'   => $route_slug.'ticket', 'uses' => $module_controller.'load_closed_ticket']);
			Route::get('/view_closed_ticket/{enc_id?}', ['view' => $route_slug.'ticket', 'uses' => $module_controller.'view_closed_ticket']);

			$module_controller = "Support\Ticket\GenerateTicketController@";
			Route::get('/generate-ticket', ['as' => $route_slug.'ticket', 'uses' => $module_controller.'generate_ticket']);
			Route::any('/store_ticket',    ['as' => $route_slug.'ticket', 'uses' => $module_controller.'store_ticket']);
			Route::any('/get_user_type',   ['as' => $route_slug.'ticket', 'uses' => $module_controller.'get_user_type']);

		});

	});

}); 