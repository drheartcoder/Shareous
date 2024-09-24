<?php

namespace App\Common\Services\Cron;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\UserModel;
use App\Models\AdminModel;
use App\Models\TransactionModel;
use App\Models\ReviewRatingModel;
use App\Models\BookingModel;
use App\Models\PropertyModel;
use App\Models\CurrencyConversionModel;

use App\Common\Services\NotificationService;
use App\Common\Services\EmailService;
use App\Common\Services\SMSService;
use App\Common\Services\PropertyDatesService;
use App\Common\Services\PropertyRateCalculatorService;
use App\Common\Services\MobileAppNotification;

use DB;

class CronService extends Controller
{
    public function __construct(
                                UserModel                     $user_model,
                                AdminModel                    $admin_model,
                                TransactionModel              $transaction_model,
                                NotificationService           $notification_service,
                                EmailService                  $email_service,
                                SMSService                    $sms_service,
                                PropertyModel                 $property_model,
                                PropertyDatesService          $property_date_service,
                                PropertyRateCalculatorService $property_rate_service,
                                BookingModel                  $booking_model,
                                ReviewRatingModel             $review_rating_model,
                                MobileAppNotification         $mobile_app_notification
                                )
    {
        $this->arr_view_data                 = [];

        $this->UserModel                     = $user_model;
        $this->AdminModel                    = $admin_model;
        $this->NotificationService           = $notification_service;
        $this->EmailService                  = $email_service;
        $this->SMSService                    = $sms_service;
        $this->PropertyDatesService          = $property_date_service;
        $this->PropertyRateCalculatorService = $property_rate_service;
        $this->ReviewRatingModel             = $review_rating_model;
        $this->PropertyModel                 = $property_model;
        $this->BookingModel                  = $booking_model;
        $this->MobileAppNotification         = $mobile_app_notification;

        $this->auth                          = auth()->guard('users');
        $user                                = $this->auth->user();
        if($user) {
        $this->user_id                       = $user->id;
        $this->user_first_name               = $user->first_name;
        $this->user_last_name                = $user->last_name;
        } else {
        $this->user_id                       = 0;
        }
    }

    public function review_notification()
    {
        $arr_booking = [];
        $obj_booking = $this->BookingModel->where(array('booking_status' => '5'))->with('property_details')->with('user_details')->get();

        $current_date = date("Y-m-d H:i:s"); 

        if (isset($obj_booking) && $obj_booking!=null) {
            $arr_booking = $obj_booking->toArray();

            foreach ($arr_booking as $booking) {
                $check_out_date   = date('Y-m-d H:i:s',strtotime($booking['check_out_date'] .'+10 day'));

                if ($check_out_date <= $current_date) {
                    $where_cnd    = array(
                                           'rating_user_id' => $booking['property_booked_by'],
                                           'property_id'    => $booking['property_id'],
                                           'booking_id'     => '0'
                                   );
                    $review_count = $this->ReviewRatingModel->where($where_cnd)->get();

                    if (count($review_count) == 0) {
                            if ($booking['user_details']['notification_by_email'] == "on") {

                                 $property_details_url =  url('/').'/property/view/'.$booking['property_details']['property_name_slug'].'?booking_id='.base64_encode(isset($booking['id']) ? $booking['id'] : '');

                                $arr_built_content     = [
                                                    'USER_NAME'      => isset($booking['user_details']['first_name'])?ucfirst($booking['user_details']['first_name']):'NA',
                                                    'Email'          => isset($booking['user_details']['email'])?ucfirst($booking['user_details']['email']):'NA' ,
                                                    'MESSAGE'        => "",
                                                    'PROPERTY_NAME'  => $booking['property_details']['property_name'],
                                                    'ADD_REVIEW_URL' => $property_details_url,
                                                    'PROJECT_NAME'   => config('app.project.name')
                                                 ];
                                $arr_mail_data                      = [];
                                $arr_mail_data['email_template_id'] = '16';
                                $arr_mail_data['arr_built_content'] = $arr_built_content;
                                $arr_mail_data['user']              = ['email' => isset($booking['user_details']['email'])?ucfirst($booking['user_details']['email']):'NA', 'first_name' => isset($booking['user_details']['first_name'])?ucfirst($booking['user_details']['first_name']):'NA'];

                                $status                             = $this->EmailService->send_mail($arr_mail_data);
                            }

                            if ($booking['user_details']['notification_by_sms'] == "on") {
                                $country_code = isset($booking['user_details']['country_code']) ? $booking['user_details']['country_code'] : '';
                                $mobile_number = isset($booking['user_details']['mobile_number']) ? $booking['user_details']['mobile_number'] : '';

                                $arr_sms_data                  = [];
                                $arr_sms_data['msg']           = "Please Add Review & Ratings Against Property Which You Recently Booked.";
                                $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                                $status                        = $this->SMSService->send_SMS($arr_sms_data);
                            }

                            if ($booking['user_details']['notification_by_push'] == 'on') {
                                $headings = 'Please Add Review & Ratings';
                                $content  = 'Please Add Review & Ratings Against Property Which You Recently Booked.';
                                $user_id  = $this->user_id;
                                $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                            }
                    }
                }
            }
        }
        dd('success');
    } // end index

