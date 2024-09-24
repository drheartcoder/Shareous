<?php
namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Common\Services\NotificationService;
use App\Common\Services\MobileAppNotification;
use App\Common\Services\EmailService;
use App\Common\Services\SMSService;
use App\Common\Services\PropertyDatesService;
use App\Common\Services\PropertyRateCalculatorService;

use App\Models\PropertyUnavailabilityModel;
use App\Models\PropertyModel;
use App\Models\BookingModel;
use App\Models\UserModel;
use App\Models\ReviewRatingModel;
use App\Models\TransactionModel;
use App\Models\SupportQueryModel;
use App\Models\SupportTeamModel;
use App\Models\CouponModel;
use App\Models\CouponsUsedModel;

use Session;
use Validator;
use TCPDF;
use PDF;
use DB;

use Razorpay\Api\Api;
use Razorpay;

class MyBookingController extends Controller
{
    public function __construct(
                                    PropertyDatesService          $property_date_service,
                                    PropertyRateCalculatorService $property_rate_service,
                                    MobileAppNotification         $mobileappnotification_service,
                                    EmailService                  $email_service,
                                    SMSService                    $sms_service,
                                    NotificationService           $notification_service,
                                    ReviewRatingModel             $review_rating_model,
                                    UserModel                     $user_model,
                                    PropertyModel                 $property_model,
                                    BookingModel                  $booking_model,
                                    TransactionModel              $transaction_model,
                                    PropertyUnavailabilityModel   $unavailability_model,
                                    SupportQueryModel             $support_query_model,
                                    SupportTeamModel              $support_team_model,
                                    CouponModel                   $coupon_model,
                                    CouponsUsedModel              $coupon_used_model
                                )
    {
        $this->arr_view_data                 = [];
        $this->PropertyModel                 = $property_model;
        $this->PropertyDatesService          = $property_date_service;
        $this->PropertyRateCalculatorService = $property_rate_service;
        $this->MobileAppNotification         = $mobileappnotification_service;
        $this->EmailService                  = $email_service;
        $this->SMSService                    = $sms_service;
        $this->UserModel                     = $user_model;
        $this->NotificationService           = $notification_service;
        $this->BookingModel                  = $booking_model;
        $this->ReviewRatingModel             = $review_rating_model;
        $this->TransactionModel              = $transaction_model;
        $this->PropertyUnavailabilityModel   = $unavailability_model;
        $this->SupportQueryModel             = $support_query_model;
        $this->SupportTeamModel              = $support_team_model;
        $this->CouponModel                   = $coupon_model;
        $this->CouponsUsedModel              = $coupon_used_model;
        
        $this->profile_image_public_img_path = url('/').config('app.project.img_path.user_profile_images');
        $this->profile_image_base_img_path   = public_path().config('app.project.img_path.user_profile_images');
        
        $this->module_url_path               = url('/').'/my-booking';
        $this->module_view_folder            = 'front.my_booking';
        $this->module_title                  = 'My Booking';
        
        $this->TCPDF                         = new TCPDF();
        $razorpay_credentials                = get_razorpay_credential();
        
        $razorpay_id                         = (isset($razorpay_credentials) && $razorpay_credentials['razorpay_id'] != '') ? $razorpay_credentials['razorpay_id'] : config('app.razorpay_credentials.razorpay_id');
        $razorpay_secret                     = (isset($razorpay_credentials) && $razorpay_credentials['razorpay_secret'] != '') ? $razorpay_credentials['razorpay_secret'] : config('app.razorpay_credentials.razorpay_secret');
        
        $this->api                           = new Api($razorpay_id, $razorpay_secret);
        
        $this->auth                          = auth()->guard('users');
        $user                                = $this->auth->user();

        if ($user) {
            $this->user_id                   = $user->id;
            $this->user_first_name           = $user->first_name;
            $this->user_last_name            = $user->last_name;
        } else {
            $this->user_id                   = 0;
        }

    }


    /*
    | Function  : Show all the new booking list to both the users
    | Author    : Deepak Arvind Salunke
    | Date      : 04/05/2018
    | Output    : Listing of new booking
    */

    public function new_booking()
    {
        $arr_booking = $obj_pagination = [];

        $user_type   = Session::get('user_type');
        $obj_booking = $this->BookingModel->with(['property_details', 'property_details.property_type', 'user_details'])
                                            ->has('transaction_details')
                                          ->where(function($query) use($user_type){
                                            if($user_type == 1) {
                                                $query->where('property_booked_by', $this->user_id);
                                            } else if($user_type == 4) {
                                                $query->where('property_owner_id', $this->user_id);
                                            }
                                          })
                                          ->where(function($query){
                                            //$query->where('booking_status','=',3);
                                            $query->orWhere('booking_status', '=', '4');
                                          })
                                          ->orderBy('check_in_date', 'DESC')
                                          ->paginate(5);
        
        if (isset($obj_booking) && $obj_booking!=null) {
            $arr_booking        = $obj_booking->toArray();
            $obj_pagination     = clone $obj_booking;
        }

        $obj_user = $this->UserModel->where('id', $this->user_id)->first();
        
        if ($obj_user) {
            $arr_user = $obj_user->toArray();
        }
        $this->array_view_data['arr_user']        = $arr_user;
        
        $this->array_view_data['arr_booking']     = $arr_booking;
        $this->array_view_data['obj_pagination']  = $obj_pagination;
        $this->array_view_data['module_url_path'] = $this->module_url_path."/new";
        $this->array_view_data['page_title']      = $this->module_title;

        return view($this->module_view_folder.'.new', $this->array_view_data);
    } // end new_booking


    public function booking_details($id = NULL)
    {
        $arr_booking = [];
        $obj_booking = $this->BookingModel->with('property_details', 'user_details', 'property_details.property_type')
                                          ->where('id', base64_decode($id))
                                          ->get();

        if(isset($obj_booking) && $obj_booking!=null)
        {
           $arr_booking = $obj_booking->toArray();
        }

        $obj_property_review = $this->ReviewRatingModel->where('status','=','1')->get();
        if(isset($obj_property_review) && $obj_property_review!=null)
        {
            $arr_property_review = $obj_property_review->toArray();
        }

        $obj_user = $this->UserModel->where('id', $this->user_id)->first();
        if($obj_user) {
            $arr_user = $obj_user->toArray();
        }

        if(count($arr_booking)>0)
        {
            foreach($arr_booking as $booking)
            {
                $arr_dates = $this->PropertyRateCalculatorService->make_dates_array($booking['check_in_date'],$booking['check_out_date']);
                $number_of_nights     = $booking['no_of_days'];
                $total_night_price    = $booking['property_details']['price_per_night'] * $number_of_nights;
                $total_service_amount = ($booking['gst_amount'] / 100) * $total_night_price;
                $total_amount         = $total_night_price + $total_service_amount;
            }
        }

        $site_settings = DB::table('site_settings')->select('admin_commission')->first();
        if( $site_settings ) {
            $service_fee_percentage = $site_settings->admin_commission;
        }
        
        $this->array_view_data['current_date']           = date('Y-m-d');
        $this->array_view_data['number_of_days']         = $number_of_nights;
        $this->array_view_data['number_of_nights']       = $number_of_nights;
        $this->array_view_data['total_night_price']      = $total_night_price;
        $this->array_view_data['total_service_amount']   = $total_service_amount;
        $this->array_view_data['service_fee_percentage'] = $service_fee_percentage;

        $this->array_view_data['previous_url']           = url()->previous();
        $this->array_view_data['arr_user']               = $arr_user;
        $this->array_view_data['arr_property_review']    = $arr_property_review;
        $this->array_view_data['arr_booking']            = $arr_booking;
        $this->array_view_data['module_url_path']        = $this->module_url_path;
        $this->array_view_data['page_title']             = $this->module_title;

        return view($this->module_view_folder.'.booking-details', $this->array_view_data);
    }


