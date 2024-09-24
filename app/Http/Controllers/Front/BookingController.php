<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Common\Services\NotificationService;
use App\Common\Services\EmailService;
use App\Common\Services\MobileAppNotification;
use App\Common\Services\SMSService;
use App\Common\Services\PropertyDatesService;
use App\Common\Services\PropertyRateCalculatorService;
use App\Common\Services\ListingService;

use App\Models\PropertyUnavailabilityModel;
use App\Models\PropertyModel;
use App\Models\BookingModel;
use App\Models\UserModel;
use App\Models\SiteSettingsModel;
use App\Models\CouponModel;
use App\Models\CouponsUsedModel;

use Validator;
use Session;
use DB;

class BookingController extends Controller
{
    public function __construct(
                                    PropertyRateCalculatorService $property_rate_service,
                                    PropertyDatesService          $property_date_service,
                                    ListingService                $listing_service,
                                    EmailService                  $email_service,
                                    SMSService                    $sms_service,
                                    NotificationService           $notification_service,
                                    MobileAppNotification         $mobileappnotification_service,
                                    UserModel                     $user_model,
                                    PropertyModel                 $property_model,
                                    BookingModel                  $booking_model,
                                    PropertyUnavailabilityModel   $unavailability_model,
                                    SiteSettingsModel             $admincommissionmodel,
                                    CouponModel                   $coupon_model,
                                    CouponsUsedModel              $coupon_used_model
                                )
    {
        $this->arr_view_data                 = [];
        $this->module_url_path               = url('/').'/property';
        $this->module_view_folder            = 'front.booking';

        $this->PropertyModel                 = $property_model;
        $this->PropertyDatesService          = $property_date_service;
        $this->PropertyRateCalculatorService = $property_rate_service;
        $this->ListingService                = $listing_service;
        $this->EmailService                  = $email_service;
        $this->SMSService                    = $sms_service;
        $this->UserModel                     = $user_model;
        $this->NotificationService           = $notification_service;
        $this->MobileAppNotification         = $mobileappnotification_service;
        $this->PropertyUnavailabilityModel   = $unavailability_model;
        $this->SiteSettingsModel             = $admincommissionmodel;
        $this->CouponModel                   = $coupon_model;
        $this->CouponsUsedModel              = $coupon_used_model;
        $this->BookingModel                  = $booking_model;

        $this->profile_image_public_img_path = url('/').config('app.project.img_path.user_profile_images');
        $this->profile_image_base_img_path   = public_path().config('app.project.img_path.user_profile_images');
        $this->module_title                  = 'Booking';
        
        $this->auth                          = auth()->guard('users');
        $user                                = $this->auth->user();
        if($user) {
            $this->user_id                   = $user->id;
            $this->user_first_name           = $user->first_name;
            $this->user_last_name            = $user->last_name;
        } else {
            $this->user_id                   = 0;
            $this->user_first_name           = '';
            $this->user_last_name            = '';
        }
    }

    /*
        Rohini j
        Date : 16-march-2018
    */

    public function index()
    {
        $arr_booking = $obj_pagination = $arr_booking_category = $arr_booking_recent = [];

        $user_id     = $this->user_id;
        $obj_booking = $this->BookingModel->with('property_details')->with('user_details');
        $obj_booking = $obj_booking->where('booking_status','=',3)->where('property_owner_id','=',$user_id)->paginate(10);

        if(isset($obj_booking) && $obj_booking != null) {
            $arr_booking    = $obj_booking->toArray();
            $obj_pagination = clone $obj_booking;
        }

        $this->array_view_data['obj_pagination']                = $obj_pagination;
        $this->array_view_data['arr_booking']                   = $arr_booking;
        $this->array_view_data['profile_image_base_img_path']   = public_path().config('app.project.img_path.user_profile_images');
        $this->array_view_data['profile_image_public_img_path'] = url('/').config('app.project.img_path.user_profile_images');
        $this->array_view_data['module_url_path']               = $this->module_url_path;
        $this->array_view_data['page_title']                    = $this->module_title;

        return view($this->module_view_folder.'.booking', $this->array_view_data);
    }

