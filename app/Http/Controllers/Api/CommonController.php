<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Common\Services\ListingService;
use App\Common\Services\EmailService;
use App\Common\Services\SMSService;
use App\Common\Services\JwtUserService;
use App\Common\Services\NotificationService;
use App\Common\Services\PropertyRateCalculatorService;

use App\Models\PropertyUnavailabilityModel;
use App\Models\SupportQueryCommentModel;
use App\Models\FavouritePropertyModel;
use App\Models\NotificationModel;
use App\Models\SupportQueryModel;
use App\Models\ReviewRatingModel;
use App\Models\PropertytypeModel;
use App\Models\AmenitiesModel;
use App\Models\PropertyModel;
use App\Models\UserModel;
use App\Models\BookingModel;

use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Image;
use Validator;
use DB;

class CommonController extends Controller
{
    public function __construct(
                                    NotificationModel           $notification_model,
                                    JwtUserService              $jwt_user_service,
                                    UserModel                   $user_model,
                                    SupportQueryModel           $support_query_model,
                                    ListingService              $listing_service,
                                    PropertyModel               $property_model,
                                    PropertyUnavailabilityModel $property_unavailibitity_model,
                                    ReviewRatingModel           $review_rating_model,
                                    AmenitiesModel              $AmenitiesModel,
                                    SMSService                  $sms_service,
                                    EmailService                $email_service,
                                    SupportQueryCommentModel    $support_query_comment_model,
                                    NotificationService         $notification_service,
                                    BookingModel                $booking_model,
                                    PropertyRateCalculatorService $property_rate_calculator_service
                                )
    {
        $this->EmailService                  = $email_service;
        $this->SMSService                    = $sms_service;
        $this->JwtUserService                = $jwt_user_service;
        $this->UserModel                     = $user_model;
        $this->PropertyModel                 = $property_model;
        $this->NotificationModel             = $notification_model;
        $this->SupportQueryModel             = $support_query_model;
        $this->ListingService                = $listing_service;
        $this->ReviewRatingModel             = $review_rating_model;
        $this->AmenitiesModel                = $AmenitiesModel;
        $this->PropertyUnavailabilityModel   = $property_unavailibitity_model;
        $this->SupportQueryCommentModel      = $support_query_comment_model;
        $this->NotificationService           = $notification_service;
        $this->BookingModel                  = $booking_model;
        $this->PropertyRateCalculatorService = $property_rate_calculator_service;

        $this->profile_image_public_path     = url('/').config('app.project.img_path.user_profile_images');
        $this->profile_image_base_path       = base_path().config('app.project.img_path.user_profile_images');
        $this->profile_image_public_path     = url('/').config('app.project.img_path.user_profile_images');
        $this->admin_image_public_path       = url('/').config('app.project.img_path.admin_profile_images');
        $this->admin_image_base_path         = public_path().config('app.project.img_path.admin_profile_images');
        $this->property_image_public_path    = url('/').config('app.project.img_path.property_image');
        $this->property_image_base_path      = base_path().config('app.project.img_path.property_image');

        $this->support_image_public_path     = url('/').config('app.project.img_path.support_profile_images');
        $this->support_image_base_path       = public_path().config('app.project.img_path.support_profile_images');
        
        $this->user_id                       = validate_user_jwt_token();
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            $this->user_id = '';
        }

