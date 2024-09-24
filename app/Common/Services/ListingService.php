<?php

namespace App\Common\Services;
use Illuminate\Http\Request;
use DB;

use App\Models\PropertyModel;
use App\Models\PropertytypeModel;
use App\Models\CategoryModel;
use App\Models\AmenitiesModel;
use App\Models\PropertyImagesModel;
use App\Models\PropertyAminitiesModel;
use App\Models\PropertyRulesModel;
use App\Models\PropertyUnavailabilityModel;
use App\Models\FavouritePropertyModel;
use App\Models\PropertyBedsArrangmentModel;
use App\Models\SleepingArrangementModel;
use App\Models\ReviewRatingModel;
use App\Models\BookingModel;
use App\Models\UserModel;


class ListingService
{
	function __construct(
							PropertyModel               $property_model,
							PropertytypeModel           $property_type_model,
							AmenitiesModel              $aminities_model,
							PropertyRulesModel          $property_rules_model,
							PropertyImagesModel         $property_images_model,
							PropertyAminitiesModel      $property_aminities_model,
							FavouritePropertyModel      $favourite_property_model,
							PropertyUnavailabilityModel $property_unavailability_model,
							PropertyBedsArrangmentModel $property_beds_arrangment_model,
							SleepingArrangementModel 	$sleeping_arrangement_model,
							ReviewRatingModel 	        $review_rating_model,
							BookingModel                $booking_model,
							UserModel                   $user_model
						)
	{
		$this->PropertyModel               =  $property_model;
		$this->PropertytypeModel           =  $property_type_model;
		$this->AminitiesModel              =  $aminities_model;
		$this->PropertyRulesModel          =  $property_rules_model;
		$this->PropertyImagesModel         =  $property_images_model;
		$this->PropertyAminitiesModel      =  $property_aminities_model;
		$this->PropertyUnavailabilityModel =  $property_unavailability_model;
		$this->FavouritePropertyModel      =  $favourite_property_model;
		$this->PropertyBedsArrangmentModel =  $property_beds_arrangment_model;
		$this->SleepingArrangementModel    =  $sleeping_arrangement_model;
		$this->ReviewRatingModel           =  $review_rating_model;
		$this->BookingModel                =  $booking_model;
		$this->UserModel                   =  $user_model; 

		$this->property_table                  = $this->PropertyModel->getTable();
		$this->property_type_table             = $this->PropertytypeModel->getTable();
		$this->aminities_table                 = $this->AminitiesModel->getTable();
		$this->property_rules_table            = $this->PropertyRulesModel->getTable();
		$this->property_image_table     	   = $this->PropertyImagesModel->getTable();
		$this->property_aminities_table 	   = $this->PropertyAminitiesModel->getTable();
		$this->property_unavailability_table   = $this->PropertyUnavailabilityModel->getTable();
		$this->favourite_property_table        = $this->FavouritePropertyModel->getTable();
		$this->property_beds_arrangment_table  = $this->PropertyBedsArrangmentModel->getTable(); 
		$this->sleeping_arrangement_table      = $this->SleepingArrangementModel->getTable();
		$this->review_rating_table             = $this->ReviewRatingModel->getTable();
		$this->booking_table                   = $this->BookingModel->getTable();
		$this->user_table                      = $this->UserModel->getTable();


		$this->prefixed_property_table                 = DB::getTablePrefix().$this->PropertyModel->getTable();
		$this->prefixed_aminities_table                = DB::getTablePrefix().$this->AminitiesModel->getTable();
		$this->prefixed_property_rules_table           = DB::getTablePrefix().$this->PropertyRulesModel->getTable();
		$this->prefixed_property_image_table           = DB::getTablePrefix().$this->PropertyImagesModel->getTable();
		$this->prefixed_property_aminities_table       = DB::getTablePrefix().$this->PropertyAminitiesModel->getTable();
		$this->prefixed_favourite_property_table       = DB::getTablePrefix().$this->FavouritePropertyModel->getTable();
		$this->prefixed_property_unavailability_table  = DB::getTablePrefix().$this->PropertyUnavailabilityModel->getTable();
		$this->prefixed_property_beds_arrangment_table = DB::getTablePrefix().$this->PropertyBedsArrangmentModel->getTable(); 
		$this->prefixed_sleeping_arrangement_table     = DB::getTablePrefix().$this->SleepingArrangementModel->getTable();
		$this->prefixed_review_rating_table            = DB::getTablePrefix().$this->ReviewRatingModel->getTable();
		$this->prefixed_booking_table                  = DB::getTablePrefix().$this->BookingModel->getTable();
		$this->prefixed_user_table                     = DB::getTablePrefix().$this->UserModel->getTable();
		$this->prefixed_property_type_table            = DB::getTablePrefix().$this->PropertytypeModel->getTable();

		$this->property_image_public_path  = url('/').config('app.project.img_path.property_image');
		$this->property_image_base_path    = base_path().config('app.project.img_path.property_image');

		DB::connection()->enableQueryLog();

	}

