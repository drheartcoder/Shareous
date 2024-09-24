<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\AdminModel;
use App\Models\MobileCountryCodeModel;
use App\Common\Services\EmailService;
use App\Common\Services\SMSService;
use App\Common\Services\NotificationService;
use App\Common\Services\MailchimpService;

use Crypt;
use Session;
use Validator;
use Socialize;

class AuthController extends Controller
{
	public function __construct(
									UserModel              $user_model,
									AdminModel             $admin_model,
									EmailService           $email_service,
									SMSService             $sms_service,
									MobileCountryCodeModel $mobile_country_code_model,
									MailchimpService       $mailchimp_service,
									NotificationService    $notification_service
								)
	{
		$this->array_view_data        = [];
		$this->module_title           = 'Login';
		$this->module_view_folder     = 'front.auth.';
		$this->UserModel              = $user_model;
		$this->AdminModel             = $admin_model;
		$this->EmailService           = $email_service;
		$this->MobileCountryCodeModel = $mobile_country_code_model;
		$this->SMSService             = $sms_service;
		$this->MailchimpService       = $mailchimp_service;
		$this->auth                   = auth()->guard('users');
		$this->NotificationService    = $notification_service;
		$this->gp_logout_url          = "https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=";
	}

	public function login()
	{
		if($this->auth->user())
		{
			return redirect('/profile');
		}

		$this->array_view_data['page_title'] = $this->module_title;
		return view($this->module_view_folder.'login', $this->array_view_data);
	}

	public function process_login(Request $request)
	{
		$arr_rules = array();
		$status    = false;

		$arr_rules['user_name']       = "required";
		$arr_rules['password']        = "required";
		$arr_rules['hiddenRecaptcha'] = "required";

		$validator = validator::make($request->all(),$arr_rules);
		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}

		$txt_user_name = $request->input('user_name');
		if (filter_var($txt_user_name, FILTER_VALIDATE_EMAIL)) {
			$login_field = 'email';
		} else {
			$login_field = 'user_name';
		}
		
		$is_blocked = $this->UserModel->where(function ($query) use ($txt_user_name) {
							                if (filter_var($txt_user_name, FILTER_VALIDATE_EMAIL)) {
							                    $query->where('email', '=', $txt_user_name);
							                } else {
							                    $query->where('user_name', '=', $txt_user_name);
							                }
							            })
									  ->where('status', '0')
									  ->count()>0;
		
		if($is_blocked) {
			Session::flash('error', 'Your account is blocked by admin, Please contact to admin.');
			return redirect()->back(); 
		}

		$obj_group_user = $this->UserModel->where(function ($query) use ($txt_user_name) {
							                if (filter_var($txt_user_name, FILTER_VALIDATE_EMAIL)) {
							                    $query->where('email', '=', $txt_user_name);
							                } else {
							                    $query->where('user_name', '=', $txt_user_name);
							                }
							            })
										->first();
		
		if($obj_group_user != null && $obj_group_user->is_mobile_verified != '1') {
			Session::flash('error', 'Please verify your mobile number first');
			return redirect('verify_otp/'.base64_encode($obj_group_user->id));
		}

		if($obj_group_user != null && $obj_group_user->is_email_verified != '1') {
			Session::flash('error', 'Your email is not verified');
			return redirect()->back(); 
		}

