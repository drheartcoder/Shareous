<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Common\Services\ListingService;
use App\Common\Services\PropertyDatesService;
use App\Common\Services\PropertyRateCalculatorService;

use App\Models\PropertyModel;
use App\Models\PropertytypeModel;
use App\Models\FavouritePropertyModel;
use App\Models\ReviewRatingModel;
use App\Models\UserModel;
use App\Models\BookingModel;
use App\Models\PropertyUnavailabilityModel;
use App\Models\SiteSettingsModel;
use App\Models\CouponModel;
use App\Models\AmenitiesModel;

use Validator;
use Session;
use DB;

class ListingController extends Controller
{
	function __construct(
							UserModel                     $user_model,
							ListingService                $listing_service,
							PropertyDatesService          $property_date_service,
							PropertyRateCalculatorService $property_rate_service,
							ReviewRatingModel             $review_rating_model,
							PropertyModel                 $property_model,
							PropertytypeModel             $PropertytypeModel,
							FavouritePropertyModel        $favourite_property_model,
							BookingModel                  $booking_model,
							PropertyUnavailabilityModel   $property_unavailability_model,
							SiteSettingsModel             $site_settings_model,
							AmenitiesModel                $aminities,
							CouponModel                   $coupon_model
						)
	{
		$this->arr_view_data                 = [];
		$this->module_title                  = 'Property';
		$this->module_view_folder            = 'front.property.';
		$this->auth                          = auth()->guard('users');
		$this->module_url_path               = url('/').'/property';

		$this->property_image_public_path    = url('/').config('app.project.img_path.property_image');
		$this->property_image_base_path      = base_path().config('app.project.img_path.property_image');

		$this->profile_image_public_img_path = url('/').config('app.project.img_path.user_profile_images');
		$this->profile_image_base_img_path   = public_path().config('app.project.img_path.user_profile_images');

		$this->ListingService                = $listing_service;
		$this->PropertyDatesService          = $property_date_service;
		$this->PropertyRateCalculatorService = $property_rate_service;
		$this->PropertyModel 				 = $property_model;
		$this->PropertytypeModel 			 = $PropertytypeModel;
		$this->FavouritePropertyModel 	     = $favourite_property_model;
		$this->SiteSettingsModel             = $site_settings_model;
		$this->ReviewRatingModel             = $review_rating_model;
		$this->UserModel           		     = $user_model;
		$this->BookingModel                  = $booking_model;
		$this->PropertyUnavailabilityModel   = $property_unavailability_model;
		$this->CouponModel                   = $coupon_model;
		$this->AmenitiesModel                = $aminities;

		if($this->auth->user()) {
			$this->user_id = $this->auth->user()->id;
		}
		else {
			$this->user_id = 0;
		}
	}
	