	public function get_property_listing(
											$property_type           = null,
											$checkin                 = null,
											$checkout                = null,
											$location                = null,
											$guests                  = null,
											$city                    = null,
											$state                   = null,
											$country                 = null,
											$postal_code             = null,
											$latitude                = null,
											$longitude               = null,
											$price_max               = null,
											$price_min               = null,
											$min_bedrooms            = null,
											$min_bathrooms           = null,
											$room_category           = null,
											$reviews                 = null,
											$amenities               = null,
											$keyword                 = null,
											$user_id                 = null,
											$property_working_status = null,
											$price_per 	             = null,
											$no_of_employee          = null,
											$build_type              = null,
											$available_area          = null,
											$featured                = null
										)
	{
		$arr_property = [];
		$data         = [];
		//dd($property_working_status,$build_type);
		$cnt_property_city = $cnt_property_state = $cnt_property_location = 0;
		
		$obj_property = DB::table($this->property_table)->select(DB::raw( 
			$this->prefixed_review_rating_table.".id as review_id,avg(".
			$this->prefixed_review_rating_table.".rating) as avg_rating,".
			$this->prefixed_review_rating_table.".booking_id as booking_id,".
			$this->prefixed_property_table.".id as id,".
			$this->prefixed_property_table.".property_name_slug as property_name_slug,".

			$this->prefixed_property_table.".currency as currency,".
			$this->prefixed_property_table.".user_id as user_id,".
			$this->prefixed_property_table.".property_name as property_name,".
			$this->prefixed_property_table.".description as description,".
			$this->prefixed_property_table.".address as address,".
			$this->prefixed_property_table.".city as city,".
			$this->prefixed_property_table.".state as state,".
			$this->prefixed_property_table.".country as country,".
			$this->prefixed_property_table.".currency as currency,".
			$this->prefixed_property_table.".currency_code as currency_code,".
			$this->prefixed_property_table.".postal_code as postal_code,".
			$this->prefixed_property_table.".property_latitude as property_latitude,".
			$this->prefixed_property_table.".property_longitude as property_longitude,".
			$this->prefixed_property_table.".number_of_guest as number_of_guest,".
			$this->prefixed_property_table.".number_of_bedrooms as number_of_bedrooms,".
			$this->prefixed_property_table.".number_of_bathrooms as number_of_bathrooms,".
			$this->prefixed_property_table.".number_of_beds as number_of_beds,".
			$this->prefixed_property_table.".property_type_id as property_type_id,".
			$this->prefixed_property_table.".price_per_night as price_per_night,".
			$this->prefixed_property_table.".service_charge as service_charge,".
			$this->prefixed_property_table.".admin_status as admin_status,".
			$this->prefixed_property_table.".total_plot_area as total_plot_area,".
			$this->prefixed_property_table.".total_build_area as total_build_area,".
			$this->prefixed_property_table.".admin_area as admin_area,".
			$this->prefixed_property_table.".property_area as property_area,".
			$this->prefixed_property_table.".price_per_sqft as price_per_sqft,".
			$this->prefixed_property_table.".price_per as price_per,".
			$this->prefixed_property_table.".price_per_office as price_per_office,".
			$this->prefixed_property_table.".property_working_status as property_working_status,".
			$this->prefixed_property_table.".no_of_slots as no_of_slots,".
			$this->prefixed_property_table.".available_no_of_slots as available_no_of_slots,".
			$this->prefixed_property_table.".available_no_of_employee as available_no_of_employee,".
			$this->prefixed_property_table.".employee as employee,".
			$this->prefixed_property_table.".room as room,".
			$this->prefixed_property_table.".desk as desk,".
			$this->prefixed_property_table.".cubicles as cubicles,".
			$this->prefixed_property_table.".no_of_employee as no_of_employee,".
			$this->prefixed_property_table.".no_of_room as no_of_room,".
			$this->prefixed_property_table.".no_of_desk as no_of_desk,".
			$this->prefixed_property_table.".no_of_cubicles as no_of_cubicles,".
			$this->prefixed_property_table.".room_price as room_price,".
			$this->prefixed_property_table.".desk_price as desk_price,".
			$this->prefixed_property_table.".cubicles_price as cubicles_price,".

			$this->prefixed_property_type_table.".name as property_type_name,".

			DB::raw('(select distinct '.$this->prefixed_property_image_table.".image from ".$this->prefixed_property_image_table).' where '.$this->prefixed_property_image_table.'.property_id = '.$this->prefixed_property_table.'.id order by id asc limit 1) as property_image'
			));

		$obj_property = $obj_property->Leftjoin($this->prefixed_property_unavailability_table,$this->property_table.".id",' = ',$this->prefixed_property_unavailability_table.'.property_id');

		$obj_property = $obj_property->Leftjoin($this->prefixed_review_rating_table,$this->prefixed_property_table.".id",' = ',$this->prefixed_review_rating_table.'.property_id');

		$obj_property = $obj_property->Leftjoin($this->prefixed_property_aminities_table,$this->prefixed_property_table.".id",' = ',$this->prefixed_property_aminities_table.'.property_id');

		$obj_property = $obj_property->Leftjoin($this->prefixed_property_type_table,$this->prefixed_property_table.".property_type_id",' = ',$this->prefixed_property_type_table.'.id');
		
		if ($user_id != null) {
			$obj_property = $obj_property->where([
				$this->prefixed_property_table.'.user_id'=>$user_id, 
				$this->prefixed_property_table.'.property_status'=>'1']);
		} else {
			$obj_property = $obj_property->where([
				$this->prefixed_property_table.'.admin_status'=>'2', 
				$this->prefixed_property_table.'.property_status'=>'1']);
		}

		if(isset($city) && $city != '')
		{
			$obj_property = $obj_property->whereRaw($this->prefixed_property_table.".city LIKE '".$city."%'");
		}
		elseif(isset($state) && $state != '')
		{ 
			$obj_property = $obj_property->whereRaw($this->prefixed_property_table.".state LIKE '".$state."%'");
		}
		elseif(isset($country) && $country != "")
		{
			$obj_property = $obj_property->whereRaw($this->prefixed_property_table.".country LIKE '".$country."%'");
		}
		elseif(isset($location) && $location != "")
		{
			$obj_property = $obj_property->whereRaw($this->prefixed_property_table.".address LIKE '".$location."%'");
		}

		if(isset($featured) && $featured == 'yes'){
			$obj_property = $obj_property->where('is_featured','yes');
		}
		/*Change by KAvita*/
		if($property_type != null && $property_type != '')
		{
			$obj_property = $obj_property->whereRaw('('.$this->prefixed_property_type_table.'.name LIKE "%'.$property_type.'%")');
		}
		if ($property_working_status != null && $property_working_status != '')
		{
			$obj_property = $obj_property->whereRaw('('.$this->prefixed_property_table.'.property_working_status LIKE "%'.$property_working_status.'%")');
		}
		if ($price_per != null && $price_per != '')
		{
			$obj_property = $obj_property->whereRaw('('.$this->prefixed_property_table.'.price_per LIKE "%'.$price_per.'%")');
		}
		if ($no_of_employee != null && $no_of_employee != '')
		{
			$obj_property = $obj_property->whereRaw('('.$this->prefixed_property_table.'.no_of_employee LIKE "%'.$no_of_employee.'%")');
		}
		if ($build_type != null && $build_type != '')
		{
			$obj_property = $obj_property->whereRaw('('.$this->prefixed_property_table.'.build_type LIKE "%'.$build_type.'%")');
		}
		if ($available_area != null && $available_area != '')
		{
			$obj_property = $obj_property->whereRaw('('.$this->prefixed_property_table.'.property_area LIKE "%'.$available_area.'%")');
		}
		if (str_slug($property_type,'-') == 'warehouse') 
		{
			if ($price_min != null) {
		      		$obj_property = $obj_property->where($this->prefixed_property_table.'.price_per_sqft','>=', $price_min);            
	    	}
	    	if($price_max != null) {
	      		$obj_property = $obj_property->where($this->prefixed_property_table.'.price_per_sqft','<=', $price_max);            
	    	}
			if ($price_max != null && $price_min != null) {
	      		$obj_property = $obj_property->whereBetween($this->prefixed_property_table.'.price_per_sqft', array($price_min, $price_max));
	    	}
		}
		else if (str_slug($property_type,'-') == 'office-space') 
		{
			if ($price_min != null) {
		      	$obj_property = $obj_property->where($this->prefixed_property_table.'.price_per_office','>=', $price_min);
	    	}
	    	if($price_max != null) {
	      		$obj_property = $obj_property->where($this->prefixed_property_table.'.price_per_office','<=', $price_max);
	    	}
			if ($price_max != null && $price_min != null) {
	      		$obj_property = $obj_property->whereBetween($this->prefixed_property_table.'.price_per_office', array($price_min, $price_max));
	    	}
		}
		else
		{
			if ($price_min != null) {
		      	$obj_property = $obj_property->where($this->prefixed_property_table.'.price_per_night','>=', $price_min);
	    	}
	    	if($price_max != null) {
	      		$obj_property = $obj_property->where($this->prefixed_property_table.'.price_per_night','<=', $price_max);
	    	}
			
			if ($price_max != null && $price_min != null) {
	      		$obj_property = $obj_property->whereBetween($this->prefixed_property_table.'.price_per_night', array($price_min, $price_max));
	    	}
		}
		/*End*/
		if ($guests != null) {
			$obj_property = $obj_property->where($this->prefixed_property_table.'.number_of_guest','>=', $guests);
		}

		if ($checkin != null && $checkout != null) {
			$checkin  = date('Y-m-d' ,strtotime($checkin));
			$checkout = date('Y-m-d' ,strtotime($checkout));

			$arr_unavilable_property = $this->get_unavailble_property_id($checkin, $checkout);
			$obj_property = $obj_property->whereNotIn($this->prefixed_property_table.".id", $arr_unavilable_property);
		}		
    	if ($min_bedrooms!=null) {
    		$obj_property = $obj_property->where($this->prefixed_property_table.'.number_of_bedrooms','>=', $min_bedrooms);      
    		$obj_property = $obj_property->orderBy($this->prefixed_property_table.'.number_of_bedrooms', 'asc');      
    	}
		
		if ($min_bathrooms != null) {
			$obj_property = $obj_property->where($this->prefixed_property_table.'.number_of_bathrooms','>=', $min_bathrooms);            
			$obj_property = $obj_property->orderBy($this->prefixed_property_table.'.number_of_bathrooms', 'asc');
		}

		if ($room_category != null) {
			$obj_property = $obj_property->whereIn($this->prefixed_property_table.'.property_type_id',$room_category);            
		}

		if ($amenities != null) {
			$obj_property = $obj_property->whereIn($this->prefixed_property_aminities_table.'.aminities_id',$amenities);            
		}

		if ($keyword != null) {
			$obj_property = $obj_property->whereRaw("(".$this->prefixed_property_table.".property_name LIKE '%".$keyword."%' )");            
		}
		 
		if ($reviews != null) 
		{
			$obj_property = $obj_property->orderBy("rating",$reviews);
		}
		else
		{
			$obj_property           = $obj_property->orderBy($this->prefixed_property_table.'.id', 'desc');
		}

		if ($obj_property) {

			$obj_property               = $obj_property->groupBy($this->prefixed_property_table.'.id');
			
			// For Google Maps
			$obj_property_maps          = $obj_property->get();
			$data['property_list_maps'] = isset($obj_property_maps) ? json_decode(json_encode($obj_property_maps),true) : array();

			$obj_property               = $obj_property->paginate(5);
			$arr_pagination             = clone $obj_property;
			$arr_property               = json_decode(json_encode($obj_property),true);

			$data['property_list']      = isset($arr_property['data'])? $arr_property['data']:array();
			$data['arr_pagination']     = $arr_pagination;
		}

		return $data;
	}