    /*
    | Function  : Show all the confirmed booking list to both the users
    | Author    : Deepak Arvind Salunke
    | Date      : 04/05/2018
    | Output    : Listing of confirmed booking
    */

    public function confirmed_booking()
    {
        $arr_booking = $obj_pagination = [];
        $user_type   = Session::get('user_type');
        $obj_booking = $this->BookingModel->with('property_details', 'property_details.property_type', 'user_details')
                                          ->where(function($query) use($user_type){
                                            if ($user_type == '1') {
                                                $query->where('property_booked_by', $this->user_id);
                                            } else if($user_type == '4') {
                                                $query->where('property_owner_id', $this->user_id);
                                            }
                                          })
                                          ->where(function($query){
                                            // $query->where('booking_status','=',1);
                                            $query->where('booking_status', '=', '5');
                                          })
                                          ->where('check_in_date', '>=', date("Y-m-d"))
                                          ->orderBy('check_in_date', 'ASC')
                                          ->paginate(5);

        if (isset($obj_booking) && $obj_booking!=null) {
            $arr_booking    = $obj_booking->toArray();
            $obj_pagination = clone $obj_booking;
        }

        $obj_user = $this->UserModel->where('id', $this->user_id)->first();
        if ($obj_user) {
            $arr_user = $obj_user->toArray();
        }

        $this->array_view_data['arr_user']        = $arr_user;
        $this->array_view_data['arr_booking']     = $arr_booking;
        $this->array_view_data['obj_pagination']  = $obj_pagination;
        $this->array_view_data['module_url_path'] = $this->module_url_path."/confirmed";
        $this->array_view_data['page_title']      = $this->module_title;

        return view($this->module_view_folder.'.confirmed', $this->array_view_data);
    } 
    // end confirmed_booking


    /*
    | Function  : Show all the completed booking list to both the users
    | Author    : Deepak Arvind Salunke
    | Date      : 04/05/2018
    | Output    : Listing of completed booking
    */

    public function completed_booking()
    {
        $arr_booking = $obj_pagination = [];
        $user_type = Session::get('user_type');
        $obj_booking = $this->BookingModel->with(['property_details'=>function($query){
                                                        $query->with(['guest_review_details'=>function($query){
                                                            $query->where('rating_user_id',$this->user_id);
                                                        }]);
                                                    }, 'property_details.property_type', 'user_details'])
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

        if(isset($obj_booking) && $obj_booking!=null) {
            $arr_booking    = $obj_booking->toArray();
            $obj_pagination = clone $obj_booking;
        }
        $this->array_view_data['arr_booking']     = $arr_booking;
        $this->array_view_data['obj_pagination']  = $obj_pagination;
        $this->array_view_data['module_url_path'] = $this->module_url_path."/completed";
        $this->array_view_data['page_title']      = $this->module_title;

        return view($this->module_view_folder.'.completed', $this->array_view_data);
    } // end completed_booking


    /*
    | Function  : Show all the cancelled booking list to both the users
    | Author    : Deepak Arvind Salunke
    | Date      : 04/05/2018
    | Output    : Listing of cancelled booking
    */

    public function cancelled_booking()
    {
        $arr_booking = $obj_pagination = [];

        $user_type = Session::get('user_type');

        $obj_booking = $this->BookingModel->with('property_details', 'property_details.property_type', 'user_details')
                                          ->where(function($query) use($user_type){
                                            if($user_type == 1)
                                            {
                                                $query->where('property_booked_by', $this->user_id);
                                            }
                                            else if($user_type == 4)
                                            {
                                                $query->where('property_owner_id', $this->user_id);
                                            }
                                          })
                                          ->where(function($query){
                                            $query->where('booking_status','=',6);
                                            $query->orWhere('booking_status','=',7);
                                          })
                                          ->orderBy('id', 'DESC')
                                          ->paginate(5);

        if(isset($obj_booking) && $obj_booking!=null)
        {
            $arr_booking        = $obj_booking->toArray();
            // dd($arr_booking);
            $obj_pagination     = clone $obj_booking;
        }
    
        $this->array_view_data['arr_booking']                   = $arr_booking;
        $this->array_view_data['obj_pagination']                = $obj_pagination;
        $this->array_view_data['module_url_path']               = $this->module_url_path."/cancelled";
        $this->array_view_data['page_title']                    = $this->module_title;

        return view($this->module_view_folder.'.cancelled', $this->array_view_data);
    } // end cancelled_booking


    /*
    | Function  : Show selected status results
    | Author    : Deepak Arvind Salunke
    | Date      : 07/05/2018
    | Output    : List of selected status booking
    */

    public function accept_booking($status = false, $enc_id = false)
    {
        
        if($status != '' && !empty($status) && $enc_id != '' && !empty($enc_id))
        {
            $arr_booking = [];
            $booking_id  = '';

            $booking_id  = base64_decode($enc_id);

            $obj_booking = $this->BookingModel->where('id', $booking_id)->first();
            if($obj_booking)
            {
                $arr_booking = $obj_booking->toArray();
            }

            if($status == 'accept')
            {
                $update_data['booking_status'] = '1';
                $update_data['host_accepted_date'] = date('Y-m-d');                
                $booking_status   = 'accepted';
                $notification_url = "/my-booking/confirmed";
            }
            else if($status == 'reject')
            {
                $update_data['booking_status'] = '4';              
                $update_data['host_rejected_date'] = date('Y-m-d');  
                $booking_status   = 'rejected';
                $notification_url = "/my-booking/cancelled";
            }
            else
            {
                Session::flash('error',"Invalid action can't be performed");
                return redirect()->back();
            }

            $update_booking = $this->BookingModel->where('id', $booking_id)->update($update_data);

            $obj_booked_by = $this->UserModel->where('id', $arr_booking['property_booked_by'])->first();
            if($obj_booked_by)
            {
                $arr_booked_by = $obj_booked_by->toArray();
            }

            $arr_built_content  = array(
                                     'USER_NAME'     => isset($this->user_first_name) ? $this->user_first_name : 'NA',
                                     'STATUS'        => $booking_status,
                                     'SUBJECT'       => "Your booking has been ".$booking_status." by host."
                                    );

            $arr_notify_data['arr_built_content']   = $arr_built_content;
            $arr_notify_data['notify_template_id']  = '11';
            $arr_notify_data['sender_id']           = $this->user_id;
            $arr_notify_data['sender_type']         = '4';
            $arr_notify_data['receiver_type']       = '1';
            $arr_notify_data['receiver_id']         = $arr_booking['property_booked_by'];
            $arr_notify_data['url']                 = $notification_url;
            $notification_status                    = $this->NotificationService->send_notification($arr_notify_data);

            $type = get_notification_type_of_user($arr_booking['property_booked_by']);

            if(isset($type) && !empty($type))
            {
                // for mail
                if($type['notification_by_email'] == 'on')
                {
                    $arr_built_content = [
                                        'USER_NAME'    => isset($arr_booked_by['display_name']) ? ucfirst($arr_booked_by['display_name']) : 'NA',
                                        'Email'        => isset($arr_booked_by['email']) ? ucfirst($arr_booked_by['email']) : 'NA' ,
                                        'SUBJECT'      => "Your booking has been ".$booking_status." by host.",
                                        'STATUS'       => $booking_status,
                                        'PROJECT_NAME' => config('app.project.name')
                                    ];
                    $arr_mail_data                         = [];
                    $arr_mail_data['email_template_id']    = '15';
                    $arr_mail_data['arr_built_content']    = $arr_built_content;
                    $arr_mail_data['user']                 = ['email' => isset($arr_booked_by['email']) ? ucfirst($arr_booked_by['email']) : 'NA', 'first_name' => isset($arr_booked_by['display_name']) ? ucfirst($arr_booked_by['display_name']) : 'NA'];

                    $status                                = $this->EmailService->send_mail($arr_mail_data);
                }

                // for sms
                if($type['notification_by_sms'] == 'on')
                {
                    $country_code  = isset($arr_booked_by['country_code']) ? $arr_booked_by['country_code'] : '';
                    $mobile_number = isset($arr_booked_by['mobile_number']) ? $arr_booked_by['mobile_number'] : '';

                    $arr_sms_data                  = [];
                    $arr_sms_data['msg']           = "Your booking has been ".$booking_status." by host.";
                    $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                    $status                        = $this->SMSService->send_SMS($arr_sms_data);
                }

                // for push notification
                if($type['notification_by_push'] == 'on')
                {
                    $headings = 'Booking Update';
                    $content  = "Your booking has been ".$booking_status." by host.";
                    $user_id  = $arr_booking['property_booked_by'];
                    $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                }
            }

            Session::flash('success','Booking is Accepted successfully.');
            return redirect()->back();

        }
        else
        {
            Session::flash('error','Something went wrong. Try again later');
            return redirect()->back();
        }
    }


