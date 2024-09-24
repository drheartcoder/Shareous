<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Common\Services\EmailService;
use App\Common\Services\MailchimpService;
use App\Common\Services\NotificationService;
use App\Common\Services\JwtUserService;
use App\Common\Services\SMSService;

use App\Models\UserModel;
use App\Models\AdminModel;
use App\Models\HostVerificationModel;
use App\Models\EmailTemplateModel;

use Validator;
use JWTAuth;
use DB;

class AuthController extends Controller
{
    public function __construct(
                                UserModel           $user_model,
                                AdminModel          $admin_model,
                                EmailService        $email_service,
                                SMSService          $sms_service,
                                JwtUserService      $jwt_user_service,
                                EmailTemplateModel  $email_template,
                                MailchimpService    $mailchimp_service,
                                NotificationService $notification_service
                            )
    {
        $this->UserModel                 = $user_model;
        $this->AdminModel                = $admin_model;
        $this->EmailService              = $email_service;
        $this->SMSService                = $sms_service;
        $this->NotificationService       = $notification_service;
        $this->EmailTemplateModel        = $email_template;
        $this->MailchimpService          = $mailchimp_service;
        $this->JwtUserService            = $jwt_user_service;
        $this->auth                      = auth()->guard('users');
        $this->profile_image_public_path = url('/').config('app.project.img_path.user_profile_images');
    }

    public function login(Request $request)
    {
        $in_process_host = '';
        $arr_rules = $check_user = $arr_data = $user_detail = [];

        $txt_user_name = $request->input('user_name');
        $password      = $request->input('password');

        $arr_rules['user_name'] = 'required';
        $arr_rules['password']  = 'required';
        $validator = Validator::make($request->all(),$arr_rules);
        if ($validator->fails()) {
            $status = 'error';
            $message = 'Fill all required fields';
        } else {
            if (filter_var($txt_user_name, FILTER_VALIDATE_EMAIL)) {
                $login_field = 'email';
                $check_user  = $this->UserModel->where('email',$txt_user_name)->first();
            } else {
                $login_field = 'user_name';
                $check_user  = $this->UserModel->where('user_name',$txt_user_name)->first();
            }

            if (isset($check_user) && count($check_user)>0) {
                $user = $this->UserModel->where('id',$check_user['id'])->first();

                if ($check_user['status'] == '0') {
                    $status  = 'error';
                    $message = 'Your account is temporarily blocked';
                } else {
                    if ($check_user['is_email_verified'] == '0' && $check_user['is_mobile_verified'] == '0') {
                        $arr_data['user_id']       = $user['id'];
                        $arr_data['mobile_number'] = $user['new_mobile_number'];
                        $arr_data['email']         = $user['email'];

                        return $this->build_response('both_not_verified','Your Email and mobile number are not verified',$arr_data);
                    } else if ($check_user['is_email_verified'] == '0' && $check_user['is_mobile_verified'] == '1') {
                        $arr_data['user_id'] = $user['id'];
                        $arr_data['email']   = $user['email'];

                        return $this->build_response('email_not_verified','Your Email is not verified',$arr_data);
                    } else if ($check_user['is_email_verified'] == '1' && $check_user['is_mobile_verified'] == '0') {
                        $arr_data['user_id']       = $user['id'];
                        $arr_data['mobile_number'] = $user['new_mobile_number'];
                        
                        return $this->build_response('mobile_not_verified', 'Your mobile number is not verified', $arr_data);
                    } else {
                        $arr_auth_credentials = [
                                                    $login_field => $request->input('user_name'),
                                                    'password'   => $request->input('password')
                                                ];

                        if ($this->auth->attempt($arr_auth_credentials)) {
                            $userToken = $this->JwtUserService->generate_user_jwt_token($user);

                            $user_detail['user_token']    = $userToken['user_token'];
                            $user_detail['user_id']       = $user['id'];
                            $user_detail['email']         = $user['email'];
                            $user_detail['address']       = $user['address'];
                            $user_detail['country']       = $user['country'];
                            $user_detail['state']         = $user['state'];
                            $user_detail['city']          = $user['city'];
                            $user_detail['first_name']    = $user['first_name'];
                            $user_detail['last_name']     = $user['last_name'];
                            $user_detail['country_code']  = $user['country_code'];
                            $user_detail['mobile_number'] = $user['mobile_number'];
                            $user_detail['user_type']     = $user['user_type'];

                            if($user['profile_image'] != '')     
                                $user_detail['profile_image'] = $this->profile_image_public_path.$user['profile_image'];
                            else
                                $user_detail['profile_image'] = url('/').'/uploads/default-profile.png';

                            $obj_process_host = HostVerificationModel::where(['user_id'=>$user['id']])->where('status',3)->count();

                            if ($obj_process_host > 0) {
                                $user_detail['in_process_host'] = "yes";
                            } else {
                                $user_detail['in_process_host'] = "no";
                            }

                            $arr_data = $user_detail;
                            $status   = 'success';
                            $message  = 'Login successfully';
                        } else {
                            return $this->build_response('error', 'Invalid login credential');
                        }
                    }
                }
            } else {
                $status  = 'error';
                $message = 'Invalid User Details';
            }
        }
        return $this->build_response($status, $message, $arr_data);
    }