	public function index(Request $request)
	{
     	$property_max_value = $property_max_sqft_value = $selected_property_id = $property_max_office_value = 0;
		$get_session_data = $val = $arr_property = $arr_favorite = $arr_property_review = [];
		$enc_property_id = $property_id = $guests = '';
		$keyword = $user_id = null;

		$ajax_search   = $request->input('ajax_search', 'no');

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
		$amenities     = $request->input('amenities', null);

		/*Change by kavita*/	
		$property_type       	  = $request->input('property_type', null);	
		$property_working_status  = $request->input('property_working_status', null);		
		$price_per  		 	  = $request->input('price_per', null);		
		$no_of_employee  	 	  = $request->input('no_of_employee', null);		
		$build_type  	 	 	  = $request->input('build_type', null);
		$available_area  	 	  = $request->input('available_area', null);
		$property_type_slug       = str_slug($request->input('property_type', null),'-');
		//End

		if(Session::get('BookingRequestData') != null || !empty(Session::get('BookingRequestData'))) 
		{
            $get_session_data = Session::get('BookingRequestData');

            if (isset($get_session_data['property_id']) && $get_session_data['property_id'] != '') {
        		$property_id = $get_session_data['property_id'];
        	}

        	if (isset($get_session_data['enc_property_id']) && $get_session_data['enc_property_id'] != '') {
        		$enc_property_id = $get_session_data['enc_property_id'];
        	}

            if (Session::get('BookingRequestData.location') != $location) {
                $val = [
	                		'property_type'   => $property_type,
	                		'location'        => $location,
	                		'checkin'         => $get_session_data['checkin'],
	                		'checkout'        => $get_session_data['checkout'],
	                		'guests'          => isset($get_session_data['guests']) ? $get_session_data['guests'] : "",
	                		'enc_property_id' => $enc_property_id,
	                		'property_id'     => $property_id
                		];
                Session::put('BookingRequestData',$val);   
            }

            if ($checkin != '00-00-0000') {
	            if (Session::get('BookingRequestData.checkin') != $checkin) {
	                $val = [
		                		'property_type'   => $property_type,
		                		'location'        => $location,
		                		'checkin'         => $checkin,
		                		'checkout'        => $get_session_data['checkout'],
		                		'guests'          => isset($get_session_data['guests']) ? $get_session_data['guests'] : "",
		                		'enc_property_id' => $enc_property_id,
		                		'property_id'     => $property_id
	                		];
	                Session::put('BookingRequestData',$val);   
	            }
        	}

        	if ($checkout != '00-00-0000') {
	            if (Session::get('BookingRequestData.checkout') != $checkout) {
	                $val = [
				                'property_type'   => $property_type,
				                'location'        => $location,
				                'checkin'         => $get_session_data['checkin'],
				                'checkout'        => $checkout,
				                'guests'          => isset($get_session_data['guests']) ? $get_session_data['guests'] : "",
				                'enc_property_id' => $enc_property_id,
				                'property_id'     => $property_id
			            	];
	                Session::put('BookingRequestData',$val);
	            }
	        }    

	        if ($checkout!='00-00-0000' && $checkin!='00-00-0000') {
	            if ($get_session_data['checkin'] != $checkin && $get_session_data['checkout'] != $checkout) 
	            {
	                $val = [
		                		'property_type'   => $property_type,
		                		'location'        => $location,
		                		'checkin'         => $checkin,
		                		'checkout'        => $checkout,
		                		'guests'          => isset($get_session_data['guests']) ? $get_session_data['guests'] : "",
		                		'enc_property_id' => $enc_property_id,
		                		'property_id'     => $property_id
	                		];

	                Session::put('BookingRequestData',$val);
	            }
	            $get_session_data['checkin']  = $checkin;		
	            $get_session_data['checkout'] = $checkout;
	        }    
            if(Session::get('BookingRequestData.guests') == null || Session::get('BookingRequestData.guests') != $guests){
                $val = [
			                'property_type'      => $property_type,
			                'location'           => $location,
			                'checkin'            => $get_session_data['checkin'],
			                'checkout'           => $get_session_data['checkout'],
			                'guests'             => $guests,
			                'enc_property_id'    => $enc_property_id,
			                'property_id'        => $property_id,
			                'property_type_slug' => $property_type_slug
		            	];

                Session::put('BookingRequestData',$val);
            }
             
            if(Session::get('BookingRequestData.property_type_slug') == null || Session::get('BookingRequestData.property_type_slug') != $guests)
            {
                $val = [
			                'property_type'      => $property_type,
			                'location'           => $location,
			                'checkin'            => $get_session_data['checkin'],
			                'checkout'           => $get_session_data['checkout'],
			                'guests'             => $guests,
			                'enc_property_id'    => $enc_property_id,
			                'property_id'        => $property_id ,
			                'property_type_slug' => $property_type_slug
		            	];
                Session::put('BookingRequestData',$val);
            }
        }
        else
        {
            $val = [
	            		'property_type'      => $property_type,
	            		'location'           => $location,
	            		'checkin'            => $checkin,
	            		'checkout'           => $checkout,
	            		'guests'             => $guests,
	            		'enc_property_id'    => $enc_property_id,
	            		'property_type_slug' => $property_type_slug
            		];
            Session::put('BookingRequestData',$val);   
        }

		$property_max_value = DB::table('property')->where(['admin_status' => '2','property_status' => '1']);
								if ($property_type_slug == 'warehouse')
								{
									$property_max_value = $property_max_value->sum('price_per_sqft');
								}
								else if ($property_type_slug == 'office-space')
								{
									$property_max_value = $property_max_value->sum('price_per_office');
								}
								else
								{
									$property_max_value = $property_max_value->sum('price_per_night');
								}

        if(isset($_REQUEST['is_featured']) && $_REQUEST['is_featured'] == "yes"){
            $featured = 'yes';
        }else {
        	$featured = 'no';
        }

		$arr_property = $this->ListingService->get_property_listing($property_type,$checkin, $checkout, $location, $guests, $city, $state, $country, $postal_code, $latitude, $longitude, $price_max, $price_min, $min_bedrooms, $min_bathrooms, $room_category, $reviews,$amenities,$keyword,$user_id,$property_working_status,$price_per,$no_of_employee,$build_type,$available_area,$featured);

		$obj_pagination    = $arr_property['arr_pagination'];
		$arr_property_maps = $arr_property['property_list_maps'];

		$obj_property_review = $this->ReviewRatingModel->where('status','1')->get();

	    if(isset($obj_property_review) && $obj_property_review!=null){
	        $arr_property_review = $obj_property_review->toArray();
	    }

		if(count($arr_property) > 0){
			foreach($arr_property['property_list'] as $key => $property){
				$total          = 0;
				$count          = 0;
				$tmp_str_rating = '';
				$no_reviews     = 0;

                if(isset($arr_property_review)){
                    foreach($arr_property_review as $rating) {
                        if($rating['property_id'] == $property['id']) {
                            $total  += floatval($rating['rating']);
                            $count++;
                        }
                    }
                }

                if($count !=0){
                    $no_reviews = $total / $count;
                }

				$arr_property['property_list'][$key]['average_rating']         = $no_reviews;
				$arr_property['property_list'][$key]['total_property_reviews'] = $count;
			}

			// For Google Maps
			foreach($arr_property_maps as $key => $property_maps){
				$total1          = 0;
				$count1          = 0;
				$tmp_str_rating1 = '';
				$no_reviews1     = 0;

                if(isset($arr_property_review)){
                    foreach($arr_property_review as $rating1) {
                        if($rating1['property_id'] == $property_maps['id']) {
                            $total1 += floatval($rating1['rating']);
                            $count1++;
                        }
                    }
                }

                if($count1 != 0){
                    $no_reviews1 = $total1 / $count1;
                }
                
				$arr_property_maps[$key]['average_rating']         = $no_reviews1;
				$arr_property_maps[$key]['total_property_reviews'] = $count1;
			}
		}

		$this->arr_view_data['arr_property']      = $arr_property['property_list'];
		$this->arr_view_data['arr_property_maps'] = $arr_property_maps; // For Google Maps

	    if(!empty($this->user_id)){
			$arr_favorite = $this->FavouritePropertyModel->where('user_id','=',$this->user_id)->get()->toArray();
	    }
		
		if($obj_pagination){
			$arr_pagination = $obj_pagination->toArray();
		}

		$arr_selected_aminities = $arr_aminities = [];
		$obj_aminities = $this->AmenitiesModel->where('status','1')->get();
		if(count($obj_aminities)>0){
			$arr_aminities = $obj_aminities->toArray();
		}

		$selected_property = isset($request->property_type)?$request->property_type:'';
		if($selected_property!='')
		{
			$obj_get_property_id = $this->PropertytypeModel->select('id','name')->where('name','like',$selected_property)->first();
			if($obj_get_property_id)
			{
				$selected_property_id = $obj_get_property_id->id;
				
				$obj_aminities = $this->AmenitiesModel->where('propertytype_id',$selected_property_id)->get();
				
				if($obj_aminities != FALSE)
		        {
		            $arr_selected_aminities =  $obj_aminities->toArray();
		        }
			}
		}

		
		$this->arr_view_data['selected_property_type']      = isset($request->property_type) ? $request->property_type : '';
		$this->arr_view_data['arr_property_review']         = $arr_property_review;
		$this->arr_view_data['obj_pagination']              = $obj_pagination;
		$this->arr_view_data['arr_property_list_maps']      = $arr_property['property_list_maps'];
		$this->arr_view_data['arr_favorite']                = $arr_favorite;
		$this->arr_view_data['arr_selected_aminities']      = $arr_selected_aminities;
		$this->arr_view_data['arr_aminities']               = $arr_aminities;
		$this->arr_view_data['property_max_value']          = $property_max_value;
		$this->arr_view_data['total']                       = isset($arr_pagination['total'])? $arr_pagination['total']:'0';
		$this->arr_view_data['to']                          = isset($arr_pagination['to'])? $arr_pagination['to']:'0';
		$this->arr_view_data['from']                        = isset($arr_pagination['from'])? $arr_pagination['from']:'0';

		$this->arr_view_data['property_type']               = get_property_type();
		$this->arr_view_data['property_category']           = get_category();
		$this->arr_view_data['property_image_public_path']  = $this->property_image_public_path;
		$this->arr_view_data['property_image_base_path']    = $this->property_image_base_path;

		$this->arr_view_data['page_title']                  = 'List '.$this->module_title;
		$this->arr_view_data['location']                    = $location;
		$this->arr_view_data['state']                       = $state;
		$this->arr_view_data['country']                     = $country;
		$this->arr_view_data['city']                        = $city;
		$this->arr_view_data['postal_code']                 = $postal_code;
		$this->arr_view_data['module_url_path']             = $this->module_url_path;

		return view($this->module_view_folder.'index',$this->arr_view_data);
	}

