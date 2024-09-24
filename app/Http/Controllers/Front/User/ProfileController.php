<?php

namespace App\Http\Controllers\Front\User;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\HostVerificationModel;
use App\Models\MobileCountryCodeModel;
use App\Common\Services\EmailService;
use App\Common\Services\SMSService;
use Twilio\Rest\Client;

use Validator;
use Session;
use Image;
use Hash;

class ProfileController extends Controller
{
    public function __construct(
	    							UserModel              $user_model,
	    							EmailService           $email_service,
	    							SMSService             $sms_service,
	    							HostVerificationModel  $host_verification_model,
	    							MobileCountryCodeModel $mobile_country_code_model
    							)
	{
		$this->arr_view_data          = [];
		$this->module_title           = 'My Account';
		$this->module_view_folder     = 'front.user';
		$this->UserModel              = $user_model;
		$this->MobileCountryCodeModel = $mobile_country_code_model;
		$this->EmailService           = $email_service;
		$this->SMSService             = $sms_service;
		$this->HostVerificationModel  = $host_verification_model;
		$this->auth                   = auth()->guard('users');
		$user                         = $this->auth->user();
      	if ($user) {
			$this->user_id = $user->id;
      	}

		$this->profile_image_public_path = url('/').config('app.project.img_path.user_profile_images');
		$this->profile_image_base_path   = public_path().config('app.project.img_path.user_profile_images');

		$this->id_proof_public_path      = url('/').config('app.project.img_path.user_id_proof');
		$this->id_proof_base_path        = public_path().config('app.project.img_path.user_id_proof');
		
		$this->photo_public_path         = url('/').config('app.project.img_path.user_photo');
		$this->photo_base_path           = public_path().config('app.project.img_path.user_photo');


		$twilio_credentials = get_twilio_credential();

		$this->twilio_service_sid = (isset($twilio_credentials) && $twilio_credentials['twilio_service_sid'] != '') ? $twilio_credentials['twilio_service_sid'] : null;
		$this->twilio_sid         = (isset($twilio_credentials) && $twilio_credentials['twilio_sid'] != '') ? $twilio_credentials['twilio_sid'] : null;
		$this->twilio_token       = (isset($twilio_credentials) && $twilio_credentials['twilio_token'] != '') ? $twilio_credentials['twilio_token'] : null;
		$this->from_user_mobile   = (isset($twilio_credentials) && $twilio_credentials['from_user_mobile'] != '') ? $twilio_credentials['from_user_mobile'] : null;
	}

	public function index()
	{
		$arr_mobile_country_code = [];

		$obj_user = $this->UserModel->where('id',$this->user_id)->first();
		if(isset($obj_user) && $obj_user != null) {
			$arr_user = $obj_user->toArray();
		}

		$obj_mobile_country_code = $this->MobileCountryCodeModel->where('iso3', '!=', null)->orderBy('iso3', 'ASC')->get();
		if( $obj_mobile_country_code ) {
			$arr_mobile_country_code = $obj_mobile_country_code->toArray();
		}
		
		$type = $this->UserModel->where('id',$this->user_id)->first(['id','notification_type']);

		$this->arr_view_data['notification_type']         = isset($type) ? $type->notification_type:'';
		$this->arr_view_data['user']                      = $arr_user;
		$this->arr_view_data['arr_mobile_country_code']   = $arr_mobile_country_code;
		$this->arr_view_data['id']                        = $this->user_id;
		$this->arr_view_data['page_title']                = $this->module_title;
		$this->arr_view_data['profile_image_public_path'] = $this->profile_image_public_path;
		$this->arr_view_data['profile_image_base_path']   = $this->profile_image_base_path;
		
		return view($this->module_view_folder.'.my_account',$this->arr_view_data);
	}

