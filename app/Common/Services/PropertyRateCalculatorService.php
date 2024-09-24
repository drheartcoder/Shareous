<?php

namespace App\Common\Services;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\PropertyModel;
use App\Models\PropertyUnavailabilityModel;

use DB;
use Session;

class PropertyRateCalculatorService extends Controller
{   
    public function __construct(PropertyModel $property_model)
    {
        DB::connection()->enableQueryLog();
        $this->PropertyModel = $property_model;
    }

    public function calculate_rate($checkin_date, $checkout_date, $property_id, $discount_type = false, $discount_rate = false, $guests, $property_type_slug, $available_no_of_slots, $available_no_of_employee, $available_no_of_room, $available_no_of_desk, $available_no_of_cubicles)
    {
        $arr_dates = $arr_property_rate = $arr_property_rate = $site_settings_info = [];
        $gst_amount = $discount_price = $gst = $service_fee = $service_fee_percentage = 0;
        $apply_gst = "no";

        // get number of days
        $arr_dates      = $this->make_dates_array($checkin_date,$checkout_date);
        $number_of_days = count($arr_dates);

        // Check if GST is added by the Host
        $apply_gst = check_host_gst($property_id);
        
        // get total unavaialable dates
        $total_unavaialable_dates = $this->get_unavaialable_dates($property_id,$checkin_date,$checkout_date);

        // convert total unavaialable dates into number of nights
        if ($total_unavaialable_dates == 0 || $total_unavaialable_dates == 1) {
            $number_of_nights = $total_unavaialable_dates;
        }
        if ($total_unavaialable_dates > 1) {
            $number_of_nights = ($total_unavaialable_dates-1);
        }

        // get property rates
        $obj_property_rates = $this->PropertyModel->where('id','=',$property_id)->first(['id', 'price_per_night', 'currency_code', 'property_area', 'no_of_slots', 'employee', 'room', 'desk', 'cubicles', 'no_of_employee', 'no_of_room', 'no_of_desk', 'no_of_cubicles', 'price_per_sqft', 'price_per_office', 'room_price', 'desk_price', 'cubicles_price']);
        if ($obj_property_rates) {
            $arr_property_rate = $obj_property_rates->toArray();
        }

        $price_per_sqft  = isset($arr_property_rate['price_per_sqft'])  ? $arr_property_rate['price_per_sqft']  : '';
        $price_per_night = isset($arr_property_rate['price_per_night']) ? $arr_property_rate['price_per_night'] : '';
        $room_price      = isset($arr_property_rate['room_price'])      ? $arr_property_rate['room_price']      : '';
        $desk_price      = isset($arr_property_rate['desk_price'])      ? $arr_property_rate['desk_price']      : '';
        $cubicles_price  = isset($arr_property_rate['cubicles_price'])  ? $arr_property_rate['cubicles_price']  : '';

        if($arr_property_rate['currency_code'] != Session::get('get_currency')) {
            $price_per_night = currencyConverterAPI($arr_property_rate['currency_code'], Session::get('get_currency'), $arr_property_rate['price_per_night']);
            $price_per_sqft  = currencyConverterAPI($arr_property_rate['currency_code'], Session::get('get_currency'), $arr_property_rate['price_per_sqft']);

            $room_price      = currencyConverterAPI($arr_property_rate['currency_code'], Session::get('get_currency'), $arr_property_rate['room_price']);
            $desk_price      = currencyConverterAPI($arr_property_rate['currency_code'], Session::get('get_currency'), $arr_property_rate['desk_price']);
            $cubicles_price  = currencyConverterAPI($arr_property_rate['currency_code'], Session::get('get_currency'), $arr_property_rate['cubicles_price']);
        } 
        else {
            $price_per_night = $arr_property_rate['price_per_night'];
            $price_per_sqft  = $arr_property_rate['price_per_sqft'];

            $room_price      = $arr_property_rate['room_price'];
            $desk_price      = $arr_property_rate['desk_price'];
            $cubicles_price  = $arr_property_rate['cubicles_price'];
        }

        if($property_type_slug == 'warehouse') {
            $slot_per_sqft     = $arr_property_rate['property_area'] / $arr_property_rate['no_of_slots'];
            $slot_price        = $slot_per_sqft * $price_per_sqft;
            $total_night_price = $number_of_nights * $slot_price * $available_no_of_slots;

            // If GST is added then only apply GST
            if( $apply_gst == 'yes' ) {
                $gst = get_gst_data(0, 'warehouse');
            }
        }
        else if($property_type_slug == 'office-space') {
            
            $cal_employee_price = $cal_room_price = $cal_desk_price = $cal_cubicles_price = 0;

            if($arr_property_rate['room'] == 'on' && $available_no_of_room != 0){
                $cal_room_price = $available_no_of_room * $room_price;
            }
            if($arr_property_rate['desk'] == 'on' && $available_no_of_desk != 0){
                $cal_desk_price = $available_no_of_desk * $desk_price;
            }
            if($arr_property_rate['cubicles'] == 'on' && $available_no_of_cubicles != 0){
                $cal_cubicles_price = $available_no_of_cubicles * $cubicles_price;
            }

            $total_night_price = $number_of_nights * ($cal_employee_price + $cal_room_price + $cal_desk_price + $cal_cubicles_price);

            // If GST is added then only apply GST
            if( $apply_gst == 'yes' ) {
                $gst = get_gst_data(0, 'office-space');
            }
        }
        else {
            $total_night_price = ($price_per_night) * ($number_of_nights) * $guests;

            $night_price = currencyConverterAPI($arr_property_rate['currency_code'],'INR',$arr_property_rate['price_per_night']);

            // If GST is added then only apply GST
            if( $apply_gst == 'yes' ) {
                $gst = get_gst_data($night_price, 'other');
            }
        }

        $total_price    = $total_night_price;
        $property_price = $arr_property_rate['price_per_night'] * ($number_of_nights);

        if (!empty($discount_type) && $discount_type != null && !empty($discount_rate) && $discount_rate != null) {
            if ($discount_type == 'percentage') {
                $discount_price = $total_price * ($discount_rate / 100);
            } else if($discount_type == 'amount') {
                $discount_price = $discount_rate;
            }
        }

        $gst_amount           = ($gst / 100) * $total_night_price;
        $total_amount         = ($total_price + $gst_amount);
        $property_total_price = $property_price + $gst_amount;

        $service_fee_data = get_service_fee($total_amount);

        $service_fee_gst_percentage = $service_fee_data['service_fee_gst_percentage'];
        $service_fee_percentage     = $service_fee_data['service_fee_percentage'];
        $service_fee_gst_amount     = $service_fee_data['service_fee_gst_amount'];
        $service_fee                = $service_fee_data['service_fee'];

        $property_total_price = $service_fee_gst_amount + $service_fee + $property_total_price;
        $total_amount         = $service_fee_gst_amount + $service_fee + $total_amount;

        $arr_property_rate['number_of_nights']           = $number_of_nights;
        $arr_property_rate['gst']                        = $gst;
        $arr_property_rate['gst_amount']                 = number_format($gst_amount, 2, '.', '');
        $arr_property_rate['service_fee']                = $service_fee;
        $arr_property_rate['service_fee_percentage']     = $service_fee_percentage;
        $arr_property_rate['service_fee_gst_percentage'] = $service_fee_gst_percentage;
        $arr_property_rate['service_fee_gst_amount']     = $service_fee_gst_amount;
        $arr_property_rate['property_type_slug']         = $property_type_slug;

        $arr_property_rate['price_per_sqft']             = round($price_per_sqft, 2);
        $arr_property_rate['available_no_of_slots']      = $available_no_of_slots;

        $arr_property_rate['available_no_of_employee']   = $available_no_of_employee;
        $arr_property_rate['available_no_of_room']       = $available_no_of_room;
        $arr_property_rate['available_no_of_desk']       = $available_no_of_desk;
        $arr_property_rate['available_no_of_cubicles']   = $available_no_of_cubicles;

        $arr_property_rate['room_price']                 = round($room_price, 2);
        $arr_property_rate['desk_price']                 = round($desk_price, 2);
        $arr_property_rate['cubicles_price']             = round($cubicles_price, 2);

        $arr_property_rate['price_per_night']            = round($price_per_night, 2);
        $arr_property_rate['number_of_guests']           = $guests;

        $arr_property_rate['discount_price']             = number_format($discount_price, 2, '.', '');
        $arr_property_rate['total_night_price']          = number_format($total_night_price, 2, '.', '');
        $arr_property_rate['total_payble_amount']        = number_format($total_amount, 2, '.', '');
        $arr_property_rate['total_amount']               = $property_total_price;

        return $arr_property_rate;
    }
    