	public function view($slug = null)
	{
		$arr_unavailble_dates = $arr_property = $arr_property_calander_dates = $get_property_beds_arrangment = $temp_data = $obj_review_rating = $obj_property_review = $arr_review_rating = $arr_sitesettings = $arr_user = $arr_property_type = [];
		$availability = 0;
		$property_id = '';

		$review_table          = $this->ReviewRatingModel->getTable();
		$user_table            = $this->UserModel->getTable();
		$prefixed_review_table = DB::getTablePrefix().$this->ReviewRatingModel->getTable();
		$prefixed_user_table   = DB::getTablePrefix().$this->UserModel->getTable();

		$this->arr_view_data['arr_favorite'] = $this->ListingService->get_user_favorite_list(1);		
			
		$temp_data = $this->PropertyModel->where('property_name_slug', $slug)->first();
		
		if (isset($temp_data) && count($temp_data)>0) {
			$property_id = $temp_data->id;
			$property_type_id = $temp_data->property_type_id;

			$obj_property_type = $this->PropertytypeModel->select('id', 'name')->where('id', $property_type_id)->first();
			if($obj_property_type)
			{
				$arr_property_type = $obj_property_type->toArray();
			}
		}

		if(Session::get('BookingRequestData') != null && Session::get('BookingRequestData.checkin') != '' && Session::get('BookingRequestData.checkout') != '') {
			$checkin = Session::get('BookingRequestData.checkin');
		    $checkout = Session::get('BookingRequestData.checkout');		     		    
			$availability  = $this->PropertyRateCalculatorService->get_unavaialable_dates($property_id,$checkin,$checkout);        	
			
			if ($availability == 0) {
				Session::flash('booking_error','Selected date not available for booking, Please select another dates.');
			}
		}


		$where_cnd = array( 'status' => '1', 'property_id' => $property_id );
		
		$obj_sitesettings = $this->SiteSettingsModel->select('gst', 'admin_commission')->first();
		if ($obj_sitesettings) {
			$arr_sitesettings = $obj_sitesettings->toArray();
		}

		$obj_property_review = $this->ReviewRatingModel->where($where_cnd)->get();

        if (isset($obj_property_review) && $obj_property_review != null) {
            $arr_property_review = $obj_property_review->toArray();
        }

        $this->arr_view_data['arr_property_review'] = $arr_property_review;

		$arr_review_rating = DB::table($prefixed_review_table)
					                ->select($prefixed_review_table.'.*', 
					                	$prefixed_user_table.".first_name",
					                	$prefixed_user_table.".last_name",
					                	$prefixed_user_table.".profile_image")
					                ->Join($prefixed_user_table,$prefixed_user_table.".id",' = ',$review_table.'.rating_user_id')
					              	->where($prefixed_review_table.'.status','1')
					                ->where($prefixed_review_table.'.property_id',$property_id)
					                ->get();

		if (count($temp_data) > 0 && $slug != null) {
			$arr_property_calander_dates          = $this->PropertyDatesService->get_property_dates($temp_data['id']);
			$arr_property                         = $this->ListingService->get_property_details($temp_data['id']);
			$this->arr_view_data['arr_aminities'] = $this->ListingService->get_property_aminities($temp_data['id']);
			$this->arr_view_data['arr_rules']     = $this->ListingService->get_property_rules($temp_data['id']);
			$this->arr_view_data['arr_images']    = $this->ListingService->get_property_images($temp_data['id']);			
			$get_property_beds_arrangment 		  = $this->ListingService->get_property_beds_arrangment($temp_data['id']);

			if ($arr_property) {
				$arr_user = $this->UserModel->where('id',$this->user_id)->first();				
			}	
		}

		$unavailble_dates = $this->PropertyUnavailabilityModel->where('property_id',$property_id)
															  ->where('to_date', '>', date('Y-m-d'))
															  ->orderBy('from_date','ASC')
															  ->get();

		if ($unavailble_dates) {
			$arr_unavailble = $unavailble_dates->toArray();
		}
		//dd( $arr_unavailble );

		if($arr_property != null && !empty($arr_property) && Session::get('BookingRequestData') != null && !empty(Session::get('BookingRequestData'))) {
			if($arr_property['id'] != Session::get('BookingRequestData.property_id')) {
				Session::put('BookingRequestData',[]);
				Session::put('SearchData',[]);
			}
		}

		$this->arr_view_data['arr_property_type']              = $arr_property_type;
		$this->arr_view_data['arr_sitesettings']               = $arr_sitesettings;
		$this->arr_view_data['arr_property_review']            = $arr_property_review;
		$this->arr_view_data['arr_review_rating']              = $arr_review_rating;
		$this->arr_view_data['sleeping_arrangment_arr']        = $get_property_beds_arrangment;
		$this->arr_view_data['arr_unavailble_dates']           = base64_encode(json_encode($arr_property_calander_dates));
		$this->arr_view_data['unavailble_dates']               = $arr_unavailble;
		$this->arr_view_data['arr_property']                   = $arr_property;
		$this->arr_view_data['arr_user'] 	                   = $arr_user;
		$this->arr_view_data['meta_title'] 	                   = isset($arr_property['meta_title'])?$arr_property['meta_title']:'';
		$this->arr_view_data['meta_keyword']                   = isset($arr_property['meta_keyword'])?$arr_property['meta_keyword']:'';
		$this->arr_view_data['meta_description']               = isset($arr_property['meta_description'])?$arr_property['meta_description']:'';		
		$this->arr_view_data['module_url_path']                = $this->module_url_path;
		$this->arr_view_data['page_title']                     = 'View '.$this->module_title;
		$this->arr_view_data['property_image_public_path']     = $this->property_image_public_path;
		$this->arr_view_data['property_image_base_path']       = $this->property_image_base_path;
		$this->arr_view_data['user_profile_image_public_path'] = $this->profile_image_public_img_path;
		$this->arr_view_data['user_profile_image_base_path']   = $this->profile_image_base_img_path;

		return view($this->module_view_folder.'view',$this->arr_view_data);
	}