	public function get_property_listing_api(
												$property_type           = null,
												$checkin                 = null,
												$checkout                = null,
												$location                = null,
												$guests                  = null,
												$city                    = null,
												$state                   = null,
												$country                 = null,
												$postal_code             = null,
												$latitude                = null,
												$longitude               = null,
												$price_max               = null,
												$price_min               = null,
												$min_bedrooms            = null,
												$min_bathrooms           = null,
												$room_category           = null,
												$reviews                 = null,
												$amenities               = null,
												$keyword                 = null,
												$user_id                 = null,
												$property_working_status = null,
												$price_per 	             = null,
												$no_of_employee          = null,
												$build_type              = null,
												$available_area          = null
											)

	{
		$arr_property          = [];
		$data                  = [];
		$arr_property_location = [];
		$arr_property_city     = [];
		$arr_property_state    = [];

		$obj_property = DB::table($this->property_table)->select(DB::raw( 
			$this->prefixed_review_rating_table.".id as review_id,avg(".
			$this->prefixed_review_rating_table.".rating) as avg_rating,".
			$this->prefixed_review_rating_table.".booking_id as booking_id,".
			$this->prefixed_property_table.".id as id,".
			$this->prefixed_property_table.".property_name_slug as property_name_slug,".

			$this->prefixed_property_table.".currency as currency,".
			$this->prefixed_property_table.".user_id as user_id,".
			$this->prefixed_property_table.".property_name as property_name,".
			$this->prefixed_property_table.".description as description,".
			$this->prefixed_property_table.".address as address,".
			$this->prefixed_property_table.".city as city,".
			$this->prefixed_property_table.".state as state,".
			$this->prefixed_property_table.".country as country,".
			$this->prefixed_property_table.".currency as currency,".
			$this->prefixed_property_table.".currency_code as currency_code,".
			$this->prefixed_property_table.".postal_code as postal_code,".
			$this->prefixed_property_table.".property_latitude as property_latitude,".
			$this->prefixed_property_table.".property_longitude as property_longitude,".
			$this->prefixed_property_table.".number_of_guest as number_of_guest,".
			$this->prefixed_property_table.".number_of_bedrooms as number_of_bedrooms,".
			$this->prefixed_property_table.".number_of_bathrooms as number_of_bathrooms,".
			$this->prefixed_property_table.".number_of_beds as number_of_beds,".
			$this->prefixed_property_table.".property_type_id as property_type_id,".
			$this->prefixed_property_table.".price_per_night as price_per_night,".
			$this->prefixed_property_table.".service_charge as service_charge,".
			$this->prefixed_property_table.".admin_status as admin_status,".
			$this->prefixed_property_table.".total_plot_area as total_plot_area,".
			$this->prefixed_property_table.".total_build_area as total_build_area,".
			$this->prefixed_property_table.".admin_area as admin_area,".
			$this->prefixed_property_table.".property_area as property_area,".
			$this->prefixed_property_table.".price_per_sqft as price_per_sqft,".
			$this->prefixed_property_table.".price_per as price_per,".
			$this->prefixed_property_table.".price_per_office as price_per_office,".
			$this->prefixed_property_table.".property_working_status as property_working_status,".
			$this->prefixed_property_table.".no_of_slots as no_of_slots,".
			$this->prefixed_property_table.".available_no_of_slots as available_no_of_slots,".
			$this->prefixed_property_table.".available_no_of_employee as available_no_of_employee,".

			$this->prefixed_property_table.".employee as employee,".
			$this->prefixed_property_table.".room as room,".
			$this->prefixed_property_table.".desk as desk,".
			$this->prefixed_property_table.".cubicles as cubicles,".
			$this->prefixed_property_table.".no_of_employee as no_of_employee,".
			$this->prefixed_property_table.".no_of_room as no_of_room,".
			$this->prefixed_property_table.".no_of_desk as no_of_desk,".
			$this->prefixed_property_table.".no_of_cubicles as no_of_cubicles,".
			$this->prefixed_property_table.".room_price as room_price,".
			$this->prefixed_property_table.".desk_price as desk_price,".
			$this->prefixed_property_table.".cubicles_price as cubicles_price,".

			DB::raw('(select distinct '.$this->prefixed_property_image_table.".image from ".$this->prefixed_property_image_table).' where '.$this->prefixed_property_image_table.'.property_id = '.$this->prefixed_property_table.'.id order by id asc limit 1) as property_image'
			));

		$obj_property = $obj_property->Leftjoin($this->prefixed_property_unavailability_table,$this->property_table.".id",' = ',$this->prefixed_property_unavailability_table.'.property_id');

		$obj_property = $obj_property->Leftjoin($this->prefixed_review_rating_table,$this->prefixed_property_table.".id",' = ',$this->prefixed_review_rating_table.'.property_id');

		$obj_property = $obj_property->Leftjoin($this->prefixed_property_aminities_table,$this->prefixed_property_table.".id",' = ',$this->prefixed_property_aminities_table.'.property_id');

		$obj_property = $obj_property->Leftjoin($this->prefixed_property_type_table,$this->prefixed_property_table.".property_type_id",' = ',$this->prefixed_property_type_table.'.id');

		if($user_id != null) {
			$obj_property = $obj_property->where([
				$this->prefixed_property_table.'.user_id'=>$user_id, 
				$this->prefixed_property_table.'.property_status'=>'1']);
		} else {
			$obj_property = $obj_property->where([
				$this->prefixed_property_table.'.admin_status'=>'2', 
				$this->prefixed_property_table.'.property_status'=>'1']);			
		}
		

		/*if(isset($city) && $city != '') 
		{
			$obj_property = $obj_property->whereRaw($this->prefixed_property_table.".city LIKE '%".$city."%'");
		}
		elseif(isset($state) && $state != '') 
		{
			$obj_property = $obj_property->whereRaw($this->prefixed_property_table.".state LIKE '".$state."%'");
		}
		elseif(isset($country) && $country!="")
		{
			$obj_property = $obj_property->whereRaw($this->prefixed_property_table.".country LIKE '".$country."%'");
		}
		elseif(isset($location) && $location!="")
		{
			$obj_property = $obj_property->whereRaw($this->prefixed_property_table.".address LIKE '".$location."%'");
		}*/

		if ($location != null  && $location != '') {
			$obj_property_location = clone $obj_property;
			$table = $this->prefixed_property_table;
			$arr_property_location = $obj_property_location->whereRaw('('.$table.'.address LIKE "%'.$location.'%")')->first();
			
			if (count($obj_property_location) > 0) {
				$obj_property = $obj_property->whereRaw('('.$table.'.address LIKE "%'.$location.'%")');
			}
		}

		if (count($arr_property_location) == 0 && $city != null && $city != '') {
			$obj_property_city = clone $obj_property;
			$arr_property_city = $obj_property_city->where($this->prefixed_property_table.'.city',$city)->first();
			
			if (count($obj_property_city) > 0) {
				$obj_property = $obj_property->where([$this->prefixed_property_table.'.city'=>$city]);
			}
		}
		 
		if (count($arr_property_location) == 0 && count($arr_property_city) == 0 && $state != null && $state != '') { 
			$obj_property_state = clone $obj_property;
			$arr_property_state = $obj_property_state->where($this->prefixed_property_table.'.state',$state)->first();
			if (count($obj_property_state) > 0) {
				$obj_property = $obj_property->where([$this->prefixed_property_table.'.state' => $state]);
			}
		}

		if (($location != null && $location != '') && count($arr_property_location) == 0 && count($arr_property_city) == 0 && count($arr_property_state) == 0) {
			$obj_property = $obj_property->whereRaw('('.$table.'.address LIKE "%'.$location.'%")');
		}



		/*Change by Kavita*/
		if ($property_type != null && $property_type != '')
		{
			$obj_property = $obj_property->whereRaw('('.$this->prefixed_property_type_table.'.name LIKE "%'.$property_type.'%")');
		}
		if ($property_working_status != null && $property_working_status != '')
		{
			$obj_property = $obj_property->whereRaw('('.$this->prefixed_property_table.'.property_working_status LIKE "%'.$property_working_status.'%")');
		}
		if ($price_per != null && $price_per != '')
		{
			$obj_property = $obj_property->whereRaw('('.$this->prefixed_property_table.'.price_per LIKE "%'.$price_per.'%")');
		}
		if ($no_of_employee != null && $no_of_employee != '')
		{
			$obj_property = $obj_property->whereRaw('('.$this->prefixed_property_table.'.no_of_employee LIKE "%'.$no_of_employee.'%")');
		}
		if ($build_type != null && $build_type != '')
		{
			$obj_property = $obj_property->whereRaw('('.$this->prefixed_property_table.'.build_type LIKE "%'.$build_type.'%")');
		}
		if ($available_area != null && $available_area != '')
		{
			$obj_property = $obj_property->whereRaw('('.$this->prefixed_property_table.'.property_area LIKE "%'.$available_area.'%")');
		}
		if (str_slug($property_type,'-') == 'warehouse') 
		{
			if ($price_min != null) {
		      		$obj_property = $obj_property->where($this->prefixed_property_table.'.price_per_sqft','>=', $price_min);            
	    	}
	    	if($price_max != null) {
	      		$obj_property = $obj_property->where($this->prefixed_property_table.'.price_per_sqft','<=', $price_max);            
	    	}
			if ($price_max != null && $price_min != null) {
	      		$obj_property = $obj_property->whereBetween($this->prefixed_property_table.'.price_per_sqft', array($price_min, $price_max));
	    	}
		}
		else if (str_slug($property_type,'-') == 'office-space') 
		{
			if ($price_min != null) {
		      		$obj_property = $obj_property->where($this->prefixed_property_table.'.price_per_office','>=', $price_min);            
	    	}
	    	if($price_max != null) {
	      		$obj_property = $obj_property->where($this->prefixed_property_table.'.price_per_office','<=', $price_max);            
	    	}
			if ($price_max != null && $price_min != null) {
	      		$obj_property = $obj_property->whereBetween($this->prefixed_property_table.'.price_per_office', array($price_min, $price_max));
	    	}
		}
		else
		{
			if ($price_min != null) {
		      		$obj_property = $obj_property->where($this->prefixed_property_table.'.price_per_night','>=', $price_min);            
	    	}
	    	if($price_max != null) {
	      		$obj_property = $obj_property->where($this->prefixed_property_table.'.price_per_night','<=', $price_max);            
	    	}
			
			if ($price_max != null && $price_min != null) {
	      		$obj_property = $obj_property->whereBetween($this->prefixed_property_table.'.price_per_night', array($price_min, $price_max));
	    	}
		}
		/*End*/

		if ($guests != null) {
			$obj_property = $obj_property->where($this->prefixed_property_table.'.number_of_guest','>=', $guests);
		}

		if ($checkin != null && $checkout != null) {
			$checkin  = date('Y-m-d' ,strtotime($checkin));
			$checkout = date('Y-m-d' ,strtotime($checkout));

			$arr_unavilable_property = $this->get_unavailble_property_id($checkin, $checkout);
			$obj_property = $obj_property->whereNotIn($this->prefixed_property_table.".id", $arr_unavilable_property);
		}		
		
		if ($price_min != null) {
      		$obj_property = $obj_property->where($this->prefixed_property_table.'.price_per_night','>=', $price_min);            
    	}
    	
    	if ($price_max != null) {
      		$obj_property = $obj_property->where($this->prefixed_property_table.'.price_per_night','<=', $price_max);            
    	}
		
		if ($price_max != null && $price_min != null) {
      		$obj_property = $obj_property->whereBetween($this->prefixed_property_table.'.price_per_night', array($price_min, $price_max));
    	}
    	
    	if ($min_bedrooms != null) {
    		$obj_property = $obj_property->where($this->prefixed_property_table.'.number_of_bedrooms','>=', $min_bedrooms);      
    		$obj_property = $obj_property->orderBy($this->prefixed_property_table.'.number_of_bedrooms', 'asc');      
    	}
		
		if ($min_bathrooms != null) {
			$obj_property = $obj_property->where($this->prefixed_property_table.'.number_of_bathrooms','>=', $min_bathrooms);            
			$obj_property = $obj_property->orderBy($this->prefixed_property_table.'.number_of_bathrooms', 'asc');
		}

		if ($room_category != null) {
			$obj_property = $obj_property->whereIn($this->prefixed_property_table.'.property_type_id',$room_category);            
		}

		if ($amenities != null) {
			$obj_property = $obj_property->whereIn($this->prefixed_property_aminities_table.'.aminities_id',$amenities);            
		}

		if ($keyword != null) {
			$obj_property = $obj_property->whereRaw("(".$this->prefixed_property_table.".property_name LIKE '%".$keyword."%' )");            
		}
		 
		if ($reviews != null) {
			$obj_property = $obj_property->orderBy("rating",$reviews);
		}

		if ($obj_property) {
			$obj_property           = $obj_property->groupBy($this->prefixed_property_table.'.id');
			$obj_property           = $obj_property->orderBy($this->prefixed_property_table.'.id', 'desc');
			
			$obj_property           = $obj_property;			
			$arr_pagination         = clone $obj_property;
			$arr_property           = $obj_property->get(); // json_decode(json_encode($obj_property),true);

			$data['property_list']  = isset($arr_property) ? $arr_property : array();
			$data['arr_pagination'] = $arr_pagination;
		}
		
		return $data;
	}