	public function update(Request $request,$id)
	{
		$arr_data = $user_data = [];
		$id = $this->user_id;
		$filename  = '';
		$obj_user  = $this->UserModel->where('id',$id)->first();
		$old_image = isset($obj_user->profile_image) ? $obj_user->profile_image : '';

		if ($request->hasFile('profile_image')) {
        	$file_extension = strtolower($request->file('profile_image')->getClientOriginalExtension());
        	if (in_array($file_extension,['png','jpg','jpeg'])) {
				$file     = $request->file('profile_image');
				$filename = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
				$path     = $this->profile_image_base_path . $filename;

				$isUpload = Image::make($file->getRealPath())->resize(168,168)->save($path);

				if ($isUpload) {
					if ($old_image != "" && $old_image != null) {
						$profile_image = $this->profile_image_base_path.$old_image;
		
						if (file_exists($profile_image)) {
							unlink($profile_image);
						}
					}
				}
			} else {
				Session::flash('error','Invalid image,Please select valid image');
				return redirect()->back();
			}
        } else {
        	$filename = $old_image;
        }

        $check_email_exist = $this->UserModel->where('id','!=',$id)->where('email',trim($request->input('email')))->first();
        if (count($check_email_exist) > 0) {
        	Session::flash('error','Email already exist, Please try another email.');
        	return redirect()->back();
        }

        $check_mobile_exist = $this->UserModel->where('id','!=',$id)->where('mobile_number',trim($request->input('mobile_number')))->first();
        if (count($check_mobile_exist) > 0) {
        	Session::flash('error','Mobile number already exist, Please try another mobile number.');
        	return redirect()->back();
        }

        $user_data = $this->UserModel->where('id','=',$id)->first();
        
        if(count($check_mobile_exist) == 0 && count($check_email_exist) == 0)
        {
			$arr_data['first_name']            = trim($request->input('first_name'));
			$arr_data['last_name']             = trim($request->input('last_name'));
			$arr_data['user_name']             = trim($request->input('user_name'));
			$arr_data['email']                 = trim($request->input('email'));
			$arr_data['address']               = trim($request->input('address'));
			$arr_data['city']                  = trim($request->input('city'));
			$arr_data['birth_date']            = date('Y-m-d',strtotime($request->input('birth_date')));
			$arr_data['display_name']          = trim($request->input('display_name'));
			$arr_data['notification_by_email'] = trim($request->input('notification_by_email'));
			$arr_data['notification_by_sms']   = trim($request->input('notification_by_sms'));
			$arr_data['notification_by_push']  = trim($request->input('notification_by_push'));
			$arr_data['profile_image']         = $filename;
			$arr_data['gstin']                 = trim($request->input('gstin'));

			if($request->input('gender') != '') {
				$arr_data['gender'] = trim($request->input('gender'));
			}

	       	$obj_data = $this->UserModel->where('id',$id)->update($arr_data);
	        if ($obj_data) 
	        {
	        	/*Change by kavita*/
	        	if ($request->input('email') != $obj_user['email']) {
	        		$verification_token             = md5(uniqid(rand(), true));
	        		$arr_user['is_email_verified']  = '0';
	        		$arr_user['verification_token'] = $verification_token;

	        		$this->UserModel->where('id', $id)->update($arr_user);	 

	        		$activation_url = '<td align="center" class="listed-btn"><a style="border: 1px solid #ff4747; color: #ffffff; display: block; font-size: 18px; letter-spacing: 0.5px; background-color: #ff4747; margin: 0 auto; max-width: 200px; padding: 11px 6px; height: initial; text-align: center; text-transform: capitalize; text-decoration: none; width: 100%; border-radius: 5px;" target="_blank" href="'.url('/verify_account/'.base64_encode($id).'/'.$verification_token ).'">Verify Email</a></td><br/>' ;

					$arr_built_content = [
											'USER_NAME'      => $request->input('user_name'),
											'Email'          => $request->input('email'),
											'ACTIVATION_URL' => $activation_url,
											'PROJECT_NAME'   => config('app.project.name')
										];
					$arr_mail_data                      = [];
					$arr_mail_data['email_template_id'] = '20';
					$arr_mail_data['arr_built_content'] = $arr_built_content;
					$arr_mail_data['user']              = [
															'email'      => $request->input('email'),
															'first_name' => $request->input('user_name')
														];

					$email_status = $this->EmailService->send_mail($arr_mail_data);
					if ($email_status) {
						Session::flash('success','Your email has changed successfully, please verify your email');
					}
					$logout_user = true;
	        	}

	        	if ($request->input('mobile_number') != $obj_user['mobile_number']) {
                    $mobile_string          = '0123456';
                    $mobile_string_shuffled = str_shuffle($mobile_string);
                    $mobile_otp             = substr($mobile_string_shuffled, 1, 4);

                    $arr_sms_data                  = [];
                    $arr_sms_data['msg']           = config('app.project.name').": Hello, An OTP to verify your mobile number is: ".$mobile_otp;
                    $arr_sms_data['mobile_number'] = $request->input('country_code').$request->input('mobile_number');
                    $this->SMSService->send_SMS($arr_sms_data);

					$arr_user_otp['country_code']            = $request->input('country_code');
					$arr_user_otp['new_mobile_number']       = $request->input('mobile_number');
					$arr_user_otp['mobile_otp']              = $mobile_otp;
					$arr_user_otp['mobile_otp_expired_time'] = date("Y-m-d H:i:s", strtotime('+30 minutes'));
                    $this->UserModel->where('id', $id)->update($arr_user_otp);
                    
                    if (isset($email_status)) {
						Session::flash('success','Your email and mobile has changed successfully, please verify and login');
                    } else {
						Session::flash('success','Your mobile has changed successfully, please verify and login');
                    }
					$logout_user = true;
                }

                if (isset($logout_user))
                {
					return redirect(url('/logout'));
                }
	            Session::flash('success', str_singular($this->module_title).' updated successfully');
	        }
	        else
	        {
	            Session::flash('error','Problem occurred while updating your profile');
	        }	
        }
           
        return redirect()->back();
	}