    public function make_dates_array($start_date,$end_date)
    {
        $dates_array = [];

        $period = new \DatePeriod(
            new \DateTime($start_date),
            new \DateInterval('P1D'),
            new \DateTime($end_date)
        );

        foreach ($period as $key => $value) {
            $dates_array[] = $value->format('Y-m-d');      
        }
        $dates_array[] = $end_date;

        return $dates_array;
    }

    public function get_unavaialable_dates($property_id,$checkin_date,$checkout_date)
    {
        $total_unavaialable_dates = $number_of_days = $total_unavaialable_dates_cnt = 0;
        $arr_unavailable_dates = $arr_tmp_unavalable_dates = $arr_dates = $interval_dates_count = $arr_unavaialable_dates = $dates_array = [];

        $start_date = date("Y-m-d",strtotime($checkin_date));
        $end_date   = date("Y-m-d",strtotime($checkout_date));
        
        $obj_unavailable_dates = PropertyUnavailabilityModel::where(['property_id'=>$property_id])->get();

        if ($obj_unavailable_dates) {
            $arr_tmp_unavalable_dates = $obj_unavailable_dates->toArray();

            if (count($arr_tmp_unavalable_dates) > 0 ) {
                foreach ($arr_tmp_unavalable_dates as $key => $date) {
                    if(isset($date['from_date']) &&  $date['from_date'] != "" && isset($date['to_date']) &&  $date['to_date'] != ""){
                        $arr_dates = $this->make_dates_array($date['from_date'],$date['to_date']);
                        array_push($arr_unavailable_dates ,$arr_dates);
                    }
                }
            }
            $arr_unavailable_dates = array_flatten($arr_unavailable_dates);
        }

        $interval_dates_count = $this->make_dates_array($start_date,$end_date);

        $total_unavaialable_dates_cnt = 0;
        foreach ($interval_dates_count as $value) {
            if(!in_array($value, $arr_unavailable_dates)) {
                $total_unavaialable_dates_cnt++;
            }
        }

        return $total_unavaialable_dates_cnt;
    }