    public function search_status(Request $request)
    {
        $booking_status  = $request->input('cmb_status');
        $booking_page    = $request->input('booking_page');

        $arr_booking     = $obj_pagination = '';

        if($booking_status != '' && $booking_status != null)
        {
            $arr_booking = $obj_pagination = [];

            $user_type   = Session::get('user_type');

            $obj_booking = $this->BookingModel->with('property_details', 'user_details')
                                              ->where(function($query) use($user_type){
                                                if($user_type == 1)
                                                {
                                                    $query->where('property_booked_by', $this->user_id);
                                                }
                                                else if($user_type == 4)
                                                {
                                                    $query->where('property_owner_id', $this->user_id);
                                                }
                                              })

                                              ->where('booking_status','=',$booking_status)
                                              ->orderBy('id', 'DESC')
                                              ->paginate(5);

            if(isset($obj_booking) && $obj_booking!=null)
            {
                $arr_booking        = $obj_booking->toArray();
                $obj_pagination     = clone $obj_booking;
            }

            if($booking_page != '' && $booking_page != null)
            {
                $booking_page       = 'new';
            }

            $this->array_view_data['booking_status']                = $booking_status;
            $this->array_view_data['arr_booking']                   = $arr_booking;
            $this->array_view_data['obj_pagination']                = $obj_pagination;
            $this->array_view_data['module_url_path']               = $this->module_url_path."/new";
            $this->array_view_data['page_title']                    = $this->module_title;

            return view($this->module_view_folder.'.'.$booking_page, $this->array_view_data);
        }
        else
        {
            return redirect(url('/').'/my-booking/new');
        }

    } // end search_status


    /*
    | Function  : cancel selected booking
    | Author    : Deepak Arvind Salunke
    | Date      : 07/05/2018
    | Output    : success or error
    */

    public function cancel_booking($enc_id = false)
    {
        $booking_id = base64_decode($enc_id);

        if($booking_id != '')
        {
            $url = url()->previous();
            $notification_url = str_replace(url('/'), "", $url);

            $booking_status = 'cancelled';
            $data['booking_status'] = 6;
            $data['cancelled_date'] = date('Y-m-d');

            $data['status_by'] = $this->user_id;
            $update_booking    = $this->BookingModel->where('id', $booking_id)->update($data);
            if($update_booking)
            {
                $obj_booking = $this->BookingModel->where('id', $booking_id)->with('property_details')->first();
                if($obj_booking)
                {
                    $arr_booking = $obj_booking->toArray();

                    $property_id    = $arr_booking['property_id'];
                    $check_in_date  = $arr_booking['check_in_date'];
                    $check_out_date = $arr_booking['check_out_date'];

                    $this->PropertyDatesService->remove_unavaialable_dates($property_id, $check_in_date, $check_out_date);
                }

                $obj_user_data = $this->UserModel->where('id', $arr_booking['property_owner_id'])->first();
                if($obj_user_data)
                {
                    $arr_user = $obj_user_data->toArray();
                }

                $arr_built_content = array(
                                        'USER_NAME' => isset($arr_user['first_name']) ? $arr_user['first_name'] : 'NA',
                                        'MESSAGE'   => "Booking is cancelled by the ".$this->user_first_name." for the ".$arr_booking['property_details']['property_name']
                                    );

                $arr_notify_data['arr_built_content']  = $arr_built_content;
                $arr_notify_data['notify_template_id'] = '9';
                $arr_notify_data['template_text']      = "Booking is cancelled by the ".$this->user_first_name." for the ".$arr_booking['property_details']['property_name'];
                $arr_notify_data['sender_id']          = $this->user_id;
                $arr_notify_data['sender_type']        = '1';
                $arr_notify_data['receiver_type']      = '4';
                $arr_notify_data['receiver_id']        = $arr_user['id'];
                $arr_notify_data['url']                = $notification_url;

                $notification_status = $this->NotificationService->send_notification($arr_notify_data);

                $type = get_notification_type_of_user($arr_user['id']);

                if(isset($type) && !empty($type))
                {
                    // for mail
                    if($type['notification_by_email'] == 'on')
                    {
                        $arr_built_content         = [
                                'USER_NAME'    => isset($arr_user['display_name'])?ucfirst($arr_user['display_name']):'NA',
                                'Email'        => isset($arr_user['email'])?ucfirst($arr_user['email']):'NA' ,
                                'SUBJECT'      => "Booking is cancelled by the ".$this->user_first_name." for the ".$arr_booking['property_details']['property_name'],
                                'STATUS'       => $booking_status,
                                'PROJECT_NAME' => config('app.project.name')
                             ];

                        $arr_mail_data                      = [];
                        $arr_mail_data['email_template_id'] = '15';
                        $arr_mail_data['arr_built_content'] = $arr_built_content;
                        $arr_mail_data['user']              = ['email' => isset($arr_user['email'])?ucfirst($arr_user['email']):'NA', 'first_name' => isset($arr_user['display_name'])?ucfirst($arr_user['display_name']):'NA'];

                        $status = $this->EmailService->send_mail($arr_mail_data);
                    }

                    // for sms
                    if($type['notification_by_sms'] == 'on')
                    {
                        $country_code = isset($arr_user['country_code']) ? $arr_user['country_code'] : '';
                        $mobile_number = isset($arr_user['mobile_number']) ? $arr_user['mobile_number'] : '';

                        $arr_sms_data                  = [];
                        $arr_sms_data['msg']           = "Booking is cancelled by the ".$this->user_first_name." for the ".$arr_booking['property_details']['property_name'];
                        $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                        $status                        = $this->SMSService->send_SMS($arr_sms_data);
                    }

                    // for push notification
                    if($type['notification_by_push'] == 'on')
                    {
                        $headings = 'Booking Update';
                        $content  = "Booking is cancelled by the ".$this->user_first_name." for the ".$arr_booking['property_details']['property_name'];
                        $user_id  = $arr_user['id'];
                        $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                    }
                }

                Session::flash('success','Booking successfully cancelled');
            }
            else
            {
                Session::flash('error','Booking was not able to cancelled. Please try again');
            }
        }
        else
        {
            Session::flash('error','Something went wrong. Please try again');
        }
        return redirect()->back();
    }


    /*
    | Function  : Store payment details after successful payment
    | Author    : Deepak Arvind Salunke
    | Date      : 08/05/2018
    | Output    : Success or Error
    */