	public function change_password()
    {
		$this->arr_view_data['page_title']                = "Change Password";
		$this->arr_view_data['profile_image_public_path'] = $this->profile_image_public_path;
		$this->arr_view_data['profile_image_base_path']   = $this->profile_image_base_path;

        return view($this->module_view_folder.'.change_password',$this->arr_view_data);
    }

    public function update_password(Request $request)
    { 
    	$arr_rules = array();
        $status = FALSE;
        $arr_rules['old_password']     = "required";
        $arr_rules['new_password']     = "required";
        $arr_rules['confirm_password'] = "required|same:new_password";        
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
           return redirect()->back()->withErrors($validator);
        }
        $old_password      =  $request->input('old_password');
        $new_password      =  $request->input('new_password');
        $confirm_password  =  $request->input('confirm_password');

        if(Hash::check($old_password,$this->auth->user()->password))
    	{
    		if($old_password!=$new_password)
	        {
	        	if($new_password == $confirm_password)
	            {
	                $user_password = bcrypt($new_password);
	                $status = $this->UserModel->where('id',$this->user_id)->update(['password'=>$user_password]);

	                if($status)
	                {
	                    $this->auth->logout();
	                    Session::flash('success','Your password changed successfully.');
	                    return redirect('/login');
	                }
	                else
	                {
	                    Session::flash('error','Problem occured, while changing password');
	                }
	                return redirect()->back();
	            }
	            else
	            {
	                Session::flash('error','Please enter the same value again.');
	                return redirect()->back();
	            }
	        }
	        else
	        {
	            Session::flash('error','Sorry you can not use current password as a new password, Please enter another new password');
	            return redirect()->back();
	        }
    	}
    	else
    	{
	    	Session::flash('error',"Incorrect old password");
	        return redirect()->back();    		
    	}
       