	public function submit_rating_review(Request $request, $getproperty_id = null)
	{
		$arr_rules = array();
        $status    = FALSE;

        $arr_rules['rating']  = "required";
        $arr_rules['comment'] = "required";
      
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $rating      = $request->input('rating');
        $comment     = $request->input('comment');
        $booking_id  = base64_decode($request->input('booking_id'));
        $review_id   = $request->input('review_id');
        $property_id = base64_decode($getproperty_id);
        $user_id     = $this->user_id;

        $arr_review_data['rating']         = $rating;
        $arr_review_data['rating_user_id'] = $user_id;
        $arr_review_data['property_id']    = $property_id;
        $arr_review_data['message']        = $comment;
        $arr_review_data['booking_id']     = $booking_id;
        
        $check_review = array(
        		'property_id'    => $property_id,
        		'rating_user_id' => $user_id,
        		'booking_id'     => $booking_id
        	);

        if(isset($review_id) && $review_id != ''){
	        $status = $this->ReviewRatingModel->where('id',$review_id)->update(['message'=>$comment,'rating'=>$rating]);
	        if($status) {
				Session::flash('success','Review & Rating updated successfully.');
	        }
	        else {
				Session::flash('error','Problem occured, while updating reviews');
	        }
        }
        else {
        	$booking_review = $this->BookingModel->where(array('property_id' => $property_id,'property_booked_by' => $user_id,'booking_status' => '5'))->get();

        	if(count($booking_review)) {
        		$get_review = $this->ReviewRatingModel->where($check_review)->where('status', 0)->first();
        		if( $get_review ) {
        			$arr_review = $get_review->toArray();
        			$get_review->delete();
        		}

        		$status = $this->ReviewRatingModel->create($arr_review_data);
	        	if($status) {
					Session::flash('success','Review & Rating Added successfully.');
		        }
		        else {
					Session::flash('error','Problem occured, while adding reviews');
		        }
        	}
        	else {
				Session::flash('error','You are not able to add review & ratings');
        	}
        }
        return redirect()->back(); 
	}