		if($obj_group_user) {
			$arr_auth_credentials = [
										$login_field => $request->input('user_name'),
										'password'   => $request->input('password')
									];
			if($this->auth->attempt($arr_auth_credentials)) {
				$remember_me = $request->input('remember_me');

				if($remember_me!= 'on' || $remember_me == null) {
					setcookie("remember_me_email","");
					// setcookie("remember_me_password","");
				} else {
					setcookie('remember_me_user_name',$request->input('user_name'), time()+60*60*24*100);
					// setcookie('remember_me_password',$request->input('password'), time()+60*60*24*100);
				}

				// set user type in session
				Session::put('user_type', '1');
				$notification_url = Session::get('notification_url');

				if($notification_url != null && !empty($notification_url)) {
					if ($notification_url == 'home') {
						return redirect(url('/'));
					}

					Session::put('notification_url','');
					return redirect(url('/').$notification_url);
				} else {
					return redirect('/');
				}
			} else {
				Session::flash('error', 'Invalid login credential.');
			}
		} else {
			Session::flash('error','Invalid login credentials.');
		}
		return redirect()->back();
	} 

	public function verify_otp(Request $request, $enc_user_id)
	{
		$arr_mobile_country_code = [];
		
		if ($request->input('mobile_number')) {
			$country_code  = $request->input('country_code');
			$mobile_number = $request->input('mobile_number');

			$mobile_string          = '0123456';
	        $mobile_string_shuffled = str_shuffle($mobile_string);
	        $mobile_otp             = substr($mobile_string_shuffled, 1, 4);

	        $obj_existing = $this->UserModel->where('mobile_number',trim($mobile_number))->count();
	        if($obj_existing>0)
	        {	
	        	Session::flash('error', 'Mobile number already exist,Please use another mobile number.');
	    		return redirect()->back();    	
	        }

			$arr_user_otp['is_mobile_verified']      = '0';
			$arr_user_otp['country_code']            = $country_code;
			$arr_user_otp['mobile_number']           = $mobile_number;
			$arr_user_otp['mobile_otp_expired_time'] = date("Y-m-d H:i:s", strtotime('+30 minutes'));
			$arr_user_otp['mobile_otp']              = $mobile_otp;
			$this->UserModel->where('id', base64_decode($enc_user_id))->update($arr_user_otp);

			$arr_sms_data = [];
			$arr_sms_data['msg'] = config('app.project.name').": Hello, An OTP to verify your mobile number is: ".$mobile_otp;
			$arr_sms_data['mobile_number'] = $country_code.$mobile_number;
	        $this->SMSService->send_SMS($arr_sms_data);
		}

		$arr_user = $this->UserModel->select('is_mobile_verified','mobile_number','country_code','resend_otp_count')
									->where('id', base64_decode($enc_user_id))
									->first();

		$obj_mobile_country_code = $this->MobileCountryCodeModel->where('iso3', '!=', null)->get();
		if( $obj_mobile_country_code )
		{
			$arr_mobile_country_code = $obj_mobile_country_code->toArray();
		}

		$this->array_view_data['arr_mobile_country_code'] = $arr_mobile_country_code;
		$this->array_view_data['arr_user']                = $arr_user;
		$this->array_view_data['page_title']              = "Verify OTP";
		$this->array_view_data['enc_user_id']             = $enc_user_id;

		return view($this->module_view_folder.'verify_otp', $this->array_view_data);
	}

	public function process_verify_otp(Request $request)
	{
		$arr_rules['mobile_otp'] = "required";
		$arr_rules['enc_user_id'] = "required";

		$validator = validator::make($request->all(),$arr_rules);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}

		$curr_time  = date('Y-m-d H:i:s');
		$user_id    = base64_decode($request->input('enc_user_id'), true);
		$mobile_otp = $request->input('mobile_otp');

		$arr_user = $this->UserModel->select('id', 'mobile_otp', 'mobile_otp_expired_time', 'social_login', 'login_via', 'is_email_verified')->where('id', $user_id)->first();
		
		if ($arr_user['mobile_otp'] != $mobile_otp) {
			Session::flash('error', "Invalid OTP");
			return redirect()->back()->withInput();
		}

		if ($arr_user['mobile_otp_expired_time'] < $curr_time) {
			Session::flash('error', "Your OTP has been expired");
			return redirect()->back()->withInput();
		}

		$arr_up['mobile_otp']              = '';
		$arr_up['mobile_otp_expired_time'] = '0000-00-00 00:00:00';
		$arr_up['is_mobile_verified']      = '1';

		$this->UserModel->where('id', $user_id)->update($arr_up);
		if ($arr_user['social_login'] == 'yes') {
			$this->auth->loginUsingId($arr_user['id']);
	        /* Init Chat Variables */
	        $this->_init_chat_details($arr_user, $arr_user['login_via']);
	    } else if($arr_user['is_email_verified'] == '0') {
	    	Session::flash('success', "Please verify your email to login");
	    	return redirect('login');
	    } else if($arr_user['is_email_verified'] == '1') {
	    	Session::flash('success', "Mobile number verified successfully. Please login to continue");
	    	return redirect('login');
	    }
		return redirect('/');
	}

    public function resend_otp(Request $request)
    {
    	$user_id = base64_decode($request->input('enc_user_id'),true);
    	if ($user_id) {
	    	$arr_user = $this->UserModel->where('id', $user_id)->first();
	    	if (isset($arr_user) && count($arr_user) > 0) {
		    	$mobile_string          = '0123456';
		        $mobile_string_shuffled = str_shuffle($mobile_string);
		        $mobile_otp             = substr($mobile_string_shuffled, 1, 4);

		        $arr_sms_data = [];
		        $arr_sms_data['msg'] = config('app.project.name').": Hello, An OTP to verify your mobile number is: ".$mobile_otp;
		        $arr_sms_data['mobile_number'] = $arr_user['country_code'].$arr_user['mobile_number'];
		        $this->SMSService->send_SMS($arr_sms_data);

				$arr_user_otp['resend_otp_count']        = $arr_user['resend_otp_count'] + 1;
				$arr_user_otp['mobile_otp']              = $mobile_otp;
				$arr_user_otp['mobile_otp_expired_time'] = date("Y-m-d H:i:s", strtotime('+30 minutes'));
		        $this->UserModel->where('id', $user_id)->update($arr_user_otp);
		        
				$ret_arr['status'] = "SUCCESS";
				$ret_arr['count']  = $arr_user['resend_otp_count'] + 1;
				$ret_arr['msg']    = "OTP has been sent to your registered mobile number";
		    } else {
				$ret_arr['status'] = "ERROR";
				$ret_arr['msg']    = "User does not exist";
		    }
	    } else {
			$ret_arr['status'] = "ERROR";
			$ret_arr['msg']    = "Invalid user";
	    }

	    return response()->json($ret_arr);
    }

	public function signup()
	{
		$user_ip       = \Request::ip();
		$user_location = \Location::get($user_ip);

		$arr_mobile_country_code = [];

		if($this->auth->user()) {
			return redirect('/profile');
		}

		$obj_mobile_country_code = $this->MobileCountryCodeModel->where('iso3', '!=', null)->orderBy('iso3', 'ASC')->get();
		if( $obj_mobile_country_code )
		{
			$arr_mobile_country_code = $obj_mobile_country_code->toArray();
		}

		$this->array_view_data['user_location_countrycode'] = isset($user_location->countryCode) && !empty($user_location->countryCode) && $user_location->countryCode != null ? $user_location->countryCode : '';

		$this->array_view_data['arr_mobile_country_code'] = $arr_mobile_country_code;
		$this->array_view_data['page_title']              = 'Sign Up';
		return view($this->module_view_folder.'sign_up', $this->array_view_data);
	}

	public function process_signup(Request $request)
	{
		$arr_rules = array();
		$status    = false;

		$is_email_exist = 0;
		$is_email_exist = $this->UserModel->where('email',$request->input('email'))->first();		
		if (count($is_email_exist) > 0) {
			if ($is_email_exist['social_login']=='yes')	{
				Session::flash('error', 'You have already signup with social media.');
				return redirect()->back()->withInput(); 
			} else {
				Session::flash('error', 'Email already registered.');
				return redirect()->back()->withInput(); 
			}
		}
		/*check that this email id is already registered as a admin or not*/
		$is_email_exist = 0;
		$is_email_exist = $this->AdminModel->where('email', $request->input('email'))->count();

		if($is_email_exist > 0) {
			Session::flash('error', 'You can not signup with these credentials');
			return redirect()->back()->withInput(); 
		}

		$arr_rules['first_name']       = "required";
		$arr_rules['last_name']        = "required";
		$arr_rules['user_name']        = "required|unique:users";
		$arr_rules['email']            = "required|email|unique:users";
		$arr_rules['password']         = "required";
		$arr_rules['confirm_password'] = "required|same:password";
		//$arr_rules['captcha-response'] = "required";
		$arr_rules['hiddenRecaptcha']  = "required";
		$arr_rules['birth_date']       = "required";
		$arr_rules['gender']           = "required";
		$arr_rules['terms']            = "required";
		$arr_rules['country_code']     = "required";
		$arr_rules['mobile_number']    = "required|unique:users";

		$messsages = array(
						//'captcha-response.required' => 'Invalid captcha code.',
						'hiddenRecaptcha.required' => 'Invalid captcha code.',
						'required'                 => 'This field is required.',
						'email.unique'             => 'Email alredy registered.',
						'confirm_password.same'    => 'Password and confirm password should be same.',
					);

		$validator = validator::make($request->all(),$arr_rules, $messsages);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput($request->all());
		}

		
		$mobile_string          = '0123456';
		$mobile_string_shuffled = str_shuffle($mobile_string);
		$mobile_otp             = substr($mobile_string_shuffled, 1, 4);

		$form_data              = $request->all();
		$verification_token     = md5(uniqid(rand(), true));

		$arr_user['first_name']              = isset($form_data['first_name']) ? trim($form_data['first_name']) : '';
		$arr_user['last_name']               = isset($form_data['last_name']) ? trim($form_data['last_name']) : '';
		$arr_user['user_name']               = isset($form_data['user_name']) ? trim($form_data['user_name']) : '';
		$arr_user['display_name']            = (isset($form_data['first_name']) && isset($form_data['last_name'])) ? str_slug($form_data['first_name'].' '.$form_data['last_name']) : '';
		$arr_user['email']                   = isset($form_data['email']) ? trim($form_data['email']) : '';
		$arr_user['password']                = isset($form_data['password']) ? bcrypt($form_data['password']) : '';
		$arr_user['birth_date']              = isset($form_data['birth_date']) ? date('Y-m-d' , strtotime($form_data['birth_date'])) : '';
		$arr_user['gender']                  = isset($form_data['gender']) ? $form_data['gender'] : '';
		$arr_user['country_code']            = isset($form_data['country_code']) ? $form_data['country_code'] : '';
		$arr_user['mobile_number']           = isset($form_data['mobile_number']) ? $form_data['mobile_number'] : '';
		$arr_user['verification_token']      = $verification_token;
		$arr_user['is_mobile_verified']      = '0';
		$arr_user['mobile_otp_expired_time'] = date("Y-m-d H:i:s", strtotime('+30 minutes'));
		$arr_user['mobile_otp']              = $mobile_otp;
		$is_user_registered                  = $this->UserModel->create($arr_user);

		if($is_user_registered) {
			$this->MailchimpService->subscribe($arr_user['email']);

			$arr_sms_data                  = [];
			$arr_sms_data['msg']           = config('app.project.name').": Hello, An OTP to verify your mobile number is: ".$mobile_otp;
			$arr_sms_data['mobile_number'] = $arr_user['country_code'].$arr_user['mobile_number'];
			$this->SMSService->send_SMS($arr_sms_data);

			// Retrieve Email Template 
			$activation_url = '<td align="center" class="listed-btn"><a target="_blank" style="border: 1px solid #ff4747; color: #ffffff; display: block; font-size: 18px; letter-spacing: 0.5px; background-color: #ff4747;
      margin: 0 auto; max-width: 200px; padding: 11px 6px; height: initial; text-align: center; text-transform: capitalize; text-decoration: none; width: 100%; border-radius: 5px;" href="'.url('/verify_account/'.base64_encode($is_user_registered->id).'/'.$verification_token ).'">Verify Email</a></td><br/>';

			$arr_built_content = [
									'USER_NAME'      => $is_user_registered->user_name,
									'Email'          => $is_user_registered->email,
									'ACTIVATION_URL' => $activation_url,
									'PROJECT_NAME'   => config('app.project.name')
								 ];
			$arr_mail_data                      = [];
			$arr_mail_data['email_template_id'] = '3';
			$arr_mail_data['arr_built_content'] = $arr_built_content;
			$arr_mail_data['user']              = ['email' => $is_user_registered->email, 'first_name' => $is_user_registered->user_name];
			$email_status                       = $this->EmailService->send_mail($arr_mail_data);

			// send notification
     		$arr_built_content = array( 'USER_NAME' => isset($is_user_registered->first_name) ? $is_user_registered->first_name : 'NA');
     		
            $arr_notify_data['arr_built_content']  = $arr_built_content;
            $arr_notify_data['notify_template_id'] = '2';
            $arr_notify_data['sender_id']          = $is_user_registered->id;
            $arr_notify_data['sender_type']        = '1';
            $arr_notify_data['receiver_id']        = '1';
            $arr_notify_data['receiver_type']      = '2';
            $arr_notify_data['url']                = '';
            $notification_status                   = $this->NotificationService->send_notification($arr_notify_data);
			//$notification_status   = $this->NotificationService->admin_new_user_notification();

			if($email_status) {
		      Session::flash('success','You are registered successfully please verify your account.');
		      return redirect(url('verify_otp/'.base64_encode($is_user_registered->id)));	
	    	} else {
		       Session::flash('error','Error while sending verification mail.');
		       return redirect(url('login'));
		    }
		} else {
			Session::flash('error', 'Error While Creating Account.');
			return redirect()->back();
		}
	}

	public function verify_account($enc_id=null, $verification_token=null)
	{
		$user_id = base64_decode($enc_id);

		$update_status = $check_verification_token = '';
		$obj_user      = $this->UserModel->where('id',$user_id)->first();

		if (isset($obj_user->is_email_verified) && $obj_user->is_email_verified == '1') {
			Session::flash('error','Your account is alredy verified');
			return redirect(url('/').'/login');
		} else {
			$check_verification_token  =  $obj_user->where('verification_token','=',$verification_token)->first();
			if ($check_verification_token) {
				$update_status = $check_verification_token->update(['is_email_verified' => '1', 'status' => '1', 'verification_token' => null]);
			}
		}
		if ($update_status) {
			Session::flash('success','Your account verified successfully.');
		} else {
			Session::flash('error', 'Error while verifying your account.');
		}
		return redirect(url('/login'));
	}

	public function logout()
	{
		$logout_msg ="";
		if (!empty(Session::get('verify_email_success'))) {
			$logout_msg = Session::get('verify_email_success');
		}

		$this->auth->logout();
		//Session::flush();
		
        if($logout_msg != "") {
        	Session::flash('success',$logout_msg);
        }
		return redirect('/login');
	}  

	public function twitterlogin(Request $request)	
	{
		// For errors
		try{
			$login_user = Socialize::driver('twitter')->user();
		}
		catch(\Exception $e) {
			//Session::flash('error', $e->getMessage());
			Session::flash('error', "Something went wrong with twitter login. Try again or contact Admin");
			return redirect(url('/').'/login');
		}

		if($login_user != null) {

			if(isset($login_user->email) && $login_user->email != "")
			{
				$arr_data['email']         = $login_user->email;
				$arr_data['fname']         = $login_user->name;
				$arr_data['profile_image'] = $login_user->avatar_original;
			}
			else
			{
				Session::flash('error', 'You can`t login through twitter,Your email id is not provided by twitter account.');
				return redirect(url('/').'/login');
			}

	        /* Check if User Exists */
	        $obj_user  = $this->UserModel->where('email',$arr_data['email'])->first();
	        if ($obj_user != null) {

	            if ($obj_user->status == "0") {
	               	Session::flash('error', 'Your account is blocked by admin, Please contact to admin.');
					return redirect(url('/').'/login');
	            }

	            if(strpos($obj_user->profile_image, 'http') !== false || $obj_user->profile_image == null) {
					$arr_up_data['login_via']     = 'twitter';
					$arr_up_data['profile_image'] = $login_user->avatar_original;

					$this->UserModel->where('id', $obj_user['id'])->update($arr_up_data);
	            }
	         	   
	            if ($obj_user->is_mobile_verified == '0') {
					Session::flash('success', 'Your twitter authentication is successfully done, Please verify your mobile number to continue');
					return redirect('/verify_otp/'.base64_encode($obj_user['id']));
				} else {
					$this->auth->loginUsingId($obj_user->id);
		            /* Init Chat Variables */
		            $this->_init_chat_details($obj_user,'twitter');
		            Session::flash('success', 'Login successful');
					return redirect('/');
				}
	        } else {
				$string                            = '123456';
				$string_shuffled                   = str_shuffle($string);
				$password                          = substr($string_shuffled, 1, 4);
				$char_string                       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';	
				$string_shuffled_char              = str_shuffle($char_string);
				$password_char                     = substr($string_shuffled_char, 1, 3);
				$randomPassword                    = $password_char.'@'.$password;
				
				$arr_new_data                      = array();
				$arr_new_data['first_name']        = $login_user->name;
				$arr_new_data['email']             = $login_user->email;
				$arr_new_data['user_name']         = $login_user->name;
				$arr_new_data['profile_image']     = $login_user->avatar_original;
				$arr_new_data['social_login']      = 'yes';
				$arr_new_data['login_via']         = 'twitter';
				$arr_new_data['is_email_verified'] = '1';
				$arr_new_data['password']          = bcrypt($randomPassword);

	            $status = $this->UserModel->create($arr_new_data);	 

	            $obj_user = $this->UserModel->where('id',$status->id)->first();

	            $arr_new_data['plain_text_password'] = $randomPassword;

	            if ($status) {
	               	// $this->auth->loginUsingId($status->id);
	                 /* Init Chat Variables */
	                // $this->_init_chat_details($obj_user,'twitter');

	                Session::flash('success', 'Your twitter authentication is successfully done, Please verify your mobile number to continue');	                
					return redirect('/verify_otp/'.base64_encode($status->id));
	            } else {
	                Session::flash('error', 'Something went wrong, Please try again later.');
					return redirect()->back(); 	 
	            }
	        }
		}
	}

	public function fblogin(Request $request)
	{
		$arr_rules             = array();
		$arr_rules['email']    = "required|email";
		$arr_rules['fname']    = "required";
		$arr_rules['lname']    = "required";
		$arr_rules['fb_token'] = "required";

		$validator = Validator::make($request->all(),$arr_rules);

		if ($validator->fails()) {
			$arr_response           = array();
			$arr_response['status'] = "error";
			$arr_response['msg']    = "Sorry you cant login through facebook,Email id is not provided by facebook.";

			return response()->json($arr_response);
		}

		$arr_data['email']       = $request->input('email');
		$arr_data['fname']       = $request->input('fname');
		$arr_data['lname']       = $request->input('lname');
		$arr_data['profile_pic'] = "https://graph.facebook.com/".$request->input('fb_user_id')."/picture?height=250&width=250";
		$arr_data['fb_token']    = $request->input('fb_token');

		if (verify_fb_token($arr_data['fb_token'])) {
			$obj_user  = $this->UserModel->where('email',$arr_data['email'])->first();

			if ($obj_user != null) {			
				if($obj_user->status == "0") {
					return ['status' => 'error', 'msg' => 'Your account is blocked by admin, Please contact to admin.'];
					/*$arr_response           = array();
					$arr_response['status'] = "error";
					$arr_response['msg']    = "Your account is blocked by admin, Please contact to admin.";
					return response()->json($arr_response);*/
				}

	            if(strpos($obj_user->profile_image, 'http') !== false || $obj_user->profile_image == null) {
					$arr_up_data['login_via']     = 'facebook';
					$arr_up_data['profile_image'] = $arr_data['profile_pic'];

					$this->UserModel->where('id', $obj_user['id'])->update($arr_up_data);
	            }

				$arr_response           = array();
	            if ($obj_user->is_mobile_verified == '0') {
					$arr_response['status'] = "SUCCESS_VERIFY";
					$arr_response['msg']    = 'Your facebbok authentication is successfully done, Please verify your mobile number to continue';
					$arr_response['enc_user_id'] = isset($obj_user->id)?base64_encode($obj_user->id):'';
					Session::flash('success', 'Your facebook authentication is successfully done, Please verify your mobile number to continue');
				} else {
					$this->auth->loginUsingId($obj_user->id);
					$this->_init_chat_details($obj_user,'facebook');

					$arr_response['status'] = "SUCCESS";
					$arr_response['msg']    = 'Login successfully.';
				}

				return response()->json($arr_response);
			} else {

				/* Register User */
				$string                             = '123456';
				$string_shuffled                    = str_shuffle($string);
				$password                           = substr($string_shuffled, 1, 4);
				
				$char_string                        = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';	
				$string_shuffled_char               = str_shuffle($char_string);
				$password_char                      = substr($string_shuffled_char, 1, 3);
				
				$randomPassword                     = $password_char.'@'.$password;
				$arr_new_data                       = array();
				
				$arr_new_data['first_name']         = $arr_data['fname'];
				$arr_new_data['last_name']          = $arr_data['lname'];
				$arr_new_data['email']              = $arr_data['email'];
				$arr_new_data['user_name']          = $arr_data['fname'];
				$arr_new_data['profile_image']      = $arr_data['profile_pic'];
				
				$arr_new_data['social_login']       = 'yes';
				$arr_new_data['login_via']          = 'facebook';
				$arr_new_data['is_email_verified']  = '1';
				$arr_new_data['is_mobile_verified'] = '0';
				$arr_new_data['password']           = bcrypt($randomPassword);

				$status = $this->UserModel->create($arr_new_data);	 

				$obj_user = $this->UserModel->where('id',$status->id)->first();

				$arr_new_data['plain_text_password'] = $randomPassword;

				if($status) {
					// $this->auth->loginUsingId($status->id);
					// $this->_init_chat_details($obj_user,'facebook');

					$arr_response                = array();
					$arr_response['status']      = "SUCCESS_VERIFY";
					$arr_response['msg']         = 'Please verify your mobile number to continue';
					$arr_response['enc_user_id'] =  isset($status->id)?base64_encode($status->id):'';//base64_encode($obj_user['id']);
					Session::flash('success', 'Your facebook authentication is successfully done, Please verify your mobile number to continue');

					return response()->json($arr_response);
				} else {
					$arr_response           = array();
					$arr_response['status'] = "Error";
					$arr_response['msg']    = 'Something went wrong, Please try again.';

					return response()->json($arr_response);
				}
			}
		} else {
			$arr_response           = array();
			$arr_response['status'] = "ERROR";
			$arr_response['msg']    = 'Invalid facebook token';

			return response()->json($arr_response);   
		}
	}
   
	public function gplogin(Request $request)
	{
		$email                  = $request->input('email','');
		$first_name             = $request->input('fName','');
		$profile_image_name_arr = explode('?', $request->input('profile_pic'));
		$profile_image          = $profile_image_name_arr[0];
		$user_name              = explode(' ',$first_name);
		if($email=="")
		{
			Session::flash('error', 'Some mandatory fields are missing,You cant login through google account.');
			$arr_response['status'] = 'error';
			$arr_response['msg']    = "Some mandatory fields are missing,You cant login through google account.";
			return response()->json($arr_response);
		}
	    $obj_user = $this->UserModel->where('email',$email)->first();

	    if ($obj_user!=false) {
	        if ($obj_user->status=="0") {
				return ['status' => 'error', 'msg' => 'Your account is blocked by admin, Please contact to admin.'];
				/*$arr_response           = array();
				$arr_response['status'] = "error";
				$arr_response['msg']    = "Your account blocked by admin, Please contact to admin.";
				return response()->json($arr_response);*/
	        }
	        
            if(strpos($obj_user->profile_image, 'http') !== false || $obj_user->profile_image == null) {
				$arr_up_data['login_via']     = 'GPlus';
				$arr_up_data['profile_image'] = $profile_image;

				$this->UserModel->where('id', $obj_user['id'])->update($arr_up_data);
            }

	        $arr_response = array();
			if ($obj_user->is_mobile_verified == '0') {
				$arr_response['status'] = "SUCCESS_VERIFY";
				$arr_response['msg']    = "Your google authentication is successfully done, Please verify your mobile number to continue";
				Session::flash('success', 'Your google authentication is successfully done, Please verify your mobile number to continue');
			} else {
				$this->auth->loginUsingId($obj_user['id'], 'google');
		        $this->_init_chat_details($obj_user,'google');
				$arr_response['status'] = "SUCCESS";
				$arr_response['msg']    = "Login successfully";
			}
			
			$arr_response['enc_user_id'] = base64_encode($obj_user['id']);
	        return response()->json($arr_response);
	        
	    } else {
			$string                            = '123456';
			$string_shuffled                   = str_shuffle($string);
			$password                          = substr($string_shuffled, 1, 4);
			
			$char_string                       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';	
			$string_shuffled_char              = str_shuffle($char_string);
			$password_char                     = substr($string_shuffled_char, 1, 3);
			
			$randomPassword                    = $password_char.'@'.$password;
			$arr_new_data                      = array();
			
			$first_name                        = $user_name[0];
			$last_name                         = $user_name[1];
			
			$arr_new_data['first_name']        = $first_name;
			$arr_new_data['last_name']         = $last_name;
			$arr_new_data['email']             = $email;
			$arr_new_data['user_name']         = $email;
			$arr_new_data['profile_image']     = $profile_image;
			$arr_new_data['social_login']      = 'yes';
			$arr_new_data['login_via']         = 'google+';
			$arr_new_data['password']          = bcrypt($randomPassword);
			$arr_new_data['is_email_verified'] = '1';
			
			$status   = $this->UserModel->create($arr_new_data);
	        $arr_new_data['plain_text_password'] = $randomPassword;
			$obj_user = $this->UserModel->where('id',$status->id)->first();

	        if ($status) {
				$arr_response                = array();
				$arr_response['status']      = "SUCCESS_VERIFY";
				$arr_response['msg']         = 'Your google authentication is successfully done, Please verify your mobile number to continue';
				$arr_response['enc_user_id'] = base64_encode($obj_user['id']);
				Session::flash('success', 'Your google authentication is successfully done, Please verify your mobile number to continue');

	            return response()->json($arr_response);
	        } else {
				$arr_response           = array();
				$arr_response['status'] = "Error";
				$arr_response['msg']    = 'Something went wrong, Please try again.';

	            return response()->json($arr_response);
	        }
	    }
	    echo str_replace('\/','/',json_encode($resp));
	}
	
	protected function _init_chat_details(\App\Models\UserModel $user,$login_type)
   	{
   		Session::put('user_type', '1');
   		/* if($login_type=='google')
         {
            $vars = ["logout_url"    => $this->gp_logout_url.url('/logout')];
         }               
        
         Session()->put('userLogged', $vars);*/
         return TRUE;
   	}
}