    public function payment_store(Request $request)
    {
        $arr_booking = [];
        $guest_invoice    = $host_invoice = $used_coupon_id = '';

        $notification_url = '/my-booking/confirmed';

        $transaction_id = $request->input('transaction_id');
        $payment_amount = $request->input('payment_amount');
        $booking_id     = $request->input('booking_id');
        $page           = $request->input('page');
        $used_coupon_id = $request->input('used_coupon_id');

        // check if coupon code is used
        if (isset($used_coupon_id) && !empty($used_coupon_id) && $used_coupon_id != null) {
            
            // For first time use only
            $obj_coupon = $this->CouponModel->where('id', $used_coupon_id)
                                            ->where('status', 1)
                                            ->where('global_expiry', '>=',date("c"))
                                            ->first();
            if ($obj_coupon) {
                $arr_coupon = $obj_coupon->toArray();
                $coupon_id = $arr_coupon['id'];
            }

            if ($arr_coupon['coupon_use'] == 2 || $arr_coupon['coupon_use'] == 3) {
                $user = $this->auth->user();
                if ($user['is_coupon_used'] == 'no') {
                    $this->UserModel->where('id', $this->user_id)->update(array('is_coupon_used' => 'yes'));
                }
            }

            // To store coupon use
            $arr_coupons_used['coupon_id']  = $used_coupon_id;
            $arr_coupons_used['user_id']    = $this->user_id;
            $arr_coupons_used['booking_id'] = $booking_id;
            $this->CouponsUsedModel->create($arr_coupons_used);
        }
        
        $res = $this->api->payment->fetch($transaction_id)->capture(array('amount' => $payment_amount * 100));

        $guest_booking = $this->BookingModel->where('id', $booking_id)->with('property_details')->first();
        if($guest_booking) {
            $arr_booking  = $guest_booking->toArray();

            $arr_booking_data['coupon_code_id'] = $used_coupon_id;
            $guest_booking->update( $arr_booking_data );
        }

        $arr_property_data = get_property_details($arr_booking['property_id']);

        // For Guest User
        $guest_transaction['transaction_id']   = $transaction_id;
        $guest_transaction['payment_type']     = 'booking';
        $guest_transaction['user_id']          = $this->user_id;
        $guest_transaction['user_type']        = '1';
        $guest_transaction['amount']           = $payment_amount;
        $guest_transaction['booking_id']       = $arr_booking['id'];
        $guest_transaction['transaction_for']  = "Payment successfully paid for the ".$arr_booking['property_details']['property_name'];
        $guest_transaction['transaction_date'] = date('Y-m-d H:i:s');

        $guest_transaction = $this->TransactionModel->create($guest_transaction);

        if($guest_transaction) {
            $guest_user_data = $this->UserModel->where('id', $this->user_id)->first();
            $utype = 'guest';
            
            //$guest_invoice = $this->generateInvoice($transaction_id, $guest_id, $host_id, $user_type)
            $guest_invoice = $this->generateInvoice($guest_transaction->id,$this->user_id,$arr_booking['property_owner_id'],$utype);

            $this->TransactionModel->where('id',$guest_transaction->id)->update(['invoice' => $guest_invoice]);

            $this->add_unavailability_dates($booking_id);

            if($guest_user_data) {
                $guest_user  = $guest_user_data->toArray();
            }

            $guest_built_content = array(
                                    'USER_NAME' => isset($this->user_first_name) ? $this->user_first_name : 'NA',
                                    'MESSAGE'   => "Payment successfully paid for the ".$arr_booking['property_details']['property_name']
                                    );

            $guest_notify_data['arr_built_content']  = $guest_built_content;
            $guest_notify_data['notify_template_id'] = '9';
            $guest_notify_data['sender_id']          = '1';
            $guest_notify_data['sender_type']        = '2';
            $guest_notify_data['receiver_type']      = '1';
            $guest_notify_data['receiver_id']        = $this->user_id;
            $guest_notify_data['url']                = $notification_url;
            $notification_status                     = $this->NotificationService->send_notification($guest_notify_data);

            $type = get_notification_type_of_user($this->user_id);

            if(isset($type) && !empty($type)) {
                
                // for mail
                if($type['notification_by_email'] == 'on') {

                    $guest_built_content         = [
                                        'USER_NAME'            => isset($guest_user['display_name'])?ucfirst($guest_user['display_name']):'NA',
                                        'Email'                => isset($guest_user['email'])?ucfirst($guest_user['email']):'NA' ,
                                        'MESSAGE'              => "Payment successfully paid for the <i>'".$arr_booking['property_details']['property_name']."'</i>",
                                        'PROJECT_NAME'         => config('app.project.name'),
                                        'NOTIFICATION_SUBJECT' => 'Notification'
                                     ];
                    $guest_mail_data                      = [];
                    $guest_mail_data['email_template_id'] = '13';
                    $guest_mail_data['arr_built_content'] = $guest_built_content;
                    $guest_mail_data['user']              = ['email' => isset($guest_user['email'])?ucfirst($guest_user['email']):'NA', 'first_name' => isset($guest_user['display_name'])?ucfirst($guest_user['display_name']):'NA'];
                    $guest_mail_data['attachment']        = public_path('uploads/invoice/'.$guest_invoice);

                    $status                               = $this->EmailService->send_invoice_mail($guest_mail_data);
                }

                // for sms
                if($type['notification_by_sms'] == 'on') {
                    
                    $country_code = isset($guest_user['country_code']) ? $guest_user['country_code'] : '';
                    $mobile_number = isset($guest_user['mobile_number']) ? $guest_user['mobile_number'] : '';

                    $guest_sms_data                  = [];
                    $guest_sms_data['msg']           = "Payment successfully paid for the '".$arr_booking['property_details']['property_name']."'";
                    $guest_sms_data['mobile_number'] = $country_code.$mobile_number;
                    $status                          = $this->SMSService->send_SMS($guest_sms_data);
                }

                // for push notification
                if($type['notification_by_push'] == 'on') {
                    $headings = 'Payment successfully';
                    $content  = "Payment successfully paid for the ".$arr_booking['property_details']['property_name'];
                    $user_id  = $this->user_id;
                    $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                }
            }
        }

        // For Host User
        $host_transaction['transaction_id']   = $transaction_id;
        $host_transaction['payment_type']     = 'booking';
        $host_transaction['user_id']          = $arr_booking['property_owner_id'];
        $host_transaction['user_type']        = '2';
        $host_transaction['amount']           = $payment_amount;
        $host_transaction['booking_id']       = $arr_booking['id'];
        $host_transaction['transaction_for']  = "Payment successfully received for the ".$arr_booking['property_details']['property_name']." by the ".$this->user_first_name;
        $host_transaction['transaction_date'] = date('Y-m-d H:i:s');

        $host_transaction = $this->TransactionModel->create($host_transaction);

        if ($host_transaction) {
            $host_user_data = $this->UserModel->where('id', $arr_booking['property_owner_id'])->first();
            $utype = 'host'; 

            $host_invoice = $this->generateInvoice($host_transaction->id,$this->user_id,$arr_booking['property_owner_id'],$utype);
            $this->TransactionModel->where('id',$host_transaction->id)->update(['invoice' => $host_invoice]);

            if($host_user_data)
            {
                $host_user  = $host_user_data->toArray();
            }

            $host_built_content  = array(
                                    'USER_NAME' => isset($host_user['first_name']) ? $host_user['first_name'] : 'NA',
                                    'MESSAGE'   => "Payment successfully received for the ".$arr_booking['property_details']['property_name']." by the ".$this->user_first_name
                                    );

            $host_notify_data['arr_built_content']  = $host_built_content;
            $host_notify_data['notify_template_id'] = '9';
            $host_notify_data['sender_id']          = '1';
            $host_notify_data['sender_type']        = '2';
            $host_notify_data['receiver_type']      = '4';
            $host_notify_data['receiver_id']        = $arr_booking['property_owner_id'];
            $host_notify_data['url']                = $notification_url;
            $notification_status                    = $this->NotificationService->send_notification($host_notify_data);

            $type = get_notification_type_of_user($arr_booking['property_owner_id']);

            if(isset($type) && !empty($type)) {
                // for mail
                if($type['notification_by_email'] == 'on') {
                    $host_built_content         = [
                                        'USER_NAME'            => isset($host_user['display_name'])?ucfirst($host_user['display_name']):'NA',
                                        'Email'                => isset($host_user['email'])?ucfirst($host_user['email']):'NA' ,
                                        'MESSAGE'              => "Payment successfully received for the <i>'".$arr_booking['property_details']['property_name']."'</i>' by the ".$this->user_first_name,
                                        'PROJECT_NAME'         => config('app.project.name'),
                                        'NOTIFICATION_SUBJECT' => 'Notification'
                                     ];
                    $host_mail_data                      = [];
                    $host_mail_data['email_template_id'] = '13';
                    $host_mail_data['arr_built_content'] = $host_built_content;
                    $host_mail_data['user']              = ['email' => isset($host_user['email'])?ucfirst($host_user['email']):'NA', 'first_name' => isset($host_user['display_name'])?ucfirst($host_user['display_name']):'NA'];
                    $host_mail_data['attachment']        = public_path('uploads/invoice/'.$host_invoice);

                    $status                              = $this->EmailService->send_invoice_mail($host_mail_data);
                }

                // for sms
                if($type['notification_by_sms'] == 'on') {
                    
                    $country_code  = isset($host_user['country_code']) ? $host_user['country_code'] : '';
                    $mobile_number = isset($host_user['mobile_number']) ? $host_user['mobile_number'] : '';

                    $host_sms_data                  = [];
                    $host_sms_data['msg']           = "Payment successfully received for the ".$arr_booking['property_details']['property_name']." by the ".$this->user_first_name;
                    $host_sms_data['mobile_number'] = $country_code.$mobile_number;
                    $status                         = $this->SMSService->send_SMS($host_sms_data);
                }

                // for push notification
                if($type['notification_by_push'] == 'on') {
                    $headings = 'Payment successfully';
                    $content  = "Payment successfully received for the ".$arr_booking['property_details']['property_name']." by the ".$this->user_first_name;
                    $user_id  = $arr_booking['property_owner_id'];
                    $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                }
            }
        }

        if(($guest_invoice != '' && $guest_invoice != null) || ($host_invoice != '' && $host_invoice != null) ) {
            
            $update_arr = array('booking_status'=> '5', 'payment_type' => 'booking');
            $this->BookingModel->where('id', $booking_id)->update($update_arr);

            if ($arr_booking['property_type_slug'] == 'warehouse') {
                $selected_no_of_slots = $arr_property_data['available_no_of_slots'] - $arr_booking['selected_no_of_slots'];
                $this->PropertyModel->where('id', $arr_booking['property_id'])->update(array('available_no_of_slots' => $selected_no_of_slots));
            }
            else if ($arr_booking['property_type_slug'] == 'office-space') {
                $selected_of_employee = $arr_property_data['available_no_of_employee'] - $arr_booking['selected_of_employee'];
                $this->PropertyModel->where('id', $arr_booking['property_id'])->update(array('available_no_of_employee' => $selected_of_employee));
            }

            $arr_json['status']  = 'success';
            $arr_json['message'] = 'Payment successfully paid for the booking';
            Session::put('BookingRequestData',[]);
            if($page == 'list')
            {
                Session::flash('success','Payment successfully paid for the booking');
            }
        } else {
            $arr_json['status']  = 'error';
            $arr_json['message'] = 'Something went wrong. Please try again';
            if($page == 'list')
            {                
                Session::flash('error','Something went wrong. Please try again');
            }
        }
        return json_encode($arr_json);
    } // end payment_store