	public function get_review_property_listing($user_id = null)
	{
		$arr_property = [];
		$data         = [];

		$obj_review_rating = DB::table($this->prefixed_review_rating_table)

		->select(DB::raw(

			$this->prefixed_review_rating_table.".id as id,".
			$this->prefixed_review_rating_table.".rating as rating,".
			$this->prefixed_review_rating_table.".message as message,".
			$this->prefixed_review_rating_table.".created_at as created_at,".
			$this->prefixed_user_table.".first_name as first_name,".
			$this->prefixed_user_table.".last_name as last_name,".
			$this->prefixed_property_table.".id as property_id,".
			$this->prefixed_property_table.".user_id as user_id,".
			$this->prefixed_property_table.".property_name as property_name,".
			$this->prefixed_property_table.".description as description,".
			$this->prefixed_property_table.".address as address,".
			$this->prefixed_property_table.".city as city,".
			$this->prefixed_property_table.".state as state,".
			$this->prefixed_property_table.".country as country,".
			$this->prefixed_property_table.".currency as currency,".
			$this->prefixed_property_table.".postal_code as postal_code,".
			$this->prefixed_property_table.".property_latitude as property_latitude,".
			$this->prefixed_property_table.".property_longitude as property_longitude,".
			$this->prefixed_property_table.".number_of_guest as number_of_guest,".
			$this->prefixed_property_table.".number_of_bedrooms as number_of_bedrooms,".
			$this->prefixed_property_table.".number_of_bathrooms as number_of_bathrooms,".
			$this->prefixed_property_table.".number_of_beds as number_of_beds,".
			$this->prefixed_property_table.".price_per_night as price_per_night,".
			$this->prefixed_property_table.".service_charge as service_charge,".
			$this->prefixed_property_table.".admin_status as admin_status,".
			$this->prefixed_property_table.".property_name_slug as property_name_slug,".

			DB::raw('(select distinct '.$this->prefixed_property_image_table.".image from ".$this->prefixed_property_image_table).' where '.$this->prefixed_property_image_table.'.property_id = '.$this->prefixed_property_table.'.id order by id asc limit 1) as property_image'
			));

	    	$obj_review_rating = $obj_review_rating->Join($this->prefixed_property_table,$this->property_table.".id",' = ',$this->prefixed_review_rating_table.'.property_id');

	    	$obj_review_rating = $obj_review_rating->Join($this->prefixed_user_table,$this->user_table.".id",' = ',$this->prefixed_review_rating_table.'.rating_user_id');

	    	if($user_id != null)
			{
				$obj_review_rating = $obj_review_rating->where([$this->prefixed_review_rating_table.'.rating_user_id' => $user_id]);
			}
			
		if($obj_review_rating)
		{
			$obj_review_rating      = $obj_review_rating->paginate(5);
			$arr_pagination         = clone $obj_review_rating;
			$arr_property           = json_decode(json_encode($obj_review_rating),true);
			$data['booking_list']   = isset($arr_property['data'])? $arr_property['data']:array();
			$data['arr_pagination'] = $arr_pagination;
		}

		return $data;
	}	

