<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Common\Services\EmailService;
use App\Common\Services\SMSService;
use App\Common\Services\NotificationService;
use App\Models\BookingModel;
use App\Models\PropertyModel;
use App\Models\CouponModel;
use App\Models\CurrencyModel;
use App\Common\Traits\MultiActionTrait;

use Validator;
use Session;
use Auth;
use Hash;
use DB;
use Datatables;

class BookingController extends Controller
{
    //use MultiActionTrait;
    function __construct(   
                            BookingModel        $booking_model,
                            PropertyModel       $property_model,
                            UserModel           $user_model,
                            EmailService        $email_service,
                            SMSService          $sms_service,
                            NotificationService $notification_service,
                            CouponModel         $coupon_model,
                            CurrencyModel       $currency_model
                        )
    {
        $this->arr_data                   = [];
        $this->admin_panel_slug           = config('app.project.admin_panel_slug');
        $this->admin_url_path             = url(config('app.project.admin_panel_slug'));
        $this->property_image_public_path = url('/').config('app.project.img_path.property_image');
        $this->property_image_base_path   = public_path().config('app.project.img_path.property_image');
        $this->module_url_path            = $this->admin_url_path."/booking";
        $this->module_title               = "Booking";
        $this->module_view_folder         = "admin.booking";
        $this->module_icon                = "fa fa-home";
        $this->EmailService               = $email_service;
        $this->SMSService                 = $sms_service;
        $this->NotificationService        = $notification_service;
        $this->PropertyModel              = $property_model;
        $this->UserModel                  = $user_model;
        $this->BookingModel               = $booking_model;
        $this->BaseModel                  = $booking_model;
        $this->CouponModel                = $coupon_model;
        $this->CurrencyModel              = $currency_model;

    }

    public function all()
    {
        $property_table          = $this->PropertyModel->getTable();   
        $prefixed_property_table = DB::getTablePrefix().$this->PropertyModel->getTable();

        $user_table              = $this->UserModel->getTable();   
        $prefixed_user_table     = DB::getTablePrefix().$this->UserModel->getTable();

        $booking_table           = $this->BookingModel->getTable();   
        $prefixed_booking_table  = DB::getTablePrefix().$this->BookingModel->getTable();

        $arr_owner = $arr_booked_by = $arr_property = [];

        $arr_owner     = DB::table($booking_table)
                            ->select(DB::raw(  
                                                $prefixed_user_table.".id,".
                                                $prefixed_user_table.".first_name as owner_firstname,".
                                                $prefixed_user_table.".last_name as owner_lastname"
                                            ))
                            ->Join($prefixed_user_table,$prefixed_user_table.".id",'=',$prefixed_booking_table.'.property_owner_id')
                            ->groupby('property_owner_id')
                            ->get();

        $arr_booked_by = DB::table($booking_table)
                            ->select(DB::raw(  
                                                $prefixed_user_table.".id,".
                                                $prefixed_user_table.".first_name as booked_by_firstname,".
                                                $prefixed_user_table.".last_name as booked_by_lastname"
                                            ))
                            ->Join($prefixed_user_table,$prefixed_user_table.".id",'=',$prefixed_booking_table.'.property_booked_by')
                            ->groupby('property_booked_by')
                            ->get();

        $arr_property = DB::table($booking_table)
                            ->select(DB::raw(  
                                                $prefixed_property_table.".id,".
                                                $prefixed_property_table.".property_name"
                                            ))
                            ->Join($prefixed_property_table,$prefixed_property_table.".id",'=',$prefixed_booking_table.'.property_id')
                            ->groupby('property_id')
                            ->get();

        $this->arr_data['booking_status']   = 'all';
        $this->arr_data['arr_property']     = $arr_property;
        $this->arr_data['arr_owner']        = $arr_owner;
        $this->arr_data['arr_booked_by']    = $arr_booked_by;
        $this->arr_data['admin_status']     = 0;
        $this->arr_data['search_action']    = $this->module_url_path.'/all';
        $this->arr_data['page_title']       = str_singular("Manage All ".$this->module_title);
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_title']     = "All ".str_singular($this->module_title);
        $this->arr_data['page_icon']        = 'fa-list';
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;

        return view($this->module_view_folder.'.index',$this->arr_data);
    }