    public function store_booking_details(Request $request)
    {
        $form_data = $arr_user = $arr_property = $arr_property_data = $arr_booking_data = $arr_admin_commission = $arr_coupon = $get_session_data = $json_arr = $val = [];
        $needed_amount = $property_currency = $amount = $available_no_of_slots = $available_no_of_employee = $available_no_of_room = $available_no_of_desk = $available_no_of_cubicles = $no_of_guest = $room_amount = $desk_amount = $cubicles_amount = 0;
        $discount_type = $coupon_id = $discount_rate = $property_id1 = $property_id = $enc_property_id = $enc_property_id1 = $no_of_guest = $coupon_status = $arr_coupon['coupon_use'] = '';
        
        parse_str($_POST['form_data'], $form_data); //This will convert the string to array

        $arr_rules               = array();
        $arr_rules['start_date'] = "required";
        $arr_rules['end_date']   = "required";

        $property_type_slug = isset($form_data['property_type_slug']) ? $form_data['property_type_slug'] : '';

        if ($property_type_slug == 'warehouse') {
            $arr_rules['available_no_of_slots'] = "required";
        }
        else if ($property_type_slug == 'office-space') {
            //$arr_rules['available_no_of_employee'] = "required";
        }   
        else {
            $arr_rules['no_of_guest'] = "required";            
        }

        $validator = Validator::make($form_data, $arr_rules);
        if ($validator->fails()) {
            $json_arr = ['status' => 'error', 'message' => 'Some fields are missing.'];
        }
        
        $enc_property_id          = $form_data['enc_property_id'];
        $property_id              = base64_decode($form_data['enc_property_id']);
        $arr_property_data        = get_property_details($property_id);
        $user_id                  = isset($arr_property_data['user_id']) ? $arr_property_data['user_id'] : '';
        $start_date               = date('Y-m-d',strtotime($form_data['start_date']));
        $end_date                 = date('Y-m-d',strtotime($form_data['end_date']));
        $no_of_guest              = isset($form_data['no_of_guest']) ? $form_data['no_of_guest'] : '';
        $checkin_time             = isset($form_data['checkin_time']) ? $form_data['checkin_time'] : '';
        $coupon_code              = isset($form_data['coupon_code']) ? $form_data['coupon_code'] : '';
        $property_type_slug       = isset($form_data['property_type_slug']) ? $form_data['property_type_slug'] : '';
        $available_no_of_slots    = isset($form_data['available_no_of_slots']) ? $form_data['available_no_of_slots'] : '';
        $available_no_of_employee = isset($form_data['available_no_of_employee']) ? $form_data['available_no_of_employee'] : '';
        $available_no_of_room     = isset($form_data['available_no_of_room']) ? $form_data['available_no_of_room'] : '';
        $available_no_of_desk     = isset($form_data['available_no_of_desk']) ? $form_data['available_no_of_desk'] : '';
        $available_no_of_cubicles = isset($form_data['available_no_of_cubicles']) ? $form_data['available_no_of_cubicles'] : '';

        if (Session::get('BookingRequestData') != null) {
            $get_session_data = Session::get('BookingRequestData');                        
             
            if(isset($get_session_data['property_id']) && $get_session_data['property_id']!='') {
                $property_id = $get_session_data['property_id'];
            }

            if(isset($get_session_data['enc_property_id']) && $get_session_data['enc_property_id']!='') {
                $enc_property_id = $get_session_data['enc_property_id'];
            }

            if(isset($get_session_data['property_type_slug']) && $get_session_data['property_type_slug']!='') {
                $property_type_slug = $get_session_data['property_type_slug'];
            }

            if(isset($get_session_data['available_no_of_slots']) && $get_session_data['available_no_of_slots']!='') {
                $available_no_of_slots = $get_session_data['available_no_of_slots'];
            }

            if(isset($get_session_data['available_no_of_employee']) && $get_session_data['available_no_of_employee']!='') {
                $available_no_of_employee = $get_session_data['available_no_of_employee'];
            }

            if(isset($get_session_data['available_no_of_room']) && $get_session_data['available_no_of_room']!='') {
                $available_no_of_room = $get_session_data['available_no_of_room'];
            }

            if(isset($get_session_data['available_no_of_desk']) && $get_session_data['available_no_of_desk']!='') {
                $available_no_of_desk = $get_session_data['available_no_of_desk'];
            }

            if(isset($get_session_data['available_no_of_cubicles']) && $get_session_data['available_no_of_cubicles']!='') {
                $available_no_of_cubicles = $get_session_data['available_no_of_cubicles'];
            }

            $val = [
                    'checkin'                  => $get_session_data['checkin'],
                    'checkout'                 => $get_session_data['checkout'],
                    'guests'                   => $no_of_guest,
                    'enc_property_id'          => $enc_property_id,
                    'property_id'              => $property_id,
                    'property_type_slug'       => $property_type_slug,
                    'available_no_of_slots'    => $available_no_of_slots,
                    'available_no_of_employee' => $available_no_of_employee,
                    'available_no_of_room'     => $available_no_of_room,
                    'available_no_of_desk'     => $available_no_of_desk,
                    'available_no_of_cubicles' => $available_no_of_cubicles
                ];

            Session::put('BookingRequestData',$val);   
        }        
        
        /*check user is login or not*/
        if ($this->auth->user() == "" ) {
            $json_arr = ['status' => 'error','message' => 'Please login first,Then only you can book a property.'];
        }

        if (Session::get('user_type') == '4') {
            $json_arr = ['status' => 'error','message' => 'Host can not booked a property.'];
        }

        /*check that user is bookied their own property or not*/
        if ($this->user_id == $user_id) {
            $json_arr = ['status' => 'error','message' => 'You can not booked your own property.'];
        }

        /*check user is already booked this property or not*/
        if ($this->PropertyDatesService->check_property_is_booked($property_id, $start_date, $end_date) == TRUE) {
            $json_arr = ['status' => 'error','message' => 'You have already booked this property on selected date.'];
        }

        // coupon code starts
        if (isset($coupon_code) && !empty($coupon_code) && $coupon_code != null) {
            $obj_coupon = $this->CouponModel->where('coupon_code', $coupon_code)
                                            ->where('status', 1)
                                            ->whereRaw('global_expiry >= "'.date("Y-m-d").'"')
                                            ->first();
            if ($obj_coupon) {
                $arr_coupon = $obj_coupon->toArray();
                $coupon_id = $arr_coupon['id'];
            }

            if ($arr_coupon['coupon_use'] == 2 || $arr_coupon['coupon_use'] == 3) {
                $user = $this->auth->user();
                if ($user['is_coupon_used'] == 'no') {
                    $coupon_status = 'apply';
                }
            } else if($arr_coupon['coupon_use'] == 1 || $arr_coupon['coupon_use'] == 3) {
                $coupon_status = 'apply';
            }

            if($coupon_status == 'apply') {
                if ($arr_coupon['discount_type'] == 2) {
                    $discount_type = 'percentage';
                    $discount_rate = $arr_coupon['discount'];
                } else if($arr_coupon['discount_type'] == 1) {
                    $discount_type = 'amount';
                    $discount_rate = $arr_coupon['discount'];
                }
            } else {
                $arr_json['status']  = 'error';
                $arr_json['message'] = 'Coupon code unavailable.';
                return json_encode($arr_json);
            }
        }
        // coupon code ends
        
        $arr_calculation_result = $this->PropertyRateCalculatorService->calculate_rate($start_date,$end_date,$property_id,$discount_type,$discount_rate,$no_of_guest, $property_type_slug, $available_no_of_slots, $available_no_of_employee, $available_no_of_room, $available_no_of_desk, $available_no_of_cubicles);

        $arr_user = $this->UserModel->where('id',$this->user_id)->first();
        if ($arr_user) {

            $session_currency_code = Session::get('get_currency');

            if($arr_calculation_result['total_payble_amount'] != '') {
                if ($session_currency_code != 'INR') {
                    $total_amount        = $arr_calculation_result['total_payble_amount'];
                    $property_pay_amount = currencyConverterAPI($session_currency_code, 'INR', $total_amount);
                } else {
                    $property_pay_amount = $arr_calculation_result['total_payble_amount'];
                }
            }

            $current_wallet_amount = ($arr_user['wallet_amount'] != 0) ? number_format($arr_user['wallet_amount'],2, '.', '') : '0.00';

            $amount = isset($arr_calculation_result['total_payble_amount']) ? $arr_calculation_result['total_payble_amount'] : '0';
            $amount_INR = currencyConverterAPI($session_currency_code, 'INR', $amount);

            if ($arr_user['wallet_amount'] >= 0) {
                $needed_amount_INR  = $arr_user['wallet_amount'] - $amount_INR;
                $payable_amount_INR = $arr_user['wallet_amount'] - $amount_INR;
            }else{
                $payable_amount_INR = floatval($amount_INR);
                $needed_amount_INR  = $amount_INR;
            }

            $service_fee_gst_percentage = isset($arr_calculation_result['service_fee_gst_percentage']) ? $arr_calculation_result['service_fee_gst_percentage'] : '';
            $service_fee_percentage     = isset($arr_calculation_result['service_fee_percentage']) ? $arr_calculation_result['service_fee_percentage'] : '';
            $service_fee_gst_amount     = isset($arr_calculation_result['service_fee_gst_amount']) ? $arr_calculation_result['service_fee_gst_amount'] : '';
            $service_fee                = isset($arr_calculation_result['service_fee']) ? $arr_calculation_result['service_fee'] : '';
            $gst_amount                 = isset($arr_calculation_result['gst_amount']) ? $arr_calculation_result['gst_amount'] : '';
            $gst_percentage             = isset($arr_calculation_result['gst']) ? $arr_calculation_result['gst'] : '';

            $payable_amount = floatval($amount);
            if( $session_currency_code != 'INR' ) {
                $needed_amount = currencyConverterAPI('INR', $session_currency_code, $needed_amount_INR);

                $gst_amount             = currencyConverterAPI($session_currency_code, 'INR', $gst_amount);
                $service_fee            = currencyConverterAPI($session_currency_code, 'INR', $service_fee);
                $service_fee_gst_amount = currencyConverterAPI($session_currency_code, 'INR', $service_fee_gst_amount);
            }
            else if( $session_currency_code == 'INR' ) {
                $needed_amount = $needed_amount_INR;
            }
        }


        if ($property_type_slug == 'warehouse') {
            $property_amount = $arr_calculation_result['price_per_sqft'];
        }
        elseif ($property_type_slug == 'office-space') {
            $property_amount = $arr_calculation_result['price_per_office'];

            $room_price     = $arr_calculation_result['room_price'];
            $desk_price     = $arr_calculation_result['desk_price'];
            $cubicles_price = $arr_calculation_result['cubicles_price'];

            if ($session_currency_code != 'INR') {
                $room_amount     = currencyConverterAPI($session_currency_code, 'INR', $room_price);
                $desk_amount     = currencyConverterAPI($session_currency_code, 'INR', $desk_price);
                $cubicles_amount = currencyConverterAPI($session_currency_code, 'INR', $cubicles_price);

            } else {
                $room_amount     = $room_price;
                $desk_amount     = $desk_price;
                $cubicles_amount = $cubicles_price;
            }
        }
        else {
            $property_amount = $arr_calculation_result['price_per_night'];
        }

        if ($session_currency_code != 'INR') {
            $property_amount = currencyConverterAPI($session_currency_code, 'INR', $property_amount);
        } else {
            $property_amount = $property_amount;
        }

        $discount_price = isset($arr_calculation_result['discount_price']) ? $arr_calculation_result['discount_price'] : '';
        $total_amount   = $property_pay_amount - $discount_price;

        $arr_booking_data['property_booked_by']         = $this->user_id;
        $arr_booking_data['property_id']                = $property_id;
        $arr_booking_data['property_owner_id']          = $user_id;
        $arr_booking_data['coupon_code_id']             = '';
        $arr_booking_data['check_in_date']              = $start_date;
        $arr_booking_data['check_out_date']             = $end_date;
        $arr_booking_data['no_of_guest']                = $no_of_guest;
        $arr_booking_data['no_of_days']                 = isset($arr_calculation_result['number_of_nights']) ? $arr_calculation_result['number_of_nights'] : '';
        $arr_booking_data['property_amount']            = abs(number_format($property_amount, 2, '.', ''));

        $arr_booking_data['gst_percentage']             = $gst_percentage;
        $arr_booking_data['gst_amount']                 = abs(number_format($gst_amount, 2, '.', ''));
        $arr_booking_data['service_fee_gst_percentage'] = $service_fee_gst_percentage;
        $arr_booking_data['service_fee_percentage']     = $service_fee_percentage;
        $arr_booking_data['service_fee_gst_amount']     = $service_fee_gst_amount;
        $arr_booking_data['service_fee']                = abs(number_format($service_fee, 2, '.', ''));
        
        $arr_booking_data['coupen_code_amount']         = abs(number_format($discount_price, 2, '.', ''));
        $arr_booking_data['total_night_price']          = abs(number_format($amount_INR, 2, '.', ''));
        $arr_booking_data['total_amount']               = abs(number_format($total_amount, 2, '.', ''));
        $arr_booking_data['booking_status']             = 3; //awaiting
        $arr_booking_data['property_type_slug']         = isset($arr_calculation_result['property_type_slug']) ? $arr_calculation_result['property_type_slug'] : '';
        $arr_booking_data['selected_no_of_slots']       = isset($arr_calculation_result['available_no_of_slots']) ? $arr_calculation_result['available_no_of_slots'] : '';
        $arr_booking_data['selected_of_employee']       = isset($arr_calculation_result['available_no_of_employee']) ? $arr_calculation_result['available_no_of_employee'] : '';
        $arr_booking_data['selected_of_room']           = isset($arr_calculation_result['available_no_of_room']) ? $arr_calculation_result['available_no_of_room'] : '';
        $arr_booking_data['selected_of_desk']           = isset($arr_calculation_result['available_no_of_desk']) ? $arr_calculation_result['available_no_of_desk'] : '';
        $arr_booking_data['selected_of_cubicles']       = isset($arr_calculation_result['available_no_of_cubicles']) ? $arr_calculation_result['available_no_of_cubicles'] : '';
        $arr_booking_data['room_amount']                = abs(number_format($room_amount, 2, '.', ''));
        $arr_booking_data['desk_amount']                = abs(number_format($desk_amount, 2, '.', ''));
        $arr_booking_data['cubicles_amount']            = abs(number_format($cubicles_amount, 2, '.', ''));

        $arr_condition['property_id']        = $property_id;
        $arr_condition['property_booked_by'] = $this->user_id;
        $arr_condition['check_in_date']      = $start_date;
        $arr_condition['check_out_date']     = $end_date;
        $arr_condition['booking_status']     = 3;

        $arr_user = $this->UserModel->where('id',$this->user_id)->first();
        if ($arr_user) {

            $check_data = $this->BookingModel->where($arr_condition)->first();
            if (count($check_data) == 0) {
                $booking_status  = $this->BookingModel->create($arr_booking_data);
                $last_booking_id = $booking_status->id;
            } else {
                $booking_status  = $this->BookingModel->where('id',$check_data['id'])->update($arr_booking_data);
                $last_booking_id = $check_data['id'];
            }

            if ($booking_status) {
                $booking_id = 'B'.str_pad($last_booking_id, 6, "0", STR_PAD_LEFT);
                $this->BookingModel->where('id','=',$last_booking_id)->update(array('booking_id' => $booking_id));
                
                // Send admin notification starts
                $arr_built_content = array(
                                        'USER_NAME' => $this->user_first_name,
                                        'MESSAGE'   => "Property is booked successfully by ".$this->user_first_name.', booking id is '.$booking_id
                                    );
                
                $arr_notify_data['notification_text']  = $arr_built_content;
                $arr_notify_data['notify_template_id'] = '9';
                $arr_notify_data['template_text']      = "Property is booked successfully by ".$this->user_first_name.', booking id is '.$booking_id;
                $arr_notify_data['sender_id']          = $this->user_id;
                $arr_notify_data['sender_type']        = '1';
                $arr_notify_data['receiver_id']        = '1';
                $arr_notify_data['receiver_type']      = '2';
                $arr_notify_data['url']                = url('/').'/admin/booking/all';
                $notification_status                   = $this->NotificationService->send_notification($arr_notify_data);
                // Send admin notification ends


                $json_arr = [
                        'status'                => 'success',
                        'message'               => 'Property is booked successfully, wait until confirmation from the host.',
                        'amount'                => abs(number_format($amount, 2, '.', '')),
                        'amount_inr'            => abs(number_format($amount_INR, 2, '.', '')) - $arr_calculation_result['discount_price'],
                        'needed_amount'         => abs(number_format($needed_amount, 2, '.', '')),
                        'payable_amount'        => abs(number_format($payable_amount, 2, '.', '')),
                        'needed_amount_inr'     => abs(number_format($needed_amount_INR, 2, '.', '')) - $arr_calculation_result['discount_price'],
                        'payable_amount_INR'    => abs(number_format($payable_amount_INR, 2, '.', '')) - $arr_calculation_result['discount_price'],
                        'booking_id'            => $last_booking_id,
                        'current_wallet_amount' => $current_wallet_amount,
                        'currency_code'         => $session_currency_code,
                        'used_coupon_id'        => $coupon_id
                    ];

            } else {
                $json_arr = [ 'status' => 'error', 'message' => 'Problem occurred while booking property' ];
            }
        }

        return json_encode($json_arr);
    }