    /*
    | Function  : Generate invoice after booking payment successful paid
    | Author    : Deepak Arvind Salunke
    | Date      : 08/05/2018
    | Output    : Success or Error
    */

    /*public function generateInvoice($transaction_id = false, $receiver_id = false, $sender_id = false, $user_type = false)
    {
        $data = $Senderdata = $ReceivedData = [];
        $html = $view = $FileName = '';

        if (isset($transaction_id) && $transaction_id != false && isset($receiver_id) && $receiver_id != false && isset($sender_id) && $sender_id != false) {
            $obj_transaction = $this->TransactionModel->where('id',$transaction_id)->first();
            if ($obj_transaction) {
                $arr_transaction = $obj_transaction->toArray();
            }

            $receiver_user_data = $this->UserModel->where('id', $receiver_id)->first();
            if ($receiver_user_data) {
                $ReceivedData = $receiver_user_data->toArray();
            }

            $sender_user_data = $this->UserModel->where('id', $sender_id)->first();
            if ($sender_user_data) {
                $SenderData = $sender_user_data->toArray();
            }

            $data['logo']     = url('/front/images/logo-inner.png');
            $data['base_url'] = url('/');

            PDF::SetTitle(config('app.project.name'));
            PDF::AddPage();
            if (isset($arr_transaction) && count($arr_transaction )>0) 
            {
                $booking_data = $this->BookingModel->where('id',$arr_transaction['booking_id'])->first();

                $data['user_type']         = $user_type;
                $data['admin_commission']  = $booking_data['admin_commission'];
                $data['commission_amount'] = $obj_transaction['amount']*( $booking_data['admin_commission']/100);
                $data['final_amount']      = ($obj_transaction['amount']) - ($data['commission_amount']);
            }

            $view = view('invoice.booking_invoice_pdf')->with(['ReceivedData' => $ReceivedData, 'SenderData' => $SenderData, 'TrasactionData' => $arr_transaction, 'Data' => $data]);
          
            $html = $view->render();

            PDF::writeHTML($html, true, false, true, false, 'L');
            $FileName = 'Invoice'.$transaction_id.$ReceivedData['id'].'.pdf';
            PDF::output(public_path('uploads/invoice/'.$FileName),'F');
            PDF::reset();
        }
        return $FileName;
    }*/


    /*
    | Function  : Generate invoice after booking payment successful paid
    | Author    : Deepak Arvind Salunke
    | Date      : 08/05/2018
    | Output    : Success or Error
    */

    public function generateInvoice($transaction_id = false, $guest_id = false, $host_id = false, $user_type = false)
    //public function generateInvoice(Request $request)
    {
        /*$transaction_id = $request->input('transaction_id');
        $guest_id       = $request->input('guest_id');
        $host_id        = $request->input('host_id');
        $user_type      = $request->input('user_type');*/

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
            PDF::SetAuthor(config('app.project.name'));
            PDF::SetCreator(PDF_CREATOR);
            PDF::SetSubject('Property Booking Invoice');

            // set margins
            PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);

            // set auto page breaks
            PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            //PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set JPEG quality
            PDF::setJPEGQuality(100);