	public function host_property_listing(Request $request)
	{
		$arr_property = $arr_property_review = [];
		$keyword = '';
		$keyword = $request->input('keyword');

		$arr_property = $this->ListingService->get_property_listing('','','','','','','','','','','','','','','','','','',$keyword,$this->user_id,'','','','','','');
				
		$obj_pagination                      = $arr_property['arr_pagination'];
		$this->arr_view_data['arr_property'] = $arr_property['property_list'];
		
		if($obj_pagination) {
			$arr_pagination = $obj_pagination->toArray();
		}

		$obj_property_review = $this->ReviewRatingModel->where('status', '1')->get();
        if(isset($obj_property_review) && $obj_property_review != null) {
            $arr_property_review = $obj_property_review->toArray();
        }

        $this->arr_view_data['arr_property_review']        = $arr_property_review;
		$this->arr_view_data['obj_pagination']             = $obj_pagination;
		$this->arr_view_data['property_image_public_path'] = $this->property_image_public_path;
		$this->arr_view_data['property_image_base_path']   = $this->property_image_base_path;
		$this->arr_view_data['module_url_path']            = $this->module_url_path;
		$this->arr_view_data['page_title']                 = 'My Listings';
			
		return view($this->module_view_folder.'my_listing',$this->arr_view_data);
	}

	public function delete($enc_id)
	{
		if($enc_id) {
			$id = base64_decode($enc_id);
			$arr_property = $this->ListingService->delete_property($id, $this->user_id);
			if($arr_property == true) {
				Session::flash('success','Property deleted successfully');
				return redirect()->back();
			}
			else {
				Session::flash('error','Soemthing Went wrong. Please try again');
				return redirect()->back();
			}
		}
		else {
			Session::flash('error','Soemthing Went wrong. Please try again');
			return redirect()->back();
		}
	}

	public function getaminities(Request $request)
	{
		$arr_aminities = $arr_response = $aminities = $arr_property_type = [];
		$property_type = $request->id;

		$obj_property_type = $this->PropertytypeModel->select('id', 'name', 'status')
													 ->where('name', $property_type)
													 ->where('status', 1)
													 ->first();
		if($obj_property_type)
		{
			$arr_property_type = $obj_property_type->toArray();

			$obj_aminities = $this->AmenitiesModel->select('id', 'propertytype_id', 'aminity_name', 'status')
												  ->where('propertytype_id', $arr_property_type['id'])
												  ->where('status', 1)
												  ->get();
			if($obj_aminities)
	        {
	            $arr_aminities = $obj_aminities->toArray();
	        }
		}

        if( sizeof($arr_aminities) > 0 )
        {
        	foreach ($arr_aminities as $key => $value) {
        		$aminities[$key]['id'] = isset($value['id']) ? ucfirst($value['id']) : '';
        		$aminities[$key]['aminity_name'] = isset($value['aminity_name']) ? ucfirst($value['aminity_name']) : '';
        	}

            $arr_response['status']        = "success";
            $arr_response['arr_aminities'] = $aminities;
        }
        else
        {
            $arr_response['status'] = "error";
            $arr_response['arr_aminities'] = $aminities;
        }
        return response()->json($arr_response);
	}