    	Session::flash('error','Problem occured, while changing password');
        return redirect()->back();       
    }


    /*
    | Function  : switch user type in session
    | Author    : Deepak Arvind Salunke
    | Date      : 23/04/2018
    | Output    : Success or Error
    */

    public function switch_user_type()
    {
    	$arr_json['status'] = 'error';

    	if(Session::get('user_type') == '1')
		{
			Session::forget('user_type');
			Session::put('user_type', '4');

			$arr_json['status'] = 'success';
		}
		else if(Session::get('user_type') == '4')
		{
			Session::forget('user_type');
			Session::put('user_type', '1');

			$arr_json['status'] = 'success';
		}

		return response()->json($arr_json);
    } // end switch_user_type



    public function check_username(Request $request)
    {
    	$status = 'not_exist';

    	$user_name = $request->input('user_name');

    	$count = $this->UserModel->where('user_name',$user_name)->where('id', '!=', $this->user_id)->count();
    	if($count)
    	{
    		$status = 'exist';
    	}
    	return response()->json($status);

    } // end check_username

     public function check_email(Request $request)
    {
    	$status = 'not_exist';

    	$email = $request->input('email'); 

    	$count = $this->UserModel->where('email',$email)->where('id', '!=', $this->user_id)->count();

    	if($count)
    	{
    		$status = 'exist';
    	}
    	return response()->json($status);

    }

    public function my_documents()
    {
		$arr_doc = '';
		$obj_user = $this->auth->user();

		$obj_doc = $this->HostVerificationModel->where(['user_id'=> $obj_user->id])->first();
		if ($obj_doc) {
			$arr_doc = $obj_doc->toArray();
		}

		$this->arr_view_data['arr_doc']              = $arr_doc;
		$this->arr_view_data['page_title']           = "My Documents";
		$this->arr_view_data['id_proof_public_path'] = $this->id_proof_public_path;
		$this->arr_view_data['id_proof_base_path']   = $this->id_proof_base_path;
		$this->arr_view_data['photo_public_path']    = $this->photo_public_path;
		$this->arr_view_data['photo_base_path']      = $this->photo_base_path;

        return view($this->module_view_folder.'.my_documents',$this->arr_view_data);
    }

    public function check_mobile_number(Request $request)
    {
    	$status = 'not_exist';

    	$mobile_number = $request->input('mobile_number'); 

    	$is_mobile_exist = $this->UserModel->where('mobile_number',$mobile_number)->where('id', '!=', $this->user_id)->get();

    	if(count($is_mobile_exist)>0)
    	{
    		$status = 'exist';
    	}
    	return response()->json($status);

    } // end check mobile no

    public function verify_otp(Request $request)
    {
    	$country_code      = $request->input('country_code', '');
    	$mobile_otp        = $request->input('mobile_otp', '');
    	$new_mobile_number = $request->input('new_mobile_number', '');

    	if ($mobile_otp == '') {
			$arr_response['status'] = 'error';
			$arr_response['msg']    = 'Please Enter Valid OTP';
			return response()->json($arr_response);
    	} else {
    		$arr_user = $this->UserModel->where('id', $this->user_id)->first();
    		if ($arr_user['new_mobile_number'] == '') {
				$arr_response['status'] = 'error';
				$arr_response['msg']    = 'Something went wrong';
			    return response()->json($arr_response);
    		}

    		if (date('Y-m-d H:i:s') > $arr_user['mobile_otp_expired_time']) {
				$arr_response['status'] = 'error';
				$arr_response['msg']    = 'This OTP is expired';
			    return response()->json($arr_response);
    		}

    		if ($mobile_otp != $arr_user['mobile_otp']) {
				$arr_response['status'] = 'error';
				$arr_response['msg']    = 'Please enter Valid OTP';
			    return response()->json($arr_response);
    		}

			$arr_up['country_code']            = $country_code;
			$arr_up['mobile_number']           = $arr_user['new_mobile_number'];
			$arr_up['new_mobile_number']       = '';
			$arr_up['mobile_otp']              = '';
			$arr_up['mobile_otp_expired_time'] = '';
			$arr_up['is_mobile_verified']      = '1';
			$is_updated = $this->UserModel->where('id', $this->user_id)->update($arr_up);
			if( $is_updated )
			{
				$arr_response['status'] = 'success';
				$arr_response['msg']    = 'OTP verified successfully';
			}
			else
			{
				$arr_response['status'] = 'error';
				$arr_response['msg']    = 'Something went wrong';
			}
		    return response()->json($arr_response);
    	}
    }

    public function generate_otp(Request $request)
    {
    	$country_code  = $request->input('country_code');
    	$mobile_number = $request->input('mobile_number');
    	
    	$is_mobile_exist = $this->UserModel->where('mobile_number',$mobile_number)->where('id', '!=', $this->user_id)->get();
    	if( count( $is_mobile_exist ) > 0 )
    	{
    		$response['status'] = 'error';
    		return response()->json($response);
    	}

    	$arr_user = $this->UserModel->where('id', $this->user_id)->first();
    	$mobile_string          = '0123456';
        $mobile_string_shuffled = str_shuffle($mobile_string);
        $mobile_otp             = substr($mobile_string_shuffled, 1, 4);

        $arr_sms_data                  = [];
        $arr_sms_data['msg']           = config('app.project.name').": Hello, An OTP to verify your mobile number is: ".$mobile_otp;
        $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
        $this->SMSService->send_SMS($arr_sms_data);

		$arr_user_otp['resend_otp_count']        = $arr_user['resend_otp_count'] + 1;
		$arr_user_otp['new_mobile_number']       = $mobile_number;
		$arr_user_otp['mobile_otp']              = $mobile_otp;
		$arr_user_otp['mobile_otp_expired_time'] = date("Y-m-d H:i:s", strtotime('+30 minutes'));
        
        $this->UserModel->where('id', $this->user_id)->update($arr_user_otp);

        $response['status'] = 'success';
        $response['count'] = $arr_user['resend_otp_count'] + 1;
	    return response()->json($response);
    }
}