            // restore full opacity
            PDF::SetAlpha(0.5);

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
            //return PDF::output($FileName,'I');
            PDF::reset();
        }
        return $FileName;
    }


    public function reject_booking(Request $request)
    {
       $enc_id     = $request->input('booking_id');
       $reason     = $request->input('reason');
       $booking_id = base64_decode($enc_id);

        if($booking_id != '')
        {
            $url              = url()->previous();
            $notification_url = str_replace(url('/'), "", $url);
            $booking_status   = 'rejected';

            $data['reject_reason']  = $reason;
            $data['booking_status'] = 4;
            $data['status_by']      = $this->user_id;
            $update_booking         = $this->BookingModel->where('id', $booking_id)->update($data);
            if($update_booking)
            {
                $obj_booking        = $this->BookingModel->where('id', $booking_id)->with('property_details')->first();
                if($obj_booking)
                {
                    $arr_booking    = $obj_booking->toArray();

                    $property_id    = $arr_booking['property_id'];
                    $check_in_date  = $arr_booking['check_in_date'];
                    $check_out_date = $arr_booking['check_out_date'];

                    $this->PropertyDatesService->remove_unavaialable_dates($property_id, $check_in_date, $check_out_date);
                }

                $obj_user_data = $this->UserModel->where('id', $arr_booking['property_booked_by'])->first();
                if($obj_user_data)
                {
                    $arr_user = $obj_user_data->toArray();
                }

                $arr_built_content  = array(
                                            'USER_NAME' => isset($arr_user['first_name']) ? $arr_user['first_name'] : 'NA',
                                            'MESSAGE'   => "Booking is rejected by the ".$this->user_first_name." for the ".$arr_booking['property_details']['property_name']
                                        );

                $arr_notify_data['arr_built_content']   = $arr_built_content;
                $arr_notify_data['notify_template_id']  = '9';
                $arr_notify_data['template_text']       = "Booking is rejected by the ".$this->user_first_name." for the ".$arr_booking['property_details']['property_name'];
                $arr_notify_data['sender_id']           = $this->user_id;
                $arr_notify_data['sender_type']         = '1';
                $arr_notify_data['receiver_type']       = '1';
                $arr_notify_data['receiver_id']         = $arr_user['id'];
                $arr_notify_data['url']                 = '';
                $notification_status                    = $this->NotificationService->send_notification($arr_notify_data);

                $type     = get_notification_type_of_user($arr_user['id']);

                if(isset($type) && !empty($type))
                {
                    // for mail
                    if($type['notification_by_email'] == 'on')
                    {
                        $arr_built_content         = [
                                        'USER_NAME'    => isset($arr_user['display_name'])?ucfirst($arr_user['display_name']):'NA',
                                        'Email'        => isset($arr_user['email'])?ucfirst($arr_user['email']):'NA' ,
                                        'SUBJECT'      => "Booking is rejected by the ".$this->user_first_name." for the ".$arr_booking['property_details']['property_name'],
                                        'STATUS'       => $booking_status,
                                        'PROJECT_NAME' => config('app.project.name')
                                     ];
                        $arr_mail_data                      = [];
                        $arr_mail_data['email_template_id'] = '15';
                        $arr_mail_data['arr_built_content'] = $arr_built_content;
                        $arr_mail_data['user']              = ['email' => isset($arr_user['email'])?ucfirst($arr_user['email']):'NA', 'first_name' => isset($arr_user['display_name'])?ucfirst($arr_user['display_name']):'NA'];

                        $status = $this->EmailService->send_mail($arr_mail_data);
                    }

                    // for sms
                    if($type['notification_by_sms'] == 'on')
                    {
                        $country_code  = isset($arr_user['country_code']) ? $arr_user['country_code'] : '';
                        $mobile_number = isset($arr_user['mobile_number']) ? $arr_user['mobile_number'] : '';

                        $arr_sms_data                  = [];
                        $arr_sms_data['msg']           = "Booking is rejected by the ".$this->user_first_name." for the ".$arr_booking['property_details']['property_name'];
                        $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                        $status = $this->SMSService->send_SMS($arr_sms_data);
                    }

                    // for push notification
                    if($type['notification_by_push'] == 'on')
                    {
                        $headings = 'Booking successfully rejected';
                        $content  = "Booking is rejected by the ".$this->user_first_name." for the ".$arr_booking['property_details']['property_name'];
                        $user_id  = $arr_user['id'];
                        $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                    }
                }

                Session::flash('success','Booking successfully rejected');
            }
            else
            {
                Session::flash('error','Booking was not able to reject. Please try again');
            }
        }
        else
        {
            Session::flash('error','Something went wrong. Please try again');
        }
        return redirect()->back();
    }


    /*
    | Function  : get form data and generate a ticket for refund and cancel booking from guest
    | Author    : Deepak Arvind Salunke
    | Date      : 09/05/2018
    | Output    : Success or Error
    */

    public function process_cancel(Request $request)
    {
        $notification_url = '/my-booking/cancelled';
        $user_type        = Session::get('user_type');
       
        $booking_id = base64_decode($request->input('cancel_booking_id'));

        $arr_data['booking_id']        = $booking_id;
        $arr_data['query_subject']     = trim($request->input('cancel_subject'));
        $arr_data['query_description'] = trim($request->input('cancel_reason'));
        $arr_data['user_id']           = $this->user_id;
        $arr_data['support_level']     = 'L2';
        $status                        = $this->SupportQueryModel->create($arr_data);

        $arr_support_built_content  = array(
                                        'USER_NAME' => isset($this->user_first_name)?$this->user_first_name:'NA',
                                        'SUBJECT'   => trim($request->input('cancel_subject'))
                                    );

        $arr_support_notify_data['notify_template_id'] = '4';
        $arr_support_notify_data['arr_built_content']  = $arr_support_built_content;
        $arr_support_notify_data['sender_id']          = $this->user_id;
        $arr_support_notify_data['sender_type']        = Session::get('user_type');
        $arr_support_notify_data['receiver_type']      = '3';
        $arr_support_notify_data['url']                = '/ticket';

        $obj_support_team = $this->SupportTeamModel->where('support_level','L2')->get();
        if(count($obj_support_team) > 0) {
            foreach($obj_support_team as $row) {
                $arr_support_notify_data['receiver_id'] = $row->id;
                $notification_status                    = $this->NotificationService->send_notification($arr_support_notify_data);
            }
        }

        $booking_status         = 'processing cancel';
        $data['booking_status'] = 7;
        $data['status_by']      = $this->user_id;
        $data['cancelled_by']   = $user_type;

        $update_booking = $this->BookingModel->where('id', $booking_id)->update($data);
        if($update_booking)
        {
            $obj_booking = $this->BookingModel->where('id', $booking_id)->with('property_details')->first();
            if($obj_booking)
            {
                $arr_booking = $obj_booking->toArray();
            }

            $obj_user_data = $this->UserModel->where('id', $arr_booking['property_booked_by'])->first();
            if($obj_user_data)
            {
                $arr_user = $obj_user_data->toArray();
            }

            $arr_built_content  = array(
                                    'USER_NAME' => isset($arr_user['first_name']) ? $arr_user['first_name'] : 'NA',
                                    'MESSAGE'   => "Your cancellation request is received and will process for the booking of ".$arr_booking['property_details']['property_name']
                                );

            $arr_notify_data['arr_built_content']  = $arr_built_content;
            $arr_notify_data['notify_template_id'] = '9';
            $arr_notify_data['template_text']      = "Your cancellation request is received and will process for the booking of ".$arr_booking['property_details']['property_name'];
            $arr_notify_data['sender_id']          = '1';
            $arr_notify_data['sender_type']        = '2';
            $arr_notify_data['receiver_type']      = Session::get('user_type');
            $arr_notify_data['receiver_id']        = $this->user_id;
            $arr_notify_data['url']                = $notification_url;

            $notification_status = $this->NotificationService->send_notification($arr_notify_data);

            //Send notification to admin
             $arr_built_content_admin  = array(
                                    'USER_NAME' => isset($arr_user['first_name']) ? $arr_user['first_name'] : 'NA',
                                    'MESSAGE'   => "User cancellation request is send to support for the booking of ".$arr_booking['property_details']['property_name']
                                    );

            $arr_notify_data_admin['arr_built_content']  = $arr_built_content_admin;
            $arr_notify_data_admin['notify_template_id'] = '9';
            $arr_notify_data_admin['template_text']      = "User cancellation request is send to support for the booking of ".$arr_booking['property_details']['property_name'];
            $arr_notify_data_admin['sender_id']          = $this->user_id;
            $arr_notify_data_admin['sender_type']        = Session::get('user_type');
            $arr_notify_data_admin['receiver_type']      = '2';
            $arr_notify_data_admin['receiver_id']        = '1';
            $arr_notify_data_admin['url']                = $notification_url;

            $notification_status = $this->NotificationService->send_notification($arr_notify_data_admin);

            $type = get_notification_type_of_user($this->user_id);

            if(isset($type) && !empty($type))
            {
                // for mail
                if($type['notification_by_email'] == 'on')
                {
                    $arr_built_content = [
                                            'USER_NAME'    => isset($arr_user['display_name']) ? ucfirst($arr_user['display_name']) : 'NA',
                                            'Email'        => isset($arr_user['email']) ? ucfirst($arr_user['email']) : 'NA' ,
                                            'SUBJECT'      => "Your cancellation request is received and will process for the booking of ".$arr_booking['property_details']['property_name'],
                                            'STATUS'       => $booking_status,
                                            'PROJECT_NAME' => config('app.project.name')
                                        ];
                $arr_mail_data                      = [];
                $arr_mail_data['email_template_id'] = '15';
                $arr_mail_data['arr_built_content'] = $arr_built_content;
                $arr_mail_data['user']              = [
                                                        'email' => isset($arr_user['email']) ? ucfirst($arr_user['email']) : 'NA',
                                                        'first_name' => isset($arr_user['display_name']) ? ucfirst($arr_user['display_name']) : 'NA'
                                                    ];

                    $status = $this->EmailService->send_mail($arr_mail_data);
                }

                // for sms
                if($type['notification_by_sms'] == 'on')
                {
                    $country_code  = isset($arr_user['country_code']) ? $arr_user['country_code'] : '';
                    $mobile_number = isset($arr_user['mobile_number']) ? $arr_user['mobile_number'] : '';

                    $arr_sms_data                  = [];
                    $arr_sms_data['msg']           = "Your cancellation request is received and will process for the booking of ".$arr_booking['property_details']['property_name'];
                    $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                    $status                        = $this->SMSService->send_SMS($arr_sms_data);
                }

                // for push notification
                if($type['notification_by_push'] == 'on')
                {
                    $headings = 'Cancel booking request successfully send';
                    $content  = "Your cancellation request is received and will process for the booking of ".$arr_booking['property_details']['property_name'];
                    $user_id  = $this->user_id;
                    $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                }
            }

            Session::flash('success','Cancel booking request successfully send');
        }
        else
        {
            Session::flash('error','Something went wrong. Please try again');
        }
        return redirect(url('/').$notification_url);

    } // end process_cancel


    /*
    | Function  : Store payment details after successful payment
    | Author    : Deepak Arvind Salunke
    | Date      : 08/05/2018
    | Output    : Success or Error
    */

    public function wallet_payment(Request $request)
    {
        $arr_booking = [];

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 12; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $transaction_id        = 'pay_A'.$randomString;
     
        $check_transaction_id  = $this->TransactionModel->where(array('transaction_id'=>$transaction_id))->get()->toArray();

        if(count( $check_transaction_id) >0)
        {
            $arr_json['status']  = 'error';
            //Session::flash('error','Transaction Id must be unique');
            $arr_json['message'] = 'Transaction Id must be unique';
        }

        $guest_invoice    = $host_invoice = '';
        $notification_url = '/my-booking/confirmed';
        $wallet_amount    = $request->input('wallet_amount');
        $payment_amount   = $request->input('amount_inr');
        $booking_id       = $request->input('booking_id');
        $used_coupon_id   = $request->input('used_coupon_id');

        // check if coupon code is used
        if (isset($used_coupon_id) && !empty($used_coupon_id) && $used_coupon_id != null) {
            
            // For first time use only
            $obj_coupon = $this->CouponModel->where('id', $used_coupon_id)
                                            ->where('status', 1)
                                            ->where('global_expiry', '>=',date("c"))
                                            ->first();
            if ($obj_coupon) {
                $arr_coupon = $obj_coupon->toArray();
                $coupon_id = $arr_coupon['id'];
            }

            if ($arr_coupon['coupon_use'] == 2 || $arr_coupon['coupon_use'] == 3) {
                $user = $this->auth->user();
                if ($user['is_coupon_used'] == 'no') {
                    $this->UserModel->where('id', $this->user_id)->update(array('is_coupon_used' => 'yes'));
                }
            }

            // To store coupon use
            $arr_coupons_used['coupon_id']  = $used_coupon_id;
            $arr_coupons_used['user_id']    = $this->user_id;
            $arr_coupons_used['booking_id'] = $booking_id;
            $this->CouponsUsedModel->create($arr_coupons_used);
        }

        $guest_booking    = $this->BookingModel->with(['property_details'])->where('id', $booking_id)->first();        
        if($guest_booking)
        {
            $arr_booking  = $guest_booking->toArray();

            $arr_booking_data['coupon_code_id'] = $used_coupon_id;
            $guest_booking->update( $arr_booking_data );
        }

        $arr_property_data = get_property_details($arr_booking['property_id']);

        // For Guest User
        $guest_transaction['transaction_id']     = $transaction_id;
        $guest_transaction['payment_type']       = 'booking';
        $guest_transaction['booking_id']         = $booking_id;
        $guest_transaction['user_id']            = $this->user_id;
        $guest_transaction['user_type']          = '1';
        $guest_transaction['amount']             = $payment_amount;
        $guest_transaction['booking_id']         = $arr_booking['id'];
        $guest_transaction['transaction_for']    = "Payment successfully paid for the ".$arr_booking['property_details']['property_name']." using wallet";
        $guest_transaction['transaction_date']   = date('Y-m-d H:i:s');
        $guest_transaction   = $this->TransactionModel->create($guest_transaction);

        if($guest_transaction)
        {
            $guest_user_data = $this->UserModel->where('id', $this->user_id)->first();
            $utype = 'guest';
            
            //$guest_invoice = $this->generateInvoice($transaction_id, $guest_id, $host_id, $user_type)
            $guest_invoice = $this->generateInvoice($guest_transaction->id,$this->user_id,$arr_booking['property_owner_id'],$utype);

            $this->TransactionModel->where('id',$guest_transaction->id)->update(['invoice' => $guest_invoice]);

            $this->add_unavailability_dates($booking_id);

            if($guest_user_data)
            {
                $guest_user  = $guest_user_data->toArray();

                $new_amount  = number_format($guest_user['wallet_amount'], 2, '.', '') - $payment_amount;

                // update wallet amount
                $guest_user_data->update(['wallet_amount' => $new_amount]);
            }

            $guest_built_content  = array(
                                    'USER_NAME' => isset($this->user_first_name) ? $this->user_first_name : 'NA',
                                    'MESSAGE'   => "Payment successfully paid for the ".$arr_booking['property_details']['property_name']
                                    );

            $guest_notify_data['arr_built_content']  = $guest_built_content;
            $guest_notify_data['notify_template_id'] = '9';
            $guest_notify_data['sender_id']          = '1';
            $guest_notify_data['sender_type']        = '2';
            $guest_notify_data['receiver_type']      = '1';
            $guest_notify_data['receiver_id']        = $this->user_id;
            $guest_notify_data['url']                = $notification_url;
            $notification_status                     = $this->NotificationService->send_notification($guest_notify_data);

            $type = get_notification_type_of_user($this->user_id);

            if(isset($type) && !empty($type))
            {
                // for mail
                if($type['notification_by_email'] == 'on')
                {
                    $guest_built_content         = [
                                        'USER_NAME'            => isset($guest_user['display_name'])?ucfirst($guest_user['display_name']):'NA',
                                        'Email'                => isset($guest_user['email']) ? ucfirst($guest_user['email']) : 'NA' ,
                                        'MESSAGE'              => "Payment successfully paid for the property <i>'".$arr_booking['property_details']['property_name']."'</i>",
                                        'PROJECT_NAME'         => config('app.project.name'),
                                        'NOTIFICATION_SUBJECT' => 'Notification'
                                     ];
                    $guest_mail_data                      = [];
                    $guest_mail_data['email_template_id'] = '13';
                    $guest_mail_data['arr_built_content'] = $guest_built_content;
                    $guest_mail_data['user']              = [ 'email' => isset($guest_user['email']) ? ucfirst($guest_user['email']) : 'NA', 'first_name' => isset($guest_user['display_name']) ? ucfirst($guest_user['display_name']) : 'NA' ];
                    $guest_mail_data['attachment']        = public_path('uploads/invoice/'.$guest_invoice);
                    $status                               = $this->EmailService->send_invoice_mail($guest_mail_data);
                }

                // for sms
                if($type['notification_by_sms'] == 'on')
                {
                    $country_code  = isset($guest_user['country_code']) ? $guest_user['country_code'] : '';
                    $mobile_number = isset($guest_user['mobile_number']) ? $guest_user['mobile_number'] : '';

                    $guest_sms_data                  = [];
                    $guest_sms_data['msg']           = "Payment successfully paid for the ".$arr_booking['property_details']['property_name'];
                    $guest_sms_data['mobile_number'] = $country_code.$mobile_number;
                    $status                          = $this->SMSService->send_SMS($guest_sms_data);
                }

                // for push notification
                if($type['notification_by_push'] == 'on')
                {
                    $headings = 'Payment successfully paid';
                    $content  = "Payment successfully paid for the ".$arr_booking['property_details']['property_name'];
                    $user_id  = $this->user_id;
                    $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
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
        $host_transaction['transaction_for']  = "Payment successfully received for the ".$arr_booking['property_details']['property_name']." by the ".$this->user_first_name;
        $host_transaction['transaction_date'] = date('Y-m-d H:i:s');

        $host_transaction   = $this->TransactionModel->create($host_transaction);
        if($host_transaction)
        {
            $host_user_data = $this->UserModel->where('id', $arr_booking['property_owner_id'])->first();
            $utype = 'host';

            //$guest_invoice = $this->generateInvoice($transaction_id, $guest_id, $host_id, $user_type)
            $host_invoice = $this->generateInvoice($host_transaction->id,$this->user_id,$arr_booking['property_owner_id'],$utype);

            $this->TransactionModel->where('id',$host_transaction->id)->update(['invoice' => $host_invoice]);

            if($host_user_data)
            {
                $host_user   = $host_user_data->toArray();
            }

            $host_built_content  = array(
                                     'USER_NAME' => isset($host_user['first_name']) ? $host_user['first_name'] : 'NA',
                                     'MESSAGE'   => "Payment successfully received for the ".$arr_booking['property_details']['property_name']." by the ".$this->user_first_name
                                    );

            $host_notify_data['arr_built_content']  = $host_built_content;
            $host_notify_data['notify_template_id'] = '9';
            $host_notify_data['sender_id']          = '1';
            $host_notify_data['sender_type']        = '2';
            $host_notify_data['receiver_type']      = '4';
            $host_notify_data['receiver_id']        = $arr_booking['property_owner_id'];
            $host_notify_data['url']                = $notification_url;
            $notification_status                    = $this->NotificationService->send_notification($host_notify_data);

            $type     = get_notification_type_of_user($arr_booking['property_owner_id']);
            
            if(isset($type) && !empty($type))
            {
                // for mail
                if($type['notification_by_email'] == 'on')
                {
                    $host_built_content         = [
                                        'USER_NAME'            => isset($host_user['display_name']) ? ucfirst($host_user['display_name']):'NA',
                                        'Email'                => isset($host_user['email'])?ucfirst($host_user['email']):'NA' ,
                                        'MESSAGE'              => "Payment successfully received for the <i>'".$arr_booking['property_details']['property_name']."'</i>' by the ".$this->user_first_name,
                                        'PROJECT_NAME'         => config('app.project.name'),
                                        'NOTIFICATION_SUBJECT' => 'Notification'
                                     ];
                    $host_mail_data                      = [];
                    $host_mail_data['email_template_id'] = '13';
                    $host_mail_data['arr_built_content'] = $host_built_content;
                    $host_mail_data['user']              = ['email' => isset($host_user['email'])?ucfirst($host_user['email']):'NA', 'first_name' => isset($host_user['display_name'])?ucfirst($host_user['display_name']):'NA'];
                    $host_mail_data['attachment']        = public_path('uploads/invoice/'.$host_invoice);
                    $status = $this->EmailService->send_invoice_mail($host_mail_data);
                }

                // for sms
                if($type['notification_by_sms'] == 'on')
                {
                    $country_code  = isset($host_user['country_code']) ? $host_user['country_code'] : '';
                    $mobile_number = isset($host_user['mobile_number']) ? $host_user['mobile_number'] : '';

                    $host_sms_data                  = [];
                    $host_sms_data['msg']           = "Payment successfully received for the ".$arr_booking['property_details']['property_name']." by the ".$this->user_first_name;
                    $host_sms_data['mobile_number'] = $country_code.$mobile_number;
                    $status                         = $this->SMSService->send_SMS($host_sms_data);
                }

                // for push notification
                if($type['notification_by_push'] == 'on')
                {
                    $headings = 'Payment successfully received';
                    $content  = "Payment successfully received for the ".$arr_booking['property_details']['property_name']." by the ".$this->user_first_name;
                    $user_id  = $arr_booking['property_owner_id'];
                    $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                }
            }
        }

        if ($guest_invoice != '' && $guest_invoice != null && $host_invoice != '' && $host_invoice != null ) {

            $update_arr = array('booking_status' => '5', 'payment_type'=>'wallet');
            $this->BookingModel->where('id', $booking_id)->update($update_arr);

            if ($arr_booking['property_type_slug'] == 'warehouse') {
                $selected_no_of_slots = $arr_property_data['available_no_of_slots'] - $arr_booking['selected_no_of_slots'];
                $this->PropertyModel->where('id', $arr_booking['property_id'])->update(array('available_no_of_slots' => $selected_no_of_slots));
            }
            else if ($arr_booking['property_type_slug'] == 'office-space') {
                $selected_of_employee = $arr_property_data['available_no_of_employee'] - $arr_booking['selected_of_employee'];
                $this->PropertyModel->where('id', $arr_booking['property_id'])->update(array('available_no_of_employee' => $selected_of_employee));
            }

            Session::put('BookingRequestData',[]);
            $arr_json['status']  = 'success';
            $arr_json['message'] = 'Payment successfully paid for the booking';   
        }
        else
        {
            $arr_json['status']  = 'error';
            $arr_json['message'] = 'Something went wrong. Please try again';
        }

        return json_encode($arr_json);

    } // end wallet_payment



    public function add_unavailability_dates($booking_id)
    {
        if( !empty( $booking_id ) && $booking_id != null )
        {
            $booking_data = $this->BookingModel->where('id',$booking_id)->first();
            $start_date = $booking_data['check_in_date'];
            $end_date   = $booking_data['check_out_date'];

            $obj_property = $this->PropertyModel->where('id', $booking_data['property_id'])
                            ->select('id', 'property_type_id', 'employee', 'room', 'desk', 'cubicles', 'no_of_slots', 'no_of_employee', 'no_of_room', 'no_of_desk', 'no_of_cubicles', 'number_of_guest', 'price_per')
                            ->first();

            if( $obj_property )
            {
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

                    if($total_available <= 0) 
                    {
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