	public function ajax_listing(Request $request)
	{
		$this->arr_view_data['page_title']      = 'List '.$this->module_title;
		$this->arr_view_data['module_url_path'] = $this->module_url_path;
		return view($this->module_view_folder.'ajax_listing',$this->arr_view_data);
	} // end ajax_listing

	public function ajax_search(Request $request)
	{
		//dd( $request->all() );

		$property_max_value = $property_max_sqft_value = $selected_property_id = $property_max_office_value = 0;
		$get_session_data = $val = $arr_property = $arr_favorite = $arr_property_review = [];
		$enc_property_id = $property_id = $guests = '';
		$keyword = $user_id = null;

		$checkin            = $request->input('checkin', null);
		$checkout           = $request->input('checkout', null);
		$guests             = $request->input('guests', null);

		$location           = $request->input('location', null);
		$city               = $request->input('city', null);
		$state              = $request->input('state', null);
		$country            = $request->input('country', null);
		$postal_code        = $request->input('postal_code', null);
		$latitude           = $request->input('latitude', null);
		$longitude          = $request->input('longitude', null);

		$price_max          = $request->input('price_max', null);
		$price_min          = $request->input('price_min', null);
		$min_bedrooms       = $request->input('min_bedrooms', null);
		$min_bathrooms      = $request->input('min_bathrooms', null);
		$room_category      = $request->input('room_category', null);
		$reviews            = $request->input('cmb_rating', null);
		$amenities          = $request->input('amenities', null);

		$property_type      = $request->input('property_type', null);
		$working_status     = $request->input('property_working_status', null);
		$price_per          = $request->input('price_per', null);
		$no_of_employee     = $request->input('no_of_employee', null);
		$build_type         = $request->input('build_type', null);
		$available_area     = $request->input('available_area', null);
		$property_type_slug = str_slug($request->input('property_type', null),'-');




		if(Session::get('BookingRequestData') != null || !empty(Session::get('BookingRequestData'))) 
		{
            $get_session_data = Session::get('BookingRequestData');

            if (isset($get_session_data['property_id']) && $get_session_data['property_id'] != '') {
        		$property_id = $get_session_data['property_id'];
        	}

        	if (isset($get_session_data['enc_property_id']) && $get_session_data['enc_property_id'] != '') {
        		$enc_property_id = $get_session_data['enc_property_id'];
        	}

            if (Session::get('BookingRequestData.location') != $location) {
                $val = [
							'property_type'   => $property_type,
							'location'        => $location,
							'checkin'         => $get_session_data['checkin'],
							'checkout'        => $get_session_data['checkout'],
							'guests'          => isset($get_session_data['guests']) ? $get_session_data['guests'] : "",
							'enc_property_id' => $enc_property_id,
							'property_id'     => $property_id
                		];
                Session::put('BookingRequestData',$val);   
            }

            if ($checkin != '00-00-0000') {
	            if (Session::get('BookingRequestData.checkin') != $checkin) {
	                $val = [
								'property_type'   => $property_type,
								'location'        => $location,
								'checkin'         => $checkin,
								'checkout'        => $get_session_data['checkout'],
								'guests'          => isset($get_session_data['guests']) ? $get_session_data['guests'] : "",
								'enc_property_id' => $enc_property_id,
								'property_id'     => $property_id
	                		];
	                Session::put('BookingRequestData',$val);   
	            }
        	}

        	if ($checkout != '00-00-0000') {
	            if (Session::get('BookingRequestData.checkout') != $checkout) {
	                $val = [
								'property_type'   => $property_type,
								'location'        => $location,
								'checkin'         => $get_session_data['checkin'],
								'checkout'        => $checkout,
								'guests'          => isset($get_session_data['guests']) ? $get_session_data['guests'] : "",
								'enc_property_id' => $enc_property_id,
								'property_id'     => $property_id
			            	];
	                Session::put('BookingRequestData',$val);
	            }
	        }    

	        if ($checkout!='00-00-0000' && $checkin!='00-00-0000') {
	            if ($get_session_data['checkin'] != $checkin && $get_session_data['checkout'] != $checkout) 
	            {
	                $val = [
								'property_type'   => $property_type,
								'location'        => $location,
								'checkin'         => $checkin,
								'checkout'        => $checkout,
								'guests'          => isset($get_session_data['guests']) ? $get_session_data['guests'] : "",
								'enc_property_id' => $enc_property_id,
								'property_id'     => $property_id
	                		];

	                Session::put('BookingRequestData',$val);
	            }
	            
	            $get_session_data['checkin']  = $checkin;
	            $get_session_data['checkout'] = $checkout;
	        }    
            if(Session::get('BookingRequestData.guests') == null || Session::get('BookingRequestData.guests') != $guests){
                $val = [
							'property_type'      => $property_type,
							'location'           => $location,
							'checkin'            => $get_session_data['checkin'],
							'checkout'           => $get_session_data['checkout'],
							'guests'             => $guests,
							'enc_property_id'    => $enc_property_id,
							'property_id'        => $property_id,
							'property_type_slug' => $property_type_slug
		            	];

                Session::put('BookingRequestData',$val);
            }
             
            if(Session::get('BookingRequestData.property_type_slug') == null || Session::get('BookingRequestData.property_type_slug') != $guests)
            {
                $val = [
							'property_type'      => $property_type,
							'location'           => $location,
							'checkin'            => $get_session_data['checkin'],
							'checkout'           => $get_session_data['checkout'],
							'guests'             => $guests,
							'enc_property_id'    => $enc_property_id,
							'property_id'        => $property_id ,
							'property_type_slug' => $property_type_slug
		            	];
                Session::put('BookingRequestData',$val);
            }
        }
        else
        {
            $val = [
						'property_type'      => $property_type,
						'location'           => $location,
						'checkin'            => $checkin,
						'checkout'           => $checkout,
						'guests'             => $guests,
						'enc_property_id'    => $enc_property_id,
						'property_type_slug' => $property_type_slug
            		];
            Session::put('BookingRequestData',$val);   
        }

		$property_max_value = DB::table('property')->where(['admin_status' => '2','property_status' => '1']);
								if ($property_type_slug == 'warehouse') {
									$property_max_value = $property_max_value->sum('price_per_sqft');
								}
								else if ($property_type_slug == 'office-space') {
									$property_max_value = $property_max_value->sum('price_per_office');
								}
								else {
									$property_max_value = $property_max_value->sum('price_per_night');
								}


        if(isset($_REQUEST['is_featured']) && $_REQUEST['is_featured'] == "yes"){
            $featured = 'yes';
        }else {
        	$featured = 'no';
        }

		$arr_property = $this->ListingService->get_property_listing($property_type,$checkin, $checkout, $location, $guests, $city, $state, $country, $postal_code, $latitude, $longitude, $price_max, $price_min, $min_bedrooms, $min_bathrooms, $room_category, $reviews,$amenities,$keyword,$user_id,$working_status,$price_per,$no_of_employee,$build_type,$available_area,$featured);

		$obj_pagination    = $arr_property['arr_pagination'];
		$arr_property_maps = $arr_property['property_list_maps'];

		$obj_property_review = $this->ReviewRatingModel->where('status','1')->get();

	    if(isset($obj_property_review) && $obj_property_review != null) {
	        $arr_property_review = $obj_property_review->toArray();
	    }

		if(count($arr_property) > 0){
			foreach($arr_property['property_list'] as $key => $property) {
				$total          = 0;
				$count          = 0;
				$tmp_str_rating = '';
				$no_reviews     = 0;

                if(isset($arr_property_review)){
                    foreach($arr_property_review as $rating) {
                        if($rating['property_id'] == $property['id']) {
                            $total  += floatval($rating['rating']);
                            $count++;
                        }
                    }
                }

                if($count != 0) {
                    $no_reviews = $total / $count;
                }

				$arr_property['property_list'][$key]['average_rating']         = $no_reviews;
				$arr_property['property_list'][$key]['total_property_reviews'] = $count;
			}

			// For Google Maps
			foreach($arr_property_maps as $key => $property_maps){
				$total1          = 0;
				$count1          = 0;
				$tmp_str_rating1 = '';
				$no_reviews1     = 0;

                if(isset($arr_property_review)){
                    foreach($arr_property_review as $rating1) {
                        if($rating1['property_id'] == $property_maps['id']) {
                            $total1 += floatval($rating1['rating']);
                            $count1++;
                        }
                    }
                }

                if($count1 != 0){
                    $no_reviews1 = $total1 / $count1;
                }
                
				$arr_property_maps[$key]['average_rating']         = $no_reviews1;
				$arr_property_maps[$key]['total_property_reviews'] = $count1;
			}
		}

		$arr_response['arr_property']      = $arr_property['property_list'];
		$arr_response['arr_property_maps'] = $arr_property_maps; // For Google Maps

	    if(!empty($this->user_id)) {
			$arr_favorite = $this->FavouritePropertyModel->where('user_id','=',$this->user_id)->get()->toArray();
	    }
		
		if($obj_pagination) {
			$arr_pagination = $obj_pagination->toArray();
		}

		$arr_selected_aminities = $arr_aminities = [];
		$obj_aminities = $this->AmenitiesModel->where('status','1')->get();
		if(count($obj_aminities) > 0) {
			$arr_aminities = $obj_aminities->toArray();
		}

		$selected_property = isset($request->property_type) ? $request->property_type : '';
		if($selected_property != '')
		{
			$obj_get_property_id = $this->PropertytypeModel->select('id','name')->where('name','like',$selected_property)->first();
			if($obj_get_property_id)
			{
				$selected_property_id = $obj_get_property_id->id;
				
				$obj_aminities = $this->AmenitiesModel->where('propertytype_id',$selected_property_id)->get();
				if($obj_aminities != FALSE)
		        {
		            $arr_selected_aminities =  $obj_aminities->toArray();
		        }
			}
		}
		
		$arr_response['total']                      = isset($arr_pagination['total']) ? $arr_pagination['total'] : '0';
		$arr_response['to']                         = isset($arr_pagination['to']) ? $arr_pagination['to'] : '0';
		$arr_response['from']                       = isset($arr_pagination['from']) ? $arr_pagination['from'] : '0';
		$arr_response['property_type_slug']         = isset($property_type_slug) ? $property_type_slug : '';

		$arr_response['selected_property_type']     = isset($request->property_type) ? $request->property_type : '';
		$arr_response['arr_property_review']        = $arr_property_review;
		$arr_response['obj_pagination']             = $obj_pagination;
		$arr_response['arr_property_list_maps']     = $arr_property['property_list_maps'];
		$arr_response['arr_favorite']               = $arr_favorite;
		$arr_response['arr_selected_aminities']     = $arr_selected_aminities;
		$arr_response['arr_aminities']              = $arr_aminities;
		$arr_response['property_max_value']         = $property_max_value;
		$arr_response['property_type']              = get_property_type();
		$arr_response['property_category']          = get_category();
		$arr_response['property_image_public_path'] = $this->property_image_public_path;
		$arr_response['property_image_base_path']   = $this->property_image_base_path;
		$arr_response['page_title']                 = 'List '.$this->module_title;
		$arr_response['location']                   = $location;
		$arr_response['state']                      = $state;
		$arr_response['country']                    = $country;
		$arr_response['city']                       = $city;
		$arr_response['postal_code']                = $postal_code;
		$arr_response['module_url_path']            = $this->module_url_path;


		$listing_html = $pagination_html = '';

		if( count( $arr_property['property_list'] ) > 0) {
			foreach ($arr_property['property_list'] as $key => $list) {
				$listing_html .= '<div class="list-vactions-details total-plot">';
				$listing_html .= '<div class="image-list-vact">';
				$listing_html .= '<a class="favorat-icn" ><i class="fa fa-heart-o"></i></a>';
				$listing_html .= '<a class="favorat-icn" ><i class="fa fa-heart"></i></a>';
				$listing_html .= '<img src="'.url('/').'/front/images/Listing-page-no-image.jpg" alt="" />';
				$listing_html .= '</div>';
				$listing_html .= '<div class="content-list-vact">';
				$listing_html .= '<h3><a href="#" target="new">'.title_case($list['property_name']).' ('.title_case($list['property_type_name']).')</a></h3>';
				$listing_html .= '<div class="review-cont"><img src="'.url('/').'/front/images/map-icns.png"> '.title_case($list['address']).'</div>';
				$listing_html .= '<div class="star-reviews-list pull-left"><img src="'.url('/').'/front/images/star1.png" /></div>';
				$listing_html .= '<div class="review-cont">0 reviews</div>';
				$listing_html .= '<div class="guest-threebox">';
				$listing_html .= '<ul>';
				$listing_html .= '<li>0 Sq.Ft<div class="txt-p">Total Area</div></li>';
				$listing_html .= '<li>0 Sq.Ft<div class="txt-p">Total Build Area</div></li>';
				$listing_html .= '<li>0 Sq.Ft<div class="txt-p">Admin Area</div></li>';
				$listing_html .= '<li>0 <div class="txt-p">Guests</div></li>';
				$listing_html .= '<li>0 <div class="txt-p">Bedroom</div></li>';
				$listing_html .= '<li>0 <div class="txt-p">Bathroom</div></li>';
				$listing_html .= '</ul>';
				$listing_html .= '</div>';
				$listing_html .= '<div class="bottom-box">';
				$listing_html .= '<div class="pricesection-value">';
				$listing_html .= '0.00 <span>/per Sq.Ft</span>';
				$listing_html .= '0.00 <span>/per Office Space</span>';
				$listing_html .= '0.00 <span>/per night</span>';
				$listing_html .= '</div>';
				$listing_html .= '<a href="#" target="new" class="view-details-btn float-right">View Details</a>';
				$listing_html .= '</div>';
				$listing_html .= '</div>';
				$listing_html .= '<div class="clearfix"></div>';
				$listing_html .= '</div>';


			}

			if(isset($obj_pagination) && $obj_pagination != null)
			{
				$pagination_html .= '<div class="text-center pagination-arrows-set" >';
					$querystringArray = \Input::only(['is_featured','property_type','search','location','checkin','checkout','guests','latitude','longitude','city','state','country','postal_code','price_max','price_min','min_bedrooms','min_bedrooms','min_bathrooms','room_category','cmb_rating','amenities','keyword','user_id','property_working_status','price_per','no_of_employee','build_type','available_area']); 
				$pagination_html .= $obj_pagination->appends($querystringArray)->render();
				$pagination_html .= '</div>';
			}
		}
		else
		{
			$listing_html .= '<div class="list-vactions-details"><div class="no-record-found"></div></div>';
		}



		$arr_response['listing_html']    = $listing_html;
		$arr_response['pagination_html'] = $pagination_html;

		return json_encode($arr_response);




	} // end ajax_search
}
