<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Common\Services\PropertyRateCalculatorService;
use App\Common\Services\MobileAppNotification;
use App\Common\Services\PropertyDatesService;
use App\Common\Services\NotificationService;
use App\Common\Services\ListingService;
use App\Common\Services\EmailService;
use App\Common\Services\SMSService;

use App\Models\PropertyUnavailabilityModel;
use App\Models\ReviewRatingModel;
use App\Models\SupportQueryModel;
use App\Models\TransactionModel;
use App\Models\SupportTeamModel;
use App\Models\PropertyModel;
use App\Models\BookingModel;
use App\Models\UserModel;
use App\Models\SiteSettingsModel;
use App\Models\CouponModel;
use App\Models\CouponsUsedModel;
use App\Models\PropertyImagesModel;

use Tymon\JWTAuth\Exceptions\JWTException;

use Validator;
use JWTAuth;
use Input;
use Image;
use TCPDF;
use PDF;
use DB;

class BookingController extends Controller
{
    public function __construct(
                                    PropertyDatesService          $property_date_service,
                                    PropertyRateCalculatorService $property_rate_service,
                                    ListingService                $listing_service,
                                    EmailService                  $email_service,
                                    SMSService                    $sms_service,
                                    NotificationService           $notification_service,
                                    MobileAppNotification         $mobileappnotification_service,
                                    UserModel                     $user_model,
                                    PropertyModel                 $property_model,
                                    BookingModel                  $booking_model,
                                    PropertyUnavailabilityModel   $unavailability_model,
                                    ReviewRatingModel             $review_rating_model,
                                    CouponModel                   $coupon_model,
                                    CouponsUsedModel              $coupon_used_model,
                                    PropertyImagesModel           $PropertyImagesModel,
                                    TransactionModel              $transaction_model,
                                    SupportQueryModel             $support_query_model,
                                    SupportTeamModel              $support_team_model,
                                    SiteSettingsModel             $admincommissionmodel
                                )
    {
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
        $this->BookingModel                  = $booking_model;
        $this->ReviewRatingModel             = $review_rating_model;
        $this->CouponModel                   = $coupon_model;
        $this->CouponsUsedModel              = $coupon_used_model;
        $this->TransactionModel              = $transaction_model;
        $this->PropertyImagesModel           = $PropertyImagesModel;
        $this->SupportQueryModel             = $support_query_model;
        $this->SupportTeamModel              = $support_team_model;

        $this->user_id = validate_user_jwt_token();
        $this->TCPDF   = new TCPDF();

        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            $this->user_id = '';
        }
        if(isset($user) && count($user) > 0) {
            $this->user_first_name           = $user->first_name;
            $this->user_last_name            = $user->last_name;
        } else {
            $this->user_id                   = 0;
        }
        