	public function get_property_details($id=null)
	{
		$arr_property = [];		
		$obj_property = DB::table($this->property_table)
							->select(DB::raw(
									$this->prefixed_property_table.".id as id,".
									$this->prefixed_property_table.".property_name_slug as property_name_slug,".
									$this->prefixed_property_table.".property_type_id as property_type_id,".
									$this->prefixed_property_table.".property_name as property_name,".
									$this->prefixed_property_table.".description as description,".
									$this->prefixed_property_table.".address as address,".
									$this->prefixed_property_table.".city as city,".
									$this->prefixed_property_table.".state as state,".
									$this->prefixed_property_table.".user_id as user_id,".			
									$this->prefixed_property_table.".currency as currency,".
									$this->prefixed_property_table.".currency_code as currency_code,".
									$this->prefixed_property_table.".country as country,".
									$this->prefixed_property_table.".postal_code as postal_code,".
									$this->prefixed_property_table.".property_latitude as property_latitude,".
									$this->prefixed_property_table.".property_longitude as property_longitude,".
									$this->prefixed_property_table.".number_of_guest as number_of_guest,".
									$this->prefixed_property_table.".number_of_bedrooms as number_of_bedrooms,".
									$this->prefixed_property_table.".number_of_bathrooms as number_of_bathrooms,".
									$this->prefixed_property_table.".admin_status as admin_status,".
									//change by kavita	
									$this->prefixed_property_table.".property_working_status as property_working_status,".
									$this->prefixed_property_table.".build_type as build_type,".
									$this->prefixed_property_table.".property_area as property_area,".
									$this->prefixed_property_table.".total_plot_area as total_plot_area,".
									$this->prefixed_property_table.".total_build_area as total_build_area,".
									$this->prefixed_property_table.".custom_type as custom_type,".
									$this->prefixed_property_table.".management as management,".
									$this->prefixed_property_table.".good_storage as good_storage,".
									$this->prefixed_property_table.".admin_area as admin_area,".
									$this->prefixed_property_table.".price_per_sqft as price_per_sqft,".
									$this->prefixed_property_table.".price_per as price_per,".
									$this->prefixed_property_table.".price_per_office as price_per_office,".
									$this->prefixed_property_table.".nearest_railway_station as nearest_railway_station,".
									$this->prefixed_property_table.".nearest_national_highway as nearest_national_highway,".
									$this->prefixed_property_table.".nearest_bus_stop as nearest_bus_stop,".
									$this->prefixed_property_table.".working_hours as working_hours,".
									$this->prefixed_property_table.".working_days as working_days,".
									$this->prefixed_property_table.".no_of_slots as no_of_slots,".

									$this->prefixed_property_table.".employee as employee,".
									$this->prefixed_property_table.".room as room,".
									$this->prefixed_property_table.".desk as desk,".
									$this->prefixed_property_table.".cubicles as cubicles,".

									$this->prefixed_property_table.".no_of_employee as no_of_employee,".
									$this->prefixed_property_table.".no_of_room as no_of_room,".
									$this->prefixed_property_table.".no_of_desk as no_of_desk,".
									$this->prefixed_property_table.".no_of_cubicles as no_of_cubicles,".

									$this->prefixed_property_table.".room_price as room_price,".
									$this->prefixed_property_table.".desk_price as desk_price,".
									$this->prefixed_property_table.".cubicles_price as cubicles_price,".
									
									$this->prefixed_property_table.".property_remark as property_remark,".
									$this->prefixed_property_table.".available_no_of_slots as available_no_of_slots,".
									$this->prefixed_property_table.".available_no_of_employee as available_no_of_employee,".
									//change by kavita
									$this->prefixed_property_table.".number_of_beds as number_of_beds,".
									$this->prefixed_property_table.".price_per_night as price_per_night,".
									$this->prefixed_property_table.".service_charge as service_charge,".
									$this->prefixed_property_table.".meta_title as meta_title,".
									$this->prefixed_property_table.".meta_keyword as meta_keyword,".
									$this->prefixed_property_table.".meta_description as meta_description,".
									$this->prefixed_user_table.".first_name as owner_first_name,".
									$this->prefixed_user_table.".last_name as owner_last_name,".
									$this->prefixed_user_table.".mobile_number as owner_mobile_number,".
									$this->prefixed_user_table.".email as owner_email,".
									$this->prefixed_user_table.".address as owner_address"
							))
						->join($this->user_table, $this->prefixed_property_table.'.user_id', '=', $this->prefixed_user_table.'.id');

		if ($obj_property) {
			$obj_property = $obj_property->where($this->prefixed_property_table.".id", $id)->first();

			$arr_property = json_decode(json_encode($obj_property),true);
		}
		
		return $arr_property;
	}
	public function get_property_aminities($id=null)
	{
		if($id!=null)
		{
			$arr_aminities = [];
			$obj_aminities = DB::table($this->aminities_table)

			->select(DB::raw(
				$this->prefixed_property_aminities_table.".id as id, ".
				$this->prefixed_aminities_table.".aminity_name as aminity_name"

				)); 

			$obj_aminities = $obj_aminities
			->LeftJoin($this->prefixed_property_aminities_table, $this->prefixed_property_aminities_table.".aminities_id",' = ',$this->prefixed_aminities_table.'.id');

			$obj_aminities = $obj_aminities

			->where($this->prefixed_property_aminities_table.'.property_id',$id);


			if($obj_aminities)
			{
				$obj_aminities = $obj_aminities->get();
				$arr_aminities = json_decode(json_encode($obj_aminities),true);
			}
		}
		return $arr_aminities;
	}