    public function newbooking()
    {
        $property_table          = $this->PropertyModel->getTable();
        $prefixed_property_table = DB::getTablePrefix().$this->PropertyModel->getTable();

        $user_table              = $this->UserModel->getTable();
        $prefixed_user_table     = DB::getTablePrefix().$this->UserModel->getTable();

        $booking_table           = $this->BookingModel->getTable();
        $prefixed_booking_table  = DB::getTablePrefix().$this->BookingModel->getTable();

        $arr_owner = $arr_booked_by = $arr_property = [];

        $arr_owner     = DB::table($booking_table)
                            ->select(DB::raw(  
                                                $prefixed_user_table.".id,".
                                                $prefixed_user_table.".first_name as owner_firstname,".
                                                $prefixed_user_table.".last_name as owner_lastname"
                                            ))
                            ->Join($prefixed_user_table,$prefixed_user_table.".id",'=',$prefixed_booking_table.'.property_owner_id')
                            ->groupby('property_owner_id')
                            ->get();

        $arr_booked_by = DB::table($booking_table)
                            ->select(DB::raw(  
                                            $prefixed_user_table.".id,".
                                            $prefixed_user_table.".first_name as booked_by_firstname,".
                                            $prefixed_user_table.".last_name as booked_by_lastname"
                                        ))
                            ->Join($prefixed_user_table,$prefixed_user_table.".id",'=',$prefixed_booking_table.'.property_booked_by')
                            ->groupby('property_booked_by')
                            ->get();

        $arr_property = DB::table($booking_table)
                            ->select(DB::raw(
                                            $prefixed_property_table.".id,".
                                            $prefixed_property_table.".property_name"
                                        ))
                            ->Join($prefixed_property_table,$prefixed_property_table.".id",'=',$prefixed_booking_table.'.property_id')
                            ->groupby('property_id')
                            ->get();

        $this->arr_data['booking_status']   = 'new';
        $this->arr_data['arr_property']     = $arr_property;
        $this->arr_data['arr_owner']        = $arr_owner;
        $this->arr_data['arr_booked_by']    = $arr_booked_by;
        $this->arr_data['admin_status']     = 1;
        $this->arr_data['search_action']    = $this->module_url_path.'/new';
        $this->arr_data['page_title']       = str_singular("Manage New ".$this->module_title);
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_title']     = "New ".str_singular($this->module_title);
        $this->arr_data['guest_user_path']  = $this->admin_url_path."/guest";
        $this->arr_data['guest_user_icon']  = 'fa-user';
        $this->arr_data['host_user_path']   = $this->admin_url_path."/host";
        $this->arr_data['host_user_icon']   = 'fa-user';
        $this->arr_data['page_icon']        = 'fa-list';
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;

        return view($this->module_view_folder.'.index',$this->arr_data);
    }

    public function confirmed()
    {
        $property_table          = $this->PropertyModel->getTable();
        $prefixed_property_table = DB::getTablePrefix().$this->PropertyModel->getTable();

        $user_table              = $this->UserModel->getTable();
        $prefixed_user_table     = DB::getTablePrefix().$this->UserModel->getTable();

        $booking_table           = $this->BookingModel->getTable();
        $prefixed_booking_table  = DB::getTablePrefix().$this->BookingModel->getTable();

        $arr_owner = $arr_booked_by = $arr_property = [];

        $arr_owner     = DB::table($booking_table)
                            ->select(DB::raw(
                                                $prefixed_user_table.".id,".
                                                $prefixed_user_table.".first_name as owner_firstname,".
                                                $prefixed_user_table.".last_name as owner_lastname"
                                            ))
                            ->Join($prefixed_user_table,$prefixed_user_table.".id",'=',$prefixed_booking_table.'.property_owner_id')
                            ->groupby('property_owner_id')
                            ->get();

        $arr_booked_by = DB::table($booking_table)
                            ->select(DB::raw(  
                                                $prefixed_user_table.".id,".
                                                $prefixed_user_table.".first_name as booked_by_firstname,".
                                                $prefixed_user_table.".last_name as booked_by_lastname"
                                            ))
                            ->Join($prefixed_user_table,$prefixed_user_table.".id",'=',$prefixed_booking_table.'.property_booked_by')
                            ->groupby('property_booked_by')
                            ->get();

        $arr_property = DB::table($booking_table)
                            ->select(DB::raw(
                                                $prefixed_property_table.".id,".
                                                $prefixed_property_table.".property_name"
                                            ))
                            ->Join($prefixed_property_table,$prefixed_property_table.".id",'=',$prefixed_booking_table.'.property_id')
                            ->groupby('property_id')
                            ->get();

        $this->arr_data['booking_status']   = 'confirmed';
        $this->arr_data['arr_property']     = $arr_property;
        $this->arr_data['arr_owner']        = $arr_owner; 
        $this->arr_data['arr_booked_by']    = $arr_booked_by; 
        $this->arr_data['admin_status']     = 2;
        $this->arr_data['search_action']    = $this->module_url_path.'/confirmed';
        $this->arr_data['page_title']       = str_singular("Manage Confirm ".$this->module_title);
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_title']     = "Confirm ".str_singular($this->module_title);
        $this->arr_data['page_icon']        = 'fa-list';
        $this->arr_data['guest_user_path']  = $this->admin_url_path."/guest";
        $this->arr_data['guest_user_icon']  = 'fa-user';
        $this->arr_data['host_user_path']   = $this->admin_url_path."/host";
        $this->arr_data['host_user_icon']   = 'fa-user';
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
        return view($this->module_view_folder.'.index',$this->arr_data);
    }