    /*
        Rohini j
        15 march 2018
        calculate property rates
    */

    public function calculate_rate_for_selected_dates (Request $request)
    {
        $property_id1 = $property_id = $enc_property_id  = $enc_property_id1 = $discount_type = $discount_rate = '';
        $arr_json     = $arr_coupon  = $get_session_data = $val              = [];

        $enc_property_id          = $request->input('enc_property_id');
        $property_id              = isset($enc_property_id) ? base64_decode($enc_property_id) : '';
        $property_type_slug       = $request->input('property_type_slug');
        $checkin_date             = $request->input('start_date');
        $checkout_date            = $request->input('end_date');
        $coupon_code              = $request->input('coupon_code');
        $guests                   = $request->input('no_of_guest');
        $available_no_of_slots    = $request->input('available_no_of_slots');
        $available_no_of_employee = $request->input('available_no_of_employee');
        $available_no_of_room     = $request->input('available_no_of_room');
        $available_no_of_desk     = $request->input('available_no_of_desk');
        $available_no_of_cubicles = $request->input('available_no_of_cubicles');

        if (Session::get('BookingRequestData') != null) {
            $get_session_data = Session::get('BookingRequestData');                        
              
            if(isset($get_session_data['property_id']) && $get_session_data['property_id']!='' && $property_id!=$get_session_data['property_id']) {
                $property_id1 = $property_id;
            } else {
                $property_id1 = isset($get_session_data['property_id']) && $get_session_data['property_id']!=''?$get_session_data['property_id']:"";
            }
        
            if(isset($get_session_data['enc_property_id']) && $get_session_data['enc_property_id']!='' && $enc_property_id!=$get_session_data['enc_property_id']) {
                $enc_property_id1 = $enc_property_id;
            } else {
                $enc_property_id1 = isset($get_session_data['enc_property_id']) && $get_session_data['enc_property_id']!='' ? $get_session_data['enc_property_id']:"";
            }

            if (Session::get('BookingRequestData.checkin') != $checkin_date) {
                $val = [ 
                        'checkin'                  => $checkin_date,
                        'checkout'                 => $get_session_data['checkout'],
                        'guests'                   => isset($get_session_data['guests']) ? $get_session_data['guests']:"",
                        'enc_property_id'          => $enc_property_id1,
                        'property_id'              => $property_id1,
                        'property_type_slug'       => isset($get_session_data['property_type_slug']) ? $get_session_data['property_type_slug'] : "",
                        'available_no_of_slots'    => isset($get_session_data['available_no_of_slots']) ? $get_session_data['available_no_of_slots'] : "",
                        'available_no_of_employee' => isset($get_session_data['available_no_of_employee']) ? $get_session_data['available_no_of_employee'] : "",
                        'available_no_of_room'     => isset($get_session_data['available_no_of_room']) ? $get_session_data['available_no_of_room'] : "",
                        'available_no_of_desk'     => isset($get_session_data['available_no_of_desk']) ? $get_session_data['available_no_of_desk'] : "",
                        'available_no_of_cubicles' => isset($get_session_data['available_no_of_cubicles']) ? $get_session_data['available_no_of_cubicles'] : ""
                    ];
                Session::put('BookingRequestData',$val);   
            }

            if (Session::get('BookingRequestData.checkout') != $checkout_date) {
                $val = [
                        'checkin'                  => $get_session_data['checkin'],
                        'checkout'                 => $checkout_date,
                        'guests'                   => isset($get_session_data['guests']) ? $get_session_data['guests'] : "",
                        'enc_property_id'          => $enc_property_id1,
                        'property_id'              => $property_id1,
                        'property_type_slug'       => isset($get_session_data['property_type_slug']) ? $get_session_data['property_type_slug'] : "",
                        'available_no_of_slots'    => isset($get_session_data['available_no_of_slots']) ? $get_session_data['available_no_of_slots'] : "",
                        'available_no_of_employee' => isset($get_session_data['available_no_of_employee']) ? $get_session_data['available_no_of_employee'] : "",
                        'available_no_of_room'     => isset($get_session_data['available_no_of_room']) ? $get_session_data['available_no_of_room'] : "",
                        'available_no_of_desk'     => isset($get_session_data['available_no_of_desk']) ? $get_session_data['available_no_of_desk'] : "",
                        'available_no_of_cubicles' => isset($get_session_data['available_no_of_cubicles']) ? $get_session_data['available_no_of_cubicles'] : ""
                    ];
                Session::put('BookingRequestData',$val);
            }

            if ( $get_session_data['checkin'] != $checkin_date && $get_session_data['checkout'] != $checkout_date) {
                $val = [
                        'checkin'                  => $checkin_date,
                        'checkout'                 => $checkout_date,
                        'guests'                   => isset($get_session_data['guests']) ? $get_session_data['guests'] : "",
                        'enc_property_id'          => $enc_property_id1,
                        'property_id'              => $property_id1,
                        'property_type_slug'       => isset($get_session_data['property_type_slug']) ? $get_session_data['property_type_slug'] : "",
                        'available_no_of_slots'    => isset($get_session_data['available_no_of_slots']) ? $get_session_data['available_no_of_slots'] : "",
                        'available_no_of_employee' => isset($get_session_data['available_no_of_employee']) ? $get_session_data['available_no_of_employee'] : "",
                        'available_no_of_room'     => isset($get_session_data['available_no_of_room']) ? $get_session_data['available_no_of_room'] : "",
                        'available_no_of_desk'     => isset($get_session_data['available_no_of_desk']) ? $get_session_data['available_no_of_desk'] : "",
                        'available_no_of_cubicles' => isset($get_session_data['available_no_of_cubicles']) ? $get_session_data['available_no_of_cubicles'] : ""
                    ];

                Session::put('BookingRequestData',$val);
            }
            /*Change by kavita*/
            if (Session::get('BookingRequestData.guests') != $guests) {
                $val = [
                        'checkin'                  => $checkin_date,
                        'checkout'                 => $checkout_date,
                        'guests'                   => $guests,
                        'enc_property_id'          => $enc_property_id1,
                        'property_id'              => $property_id1,
                        'property_type_slug'       => isset($get_session_data['property_type_slug']) ? $get_session_data['property_type_slug'] : "",
                        'available_no_of_slots'    => isset($get_session_data['available_no_of_slots']) ? $get_session_data['available_no_of_slots'] : "",
                        'available_no_of_employee' => isset($get_session_data['available_no_of_employee']) ? $get_session_data['available_no_of_employee'] : "",
                        'available_no_of_room'     => isset($get_session_data['available_no_of_room']) ? $get_session_data['available_no_of_room'] : "",
                        'available_no_of_desk'     => isset($get_session_data['available_no_of_desk']) ? $get_session_data['available_no_of_desk'] : "",
                        'available_no_of_cubicles' => isset($get_session_data['available_no_of_cubicles']) ? $get_session_data['available_no_of_cubicles'] : ""
                    ];
                Session::put('BookingRequestData',$val);   
            }
            if (Session::get('BookingRequestData.property_type_slug') != $property_type_slug) {
                $val = [
                        'checkin'                  => $checkin_date,
                        'checkout'                 => $checkout_date,
                        'guests'                   => $guests,
                        'enc_property_id'          => $enc_property_id1,
                        'property_id'              => $property_id1 ,
                        'property_type_slug'       => $property_type_slug,
                        'available_no_of_slots'    => isset($get_session_data['available_no_of_slots']) ? $get_session_data['available_no_of_slots'] : "",
                        'available_no_of_employee' => isset($get_session_data['available_no_of_employee']) ? $get_session_data['available_no_of_employee'] : "",
                        'available_no_of_room'     => isset($get_session_data['available_no_of_room']) ? $get_session_data['available_no_of_room'] : "",
                        'available_no_of_desk'     => isset($get_session_data['available_no_of_desk']) ? $get_session_data['available_no_of_desk'] : "",
                        'available_no_of_cubicles' => isset($get_session_data['available_no_of_cubicles']) ? $get_session_data['available_no_of_cubicles'] : ""
                    ];
                Session::put('BookingRequestData',$val);   
            }
            if (Session::get('BookingRequestData.available_no_of_slots') != $available_no_of_slots) {
                $val = [
                        'checkin'                  => $checkin_date,
                        'checkout'                 => $checkout_date,
                        'guests'                   => $guests,
                        'enc_property_id'          => $enc_property_id1,
                        'property_id'              => $property_id1 ,
                        'property_type_slug'       => isset($get_session_data['property_type_slug']) ? $get_session_data['property_type_slug'] : "",
                        'available_no_of_slots'    => $available_no_of_slots,
                        'available_no_of_employee' => isset($get_session_data['available_no_of_employee']) ? $get_session_data['available_no_of_employee'] : "",
                        'available_no_of_room'     => isset($get_session_data['available_no_of_room']) ? $get_session_data['available_no_of_room'] : "",
                        'available_no_of_desk'     => isset($get_session_data['available_no_of_desk']) ? $get_session_data['available_no_of_desk'] : "",
                        'available_no_of_cubicles' => isset($get_session_data['available_no_of_cubicles']) ? $get_session_data['available_no_of_cubicles'] : ""
                    ];
                Session::put('BookingRequestData',$val);   
            }
            if (Session::get('BookingRequestData.available_no_of_employee') != $available_no_of_employee) {
                $val = [
                        'checkin'                  => $checkin_date,
                        'checkout'                 => $checkout_date,
                        'guests'                   => $guests,
                        'enc_property_id'          => $enc_property_id1,
                        'property_id'              => $property_id1 ,
                        'property_type_slug'       => isset($get_session_data['property_type_slug']) ? $get_session_data['property_type_slug'] : "",
                        'available_no_of_slots'    => isset($get_session_data['available_no_of_slots']) ? $get_session_data['available_no_of_slots'] : "",
                        'available_no_of_employee' => $available_no_of_employee,
                        'available_no_of_room'     => isset($get_session_data['available_no_of_room']) ? $get_session_data['available_no_of_room'] : "",
                        'available_no_of_desk'     => isset($get_session_data['available_no_of_desk']) ? $get_session_data['available_no_of_desk'] : "",
                        'available_no_of_cubicles' => isset($get_session_data['available_no_of_cubicles']) ? $get_session_data['available_no_of_cubicles'] : ""
                    ];
                Session::put('BookingRequestData',$val);   
            }
            if (Session::get('BookingRequestData.available_no_of_room') != $available_no_of_room) {
                $val = [
                        'checkin'                  => $checkin_date,
                        'checkout'                 => $checkout_date,
                        'guests'                   => $guests,
                        'enc_property_id'          => $enc_property_id1,
                        'property_id'              => $property_id1 ,
                        'property_type_slug'       => isset($get_session_data['property_type_slug']) ? $get_session_data['property_type_slug'] : "",
                        'available_no_of_slots'    => isset($get_session_data['available_no_of_slots']) ? $get_session_data['available_no_of_slots'] : "",
                        'available_no_of_employee' => isset($get_session_data['available_no_of_employee']) ? $get_session_data['available_no_of_employee'] : "",
                        'available_no_of_room'     => $available_no_of_room,
                        'available_no_of_desk'     => isset($get_session_data['available_no_of_desk']) ? $get_session_data['available_no_of_desk'] : "",
                        'available_no_of_cubicles' => isset($get_session_data['available_no_of_cubicles']) ? $get_session_data['available_no_of_cubicles'] : ""
                    ];
                Session::put('BookingRequestData',$val);   
            }
            if (Session::get('BookingRequestData.available_no_of_desk') != $available_no_of_desk) {
                $val = [
                        'checkin'                  => $checkin_date,
                        'checkout'                 => $checkout_date,
                        'guests'                   => $guests,
                        'enc_property_id'          => $enc_property_id1,
                        'property_id'              => $property_id1 ,
                        'property_type_slug'       => isset($get_session_data['property_type_slug']) ? $get_session_data['property_type_slug'] : "",
                        'available_no_of_slots'    => isset($get_session_data['available_no_of_slots']) ? $get_session_data['available_no_of_slots'] : "",
                        'available_no_of_employee' => isset($get_session_data['available_no_of_employee']) ? $get_session_data['available_no_of_employee'] : "",
                        'available_no_of_room'     => isset($get_session_data['available_no_of_room']) ? $get_session_data['available_no_of_room'] : "",
                        'available_no_of_desk'     => $available_no_of_desk,
                        'available_no_of_cubicles' => isset($get_session_data['available_no_of_cubicles']) ? $get_session_data['available_no_of_cubicles'] : ""
                    ];
                Session::put('BookingRequestData',$val);   
            }
            if (Session::get('BookingRequestData.available_no_of_cubicles') != $available_no_of_cubicles) {
                $val = [
                        'checkin'                  => $checkin_date,
                        'checkout'                 => $checkout_date,
                        'guests'                   => $guests,
                        'enc_property_id'          => $enc_property_id1,
                        'property_id'              => $property_id1 ,
                        'property_type_slug'       => isset($get_session_data['property_type_slug']) ? $get_session_data['property_type_slug'] : "",
                        'available_no_of_slots'    => isset($get_session_data['available_no_of_slots']) ? $get_session_data['available_no_of_slots'] : "",
                        'available_no_of_employee' => isset($get_session_data['available_no_of_employee']) ? $get_session_data['available_no_of_employee'] : "",
                        'available_no_of_room'     => isset($get_session_data['available_no_of_room']) ? $get_session_data['available_no_of_room'] : "",
                        'available_no_of_desk'     => isset($get_session_data['available_no_of_desk']) ? $get_session_data['available_no_of_desk'] : "",
                        'available_no_of_cubicles' => $available_no_of_cubicles
                    ];
                Session::put('BookingRequestData',$val);   
            }
        } else {
            $val = [
                    'checkin'                  => $checkin_date,
                    'checkout'                 => $checkout_date,
                    'guests'                   => $guests,
                    'enc_property_id'          => $enc_property_id,
                    'property_id'              => $property_id,
                    'property_type_slug'       => $property_type_slug,
                    'available_no_of_slots'    => $available_no_of_slots,
                    'available_no_of_employee' => $available_no_of_employee,
                    'available_no_of_room'     => $available_no_of_room,
                    'available_no_of_desk'     => $available_no_of_desk,
                    'available_no_of_cubicles' => $available_no_of_cubicles
                ];

            Session::put('BookingRequestData',$val);   
        } 

        $availability = $this->PropertyDatesService->check_unavaialable_dates($property_id,date('Y-m-d',strtotime($checkin_date)),date('Y-m-d',strtotime($checkout_date)));
        if ($availability == FALSE ) {
            $arr_json['status'] = 'UNAVAILABLE';
            return response()->json($arr_json);
        }

        $obj_property_rates = $this->PropertyModel->where('id','=',$property_id)->get(['id','price_per_night','currency_code']);

        if ($obj_property_rates) {
            $_arr_rate = $obj_property_rates->toArray();
            if (count($_arr_rate) <= 0) {
                $arr_json['status'] = 'NO_RATES';
                return response()->json($arr_json);
            }
        }

        if (count($obj_property_rates) > 0) {
            foreach ($obj_property_rates as $value) {
                $currency_code = $value->currency_code;
                $currency_code = Session::get('get_currency');

                if ($currency_code != 'INR') {
                    $convert_single = currencyConverterAPI($currency_code, 'INR', 1);
                } else {
                    $convert_single = 1;
                }          
            }
        }

        $user_id = $this->user_id;

        // coupon code starts
        if(isset($coupon_code) && !empty($coupon_code) && $coupon_code != null) {

            $obj_coupon = $this->CouponModel->where('coupon_code', '=', $coupon_code)
                                            ->where('status', '1')
                                            ->whereRaw('global_expiry >= "'.date("Y-m-d").'"')
                                            ->whereDoesntHave('coupon_code_used', function($query) use ($user_id) {
                                                $query->where('user_id', '=', $user_id);
                                            })
                                            ->first();

            if($obj_coupon) {
                $arr_coupon = $obj_coupon->toArray();
                $coupon_status = 'apply';
            } else {
                $arr_json['status'] = 'COUPON_INCORRECT';
                return response()->json($arr_json);
            }

            if($coupon_status == 'apply') {
                if($arr_coupon['discount_type'] == 2) {
                    $discount_type = 'percentage';
                    $discount_rate = $arr_coupon['discount'];
                } else if($arr_coupon['discount_type'] == 1) {
                    $discount_type = 'amount';
                    $discount_rate = $arr_coupon['discount'];
                }
            } else {
                $arr_json['status'] = 'COUPON_UNAVAILABLE';
                return response()->json($arr_json);
            }
        }
        // coupon code ends

        /*Now Calculating Actual Rates */
        $arr_cal_result = $this->PropertyRateCalculatorService->calculate_rate(date('Y-m-d', strtotime($checkin_date)), date('Y-m-d', strtotime($checkout_date)), $property_id, $discount_type, $discount_rate, $guests, $property_type_slug, $available_no_of_slots, $available_no_of_employee, $available_no_of_room, $available_no_of_desk, $available_no_of_cubicles);

        if($arr_cal_result) {
            $arr_cal_result['service_fee_gst_percentage'] = $arr_cal_result['service_fee_gst_percentage'];
            $arr_cal_result['service_fee_percentage']     = $arr_cal_result['service_fee_percentage'];
            $arr_cal_result['service_fee']                = $arr_cal_result['service_fee'] + $arr_cal_result['service_fee_gst_amount'];
            $arr_cal_result['gst_amount']                 = $arr_cal_result['gst_amount'];
        }

        if($arr_cal_result['total_payble_amount'] != '') {
            if ($currency_code != 'INR') {
               $total_amount          = $arr_cal_result['total_payble_amount'];
               $total_convert_in_INR  = currencyConverterAPI($currency_code, 'INR', $total_amount);
            } else {
                $total_convert_in_INR = $arr_cal_result['total_payble_amount'];
            }
        }

        if($total_convert_in_INR > $arr_cal_result['discount_price']) {
            $discounted_total_convert_inr = round($total_convert_in_INR, 2) - $arr_cal_result['discount_price'];
        }
        else {
            $arr_json['status'] = 'ERROR';
            return response()->json($arr_json);
        }

        $arr_calculation_result = array(
            'arr_cal_result'        => $arr_cal_result,
            'convert_single'        => round($convert_single, 2),
            'total_amount_inr'      => round($total_convert_in_INR, 2),
            'amount_discount_inr'   => $discounted_total_convert_inr,
            'currency_code'         => $currency_code,
            'total_payble_amount'   => round($arr_cal_result['total_payble_amount'], 2),
            'total_property_amount' => round($arr_cal_result['total_amount'], 2)
        );

        $arr_json['arr_data'] = $arr_calculation_result;
        $arr_json['status']   = 'SUCCESS';
        return response()->json($arr_json);
    }
    