    public function check_availability($property_id,$checkin_date,$checkout_date)
    {
        $arr_unavaialable_dates = []; $cnt = 0;

        $start_date = date("Y-m-d",strtotime($checkin_date));
        $end_date = date("Y-m-d",strtotime($checkout_date));        

        $obj_unavaialable_dates = PropertyUnavailabilityModel::whereRaw("property_id = ".$property_id." AND (
                                    ((DATE('".$start_date."') BETWEEN DATE(from_date) AND DATE(to_date)) OR
                                    (DATE('".$end_date."') BETWEEN DATE(from_date) AND DATE(to_date)) OR
                                    (DATE(from_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."')) OR
                                    (DATE(to_date) BETWEEN DATE('".$start_date."') AND DATE('".$end_date."'))) )")
                                ->groupBy('from_date','to_date')                                       
                                ->get();

        if(count($obj_unavaialable_dates) > 0) {
            $arr_unavaialable_dates = $obj_unavaialable_dates->toArray();                        
            $cnt = count($arr_unavaialable_dates);
        }     

        return $cnt;
    }

    public function calculate_rate_api($checkin_date, $checkout_date, $property_id, $discount_type = false, $discount_rate = false, $user_currency = 'INR', $guests, $property_type_slug, $available_no_of_slots, $available_no_of_employee, $available_no_of_room, $available_no_of_desk, $available_no_of_cubicles)
    {   
        $arr_dates = $arr_property_rate = $arr_property_rate = $site_settings_info = [];
        $total_service_amount = $discount_price = $gst = $gst_amount = $service_fee = $service_fee_percentage = 0;
        $apply_gst = "no";

        $arr_dates      = $this->make_dates_array($checkin_date,$checkout_date);
        $number_of_days = count($arr_dates);

        // Check if GST is added by the Host
        $apply_gst = check_host_gst($property_id);
        
        $total_unavaialable_dates = $this->get_unavaialable_dates($property_id,$checkin_date,$checkout_date);

        if($total_unavaialable_dates == 0 || $total_unavaialable_dates == 1) {
            $number_of_nights = $total_unavaialable_dates;
        }

        if($total_unavaialable_dates > 1) {
            $number_of_nights = ($total_unavaialable_dates-1);
        }

        $obj_property_rates = $this->PropertyModel->where('id','=',$property_id)->first(['id', 'price_per_night', 'currency_code', 'property_area', 'no_of_slots', 'employee', 'room', 'desk', 'cubicles', 'no_of_employee', 'no_of_room', 'no_of_desk', 'no_of_cubicles', 'price_per_sqft', 'price_per_office', 'room_price', 'desk_price', 'cubicles_price']);
        if($obj_property_rates) {
            $arr_property_rate = $obj_property_rates->toArray();
        }

        if($arr_property_rate['currency_code'] != $user_currency) {
            $price_per_night = currencyConverterAPI($arr_property_rate['currency_code'], $user_currency, $arr_property_rate['price_per_night']);
            $price_per_sqft  = currencyConverterAPI($arr_property_rate['currency_code'], $user_currency, $arr_property_rate['price_per_sqft']);

            $room_price      = currencyConverterAPI($arr_property_rate['currency_code'], Session::get('get_currency'), $arr_property_rate['room_price']);
            $desk_price      = currencyConverterAPI($arr_property_rate['currency_code'], Session::get('get_currency'), $arr_property_rate['desk_price']);
            $cubicles_price  = currencyConverterAPI($arr_property_rate['currency_code'], Session::get('get_currency'), $arr_property_rate['cubicles_price']);
        } else {
            $price_per_sqft  = isset($arr_property_rate['price_per_sqft']) ? $arr_property_rate['price_per_sqft'] : '';
            $price_per_night = isset($arr_property_rate['price_per_night']) ? $arr_property_rate['price_per_night'] : '';

            $room_price      = isset($arr_property_rate['room_price']) ? $arr_property_rate['room_price'] : '';
            $desk_price      = isset($arr_property_rate['desk_price']) ? $arr_property_rate['desk_price'] : '';
            $cubicles_price  = isset($arr_property_rate['cubicles_price']) ? $arr_property_rate['cubicles_price'] : '';
        }
        
        $arr_property_rate['currency_code'] = $user_currency;
        
        if($property_type_slug == 'warehouse') {
            $slot_per_sqft     = $arr_property_rate['property_area'] / $arr_property_rate['no_of_slots'];
            $slot_price        = $slot_per_sqft * $price_per_sqft;
            $total_night_price = $number_of_nights * $slot_price * $available_no_of_slots;

            // If GST is added then only apply GST
            if( $apply_gst == 'yes' ) {
                $gst = get_gst_data(0, 'warehouse');
            }
        } else if($property_type_slug == 'office-space') {
            $cal_employee_price = $cal_room_price = $cal_desk_price = $cal_cubicles_price = 0;

            if($arr_property_rate['room'] == 'on' && $available_no_of_room != 0) {
                $cal_room_price = $available_no_of_room * $room_price;
            }
            if($arr_property_rate['desk'] == 'on' && $available_no_of_desk != 0) {
                $cal_desk_price = $available_no_of_desk * $desk_price;
            }
            if($arr_property_rate['cubicles'] == 'on' && $available_no_of_cubicles != 0) {
                $cal_cubicles_price = $available_no_of_cubicles * $cubicles_price;
            }

            $total_night_price = $number_of_nights * ($cal_employee_price + $cal_room_price + $cal_desk_price + $cal_cubicles_price);

            // If GST is added then only apply GST
            if( $apply_gst == 'yes' ) {
                $gst = get_gst_data(0, 'office-space');
            }
        } else {
            $total_night_price = ($price_per_night) * ($number_of_nights) * $guests;

            $night_price = currencyConverterAPI($arr_property_rate['currency_code'],'INR',$arr_property_rate['price_per_night']);

            // If GST is added then only apply GST
            if( $apply_gst == 'yes' ) {
                $gst = get_gst_data($night_price, 'other');
            }
        }

        $total_price = $total_night_price;

        if(!empty($discount_type) && $discount_type != null && !empty($discount_rate) && $discount_rate != null) {
            if ($discount_type == 'percentage') {
                $discount_price = $total_price * ($discount_rate / 100);
                $total_price    = $total_price - $discount_price;
            } else if($discount_type == 'amount') {
                if ($arr_property_rate['currency_code'] != 'INR') {
                   $discount_rate  = currencyConverterAPI($arr_property_rate['currency_code'],'INR',$discount_rate);
                }
                $discount_price = $discount_rate;
                $total_price    = $total_price - $discount_rate;
            }
        }

        $gst_amount   = ($gst / 100) * $total_night_price;
        $total_amount = ($total_price + $gst_amount);

        $service_fee_data = get_service_fee($total_amount);

        $service_fee_gst_percentage = $service_fee_data['service_fee_gst_percentage'];
        $service_fee_percentage     = $service_fee_data['service_fee_percentage'];
        $service_fee_gst_amount     = $service_fee_data['service_fee_gst_amount'];
        $service_fee                = $service_fee_data['service_fee'];
        $total_amount               = $service_fee_gst_amount + $service_fee + $total_amount;
        
        $arr_property_rate['no_of_guest']                = $guests;

        $arr_property_rate['gst']                        = $gst;
        $arr_property_rate['gst_amount']                 = number_format($gst_amount, 2, '.', '');
        $arr_property_rate['service_fee']                = $service_fee;
        $arr_property_rate['service_fee_percentage']     = $service_fee_percentage;
        $arr_property_rate['service_fee_gst_percentage'] = $service_fee_gst_percentage;
        $arr_property_rate['service_fee_gst_amount']     = $service_fee_gst_amount;

        $arr_property_rate['property_type_slug']         = $property_type_slug;
        $arr_property_rate['number_of_nights']           = $number_of_nights;
        $arr_property_rate['price_per_night']            = number_format($price_per_night, 2, '.','');
        $arr_property_rate['number_of_guests']           = $guests;

        $arr_property_rate['price_per_sqft']             = round($price_per_sqft, 2);
        $arr_property_rate['available_no_of_slots']      = $available_no_of_slots;

        $arr_property_rate['available_no_of_employee']   = $available_no_of_employee;
        $arr_property_rate['available_no_of_room']       = $available_no_of_room;
        $arr_property_rate['available_no_of_desk']       = $available_no_of_desk;
        $arr_property_rate['available_no_of_cubicles']   = $available_no_of_cubicles;

        $arr_property_rate['room_price']                 = round($room_price, 2);
        $arr_property_rate['desk_price']                 = round($desk_price, 2);
        $arr_property_rate['cubicles_price']             = round($cubicles_price, 2);

        $arr_property_rate['currency_code']              = $user_currency;
        $arr_property_rate['discount_price']             = number_format($discount_price,2,'.','');
        $arr_property_rate['total_night_price']          = number_format($total_night_price,2,'.','');
        $arr_property_rate['total_service_amount']       = number_format($total_service_amount,2,'.','');
        $arr_property_rate['total_payble_amount']        = number_format($total_amount,2,'.','');

        return $arr_property_rate;
    }
}