    public function completed()
    {
        $property_table          = $this->PropertyModel->getTable();   
        $prefixed_property_table = DB::getTablePrefix().$this->PropertyModel->getTable();

        $user_table              = $this->UserModel->getTable();   
        $prefixed_user_table     = DB::getTablePrefix().$this->UserModel->getTable();

        $booking_table           = $this->BookingModel->getTable();   
        $prefixed_booking_table  = DB::getTablePrefix().$this->BookingModel->getTable();

        $arr_owner = $arr_booked_by = $arr_property = [];

        $arr_owner     = DB::table($booking_table)
                            ->select(DB::raw(  
                                                $prefixed_user_table.".id,".
                                                $prefixed_user_table.".first_name as owner_firstname,".
                                                $prefixed_user_table.".last_name as owner_lastname"
                                            ))
                            ->Join($prefixed_user_table,$prefixed_user_table.".id",'=',$prefixed_booking_table.'.property_owner_id')
                            ->groupby('property_owner_id')
                            ->get();

        $arr_booked_by = DB::table($booking_table)
                            ->select(DB::raw(
                                                $prefixed_user_table.".id,".
                                                $prefixed_user_table.".first_name as booked_by_firstname,".
                                                $prefixed_user_table.".last_name as booked_by_lastname"
                                            ))
                            ->Join($prefixed_user_table,$prefixed_user_table.".id",'=',$prefixed_booking_table.'.property_booked_by')
                            ->groupby('property_booked_by')
                            ->get();

        $arr_property = DB::table($booking_table)
                            ->select(DB::raw(  
                                                $prefixed_property_table.".id,".
                                                $prefixed_property_table.".property_name"
                                            ))
                            ->Join($prefixed_property_table,$prefixed_property_table.".id",'=',$prefixed_booking_table.'.property_id')
                            ->groupby('property_id')
                            ->get();

        $this->arr_data['booking_status']   = 'completed';
        $this->arr_data['arr_property']     = $arr_property;
        $this->arr_data['arr_owner']        = $arr_owner; 
        $this->arr_data['arr_booked_by']    = $arr_booked_by; 
        $this->arr_data['admin_status']     = 3;
        $this->arr_data['search_action']    = $this->module_url_path.'/completed';
        $this->arr_data['page_title']       = str_singular("Manage Completed ".$this->module_title);
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_title']     = "Completed ".str_singular($this->module_title);
        $this->arr_data['page_icon']        = 'fa-list';
        $this->arr_data['guest_user_path']  = $this->admin_url_path."/guest";
        $this->arr_data['guest_user_icon']  = 'fa-user';
        $this->arr_data['host_user_path']   = $this->admin_url_path."/host";
        $this->arr_data['host_user_icon']   = 'fa-user';
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
        return view($this->module_view_folder.'.index',$this->arr_data);
    }