    public function store_currency_conversion()
    {
        if (php_sapi_name() !='cli') die('Invalid Request');

        $currency_conversion_obj = CurrencyConversionModel::select('id', 'from_currency_id', 'to_currency_id', 'conversion_rate')->with(['from_currency_detail', 'to_currency_detail'])->get();

        if (isset($currency_conversion_obj) && count($currency_conversion_obj)) {
            
            $currency_conversion_arr = $currency_conversion_obj->toArray();

            foreach ($currency_conversion_arr AS $currency_conversion_data) {

                $from_currency_code = $currency_conversion_data['from_currency_detail']['currency_code'];
                $to_currency_code = $currency_conversion_data['to_currency_detail']['currency_code'];

                $conversion_rate = currency_conversion_api($from_currency_code, $to_currency_code);

                CurrencyConversionModel::where('from_currency_id', $currency_conversion_data['from_currency_id'])
                                        ->where('to_currency_id',  $currency_conversion_data['to_currency_id'])
                                        ->update(['conversion_rate' => $conversion_rate]);
            }
            dump('Success');
        }
    }

    public function send_booking_reminder()
    {
        if (php_sapi_name() !='cli') die('Invalid Request');
        $tomorrow_bookings_obj = $this->BookingModel->with('property_details', 'user_details', 'booking_by_user_details')
                                                    ->whereRaw('DATE(DATE_ADD(check_in_date, INTERVAL -1 DAY)) = CURRENT_DATE')
                                                    ->where('booking_status', '=', '5')
                                                    ->orderBy('check_in_date', 'ASC')
                                                    ->get();

        $tomorrow_bookings_arr = $tomorrow_bookings_obj->toArray();
        if (isset($tomorrow_bookings_arr) && count($tomorrow_bookings_arr) > 0) {
            
            foreach ($tomorrow_bookings_arr as $tomorrow_bookings) {
                $user_details     = $tomorrow_bookings['booking_by_user_details'];
                $owner_details    = $tomorrow_bookings['user_details'];
                $property_details = $tomorrow_bookings['property_details'];

                $built_content = [
                                    'USER_NAME'        => ucfirst($user_details['display_name']),
                                    'CHECKIN_DATE'     => get_added_on_date($tomorrow_bookings['check_in_date']),
                                    'CHECKOUT_DATE'    => get_added_on_date($tomorrow_bookings['check_out_date']),
                                    'PROPERTY_DETAILS' => '<b>'.$property_details['property_name'].'</b><br/> '.$property_details['address'],
                                    'OWNER_DETAILS'    => '<b>'.ucfirst($owner_details['first_name'].' '.$owner_details['last_name']).'</b><br/>'.$owner_details['email'].'<br>'.$owner_details['mobile_number'],
                                    'PROJECT_NAME'     => config('app.project.name')
                                ];
                $mail_data                      = [];
                $mail_data['email_template_id'] = '21';
                $mail_data['arr_built_content'] = $built_content;
                $mail_data['user']              = [
                                                    'email'      => isset($user_details['email']) ? $user_details['email'] : 'NA',
                                                    'first_name' => isset($user_details['display_name']) ? ucfirst($user_details['display_name']) : 'NA'
                                                ];

                $this->EmailService->send_mail($mail_data);
            }
            dump(count($tomorrow_bookings_arr).' reminder emails sent today '.date('Y-m-d H:i:s'));
        } else {
            dump("No bookings to remind");
        }
    }

