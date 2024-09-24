<?php
namespace App\Common\Services;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\PropertyUnavailabilityModel;
use App\Models\BookingModel;
use App\Models\PropertyModel;

class PropertyDatesService extends Controller
{	
    public function __construct() {  }

    /* This functions gives information about the different types of dates the property has unavailable.*/
    public function get_property_dates($property_id)
    {
        $property_type_slug = '';
        $arr_unavailable_dates = $arr_tmp_unavalable_dates = $arr_dates = [];

        $obj_unavailable_dates = PropertyUnavailabilityModel::where(['property_id' => $property_id])->get();
        if($obj_unavailable_dates) {
            $arr_tmp_unavalable_dates = $obj_unavailable_dates->toArray();

            if(count($arr_tmp_unavalable_dates) > 0 ) {
                foreach ($arr_tmp_unavalable_dates as $key => $date) {
                    if (isset($date['from_date']) &&  $date['from_date'] != "" && isset($date['to_date']) &&  $date['to_date'] != "")
                    {
                        $arr_dates = $this->make_dates_array($date['from_date'],$date['to_date']);
                        array_push($arr_unavailable_dates ,$arr_dates);
                    }
                }
            }
            $arr_unavailable_dates = array_flatten($arr_unavailable_dates);
        } 
        $arr_tmp['arr_unavailable_dates'] = $arr_unavailable_dates;
        return $arr_tmp;
    }

    public function make_dates_array($start_date,$end_date)
    {
        $dates = [];
      
        $dt_start_date  = new \DateTime($start_date);
        $dt_end_date    = new \DateTime($end_date);
        $dt_interval    = \DateInterval::createFromDateString(" 1 day ");
        $dt_period      = new \DatePeriod($dt_start_date, $dt_interval ,$dt_end_date);

        if(sizeof($dt_period)>0)
        {
            foreach ($dt_period as $key => $date) 
            {
                $tmp_date = $date->format('Y-m-d');
                $dates[]  = $tmp_date;
            }    
            array_push($dates,$dt_end_date->format('Y-m-d'));
        }

        return $dates;
    }

    public function check_unavaialable_dates($property_id,$arrival_date,$departure_date)
    {
        $count = PropertyUnavailabilityModel::where('property_id',$property_id)   
                        ->whereRaw("
                            ((DATE('".$arrival_date."') BETWEEN DATE(from_date) AND DATE(to_date)) OR
                            (DATE('".$departure_date."') BETWEEN DATE(from_date) AND DATE(to_date)) OR
                            (DATE(from_date) BETWEEN DATE('".$arrival_date."') AND DATE('".$departure_date."')) OR
                            (DATE(to_date) BETWEEN DATE('".$arrival_date."') AND DATE('".$departure_date."'))) 
                            ")
                        ->count();  
        $availability = TRUE;

        if ($count >= 1) {
           return FALSE;  
        }  
        return $availability;
    }

    public function check_property_is_booked($property_id,$checkin_date,$checkout_date)
    {
        $is_booked_flag = FALSE;
        $arr_dates      = [];
        $arr_dates      = [$checkin_date,$checkout_date];
        $is_booked      = BookingModel::where('property_id','=',$property_id)
                                      ->whereBetween('check_in_date',$arr_dates)
                                      ->whereBetween('check_out_date',$arr_dates)
                                      ->where(function($query){
                                            $query->where('booking_status','=',1);
                                            $query->orWhere('booking_status','=',2);
                                            //$query->orWhere('booking_status','=',3);
                                            $query->orWhere('booking_status','=',5);
                                        })
                                      ->count();
        if ($is_booked > 0) {
            $is_booked_flag = TRUE;
        }
        return $is_booked_flag;
    }

    public function remove_unavaialable_dates($property_id,$arrival_date,$departure_date)
    {
        $status = FALSE;
        $count = PropertyUnavailabilityModel::where('property_id',$property_id)   
                        ->whereRaw("
                            ((DATE('".$arrival_date."') BETWEEN DATE(from_date) AND DATE(to_date)) OR
                            (DATE('".$departure_date."') BETWEEN DATE(from_date) AND DATE(to_date)) OR
                            (DATE(from_date) BETWEEN DATE('".$arrival_date."') AND DATE('".$departure_date."')) OR
                            (DATE(to_date) BETWEEN DATE('".$arrival_date."') AND DATE('".$departure_date."'))) 
                            ")
                        ->count();
        if ($count > 0) {
            $status = PropertyUnavailabilityModel::where('property_id',$property_id)
                        ->whereRaw("
                            ((DATE('".$arrival_date."') BETWEEN DATE(from_date) AND DATE(to_date)) OR
                            (DATE('".$departure_date."') BETWEEN DATE(from_date) AND DATE(to_date)) OR
                            (DATE(from_date) BETWEEN DATE('".$arrival_date."') AND DATE('".$departure_date."')) OR
                            (DATE(to_date) BETWEEN DATE('".$arrival_date."') AND DATE('".$departure_date."'))) 
                            ")
                        ->delete();
        }
        return $status;
    }
} ?>