	public function get_property_rules($id=null)
	{
		$arr_rules = [];
		if($id!=null)
		{
			$obj_rules = DB::table($this->property_rules_table)

			->select(DB::raw(
				$this->prefixed_property_rules_table.".rules as rules "
				));

			$obj_rules = $obj_rules->where($this->prefixed_property_rules_table.".property_id", $id)->get();
			if($obj_rules)
			{
				$arr_rules = json_decode(json_encode($obj_rules),true);
			}
		}
		return $arr_rules;
	}

	public function get_unavailble_dates($id=null)
	{
		$obj_availble_date  = '';
		$arr_available_date = [];
		if($id!=null)
		{
			$obj_availble_date = DB::table($this->property_unavailability_table)
			->select(DB::raw(
				$this->prefixed_property_unavailability_table.".id as id,".
				$this->prefixed_property_unavailability_table.".property_id as property_id,".
				$this->prefixed_property_unavailability_table.".from_date as from_date,".
				$this->prefixed_property_unavailability_table.".to_date as to_date "
				));
			$obj_availble_date = $obj_availble_date->where($this->prefixed_property_unavailability_table.".property_id",$id)->get();
			if($obj_availble_date)
			{
				$arr_available_date = json_decode(json_encode($obj_availble_date),true);
			}	

		}
		return $arr_available_date;
	}