        $this->property_image_base_path      = base_path().config('app.project.img_path.property_image');
        $this->property_image_public_path    = url('/').config('app.project.img_path.property_image');
        $this->profile_image_public_img_path = url('/').config('app.project.img_path.user_profile_images');
        $this->profile_image_base_img_path   = public_path().config('app.project.img_path.user_profile_images');
    }

    public function get_login_user_details()
    {
        $status   = 'error';
        $message  = 'Record not found';
        $obj_user = $this->UserModel->where('id', $this->user_id)->first();
        if($obj_user) {
            $arr_user = $obj_user->toArray();

            $user_data['user_id']       = $arr_user['id'];
            $user_data['user_name']     = $arr_user['user_name'];
            $user_data['first_name']    = $arr_user['first_name'];
            $user_data['last_name']     = $arr_user['last_name'];
            $user_data['country_code']  = $arr_user['country_code'];
            $user_data['mobile_number'] = $arr_user['mobile_number'];
            $user_data['email']         = $arr_user['email'];
            $user_data['wallet_amount'] = $arr_user['wallet_amount'];

            $status  = 'success';
            $message = 'Record get successfully';
        }
        return $this->build_response($status,$message,$user_data);
    }

    public function calculate_dates_rate(Request $request)
    {
        $arr_json = $val = $property_details = $arr_calculation_result = $BookingRequestData = [];
        $convert_single = $available_no_of_slots = $available_no_of_employee = $guests = 0;
        $discount_type = $discount_rate = '';

        $arr_rules               = array();
        $arr_rules['start_date'] = "required";
        $arr_rules['end_date']   = "required";

        if ($request->input('property_type_slug') == 'warehouse') {
            $arr_rules['available_no_of_slots'] = "required";
        }
        elseif ($request->input('property_type_slug') == 'office-space') {
            //$arr_rules['available_no_of_employee'] = "required";
        }
        else {
            $arr_rules['no_of_guest'] = "required";
        }
        
        $property_id              = $request->input('property_id');
        $checkin_date             = $request->input('start_date');
        $checkout_date            = $request->input('end_date');
        $coupon_code              = $request->input('coupon_code');
        $user_currency            = $request->input('user_currency');
        $guests                   = $request->input('no_of_guest');
        $property_type_slug       = $request->input('property_type_slug','');
        $available_no_of_slots    = $request->input('available_no_of_slots',0);
        $available_no_of_employee = $request->input('available_no_of_employee',0);
        $available_no_of_room     = $request->input('available_no_of_room',0);
        $available_no_of_desk     = $request->input('available_no_of_desk',0);
        $available_no_of_cubicles = $request->input('available_no_of_cubicles',0);

        $user_id                  = $this->user_id;

        if(isset($user_id) && $user_id != '') {
            $validator = Validator::make($request->all(),$arr_rules);

            if($validator->fails()) {
                $status  = 'error';
                $message = 'fill all required fields'; 
            } else {
                $availability = $this->PropertyDatesService->check_unavaialable_dates($property_id,$checkin_date,$checkout_date);

                if ($availability == FALSE ) {
                    $status  = 'UNAVAILABLE';
                    $message = 'These date are Unavailable, kindly select another dates';
                    return $this->build_response($status,$message);
                } else {
                    $obj_property_rates = $this->PropertyModel->where('id','=',$property_id)->get(['id','price_per_night','currency_code']);

                    if (count($obj_property_rates) > 0) {
                        $_arr_rate = $obj_property_rates->toArray();
                        if(count($_arr_rate) <= 0) {
                            $status  = 'error';
                            $message = 'No Rates available';
                            return $this->build_response($status,$message);
                        } else {
                            foreach ($obj_property_rates as $value) {

                                $currency_code = $user_currency;
                                if($currency_code != 'INR') {
                                    $convert_single = currencyConverterAPI($currency_code, 'INR', 1);
                                } else {
                                    $convert_single = '1';
                                }
                            }
                        }
                    }

                    // coupon code starts
                    if(isset($coupon_code) && !empty($coupon_code) && $coupon_code != null) {
                        $obj_coupon = $this->CouponModel->where('coupon_code', '=', $coupon_code)
                                                        ->where('status', '1')
                                                        ->where('global_expiry', '>=',date("Y-m-d H:i:s"))
                                                        ->whereDoesntHave('coupon_code_used', function($query) use ($user_id){
                                                            $query->where('user_id', '=', $user_id);
                                                        })->first();
                        
                        if($obj_coupon) {
                            $arr_coupon = $obj_coupon->toArray();
                            $coupon_status = 'apply';
                        } else {
                            return $this->build_response('error', "Coupon code Unavailable");
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
                            $status  = 'error';
                            $message = 'Coupon code Unavailable';
                            return $this->build_response($status,$message);
                        }
                    }
                    // coupon code ends

                    /*Now Calculating Actual Rates */
                    $arr_cal_result = $this->PropertyRateCalculatorService->calculate_rate_api($checkin_date, $checkout_date, $property_id, $discount_type, $discount_rate, $user_currency,$guests, $property_type_slug, $available_no_of_slots, $available_no_of_employee, $available_no_of_room, $available_no_of_desk, $available_no_of_cubicles);

                    if($arr_cal_result['total_payble_amount'] != '') {
                        if($currency_code != 'INR') {
                           $total_amount          = $arr_cal_result['total_payble_amount'];
                           $total_convert_in_INR  = currencyConverterAPI($currency_code, 'INR', $total_amount);
                        } else {
                            $total_convert_in_INR = $arr_cal_result['total_payble_amount'];
                        }
                    }                 
                }

                $status  = 'success';
                $message = 'Date available';

                $arr_calculation_result = array(
                            'arr_cal_result'       => $arr_cal_result,
                            'convert_single'       => number_format($convert_single, 2, '.', ''),
                            'total_convert_in_INR' => number_format($total_convert_in_INR, 2, '.', ''),
                            'currency_code'        => $currency_code,
                            'total_payble_amount'  => number_format($arr_cal_result['total_payble_amount'], 2, '.', '')
                        );
            }
        } else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }
        $arr_data['calculation_result'] = $arr_calculation_result;
        return $this->build_response($status,$message,$arr_data);
    }

    public function get_available_slots(Request $request)
    {
        $selected_field = $html = $session_available_no = '';
        $available = 0;

        $form_data   = $request->all();

        $property_id = isset($form_data['property_id']) && !empty($form_data['property_id']) ? $form_data['property_id'] : '';
        $type_slug   = isset($form_data['property_type_slug']) && !empty($form_data['property_type_slug']) ? $form_data['property_type_slug'] : '';
        $start_date  = isset($form_data['start_date']) && !empty($form_data['start_date']) ? date('c',strtotime($form_data['start_date'])) : '';
        $end_date    = isset($form_data['end_date']) && !empty($form_data['end_date']) ? date('c',strtotime($form_data['end_date'])) : '';

        if($type_slug == 'warehouse') {
            $selected_field = 'selected_no_of_slots';
            $available_no   = 'no_of_slots';
        }
        else if($type_slug == 'office-space') {
            $selected_field = 'selected_of_employee';
            $available_no   = 'no_of_employee';
        }
        else {
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
            }
            else if($type_slug == 'office-space') {
                $select_option = ucwords($arr_property['price_per']);
            }
            else {
                $select_option = 'Guest';
            }
        }

        $total_available = (int) $available - (int) $obj_booking;

        if($total_available > 0) {
            $status  = 'success';
            $message = '';
            $data['total_available'] = $total_available ;
        }
        else {
            $status  = 'success';
            $message = '';
            $data['total_available'] = 0 ;
        }
        return $this->build_response($status,$message,$data);

    } // end get_available_slots


    public function get_available_office(Request $request)
    {
        $employee = $room = $desk = $cubicles = $no_of_employee = $no_of_room = $no_of_desk = $no_of_cubicles = $employee_html = $room_html = $desk_html = $cubicles_html = $session_no_of_employee = $session_no_of_room = $session_no_of_desk = $session_no_of_cubicles = '';
        $data = [];

        $form_data   = $request->all();

        $property_id = isset($form_data['property_id']) && !empty($form_data['property_id']) ? $form_data['property_id'] : '';
        $type_slug   = isset($form_data['property_type_slug']) && !empty($form_data['property_type_slug']) ? $form_data['property_type_slug'] : '';
        $start_date  = isset($form_data['start_date']) && !empty($form_data['start_date']) ? date('c',strtotime($form_data['start_date'])) : '';
        $end_date    = isset($form_data['end_date']) && !empty($form_data['end_date']) ? date('c',strtotime($form_data['end_date'])) : '';

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
                $data['total_available_employee'] = $total_available_employee ;
            }
            else {
                $data['total_available_employee'] = 0 ;
            }
        }

        if( $room == 'on' ) {
            $room_booking = $this->BookingModel->where('property_id', $property_id)
            ->select('id', 'booking_id', 'property_id', 'check_in_date', 'check_out_date', 'property_type_slug', 'selected_of_employee', 'selected_of_room', 'selected_of_desk', 'selected_of_cubicles')
            ->where('booking_status', '!=', 6)
            //->where('payment_type', '!=', null)
            ->whereRaw("((DATE('".$start_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE('".$end_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE(check_in_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."')) OR (DATE(check_out_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."'))) ")
            ->sum('selected_of_room');

            $total_available_room = (int) $no_of_room - (int) $room_booking;

            if($total_available_room > 0) {
                $data['total_available_room'] = $total_available_room ;
            }
            else {
                $data['total_available_room'] = 0 ;
            }
        }

        if( $desk == 'on' ) {
            $desk_booking = $this->BookingModel->where('property_id', $property_id)
            ->select('id', 'booking_id', 'property_id', 'check_in_date', 'check_out_date', 'property_type_slug', 'selected_of_employee', 'selected_of_room', 'selected_of_desk', 'selected_of_cubicles')
            ->where('booking_status', '!=', 6)
            //->where('payment_type', '!=', null)
            ->whereRaw("((DATE('".$start_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE('".$end_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE(check_in_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."')) OR (DATE(check_out_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."'))) ")
            ->sum('selected_of_desk');

            $total_available_desk = (int) $no_of_desk - (int) $desk_booking;

            if($total_available_desk > 0) {
                $data['total_available_desk'] = $total_available_desk ;
            }
            else {
                $data['total_available_desk'] = 0 ;
            }
        }

        if( $cubicles == 'on' ) {
            $cubicles_booking = $this->BookingModel->where('property_id', $property_id)
            ->select('id', 'booking_id', 'property_id', 'check_in_date', 'check_out_date', 'property_type_slug', 'selected_of_employee', 'selected_of_room', 'selected_of_desk', 'selected_of_cubicles')
            ->where('booking_status', '!=', 6)
            //->where('payment_type', '!=', null)
            ->whereRaw("((DATE('".$start_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE('".$end_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE(check_in_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."')) OR (DATE(check_out_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."'))) ")
            ->sum('selected_of_cubicles');

            $total_available_cubicles = (int) $no_of_cubicles - (int) $cubicles_booking;

            if($total_available_cubicles > 0) {
                $data['total_available_cubicles'] = $total_available_cubicles;
            }
            else {
                $data['total_available_cubicles'] = 0;
            }
        }

        $status  = 'success';
        $message = '';
        return $this->build_response($status,$message,$data);

    } // end get_available_office

    public function store_booking_details(Request $request)
    {
        $form_data = $arr_property_data = $arr_booking_data = $arr_admin_commission = [];
        $property_amount = $available_no_of_slots = $available_no_of_employee = $guests = 0;
        $discount_type = $discount_rate = '';
        $user_currency = 'INR';
        
        $form_data = $request->all();
        $arr_rules = array();
        $arr_rules['start_date'] = "required";
        $arr_rules['end_date']   = "required";

        if ($request->input('property_type_slug') == 'warehouse') {
            $arr_rules['available_no_of_slots'] = "required";
        }
        elseif ($request->input('property_type_slug') == 'office-space') {
            //$arr_rules['available_no_of_employee'] = "required";
        }   
        else {
            $arr_rules['no_of_guest'] = "required";
        }
        
        if (isset($this->user_id) && $this->user_id != '') {
            $validator = Validator::make($request->all(),$arr_rules);
            if($validator->fails()) {
                $status  = 'error';
                $message = 'fill all required fields'; 
            } 
            else {
                $property_id       = $request->input('property_id');
                $arr_property_data = get_property_details($property_id);
                $user_id           = isset($arr_property_data['user_id']) ? $arr_property_data['user_id'] : '';

                if($request->input('user_type') == '4') {
                    $status  = 'error';
                    $message = 'Host can not booked a property.';
                }
                else if($this->user_id == $user_id) {
                    $status  = 'error';
                    $message = 'You can not booked your own property.'; 
                }
                else {
                    /*check that user is bookied their own property or not*/
                    $start_date               = date('Y-m-d',strtotime($request->input('start_date')));
                    $end_date                 = date('Y-m-d',strtotime($request->input('end_date')));
                    $user_currency            = $request->input('user_currency','INR');
                    $guests                   = $request->input('no_of_guest',0);
                    $property_type_slug       = $request->input('property_type_slug');
                    $available_no_of_slots    = $request->input('available_no_of_slots',0);
                    $available_no_of_employee = $request->input('available_no_of_employee',0);
                    $available_no_of_room     = $request->input('available_no_of_room',0);
                    $available_no_of_desk     = $request->input('available_no_of_desk',0);
                    $available_no_of_cubicles = $request->input('available_no_of_cubicles',0);
                    $coupon_code              = $request->input('coupon_code');

                    // coupon code starts
                    if(isset($coupon_code) && !empty($coupon_code) && $coupon_code != null) {
                        $obj_coupon = $this->CouponModel->where('coupon_code', '=', $coupon_code)
                                                        ->where('status', '1')
                                                        ->where('global_expiry', '>=',date("Y-m-d H:i:s"))
                                                        ->whereDoesntHave('coupon_code_used', function($query) use ($user_id){
                                                            $query->where('user_id', '=', $user_id);
                                                        })->first();
                        
                        if($obj_coupon) {
                            $arr_coupon = $obj_coupon->toArray();
                            $coupon_status = 'apply';
                        }

                        if($coupon_status == 'apply') {
                            if($arr_coupon['discount_type'] == 2) {
                                $discount_type = 'percentage';
                                $discount_rate = $arr_coupon['discount'];
                            } else if($arr_coupon['discount_type'] == 1) {
                                $discount_type = 'amount';
                                $discount_rate = $arr_coupon['discount'];
                            }
                        }
                    }
                    // coupon code ends

                    $obj_admin_commission = $this->SiteSettingsModel->first();
                    if($obj_admin_commission) {
                        $arr_admin_commission = $obj_admin_commission->toArray();

                        $arr_calculation_result = $this->PropertyRateCalculatorService->calculate_rate_api($start_date, $end_date, $property_id, $discount_type, $discount_rate, $user_currency,$guests, $property_type_slug, $available_no_of_slots, $available_no_of_employee, $available_no_of_room, $available_no_of_desk, $available_no_of_cubicles);

                        if ($property_type_slug == 'warehouse') {
                            $property_amount = $arr_calculation_result['price_per_sqft'];
                        }
                        else if ($property_type_slug == 'office-space') {
                            $property_amount = $arr_calculation_result['price_per_office'];
                        }
                        else {
                            $property_amount = $arr_calculation_result['price_per_night'];
                        }

                        $service_fee_gst_percentage = isset($arr_calculation_result['service_fee_gst_percentage']) ? $arr_calculation_result['service_fee_gst_percentage'] : '';
                        $service_fee_percentage     = isset($arr_calculation_result['service_fee_percentage']) ? $arr_calculation_result['service_fee_percentage'] : '';
                        $service_fee_gst_amount     = isset($arr_calculation_result['service_fee_gst_amount']) ? $arr_calculation_result['service_fee_gst_amount'] : '';
                        $service_fee                = isset($arr_calculation_result['service_fee']) ? $arr_calculation_result['service_fee'] : '';
                        $gst_amount                 = isset($arr_calculation_result['gst_amount']) ? $arr_calculation_result['gst_amount'] : '';
                        $gst_percentage             = isset($arr_calculation_result['gst']) ? $arr_calculation_result['gst'] : '';
                        
                        $arr_booking_data['property_id']        = $property_id;
                        $arr_booking_data['property_owner_id']  = $user_id;
                        $arr_booking_data['property_booked_by'] = $this->user_id;
                        $arr_booking_data['coupon_code_id']     = '';
                        $arr_booking_data['check_in_date']      = $start_date;
                        $arr_booking_data['check_out_date']     = $end_date;
                        $arr_booking_data['no_of_guest']        = $guests;

                        /*Change By Kavita*/
                        $arr_booking_data['property_type_slug']   = $property_type_slug;
                        $arr_booking_data['selected_no_of_slots'] = $available_no_of_slots;
                        $arr_booking_data['selected_of_employee'] = $available_no_of_employee;
                        $arr_booking_data['no_of_days']           = isset($arr_calculation_result['number_of_nights']) ? $arr_calculation_result['number_of_nights'] : '';
                        $arr_booking_data['property_amount']      = $property_amount;
                        $arr_booking_data['coupen_code_amount']   = isset($arr_calculation_result['discount_price']) ? $arr_calculation_result['discount_price'] : '';
                        $arr_booking_data['total_night_price']    = isset($arr_calculation_result['total_night_price']) ? $arr_calculation_result['total_night_price'] : '';
                        $arr_booking_data['total_amount']         = isset($arr_calculation_result['total_payble_amount']) ? $arr_calculation_result['total_payble_amount'] : '';
                        $arr_booking_data['booking_status']       = 3; //awaiting
                        $arr_booking_data['admin_commission']     = isset($arr_admin_commission['admin_commission']) ? $arr_admin_commission['admin_commission'] : '';

                        $arr_booking_data['gst_percentage']             = $gst_percentage;
                        $arr_booking_data['gst_amount']                 = abs(number_format($gst_amount, 2, '.', ''));
                        $arr_booking_data['service_fee_gst_percentage'] = $service_fee_gst_percentage;
                        $arr_booking_data['service_fee_percentage']     = $service_fee_percentage;
                        $arr_booking_data['service_fee_gst_amount']     = $service_fee_gst_amount;
                        $arr_booking_data['service_fee']                = abs(number_format($service_fee, 2, '.', ''));

                        $room_amount     = isset($arr_calculation_result['room_price']) ? $arr_calculation_result['room_price'] : 0;
                        $desk_amount     = isset($arr_calculation_result['desk_price']) ? $arr_calculation_result['desk_price'] : 0;
                        $cubicles_amount = isset($arr_calculation_result['cubicles_price']) ? $arr_calculation_result['cubicles_price'] : 0;

                        $arr_booking_data['selected_of_room']     = $available_no_of_room;
                        $arr_booking_data['selected_of_desk']     = $available_no_of_desk;
                        $arr_booking_data['selected_of_cubicles'] = $available_no_of_cubicles;
                        $arr_booking_data['room_amount']          = abs(number_format($room_amount, 2, '.', ''));
                        $arr_booking_data['desk_amount']          = abs(number_format($desk_amount, 2, '.', ''));
                        $arr_booking_data['cubicles_amount']      = abs(number_format($cubicles_amount, 2, '.', ''));

                        $booking_status  = $this->BookingModel->create($arr_booking_data);
                        $last_booking_id = $booking_status->id;
                        $booking_id      = 'B'.str_pad($last_booking_id,6,"0",STR_PAD_LEFT);

                        $this->BookingModel->where('id','=',$last_booking_id)->update(array('booking_id'=>$booking_id));

                        if($booking_status) {
                            $obj_user_data = $this->UserModel->where('id', $user_id)->first();
                            if($obj_user_data) {
                                $arr_user = $obj_user_data->toArray();
                            }

                            $arr_built_content = array(
                                    'USER_NAME' => isset($this->user_first_name) ? $this->user_first_name : 'NA',
                                    'SUBJECT'   => "New booking request for your property."
                                );

                            $arr_notify_data['arr_built_content']  = $arr_built_content;
                            $arr_notify_data['notify_template_id'] = '10';
                            $arr_notify_data['sender_id']          = $this->user_id;
                            $arr_notify_data['sender_type']        = '1';
                            $arr_notify_data['receiver_type']      = '4';
                            $arr_notify_data['receiver_id']        = $user_id;
                            $arr_notify_data['url']                = "/my-booking/new";

                            $this->NotificationService->send_notification($arr_notify_data);

                            $type = get_notification_type_of_user($user_id);

                            if(isset($type) && !empty($type)) {
                                // for mail 
                                if($type['notification_by_email'] == 'on') {
                                    $arr_built_content = [
                                            'USER_NAME'    => isset($arr_user['display_name'])?ucfirst($arr_user['display_name']):'NA',
                                            'Email'        => isset($arr_user['email'])?ucfirst($arr_user['email']):'NA',
                                            'MESSAGE'      => "",
                                            'PROJECT_NAME' => config('app.project.name')
                                        ];

                                    $arr_mail_data                      = [];
                                    $arr_mail_data['email_template_id'] = '14';
                                    $arr_mail_data['arr_built_content'] = $arr_built_content;
                                    $arr_mail_data['user']              = [ 'email' => isset($arr_user['email']) ? ucfirst($arr_user['email']) : 'NA', 'first_name' => isset($arr_user['display_name']) ? ucfirst($arr_user['display_name']) : 'NA'];

                                    $this->EmailService->send_mail($arr_mail_data);
                                }

                                // for sms 
                                if($type['notification_by_sms'] == 'on') {
                                    
                                    $country_code  = isset($arr_user['country_code']) ? $arr_user['country_code'] : '';
                                    $mobile_number = isset($arr_user['mobile_number']) ? $arr_user['mobile_number'] : '';

                                    $arr_sms_data                  = [];
                                    $arr_sms_data['msg']           = "New booking request for your property.";
                                    $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                                    $this->SMSService->send_SMS($arr_sms_data);
                                }

                                // for push notification
                                if($type['notification_by_push'] == 'on') {
                                    $headings = 'Property is booked successfully';
                                    $content  = 'Property is booked successfully, make payment to confirm it';
                                    $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                                }
                            }

                            $data['booking_id'] = $last_booking_id;
                            $status  = 'success';
                            $message = 'Property is booked successfully, make payment to confirm it'; 
                            return $this->build_response($status,$message,$data);
                        } else {
                            $status  = 'error';
                            $message = 'Error occure while booking a property.'; 
                        }
                    } else {
                        $status  = 'error';
                        $message = 'Error occured while booking property.'; 
                    }
                }
            }
        }
        else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }
        return $this->build_response($status,$message);
    }

    public function submit_review(Request $request)
    {
        $arr_rules = array();
        $status    = FALSE;
        $review_id = '';

        $arr_rules['property_id'] = "required";
        $arr_rules['rating']      = "required";
        $arr_rules['comment']     = "required";

        $user_id = $this->user_id;
        if (isset($user_id) && $user_id != '') {
            $validator = Validator::make($request->all(),$arr_rules);

            if($validator->fails()) {
                $status  = 'error';
                $message = 'fill all required fields'; 
            }
            else {
                $property_id = $request->input('property_id');
                $booking_id  = $request->input('booking_id'); 
                $rating      = $request->input('rating');
                $comment     = $request->input('comment');
                $review_id   = $request->input('review_id');

                $arr_review_data['property_id']    = $property_id;
                $arr_review_data['booking_id']     = $booking_id;
                $arr_review_data['rating_user_id'] = $user_id;
                $arr_review_data['rating']         = $rating;
                $arr_review_data['message']        = $comment;
                
                $check_review = array(
                                        'property_id'    => $property_id,
                                        'rating_user_id' => $user_id,
                                        'booking_id'     => $booking_id
                                    );

                $obj_check_reviews = $this->ReviewRatingModel->where($check_review)->get();

                if(isset($obj_check_reviews) && isset($review_id) && $review_id != '') {
                    $status = $this->ReviewRatingModel->where('id',$review_id)->update(['message'=>$comment,'rating'=>$rating]);
                    if($status) {
                        $status  = 'success';
                        $message = 'Review & Rating updated successfully.'; 
                    }
                    else {
                        $status  = 'error';
                        $message = 'Problem occured, while updating reviews';
                    }
                }
                else {
                    $booking_review = $this->BookingModel->where(array('property_id' => $property_id,'property_booked_by' => $user_id,'booking_status' => '5'))->get();

                    if(count($booking_review)) {
                        $status = $this->ReviewRatingModel->create($arr_review_data);
                        if($status) {
                            $status  = 'success';
                            $message = 'Review & Rating Added successfully.'; 
                        }
                        else {
                            $status  = 'error';
                            $message = 'Problem occured, while adding reviews';
                        }
                    }
                    else {
                        $status  = 'error';
                        $message = 'You are not able to add review & ratings';
                    }
                }
            }
        }
        else {
            $status   = 'error';
            $message  = 'Token expired, user not found.';
        }
        return $this->build_response($status,$message);
    }

    public function new_booking(Request $request)
    {
        $arr_booking = $property_data = $arr_pagination = $arr_data = [];

        $status  = 'error';
        $message = 'Record not found';

        $user_id = $this->user_id;
        if (isset($user_id) && $user_id != '') {
            $user_type   = $request->input('user_type');

            $obj_booking = $this->BookingModel->with('property_details', 'user_details')
                                                ->has('transaction_details')
                                              ->where(function($query) use($user_type) {
                                                    if($user_type == '1') {
                                                        $query->where('property_booked_by', $this->user_id);
                                                    } else if($user_type == '4') {
                                                        $query->where('property_owner_id', $this->user_id);
                                                    }
                                               })
                                              ->where(function($query) {
                                                    //$query->where('booking_status','=', '3');
                                                    $query->orWhere('booking_status','=', '4');
                                                })
                                              ->orderBy('id', 'DESC')
                                              ->paginate(5);    

            if (isset($obj_booking) && $obj_booking!=null) {
                $status  = 'success';
                $message = 'Record get successfully';
                $arr_pagination = $obj_booking->toArray();

                $arr_data['total']         = $arr_pagination['total'];
                $arr_data['per_page']      = $arr_pagination['per_page'];
                $arr_data['current_page']  = $arr_pagination['current_page'];
                $arr_data['last_page']     = $arr_pagination['last_page'];
                $arr_data['next_page_url'] = $arr_pagination['next_page_url'];
                $arr_data['prev_page_url'] = $arr_pagination['prev_page_url'];
                $arr_data['from']          = $arr_pagination['from'];
                $arr_data['to']            = $arr_pagination['to'];

                if (isset($arr_pagination['data']) && count($arr_pagination['data'])>0) {
                    foreach ($arr_pagination['data'] as $key => $property) {
                        $property_data[$key]['id']                 = $property['id'];
                        $property_data[$key]['property_id']        = $property['property_id'];
                        $property_data[$key]['property_name_slug'] = $property['property_details']['property_name_slug'];
                        $property_data[$key]['booking_id']         = $property['booking_id'];
                        $property_data[$key]['property_name']      = isset($property['property_details']['property_name']) ? $property['property_details']['property_name'] : '';
                        $property_data[$key]['booking_status']     = isset($property['booking_status']) ? $property['booking_status'] : '';
                        $property_data[$key]['booking_date']       = isset($property['created_at']) ? get_added_on_date($property['created_at']) : '';
                        $property_data[$key]['check_in_date']      = isset($property['check_in_date']) ? get_added_on_date($property['check_in_date']) : '';
                        $property_data[$key]['check_out_date']     = isset($property['check_out_date']) ? get_added_on_date($property['check_out_date']) : '';
                        $property_data[$key]['currency']           = isset($property['property_details']['currency']) ? $property['property_details']['currency'] : '';
                        $property_data[$key]['total_amount']       = isset($property['total_amount']) ? number_format($property['total_amount'],'2','.','' ) : '';
                    } 
                } else {
                    $status  = 'error';
                    $message = 'Record not found';
                }
            } else {
                $status  = 'error';
                $message = 'Record not found';
            }  
        }
        else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }

        $arr_data['property_data'] = $property_data;
        return $this->build_response($status,$message,$arr_data);
    }

    public function booking_details(Request $request)
    {
        $single_currency = '';
        $arr_booking = $arr_data = $booking_data = $arr_user = $user_data = [];
        
        $status  = 'error';
        $message = 'Record not found.';

        $booking_id = $request->input('booking_id');
        if (isset($this->user_id) && $this->user_id != '') {
            if (isset($booking_id) && $booking_id != '') {
                
                /*Pending Point*/
                $obj_user = $this->UserModel->where('id', $this->user_id)->first();
                if($obj_user) {
                    $arr_user = $obj_user->toArray();
                    $user_data['user_id']       = $arr_user['id'];
                    $user_data['user_name']     = $arr_user['user_name'];
                    $user_data['first_name']    = $arr_user['first_name'];
                    $user_data['last_name']     = $arr_user['last_name'];
                    $user_data['country_code']  = $arr_user['country_code'];
                    $user_data['mobile_number'] = $arr_user['mobile_number'];
                    $user_data['email']         = $arr_user['email'];
                    $user_data['wallet_amount'] = $arr_user['wallet_amount'];
                }

                $obj_booking = $this->BookingModel->with('property_details', 'user_details')
                                                  ->where('id','=',$booking_id)
                                                  ->first();

                if(isset($obj_booking) && $obj_booking != null) {
                    $arr_booking = $obj_booking->toArray();

                    if(isset($arr_booking) && count($arr_booking)>0) {
                        $status  = 'success';
                        $message = 'Record get successfully.';

                        $booking_data['property_name']        = $arr_booking['property_details']['property_name'];
                        $booking_data['booking_status']       = isset($arr_booking['booking_status']) ? $arr_booking['booking_status'] : '';
                        $booking_data['currency']             = isset($arr_booking['property_details']['currency']) ? $arr_booking['property_details']['currency'] : '';
                        $booking_data['total_amount']         = isset($arr_booking['total_amount']) ? number_format($arr_booking['total_amount'],'2','.','' ) : '';
                        $booking_data['booking_date']         = isset($arr_booking['created_at']) ? get_added_on_date($arr_booking['created_at']) : '';
                        $booking_data['check_in_date']        = isset($arr_booking['check_in_date']) ? get_added_on_date($arr_booking['check_in_date']) : '';
                        $booking_data['check_out_date']       = isset($arr_booking['check_out_date']) ? get_added_on_date($arr_booking['check_out_date']) : '';
                        $booking_data['reject_reason']        = isset($booking['reject_reason']) ? $booking['reject_reason'] : '';
                        
                        $booking_data['no_of_days']           = $arr_booking['no_of_days'];
                        $booking_data['property_amount']      = $arr_booking['property_amount'];
                        $booking_data['gst_percent_amount']   = $arr_booking['gst_amount'] * 100/100;
                        $booking_data['gst_amount']           = $arr_booking['gst_amount'] / 100;
                        $booking_data['service_fee']          = $arr_booking['service_fee'];
                        $booking_data['total_night_price']    = $arr_booking['property_details']['price_per_night'] * $arr_booking['no_of_days'];
                        $booking_data['total_service_amount'] = ($arr_booking['gst_amount']/100)*$booking_data['total_night_price'];
                        $booking_data['total_amount']         = $booking_data['total_night_price'] + $booking_data['total_service_amount'];

                        $currency_code = isset($arr_booking['property_details']['currency_code']) ? $arr_booking['property_details']['currency_code'] : '';
                        $currency     = $arr_booking['property_details']['currency'];
                        $total_amount = $booking_data['total_night_price'] + $booking_data['total_service_amount'];

                        if($currency_code != 'INR' && $currency != '') {
                            $inr_currency    = currencyConverter($currency_code, 'INR', $total_amount);
                            $single_currency = currencyConverter($currency_code, 'INR', '1');
                        }
                        else {
                            $inr_currency = $total_amount;
                        }

                        $needed_amount     = $inr_currency - $arr_user['wallet_amount'];
                        $property_currency = currencyConverter('INR', $currency_code, $needed_amount);

                        $booking_data['single_currency']   = $single_currency;
                        $booking_data['inr_currency']      = $inr_currency;
                        $booking_data['property_currency'] = $property_currency;

                        if($currency_code != 'INR') {
                            $booking_data['total_convert_in_INR'] = currencyConverter($currency_code, 'INR', $total_amount);
                        }   
                    }
                }

                $obj_property_review = $this->ReviewRatingModel->where('status','=','1')->get();

                if(isset($obj_property_review) && $obj_property_review != null) {
                    $total = $count = 0;
                    $tmp_str_rating = '';
                    $arr_property_review = $obj_property_review->toArray();

                    if(isset($arr_property_review)) {
                        foreach($arr_property_review as $rating) {
                            if($rating['property_id'] == $arr_booking['property_details']['id']) {
                                $total += floatval($rating['rating']);
                                $count++;
                            }
                        }
                    }
                  
                    if($count != 0) {
                        $booking_data['no_reviews'] = $total/$count;
                        $booking_data['review_avg'] = 5-number_format(($total/$count),1);
                    }
                    else {
                        $booking_data['no_reviews'] = 0;
                    }
                }
            }
            else {
                $status  = 'error';
                $message = 'Booking id should not be blank.';
            }
        }
        else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }
        $arr_data['booking_data'] = $booking_data;
        $arr_data['user_data']    = $user_data;
        return $this->build_response($status,$message,$arr_data);
    }

    public function confirmed_booking(Request $request)
    {
        $property_data = $arr_pagination = $arr_data = [];
        
        $status  = 'error';
        $message = 'Record not found';
        $user_id = $this->user_id;

        if (isset($user_id) && $user_id != '') {
            $user_type = $request->input('user_type');

            $obj_booking = $this->BookingModel->with('property_details', 'user_details')
                                                ->where(function($query) use($user_type){
                                                    if ($user_type == '1') {
                                                        $query->where('property_booked_by', $this->user_id);
                                                    } else if($user_type == '4') {
                                                        $query->where('property_owner_id', $this->user_id);
                                                    }
                                                })
                                                ->where(function($query){
                                                    //$query->where('booking_status','=',1);
                                                    $query->where('booking_status','=','5');
                                                })
                                                ->where('check_in_date', '>=', date("Y-m-d"))
                                                ->orderBy('check_in_date', 'DESC')
                                                ->paginate(5);

            if(isset($obj_booking) && $obj_booking != null) {
                $arr_pagination = $obj_booking->toArray();
                
                $arr_data['total']         = $arr_pagination['total'];
                $arr_data['per_page']      = $arr_pagination['per_page'];
                $arr_data['current_page']  = $arr_pagination['current_page'];
                $arr_data['last_page']     = $arr_pagination['last_page'];
                $arr_data['next_page_url'] = $arr_pagination['next_page_url'];
                $arr_data['prev_page_url'] = $arr_pagination['prev_page_url'];
                $arr_data['from']          = $arr_pagination['from'];
                $arr_data['to']            = $arr_pagination['to'];

                if (isset($arr_pagination['data']) && count($arr_pagination['data'])>0) {
                    foreach ($arr_pagination['data'] as $key => $property) {
                        $property_data[$key]['id']                 = $property['id'];
                        $property_data[$key]['property_id']        = $property['property_id'];
                        $property_data[$key]['property_name_slug'] = $property['property_details']['property_name_slug'];
                        $property_data[$key]['booking_id']         = $property['booking_id'];
                        $property_data[$key]['property_name']      = isset($property['property_details']['property_name']) ? $property['property_details']['property_name'] : '';
                        $property_data[$key]['booking_status']     = isset($property['booking_status']) ? $property['booking_status'] : '';
                        $property_data[$key]['booking_date']       = isset($property['created_at']) ? get_added_on_date($property['created_at']) : '';
                        $property_data[$key]['check_in_date']      = isset($property['check_in_date']) ? get_added_on_date($property['check_in_date']) : '';
                        $property_data[$key]['check_out_date']     = isset($property['check_out_date']) ? get_added_on_date($property['check_out_date']) : '';
                        $property_data[$key]['currency']           = isset($property['property_details']['currency']) ? $property['property_details']['currency'] : '';
                        $property_data[$key]['total_amount']       = isset($property['total_amount']) ? number_format($property['total_amount'],'2','.','' ) : '';
                    } 
                    $status  = 'success';
                    $message = 'Record get successfully';
                }
                else {
                    $status  = 'error';
                    $message = 'Record not found';
                }
            }
        }
        else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }    
        $arr_data['property_data'] = $property_data;
        return $this->build_response($status,$message,$arr_data);
    }

    public function completed_booking(Request $request)
    {
        $arr_booking = $obj_pagination = $property_data = [];
        $status  = 'error';
        $message = 'Record not found';
        if (isset($this->user_id) && $this->user_id != '')  {
            $user_type = $request->input('user_type');

            $obj_booking = $this->BookingModel->with('property_details', 'user_details')
                                                ->where(function($query) use($user_type){
                                                    if($user_type == 1) {
                                                        $query->where('property_booked_by', $this->user_id);
                                                    } else if($user_type == 4) {
                                                        $query->where('property_owner_id', $this->user_id);
                                                    }
                                                })
                                                ->where('booking_status', '=', '5')
                                                ->where('check_in_date', '<', date('Y-m-d'))
                                                ->orderBy('check_in_date', 'DESC')
                                                ->paginate(5);

            if (isset($obj_booking) && $obj_booking != null) {
                $status  = 'success';
                $message = 'Record get successfully';

                $arr_pagination = $obj_booking->toArray();

                $arr_data['total']         = $arr_pagination['total'];
                $arr_data['per_page']      = $arr_pagination['per_page'];
                $arr_data['current_page']  = $arr_pagination['current_page'];
                $arr_data['last_page']     = $arr_pagination['last_page'];
                $arr_data['next_page_url'] = $arr_pagination['next_page_url'];
                $arr_data['prev_page_url'] = $arr_pagination['prev_page_url'];
                $arr_data['from']          = $arr_pagination['from'];
                $arr_data['to']            = $arr_pagination['to'];

                if (isset($arr_pagination['data']) && count($arr_pagination['data'])>0) {
                    foreach ($arr_pagination['data'] as $key => $property) {
                        $property_data[$key]['id']                 = $property['id'];
                        $property_data[$key]['property_id']        = $property['property_id'];
                        $property_data[$key]['property_name_slug'] = $property['property_details']['property_name_slug'];
                        $property_data[$key]['booking_id']         = $property['booking_id'];
                        $property_data[$key]['property_name']      = isset($property['property_details']['property_name']) ? $property['property_details']['property_name'] : '';
                        $property_data[$key]['booking_status']     = isset($property['booking_status']) ? $property['booking_status'] : '';
                        $property_data[$key]['booking_date']       = isset($property['created_at']) ? get_added_on_date($property['created_at']) : '';
                        $property_data[$key]['check_in_date']      = isset($property['check_in_date']) ? get_added_on_date($property['check_in_date']) : '';
                        $property_data[$key]['check_out_date']     = isset($property['check_out_date']) ? get_added_on_date($property['check_out_date']) : '';
                        $property_data[$key]['currency']           = isset($property['property_details']['currency']) ? $property['property_details']['currency'] : '';
                        $property_data[$key]['total_amount']       = isset($property['total_amount']) ? number_format($property['total_amount'],'2','.','' ) : '';
                        
                        $property_img_data = $this->PropertyImagesModel->where('property_id',$property['property_id'])->first();
                        $property_data[$key]['property_image'] = $this->property_image_public_path.$property_img_data['image'];
                    }
                }
                else {
                    $status  = 'error';
                    $message = 'Record not found';
                }
            } else {
                $status  = 'error';
                $message = 'Record not found';
            }
        } else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }
        $arr_data['property_data'] = $property_data;
        return $this->build_response($status,$message,$arr_data);
    }

    public function cancelled_booking(Request $request)
    {
        $arr_booking = $arr_pagination = $property_data  = [];

        $status     = 'error';
        $message    = 'Record not found';
        if (isset($this->user_id) && $this->user_id != '') {
            $user_type = $request->input('user_type');

            $obj_booking = $this->BookingModel->with('property_details', 'user_details')
                                              ->where(function($query) use($user_type){
                                                if($user_type == 1) {
                                                    $query->where('property_booked_by', $this->user_id);
                                                }
                                                else if($user_type == 4) {
                                                    $query->where('property_owner_id', $this->user_id);
                                                }
                                              })
                                              ->where(function($query) {
                                                $query->where('booking_status','=',6);
                                                $query->orWhere('booking_status','=',7);
                                              })
                                              ->orderBy('id', 'DESC')
                                              ->paginate(5);

            if(isset($obj_booking) && $obj_booking != null) {
                $status  = 'success';
                $message = 'Record get successfully';

                $arr_pagination = $obj_booking->toArray();
                
                $arr_data['total']         = $arr_pagination['total'];
                $arr_data['per_page']      = $arr_pagination['per_page'];
                $arr_data['current_page']  = $arr_pagination['current_page'];
                $arr_data['last_page']     = $arr_pagination['last_page'];
                $arr_data['next_page_url'] = $arr_pagination['next_page_url'];
                $arr_data['prev_page_url'] = $arr_pagination['prev_page_url'];
                $arr_data['from']          = $arr_pagination['from'];
                $arr_data['to']            = $arr_pagination['to'];

                if (isset($arr_pagination['data']) && count($arr_pagination['data'])>0) {
                    foreach ($arr_pagination['data'] as $key => $property) {
                        $property_data[$key]['id']                 = $property['id'];
                        $property_data[$key]['property_id']        = $property['property_id'];
                        $property_data[$key]['property_name_slug'] = $property['property_details']['property_name_slug'];
                        $property_data[$key]['booking_id']         = $property['booking_id'];
                        $property_data[$key]['property_name']      = isset($property['property_details']['property_name']) ? $property['property_details']['property_name'] : '';
                        $property_data[$key]['booking_status']     = isset($property['booking_status']) ? $property['booking_status'] : '';
                        $property_data[$key]['booking_date']       = isset($property['created_at']) ? get_added_on_date($property['created_at']) : '';
                        $property_data[$key]['check_in_date']      = isset($property['check_in_date']) ? get_added_on_date($property['check_in_date']) : '';
                        $property_data[$key]['check_out_date']     = isset($property['check_out_date']) ? get_added_on_date($property['check_out_date']) : '';
                        $property_data[$key]['currency']           = isset($property['property_details']['currency']) ? $property['property_details']['currency'] : '';
                        $property_data[$key]['refund_amount']      = isset($property['refund_amount']) ? number_format($property['refund_amount'],'2','.','' ) : '';
                        $property_data[$key]['total_amount']       = isset($property['total_amount']) ? number_format($property['total_amount'],'2','.','' ) : '';
                    } 
                }
                else {
                    $status  = 'error';
                    $message = 'Record not found';
                }
            }
            else {
                $status  = 'error';
                $message = 'Record not found';
            }
        }
        else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }

        $arr_data['property_data'] = $property_data;
        return $this->build_response($status,$message,$arr_data);
    }

    public function accept_booking(Request $request)
    {
        $arr_booking = [];
        $booking_id = $notification_url = '';

        if (isset($this->user_id) && $this->user_id != '') {
            $booking_id = $request->input('booking_id');
            $status     = $request->input('status');

            if ($status != '' && !empty($status) && $booking_id != '' && !empty($booking_id)) {
                $obj_booking = $this->BookingModel->where('id', $booking_id)->first();
                if($obj_booking) {
                    $arr_booking = $obj_booking->toArray();

                    if($status == 'accept') {
                        $update_data['booking_status'] = '1';
                        $booking_status   = 'accepted';
                        $notification_url = "";
                    } else if($status == 'reject') {
                        $update_data['booking_status'] = '4';
                        $booking_status   = 'rejected';
                        $notification_url = "";
                    } else {
                        return $this->build_response('error', "Invalid action ".$status." can't be performed");
                    }
                    
                    $update_booking = $this->BookingModel->where('id', $booking_id)->update($update_data);
                    $obj_booked_by = $this->UserModel->where('id', $arr_booking['property_booked_by'])->first();
                    
                    if ($obj_booked_by) {
                        $arr_booked_by = $obj_booked_by->toArray();
                    }

                    $arr_built_content = array(
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
                    $this->NotificationService->send_notification($arr_notify_data);

                    $type = get_notification_type_of_user($arr_booking['property_booked_by']);

                    if (isset($type) && !empty($type)) {
                        
                        if ($type['notification_by_email'] == 'on') {
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
                            $arr_mail_data['user']              = [
                                                                    'email' => isset($arr_booked_by['email']) ? ucfirst($arr_booked_by['email']) : 'NA',
                                                                    'first_name' => isset($arr_booked_by['display_name']) ? ucfirst($arr_booked_by['display_name']) : 'NA'
                                                                ];
                            $this->EmailService->send_mail($arr_mail_data);
                        }

                        if ($type['notification_by_sms'] == 'on') {
                            
                            $country_code  = isset($arr_booked_by['country_code']) ? $arr_booked_by['country_code'] : '';
                            $mobile_number = isset($arr_booked_by['mobile_number']) ? $arr_booked_by['mobile_number'] : '';

                            $arr_sms_data                  = [];
                            $arr_sms_data['msg']           = "Your booking has been ".$booking_status." by host.";
                            $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                            $this->SMSService->send_SMS($arr_sms_data);
                        }

                        if ($type['notification_by_push'] == 'on') {
                            $headings = 'Booking Update';
                            $content  = "Your booking has been ".$booking_status." by host.";
                            $user_id  = $arr_booking['property_booked_by'];
                            $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                        }
                    }

                    if ($status == 'accept') {
                        return $this->build_response('success', "Booking accepted successfully");                        
                    } else {
                        return $this->build_response('success', "Booking rejected successfully");
                    }
                } else {
                    return $this->build_response('error', "Record not found");
                }    
            } else {
                return $this->build_response('error', "Something went wrong. Try again later");
            }
        } else {
            return $this->build_response('error', "Token expired, user not found");
        }
        return $this->build_response($status,$message);
    }

    public function cancel_booking(Request $request)
    {
        $notification_url = '';

        if (isset($this->user_id) && $this->user_id != '') {
            $booking_id = $request->input('booking_id');
            
            if(isset($booking_id) && $booking_id != '') {

                $arr_data['booking_id']        = $booking_id;
                $arr_data['query_subject']     = trim($request->input('cancel_subject'));
                $arr_data['query_description'] = trim($request->input('cancel_reason'));
                $arr_data['user_id']           = $this->user_id;
                $arr_data['support_level']     = 'L2';
                $this->SupportQueryModel->create($arr_data);

                $arr_support_built_content = array(
                                                'USER_NAME' => isset($this->user_first_name)?$this->user_first_name:'NA',
                                                'SUBJECT'   => trim($request->input('cancel_subject'))
                                            );

                $arr_support_notify_data['arr_built_content']  = $arr_support_built_content;   
                $arr_support_notify_data['notify_template_id'] = '4';   
                $arr_support_notify_data['sender_id']          = $this->user_id;
                $arr_support_notify_data['sender_type']        = $request->input('user_type');
                $arr_support_notify_data['receiver_type']      = '3';
                $arr_support_notify_data['url']                = '/ticket';

                $obj_support_team = $this->SupportTeamModel->where('support_level','L2')->get();
                
                if (count($obj_support_team) > 0) {
                    foreach ($obj_support_team as $row) {
                        $arr_support_notify_data['receiver_id'] = $row->id;
                        $this->NotificationService->send_notification($arr_support_notify_data);
                    }
                }

                $booking_status         = 'processing cancel';
                $data['booking_status'] = 7;
                $data['status_by']      = $this->user_id;

                $update_booking = $this->BookingModel->where('id', $booking_id)->update($data);
                
                if ($update_booking) {
                    $obj_booking = $this->BookingModel->where('id', $booking_id)->with('property_details')->first();
                    if ($obj_booking) {
                        $arr_booking = $obj_booking->toArray();
                    }

                    $obj_user_data = $this->UserModel->where('id', $arr_booking['property_booked_by'])->first();
                    if ($obj_user_data) {
                        $arr_user = $obj_user_data->toArray();
                    }

                    $arr_built_content = array(
                                'USER_NAME' => isset($arr_user['first_name']) ? $arr_user['first_name'] : 'NA',
                                'MESSAGE'   => "Your cancellation request is received and will process for the booking of ".$arr_booking['property_details']['property_name']
                            );

                    $arr_notify_data['arr_built_content']  = $arr_built_content;
                    $arr_notify_data['notify_template_id'] = '9';
                    $arr_notify_data['template_text']      = "Your cancellation request is received and will process for the booking of ".$arr_booking['property_details']['property_name'];
                    $arr_notify_data['sender_id']          = '1';
                    $arr_notify_data['sender_type']        = '2';
                    $arr_notify_data['receiver_type']      = $request->input('user_type');
                    $arr_notify_data['receiver_id']        = $this->user_id;
                    $arr_notify_data['url']                = $notification_url;
                    $notification_status = $this->NotificationService->send_notification($arr_notify_data);

                    $type = get_notification_type_of_user($this->user_id);

                    if (isset($type) && !empty($type)) {
                        if ($type['notification_by_email'] == 'on') {
                            $arr_built_content = [
                                    'USER_NAME'    => isset($arr_user['display_name'])?ucfirst($arr_user['display_name']):'NA',   
                                    'Email'        => isset($arr_user['email'])?ucfirst($arr_user['email']):'NA' ,  
                                    'SUBJECT'      => "Your cancellation request is received and will process for the booking of ".$arr_booking['property_details']['property_name'],
                                    'STATUS'       => $booking_status,
                                    'PROJECT_NAME' => config('app.project.name')
                                ];

                            $arr_mail_data                      = [];
                            $arr_mail_data['email_template_id'] = '15';
                            $arr_mail_data['arr_built_content'] = $arr_built_content;
                            $arr_mail_data['user']              = ['email' => isset($arr_user['email'])?ucfirst($arr_user['email']):'NA', 'first_name' => isset($arr_user['display_name'])?ucfirst($arr_user['display_name']):'NA'];

                            $this->EmailService->send_mail($arr_mail_data);
                        }

                        if ($type['notification_by_sms'] == 'on') {

                            $country_code  = isset($arr_user['country_code']) ? $arr_user['country_code'] : '';
                            $mobile_number = isset($arr_user['mobile_number']) ? $arr_user['mobile_number'] : '';

                            $arr_sms_data                  = [];
                            $arr_sms_data['msg']           = "Your cancellation request is received and will process for the booking of ".$arr_booking['property_details']['property_name'];
                            $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                            $this->SMSService->send_SMS($arr_sms_data);
                        }

                        if ($type['notification_by_push'] == 'on') {
                            $headings = 'Cancel booking request successfully send';
                            $content  = "Your cancellation request is received and will process for the booking of ".$arr_booking['property_details']['property_name'];
                            $user_id  = $this->user_id;
                            $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                        }
                    }
                    $status   = 'success';
                    $message  = 'Cancel booking request successfully send';
                } else {
                    $status   = 'error';
                    $message  = 'Something went wrong, please try again';
                }
            } else {
                $status   = 'error';
                $message  = 'Booking id should not be blank.';
            }
        } else {
            $status   = 'error';
            $message  = 'Token expired, user not found.';
        }  
        return $this->build_response($status,$message);  

    } // end process_cancel


    public function pay_booking(Request $request)
    {
        if ($this->user_id) {

            $obj_user = $this->UserModel->select('first_name')->where('id', $this->user_id)->first();
            $arr_user = $obj_user->toArray();
            $arr_rules['payment_type']   = "required";
            $arr_rules['booking_amount'] = "required";
            $arr_rules['booking_id']     = "required";

            $validator = validator::make($request->all(),$arr_rules);

            if ($validator->fails()) {
                return $this->build_response("Error","Please provide all required parameters");
            } else {
                $payment_type   = $request->input("payment_type");
                $payment_amount = $request->input('booking_amount');
                $booking_id     = $request->input('booking_id');
                
                if ($payment_type != "wallet" && $payment_type != "direct") {
                    return $this->build_response("Error","Invalid payment type");
                } else if($payment_type == "wallet"){
                    $wallet_amount = $request->input('wallet_amount');

                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $charactersLength = strlen($characters);
                    $randomString = '';
                    for ($i = 0; $i < 12; $i++) {
                        $randomString .= $characters[rand(0, $charactersLength - 1)];
                    }

                    $transaction_id = 'pay_A'.$randomString;

                    $check_transaction_id = $this->TransactionModel->where(['transaction_id' => $transaction_id])->get()->toArray();
                    if (count( $check_transaction_id) > 0) {
                        return $this->build_response("Error", "Transaction Id must be unique");
                    }

                } else if($payment_type == "direct") {
                    $transaction_id = $request->input('transaction_id');
                }

                $guest_invoice = $host_invoice = '';
                $notification_url = '/my-booking/completed';

                $guest_booking = $this->BookingModel->where('id', $booking_id)->with('property_details')->first();
                if ($guest_booking) {
                    $arr_booking = $guest_booking->toArray();
                }

                $arr_property_data = get_property_details($arr_booking['property_id']);
                // For Guest User
                $guest_transaction['transaction_id']   = $transaction_id;
                $guest_transaction['payment_type']     = ($payment_type == "wallet") ? $payment_type : "booking";
                $guest_transaction['user_id']          = $this->user_id;
                $guest_transaction['user_type']        = '1';
                $guest_transaction['amount']           = $payment_amount;
                $guest_transaction['booking_id']       = $arr_booking['id'];
                $guest_transaction['transaction_for']  = "Payment successfully paid for the ".$arr_booking['property_details']['property_name'];
                $guest_transaction['transaction_date'] = date('Y-m-d H:i:s');
                $guest_transaction = $this->TransactionModel->create($guest_transaction);

                if ($guest_transaction) {
                    $guest_user_data = $this->UserModel->where('id', $this->user_id)->first();
                    $utype = 'guest';

                    $guest_invoice = $this->generateInvoice($guest_transaction->id, $this->user_id, $arr_booking['property_owner_id'],$utype);

                    $this->TransactionModel->where('id',$guest_transaction->id)->update(['invoice' => $guest_invoice]);

                    $this->add_unavailability_dates($booking_id);

                    if ($guest_user_data) {
                        $guest_user = $guest_user_data->toArray();

                        if ($payment_type == "wallet") {
                            $new_amount = number_format($guest_user['wallet_amount'], 2, '.', '') - $payment_amount;
                            $guest_user_data->update(['wallet_amount' => $new_amount]);
                        }
                    }

                    $guest_built_content = array(
                            'USER_NAME' => isset($arr_user['first_name']) ? $arr_user['first_name'] : 'NA',
                            'MESSAGE'   => "Payment successfully paid for the ".$arr_booking['property_details']['property_name']
                        );

                    $guest_notify_data['arr_built_content']  = $guest_built_content;
                    $guest_notify_data['notify_template_id'] = '9';
                    $guest_notify_data['sender_id']          = '1';
                    $guest_notify_data['sender_type']        = '2';
                    $guest_notify_data['receiver_type']      = '1';
                    $guest_notify_data['receiver_id']        = $this->user_id;
                    $guest_notify_data['url']                = $notification_url;
                    $notification_status = $this->NotificationService->send_notification($guest_notify_data);

                    $type = get_notification_type_of_user($this->user_id);

                    if (isset($type) && !empty($type)) {
                        if ($type['notification_by_email'] == 'on') {
                            $guest_built_content = [
                                'USER_NAME' => isset($guest_user['display_name']) ? ucfirst($guest_user['display_name']) : 'NA',
                                'Email' => isset($guest_user['email']) ? ucfirst($guest_user['email']) : 'NA',
                                'MESSAGE' => "Payment successfully paid for the ".$arr_booking['property_details']['property_name'],
                                'PROJECT_NAME' => config('app.project.name'),
                                'NOTIFICATION_SUBJECT' => 'Notification'
                            ];

                            $guest_mail_data                      = [];
                            $guest_mail_data['email_template_id'] = '13';
                            $guest_mail_data['arr_built_content'] = $guest_built_content;
                            $guest_mail_data['user']              = [ 'email' => isset($guest_user['email']) ? ucfirst($guest_user['email']) : 'NA', 'first_name' => isset($guest_user['display_name']) ? ucfirst($guest_user['display_name']) : 'NA'];
                            $guest_mail_data['attachment']        = public_path('uploads/invoice/'.$guest_invoice);
                            $this->EmailService->send_invoice_mail($guest_mail_data);
                        }

                        if($type['notification_by_sms'] == 'on') {
                            $country_code  = isset($guest_user['country_code']) ? $guest_user['country_code'] : '';
                            $mobile_number = isset($guest_user['mobile_number']) ? $guest_user['mobile_number'] : '';
                            $guest_sms_data                  = [];
                            $guest_sms_data['msg']           = "Payment successfully paid for the ".$arr_booking['property_details']['property_name'];
                            $guest_sms_data['mobile_number'] = $country_code.$mobile_number;
                            $this->SMSService->send_SMS($guest_sms_data);
                        }

                        if($type['notification_by_push'] == 'on') {
                            $headings = 'Payment successfully paid';
                            $content  = "Payment successfully paid for the ".$arr_booking['property_details']['property_name'];
                            $user_id  = $this->user_id;
                            $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                        }
                    }
                }

                // For Host User
                $host_transaction['transaction_id']   = $transaction_id;
                $host_transaction['payment_type']     = 'wallet';
                $host_transaction['user_id']          = $arr_booking['property_owner_id'];
                $host_transaction['user_type']        = '2';
                $host_transaction['amount']           = $payment_amount;
                $host_transaction['booking_id']       = $arr_booking['id'];
                $host_transaction['transaction_for']  = "Payment successfully received for the ".$arr_booking['property_details']['property_name']." by the ".$arr_user['first_name'];
                $host_transaction['transaction_date'] = date('Y-m-d H:i:s');
                $host_transaction = $this->TransactionModel->create($host_transaction);

                if ($host_transaction) {
                    $host_user_data = $this->UserModel->where('id', $arr_booking['property_owner_id'])->first();
                    $utype = 'host';
                    $host_invoice = $this->generateInvoice($host_transaction->id, $arr_booking['property_owner_id'], $this->user_id,$utype);
                    $this->TransactionModel->where('id',$host_transaction->id)->update(['invoice' => $host_invoice]);

                    if($host_user_data) {
                        $host_user = $host_user_data->toArray();
                    }

                    $host_built_content = array(
                            'USER_NAME' => isset($host_user['first_name']) ? $host_user['first_name'] : 'NA',
                            'MESSAGE'   => "Payment successfully received for the ".$arr_booking['property_details']['property_name']." by the ".$arr_user['first_name']
                        );

                    $host_notify_data['arr_built_content']  = $host_built_content;
                    $host_notify_data['notify_template_id'] = '9';
                    $host_notify_data['sender_id']          = '1';
                    $host_notify_data['sender_type']        = '2';
                    $host_notify_data['receiver_type']      = '4';
                    $host_notify_data['receiver_id']        = $arr_booking['property_owner_id'];
                    $host_notify_data['url']                = $notification_url;
                    $notification_status = $this->NotificationService->send_notification($host_notify_data);

                    $type = get_notification_type_of_user($arr_booking['property_owner_id']);

                    if(isset($type) && !empty($type)) {
                        
                        // for mail
                        if($type['notification_by_email'] == 'on') {
                            $host_built_content = [
                                    'USER_NAME' => isset($host_user['display_name']) ? ucfirst($host_user['display_name']) : 'NA',
                                    'Email' => isset($host_user['email']) ? ucfirst($host_user['email']) : 'NA' ,
                                    'MESSAGE' => "Payment successfully received for the ".$arr_booking['property_details']['property_name']." by the ".$arr_user['first_name'],
                                    'PROJECT_NAME' => config('app.project.name'),
                                    'NOTIFICATION_SUBJECT' => 'Notification'
                                ];

                            $host_mail_data                      = [];
                            $host_mail_data['email_template_id'] = '13';
                            $host_mail_data['arr_built_content'] = $host_built_content;
                            $host_mail_data['user']              = ['email' => isset($host_user['email']) ? ucfirst($host_user['email']) : 'NA', 'first_name' => isset($host_user['display_name']) ? ucfirst($host_user['display_name']) : 'NA'];
                            $host_mail_data['attachment']        = public_path('uploads/invoice/'.$host_invoice);
                            $status = $this->EmailService->send_invoice_mail($host_mail_data);
                        }

                        // for sms
                        if($type['notification_by_sms'] == 'on') {
                            $country_code  = isset($host_user['country_code']) ? $host_user['country_code'] : '';
                            $mobile_number = isset($host_user['mobile_number']) ? $host_user['mobile_number'] : '';
                            $host_sms_data                  = [];
                            $host_sms_data['msg']           = "Payment successfully received for the ".$arr_booking['property_details']['property_name']." by the ".$arr_user['first_name'];
                            $host_sms_data['mobile_number'] = $country_code.$mobile_number;
                            $status = $this->SMSService->send_SMS($host_sms_data);
                        }

                        // for push notification
                        if($type['notification_by_push'] == 'on') {
                            $headings = 'Payment successfully received';
                            $content  = "Payment successfully received for the ".$arr_booking['property_details']['property_name']." by the ".$arr_user['first_name'];
                            $user_id  = $arr_booking['property_owner_id'];
                            $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                        }
                    }
                }

                if($guest_invoice != '' && $guest_invoice != null && $host_invoice != '' && $host_invoice != null ) {
                    $update_arr = array('booking_status'=>'5','payment_type'=>($payment_type=="wallet")?$payment_type:"booking");
                    $this->BookingModel->where('id', $booking_id)->update($update_arr);

                    /*Change by Kavita*/
                    if ($arr_booking['property_type_slug'] == 'warehouse') {
                        $selected_no_of_slots = $arr_property_data['available_no_of_slots'] - $arr_booking['selected_no_of_slots'];
                        $this->PropertyModel->where('id', $arr_booking['property_id'])->update(array('available_no_of_slots' => $selected_no_of_slots));
                    }
                    else if ($arr_booking['property_type_slug'] == 'office-space') {
                        $selected_of_employee = $arr_property_data['available_no_of_employee']-$arr_booking['selected_of_employee'];
                        $this->PropertyModel->where('id', $arr_booking['property_id'])->update(array('available_no_of_employee' => $selected_of_employee));
                    }
                    /*End*/

                    $arr_json['status'] = 'success';
                    return $this->build_response("Success", "Payment successful for the booking");
                } else {
                    return $this->build_response("Error", "Something went wrong. Please try again");
                }
            }
        } else {
            return $this->build_response("Error","Invalid user");
        }
    }

    public function generateInvoice($transaction_id = false, $guest_id = false, $host_id = false, $user_type = false)
    {
        $data = $host_data = $guest_data = [];
        $html = $view = $FileName = '';

        if (isset($transaction_id) && $transaction_id != false && isset($guest_id) && $guest_id != false && isset($host_id) && $host_id != false) {
            
            $obj_transaction = $this->TransactionModel->where('id', $transaction_id)->with('booking_details.property_details')->first();
            if ($obj_transaction) {
                $arr_transaction = $obj_transaction->toArray();
            }

            $guest_user_data = $this->UserModel->where('id', $guest_id)->first();
            if ($guest_user_data) {
                $guest_data = $guest_user_data->toArray();
            }

            $host_user_data = $this->UserModel->where('id', $host_id)->first();
            if ($host_user_data) {
                $host_data = $host_user_data->toArray();
                
                if( !empty( $host_data['gstin'] ) && $host_data['gstin'] != null ) {
                    $host_data['apply_gst'] = 'yes';
                } else {
                    $host_data['apply_gst'] = 'no';
                }
            }

            $data['logo']     = url('/front/images/logo-inner.png');
            $data['base_url'] = url('/');

            PDF::SetTitle(config('app.project.name'));

            if (isset($arr_transaction) && count($arr_transaction ) > 0) {

                $data['user_type']                  = $user_type;
                $data['admin_commission']           = $arr_transaction['booking_details']['admin_commission'];
                $data['gst_amount']                 = $arr_transaction['booking_details']['gst_amount'];
                $data['gst_percentage']             = $arr_transaction['booking_details']['gst_percentage'];
                $data['service_fee']                = $arr_transaction['booking_details']['service_fee'];
                $data['service_fee_gst_amount']     = $arr_transaction['booking_details']['service_fee_gst_amount'];
                $data['service_fee_percentage']     = $arr_transaction['booking_details']['service_fee_percentage'];
                $data['service_fee_gst_percentage'] = $arr_transaction['booking_details']['service_fee_gst_percentage'];
                $data['final_amount']               = $arr_transaction['amount'] - $data['service_fee'];
            }

            $view1 = view('invoice.new_invoice')->with(['guest_data' => $guest_data, 'host_data' => $host_data, 'transaction_data' => $arr_transaction, 'data' => $data]);

            $view2 = view('invoice.new_invoice2')->with(['guest_data' => $guest_data, 'host_data' => $host_data, 'transaction_data' => $arr_transaction, 'data' => $data]);

            $view3 = view('invoice.new_invoice3')->with(['guest_data' => $guest_data, 'host_data' => $host_data, 'transaction_data' => $arr_transaction, 'data' => $data]);
            
            // First Page 
            PDF::AddPage();
            $html1 = $view1->render();
            PDF::writeHTML($html1, true, false, true, false, 'L');

            // Second Page 
            PDF::AddPage();
            $html2 = $view2->render();
            PDF::writeHTML($html2, true, false, true, false, 'L');

            // Third Page 
            PDF::AddPage();
            $html3 = $view3->render();
            PDF::writeHTML($html3, true, false, true, false, 'L');

            $FileName = 'Invoice'.$transaction_id.$guest_data['id'].'.pdf';
            PDF::output(public_path('uploads/invoice/'.$FileName),'F');
            PDF::reset();
        }
        return $FileName;
    }

    public function add_unavailability_dates($booking_id)
    {
        if( !empty( $booking_id ) && $booking_id != null ) {
            $booking_data = $this->BookingModel->where('id',$booking_id)->first();
            $start_date   = $booking_data['check_in_date'];
            $end_date     = $booking_data['check_out_date'];

            $obj_property = $this->PropertyModel->where('id', $booking_data['property_id'])
                            ->select('id', 'property_type_id', 'employee', 'room', 'desk', 'cubicles', 'no_of_slots', 'no_of_employee', 'no_of_room', 'no_of_desk', 'no_of_cubicles', 'number_of_guest', 'price_per')
                            ->first();

            if( $obj_property ) {
                $arr_property = $obj_property->toArray();
                $property_type_slug = get_property_type_slug($arr_property['property_type_id']);

                if($property_type_slug == 'office-space') {
                    $total_available_employee = $total_available_room = $total_available_desk = $total_available_cubicles = 0;
                    
                    $employee = $arr_property['employee'];
                    $room     = $arr_property['room'];
                    $desk     = $arr_property['desk'];
                    $cubicles = $arr_property['cubicles'];

                    if( $employee == 'on' ) {
                        $employee_booking = $this->BookingModel->where('property_id', $booking_data['property_id'])
                        ->select('id', 'booking_id', 'property_id', 'check_in_date', 'check_out_date', 'property_type_slug', 'selected_of_employee', 'selected_of_room', 'selected_of_desk', 'selected_of_cubicles')
                        ->where('booking_status', '!=', 6)
                        ->whereRaw("((DATE('".$start_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE('".$end_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE(check_in_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."')) OR (DATE(check_out_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."'))) ")
                        ->sum('selected_of_employee');

                        $no_of_employee = $arr_property['no_of_employee'];

                        $total_available_employee = (int) $no_of_employee - (int) $employee_booking;
                    }

                    if( $room == 'on' ) {
                        $room_booking = $this->BookingModel->where('property_id', $booking_data['property_id'])
                        ->select('id', 'booking_id', 'property_id', 'check_in_date', 'check_out_date', 'property_type_slug', 'selected_of_employee', 'selected_of_room', 'selected_of_desk', 'selected_of_cubicles')
                        ->where('booking_status', '!=', 6)
                        ->whereRaw("((DATE('".$start_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE('".$end_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE(check_in_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."')) OR (DATE(check_out_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."'))) ")
                        ->sum('selected_of_room');

                        $no_of_room = $arr_property['no_of_room'];

                        $total_available_room = (int) $no_of_room - (int) $room_booking;
                    }

                    if( $desk == 'on' ) {
                        $desk_booking = $this->BookingModel->where('property_id', $booking_data['property_id'])
                        ->select('id', 'booking_id', 'property_id', 'check_in_date', 'check_out_date', 'property_type_slug', 'selected_of_employee', 'selected_of_room', 'selected_of_desk', 'selected_of_cubicles')
                        ->where('booking_status', '!=', 6)
                        ->whereRaw("((DATE('".$start_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE('".$end_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE(check_in_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."')) OR (DATE(check_out_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."'))) ")
                        ->sum('selected_of_desk');

                        $no_of_desk = $arr_property['no_of_desk'];

                        $total_available_desk = (int) $no_of_desk - (int) $desk_booking;
                    }

                    if( $cubicles == 'on' ) {
                        $cubicles_booking = $this->BookingModel->where('property_id', $booking_data['property_id'])
                        ->select('id', 'booking_id', 'property_id', 'check_in_date', 'check_out_date', 'property_type_slug', 'selected_of_employee', 'selected_of_room', 'selected_of_desk', 'selected_of_cubicles')
                        ->where('booking_status', '!=', 6)
                        ->whereRaw("((DATE('".$start_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE('".$end_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE(check_in_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."')) OR (DATE(check_out_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."'))) ")
                        ->sum('selected_of_cubicles');

                        $no_of_cubicles = $arr_property['no_of_cubicles'];

                        $total_available_cubicles = (int) $no_of_cubicles - (int) $cubicles_booking;
                    }

                    if($total_available_employee <= 0 && $total_available_room <= 0 && $total_available_desk <= 0 && $total_available_cubicles <= 0)
                    {
                        $dates['property_id'] = $booking_data['property_id'];
                        $dates['booking_id']  = $booking_id;
                        $dates['type']        = 'MONTHLY';
                        $dates['from_date']   = $start_date;
                        $dates['to_date']     = $end_date;
                        $this->PropertyUnavailabilityModel->create( $dates );
                    }

                }
                else{
                    if($property_type_slug == 'warehouse') {
                        $selected_field = 'selected_no_of_slots';
                        $available_no   = 'no_of_slots';
                    }
                    else {
                        $selected_field = 'no_of_guest';
                        $available_no   = 'number_of_guest';
                    }

                    $obj_booking = $this->BookingModel->where('property_id', $booking_data['property_id'])
                        ->select('id', 'booking_id', 'property_id', 'check_in_date', 'check_out_date', 'property_type_slug', $selected_field)
                        ->where('booking_status', '!=', 6)
                        ->whereRaw("((DATE('".$start_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE('".$end_date."') BETWEEN DATE(check_in_date) AND DATE(check_out_date)) OR (DATE(check_in_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."')) OR (DATE(check_out_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."'))) ")
                        ->sum( $selected_field );

                    $available = $arr_property[ $available_no ];

                    $total_available = (int) $available - (int) $obj_booking;

                    if($total_available <= 0) {
                        $dates['property_id'] = $booking_data['property_id'];
                        $dates['booking_id']  = $booking_id;
                        $dates['type']        = 'MONTHLY';
                        $dates['from_date']   = $start_date;
                        $dates['to_date']     = $end_date;
                        $this->PropertyUnavailabilityModel->create( $dates );
                    }
                }
            }
        }
        return true;
    } // end add_unavailability_dates
}