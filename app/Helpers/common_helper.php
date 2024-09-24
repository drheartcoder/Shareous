<?php 
use App\Models\UserModel;
use App\Models\AdminModel;
use App\Models\SupportTeamModel;
use App\Models\PropertyModel;
use App\Models\CurrencyModel;

	function _generate_password()
    {
    	$randomString   = '';
	    $final_password = '';

	    $alphabet_caps  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $alphabet_small = 'abcdefghijklmnopqrstuvwxyz';
	    $digits         = '1234567890';
	    $special_chars  = '!@#$%^&*()';

	    $shuffled_caps = str_shuffle($alphabet_caps);
	    $shuffled_small = str_shuffle($alphabet_small);
	    $shuffled_digits = str_shuffle($digits);    
	    $shuffled_special_chars = str_shuffle($special_chars);    

	    for ($i = 0; $i < 2; $i++) {
	        $randomString .= $shuffled_caps[rand(0,25)];
	    }

	    for ($i = 0; $i < 2; $i++) {
	        $randomString .= $shuffled_small[rand(0,25)];
	    }

	    for ($i = 0; $i < 2; $i++) {
	        $randomString .= $shuffled_digits[rand(0,9)];
	    }

	    for ($i = 0; $i < 2; $i++) {
	        $randomString .= $shuffled_special_chars[rand(0,9)];
	    }
	    
	    $final_password = str_shuffle($randomString);
	    return $final_password;
    }

    function get_added_on_date($created_date)
    {
    	$date ='';
    	if ($created_date!="" || $created_date!="0000-00-00 00:00:00") {
    		$date = date('d-M-Y',strtotime($created_date));	
    	}
    	return $date;
    }

    function get_added_on_date_time($created_date)
    {
    	$date ='';
    	if ($created_date!="" || $created_date!="0000-00-00 00:00:00") {
    		$date = date('d-M-Y h:i A',strtotime($created_date));	
    	}
    	return $date;
    }

    function validate_login($type)
	{
		$auth = auth()->guard($type); 
		$user_auth = false;
		if ($auth->check()) {
			$user_auth = $auth->check();
		}
		
		return $user_auth;
	}

	function get_request_id()
    {
    	$secure = TRUE;
    	$bytes  = openssl_random_pseudo_bytes(8, $secure);
    	$token  = "S_".bin2hex($bytes);
    	return $token;
    }

    /*Get notification type of user*/
    function get_notification_type_of_user($id)
    {
	    $type = [];
    	if ($id!='') {
	    	$obj_user = UserModel::where('id',$id)->first(['id','notification_type','notification_by_email','notification_by_sms','notification_by_push']);
	    	if (isset($obj_user) && $obj_user!=null) {
	    		$type['notification_by_email'] = $obj_user['notification_by_email'];
	    		$type['notification_by_sms']   = $obj_user['notification_by_sms'];
	    		$type['notification_by_push']  = $obj_user['notification_by_push'];
	    	}    		
    		return $type;
    	}
    	return false;
    }

    function get_formated_time($date=false)
	{
		$format = 'h:i a';
		if ($date!=false) {
			return date($format,strtotime($date));
		}
		return '-';
	}

	function getUserIP()
	{
	    $client  = @$_SERVER['HTTP_CLIENT_IP'];
	    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	    $remote  = $_SERVER['REMOTE_ADDR'];

	    if(filter_var($client, FILTER_VALIDATE_IP)) {
	        $ip = $client;
	    } elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
	        $ip = $forward;
	    } else {
	        $ip = $remote;
	    }

	    return $ip;
	}


	/*
    | Function  : get profile image according to user type 
    | Author    : Deepak Arvind Salunke
    | Date      : 25/04/2018
    | Output    : return profile image path
    */

	function get_profile_image($model_name, $user_id, $public_path, $base_path)
	{
		if($model_name != '' && $user_id != '' && $public_path != '' && $base_path != '' ) {
			if($model_name == 'AdminModel') {
				$obj_user = AdminModel::where('id',$user_id)->first();
			} else if($model_name == 'SupportTeamModel') {
				$obj_user = SupportTeamModel::where('id',$user_id)->first();
			} else if($model_name == 'UserModel') {
				$obj_user = UserModel::where('id',$user_id)->first();
			}

	    	if($obj_user) {
	    		$arr_user = $obj_user->toArray();
	    		$user_profile_image = $arr_user['profile_image'];

	    		if (file_exists($base_path.$user_profile_image) && $arr_user['profile_image']!='') {
                	$profile_image = $public_path.$user_profile_image;
	    		} else {
	    			$profile_image = url('/uploads').'/default-profile.png';
	    		}
	    	} else {
				$profile_image = url('/uploads').'/default-profile.png';
			}
		}
		else
		{
			$profile_image = url('/uploads').'/default-profile.png';
		}

		return $profile_image;

	} // end get_profile_image