        if(isset($user) && count($user) > 0) {
            $this->user_id            = $user->id;
            $this->user_first_name    = $user->first_name;
            $this->user_last_name     = $user->last_name;
            $this->user_user_name     = $user->user_name;
            $this->user_display_name  = $user->display_name;
            $this->user_profile_image = $user->profile_image;
        } else {
            $this->user_id            = 0;
            $this->user_first_name    = '';
            $this->user_last_name     = '';
            $this->user_user_name     = '';
            $this->user_display_name  = '';
            $this->user_profile_image = '';
        }
    }

    /*public function generate_token()
    {
        $user_arr = UserModel::where('id', 9)->first();
        if(null === $user_arr)
            return $this->build_response('error', "User doesn't exist", []);
        $user_details = $this->JwtUserService->generate_user_jwt_token($user_arr);
        return $this->build_response('success', "User details fetched successfully", $user_details);
    }

    public function auth_token()
    {
        $userid = validate_user_jwt_token();
        $arr_select = ['user_name', 'email', 'display_name', 'last_name', 'mobile_number', 'gender', 'birth_date', 'address', 'city', 'profile_image', 'status'];
        $user_arr = UserModel::select($arr_select)->where('id', $userid)->first();

        if(null === $user_arr)
            return $this->build_response('error', "Unable to fetch user details");
        else
            return $this->build_response('success', "User details fetched successfully", $user_arr);
    }*/

    public function profile(Request $request)
    {
        $user_detail = [];
        $user_id     = validate_user_jwt_token();

        $arr_user_data  = $this->UserModel->where('id',$user_id)->first();

        if (isset($user_id) && $user_id !='') {
            if (isset($arr_user_data) && count($arr_user_data)>0) {
                $user_detail['user_detail']       = $arr_user_data['id'];
                $user_detail['first_name']        = $arr_user_data['first_name'];
                $user_detail['last_name']         = $arr_user_data['last_name'];
                $user_detail['display_name']      = $arr_user_data['display_name'];
                $user_detail['user_name']         = $arr_user_data['user_name'];
                $user_detail['email']             = $arr_user_data['email'];
                $user_detail['country_code']      = $arr_user_data['country_code'];
                $user_detail['mobile_number']     = $arr_user_data['mobile_number'];
                $user_detail['address']           = $arr_user_data['address'];
                $user_detail['country']           = $arr_user_data['country'];
                $user_detail['state']             = $arr_user_data['state'];
                $user_detail['city']              = $arr_user_data['city'];
                $user_detail['notification_type'] = $arr_user_data['notification_type'];
                $user_detail['gender']            = $arr_user_data['gender'];
                $user_detail['user_type']         = $arr_user_data['user_type'];
                $user_detail['profile_image']     = $arr_user_data['profile_image'];
                $user_detail['gstin']             = $arr_user_data['gstin'];

                if(isset($arr_user_data['birth_date']) && $arr_user_data['birth_date'] != "" && $arr_user_data['birth_date'] != '0000-00-00') {
                    $user_detail['birth_date'] = date('m/d/Y',strtotime($arr_user_data['birth_date']));
                }
                else {
                    $user_detail['birth_date'] = '';
                }

                if($arr_user_data['profile_image'] != '') {
                    if(strpos($arr_user_data['profile_image'], 'http') !== false || $arr_user_data['profile_image'] == null) {
                        $user_detail['profile_image'] = $arr_user_data['profile_image'];
                    } else {
                        $user_detail['profile_image'] = $this->profile_image_public_path.$arr_user_data['profile_image'];
                    }
                } else {
                  $user_detail['profile_image'] = url('/').'/uploads/default-profile.png';
                }

                $status  = 'success';
                $message = 'Record get successfully.';
            } else {
                $status  = 'error';
                $message = 'Record not found.';
            }
        } else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }

        $arr_data = $user_detail ;
        return $this->build_response($status,$message,$arr_data);
    }

    public function update_profile(Request $request)
    {
        $arr_data  = [];
        $filename  = '';
        $user_id   = validate_user_jwt_token();

        if (isset($user_id) && $user_id !='') {
            $arr_rules['first_name']    = "required";
            $arr_rules['last_name']     = "required";
            $arr_rules['email']         = "required";
            $arr_rules['country_code']  = "required";
            $arr_rules['mobile_number'] = "required";
            $arr_rules['address']       = "required";
            $arr_rules['city']          = "required";
            $arr_rules['birth_date']    = "required";
            $arr_rules['gender']        = "required";
            $arr_rules['display_name']  = "required";

            /*$arr_rules['state']         = "required";
            $arr_rules['country']       = "required";
            $arr_rules['user_name']     = "required";
            $arr_rules['profile_image'] = "required";*/

            $validator = Validator::make($request->all(),$arr_rules);

            if ($validator->fails()) {
                $status = 'error';
                $message = 'Fill all required fields';
            } else {
                
                $mobile_number            = trim($request->input('mobile_number', ''));
                $arr_data['first_name']   = trim($request->input('first_name'));
                $arr_data['last_name']    = trim($request->input('last_name'));
                $arr_data['email']        = trim($request->input('email'));
                $arr_data['address']      = trim($request->input('address'));
                $arr_data['city']         = trim($request->input('city'));
                $arr_data['state']        = trim($request->input('state'));
                $arr_data['country']      = trim($request->input('country'));
                $arr_data['birth_date']   = date('Y-m-d',strtotime($request->input('birth_date')));
                $arr_data['display_name'] = trim($request->input('display_name'));
                $arr_data['gstin']        = trim($request->input('gstin'));

                if($request->input('gender') != '') {
                    $arr_data['gender'] = trim($request->input('gender'));
                }

                $is_email_exits = $this->UserModel->where('email', $arr_data['email'])->where('id','<>',$user_id)->count();

                if ($is_email_exits == 0) {
                   /* $is_username_exits   = $this->UserModel->where('user_name',$arr_data['user_name'])->where('id','!=',$user_id)->count();

                    if($is_username_exits == 0)
                    {*/
                        $is_mobileno_exits = $this->UserModel->where('mobile_number',$mobile_number)->where('id','!=',$user_id)->count();
                        if ($is_mobileno_exits == 0) {
                            $obj_user  = $this->UserModel->where('id',$user_id)->first();
                            $obj_user = $obj_user->toArray();
                            $old_image = isset($obj_user['profile_image']) ? $obj_user['profile_image']:'';

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
                                    $status  = 'error';
                                    $message = 'Invalid image,Please select valid image.';
                                }
                            } else {
                                $filename = $old_image;
                            }

                            $arr_data['profile_image'] = $filename;
                            $is_exist = $this->UserModel->where('id',$user_id)->count();
                            
                            if ($is_exist) {
                                $obj_data = $this->UserModel->where('id',$user_id)->update($arr_data);
                                $status  = 'success';
                                $message = "Profile updated successfully";

                                if ($obj_data) {
                                    
                                    // Check if email changed
                                    if ($request->input('email') != $obj_user['email']) {

                                        $string          = '0123456';
                                        $string_shuffled = str_shuffle($string);
                                        $otp             = substr($string_shuffled, 1, 4);

                                        $verification_token             = md5(uniqid(rand(), true));
                                        $arr_user['is_email_verified']  = '0';
                                        $arr_user['verification_token'] = $verification_token;
                                        $arr_user['otp']                = $otp;
                                        $arr_user['otp_expired_time']   = date("Y-m-d H:i:s", strtotime('+30 minutes'));
                                            
                                        $this->UserModel->where('id',$user_id)->update($arr_user);
                                        // dd($obj_data);
                                        $arr_built_content  = [
                                                                'OTP'          => $otp,
                                                                'USER_NAME'    => $obj_user['user_name'],
                                                                'PROJECT_NAME' => config('app.project.name')
                                                            ];
                                        $arr_mail_data                      = [];
                                        $arr_mail_data['email_template_id'] = '18';
                                        $arr_mail_data['arr_built_content'] = $arr_built_content;
                                        $arr_mail_data['user']              = [
                                                                                'email'      => $request->input('email'),
                                                                                'first_name' => $obj_user['user_name']
                                                                            ];

                                        $email_status = $this->EmailService->send_mail($arr_mail_data);
                                        // dd($email_status);
                                        $status  = 'email_not_verified';
                                        $message = "Your email has changed successfully, please verify your email and login";
                                    }

                                    if ($mobile_number != $obj_user['mobile_number']) {
                                        $mobile_string          = '0123456';
                                        $mobile_string_shuffled = str_shuffle($mobile_string);
                                        $mobile_otp             = substr($mobile_string_shuffled, 1, 4);

                                        $arr_sms_data                  = [];
                                        $arr_sms_data['msg']           = config('app.project.name').": Hello, An OTP to verify your mobile number is: ".$mobile_otp;
                                        $arr_sms_data['mobile_number'] = isset($country_code) ? $country_code : ''.isset($mobile_number) ? $mobile_number : '';
                                        $this->SMSService->send_SMS($arr_sms_data);

                                        $arr_user_otp['mobile_otp']              = $mobile_otp;
                                        $arr_user_otp['new_mobile_number']       = $mobile_number;
                                        $arr_user_otp['is_mobile_verified']      = '0';
                                        $arr_user_otp['mobile_otp_expired_time'] = date("Y-m-d H:i:s", strtotime('+30 minutes'));
                                        //$arr_user['is_mobile_verified']      = '0';
                                        $this->UserModel->where('id',$user_id)->update($arr_user_otp);
                                        
                                        if ($status == 'email_not_verified') {
                                            $status  = 'both_not_verified';
                                            $message = "Your mobile number and email has changed successfully, please verify and login";
                                        } else {
                                            $status  = 'mobile_not_verified';
                                            $message = "Your mobile number has changed successfully, please verify your email and login";
                                        }
                                    }
                                    return $this->build_response($status, $message);
                                } else {
                                    return $this->build_response('error', "Problem occurred while updating profile image");
                                }
                            } else {
                                return $this->build_response('error', "Invalid user");
                            }
                        } else {
                            return $this->build_response('error', "Mobile number is already exists");
                        }
                    /*}else{
                        $status  = 'error';
                        $message = 'User Name Is Already Exists.';
                    }*/

                } else {
                    $status  = 'error';
                    $message = 'Email Is Already Exists.';
                } 
            }
        } else {
            return $this->build_response('error', "Token expired, user not found");
        }

       return $this->build_response($status,$message);
    }

    public function update_notification_setting(Request $request)
    {
        $user_id = validate_user_jwt_token();
        $arr_data['notification_by_email'] = trim($request->input('notification_by_email'));
        $arr_data['notification_by_sms']   = trim($request->input('notification_by_sms'));
        $arr_data['notification_by_push']  = trim($request->input('notification_by_push'));
        $obj_data = $this->UserModel->where('id',$user_id)->update($arr_data);
        
        if($obj_data) {
            $status  = 'success';
            $message = 'Your notification settings changed successfully.';
        }
        else {
            $status  = 'error';
            $message = 'Problem occured, while changing settings.';
        }
        return $this->build_response($status,$message);
    }

    public function change_password(Request $request)
    {
        $arr_rules = array();
        $status    = FALSE;
        $user_id   = validate_user_jwt_token();

        if(isset($user_id) && $user_id != '') {
            $arr_rules['old_password']     = "required";
            $arr_rules['new_password']     = "required";
            $arr_rules['confirm_password'] = "required|same:new_password";

            $validator = Validator::make($request->all(),$arr_rules);

            if($validator->fails()) {
                $status = 'error';
                $message = 'Fill all required fields';
            } else {
                $old_password     = $request->input('old_password');
                $new_password     = $request->input('new_password');
                $confirm_password = $request->input('confirm_password');

                $obj_user = $this->UserModel->where('id',$user_id)->first();
                if(\Hash::check($old_password,$obj_user['password'])) {
                    if($old_password != $new_password) {
                        if($new_password == $confirm_password) {
                            $user_password = bcrypt($new_password);
                            $status        = $this->UserModel->where('id',$user_id)->update(['password'=>$user_password]);

                            if($status) {
                                $status  = 'success';
                                $message = 'Your password changed successfully.';
                            } else {
                                $status  = 'error';
                                $message = 'Problem occured, while changing password.';
                            }
                        } else {
                            $status  = 'error';
                            $message = 'Please enter the same value again.';
                        }
                    } else {
                        $status  = 'error';
                        $message = 'Sorry you not use current password as new password, Please enter another new password.';
                    }
                } else {
                    $status  = 'error';
                    $message = 'Incorrect old password.';
                }
            }
        } else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }

        return $this->build_response($status,$message);
    }

    public function notification_listing(Request $request)
    {
        $notify_arr = $arr_data = $notification_settings = [];
        $user_id    = validate_user_jwt_token();

        if (isset($user_id) && $user_id !='') {
            $user_type = $request->input('user_type');
            $notification_settings = $this->UserModel->selectRaw('notification_by_email,notification_by_sms,notification_by_push')->where('id',$user_id)->first()->toArray();

            $arr_data['notification_settings']['notification_by_email'] = ($notification_settings['notification_by_email'] != '') ? $notification_settings['notification_by_email'] : 'off';
            $arr_data['notification_settings']['notification_by_sms']   = ($notification_settings['notification_by_sms'] != '') ? $notification_settings['notification_by_sms'] : 'off';
            $arr_data['notification_settings']['notification_by_push']  = ($notification_settings['notification_by_push'] != '') ? $notification_settings['notification_by_push'] : 'off';

            $obj_notification = $this->NotificationModel->where('receiver_id', $user_id)->where('receiver_type',$user_type)->orderBy('id','desc')->paginate(10);

            if (count($obj_notification) > 0) {
                $status  = 'success';
                $message = 'Records get successfully.';
                $arr_pagination = $obj_notification->toArray();

                $arr_data['total']         = $arr_pagination['total'];
                $arr_data['per_page']      = $arr_pagination['per_page'];
                $arr_data['current_page']  = $arr_pagination['current_page'];
                $arr_data['last_page']     = $arr_pagination['last_page'];
                $arr_data['next_page_url'] = $arr_pagination['next_page_url'];
                $arr_data['prev_page_url'] = $arr_pagination['prev_page_url'];
                $arr_data['from']          = $arr_pagination['from'];
                $arr_data['to']            = $arr_pagination['to'];

                if (isset($arr_pagination) && count($arr_pagination) > 0) {

                    foreach ($arr_pagination['data'] as $key => $value) {
                        if ($value['sender_type'] == '1' || $value['sender_type'] == '4') {
                            $notify_arr[$key]['profile_image'] = get_profile_image('UserModel', $value['sender_id'], $this->profile_image_public_path, $this->profile_image_base_path);
                        }

                        if ($value['sender_type'] == '2') {
                            $notify_arr[$key]['profile_image'] = get_profile_image('AdminModel', $value['sender_id'], $this->admin_image_public_path, $this->admin_image_base_path);
                        }

                        if ($value['sender_type'] == '3') {
                            $notify_arr[$key]['profile_image'] = get_profile_image('SupportTeamModel', $value['sender_id'], $this->support_image_public_path, $this->support_image_base_path);
                        }

                        $notify_arr[$key]['id']                = $value['id'];
                        $notify_arr[$key]['sender_id']         = $value['sender_id'];
                        $notify_arr[$key]['receiver_id']       = $value['receiver_id'];
                        $notify_arr[$key]['notification_text'] = $value['notification_text'];
                        $notify_arr[$key]['is_read']           = $value['is_read'];
                        $notify_arr[$key]['sender_type']       = $value['sender_type'];
                        $notify_arr[$key]['receiver_type']     = $value['receiver_type'];
                        $notify_arr[$key]['created_at']        = $value['created_at'];
                        $notify_arr[$key]['url']               = $value['url'];
                    }
                }
            } else {
                $status  = 'error';
                $message = 'No record found.';
            }
            $arr_data['notification_data'] = $notify_arr;
        } else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }
        return $this->build_response($status,$message,$arr_data);
    }

    public function delete_notification(Request $request)
    {
        $notification_id = explode(',', $request->input('id'));
        if (isset($notification_id) && count($notification_id)>0) {
            foreach ($notification_id as $key => $value) {
                $notification_exist = $this->NotificationModel->where('id',$value)->count();
                if($notification_exist > 0) {
                    $is_delete = $this->NotificationModel->where('id',$value)->delete();
                }
            }
            if($is_delete) {
                $status  = 'success';
                $message = 'Notification deleted successfully.';
            }
            else {
                $status  = 'error';
                $message = 'Problem occured while notification deletion.';
            }
        }
        else {
            $status  = 'error';
            $message = 'Record not found.';
        }
        return $this->build_response($status,$message);
    }

    public function read_notification(Request $request)
    {
        $notification_id = $request->input('id');

        $res = $this->NotificationModel->where('id',$notification_id)->update(['is_read'=>1]);
        if ($res) {
            $status  = 'success';
            $message = 'Notification read successfully';
            return $this->build_response($status,$message);
        }
        else {
            $status  = 'error';
            $message = 'Error occured while reading notification';
            return $this->build_response($status,$message);
        }
    }

    public function my_query(Request $request)
    {
        $user_id   = validate_user_jwt_token();
        $arr_query = $json_data = [];

        if(isset($user_id) && $user_id != '') {
            $user_type = $request->input('user_type');
            $obj_query = $this->SupportQueryModel->with('query_type_details')->where('user_id',$user_id)->where('user_type',$user_type)->paginate('10');

            $arr_query = $obj_query->toArray();

            if(isset($arr_query) && count($arr_query)>0) {
                $status  = 'success';
                $message = 'Records get successfully.';

                $json_data['total']         = $arr_query['total'];
                $json_data['per_page']      = $arr_query['per_page'];
                $json_data['current_page']  = $arr_query['current_page'];
                $json_data['last_page']     = $arr_query['last_page'];
                $json_data['next_page_url'] = $arr_query['next_page_url'];
                $json_data['prev_page_url'] = $arr_query['prev_page_url'];
                $json_data['from']          = $arr_query['from'];
                $json_data['to']            = $arr_query['to'];

                foreach ($arr_query['data'] as $key => $value) {
                    $my_query_data[$key]['id']                 = $value['id'];
                    $my_query_data[$key]['query_type_details'] = $value['query_type_details'];
                    $my_query_data[$key]['query_subject']      = $value['query_subject'];
                    $my_query_data[$key]['created_at']         = $value['created_at'];
                    $my_query_data[$key]['support_level']      = $value['support_level'];
                    $my_query_data[$key]['status']             = $value['status'];
                }
                $json_data['query_data'] = $my_query_data;
            }
            else {
                $status  = 'error';
                $message = 'Records not found.';
            }
        }
        else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }
        return $this->build_response($status,$message,$json_data);
    }

    public function get_property_type()
    {
        $arr_property_type = PropertytypeModel::where('status','=','1')->select('id','name','status')->get();

        if (isset($arr_property_type) && count($arr_property_type)>0) {
            $status            = 'success';
            $message           = 'Records get successfully.';
            $arr_property_type = $arr_property_type->toArray();
            foreach ($arr_property_type as $key => $value) {
                $property_type[0]['id']          = 0;
                $property_type[0]['name']        = 'Featured';
                $property_type[0]['status']      = '1';
                $property_type[$key+1]['id']     = $value['id'];
                $property_type[$key+1]['name']   = $value['name'];
                $property_type[$key+1]['status'] = $value['status'];
            }
            $arr_data['property_type'] = $property_type;
            return $this->build_response($status,$message,$arr_data);
        }
        else {
            $status  = 'error';
            $message = 'Records not found.';
            return $this->build_response($status,$message);
        }
    }

    public function get_all_property_type()
    {
        $arr_property_type = PropertytypeModel::where('status','=','1')->select('id','name','status')->get();

        if (isset($arr_property_type) && count($arr_property_type)>0) {
            $status             = 'success';
            $message            = 'Records get successfully.';
            $arr_property_type  = $arr_property_type->toArray();
            foreach ($arr_property_type as $key => $value) {
                $property_type[$key]['id']     = $value['id'];
                $property_type[$key]['name']   = $value['name'];
                $property_type[$key]['status'] = $value['status'];
            }
            $arr_data['property_type'] = $property_type;
            return $this->build_response($status,$message,$arr_data);
        }
        else {
            $status  = 'error';
            $message = 'Records not found.';
            return $this->build_response($status,$message);
        }
    }

    public function get_amenities()
    {
        $obj_aminities = $this->AmenitiesModel->where('status','1')->get();
        if(isset($obj_aminities) && count($obj_aminities)>0) {
            $arr_aminities = $obj_aminities->toArray();
            foreach ($arr_aminities as $key => $value) {
                $aminities_data[$key]['id']              = $value['id'];
                $aminities_data[$key]['propertytype_id'] = $value['propertytype_id'];
                $aminities_data[$key]['aminity_name']    = $value['aminity_name'];
                $aminities_data[$key]['slug']            = $value['slug'];
            }

            $status                     = 'success';
            $message                    = 'Records get successfully.';
            $arr_data['aminities_data'] = $aminities_data;
            return $this->build_response($status,$message,$arr_data);
        }
        else {
            $status  = 'error';
            $message = 'Record not found.';
            return $this->build_response($status,$message);
        }
    }

    public function home_listing(Request $request)
    {
        $user_favourites_arr = $obj_data = $top_property = $property_data = $obj_data_location = [];
        $cnt_data_location = $cnt_property_state = $cnt_property_city = $price = 0;
        $location = $country = $state = $city = '';
        $user_id = validate_user_jwt_token();

        if (trim($request->input('location')) == '' ) {
            return $this->build_response("location_missing", "Please select location");
        }

        if (isset($user_id) && $user_id !='') {
            $obj_user_favourites = FavouritePropertyModel::select('user_id', 'property_id')->where('user_id', '=', $user_id)->get();
            if (isset($obj_user_favourites) && count($obj_user_favourites) > 0) {
                $user_favourites_arr = $obj_user_favourites->toArray();
            }
        }

        $user_currency = $request->input('user_currency', 'INR');
        $property_type = $request->input('property_type_id');
        $location      = $request->input('location');
        $country       = $request->input('country');
        $state         = $request->input('state');
        $city          = $request->input('city');

        $obj_data = DB::table('property as P')
                        ->select('P.id',
                            'P.property_type_id',
                            'P.property_name',
                            'P.address',
                            'P.country',
                            'P.state',
                            'P.city',
                            'P.property_name_slug',
                            'P.number_of_guest',
                            'P.number_of_bedrooms',
                            'P.number_of_beds',
                            'P.price_per_night',
                            'P.price_per_sqft',
                            'P.price_per_office',
                            'P.price_per',
                            'P.currency',
                            'P.currency_code',
                            'P.admin_status',
                            'P.property_longitude',
                            'P.property_latitude',
                            'P.total_build_area',
                            'P.total_plot_area',
                            'P.admin_area',
                            'P.employee',
                            'P.room',
                            'P.desk',
                            'P.cubicles',
                            'P.no_of_employee',
                            'P.no_of_room',
                            'P.no_of_desk',
                            'P.no_of_cubicles',
                            'P.room_price',
                            'P.desk_price',
                            'P.cubicles_price',
                            'property_images.image as property_image')
                        ->join('property_images','property_images.property_id','=','P.id')
                        ->where('admin_status','2')
                        ->orderByRaw("RAND()");
                        //->orderBy(DB::raw('RAND()'));

        if (isset($property_type) && $property_type != '' && $property_type == 0) {
            $obj_data = $obj_data->where('P.is_featured','yes');
        }

        if (isset($property_type) && $property_type != '' && $property_type != 0) {
            $obj_data = $obj_data->where('P.property_type_id',$property_type);
        }

        if(isset($city) && $city != '') {
            $obj_data = $obj_data->whereRaw("P.city LIKE '%".$city."%'");
        }
        elseif(isset($state) && $state != '') {
            $obj_data = $obj_data->whereRaw("P.state LIKE '".$state."%'");
        }
        elseif(isset($country) && $country != ""){
            $obj_data = $obj_data->whereRaw("P.country LIKE '".$country."%'");
        }
        elseif(isset($location) && $location != "") {
            $obj_data = $obj_data->whereRaw("P.address LIKE '".$location."%'");
        }

        $obj_data = $obj_data->groupBy('P.id')->orderBy('P.id','DESC')->limit(6)->get();

        if (isset($obj_data) && count($obj_data)>0) {

            foreach ($obj_data as $key => $row) {
                $property_data[$key]['is_favourite'] = '0';
                if(isset($user_favourites_arr) && count($user_favourites_arr) > 0) {
                    foreach ($user_favourites_arr as $user_favourites) {
                        if ($user_favourites['property_id'] == $row->id) {
                            $property_data[$key]['is_favourite'] = '1';
                        }
                    }
                }

                $property_type_slug = get_property_type_slug($row->property_type_id);
                if($property_type_slug == 'warehouse') {
                    $converted_price = currencyConverterAPI($row->currency_code, $user_currency, $row->price_per_sqft);
                    $price = $row->price_per_sqft;
                }
                else if($property_type_slug == 'office-space') {
                    $converted_price = currencyConverterAPI($row->currency_code,$user_currency,$row->price_per_office);
                    $price = $row->price_per_office;
                }
                else {
                    $converted_price = currencyConverterAPI($row->currency_code, $user_currency, $row->price_per_night);
                    $price = $row->price_per_night;
                }

                $property_full_name = isset($row->property_name) ? $row->property_name : '';
                $property_type_name = ucfirst(str_replace('-', ' ', $property_type_slug));
                $property_full_name = $property_full_name.' - '.$property_type_name;

                $property_data[$key]['property_id']        = $row->id;
                $property_data[$key]['property_type_id']   = $row->property_type_id;
                $property_data[$key]['property_type_slug'] = $property_type_slug;
                $property_data[$key]['property_name_slug'] = $row->property_name_slug;
                $property_data[$key]['property_name']      = $property_full_name;
                $property_data[$key]['address']            = $row->address;
                $property_data[$key]['number_of_guest']    = $row->number_of_guest;
                $property_data[$key]['number_of_bedrooms'] = $row->number_of_bedrooms;
                $property_data[$key]['number_of_beds']     = $row->number_of_beds;
                $property_data[$key]['price_per_night']    = $price;
                $property_data[$key]['price_per']          = $row->price_per;
                $property_data[$key]['currency']           = $row->currency;
                $property_data[$key]['total_build_area']   = $row->total_build_area;
                $property_data[$key]['total_plot_area']    = $row->total_plot_area;
                $property_data[$key]['admin_area']         = $row->admin_area;

                $property_data[$key]['employee']           = $row->employee;
                $property_data[$key]['room']               = $row->room;
                $property_data[$key]['desk']               = $row->desk;
                $property_data[$key]['cubicles']           = $row->cubicles;
                $property_data[$key]['no_of_employee']     = $row->no_of_employee;
                $property_data[$key]['no_of_room']         = $row->no_of_room;
                $property_data[$key]['no_of_desk']         = $row->no_of_desk;
                $property_data[$key]['no_of_cubicles']     = $row->no_of_cubicles;
                $property_data[$key]['room_price']         = $row->room_price;
                $property_data[$key]['desk_price']         = $row->desk_price;
                $property_data[$key]['cubicles_price']     = $row->cubicles_price;
                
                $user_currency_data = get_currency_detail($user_currency);
                $property_data[$key]['user_currency']      = $user_currency_data['currency'];
                $property_data[$key]['currency']           = $user_currency_data['currency'];
                $property_data[$key]['converted_price']    = number_format($converted_price,2);
                $property_data[$key]['property_longitude'] = $row->property_longitude;
                $property_data[$key]['property_latitude']  = $row->property_latitude;

                if (isset($row->property_image) && $row->property_image != '') {
                    $property_data[$key]['property_image'] = $this->property_image_public_path.$row->property_image;
                } else {
                    $property_data[$key]['property_image'] = url('/').'/front/images/Listing-page-no-image.jpg';
                }

                /*Get Property review average*/
                $reviw_avg = $this->ReviewRatingModel->where('property_id',$row->id)->where('status', '1')->get();

                $avg = 0;
                foreach ($reviw_avg as $rev) {
                    $avg += $rev['rating'];
                }

                if ($avg > 0) {
                    $avg = round($avg/count($reviw_avg),1);
                }

                $property_data[$key]['review_avg']   = $avg;
                $property_data[$key]['no_of_review'] = count($reviw_avg);
            }


            /*Get top destination property */
            if ($property_type == '' || $property_type == 0) {
                $top_dest_obj = DB:: table('booking as B')
                                        ->select('B.property_id',
                                                'P.id',
                                                'P.property_type_id',
                                                'P.property_name',
                                                'P.property_name_slug',
                                                'P.number_of_guest',
                                                'P.number_of_bedrooms',
                                                'P.number_of_beds',
                                                'P.price_per_night',
                                                'P.price_per',
                                                'P.currency',
                                                'P.property_longitude',
                                                'P.property_latitude',
                                                'P.total_build_area',
                                                'P.total_plot_area',
                                                'P.admin_area',
                                                'P.employee',
                                                'P.room',
                                                'P.desk',
                                                'P.cubicles',
                                                'P.no_of_employee',
                                                'P.no_of_room',
                                                'P.no_of_desk',
                                                'P.no_of_cubicles',
                                                'P.room_price',
                                                'P.desk_price',
                                                'P.cubicles_price',
                                                'property_images.image as property_image',
                                                DB::raw("COUNT('B.property_id') AS property_count")
                                            )
                                        ->join('property as P','P.id','=','B.property_id')
                                        ->join('property_images','property_images.property_id','=','P.id')
                                        ->where('admin_status','2')
                                        ->orderBy('property_count','DESC')
                                        ->groupBy('B.property_id')
                                        ->limit(6)
                                        ->get();

                if (isset($top_dest_obj) && count($top_dest_obj) > 0) {
                   foreach ($top_dest_obj as $key1 => $value) {
                        $top_property[$key1]['property_id']        = $value->id;
                        $top_property[$key1]['property_type_id']   = $value->property_type_id;
                        $top_property[$key1]['property_type_slug'] = get_property_type_slug($value->property_type_id);
                        $top_property[$key1]['property_name_slug'] = $value->property_name_slug;
                        $top_property[$key1]['property_name']      = $value->property_name;
                        $top_property[$key1]['number_of_guest']    = $value->number_of_guest;
                        $top_property[$key1]['number_of_bedrooms'] = $value->number_of_bedrooms;
                        $top_property[$key1]['number_of_beds']     = $value->number_of_beds;
                        $top_property[$key1]['price_per_night']    = $value->price_per_night;
                        $top_property[$key1]['currency']           = $value->currency;
                        $top_property[$key1]['property_latitude']  = $value->property_latitude;
                        $top_property[$key1]['property_longitude'] = $value->property_longitude;
                        $top_property[$key1]['total_build_area']   = $value->total_build_area;
                        $top_property[$key1]['total_plot_area']    = $value->total_plot_area;
                        $top_property[$key1]['admin_area']         = $value->admin_area;
                        $top_property[$key1]['price_per']          = $value->price_per;

                        $top_property[$key1]['employee']           = $value->employee;
                        $top_property[$key1]['room']               = $value->room;
                        $top_property[$key1]['desk']               = $value->desk;
                        $top_property[$key1]['cubicles']           = $value->cubicles;
                        $top_property[$key1]['no_of_employee']     = $value->no_of_employee;
                        $top_property[$key1]['no_of_room']         = $value->no_of_room;
                        $top_property[$key1]['no_of_desk']         = $value->no_of_desk;
                        $top_property[$key1]['no_of_cubicles']     = $value->no_of_cubicles;
                        $top_property[$key1]['room_price']         = $value->room_price;
                        $top_property[$key1]['desk_price']         = $value->desk_price;
                        $top_property[$key1]['cubicles_price']     = $value->cubicles_price;

                        if(isset($value->property_image) && $value->property_image!='') {
                            $top_property[$key1]['property_image']  = $this->property_image_public_path.$value->property_image;
                        } else {
                            $top_property[$key1]['property_image']  = url('/').'/front/images/Listing-page-no-image.jpg';
                        }

                        /*Get Property review average*/
                        $reviw_avg = $this->ReviewRatingModel->where('property_id',$value->id)->where('status', '1')->get();

                        $avg = 0;
                        foreach ($reviw_avg as $rev) {
                          $avg += $rev['rating'];
                        }

                        if ($avg > 0) {
                            $avg =  round($avg/count($reviw_avg),1);
                        }

                        $top_property[$key1]['review_avg']   = $avg;
                        $top_property[$key1]['no_of_review'] = count($reviw_avg);
                   }
                }
            }

            $status                    = 'success';
            $message                   = 'Records get successfully.';
            $arr_data['property_data'] = $property_data;
            $arr_data['top_property']  = $top_property;

            return $this->build_response($status,$message,$arr_data);
        } else {
            $status  = 'error';
            $message = 'Records not found.';
            return $this->build_response($status,$message);
        }
    }

    public function view_all_listing(Request $request)
    {
        $fav_arr = $obj_data = $arr_pagination = $arr_data = $property_data = $obj_data_location = $user_favourites_arr = [];
        $location_data_exist = $price = $city_data_exist = $state_data_exist = 0;

        $user_id       = validate_user_jwt_token();
        $property_type = $request->input('property_type_id');
        $location      = $request->input('location');
        $country       = $request->input('country');
        $state         = $request->input('state');
        $city          = $request->input('city');
        $is_featured   = $request->input('is_featured');
        $user_currency = $request->input('user_currency', 'INR');

        if (isset($user_id) && $user_id != '') {
            $obj_user_favourites = FavouritePropertyModel::select('user_id', 'property_id')->where('user_id', '=', $user_id)->get();
            if (isset($obj_user_favourites) && count($obj_user_favourites) > 0) {
                $user_favourites_arr = $obj_user_favourites->toArray();
            }
        }

        $obj_data = DB::table('property as P')
                            ->select('P.id',
                                    'P.property_type_id',
                                    'P.property_name',
                                    'P.property_name_slug',
                                    'P.number_of_guest',
                                    'P.number_of_bedrooms',
                                    'P.number_of_beds',
                                    'P.price_per_night',
                                    'P.price_per_sqft',
                                    'P.price_per_office',
                                    'P.price_per',
                                    'P.currency',
                                    'P.currency_code',
                                    'P.admin_status',
                                    'P.address',
                                    'P.property_latitude',
                                    'P.property_longitude',
                                    'P.total_build_area',
                                    'P.total_plot_area',
                                    'P.admin_area',
                                    'P.employee',
                                    'P.room',
                                    'P.desk',
                                    'P.cubicles',
                                    'P.no_of_employee',
                                    'P.no_of_room',
                                    'P.no_of_desk',
                                    'P.no_of_cubicles',
                                    'P.room_price',
                                    'P.desk_price',
                                    'P.cubicles_price',
                                    'PI.image as property_image'
                                 )
                            ->join('property_images AS PI', 'PI.property_id', '=', 'P.id')
                            ->where('admin_status', '=', "2");

        if (isset($property_type) && $property_type != '' && $property_type != 0) {
            $obj_data = $obj_data->where('P.property_type_id', '=',$property_type);
        }

        if (!$location_data_exist && (isset($city) && $city != '')) {
            $obj_city_data   = clone $obj_data;
            $city_data_exist = $obj_city_data->whereRaw("P.city LIKE '%".$city."%'")->count();

            if ($city_data_exist) {
                $obj_data = $obj_data->whereRaw("P.city LIKE '%".$city."%'");
            }
        }

        if (!$location_data_exist && !$city_data_exist && (isset($state) && $state != '')) {
            $obj_state_data   = clone $obj_data;
            $state_data_exist = $obj_state_data->whereRaw("P.state LIKE '%".$state."%'")->count();

            if ($state_data_exist) {
                $obj_data = $obj_data->whereRaw("P.state LIKE '%".$state."%'");
            }
        }

        if (!$location_data_exist && !$city_data_exist && !$state_data_exist && (isset($country) && $country != '')) {
            $obj_country_data   = clone $obj_data;
            $country_data_exist = $obj_country_data->whereRaw("P.country LIKE '%".$country."%'")->count();

            if ($country_data_exist) {
                $obj_data = $obj_data->whereRaw("P.country LIKE '%".$country."%'");
            }
        }

        if (isset($location) && $location != '') {
            $obj_location_data = clone $obj_data;
            $location_data_exist = $obj_location_data->whereRaw("(P.address LIKE '%".$location."%')")->count();
            if ($location_data_exist) {
                $obj_data = $obj_data->whereRaw("(P.address LIKE '%".$location."%')");
            }
        }

        if(isset($is_featured) && $is_featured == 'yes') {
            $obj_data = $obj_data->where("P.is_featured",'yes');
        }
        
        $obj_data = $obj_data->orderBy('P.id', 'DESC')->groupBy('P.id')->paginate(6);
        if (isset($obj_data) && count($obj_data) > 0) {
            $arr_pagination = $obj_data->toArray();

            $arr_data['total']         = $arr_pagination['total'];
            $arr_data['per_page']      = $arr_pagination['per_page'];
            $arr_data['current_page']  = $arr_pagination['current_page'];
            $arr_data['last_page']     = $arr_pagination['last_page'];
            $arr_data['next_page_url'] = $arr_pagination['next_page_url'];
            $arr_data['prev_page_url'] = $arr_pagination['prev_page_url'];
            $arr_data['from']          = $arr_pagination['from'];
            $arr_data['to']            = $arr_pagination['to'];

            if(isset($arr_pagination['data']) && count($arr_pagination['data'])) {

                foreach ($arr_pagination['data'] as $key => $row) {
                    $property_data[$key]['is_favourite'] = '0';

                    if(isset($user_favourites_arr) && count($user_favourites_arr) > 0) {
                        foreach ($user_favourites_arr as $user_favourites) {
                            if(!in_array($user_favourites['property_id'], $fav_arr)) {
                                array_push($fav_arr, $user_favourites['property_id']);
                            }

                            if ($user_favourites['property_id'] == $row->id) {
                                $property_data[$key]['is_favourite'] = '1';
                            }
                        }
                    }

                    if(in_array($row->id,$fav_arr)){
                        $property_data[$key]['is_favourite'] = '1';
                    }

                     $property_type_slug = get_property_type_slug($row->property_type_id);
                    if($property_type_slug == 'warehouse') {
                        $converted_price = currencyConverterAPI($row->currency_code, $user_currency, $row->price_per_sqft);
                        $price           = $row->price_per_sqft;
                    } else if($property_type_slug == 'office-space') {
                        $converted_price = currencyConverterAPI($row->currency_code,$user_currency,$row->price_per_office);
                        $price           = $row->price_per_office;
                    } else {
                        $converted_price = currencyConverterAPI($row->currency_code, $user_currency, $row->price_per_night);
                        $price           = $row->price_per_night;
                    }

                    $property_full_name = isset($row->property_name) ? $row->property_name : '';
                    $property_type_name = ucfirst(str_replace('-', ' ', $property_type_slug));
                    $property_full_name = $property_full_name.' - '.$property_type_name;

                    $property_data[$key]['property_id']        = $row->id;
                    $property_data[$key]['property_type_id']   = $row->property_type_id;
                    $property_data[$key]['property_type_slug'] = get_property_type_slug($row->property_type_id);
                    $property_data[$key]['property_name_slug'] = $row->property_name_slug;
                    $property_data[$key]['property_name']      = $property_full_name;
                    $property_data[$key]['number_of_guest']    = $row->number_of_guest;
                    $property_data[$key]['number_of_bedrooms'] = $row->number_of_bedrooms;
                    $property_data[$key]['number_of_beds']     = $row->number_of_beds;
                    $property_data[$key]['price_per_night']    = $price;
                    $property_data[$key]['price_per']          = $row->price_per;
                    $property_data[$key]['currency']           = $row->currency;
                    $property_data[$key]['currency_code']      = $row->currency_code;
                    $property_data[$key]['total_build_area']   = $row->total_build_area;
                    $property_data[$key]['total_plot_area']    = $row->total_plot_area;
                    $property_data[$key]['admin_area']         = $row->admin_area;
                    $property_data[$key]['converted_price']    = number_format($converted_price,2);

                    $property_data[$key]['employee']           = $row->employee;
                    $property_data[$key]['room']               = $row->room;
                    $property_data[$key]['desk']               = $row->desk;
                    $property_data[$key]['cubicles']           = $row->cubicles;
                    $property_data[$key]['no_of_employee']     = $row->no_of_employee;
                    $property_data[$key]['no_of_room']         = $row->no_of_room;
                    $property_data[$key]['no_of_desk']         = $row->no_of_desk;
                    $property_data[$key]['no_of_cubicles']     = $row->no_of_cubicles;
                    $property_data[$key]['room_price']         = $row->room_price;
                    $property_data[$key]['desk_price']         = $row->desk_price;
                    $property_data[$key]['cubicles_price']     = $row->cubicles_price;

                    $user_currency_data = get_currency_detail($user_currency);
                    $property_data[$key]['user_currency']      = $user_currency_data['currency'];
                    $property_data[$key]['currency']           = $user_currency_data['currency'];

                    $property_data[$key]['address']            = $row->address;
                    $property_data[$key]['property_latitude']  = $row->property_latitude;
                    $property_data[$key]['property_longitude'] = $row->property_longitude;
                    
                    if(isset($row->property_image) && $row->property_image!='') {
                        $property_data[$key]['property_image'] = $this->property_image_public_path.$row->property_image;
                    } else {
                        $property_data[$key]['property_image'] = url('/').'/front/images/Listing-page-no-image.jpg';
                    }

                    /*Get Property review average*/
                    $reviw_avg = $this->ReviewRatingModel->where('property_id',$row->id)->where('status', '1')->get();
                    $avg = 0;
                    foreach($reviw_avg as $rev) {
                      $avg += $rev['rating'];
                    }

                    if ($avg > 0) {
                        $avg = round($avg/count($reviw_avg),1);
                    }

                    $property_data[$key]['review_avg']   = $avg;
                    $property_data[$key]['no_of_review'] = count($reviw_avg);
                }

                $arr_data['property_data'] = $property_data;
                return $this->build_response('success', "Records get successfully", $arr_data);
            } else {
                return $this->build_response('error', "Records not found");
            }
        } else {
            return $this->build_response('error', "Records not found");
        }
    }

    public function property_listing(Request $request)
    {
        $property_data = $amenities_arr = $fav_arr = $arr_data = $get_session_data = $val = [];
        $enc_property_id = $property_id = $guests = '';
        $property_max_value = $price = 0;
        
        $checkin       = $request->input('checkin', null);
        $checkout      = $request->input('checkout', null);

        $location      = $request->input('location', null);
        $guests        = $request->input('guests', null);

        $city          = $request->input('city', null);
        $state         = $request->input('state', null);
        $country       = $request->input('country', null);
        $postal_code   = $request->input('postal_code', null);

        $latitude      = $request->input('latitude', null);
        $longitude     = $request->input('longitude', null);

        $price_max     = $request->input('price_max', null);
        $price_min     = $request->input('price_min', null);
        $min_bedrooms  = $request->input('min_bedrooms', null);
        $min_bathrooms = $request->input('min_bathrooms', null);
        $room_category = $request->input('room_category', null);
        $reviews       = $request->input('cmb_rating', null);
        $amenities     = $request->input('aminities', null);
        $amenities     = json_decode($amenities);
        
        /*Change by kavita*/    
        $property_type           = $request->input('property_type', null);
        $property_working_status = $request->input('property_working_status', null);
        $price_per               = $request->input('price_per', null);
        $no_of_employee          = $request->input('no_of_employee', null);
        $build_type              = $request->input('build_type', null);
        $available_area          = $request->input('available_area', null);
        $user_currency           = $request->input('user_currency', 'INR');
        //End       
        
        $user_id = validate_user_jwt_token();
        if (isset($user_id) && $user_id !='') {
            $obj_user_favourites = FavouritePropertyModel::select('user_id', 'property_id')->where('user_id', '=', $user_id)->get();
            if (isset($obj_user_favourites) && count($obj_user_favourites) > 0) {
                $user_favourites_arr = $obj_user_favourites->toArray();
            }
        }

        if (count($amenities) > 0) {
            foreach ($amenities as $amen) {
                array_push($amenities_arr, $amen->aminities);
            }
        }
        
        $keyword = $user_id = null;
        $arr_property = $this->ListingService->get_property_listing_api($property_type,$checkin,$checkout,$location ,$guests, $city, $state, $country, $postal_code, $latitude, $longitude, $price_max, $price_min, $min_bedrooms, $min_bathrooms, $room_category, $reviews,$amenities_arr,$keyword,$user_id,$property_working_status,$price_per,$no_of_employee,$build_type,$available_area);
        
        if (isset($arr_property) && count($arr_property) > 0) {
            $arr_pagination = $arr_property['property_list'];

            if(count($arr_pagination) > 0) {
                foreach ($arr_pagination as $key => $row) {
                    $property_data[$key]['is_favourite'] = '0';

                    if(isset($user_favourites_arr) && count($user_favourites_arr) > 0) {
                        foreach ($user_favourites_arr as $user_favourites) {
                            if(!in_array($user_favourites['property_id'], $fav_arr)) {
                                array_push($fav_arr, $user_favourites['property_id']);
                            }
                            if ($user_favourites['property_id'] == $row->id) {
                                $property_data[$key]['is_favourite'] = '1';
                            }
                        }
                    }

                    if(in_array($row->id,$fav_arr)){
                        $property_data[$key]['is_favourite'] = '1';
                    }

                    $property_type_slug = get_property_type_slug($row->property_type_id);
                    if($property_type_slug == 'warehouse') {
                        $converted_price = currencyConverterAPI($row->currency_code, $user_currency, $row->price_per_sqft);
                        $price = $row->price_per_sqft;
                    } else if($property_type_slug == 'office-space') {
                        $converted_price = currencyConverterAPI($row->currency_code,$user_currency,$row->price_per_office);
                        $price = $row->price_per_office;
                    } else {
                        $converted_price = currencyConverterAPI($row->currency_code, $user_currency, $row->price_per_night);
                        $price = $row->price_per_night;
                    }

                    $property_data[$key]['property_id']        = $row->id;
                    $property_data[$key]['property_type_id']   = $row->property_type_id;
                    $property_data[$key]['property_type_slug'] = $property_type_slug;
                    $property_data[$key]['property_name_slug'] = $row->property_name_slug;
                    $property_data[$key]['property_name']      = $row->property_name;
                    $property_data[$key]['number_of_guest']    = $row->number_of_guest;
                    $property_data[$key]['number_of_bedrooms'] = $row->number_of_bedrooms;
                    $property_data[$key]['number_of_beds']     = $row->number_of_beds;
                    $property_data[$key]['price_per_night']    = $price;
                    $property_data[$key]['price_per']          = $row->price_per;
                    $property_data[$key]['currency']           = $row->currency;
                    $property_data[$key]['address']            = $row->address;
                    $property_data[$key]['property_latitude']  = $row->property_latitude;
                    $property_data[$key]['property_longitude'] = $row->property_longitude;
                    $property_data[$key]['total_build_area']   = $row->total_build_area;
                    $property_data[$key]['total_plot_area']    = $row->total_plot_area;
                    $property_data[$key]['admin_area']         = $row->admin_area;
                    $property_data[$key]['converted_price']    = number_format($converted_price,2);
                    $user_currency_data                        = get_currency_detail($user_currency);
                    $property_data[$key]['currency']           = $user_currency_data['currency'];
                    $property_data[$key]['user_currency']      = $user_currency_data['currency'];

                    $property_data[$key]['employee']           = $row->employee;
                    $property_data[$key]['room']               = $row->room;
                    $property_data[$key]['desk']               = $row->desk;
                    $property_data[$key]['cubicles']           = $row->cubicles;
                    $property_data[$key]['no_of_employee']     = $row->no_of_employee;
                    $property_data[$key]['no_of_room']         = $row->no_of_room;
                    $property_data[$key]['no_of_desk']         = $row->no_of_desk;
                    $property_data[$key]['no_of_cubicles']     = $row->no_of_cubicles;
                    $property_data[$key]['room_price']         = $row->room_price;
                    $property_data[$key]['desk_price']         = $row->desk_price;
                    $property_data[$key]['cubicles_price']     = $row->cubicles_price;

                    if (isset($row->property_image) && $row->property_image!='') {
                        $property_data[$key]['property_image'] = $this->property_image_public_path.$row->property_image;
                    } else {
                        $property_data[$key]['property_image'] = url('/').'/front/images/Listing-page-no-image.jpg';
                    }

                    /*Get Property review average*/
                    $reviw_avg = $this->ReviewRatingModel->where('property_id',$row->id)->where('status', '1')->get();
                    $avg = 0;
                    
                    foreach ($reviw_avg as $rev) {
                      $avg += $rev['rating'];
                    }

                    if($avg > 0) {
                        $avg = round($avg/count($reviw_avg),1);
                    }

                    $property_data[$key]['review_avg']   = $avg;
                    $property_data[$key]['no_of_review'] = count($reviw_avg);
                }

                $status                    = 'success';
                $message                   = 'Records get successfully.';
                $arr_data['property_data'] = $property_data;
            } else {
                $status  = 'error';
                $message = 'Records not found.';
            }
        } else {
            $status  = 'error';
            $message = 'Records not found.';
        }
        return $this->build_response($status, $message, $arr_data);
    }

    public function property_details(Request $request)
    {
        $status = $message = $arr_property = $arr_property_calander_dates = $get_property_beds_arrangment = $temp_data = $obj_review_rating = $obj_property_review = $property_details = $review_rating_details = $arr_aminities = $beds_arrangement = $property_rules = $property_images = $dates_data = [];

        if (!$this->user_id) {
            return $this->build_response('Error',"Invalid user");
        }

        $user_currency         = $request->input('user_currency', 'INR');
        $review_table          = $this->ReviewRatingModel->getTable();
        $user_table            = $this->UserModel->getTable();
        $prefixed_review_table = \DB::getTablePrefix().$this->ReviewRatingModel->getTable();
        $prefixed_user_table   = \DB::getTablePrefix().$this->UserModel->getTable();
        $obj_property_data     = $this->PropertyModel->where('property_name_slug',$request->input('slug'))->first();

        if(isset($obj_property_data) && count($obj_property_data)>0) {

            $is_user_favourite = FavouritePropertyModel::select('user_id', 'property_id')
                                                       ->where('user_id', '=', $this->user_id)
                                                       ->where('property_id', '=', $obj_property_data['id'])
                                                       ->count();

            $property_details['own_property']  = ($this->user_id == $obj_property_data['user_id']) ? 'TRUE' : 'FALSE';
            $property_details['is_favourite']  = $is_user_favourite;
            $property_details['id']            = $obj_property_data['id'];
            $property_details['user_id']       = $obj_property_data['user_id'];
            $property_details['property_name'] = $obj_property_data['property_name'];
            $property_details['description']   = $obj_property_data['description'];
            $property_details['address']       = $obj_property_data['address'];
            $property_details['currency']      = $obj_property_data['currency'];

            $property_type_slug = get_property_type_slug($obj_property_data['property_type_id']);
            if($property_type_slug == 'warehouse') {
                $converted_price = currencyConverterAPI($obj_property_data['currency_code'], $user_currency, $obj_property_data['price_per_sqft']);
                $price = $obj_property_data['price_per_sqft'];
            } else if($property_type_slug == 'office-space') {
                $converted_price = currencyConverterAPI($obj_property_data['currency_code'], $user_currency, $obj_property_data['price_per_office']);
                $price = $obj_property_data['price_per_office'];
            } else {
                $converted_price = currencyConverterAPI($obj_property_data['currency_code'], $user_currency, $obj_property_data['price_per_night']);
                $price = $obj_property_data['price_per_night'];
            }

            /***Changes by kavita***/
            $user_currency_data                           = get_currency_detail($user_currency);
            $property_details['converted_price']          = number_format($converted_price,2);
            $property_details['user_currency']            = $user_currency_data['currency'];
            $property_details['currency']                 = $user_currency_data['currency'];
            $property_details['number_of_guest']          = $obj_property_data['number_of_guest'];
            $property_details['number_of_bedrooms']       = $obj_property_data['number_of_bedrooms'];
            $property_details['number_of_bathrooms']      = $obj_property_data['number_of_bathrooms'];
            $property_details['number_of_beds']           = $obj_property_data['number_of_beds'];
            $property_details['price_per_night']          = $price;
            $property_details['property_latitude']        = $obj_property_data['property_latitude'];
            $property_details['property_longitude']       = $obj_property_data['property_longitude'];
            $property_details['property_type_id']         = $obj_property_data['property_type_id'];
            $property_details['property_type_slug']       = $property_type_slug;
            $property_details['property_working_status']  = $obj_property_data['property_working_status'];
            $property_details['property_area']            = $obj_property_data['property_area'];
            $property_details['total_plot_area']          = $obj_property_data['total_plot_area'];
            $property_details['total_build_area']         = $obj_property_data['total_build_area'];
            $property_details['custom_type']              = $obj_property_data['custom_type'];
            $property_details['management']               = $obj_property_data['management'];
            $property_details['good_storage']             = $obj_property_data['good_storage'];
            $property_details['admin_area']               = $obj_property_data['admin_area'];
            $property_details['build_type']               = $obj_property_data['build_type'];
            $property_details['property_remark']          = $obj_property_data['property_remark'];
            $property_details['price_per_sqft']           = $obj_property_data['price_per_sqft'];
            $property_details['price_per']                = $obj_property_data['price_per'];
            $property_details['price_per_office']         = $obj_property_data['price_per_office'];
            $property_details['no_of_slots']              = $obj_property_data['no_of_slots'];
            $property_details['no_of_employee']           = $obj_property_data['no_of_employee'];
            $property_details['nearest_railway_station']  = $obj_property_data['nearest_railway_station'];
            $property_details['nearest_national_highway'] = $obj_property_data['nearest_national_highway'];
            $property_details['nearest_bus_stop']         = $obj_property_data['nearest_bus_stop'];
            $property_details['working_hours']            = $obj_property_data['working_hours'];
            $property_details['working_days']             = $obj_property_data['working_days'];
            $property_details['available_no_of_slots']    = isset($obj_property_data['available_no_of_slots']) ? $obj_property_data['available_no_of_slots'] : '0';
            $property_details['available_no_of_employee'] = isset($obj_property_data['available_no_of_employee']) ? $obj_property_data['available_no_of_employee'] : '0';

            $property_details['employee']                 = $obj_property_data['employee'];
            $property_details['room']                     = $obj_property_data['room'];
            $property_details['desk']                     = $obj_property_data['desk'];
            $property_details['cubicles']                 = $obj_property_data['cubicles'];
            $property_details['no_of_employee']           = $obj_property_data['no_of_employee'];
            $property_details['no_of_room']               = $obj_property_data['no_of_room'];
            $property_details['no_of_desk']               = $obj_property_data['no_of_desk'];
            $property_details['no_of_cubicles']           = $obj_property_data['no_of_cubicles'];
            $property_details['room_price']               = $obj_property_data['room_price'];
            $property_details['desk_price']               = $obj_property_data['desk_price'];
            $property_details['cubicles_price']           = $obj_property_data['cubicles_price'];
            /***End***/

            $arr_review_rating = \DB::table($prefixed_review_table)
                                    ->select('*',
                                            $prefixed_user_table.".first_name",
                                            $prefixed_user_table.".last_name",
                                            $prefixed_user_table.".profile_image")
                                    ->Join($prefixed_user_table,$prefixed_user_table.".id",'=',$review_table.'.rating_user_id')
                                    ->where($prefixed_review_table.'.status','1')
                                    ->where($prefixed_review_table.'.property_id',$obj_property_data['id'])
                                    ->get();

            $total = $count = 0;
            $tmp_str_rating = '';

            if (isset($arr_review_rating)) {
                foreach ($arr_review_rating as $key => $rating) {
                    $review_rating_details[$key]['first_name']    = $rating->first_name ;
                    $review_rating_details[$key]['last_name']     = $rating->last_name ;
                    $review_rating_details[$key]['profile_image'] = $rating->profile_image ? $this->profile_image_public_path.$rating->profile_image : '';
                    $review_rating_details[$key]['rating']        = $rating->rating ;
                    $review_rating_details[$key]['message']       = $rating->message;
                    $review_rating_details[$key]['review_date']   = date('d M Y h:i A',strtotime($rating->created_at));

                    $total += floatval($rating->rating);
                    $count++;
                }
            }

            if($count != 0) {
                $reviews = number_format(($total/$count),1);
            } else {
                $reviews = 0;
            }

            $arr_rules = $this->ListingService->get_property_rules($obj_property_data['id']);

            $arr_aminities = $this->ListingService->get_property_aminities($obj_property_data['id']);
            $arr_images    = $this->ListingService->get_property_images($obj_property_data['id']);
            if (isset($arr_images)) {
                foreach ($arr_images as $key2 => $images) {
                    $property_images[$key2]['image_name'] = $this->property_image_public_path.$images['image_name'];
                }
            }

            $arr_property_beds_arrangment = $this->ListingService->get_property_beds_arrangment($obj_property_data['id']);
            if (isset($arr_property_beds_arrangment)) {
                foreach ($arr_property_beds_arrangment as $key => $property_beds) {
                    $beds_arrangement[$key]['double_bed'] = $property_beds->double_bed;
                    $beds_arrangement[$key]['single_bed'] = $property_beds->single_bed;
                    $beds_arrangement[$key]['queen_bed']  = $property_beds->queen_bed;
                    $beds_arrangement[$key]['sofa_bed']   = $property_beds->sofa_bed;
                }
            }

            $unavailble_dates = $this->PropertyUnavailabilityModel->where('property_id',$obj_property_data['id'])
                                                                  ->whereRaw("DATE(to_date) > '".date('Y-m-d')."'")
                                                                  ->orderBy('from_date','ASC')->get();
            if($unavailble_dates) {
                $arr_unavailble = $unavailble_dates->toArray();
                foreach($unavailble_dates as $key => $row) {
                    $dates_data[$key]['from_date'] = date("d/m/Y",strtotime($row['from_date']));
                    $dates_data[$key]['to_date']   = date("d/m/Y",strtotime($row['to_date']));
                }
            }

            $property_details['arr_aminities']                = $arr_aminities;
            $property_details['arr_images']                   = $property_images;
            $property_details['unavailble_dates']             = $dates_data;
            $property_details['no_of_reviews']                = $count;
            $property_details['average_rating']               = $reviews;
            $property_details['review_rating_details']        = $review_rating_details;
            $property_details['get_property_beds_arrangment'] = $beds_arrangement;
            $property_details['property_rules']               = $arr_rules;

            $status  = 'success';
            $message = 'Records get successfully';
        } else {
            $status  = 'error';
            $message = 'No record found';
        }
        return $this->build_response($status,$message,$property_details);
    }

    public function get_currency_list()
    {
        if (!isset($this->user_id) && $this->user_id == '') {
            return $this->build_response('error', 'Invalid User');
        } else {
            $currency_arr = currency_list();
            if (isset($currency_arr) && count($currency_arr) > 0) {
                return $this->build_response('success', '', $currency_arr);
            } else {
                return $this->build_response('error', 'No data found');
            }
        }
    }


    public function booking_details(Request $request)
    {
        $data = [];
        $gst_percentage = $total_night_price = $total_service_amount = 0;

        $id = $request->input('id');
        $user_currency = $request->input('user_currency');

        if( isset($id) && !empty($id) ) {
            $arr_booking = [];
            $obj_booking = $this->BookingModel->with(['property_details' => function($query){
                                                    $query->select('id','currency','currency_code','number_of_guest','number_of_bedrooms','number_of_bathrooms','number_of_beds','price_per_night','property_name_slug','property_area','total_plot_area','total_build_area','custom_type','build_type','management','good_storage','admin_area','price_per_sqft','price_per_office','price_per','no_of_slots','available_no_of_slots','room','no_of_room','room_price','desk','no_of_desk','desk_price','cubicles','no_of_cubicles','cubicles_price');
                                              }])
                                              ->select('booking_id','property_id','check_in_date','check_out_date','no_of_guest','no_of_days','property_amount','service_fee','gst_amount','total_night_price','coupen_code_amount','admin_commission','refund_amount','total_amount','booking_status','cancelled_by','cancelled_reason','cancelled_date','property_type_slug','selected_no_of_slots','selected_of_room','selected_of_desk','selected_of_cubicles','room_amount','desk_amount','cubicles_amount')
                                              ->where('id',$id)
                                              ->first();

            if(isset($obj_booking) && $obj_booking != null) {
                $arr_booking = $obj_booking->toArray();

                $number_of_nights     = $arr_booking['no_of_days'];
                $total_night_price    = $arr_booking['property_details']['price_per_night'] * $number_of_nights;
                $total_service_amount = ($arr_booking['gst_amount'] / 100) * $total_night_price;
                $total_amount         = $total_night_price + $total_service_amount;

                // Convert Currency into selected currency
                if( $user_currency != 'INR' ) {
                    $arr_booking['total_night_price']  = currencyConverterAPI('INR', $user_currency, $arr_booking['total_night_price']);
                    $arr_booking['coupen_code_amount'] = currencyConverterAPI('INR', $user_currency, $arr_booking['coupen_code_amount']);
                    $arr_booking['refund_amount']      = currencyConverterAPI('INR', $user_currency, $arr_booking['refund_amount']);
                    $arr_booking['total_amount']       = currencyConverterAPI('INR', $user_currency, $arr_booking['total_amount']);
                    $arr_booking['room_amount']        = currencyConverterAPI('INR', $user_currency, $arr_booking['room_amount']);
                    $arr_booking['desk_amount']        = currencyConverterAPI('INR', $user_currency, $arr_booking['desk_amount']);
                    $arr_booking['cubicles_amount']    = currencyConverterAPI('INR', $user_currency, $arr_booking['cubicles_amount']);

                    $arr_booking['property_details']['price_per_night']  = currencyConverterAPI('INR', $user_currency, $arr_booking['property_details']['price_per_night']);
                    $arr_booking['property_details']['price_per_sqft']   = currencyConverterAPI('INR', $user_currency, $arr_booking['property_details']['price_per_sqft']);
                    $arr_booking['property_details']['price_per_office'] = currencyConverterAPI('INR', $user_currency, $arr_booking['property_details']['price_per_office']);
                    $arr_booking['property_details']['room_price']       = currencyConverterAPI('INR', $user_currency, $arr_booking['property_details']['room_price']);
                    $arr_booking['property_details']['desk_price']       = currencyConverterAPI('INR', $user_currency, $arr_booking['property_details']['desk_price']);
                    $arr_booking['property_details']['cubicles_price']   = currencyConverterAPI('INR', $user_currency, $arr_booking['property_details']['cubicles_price']);
                }

                $arr_booking['paid_amount'] = $arr_booking['total_amount'];
                $arr_booking['total_amount'] = $arr_booking['total_amount'] + $arr_booking['coupen_code_amount'];

                if( $arr_booking['property_type_slug'] == 'warehouse' ) {
                    $gst_percentage = get_gst_data(0, 'warehouse');
                } else if( $arr_booking['property_type_slug'] == 'office-space' ) {
                    $gst_percentage = get_gst_data(0, 'office-space');
                } else {
                    $gst_percentage = get_gst_data($arr_booking['property_details']['price_per_night'], 'other');
                }

            } else {
                $status  = 'error';
                $message = '';
                return $this->build_response($status,$message,$data);
            }

            $site_settings = DB::table('site_settings')->select('admin_commission')->first();
            if( $site_settings ) {
                $service_fee_percentage = $site_settings->admin_commission;
            }

            // Convert Currency into selected currency
            if( $user_currency != 'INR' ) {
                $data['total_night_price']    = currencyConverterAPI('INR', $user_currency, $total_night_price);
                $data['total_service_amount'] = currencyConverterAPI('INR', $user_currency, $total_service_amount);
            } else {
                $data['total_night_price']    = $total_night_price;
                $data['total_service_amount'] = $total_service_amount;
            }

            $data['number_of_nights']       = $number_of_nights;
            $data['service_fee_percentage'] = $service_fee_percentage;
            $data['gst_percentage']         = $gst_percentage;
            $data['arr_booking']            = $arr_booking;

            $status  = 'success';
            $message = '';
        } else {
            $status  = 'error';
            $message = 'Something went wrong!';
        }

        return $this->build_response($status,$message,$data);
    }


    // Database Chat Starts
    public function store_chat(Request $request)
    {
        $arr_comment_data = [
                                'query_id'        => $request->input('query_id'),
                                'comment_by'      => $this->user_id,
                                'user_id'         => $this->user_id,
                                'user_type'       => $request->input('user_type'),
                                'support_user_id' => $request->input('support_user_id'),
                                'is_read'         => 0,
                                'comment'         => $request->input('comment'),
                            ];

        $obj_comment = $this->SupportQueryCommentModel->create($arr_comment_data);


        // Notification To User Starts
        $arr_built_content = array(
                                    'USER_NAME' => $this->user_first_name,
                                    'MESSAGE'   => "User has replied on your message."
                                );

        $arr_notify_data['arr_built_content']  = $arr_built_content;
        $arr_notify_data['notify_template_id'] = '9';
        $arr_notify_data['sender_id']          = $this->user_id;
        $arr_notify_data['sender_type']        = '3';
        $arr_notify_data['receiver_id']        = $request->input('support_user_id');
        $arr_notify_data['receiver_type']      = $request->input('user_type');
        $arr_notify_data['url']                = "/ticket";
        $notification_status = $this->NotificationService->send_notification($arr_notify_data);
        // Notification To User Ends

        $status  = 'success';
        $message = 'message send successfully.';
        return $this->build_response($status, $message);
    }
    
    public function get_current_chat_messages(Request $request)
    {
        $arr_ticket_chats = [];

        $query_id        = (int) $request->input('query_id');
        $user_id         = (int) $this->user_id;
        $user_type       = (int) $request->input('user_type');
        $support_user_id = (int) $request->input('support_user_id');

        $this->read_unread_message($query_id, $user_id, $user_type, $support_user_id);

        $select_query = '';

        $select_query = "SELECT 
                            support_query_comments.id,
                            support_query_comments.query_id,
                            support_query_comments.user_id,
                            support_query_comments.user_type,
                            support_query_comments.support_user_id, 
                            support_query_comments.is_read,
                            support_query_comments.comment,
                            support_query_comments.comment_by,
                            DATE_FORMAT(support_query_comments.created_at,'%d %b, %h:%i %p') as date,
                            support_team.user_name,
                            support_team.first_name,
                            support_team.last_name,
                            support_team.email,
                            support_team.support_level,
                            support_team.contact,
                            support_team.address,
                            support_team.profile_image
                        FROM support_query_comments
                        LEFT JOIN support_team
                        ON support_query_comments.support_user_id = support_team.id
                        WHERE support_query_comments.query_id = $query_id
                        AND support_query_comments.user_id = $user_id
                        AND support_query_comments.user_type = $user_type
                        AND support_query_comments.support_user_id = $support_user_id
                        ORDER BY support_query_comments.id ASC";
        
        $arr_ticket_comments = [];
        if($select_query != '')
        {
            $obj_ticket_comments = \DB::select($select_query);

            if(isset($obj_ticket_comments) && sizeof($obj_ticket_comments) > 0) {
                $arr_ticket_comments = json_decode(json_encode($obj_ticket_comments), true);

                foreach ($arr_ticket_comments as $key => $value) {
                    $arr_ticket_chats[$key]['id']              = $value['id'];
                    $arr_ticket_chats[$key]['query_id']        = $value['query_id'];
                    $arr_ticket_chats[$key]['user_id']         = $value['user_id'];
                    $arr_ticket_chats[$key]['user_type']       = $value['user_type'];
                    $arr_ticket_chats[$key]['support_user_id'] = $value['support_user_id'];
                    $arr_ticket_chats[$key]['is_read']         = $value['is_read'];
                    $arr_ticket_chats[$key]['comment']         = $value['comment'];
                    $arr_ticket_chats[$key]['comment_by']      = $value['comment_by'];
                    $arr_ticket_chats[$key]['date']            = $value['date'];
                    $arr_ticket_chats[$key]['user_name']       = $value['user_name'];
                    $arr_ticket_chats[$key]['first_name']      = $value['first_name'];
                    $arr_ticket_chats[$key]['last_name']       = $value['last_name'];
                    $arr_ticket_chats[$key]['email']           = $value['email'];
                    $arr_ticket_chats[$key]['support_level']   = $value['support_level'];
                    $arr_ticket_chats[$key]['contact']         = $value['contact'];
                    $arr_ticket_chats[$key]['address']         = $value['address'];
                    $arr_ticket_chats[$key]['profile_image']   = $this->support_image_public_path.$value['profile_image'];
                }
            }
        }

        $arr_ticket_comments = json_decode(json_encode($arr_ticket_chats), true);
        
        $comment = [];

        if(isset($arr_ticket_comments) && sizeof($arr_ticket_comments)>0)
        {
            $status          = 'success';
            $message         = 'support chat available';
            $comment['data'] = $arr_ticket_comments;
            return $this->build_response($status, $message, $comment);
        }
        
        $status          = 'error';
        $message         = 'support chat not available';
        $comment['data'] = $arr_ticket_comments;
        
        return $this->build_response($status, $message, $comment);
    }

    public function get_previous_chat($query_id, $user_id, $user_type, $support_user_id)
    {
        $select_query = '';

        if($query_id != '' && $user_id != '' && $user_type != '' && $support_user_id != '')
        {
            $select_query = "SELECT 
                                support_query_comments.id,
                                support_query_comments.query_id,
                                support_query_comments.user_id,
                                support_query_comments.user_type,
                                support_query_comments.support_user_id, 
                                support_query_comments.is_read,
                                support_query_comments.comment,
                                support_query_comments.comment_by,
                                DATE_FORMAT(support_query_comments.created_at,'%d %b, %h:%i %p') as date,
                                support_team.user_name,
                                support_team.first_name,
                                support_team.last_name,
                                support_team.email,
                                support_team.support_level,
                                support_team.contact,
                                support_team.address,
                                support_team.profile_image
                            FROM support_query_comments
                            LEFT JOIN support_team
                            ON support_query_comments.support_user_id = support_team.id
                            WHERE support_query_comments.query_id = $query_id
                            AND support_query_comments.user_id = $user_id
                            AND support_query_comments.user_type = $user_type
                            AND support_query_comments.support_user_id = $support_user_id
                            ORDER BY support_query_comments.id ASC";
            
            $arr_ticket_comments = [];
            if($select_query != '')
            {
                $obj_ticket_comments =  \DB::select($select_query);

                if(isset($obj_ticket_comments) && sizeof($obj_ticket_comments) > 0) {
                    $arr_ticket_comments = json_decode(json_encode($obj_ticket_comments), true);
                }
            }
            return $arr_ticket_comments;
        }
        return [];
    }

    public function read_unread_message($query_id, $user_id, $user_type, $support_user_id)
    {
        if($query_id != '' && $user_id != '' && $user_type != '' && $support_user_id != '')
        {
            return $this->SupportQueryCommentModel->where('query_id', $query_id)
                                                  ->where('user_id', $user_id)
                                                  ->where('user_type', $user_type)
                                                  ->where('support_user_id', $support_user_id)
                                                  ->where('comment_by', $support_user_id)
                                                  ->update(['is_read' => '1']);
        }
        return true;
    }
    // Database Chat Ends

}