    public function release_allocated_slots()
    {
        $arr_booking = [];
        $status = false;
        
        $obj_booking = $this->BookingModel->select('id', 'property_id', 'property_type_slug', 'selected_no_of_slots', 'selected_of_employee', 'check_out_date')
                                          ->where('check_out_date', date('c'))
                                          ->get();
        if( $obj_booking ) {
            $arr_booking = $obj_booking->toArray();

            foreach ($arr_booking as $key => $booking) {
                $property_id       = $booking['property_id'];
                $selected_slots    = $booking['selected_no_of_slots'];
                $selected_employee = $booking['selected_of_employee'];

                $obj_property = $this->PropertyModel->select('id', 'no_of_slots', 'no_of_employee', 'available_no_of_employee', 'available_no_of_slots')
                                                    ->where('id', $property_id)
                                                    ->first();
                if($obj_property) {
                    $arr_property = $obj_property->toArray();

                    $total_slots        = $arr_property['no_of_slots'];
                    $total_employee     = $arr_property['no_of_employee'];
                    $available_employee = $arr_property['available_no_of_employee'];
                    $available_slots    = $arr_property['available_no_of_slots'];

                    $added_slots    = $selected_slots + $available_slots;
                    $added_employee = $total_employee + $available_employee;

                    if($added_slots > $total_slots) {
                        $added_slots = $total_slots;
                    }

                    if($added_employee > $total_employee) {
                        $added_employee = $total_employee;
                    }

                    $status = $obj_property->update( array( 'available_no_of_employee' => $added_employee, 'available_no_of_slots' => $added_slots ) );
                }
            }
        }

        if($status) {
            dd('success');
        }
        else {
            dd('error');
        }

    } // end release_allocated_slots


    public function cancel_unpaided_booking()
    {
        $arr_booking = [];
        $status = false;

        $current_time = date("Y-m-d H:i:s");
        $obj_booking = $this->BookingModel->select('id', 'property_id', 'payment_type', 'booking_status', 'cancelled_reason', 'created_at', 'updated_at')
                                          ->where('booking_status', 3)
                                          ->where('payment_type', null)
                                          ->get();
        if( $obj_booking ) {
            $arr_booking = $obj_booking->toArray();

            foreach ($arr_booking as $key => $booking) {
                $booking_id = $booking['id'];
                $created_at  = $booking['created_at'];
                $created_at_plus_10_mins = date("Y-m-d H:i:s", strtotime('+10 minutes', strtotime($created_at) ));

                if( $current_time > $created_at_plus_10_mins )
                {
                    $status = $this->BookingModel->where('id', $booking_id)->update( array( 'booking_status' => 6, 'cancelled_reason' => 'Auto cancelled for payment not completed', 'cancelled_date' => $current_time ) );
                }
            }
        }

        if($status) {
            dd('success');
        }
        else {
            dd('error');
        }

    } // end release_allocated_slots


    public function general_cron()
    {
        $status = $this->UserModel->where('resend_otp_count', '!=', null)->update( ['resend_otp_count' => null] );

        if($status) {
            dd('success');
        }
        else {
            dd('error');
        }

    } // end general_cron
}