    /*
    | Function  : change the status of the booking according to the action perform by the host
    | Author    : Deepak Arvind Salunke
    | Date      : 03/05/2018
    | Output    : Success or Error
    */

    public function change_status($status = false, $enc_id = false)
    {
        if($status != '' && !empty($status) && $enc_id != '' && !empty($enc_id))
        {
            $arr_booking = [];
            $booking_id  = '';

            $booking_id  = base64_decode($enc_id);

            $obj_booking = $this->BookingModel->where('id', $booking_id)->first();
            if($obj_booking) {
                $arr_booking = $obj_booking->toArray();
            }

            if($status == 'accept') {
                $update_data['booking_status'] = '1';
                $booking_status   = 'accepted';
                $notification_url = "/my-booking/confirmed";
            } else if($status == 'reject') {
                $update_data['booking_status'] = '4';
                $booking_status   = 'rejected';
                $notification_url = "/my-booking/cancelled";
            } else {
                Session::flash('error',"Invalid action can't be performed");
                return redirect()->back();
            }

            $update_booking = $this->BookingModel->where('id', $booking_id)->update($update_data);

            $obj_booked_by = $this->UserModel->where('id', $arr_booking['property_booked_by'])->first();
            if($obj_booked_by) {
                $arr_booked_by = $obj_booked_by->toArray();
            }

            $arr_built_content  = array(
                                        'USER_NAME' => isset($this->user_first_name) ? $this->user_first_name : 'NA',
                                        'STATUS'    => $booking_status,
                                        'SUBJECT'   => "Your booking has been ".$booking_status." by host."
                                    );

            $arr_notify_data['arr_built_content']  = $arr_built_content;
            $arr_notify_data['notify_template_id'] = '11';
            $arr_notify_data['sender_id']          = $this->user_id;
            $arr_notify_data['sender_type']        = '4';
            $arr_notify_data['receiver_type']      = '1';
            $arr_notify_data['receiver_id']        = $arr_booking['property_booked_by'];
            $arr_notify_data['url']                = $notification_url;
            $notification_status                   = $this->NotificationService->send_notification($arr_notify_data);

            $type = get_notification_type_of_user($arr_booking['property_booked_by']);

            if(isset($type) && !empty($type)) {
                
                // for mail
                if($type['notification_by_email'] == 'on') {
                    $arr_built_content = [
                                        'USER_NAME'    => isset($arr_booked_by['display_name'])?ucfirst($arr_booked_by['display_name']):'NA',
                                        'Email'        => isset($arr_booked_by['email'])?ucfirst($arr_booked_by['email']):'NA' ,
                                        'SUBJECT'      => "Your booking has been ".$booking_status." by host.",
                                        'STATUS'       => $booking_status,
                                        'PROJECT_NAME' => config('app.project.name')
                                    ];
                    $arr_mail_data                      = [];
                    $arr_mail_data['email_template_id'] = '15';
                    $arr_mail_data['arr_built_content'] = $arr_built_content;
                    $arr_mail_data['user']              = ['email' => isset($arr_booked_by['email'])?ucfirst($arr_booked_by['email']):'NA', 'first_name' => isset($arr_booked_by['display_name'])?ucfirst($arr_booked_by['display_name']):'NA'];

                    $status                             = $this->EmailService->send_mail($arr_mail_data);
                }

                // for sms
                if($type['notification_by_sms'] == 'on') {
                    $country_code  = isset($arr_booked_by['country_code']) ? $arr_booked_by['country_code'] : '';
                    $mobile_number = isset($arr_booked_by['mobile_number']) ? $arr_booked_by['mobile_number'] : '';

                    $arr_sms_data                  = [];
                    $arr_sms_data['msg']           = "Your booking has been ".$booking_status." by host.";
                    $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                    $status                        = $this->SMSService->send_SMS($arr_sms_data);
                }

                // for push notification
                if($type['notification_by_push'] == 'on') {
                    $headings = 'Booking status updated successfully.';
                    $content  = 'Booking status updated successfully.';
                    $user_id  = $arr_booking['property_booked_by'];
                    $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                }
            }

            Session::flash('success','Booking status updated successfully.');
            return redirect()->back();

        } else {
            Session::flash('error','Something went wrong. Try again later');
            return redirect()->back();
        }
    } // end change_status