    public function cancel()
    {
        $property_table          = $this->PropertyModel->getTable();   
        $prefixed_property_table = DB::getTablePrefix().$this->PropertyModel->getTable();

        $user_table              = $this->UserModel->getTable();   
        $prefixed_user_table     = DB::getTablePrefix().$this->UserModel->getTable();

        $booking_table           = $this->BookingModel->getTable();   
        $prefixed_booking_table  = DB::getTablePrefix().$this->BookingModel->getTable();

        $arr_owner = $arr_booked_by = $arr_property = [];

        $arr_owner     = DB::table($booking_table)
                            ->select(DB::raw(  
                                                $prefixed_user_table.".id,".
                                                $prefixed_user_table.".first_name as owner_firstname,".
                                                $prefixed_user_table.".last_name as owner_lastname"
                                            ))
                            ->Join($prefixed_user_table,$prefixed_user_table.".id",'=',$prefixed_booking_table.'.property_owner_id')
                            ->groupby('property_owner_id')
                            ->get();

        $arr_booked_by = DB::table($booking_table)
                            ->select(DB::raw(  
                                                $prefixed_user_table.".id,".
                                                $prefixed_user_table.".first_name as booked_by_firstname,".
                                                $prefixed_user_table.".last_name as booked_by_lastname"
                                            ))
                            ->Join($prefixed_user_table,$prefixed_user_table.".id",'=',$prefixed_booking_table.'.property_booked_by')
                            ->groupby('property_booked_by')
                            ->get();

        $arr_property = DB::table($booking_table)
                            ->select(DB::raw(  
                                                $prefixed_property_table.".id,".
                                                $prefixed_property_table.".property_name"
                                            ))
                            ->Join($prefixed_property_table,$prefixed_property_table.".id",'=',$prefixed_booking_table.'.property_id')
                            ->groupby('property_id')
                            ->get();

        $this->arr_data['booking_status']   = 'cancel';
        $this->arr_data['arr_property']     = $arr_property;
        $this->arr_data['arr_owner']        = $arr_owner; 
        $this->arr_data['arr_booked_by']    = $arr_booked_by; 
        $this->arr_data['admin_status']     = 4;
        $this->arr_data['search_action']    = $this->module_url_path.'/cancel';
        $this->arr_data['page_title']       = str_singular("Manage Cancel ".$this->module_title);
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_title']     = "Cancel ".str_singular($this->module_title);
        $this->arr_data['page_icon']        = 'fa-list';
        $this->arr_data['guest_user_path']  = $this->admin_url_path."/guest";
        $this->arr_data['guest_user_icon']  = 'fa-user';
        $this->arr_data['host_user_path']   = $this->admin_url_path."/host";
        $this->arr_data['host_user_icon']   = 'fa-user';
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
        return view($this->module_view_folder.'.index',$this->arr_data);
    }

