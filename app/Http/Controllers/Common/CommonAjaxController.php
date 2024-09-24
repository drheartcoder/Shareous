<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\AdminModel;
use App\Common\Services\EmailService;
use App\Common\Services\NotificationService;

use Crypt;
use Session;
use Validator;

class CommonAjaxController extends Controller
{
	public function __construct(
									UserModel           $user_model,
									AdminModel          $admin_model,
									EmailService        $email_service,
									NotificationService $notification_service
								)
	{
		$this->array_view_data     = [];
		$this->UserModel           = $user_model;
		$this->AdminModel          = $admin_model;
		$this->EmailService        = $email_service;
		$this->NotificationService = $notification_service;
	}

	public function check_username_duplication(Request $request)
	{
		$arr_response = [];
		$userid       = $request->input('userid');
		$username     = $request->input('username');

		if($username != '')
		{
			if($userid != '')
			{
				$user_data = $this->UserModel->where('user_name',$username)->where('id',$userid)->get();
			}
			else
			{
				$user_data = $this->UserModel->where('user_name',$username)->get();
			}

			if( count($user_data) > 0 )
			{
				$arr_response['status'] = 'exist';
				$arr_response['msg']    = 'Username already exist, Please try another username.';
			}
			else
			{
				$arr_response['status'] = 'allow';
				$arr_response['msg']    = 'Username Available.';
			}
		}
		else
		{
			$arr_response['status'] = 'error';
			$arr_response['msg']    = 'This field is required.';
		}
		return json_encode($arr_response);
	}
}