    /*
    | Function  : reject selected booking and store reason for it
    | Author    : Deepak Arvind Salunke
    | Date      : 07/05/2018
    | Output    : success or error
    */

    public function reject_booking(Request $request)
    {
        $enc_id     = $request->input('booking_id');
        $reason     = $request->input('reason');
        $booking_id = base64_decode($enc_id);

        if($booking_id != '') {
            $url              = url()->previous();
            $notification_url = str_replace(url('/'), "", $url);

            $booking_status         = 'rejected';
            $data['reject_reason']  = $reason;
            $data['booking_status'] = 4;
            $data['status_by']      = $this->user_id;
            
            $update_booking         = $this->BookingModel->where('id', $booking_id)->update($data);
            if($update_booking) {
                $obj_booking        = $this->BookingModel->where('id', $booking_id)->with('property_details')->first();
                
                if($obj_booking) {
                    $arr_booking    = $obj_booking->toArray();

                    $property_id    = $arr_booking['property_id'];
                    $check_in_date  = $arr_booking['check_in_date'];
                    $check_out_date = $arr_booking['check_out_date'];

                    $this->PropertyDatesService->remove_unavaialable_dates($property_id, $check_in_date, $check_out_date);
                }

                $obj_user_data      = $this->UserModel->where('id', $arr_booking['property_booked_by'])->first();
                if($obj_user_data) {
                    $arr_user       = $obj_user_data->toArray();
                }

                $arr_built_content  = array(
                                            'USER_NAME' => isset($arr_user['first_name']) ? $arr_user['first_name'] : 'NA',
                                            'MESSAGE'   => "Booking is rejected by the ".$this->user_first_name." for the ".$arr_booking['property_details']['property_name']
                                        );

                $arr_notify_data['arr_built_content']  = $arr_built_content;
                $arr_notify_data['notify_template_id'] = '9';
                $arr_notify_data['template_text']      = "Booking is rejected by the ".$this->user_first_name." for the ".$arr_booking['property_details']['property_name'];
                $arr_notify_data['sender_id']          = $this->user_id;
                $arr_notify_data['sender_type']        = '1';
                $arr_notify_data['receiver_type']      = '4';
                $arr_notify_data['receiver_id']        = $arr_user['id'];
                $arr_notify_data['url']                = $notification_url;

                $notification_status                    = $this->NotificationService->send_notification($arr_notify_data);

                $type = get_notification_type_of_user($arr_user['id']);

                if(isset($type) && !empty($type)) {

                    // for mail
                    if($type['notification_by_email'] == 'on') {
                        $arr_built_content = [
                                        'USER_NAME'    => isset($arr_user['display_name'])?ucfirst($arr_user['display_name']):'NA',
                                        'Email'        => isset($arr_user['email'])?ucfirst($arr_user['email']):'NA' ,
                                        'SUBJECT'      => "Booking is rejected by the ".$this->user_first_name." for the ".$arr_booking['property_details']['property_name'],
                                        'STATUS'       => $booking_status,
                                        'PROJECT_NAME' => config('app.project.name')
                                    ];
                        $arr_mail_data                      = [];
                        $arr_mail_data['email_template_id'] = '15';
                        $arr_mail_data['arr_built_content'] = $arr_built_content;
                        $arr_mail_data['user']              = ['email' => isset($arr_user['email']) ? ucfirst($arr_user['email']) : 'NA', 'first_name' => isset($arr_user['display_name']) ? ucfirst($arr_user['display_name']) : 'NA'];
                        
                        $status = $this->EmailService->send_mail($arr_mail_data);
                    }

                    // for sms
                    if($type['notification_by_sms'] == 'on') {
                        $country_code  = isset($arr_user['country_code']) ? $arr_user['country_code'] : '';
                        $mobile_number = isset($arr_user['mobile_number']) ? $arr_user['mobile_number'] : '';

                        $arr_sms_data                  = [];
                        $arr_sms_data['msg']           = "Booking is rejected by the ".$this->user_first_name." for the ".$arr_booking['property_details']['property_name'];
                        $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                        
                        $status = $this->SMSService->send_SMS($arr_sms_data);
                    }

                    // for push notification
                    if($type['notification_by_push'] == 'on') {
                        $headings = 'Booking successfully rejected';
                        $content  = 'Booking successfully rejected.';
                        $user_id  = $arr_user['id'];
                        $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                    }
                }
                Session::flash('success','Booking successfully rejected');
            } else {
                Session::flash('error','Booking was not able to reject. Please try again');
            }
        } else {
            Session::flash('error','Something went wrong. Please try again');
        }

        return redirect()->back();
    } // end reject_booking