	public function get_property_images($id=null)
	{
		$arr_images = [];
		if($id!=null)
		{
			$obj_images = DB::table($this->property_image_table)
			->select(DB::raw(
				$this->prefixed_property_image_table.".image as image_name "
				));

			$obj_images = $obj_images->where($this->prefixed_property_image_table.".property_id", $id)->get();
			if($obj_images)
			{
				$arr_images = json_decode(json_encode($obj_images),true);
			}
		}
		return $arr_images;
	}

	public function get_property_beds_arrangment($id=null)
	{
		$arr_sleeping_arrangment = [];
		if($id!=null)
		{
			$arr_sleeping_arrangment = DB::table($this->property_beds_arrangment_table)
												->where($this->prefixed_property_beds_arrangment_table.".property_id", $id)
												->get();			
		}
		return $arr_sleeping_arrangment;
		
	}

	public function get_user_favorite_list($user_id=null)
	{
		$data = [];
		$arr_property = [];

		if($user_id!=null)
		{
			$obj_property = DB::table($this->favourite_property_table)
							->select(DB::raw( 
							$this->prefixed_favourite_property_table.".id as id,".
							$this->prefixed_favourite_property_table.".user_id as user_id,".
							$this->prefixed_property_table.".property_name_slug as property_name_slug,".
							$this->prefixed_property_table.".id as property_id,".
							$this->prefixed_property_table.".property_type_id as property_type_id,".
							$this->prefixed_property_table.".property_name as property_name,".
							$this->prefixed_property_table.".description as description,".
							$this->prefixed_property_table.".currency as currency,".
							$this->prefixed_property_table.".address as address,".
							$this->prefixed_property_table.".city as city,".
							$this->prefixed_property_table.".state as state,".
							$this->prefixed_property_table.".country as country,".
							$this->prefixed_property_table.".currency_code as currency_code,".
							$this->prefixed_property_table.".postal_code as postal_code,".
							$this->prefixed_property_table.".property_latitude as property_latitude,".
							$this->prefixed_property_table.".property_longitude as property_longitude,".
							$this->prefixed_property_table.".number_of_guest as number_of_guest,".
							$this->prefixed_property_table.".number_of_bedrooms as number_of_bedrooms,".
							$this->prefixed_property_table.".number_of_bathrooms as number_of_bathrooms,".
							$this->prefixed_property_table.".number_of_beds as number_of_beds,".
							$this->prefixed_property_table.".price_per_night as price_per_night,".
							$this->prefixed_property_table.".price_per_sqft as price_per_sqft,".
							$this->prefixed_property_table.".price_per_office as price_per_office,".
							$this->prefixed_property_table.".service_charge as service_charge,".
							$this->prefixed_property_table.".no_of_slots as no_of_slots,".
							$this->prefixed_property_table.".no_of_employee as no_of_employee,".
							$this->prefixed_property_table.".property_working_status as property_working_status,".
							$this->prefixed_property_table.".property_area as property_area,".
							$this->prefixed_property_table.".total_plot_area as total_plot_area,".
							$this->prefixed_property_table.".total_build_area as total_build_area,".
							$this->prefixed_property_table.".admin_area as admin_area,".
							$this->prefixed_property_table.".price_per as price_per,".

							$this->prefixed_property_table.".employee as employee,".
							$this->prefixed_property_table.".room as room,".
							$this->prefixed_property_table.".desk as desk,".
							$this->prefixed_property_table.".cubicles as cubicles,".

							$this->prefixed_property_table.".no_of_employee as no_of_employee,".
							$this->prefixed_property_table.".no_of_room as no_of_room,".
							$this->prefixed_property_table.".no_of_desk as no_of_desk,".
							$this->prefixed_property_table.".no_of_cubicles as no_of_cubicles,".

							$this->prefixed_property_table.".room_price as room_price,".
							$this->prefixed_property_table.".desk_price as desk_price,".
							$this->prefixed_property_table.".cubicles_price as cubicles_price,".

							$this->prefixed_property_type_table.".name as property_type_name,".

							DB::raw('(select distinct '.$this->prefixed_property_image_table.".image from ".$this->prefixed_property_image_table).' where '.$this->prefixed_property_image_table.'.property_id = '.$this->prefixed_property_table.'.id order by id asc limit 1) as property_image'));

			$obj_property = $obj_property->LeftJoin($this->prefixed_property_table,$this->property_table.".id",' = ',$this->prefixed_favourite_property_table.'.property_id');

			$obj_property = $obj_property->Leftjoin($this->prefixed_property_type_table,$this->prefixed_property_table.".property_type_id",' = ',$this->prefixed_property_type_table.'.id');
							
			$obj_property = $obj_property->where($this->prefixed_favourite_property_table.'.user_id', $user_id)->paginate(5);
		
			$arr_pagination          = clone $obj_property;
			$arr_property            = json_decode(json_encode($obj_property),true);

			$data['property_list']   = isset($arr_property['data'])? $arr_property['data']:array();
			$data['arr_pagination']  = $arr_pagination;

		}
		return $data;
	}