    public function load_data(Request $request)
    {
        $currency_symbol    = '<i class="fa fa-inr" aria-hidden="true"></i> ';
        $column             = '';
        $UserData           = $final_array = [];
        $property_name      = $request->input('property_name');
        $property_owner_id  = $request->input('property_owner_id');
        $property_booked_id = $request->input('property_booked_id');
        $booking_date       = $request->input('booking_date');
        $user_id            = $request->input('user_id');
        $host_user_id       = $request->input('host_user_id');
        $booking_status     = $request->input('booking_status');

        if ($request->input('order')[0]['column'] == 1) {
            $column = "id";
        }
        if ($request->input('order')[0]['column'] == 2) {
            $column = "property_name";
        }
        if ($request->input('order')[0]['column'] == 3) {
            $column = "first_name";
        }
        if ($request->input('order')[0]['column'] == 4) {
            $column = "first_name";
        }
        if ($request->input('order')[0]['column'] == 5) {
            $column = "coupon_code";
        }
        if ($request->input('order')[0]['column'] == 6) {
            $column = "property_amount";
        }
        if ($request->input('order')[0]['column'] == 8) {
            $column = "gst_amount";
        }
        if ($request->input('order')[0]['column'] == 9) {
            $column = "total_night_price";
        }
        if ($request->input('order')[0]['column'] == 10) {
            $column = "coupen_code_amount";
        }
        if ($request->input('order')[0]['column'] == 11) {
            $column = "total_amount";
        }
        
        $order = strtoupper($request->input('order')[0]['dir']);
        $admin_status_id = $request->input('admin_status');

        $obj_currency = $this->CurrencyModel->where('currency_code', 'INR')->first();
        if($obj_currency) {
            $arr_currency    = $obj_currency->toArray();
            $currency_symbol = $arr_currency['currency'];
        }

        $property_table          = $this->PropertyModel->getTable();   
        $prefixed_property_table = DB::getTablePrefix().$this->PropertyModel->getTable();

        $booking_table           = $this->BookingModel->getTable();   
        $prefixed_booking_table  = DB::getTablePrefix().$this->BookingModel->getTable();

        $coupon_table            = $this->CouponModel->getTable();   
        $prefixed_coupon_table   = DB::getTablePrefix().$this->CouponModel->getTable();

        $user_table              = $this->UserModel->getTable();   
        $prefixed_user_table     = DB::getTablePrefix().$this->UserModel->getTable();

        $obj_data                = DB::table($booking_table)
                                    ->select(DB::raw(  
                                            $prefixed_booking_table.".*,".
                                            $prefixed_property_table.".property_name as property_name,".
                                            $prefixed_property_table.".currency as currency,".
                                            $prefixed_coupon_table.".coupon_code as coupon_code,".
                                            $prefixed_user_table.".first_name as owner_firstname,".
                                            $prefixed_user_table.".last_name as owner_lastname,".
                                            "DATE_FORMAT(".$prefixed_booking_table.".created_at,'%Y-%m-%d') as booking_date"
                                        ))
                                    //->whereIn('booking_status',$arr_status_id)
                                    ->Join($prefixed_property_table,$prefixed_property_table.".id",'=',$prefixed_booking_table.'.property_id')
                                    ->leftJoin($prefixed_coupon_table,$prefixed_coupon_table.".id",'=',$prefixed_booking_table.'.coupon_code_id')
                                    ->leftJoin($prefixed_user_table,$prefixed_user_table.".id",'=',$prefixed_booking_table.'.property_owner_id')
                                    ->where($prefixed_booking_table.'.booking_status','!=','3');

        if($user_id != '') {
            $obj_data = $obj_data->where($prefixed_booking_table.'.property_booked_by',base64_decode($user_id));
        }
        if($host_user_id != '') {
            $obj_data = $obj_data->where($prefixed_booking_table.'.property_owner_id',base64_decode($host_user_id));
        }
        if($property_name != '') {
            $obj_data = $obj_data->where($prefixed_booking_table.'.property_id',base64_decode($property_name));
        }
        if($property_owner_id != '') {
            $obj_data = $obj_data->where($prefixed_booking_table.'.property_owner_id',base64_decode($property_owner_id));
        }
        if($property_booked_id != '') {
            $obj_data = $obj_data->where($prefixed_booking_table.'.property_booked_by',base64_decode($property_booked_id));
        }
        
        if($booking_date != '') {
            $book_date = date('Y-m-d',strtotime($booking_date));
            $obj_data  = $obj_data->where($prefixed_booking_table.'.created_at','LIKE','%'.$book_date.'%'); 
        }
        if($booking_status == 'confirmed') {
            $obj_data = $obj_data->where($prefixed_booking_table.'.check_in_date','>=', date("Y-m-d"))
                                 ->where($prefixed_booking_table.'.booking_status','5');
        }
        else if($booking_status == 'completed') {
            $obj_data = $obj_data->where($prefixed_booking_table.'.check_in_date','<', date("Y-m-d"))
                                 ->where($prefixed_booking_table.'.booking_status','5');
        }
        else if($booking_status == 'cancel') {
            $obj_data = $obj_data->where(function($q) use($prefixed_booking_table) {
                                    $q->where($prefixed_booking_table.'.booking_status','6')
                                      ->orWhere($prefixed_booking_table.'.booking_status','7');
                                });
        }

        $count = count($obj_data->get());

        if($order == 'ASC' && $column == '') {
            $obj_data = $obj_data->orderBy('id','DESC')->limit($_GET['length'])->offset($_GET['start']);
        }
        if( $order != '' && $column != '' ) {
            $obj_data = $obj_data->orderBy($column,$order)->limit($_GET['length'])->offset($_GET['start']);
        }

        $UserData = $obj_data->get();
        $resp['draw']            = $_GET['draw'];
        $resp['recordsTotal']    = $count;
        $resp['recordsFiltered'] = $count;
        $booking_status          = ''; 

        if(count($UserData)>0) {
            $i = 0;
            foreach($UserData as $data) {
                $booking_id = isset($data->booking_id) ? $data->booking_id : '';

                $property_booked_by  = [];
                $property_booked_by  = get_user_details($data->property_booked_by);
                $property_owner_name = $data->owner_firstname."&nbsp;".$data->owner_lastname;

                $booked_by = isset($property_booked_by['first_name']) && $property_booked_by['last_name'] ? $property_booked_by['first_name']."&nbsp;".$property_booked_by['last_name'] : "-";

                if(!empty($data->coupon_code)) {
                    $coupon_code = $data->coupon_code;
                } else {
                    $coupon_code = "-";
                }

                $check_in_date  = isset($data->check_in_date) ? date('d-M-Y',strtotime($data->check_in_date)) : '';
                $check_out_date = isset($data->check_out_date) ? date('d-M-Y',strtotime($data->check_out_date)) : '';
                $booking_date   = isset($data->booking_date) ? date('d-M-Y',strtotime($data->booking_date)) : '';

                $booking_info = "";
                $booking_info .= "<b>Booking ID : </b>".$booking_id."<br><b>Coupon Code : </b>".$coupon_code."<br><b>Check In Date : </b>".$check_in_date."<br><b>Check Out Date : </b>".$check_out_date."<br><b>No Of Days : </b>&nbsp;".$data->no_of_days."<br><b>Booking Date : </b>".$booking_date;

                if($data->booking_status != null && $data->booking_status == "4") {
                    $booking_info .= "<br><b>Reason For Rejection</b><br>".$data->reject_reason;
                }
               
                $amount = "<b>Property Amount : </b>".$currency_symbol.' '.$data->property_amount."<br>
                           <b>GST Tax Price : </b>".$currency_symbol.' '.$data->gst_amount."<br>
                           <b>Service fee : </b>".$currency_symbol.' '.$data->service_fee."<br>
                           <b>Coupon Code Amount : </b>".$data->coupen_code_amount."<br>
                           <b>Total Amount : </b>".$currency_symbol.' '.$data->total_amount;

                if($data->booking_status == 5 && $data->check_in_date >= date("Y-m-d") ) {
                    $booking_status = '<span class="badge badge-success" style="padding: 9px;">Confirmed</span>';
                } else if($data->booking_status == 5 && $data->check_in_date < date("Y-m-d") ) {
                    $booking_status = '<span class="badge badge-warning" style="padding: 9px;">Completed</span>';
                } else if($data->booking_status == 6) {
                    $booking_status = '<span class="badge badge-important" style="padding: 9px;">Cancel</span>';
                } else if($data->booking_status == 7) {
                    $booking_status = '<span class="badge badge-important" style="padding: 9px;">Requested</span>';
                }

                $built_view_href = $this->module_url_path.'/view/'.base64_encode($data->id);
                $built_view_button = "<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' href='".$built_view_href."'  data-original-title='View'><i class='fa fa-eye' ></i></a>";

                $final_array[$i][0] = "<input type='checkbox' name='checked_record[]' id='checked_record' class='checked_record' value='".base64_encode($data->id)."'/>";
                $final_array[$i][1] = $data->property_name;
                $final_array[$i][2] = $property_owner_name;
                $final_array[$i][3] = $booked_by;
                $final_array[$i][4] = $booking_info;
                $final_array[$i][5] = $amount;
                $final_array[$i][6] = $booking_status;
                $final_array[$i][7] = $built_view_button;
                $i++;
            }
        }

        $resp['data'] = $final_array;
        echo str_replace("\/", "/",  json_encode($resp));exit;
    }
  