    /*
    | Function  : store data in session
    | Author    : Deepak Arvind Salunke
    | Date      : 10/05/2018
    | Output    : success or error
    */
    public function session_booking_store(Request $request)
    {
        $enc_property_id = $property_id = $booking_id = $no_of_guest = $property_type_slug = $available_no_of_slots = $available_no_of_employee = $available_no_of_room = $available_no_of_desk = $available_no_of_cubicles = $property_name_slug = '';
        
        $booking_id               = $request->input('booking_id');
        $start_date               = $request->input('start_date');
        $end_date                 = $request->input('end_date');
        $no_of_guest              = $request->input('no_of_guest');
        $property_type_slug       = $request->input('property_type_slug');
        $available_no_of_slots    = $request->input('available_no_of_slots');
        $available_no_of_employee = $request->input('available_no_of_employee');
        $available_no_of_room     = $request->input('available_no_of_room');
        $available_no_of_desk     = $request->input('available_no_of_desk');
        $available_no_of_cubicles = $request->input('available_no_of_cubicles');

        $val = [ 
                    'checkin'                  => $start_date,
                    'checkout'                 => $end_date,
                    'guests'                   => $no_of_guest,
                    'enc_property_id'          => $enc_property_id,
                    'property_id'              => $property_id,
                    'property_type_slug'       => $property_type_slug,
                    'available_no_of_slots'    => $available_no_of_slots,
                    'available_no_of_employee' => $available_no_of_employee,
                    'available_no_of_room'     => $available_no_of_room,
                    'available_no_of_desk'     => $available_no_of_desk,
                    'available_no_of_cubicles' => $available_no_of_cubicles
                ];

        if(Session::get('BookingRequestData') != null) {
            Session::put('BookingRequestData', $val);
        } else {
            Session::set('BookingRequestData', $val);
        }

        $arr_json['status'] = 'success';
        return response()->json($arr_json);
    } // end session_booking_store