    public function registration(Request $request)
    {
        $arr_rules = $arr_data = [];
        $is_email_exist = 0;

        $arr_rules['first_name']    = "required";
        $arr_rules['last_name']     = "required";
        $arr_rules['user_name']     = "required";
        $arr_rules['email']         = "required|email";
        $arr_rules['password']      = "required";
        $arr_rules['birth_date']    = "required";
        $arr_rules['gender']        = "required";
        $arr_rules['terms']         = "required";
        $arr_rules['country_code']  = "required";
        $arr_rules['mobile_number'] = "required";
        
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails()) {
           $status  = 'error';
           $message = 'fill all required fields';    
        } else {
            
            $verification_token = md5(uniqid(rand(), true));
            $otp_expired_time   = date("Y-m-d H:i:s", strtotime('+30 minutes'));

            $form_data                           = $request->all();
            $arr_user['first_name']              = trim($form_data['first_name']);
            $arr_user['last_name']               = trim($form_data['last_name']);
            $arr_user['user_name']               = strtolower(trim($form_data['user_name']));
            $arr_user['display_name']            = (isset($form_data['user_name']) && isset($form_data['last_name'])) ? str_slug($form_data['user_name'].' '.$form_data['last_name']) : '';
            $arr_user['email']                   = strtolower(trim($form_data['email']));
            $arr_user['password']                = bcrypt($form_data['password']);
            $arr_user['birth_date']              = (isset($form_data['birth_date']) && $form_data['birth_date'] != '0000-00-00') ? date('Y-m-d' , strtotime($form_data['birth_date'])) : '0000-00-00';
            $arr_user['gender']                  = $form_data['gender'];
            $arr_user['country_code']            = $form_data['country_code'];
            $arr_user['mobile_number']           = $form_data['mobile_number'];
            $arr_user['verification_token']      = $verification_token;
            $arr_user['otp_expired_time']        = $otp_expired_time;
            $arr_user['mobile_otp_expired_time'] = $otp_expired_time;

            $is_user_exits = $this->UserModel->where('email',$form_data['email'])->get();

            if (count($is_user_exits) == 0) {
                $is_email_exist = $this->AdminModel->where('email',$form_data['email'])->count();

                if ($is_email_exist == 0) {
                    $is_username_exits = $this->UserModel->where('user_name',$form_data['user_name'])->count();

                    if ($is_username_exits == 0) {
                        $is_mobileno_exits = $this->UserModel->where('mobile_number',$form_data['mobile_number'])->count();
                        
                        if ($is_mobileno_exits == 0) {
                                $is_user_registered = $this->UserModel->create($arr_user);

                                if ($is_user_registered) {
                                    $this->MailchimpService->subscribe($arr_user['email']);

                                    $string          = '0123456';
                                    $string_shuffled = str_shuffle($string);
                                    $otp             = substr($string_shuffled, 1, 4);

                                    $arr_built_content = [
                                                            'OTP'          => $otp,
                                                            'USER_NAME'    => $is_user_registered->user_name,
                                                            'PROJECT_NAME' => config('app.project.name')
                                                        ];
                                    $arr_mail_data                      = [];
                                    $arr_mail_data['email_template_id'] = '18';
                                    $arr_mail_data['arr_built_content'] = $arr_built_content;
                                    $arr_mail_data['user']              = ['email' => $is_user_registered->email, 'first_name' => $is_user_registered->user_name];
                                    $email_status = $this->EmailService->send_mail($arr_mail_data);

                                    // Send OTP SMS
                                    $mobile_string          = '0123456';
                                    $mobile_string_shuffled = str_shuffle($mobile_string);
                                    $mobile_otp             = substr($mobile_string_shuffled, 1, 4);

                                    $country_code = isset($is_user_registered->country_code) ? $is_user_registered->country_code : '';
                                    $mobile_number = isset($is_user_registered->mobile_number) ? $is_user_registered->mobile_number : '';

                                    $arr_sms_data                  = [];
                                    $arr_sms_data['msg']           = config('app.project.name').": Hello, An OTP to verify your mobile number is: ".$mobile_otp;
                                    $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                                    $sms_resp = $this->SMSService->send_SMS($arr_sms_data);
                                    
                                    $arr_built_content = array( 'USER_NAME' => isset($is_user_registered->first_name) ? $is_user_registered->first_name : 'NA' );

                                    $arr_notify_data['arr_built_content']  = $arr_built_content;   
                                    $arr_notify_data['notify_template_id'] = '2';
                                    $arr_notify_data['sender_id']          = $is_user_registered->id;
                                    $arr_notify_data['sender_type']        = '1';
                                    $arr_notify_data['receiver_id']        = '1';
                                    $arr_notify_data['receiver_type']      = '2';
                                    $arr_notify_data['url']                = '';
                                    $this->NotificationService->send_notification($arr_notify_data);

                                    if ($email_status) {
                                        $up_user_arr['otp']        = $otp;
                                        $up_user_arr['mobile_otp'] = $mobile_otp;
                                        $this->UserModel->where('id',$is_user_registered->id)->update($up_user_arr);

                                        $arr_data = array('user_id' => $is_user_registered->id);
                                        $status   = 'success';
                                        $message  = 'Thank you for signing up with '.config('app.project.name').'. Please check your email for OTP to verify your account and complete signup process successfully. OTP will be valid within 30 minutes'; 
                                    } else {
                                        $status  = 'error';
                                        $message = 'Error while sending verification mail.';
                                    }
                                } else {
                                    $status  = 'error';
                                    $message = 'Error while creating account';
                                }
                        } else {
                            $status  = 'error';
                            $message = 'This mobile number is already registered with us';
                        }
                    } else {
                        $status  = 'error';
                        $message = 'This Username is already taken';
                    }
                } else {
                    $status  = 'error';
                    $message = 'You can not login with these credetials';
                }
            } else {
                $status  = 'error';
                $message = 'This email is already registered, Please login';
            }
        }
        return $this->build_response($status, $message,$arr_data);
    }

    public function verify_otp(Request $request)
    {
        $json_arr = $user = $arr_data = $user_data = array();
        $status = '';
        
        $user_id = $request->input('user_id');
        $verify_type = $request->input('verify_type', 'both');

        $arr_rules['user_id'] = 'required';
        if ($verify_type == 'email' || $verify_type == 'both') {
            $arr_rules['otp'] = 'required';
        }
        if ($verify_type == 'mobile' || $verify_type == 'both') {
            $arr_rules['mobile_otp'] = 'required';
        }

        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails()) {
            $status  = 'error';
            $message = 'Fill all required fields.'; 
        } else {
            
            $otp           = $request->input('otp', 0);
            $mobile_otp    = $request->input('mobile_otp', 0);
            $country_code  = $request->input('country_code', '');
            $mobile_number = $request->input('mobile_number', '');
            $current_time  = date("H:i:s");
            $user          = $this->UserModel->where('id',$user_id)->first();

            if (isset($user) && count($user)>0) {

                if (($user->otp != $otp) && ($user->mobile_otp != $mobile_otp)) {
                    $status   = 'error';
                    $message  = 'Invalid OTP';
                } else if (($otp != 0) && ($user->otp != $otp)) {
                    $status   = 'error';
                    $message  = 'Invalid email OTP';
                } else if (($mobile_otp != 0) && ($user->mobile_otp != $mobile_otp)) {
                    $status   = 'error';
                    $message  = 'Invalid mobile OTP';
                } else {

                    if ($verify_type == 'email' || $verify_type == 'both') {
                        if ($current_time < date('H:i:s',strtotime($user->otp_expired_time))) {
                            $user_data['is_email_verified'] = "1";
                            $user_data['otp']               = "0";
                            $user_data['status']            = "1"; 
                        } else {
                            $status   = 'otp expired';
                            $message  = 'This OTP is expired';
                            $arr_data = array('user_id' => $user->id);
                        }
                    }

                    if ($verify_type == 'mobile' || $verify_type == 'both') {
                        if($current_time < date('H:i:s',strtotime($user->mobile_otp_expired_time))) {
                            $user_data['is_mobile_verified'] = "1";
                            $user_data['mobile_otp']         = "0";
                            if (isset($mobile_number) && $mobile_number != '') {
                                $user_data['mobile_number'] = $mobile_number;
                            }
                            if (isset($country_code) && $country_code != '') {
                                $user_data['country_code']  = $country_code;
                            }
                        } else {
                            $status   = 'otp expired';
                            $message  = 'This OTP is expired';
                            $arr_data = array('user_id' => $user->id);
                        }
                    }

                    $verify_user = $this->UserModel->where('id', $user_id)->update($user_data);
                    if ($verify_user && ($status != "otp expired")) {
                        $status  = 'success';
                        $message = 'OTP verified successfully';
                    } else if ($status != "otp expired") {
                        $status  = 'error';
                        $message = 'Error occured while OTP verification';
                    }
                }
            } else {
                $status  = 'error';
                $message = 'Invalid OTP';
            }
        }
        return $this->build_response($status, $message, $arr_data);
    }

    public function resend_otp(Request $request)
    {
        $arr_data = [];
        $user_id  = $request->input('user_id');
        $otp_type = $request->input('otp_type');

        $arr_data = $this->UserModel->where('id',$user_id)->first();
        if (isset($arr_data) & count($arr_data)>0) {
            
            $otp_expired_time = date("Y-m-d H:i:s", strtotime('+30 minutes'));

            if (isset($otp_type) && ($otp_type == 'email' || $otp_type == 'both')) {
                $string          = '0123456';
                $string_shuffled = str_shuffle($string);
                $otp             = substr($string_shuffled, 1, 4);

                $arr_up['otp']              = $otp;
                $arr_up['otp_expired_time'] = date("Y-m-d H:i:s", strtotime('+30 minutes'));

                $obj_email_template = $this->EmailTemplateModel->where('id','18')->first();

                if ($obj_email_template) {
                    $arr_built_content  = [
                                            'OTP'          => $otp,
                                            'USER_NAME'    => $arr_data['user_name'],
                                            'PROJECT_NAME' => config('app.project.name')
                                         ];
                    $arr_mail_data                      = [];
                    $arr_mail_data['email_template_id'] = '18';
                    $arr_mail_data['arr_built_content'] = $arr_built_content;
                    $arr_mail_data['user']              = ['email' => $arr_data['email'], 'first_name' => $arr_data['first_name']];
                    $this->EmailService->send_mail($arr_mail_data);
                }
                $this->UserModel->where('id',$user_id)->update($arr_up);
            } 

            if(isset($otp_type) && ($otp_type == 'mobile' || $otp_type == 'both')) {
                $string          = '0123456';
                $string_shuffled = str_shuffle($string);
                $mobile_otp      = substr($string_shuffled, 1, 4);

                $arr_up['mobile_otp'] = $mobile_otp;
                $arr_up['mobile_otp_expired_time'] = date("Y-m-d H:i:s", strtotime('+30 minutes'));
                
                $country_code  = isset($arr_data['country_code']) ? $arr_data['country_code'] : '';
                $mobile_number = isset($arr_data['mobile_number']) ? $arr_data['mobile_number'] : '';

                $arr_sms_data = [];
                $arr_sms_data['msg'] = config('app.project.name').": Hello, An OTP to verify your mobile number is: ".$mobile_otp;
                $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                $this->SMSService->send_SMS($arr_sms_data);

                $status_up = $this->UserModel->where('id',$user_id)->update($arr_up);
            }
            $status  = 'success';
            $message = 'OTP sent successfully';
        } else {
            $status  = 'error';
            $message = 'This user is not registered with us';
        }
        return $this->build_response($status, $message);
    }

    public function forgot_password(Request $request)
    {
        $arr_rules = $user_data = [];
        $user_id = $request->input('user_id');

        $arr_rules['email'] = 'required';
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails()) {
            $status  = 'error';
            $message = 'Fill all required fields.';
        } else {
            
            $obj_user = $this->UserModel->where('email',$request->input('email'))->first();
            if (isset($obj_user) && count($obj_user) > 0) {
                if($obj_user->status == 1 ) {
                    
                    //send password reset link
                    $string                = '123456';
                    $str                   = 'abcdefghijklmnopqrstuvwxyz';
                    $shuffled              = str_shuffle($str);
                    $string_shuffled       = str_shuffle($string);
                    $password_str          = substr($shuffled, 1, 3);
                    $password              = substr($string_shuffled, 1, 4);
                    $randomPassword        = $password_str.'@'.$password;
                    $user_data['password'] = bcrypt($randomPassword);

                    $this->UserModel->where('id',$obj_user->id)->update($user_data);

                    $arr_built_content = [
                                            'USER_NAME'    => $obj_user->first_name,   
                                            'PASSWORD'     => $randomPassword,
                                            'PROJECT_NAME' => config('app.project.name')
                                        ];

                    $arr_mail_data                      = [];
                    $arr_mail_data['email_template_id'] = '19';
                    $arr_mail_data['arr_built_content'] = $arr_built_content;
                    $arr_mail_data['user']              = ['email' => $obj_user->email, 'first_name' => $obj_user->user_name];
                    $email_status = $this->EmailService->send_mail($arr_mail_data);

                    if ($email_status) {
                        $status  = 'success';
                        $message = 'Password sent successfully to your email id.';
                    } else {
                        $status  = 'error';
                        $message = 'invalid email';
                    }
                } else {
                    $status  = 'error';
                    $message = 'Your account blocked by admin';
                }
            } else {
                $status  = 'error';
                $message = 'This email is not registered with us';
            }
            return $this->build_response($status, $message);
        }
    }

    public function terms_and_condition()
    {
        $page_data = $arr_data = [];

        $page_data = DB::table('front_pages')->select('page_description')->where('page_slug','terms-conditions')->first();
        
        if (isset($page_data) && count($page_data)>0) {
            $status  = 'success';
            $message = 'Record get successfully';
            $arr_data['page_description'] = $page_data->page_description;
        } else {
            $status  = 'error';
            $message = 'Record not found';
        }
        return $this->build_response($status, $message, $arr_data);
    }

    public function refund_policy()
    {
        $page_data = $arr_data = [];

        $page_data = DB::table('front_pages')->select('page_description')->where('page_slug','refund-policy')->first();
        if (isset($page_data) && count($page_data)>0) 
        {
            $status  = 'success';
            $message = 'Record get successfully';
            $arr_data['page_description'] = $page_data->page_description;
        }
        else
        {
            $status  = 'error';
            $message = 'Record not found';
        }
        return $this->build_response($status, $message,$arr_data);
    }

    public function fblogin(Request $request)
    {
        $arr_rules = $arr_data = $responce_data = array();
        
        $arr_rules['email']      = "required|email";
        $arr_rules['first_name'] = "required";
        $arr_rules['last_name']  = "required";
        //$arr_rules['fb_token']   = "required";

        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails()) {
            $arr_response = array();
            $status       = "error";
            $message      = "Fill all required fields";
            return $this->build_response($status,$message);
        }

        $arr_data['email']         = $request->input('email');
        $arr_data['first_name']    = $request->input('first_name');
        $arr_data['last_name']     = $request->input('last_name');
        $arr_data['fb_token']      = $request->input('fb_token');
        $arr_data['profile_image'] = $request->input('profile_image', '');

        /* Verify FB Token */
        /*if(verify_fb_token($arr_data['fb_token']))
        {*/
            /* Check if User Exists */
            $obj_user  = $this->UserModel->where('email',$arr_data['email'])->first();                 
            if ($obj_user != null) {
                if($obj_user->status == "0") {
                    $status  = "error";
                    $message = "Your account blocked by admin, Please contact to admin";
                    return $this->build_response($status,$message);
                }

                if ($obj_user->social_login == 'yes') 
                {
                    /*if($obj_user->login_via != "facebook") {
                        $status  = "error";
                        $message = "You are already registered with us. Please login using ".$obj_user->login_via;
                        return $this->build_response($status,$message);
                    }*/
                } /*else {
                    $status  = "error";
                    $message = "You are already registered with us. Please login with those credentials";
                    return $this->build_response($status,$message);
                }*/

                if(strpos($obj_user['profile_image'], 'http') !== false || $obj_user['profile_image'] == null) {
                    $arr_up_data['login_via']     = 'facebook';
                    $arr_up_data['profile_image'] = $arr_data['profile_image'];

                    $this->UserModel->where('id', $obj_user['id'])->update($arr_up_data);
                }

                $this->auth->loginUsingId($obj_user->id);
                /* Init Chat Variables */
                // $this->_init_chat_details($obj_user,'facebook');

                $userToken = $this->JwtUserService->generate_user_jwt_token($obj_user);

                $user_detail['user_token']    = $userToken['user_token'];
                $user_detail['user_id']       = $obj_user['id'];
                $user_detail['email']         = $obj_user['email'];
                $user_detail['address']       = $obj_user['address'];
                $user_detail['country']       = $obj_user['country'];
                $user_detail['state']         = $obj_user['state'];
                $user_detail['city']          = $obj_user['city'];
                $user_detail['first_name']    = $obj_user['first_name'];
                $user_detail['last_name']     = $obj_user['last_name'];
                $user_detail['country_code']  = $obj_user['country_code'];
                $user_detail['mobile_number'] = $obj_user['mobile_number'];
                $user_detail['user_type']     = $obj_user['user_type'];

                if($obj_user['profile_image'] != '') {
                    if(strpos($obj_user['profile_image'], 'http') !== false || $obj_user['profile_image'] == null) {
                        $user_detail['profile_image'] = $obj_user['profile_image'];
                    } else {
                        $user_detail['profile_image'] = $this->profile_image_public_path.$obj_user['profile_image'];
                    }
                } else {
                    $user_detail['profile_image'] = url('/').'/uploads/default-profile.png'; 
                }

                $obj_process_host = HostVerificationModel::where(['user_id' => $obj_user['id']])->where('status', 3)->count();
                if ($obj_process_host > 0) {
                    $user_detail['in_process_host'] = "yes";
                } else {
                    $user_detail['in_process_host'] = "no";
                }

                $responce_data = $user_detail;
                $status        = 'success';
                $message       = 'Login successfully.';
                return $this->build_response($status, $message, $responce_data);
            } else {
                /* Register User */
                $string = '123456';
                $string_shuffled = str_shuffle($string);
                $password = substr($string_shuffled, 1, 4);

                $char_string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';    
                $string_shuffled_char = str_shuffle($char_string);
                $password_char = substr($string_shuffled_char, 1, 3);

                $randomPassword = $password_char.'@'.$password;
                $arr_new_data  = array();

                $arr_new_data['first_name']        = $arr_data['first_name'];
                $arr_new_data['last_name']         = $arr_data['last_name'];
                $arr_new_data['email']             = $arr_data['email'];
                $arr_new_data['user_name']         = $arr_data['email'];
                $arr_new_data['social_login']      = 'yes';
                $arr_new_data['password']          = bcrypt($randomPassword);
                $arr_new_data['profile_image']     = $arr_data['profile_image'];
                $arr_new_data['login_via']         = 'facebook';
                $arr_new_data['is_email_verified'] = 1;
                
                $status = $this->UserModel->create($arr_new_data);
                
                $arr_new_data['plain_text_password'] = $randomPassword;

                $obj_user = $this->UserModel->where('id',$status->id)->first();

                if ($status) {
                    $this->MailchimpService->subscribe($obj_user['email']);
                    $this->auth->loginUsingId($status->id);

                    /* Init Chat Variables */
                    // $this->_init_chat_details($obj_user,'facebook');

                    $userToken = $this->JwtUserService->generate_user_jwt_token($obj_user);
                     
                    $user_detail['user_token']    = $userToken['user_token'];
                    $user_detail['user_id']       = $obj_user['id'];
                    $user_detail['email']         = $obj_user['email'];
                    $user_detail['address']       = $obj_user['address'];
                    $user_detail['country']       = $obj_user['country'];
                    $user_detail['state']         = $obj_user['state'];
                    $user_detail['city']          = $obj_user['city'];
                    $user_detail['first_name']    = $obj_user['first_name'];
                    $user_detail['last_name']     = $obj_user['last_name'];
                    $user_detail['country_code']  = $obj_user['country_code'];
                    $user_detail['mobile_number'] = $obj_user['mobile_number'];
                    $user_detail['user_type']     = $obj_user['user_type'];
                    $user_detail['login_via']     = 'facebook';

                    if ($obj_user['profile_image'] != '') {
                        if(strpos($obj_user['profile_image'], 'http') !== false || $obj_user['profile_image'] == null) {
                            $user_detail['profile_image'] = $obj_user['profile_image'];
                        } else {
                            $user_detail['profile_image'] = $this->profile_image_public_path.$obj_user['profile_image'];
                        }
                    } else {
                        $user_detail['profile_image'] = url('/').'/uploads/default-profile.png';
                    }

                    $obj_process_host = HostVerificationModel::where(['user_id' => $obj_user['id']])->where('status', 3)->count();
                    if ($obj_process_host > 0) {
                        $user_detail['in_process_host'] = "yes";
                    } else {
                        $user_detail['in_process_host'] = "no";
                    }

                    $responce_data = $user_detail;
                    $status        = 'success';
                    $message       = 'Login successfully.';
                    return $this->build_response($status,$message,$responce_data); 
                } else {
                    $status      = "Error";
                    $message     = 'Something went wrong, Please try again.';
                    return $this->build_response($status,$message);
                }  
            }
        /*}
        else
        {
            $status      = "error";
            $message     = 'Invalid facebook token.';
            return $this->build_response($status,$message); 
        }*/
    }

    public function gplogin(Request $request)
    {
        $response_data = $user_detail = $user_name = [];

        $email         = $request->input('email');
        $first_name    = $request->input('name');
        $user_name     = explode(' ',$first_name);
        $profile_image = $request->input('profile_image', '');
        
        $obj_user   = $this->UserModel->where('email',$email)->first();

        if ($obj_user != false) {
            /* Check if User is Verified and Active */
            if($obj_user->status=="0") {
                $status        =  "error";
                $message       =  "Your account blocked by admin, Please contact to admin";
                return $this->build_response($status,$message);
            }

            if ($obj_user->social_login == 'yes') {
                /*if($obj_user->login_via != "gmail") {
                    $status  = "error";
                    $message = "You are already registered with us. Please login using ".$obj_user->login_via;
                    return $this->build_response($status,$message);
                }*/
            } else {
                $status  = "error";
                $message = "You are already registered with us. Please login with those credentials";
                return $this->build_response($status,$message);
            }

            if(strpos($obj_user['profile_image'], 'http') !== false || $obj_user['profile_image'] == null) {
                $arr_up_data['login_via']     = 'gmail';
                $arr_up_data['profile_image'] = $profile_image;

                $this->UserModel->where('id', $obj_user['id'])->update($arr_up_data);
            }

            $this->auth->loginUsingId($obj_user['id']);

            /* Init Chat Variables */
            //$this->_init_chat_details($obj_user,'google');

            $userToken = $this->JwtUserService->generate_user_jwt_token($obj_user);
                     
            $user_detail['user_token']    = $userToken['user_token'];
            $user_detail['user_id']       = $obj_user['id']; 
            $user_detail['email']         = $obj_user['email'];
            $user_detail['address']       = $obj_user['address'];
            $user_detail['country']       = $obj_user['country'];
            $user_detail['state']         = $obj_user['state'];
            $user_detail['city']          = $obj_user['city'];
            $user_detail['first_name']    = $obj_user['first_name'];
            $user_detail['last_name']     = $obj_user['last_name'];
            $user_detail['country_code']  = $obj_user['country_code'];
            $user_detail['mobile_number'] = $obj_user['mobile_number'];
            $user_detail['user_type']     = $obj_user['user_type'];

            if($obj_user['profile_image'] != '') {
                if(strpos($obj_user['profile_image'], 'http') !== false || $obj_user['profile_image'] == null) {
                    $user_detail['profile_image'] = $obj_user['profile_image'];
                } else {
                    $user_detail['profile_image'] = $this->profile_image_public_path.$obj_user['profile_image'];
                }
            } else {
                $user_detail['profile_image'] = url('/').'/uploads/default-profile.png';
            }

            $obj_process_host = HostVerificationModel::where(['user_id' => $obj_user['id']])->where('status', 3)->count();
            if ($obj_process_host > 0) {
                $user_detail['in_process_host'] =  "yes";
            } else {
                $user_detail['in_process_host'] =  "no";
            }

            $response_data      = $user_detail;
            $status             = 'success';
            $message            = 'Login successfully.';
            return $this->build_response($status,$message,$response_data);
        } else {
            $string          = '123456';
            $string_shuffled = str_shuffle($string);
            $password        = substr($string_shuffled, 1, 4);

            $char_string          = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $string_shuffled_char = str_shuffle($char_string);
            $password_char        = substr($string_shuffled_char, 1, 3);

            $randomPassword = $password_char.'@'.$password;
            $arr_new_data   = array();

            $first_name = isset($user_name[0])?$user_name[0]:'';
            $last_name  = isset($user_name[1])?$user_name[1]:'';

            $arr_new_data['first_name']        = $first_name;
            $arr_new_data['last_name']         = $last_name;
            $arr_new_data['email']             = $email;
            $arr_new_data['user_name']         = $email;
            $arr_new_data['social_login']      = 'yes';
            $arr_new_data['password']          = bcrypt($randomPassword);
            $arr_new_data['login_via']         = 'gmail';
            $arr_new_data['profile_image']     = $profile_image;
            $arr_new_data['is_email_verified'] = 1;
            
            $status = $this->UserModel->create($arr_new_data);
            $obj_user = $this->UserModel->where('id',$status->id)->first();

            $arr_new_data['plain_text_password'] = $randomPassword;
            if($status) {
                $this->MailchimpService->subscribe($obj_user['email']);
                $this->auth->loginUsingId($status->id);
                
                /* Init Chat Variables */
                //$this->_init_chat_details($obj_user,'google');

                $userToken = $this->JwtUserService->generate_user_jwt_token($obj_user);

                $user_detail['user_token']    = $userToken['user_token'];
                $user_detail['user_id']       = $obj_user['id']; 
                $user_detail['email']         = $obj_user['email'];
                $user_detail['address']       = $obj_user['address'];
                $user_detail['country']       = $obj_user['country'];
                $user_detail['state']         = $obj_user['state'];
                $user_detail['city']          = $obj_user['city'];
                $user_detail['first_name']    = $obj_user['first_name'];
                $user_detail['last_name']     = $obj_user['last_name'];
                $user_detail['country_code']  = $obj_user['country_code'];
                $user_detail['mobile_number'] = $obj_user['mobile_number'];
                $user_detail['user_type']     = $obj_user['user_type'];

                if ($obj_user['profile_image'] != '') {
                    if(strpos($obj_user['profile_image'], 'http') !== false || $obj_user['profile_image'] == null) {
                        $user_detail['profile_image'] = $obj_user['profile_image'];
                    } else {
                        $user_detail['profile_image'] = $this->profile_image_public_path.$obj_user['profile_image'];
                    }
                } else {
                    $user_detail['profile_image'] = url('/').'/uploads/default-profile.png';
                }

                $obj_process_host = HostVerificationModel::where(['user_id' => $obj_user['id']])->where('status', 3)->count();

                if ($obj_process_host > 0) {
                    $user_detail['in_process_host'] =  "yes";
                } else {
                    $user_detail['in_process_host'] =  "no";
                }

                $response_data = $user_detail;
                $status        = 'success';
                $message       = 'Login successfully.';
                return $this->build_response($status, $message, $response_data);
            } else {
                $status  = "Error";
                $message = 'Something went wrong, Please try again.';
                return $this->build_response($status,$message);
            } 
        }
        echo str_replace('\/','/',json_encode($resp));
    }

    public function twitterlogin(Request $request)
    {
        $response_data = $user_detail = $user_name = [];
        $email         = $request->input('user_id');
        $first_name    = $request->input('name');
        $user_name     = explode(' ',$first_name);
        $profile_image = $request->input('profile_image', '');
        
        $obj_user      = $this->UserModel->where('email',$email)->first();

        if($obj_user != false) {
            /* Check if User is Verified and Active */
            if($obj_user->status == "0") {
                $status  = "error";
                $message = "Your account blocked by admin, Please contact to admin";
                return $this->build_response($status,$message);
            }

            if ($obj_user->social_login == 'yes') {
                /*if($obj_user->login_via != "twitter") {
                    $status  = "error";
                    $message = "You are already registered with us. Please login using ".$obj_user->login_via;
                    return $this->build_response($status,$message);
                }*/
            } else {
                $status  = "error";
                $message = "You are already registered with us. Please login with those credentials";
                return $this->build_response($status,$message);
            }

            if(strpos($obj_user['profile_image'], 'http') !== false || $obj_user['profile_image'] == null) {
                $arr_up_data['login_via']     = 'twitter';
                $arr_up_data['profile_image'] = $profile_image;

                $this->UserModel->where('id', $obj_user['id'])->update($arr_up_data);
            }
            $this->auth->loginUsingId($obj_user['id']);

            /* Init Chat Variables */
            //$this->_init_chat_details($obj_user,'google');

            $userToken = $this->JwtUserService->generate_user_jwt_token($obj_user);
                     
            $user_detail['user_token']    = $userToken['user_token'];
            $user_detail['user_id']       = $obj_user['id']; 
            $user_detail['email']         = $obj_user['email'];
            $user_detail['address']       = $obj_user['address'];
            $user_detail['first_name']    = $obj_user['first_name'];
            $user_detail['last_name']     = $obj_user['last_name'];
            $user_detail['country_code']  = $obj_user['country_code'];
            $user_detail['mobile_number'] = $obj_user['mobile_number'];
            $user_detail['user_type']     = $obj_user['user_type'];

            if($obj_user['profile_image'] != '') {
                if(strpos($obj_user['profile_image'], 'http') !== false || $obj_user['profile_image'] == null) {
                    $user_detail['profile_image'] = $obj_user['profile_image'];
                } else {
                    $user_detail['profile_image'] = $this->profile_image_public_path.$obj_user['profile_image'];
                }
            } else {
                $user_detail['profile_image'] = url('/').'/uploads/default-profile.png';
            }

            $obj_process_host = HostVerificationModel::where(['user_id' => $obj_user['id']])->where('status', 3)->count();
            if ($obj_process_host > 0) {
                $user_detail['in_process_host'] = "yes";
            } else {
                $user_detail['in_process_host'] = "no";
            }

            $response_data = $user_detail;
            $status        = 'success';
            $message       = 'Login successfully.';
            return $this->build_response($status,$message,$response_data);
        } else {
            $string          = '123456';
            $string_shuffled = str_shuffle($string);
            $password        = substr($string_shuffled, 1, 4);

            $char_string          = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $string_shuffled_char = str_shuffle($char_string);
            $password_char        = substr($string_shuffled_char, 1, 3);

            $randomPassword = $password_char.'@'.$password;
            $arr_new_data   = array();

            $first_name = isset($user_name[0]) ? $user_name[0] : '';
            $last_name  = isset($user_name[1]) ? $user_name[1] : '';
            
            $arr_new_data['first_name']        = $first_name;
            $arr_new_data['last_name']         = $last_name;
            $arr_new_data['email']             = $email;
            $arr_new_data['user_name']         = $email;
            $arr_new_data['social_login']      = 'yes';
            $arr_new_data['password']          = bcrypt($randomPassword);
            $arr_new_data['login_via']         = 'twitter';
            $arr_new_data['profile_image']     = $profile_image;
            $arr_new_data['is_email_verified'] = 1;
            
            $status = $this->UserModel->create($arr_new_data);
            $obj_user = $this->UserModel->where('id',$status->id)->first();

            $arr_new_data['plain_text_password'] = $randomPassword;

            if ($status) {
                $this->MailchimpService->subscribe($obj_user['email']);
                $this->auth->loginUsingId($status->id);
                /* Init Chat Variables */
                //$this->_init_chat_details($obj_user,'google');

                $userToken = $this->JwtUserService->generate_user_jwt_token($obj_user);
                     
                $user_detail['user_token']    = $userToken['user_token'];
                $user_detail['user_id']       = $obj_user['id']; 
                $user_detail['email']         = $obj_user['email'];
                $user_detail['address']       = $obj_user['address'];
                $user_detail['first_name']    = $obj_user['first_name'];
                $user_detail['last_name']     = $obj_user['last_name'];
                $user_detail['country_code']  = $obj_user['country_code'];
                $user_detail['mobile_number'] = $obj_user['mobile_number'];
                $user_detail['user_type']     = $obj_user['user_type'];

                if($obj_user['profile_image'] != '') {
                    if(strpos($obj_user['profile_image'], 'http') !== false || $obj_user['profile_image'] == null) {
                        $user_detail['profile_image'] = $obj_user['profile_image'];
                    } else {
                        $user_detail['profile_image'] = $this->profile_image_public_path.$obj_user['profile_image'];
                    }
                } else {
                    $user_detail['profile_image'] = url('/').'/uploads/default-profile.png';
                }
                
                $obj_process_host = HostVerificationModel::where(['user_id' => $obj_user['id']])->where('status', 3)->count();
                if ($obj_process_host > 0) {
                    $user_detail['in_process_host'] = "yes";
                } else {
                    $user_detail['in_process_host'] = "no";
                }

                $response_data = $user_detail;
                $status        = 'success';
                $message       = 'Login successfully.';
                return $this->build_response($status,$message,$response_data);
            } else {
                $status  = "Error";
                $message = 'Something went wrong, Please try again.';
                return $this->build_response($status,$message);
            } 
        }
        echo str_replace('\/','/',json_encode($resp));
    }
}