    public function view($enc_property_id)
    {
        $arr_property_data = [];
        if($enc_property_id != false && $enc_property_id != "") {
            $id = base64_decode($enc_property_id);

            $obj_property_data = $this->BookingModel
                                      ->with('property_details','user_details','property_details.property_type','property_details.property_rules','property_details.property_images','property_details.property_unavailability','property_details.property_aminities.aminities','property_details.property_bed_arrangment','booking_by_user_details')
                                      ->where('id',$id)
                                      ->first();
            if($obj_property_data) {
                $arr_property_data = $obj_property_data->toArray();
            }
        }

        $this->arr_data['arr_property_data']          = $arr_property_data;
        $this->arr_data['page_title']                 = str_singular("View ".$this->module_title);
        $this->arr_data['module_icon']                = $this->module_icon;
        $this->arr_data['module_title']               = "View ".str_singular($this->module_title);
        $this->arr_data['page_icon']                  = 'fa-view';
        $this->arr_data['module_url_path']            = $this->module_url_path;
        $this->arr_data['admin_panel_slug']           = $this->admin_panel_slug;
        $this->arr_data['property_image_public_path'] = $this->property_image_public_path;
        $this->arr_data['property_image_base_path']   = $this->property_image_base_path;

        return view($this->module_view_folder.'.view',$this->arr_data);
    }
    /*work by kavita*/
   
     /*end */
}