    public function get_available_slots(Request $request)
    {
        $selected_field = $html = $session_available_no = $session_selected = '';
        $available = 0;

        $form_data   = $request->all();

        $property_id = isset($form_data['property_id']) && !empty($form_data['property_id']) ? base64_decode($form_data['property_id']) : '';
        $type_slug   = isset($form_data['type_slug']) && !empty($form_data['type_slug']) ? $form_data['type_slug'] : '';
        $start_date  = isset($form_data['start_date']) && !empty($form_data['start_date']) ? date('c',strtotime($form_data['start_date'])) : '';
        $end_date    = isset($form_data['end_date']) && !empty($form_data['end_date']) ? date('c',strtotime($form_data['end_date'])) : '';
        
        $session_no_of_guest = isset($form_data['session_no_of_guest']) && !empty($form_data['session_no_of_guest']) ? $form_data['session_no_of_guest'] : '';

        $session_no_of_slots = Session::get('BookingRequestData') != null ? Session::get('BookingRequestData.available_no_of_slots') : "";

        if($type_slug == 'warehouse') {
            $selected_field = 'selected_no_of_slots';
            $available_no   = 'no_of_slots';
        } else if($type_slug == 'office-space') {
            $selected_field = 'selected_of_employee';
            $available_no   = 'no_of_employee';
        } else {
            $selected_field = 'no_of_guest';
            $available_no   = 'number_of_guest';
        }

        $obj_booking = $this->BookingModel->where('property_id', $property_id)
            ->select('id', 'booking_id', 'property_id', 'check_in_date', 'check_out_date', 'property_type_slug', $selected_field)
            ->where('booking_status', '!=', 6)
            //->where('payment_type', '!=', null)
            ->whereRaw("((DATE('".$start_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE('".$end_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE(check_in_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."')) OR (DATE(check_out_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."'))) ")
            ->sum( $selected_field );

        $obj_property = $this->PropertyModel->select('id', $available_no, 'price_per')->where('id', $property_id)->first();
        if($obj_property) {
            $arr_property = $obj_property->toArray();
            $available    = $arr_property[ $available_no ];

            if($type_slug == 'warehouse') {
                $select_option = 'Slots';
                $session_selected = $session_no_of_slots;
            } else if($type_slug == 'office-space') {
                $select_option = ucwords($arr_property['price_per']);
            } else {
                $select_option = 'Guest';
                $session_selected = $session_no_of_guest;
            }
        }

        $total_available = (int) $available - (int) $obj_booking;

        if($total_available > 0) {
            $html .= '<option value="">Select No. of '.$select_option.'</option>';
            for ( $i = 1; $i <= $total_available; $i++ ) { 

                if($session_selected == $i) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }

                $html .= '<option value="'.$i.'" '. $selected .' >'.$i.' '.$select_option.'</option>';
            }
        } else {
            $html .= '<option value="">No '.$select_option.' Available</option>';
        }

        return $html;
    } // end get_available_slots