	public function get_unavailble_property_id($checkin=null, $checkout=null)
	{
		$arr_unavailble_property=[];
		$table = $this->prefixed_property_unavailability_table;
		$obj_unavailble_property = DB::table($this->property_unavailability_table)
		->select(DB::raw(
			$this->prefixed_property_unavailability_table.".property_id as property_id"
			));	

		$obj_unavailble_property = $obj_unavailble_property->whereBetween($this->prefixed_property_unavailability_table.'.from_date',[DATE($checkin),DATE($checkout)]);
		

		$obj_unavailble_property = $obj_unavailble_property->OrWhereBetween($this->prefixed_property_unavailability_table.'.to_date',[$checkin,$checkout]);
		$obj_unavailble_property = $obj_unavailble_property->OrWhere(function ($q) use($table, $checkin, $checkout)
		{
			$q->where($table.'.to_date',"=",DATE($checkout));
			$q->orWhere($table.'.from_date', "=", DATE($checkout));
			$q->orWhere($table.'.from_date', "=", DATE($checkin));
			$q->orWhere($table.'.to_date', "=", DATE($checkin));
		});


		if($obj_unavailble_property)
		{

			$obj_unavailble_property = $obj_unavailble_property->groupBy($this->prefixed_property_unavailability_table.".property_id");

			$obj_unavailble_property = $obj_unavailble_property->pluck($this->prefixed_property_unavailability_table.".property_id");

			$arr_unavailble_property = json_decode(json_encode($obj_unavailble_property),true);
		}

		// dd($arr_unavailble_property);
		return $arr_unavailble_property;

	}

	public function delete_property($id = null, $user_id=null)
	{
		$is_deleted = false;

		if($id != null && $user_id != null)
		{
			$obj_property = $this->PropertyModel->where('id', $id)->where('user_id', $user_id)->first();
			if($obj_property)
			{
				$arr_property = $obj_property->toArray();

				$property_id = $arr_property['id'];

				DB::beginTransaction();
				try 
				{
					$this->unlink_images($this->PropertyImagesModel, $property_id);

					$this->PropertyRulesModel->where('property_id', $property_id)->delete();
					$this->PropertyUnavailabilityModel->where('property_id', $property_id)->delete();
					$this->PropertyBedsArrangmentModel->where('property_id', $property_id)->delete();
					$this->FavouritePropertyModel->where('property_id', $property_id)->delete();
					$this->PropertyAminitiesModel->where('property_id', $property_id)->delete();
					$this->PropertyImagesModel->where('property_id', $property_id)->delete();
					$obj_property->delete();
					
					DB::commit();

					$is_deleted = true;
				}
				catch (\Exception $e) 
				{
				    DB::rollback();
				}
			}
		}
		return $is_deleted;
	}

	public function unlink_images($table_name = null, $id = null)
	{
		$images_unlink = false;

		if($table_name != null && $id != null)
		{
			$obj_images = $table_name->where('property_id', $id)->get();
			if(count($obj_images) > 0)
			{
				$arr_images = $obj_images->toArray();

				foreach ($arr_images as $data)
				{
					$images = $data['image'];

					if($images!="" && $images!=null) 
					{
						$property_image = $this->property_image_base_path.$images;
		
						if(file_exists($property_image))
						{
							unlink($property_image);
						}
					}
				}
				$images_unlink = true;
			}
			
		}
		return $images_unlink;
	}

}