    public function get_available_office(Request $request)
    {
        $employee = $room = $desk = $cubicles = $no_of_employee = $no_of_room = $no_of_desk = $no_of_cubicles = $employee_html = $room_html = $desk_html = $cubicles_html = $session_no_of_employee = $session_no_of_room = $session_no_of_desk = $session_no_of_cubicles = '';
        $html = [];

        $form_data   = $request->all();

        $property_id = isset($form_data['property_id']) && !empty($form_data['property_id']) ? base64_decode($form_data['property_id']) : '';
        $type_slug   = isset($form_data['type_slug']) && !empty($form_data['type_slug']) ? $form_data['type_slug'] : '';
        $start_date  = isset($form_data['start_date']) && !empty($form_data['start_date']) ? date('c',strtotime($form_data['start_date'])) : '';
        $end_date    = isset($form_data['end_date']) && !empty($form_data['end_date']) ? date('c',strtotime($form_data['end_date'])) : '';

        $session_no_of_employee = Session::get('BookingRequestData') != null ? Session::get('BookingRequestData.available_no_of_employee') : "";
        $session_no_of_room     = Session::get('BookingRequestData') != null ? Session::get('BookingRequestData.available_no_of_room') : "";
        $session_no_of_desk     = Session::get('BookingRequestData') != null ? Session::get('BookingRequestData.available_no_of_desk') : "";
        $session_no_of_cubicles = Session::get('BookingRequestData') != null ? Session::get('BookingRequestData.available_no_of_cubicles') : "";

        $obj_property = $this->PropertyModel->select('id', 'price_per', 'employee', 'room', 'desk', 'cubicles', 'no_of_employee', 'no_of_room', 'no_of_desk', 'no_of_cubicles')->where('id', $property_id)->first();
        if($obj_property) {
            $arr_property = $obj_property->toArray();
            
            $employee = $arr_property['employee'];
            $room     = $arr_property['room'];
            $desk     = $arr_property['desk'];
            $cubicles = $arr_property['cubicles'];

            $no_of_employee = $arr_property['no_of_employee'];
            $no_of_room     = $arr_property['no_of_room'];
            $no_of_desk     = $arr_property['no_of_desk'];
            $no_of_cubicles = $arr_property['no_of_cubicles'];
        }

        if( $employee == 'on' ) {
            $employee_booking = $this->BookingModel->where('property_id', $property_id)
            ->select('id', 'booking_id', 'property_id', 'check_in_date', 'check_out_date', 'property_type_slug', 'selected_of_employee', 'selected_of_room', 'selected_of_desk', 'selected_of_cubicles')
            ->where('booking_status', '!=', 6)
            //->where('payment_type', '!=', null)
            ->whereRaw("((DATE('".$start_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE('".$end_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE(check_in_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."')) OR (DATE(check_out_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."'))) ")
            ->sum('selected_of_employee');

            $total_available_employee = (int) $no_of_employee - (int) $employee_booking;

            if($total_available_employee > 0) {
                $employee_html .= '<option value="">Select No. of Person</option>';
                for ( $i = 1; $i <= $total_available_employee; $i++ ) { 
                    if($session_no_of_employee == $i) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    $employee_html .= '<option value="'.$i.'" '. $selected .' >'.$i.' Person</option>';
                }
            } else {
                $employee_html .= '<option value="">No Person Available</option>';
            }
        }

        if( $room == 'on' )
        {
            $room_booking = $this->BookingModel->where('property_id', $property_id)
            ->select('id', 'booking_id', 'property_id', 'check_in_date', 'check_out_date', 'property_type_slug', 'selected_of_employee', 'selected_of_room', 'selected_of_desk', 'selected_of_cubicles')
            ->where('booking_status', '!=', 6)
            //->where('payment_type', '!=', null)
            ->whereRaw("((DATE('".$start_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE('".$end_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE(check_in_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."')) OR (DATE(check_out_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."'))) ")
            ->sum('selected_of_room');

            $total_available_room = (int) $no_of_room - (int) $room_booking;

            if($total_available_room > 0) {
                $room_html .= '<option value="">Select No. of Room</option>';
                for ( $i = 1; $i <= $total_available_room; $i++ ) { 
                    if($session_no_of_room == $i) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    $room_html .= '<option value="'.$i.'" '. $selected .' >'.$i.' Room</option>';
                }
            } else {
                $room_html .= '<option value="">No Room Available</option>';
            }
        }

        if( $desk == 'on' )
        {
            $desk_booking = $this->BookingModel->where('property_id', $property_id)
            ->select('id', 'booking_id', 'property_id', 'check_in_date', 'check_out_date', 'property_type_slug', 'selected_of_employee', 'selected_of_room', 'selected_of_desk', 'selected_of_cubicles')
            ->where('booking_status', '!=', 6)
            //->where('payment_type', '!=', null)
            ->whereRaw("((DATE('".$start_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE('".$end_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE(check_in_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."')) OR (DATE(check_out_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."'))) ")
            ->sum('selected_of_desk');

            $total_available_desk = (int) $no_of_desk - (int) $desk_booking;

            if($total_available_desk > 0) {
                $desk_html .= '<option value="">Select No. of Desk</option>';
                for ( $i = 1; $i <= $total_available_desk; $i++ ) { 
                    if($session_no_of_desk == $i) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    $desk_html .= '<option value="'.$i.'" '. $selected .' >'.$i.' Desk</option>';
                }
            } else {
                $desk_html .= '<option value="">No Desk Available</option>';
            }
        }

        if( $cubicles == 'on' )
        {
            $cubicles_booking = $this->BookingModel->where('property_id', $property_id)
            ->select('id', 'booking_id', 'property_id', 'check_in_date', 'check_out_date', 'property_type_slug', 'selected_of_employee', 'selected_of_room', 'selected_of_desk', 'selected_of_cubicles')
            ->where('booking_status', '!=', 6)
            //->where('payment_type', '!=', null)
            ->whereRaw("((DATE('".$start_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE('".$end_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE(check_in_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."')) OR (DATE(check_out_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."'))) ")
            ->sum('selected_of_cubicles');

            $total_available_cubicles = (int) $no_of_cubicles - (int) $cubicles_booking;

            if($total_available_cubicles > 0) {
                $cubicles_html .= '<option value="">Select No. of Cubicles</option>';
                for ( $i = 1; $i <= $total_available_cubicles; $i++ ) { 
                    if($session_no_of_cubicles == $i) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    $cubicles_html .= '<option value="'.$i.'" '. $selected .' >'.$i.' Cubicles</option>';
                }
            } else {
                $cubicles_html .= '<option value="">No Cubicles Available</option>';
            }
        }

        $html['employee_html'] = $employee_html;
        $html['room_html']     = $room_html;
        $html['desk_html']     = $desk_html;
        $html['cubicles_html'] = $cubicles_html;

        return $html;

    } // end get_available_office
}
