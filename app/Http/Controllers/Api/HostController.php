<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Common\Services\ListingService;
use App\Common\Services\PropertyDatesService;
use App\Common\Services\PropertyRateCalculatorService;

use App\Models\UserModel;
use App\Models\PropertyModel;
use App\Models\PropertyBedsArrangmentModel;
use App\Models\PropertyImagesModel;
use App\Models\AmenitiesModel;
use App\Models\PropertyRulesModel;
use App\Models\PropertyAminitiesModel;
use App\Models\PropertyUnavailabilityModel;
use App\Models\ReviewRatingModel;
use App\Models\BankDetailsModel;
use App\Models\TransactionModel;
use App\Models\BookingModel;
use App\Models\HostVerificationModel;

use Image;
use File;
use Validator;
use Input;
use DB;
use Mail;

class HostController extends Controller
{
    public function __construct(
									UserModel                     $user_model,
									ListingService                $listing_service,
									BankDetailsModel              $bank_details,
									PropertyModel                 $property_model,
									PropertyBedsArrangmentModel   $property_beds_arrangment_model,
									AmenitiesModel                $aminities,
									PropertyDatesService          $property_date_service,
									PropertyRulesModel            $property_rules_model,
									PropertyImagesModel           $property_images_model,
									PropertyAminitiesModel        $property_aminities_model,
									PropertyUnavailabilityModel   $property_unavailibitity_model,
									PropertyRateCalculatorService $property_rate_service,
									ReviewRatingModel             $review_rating_model,
									BookingModel                  $BookingModel,
									HostVerificationModel         $HostVerificationModel,
									TransactionModel              $transaction_model
								)
    {
		$this->UserModel                     = $user_model;
		$this->ListingService                = $listing_service;
		$this->PropertyImagesModel           = $property_images_model;
		$this->PropertyBedsArrangmentModel   = $property_beds_arrangment_model;
		$this->PropertyModel                 = $property_model;
		$this->BankDetailsModel              = $bank_details;
		$this->AmenitiesModel                = $aminities;
		$this->PropertyRulesModel            = $property_rules_model;
		$this->PropertyAminitiesModel        = $property_aminities_model;
		$this->PropertyUnavailabilityModel   = $property_unavailibitity_model;
		$this->ReviewRatingModel             = $review_rating_model;
		$this->PropertyDatesService          = $property_date_service;
		$this->PropertyRateCalculatorService = $property_rate_service;
		$this->TransactionModel              = $transaction_model;
		$this->BookingModel                  = $BookingModel;
		$this->HostVerificationModel         = $HostVerificationModel;
		$this->user_id                       = validate_user_jwt_token();

		$this->property_image_base_path      = base_path().config('app.project.img_path.property_image');
		$this->property_image_public_path    = url('/').config('app.project.img_path.property_image');

		$this->profile_image_public_img_path = url('/').config('app.project.img_path.user_profile_images');
		$this->profile_image_base_img_path   = public_path().config('app.project.img_path.user_profile_images');

		$this->id_proof_public_path          = url('/').config('app.project.img_path.user_id_proof');
		$this->id_proof_base_path            = public_path().config('app.project.img_path.user_id_proof');

		$this->photo_public_path             = url('/').config('app.project.img_path.user_photo');
		$this->photo_base_path               = public_path().config('app.project.img_path.user_photo');
    }
    
    public function my_documents()
    {
		$arr_doc = '';

		$user_id = $this->user_id;
		$obj_doc = $this->HostVerificationModel->where(['user_id'=> $user_id])->first();
		if(isset($obj_doc) && count($obj_doc)>0) {
			$arr_doc = $obj_doc->toArray();

			$data['id_proof_public_path'] = $this->id_proof_public_path;
			$data['photo_public_path'] 	  = $this->photo_public_path;
			$data['doc_data']             = $arr_doc;
			
			$status  = 'success';
			$message = 'Record get successfully';
		    return $this->build_response($status,$message,$data);
		}
		else {
			$status = 'success';
		    $message = 'Record not found';
		    return $this->build_response($status,$message);
		}
    }

    public function get_sleeping_arrangement(Request $request)
    {
		$status         = 'error';
		$message        = 'Record Not Found';
		$no_of_bedrooms = $request->input('no_of_bedrooms');

    	$get_sleeping_arrangement = get_sleeping_arrangement();
    	if(isset($get_sleeping_arrangement) && count($get_sleeping_arrangement)>0) {
      		foreach ($get_sleeping_arrangement as $key => $value) {
      			$arr_data[$key]['id'] 	 = $value['id'];
      			$arr_data[$key]['value'] = $value['value'];
      		}
		    $status  = 'success';
		    $message = 'Record get successfully';
	    }
	    return $this->build_response($status,$message,$arr_data);
    }

    public function create_property_step1()
    {
    	$arr_property_type = $property_data = $arr_category = $currency_list = $currency_data = $category_data = $arr_data = [];
    	$status = $message = '';

    	$user_id = $this->user_id;
    	if (isset($user_id) && $user_id != '') {
    		$arr_property_type  = get_property_type();
	    	$arr_category       = get_category();
	    	$property_id        = get_incomplete_property($user_id);
	        $currency_list     	= get_currency();

	        if(isset($property_id) && $property_id != "") {
		    	$property_data = $this->PropertyModel->where('id',$property_id)->first();
		          $status  = 'error_edit';
		          $message = 'First add details of previous property,After then only you can add new property.';
		          $arr_data['property_id']        = $property_id;
		          $arr_data['property_type_slug'] = get_property_type_slug($property_data['property_type_id']);
		          return $this->build_response($status,$message,$arr_data);
		    }
		    else {
		    	$status  = 'success';
		    	$message = 'Record get successfully';
		    	if (isset($arr_property_type) && count($arr_property_type)>0) {
		    		foreach ($arr_property_type as $key => $value) {
		    			$property_data[$key]['id']   = $value['id'];
		    			$property_data[$key]['name'] = $value['name'];
		    			$property_data[$key]['slug'] = str_slug($value['name'],'-');
		    		}
		    	}

		    	if (isset($arr_category) && count($arr_category)>0) {
		    		foreach ($arr_category as $key1 => $value1) {
		    			$category_data[$key1]['id']            = $value1['id'];
		    			$category_data[$key1]['category_name'] = $value1['category_name'];
		    		}
		    	}
		    	if (isset($currency_list) && count($currency_list)>0) {
		    		foreach ($currency_list as $key2 => $value2) {
		    			$currency_data[$key2]['id']   		  = $value2['id'];
		    			$currency_data[$key2]['currency'] 	  = $value2['currency'];
		    			$currency_data[$key2]['currency_code']= $value2['currency_code'];
		    		}
		    	}
		    }
    	}
    	else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }

    	$arr_data['property_data'] = $property_data;
    	$arr_data['category_data'] = $category_data;
    	$arr_data['currency_data'] = $currency_data;
    	return $this->build_response($status,$message,$arr_data);
    }

   	public function store_property_step1(Request $request)
   	{
   		$status = $message = $property_status = $property_image_status = $property_beds_arrangment = $property_name_slug = $latitude = $longitude = $city = $state = $country = '';
        $arr_rules = $form_data = $arr_property = $arr_property_image = $arr_images = $arr_bedrooms = $arr_data = $double_bed_arr = $queen_bed_arr = $single_bed_arr = $sofa_bed_arr = array();

        $user_id       = $this->user_id;
        $form_data     = $request->all();
        $property_slug = get_property_type_slug($form_data['property_type']);

        if (isset($user_id) && $user_id != '') {
	        $arr_rules['property_name'] = "required";
	        $arr_rules['description']   = "required";
	        $arr_rules['property_type'] = "required";
	        $arr_rules['address']       = "required";
	        $arr_rules['currency']      = "required";
	        $arr_rules['postal_code']   = "required";

	        if ($property_slug == 'warehouse') {
				$arr_rules['property_working_status'] = "required";
				$arr_rules['property_area']           = "required";
				$arr_rules['total_build_area']        = "required";
				$arr_rules['custom_type']             = "required";
				$arr_rules['management']              = "required";
				$arr_rules['good_storage']            = "required";
				$arr_rules['admin_area']              = "required";
				$arr_rules['price_per_sqft']          = "required";
				$arr_rules['build_type']              = "required";
	        }
	        else if ($property_slug == 'office-space') {
				$arr_rules['property_working_status'] = "required";
				$arr_rules['property_area']           = "required";
				$arr_rules['total_build_area']        = "required";
				$arr_rules['admin_area']              = "required";
				$arr_rules['build_type']              = "required";
	        }
	        else {
	            $arr_rules['no_of_guest']    = "required";
	            $arr_rules['no_of_bedrooms'] = "required";
	            $arr_rules['bathrooms']      = "required";
	            $arr_rules['no_of_beds']     = "required";
	            $arr_rules['price']          = "required";
	        }

	        $validator = Validator::make($request->all(),$arr_rules);
	        if($validator->fails()) {
				$status  = 'error';
				$message = 'Fill all required fields';
	        }
	        else {
		        if(isset($form_data['property_name'])) {
		            $res = $this->PropertyModel->where('property_name',$form_data['property_name'])->get();
		            if(count($res)>0) {
						$property_name_slug = str_slug($form_data['property_name'].count($res));
			        }
			        else {
						$property_name_slug = str_slug($form_data['property_name']);
			        }
		        }
			 
			    /* if(count($res)>0)
		        {
		      		$status   = 'error';
		        	$message  = 'Property name already exist, Please try another property name.';
		      	}
		      	else
		      	{*/
		        
		        if($form_data['address'] != '') {
					$latitude  = $request->input('latitude');
					$longitude = $request->input('longitude');
					$city      = $request->input('city');
					$state     = $request->input('state');
					$country   = $request->input('country');

					/*$cityclean = str_replace (" ", "+", $form_data['address']);
		            $details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $cityclean . "&sensor=false";
					$ch = curl_init();
		            curl_setopt($ch, CURLOPT_URL, $details_url);
		            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

		            $content = curl_exec($ch);
		            curl_close($ch);
		            $metadata = json_decode($content, true); //json decoder

		            $result=array();
		            if(sizeof($metadata['results']))
		            {
		                $result = $metadata['results'][0];
		                if(sizeof($result)>0)
		                {
		                  $latitude     = $request->input('latitude')!=''&&$request->input('latitude')!=null?$request->input('latitude'):$result['geometry']['location']['lat'];
		                  $longitude    = $request->input('longitude')!=''&&$request->input('longitude')!=null?$request->input('longitude'):$result[0]['geometry']['location']['lng'];

		                  $city    = $request->input('city')!=''&&$request->input('city')!=null?$request->input('city'):$result['address_components'][0]['long_name'];

		                  $state    = $request->input('state')!=''&&$request->input('state')!=null?$request->input('state'):$result['address_components'][2]['long_name'];
		                  $country    = $request->input('country')!=''&&$request->input('country')!=null?$request->input('country'):$result['address_components'][3]['long_name'];
		                }
		            }*/
		        }

		        /*Changes done by kavita*/
		        $currency_id                              = $request->input('currency');
		        $currency_details                         = isset($currency_id) ? get_currency($currency_id) : '';
		        $arr_property['user_id']                  = $this->user_id;
		        $arr_property['category_id']              = isset($form_data['property_category'])?$form_data['property_category']:'';
		        $arr_property['property_type_id']         = isset($form_data['property_type'])?$form_data['property_type']:'';
		        $arr_property['property_name']            = isset($form_data['property_name'])?$form_data['property_name']:'';
		        $arr_property['description']              = isset($form_data['description'])?$form_data['description']:'';
		        $arr_property['address']                  = isset($form_data['address'])?$form_data['address']:'';
		        $arr_property['city']                     = isset($form_data['city'])?$form_data['city']:$city;
		        $arr_property['country']                  = isset($form_data['country'])?$form_data['country']:$country;
		        $arr_property['state']                    = isset($form_data['state'])?$form_data['state']:$state;
		        $arr_property['postal_code']              = isset($form_data['postal_code'])?$form_data['postal_code']:'';
		        $arr_property['property_latitude']        = $latitude;
		        $arr_property['property_longitude']       = $longitude;
		        $arr_property['number_of_guest']          = isset($form_data['no_of_guest'])?$form_data['no_of_guest']:'';
		        $arr_property['number_of_bedrooms']       = isset($form_data['no_of_bedrooms'])?$form_data['no_of_bedrooms']:'';
		        $arr_property['number_of_bathrooms']      = isset($form_data['bathrooms'])?$form_data['bathrooms']:'';
		        $arr_property['number_of_beds']           = isset($form_data['no_of_beds'])?$form_data['no_of_beds']:'';
		        $arr_property['price_per_night']          = isset($form_data['price'])?$form_data['price']:'';
		        $arr_property['currency']                 = isset($currency_details['currency'])?$currency_details['currency']:'';
		        $arr_property['currency_code']            = isset($currency_details['currency_code'])?$currency_details['currency_code']:'';
		        $arr_property['property_working_status']  = isset($form_data['property_working_status'])?$form_data['property_working_status']:'';
		        $arr_property['property_area']            = isset($form_data['property_area'])?$form_data['property_area']:'';
		        $arr_property['total_plot_area']          = isset($form_data['total_plot_area'])?$form_data['total_plot_area']:'';
		        $arr_property['total_build_area']         = isset($form_data['total_build_area'])?$form_data['total_build_area']:'';
		        $arr_property['custom_type']              = isset($form_data['custom_type'])?$form_data['custom_type']:'';
		        $arr_property['management']               = isset($form_data['management'])?$form_data['management']:'';
		        $arr_property['good_storage']             = isset($form_data['good_storage'])?$form_data['good_storage']:'';
		        $arr_property['admin_area']               = isset($form_data['admin_area'])?$form_data['admin_area']:'';
		        $arr_property['build_type']               = isset($form_data['build_type'])?$form_data['build_type']:'';
		        $arr_property['property_remark']          = isset($form_data['property_remark'])?$form_data['property_remark']:'';
		        $arr_property['price_per_sqft']           = isset($form_data['price_per_sqft'])?$form_data['price_per_sqft']:'';
		        $arr_property['price_per']                = isset($form_data['price_per'])?$form_data['price_per']:'';
		        $arr_property['price_per_office']         = isset($form_data['price_per_office'])?$form_data['price_per_office']:'';
		        $arr_property['no_of_slots']              = isset($form_data['no_of_slots'])?$form_data['no_of_slots']:'';
		        $arr_property['available_no_of_slots']    = isset($form_data['no_of_slots'])?$form_data['no_of_slots']:'';
		        $arr_property['no_of_employee']           = isset($form_data['no_of_employee'])?$form_data['no_of_employee']:'';
		        $arr_property['available_no_of_employee'] = isset($form_data['no_of_employee'])?$form_data['no_of_employee']:'';

		        $arr_property['employee']                 = isset($form_data['office_person'])?$form_data['office_person']:'off';
		        $arr_property['room']                     = isset($form_data['office_private_room'])?$form_data['office_private_room']:'off';
		        $arr_property['desk']                     = isset($form_data['office_dedicated_desk'])?$form_data['office_dedicated_desk']:'off';
		        $arr_property['cubicles']                 = isset($form_data['office_cubicles'])?$form_data['office_cubicles']:'off';
		        $arr_property['no_of_employee']           = isset($form_data['no_of_employee'])?$form_data['no_of_employee']:'';
		        $arr_property['no_of_room']               = isset($form_data['no_of_room'])?$form_data['no_of_room']:'';
		        $arr_property['no_of_desk']               = isset($form_data['no_of_desk'])?$form_data['no_of_desk']:'';
		        $arr_property['no_of_cubicles']           = isset($form_data['no_of_cubicles'])?$form_data['no_of_cubicles']:'';
		        $arr_property['room_price']               = isset($form_data['room_price'])?$form_data['room_price']:'';
		        $arr_property['desk_price']               = isset($form_data['desk_price'])?$form_data['desk_price']:'';
		        $arr_property['cubicles_price']           = isset($form_data['cubicles_price'])?$form_data['cubicles_price']:'';

		        $arr_property['property_name_slug']       = $property_name_slug;
		        $arr_property['property_status']          = 2;
		        $arr_property['admin_status']             = 1;
		        $property_status = $this->PropertyModel->create($arr_property);
		        /*End*/

		        if(isset($form_data['no_of_bedrooms'])) {
		        	$double_bed = json_decode($request->input('double_bed'));
			        if (isset($double_bed) && $double_bed != '') {
			        	foreach ($double_bed as $key => $value) {
				       		$double_bed_arr[$key] = $value->double_bed;
				       	}
			        }

				    $single_bed = json_decode($request->input('single_bed'));
			       	if (isset($double_bed) && count($single_bed)>0) {
				       	foreach ($single_bed as $key1 => $value1) {
				       		$single_bed_arr[$key1] = $value1->single_bed;
				       	}
				    }

			       	$queen_bed = json_decode($request->input('queen_bed'));
			       	if (isset($queen_bed) && count($queen_bed)>0 ) {
				       	foreach ($queen_bed as $key2 => $value2) {
				       		$queen_bed_arr[$key2] = $value2->queen_bed;
				       	}
				    }

			       	$sofa_bed = json_decode($request->input('sofa_bed'));
			       	if (isset($sofa_bed) && count($sofa_bed)>0) {
				       	foreach ($sofa_bed as $key3 => $value3) {
				       		$sofa_bed_arr[$key3] = $value3->sofa_bed;
				       	}
				    }

		            $no_of_bedrooms = $form_data['no_of_bedrooms'];
		            for( $i = 0; $i < $no_of_bedrooms; $i++ ) {
						$j                              = $i + 1;
						$arr_bedrooms['double_bed']     = isset($double_bed_arr[$i])?$double_bed_arr[$i]:'';
						$arr_bedrooms['single_bed']     = isset($single_bed_arr[$i])?$single_bed_arr[$i]:'';
						$arr_bedrooms['queen_bed']      = isset($queen_bed_arr[$i])?$queen_bed_arr[$i]:'';
						$arr_bedrooms['sofa_bed']       = isset($sofa_bed_arr[$i])?$sofa_bed_arr[$i]:'';
						$arr_bedrooms['no_of_bedrooms'] = $j;
						$arr_bedrooms['property_id']    = isset($property_status->id)?$property_status->id:'';
						$property_beds_arrangment       = $this->PropertyBedsArrangmentModel->create($arr_bedrooms);
		            }
		        }

		        if($property_status || $property_beds_arrangment) {
					$arr_data['property_type_slug'] = get_property_type_slug($property_status->property_type_id);
					$arr_data['property_id']        = $property_status->id;
		         	
					$status  = 'success';
					$message = 'First step of property is created successfully.';
		        }
		        else {
					$status  = 'error';
					$message = 'Error occure while adding record of property.';
		        }
		    }
		}
		else {
			$status  = 'error';
			$message = 'Token expired, user not found.';
        }
        return $this->build_response($status,$message,$arr_data);
   	}

   	public function get_aminities(Request $request)
    {
   		$arr_aminities = $amenities_data = $arr_rules = $rules_data = [];
   		$obj_aminities = $status = $message = '';

   		$property_id = $request->input('property_id');
   		if ($property_id != "") {
   			$obj_property     = $this->PropertyModel->where('id','=',$property_id)->first();
   			$property_type_id = isset($obj_property->property_type_id)?$obj_property->property_type_id:'';
   			$obj_aminities    = $this->AmenitiesModel->where('propertytype_id','=',$property_type_id)->get();

   			if (isset($obj_aminities) && count($obj_aminities)>0) {
   				$arr_aminities = $obj_aminities->toArray();

   				foreach ($arr_aminities as $key => $value) {
					$amenities_data[$key]['id']           = $value['id'];
					$amenities_data[$key]['aminity_name'] = $value['aminity_name'];
   				}
   			}

	        $obj_rules = $this->PropertyRulesModel->where('property_id','=',$property_id)->get();
	        if (isset($obj_rules) && count($obj_rules)>0) {
	            $arr_rules = $obj_rules->toArray();
	            foreach ($arr_rules as $key1 => $value1) {
   					$rules_data[$key1]['id']    = $value1['id'];
   					$rules_data[$key1]['rules'] = $value1['rules'];
   				}
	        }

	        if (count($obj_rules)>0 || count($obj_aminities)>0 ) {
				$status  = 'success';
				$message = 'Record get successfully';
	        } else {
				$status  = 'error';
				$message = 'Record Not Found';
	        }
   		}
   		$arr_data['amenities_data'] = $amenities_data;
   		$arr_data['rules_data'] 	= $rules_data;
   		return $this->build_response($status,$message,$arr_data);
    }

    public function store_property_step2(Request $request)
   	{
      	$arr_rules = $form_data = $arr_property = $arr_unavailibility = $arr_aminities = $to_date = array();
      	$aminities_status = $unavailaibility_status = $status = $message = '';
      	$form_data = $request->all();

      	$property_id = $request->input('property_id');
      	if ($property_id != '') {
	      	$j = 0;
	       	if(count(Input::file('property_images'))>0 ) {
	          $image_count = count(Input::file('property_images'));

	          	for($i=0; $i < $image_count; $i++) {
		            if($request->file('property_images')[$i] != '' && $request->file('property_images')[$i] != null) {
						$image_file = $request->file('property_images')[$i];
						$filename   = rand(1111,9999);
						$extension  = $image_file->getClientOriginalExtension();
						$imageName  = sha1(uniqid().$filename.uniqid()).'.'.$extension;

						if((strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'png') && $j<10) {
							$image_file->move($this->property_image_base_path,$imageName);
							$property_image_status = $this->PropertyImagesModel->create(['property_id'=>$property_id,'image' => $imageName]);
							$j++;
						}
		            }
	          	}
	       	} else {
	       		$status  = 'error';
		        $message = 'Please upload atleast one image.';
	       	}

	       	$aminities = json_decode($request->input('aminities'));

	      	if(isset($aminities) && sizeof($aminities) > 0) {
	         	foreach ($aminities as $value_am) {
		            $arr_aminities['property_id']  = $property_id;
		            $arr_aminities['aminities_id'] = $value_am->aminities;
		            $aminities_status              = $this->PropertyAminitiesModel->create($arr_aminities);
		            $arr_aminities = [];
	         	}
	      	}

			$from_date   = json_decode($request->input('from_date'));
			$to_date_arr = json_decode($request->input('to_date'));
	      	if (isset($to_date_arr) && count($to_date_arr)>0) {
	      		foreach ($to_date_arr as $ToDateKey => $ToDateValue) {
					$to_date[$ToDateKey] = $ToDateValue->to_date;
		      	}
	      	}
	      	if(isset($from_date) && $from_date != "" && isset($to_date) && $to_date != "") {
	          	foreach ($from_date as $key1 => $value1) {
					$arr_unavailibility['property_id'] = $property_id;
					$arr_unavailibility['from_date']   = isset($value1->from_date) && !empty($value1->from_date) ? date('Y-m-d',strtotime($value1->from_date)) : '';
					$arr_unavailibility['to_date']     = isset($to_date[$key1]) && !empty($to_date[$key1]) ? date('Y-m-d',strtotime($to_date[$key1])):'';
					$unavailaibility_status            = $this->PropertyUnavailabilityModel->create($arr_unavailibility);
	          	}
	      	}

		    $arr_property['property_status']          = 1;
		    $arr_property['nearest_railway_station']  = isset($form_data['nearest_railway_station'])?$form_data['nearest_railway_station']:'';
		    $arr_property['nearest_national_highway'] = isset($form_data['nearest_national_highway'])?$form_data['nearest_national_highway']:'';
		    $arr_property['nearest_bus_stop']         = isset($form_data['nearest_bus_stop'])?$form_data['nearest_bus_stop']:'';
		    $arr_property['working_hours']            = isset($form_data['working_hours'])?$form_data['working_hours']:'';
		    $arr_property['working_days']             = isset($form_data['working_days'])?$form_data['working_days']:'';
      	
         	$this->PropertyModel->where('id','=',$property_id)->update($arr_property); /*all steps of property are completed*/
	        $status  = 'success';
	        $message = 'property details are added successfully.';
	    }
	    else {
	        $status  = 'error';
	        $message = 'Property id should not be blank';
      	}
        return $this->build_response($status,$message);
   	}

   	public function add_rules(Request $request)
   	{
   		$form_data = $arr_data = $rules_data = $rule_arr = [];
   		$rule_status = $status = $message = '';
   		
   		$form_data = $request->all();
   		if ($form_data['property_id'] != '') {
   			$check_rule_status = $this->PropertyRulesModel->where('property_id',$form_data['property_id'])->where('rules', '=', $form_data['rules'])->first();    

   			if (count($check_rule_status) > 0) {
				$status  = 'error';
				$message = 'Rule already added.';
   			} else {
				$rule_arr['property_id'] = $form_data['property_id'];
				$rule_arr['rules']       = isset($form_data['rules']) ? $form_data['rules'] : '';
   				$rule_status = $this->PropertyRulesModel->create($rule_arr);

   				if($rule_status) {
   					$status  = 'success';
   					$message = 'Property rules added successfully.';
   				} else {
   					$status  = 'error';
   					$message = 'Error while adding rules.';
   				}
   			}

   			$obj_rules = $this->PropertyRulesModel->where('property_id','=',$form_data['property_id'])->get();
   			if (isset($obj_rules) && count($obj_rules) > 0) {
   				$arr_rules = $obj_rules->toArray();
   				foreach ($arr_rules as $key1 => $value1) {
   					$rules_data[$key1]['id']    = $value1['id'];
   					$rules_data[$key1]['rules'] = $value1['rules'];
   				}
   			}
	   		$arr_data['rules_data'] = $rules_data;
   		} else {
   			$status  = 'error';
   			$message = 'Property id should not be blank';
   		}
   		return $this->build_response($status, $message, $arr_data);
   	}

   	public function update_rules(Request $request)
   	{
   		$form_data = $arr_update = $arr_json = $arr_data = [];
   		$status = $message = '';

   		$form_data     = $request->all();
   		$house_rule_id = isset($form_data['rule_id']) ? $form_data['rule_id'] : '';

   		$check_update_status = $this->PropertyRulesModel->where('id','!=',$house_rule_id)->where('property_id','=',$form_data['property_id'])->where('rules','=',$form_data['rules'])->first();      

   		if(count($check_update_status)>0) {
   			$status  = 'error';
   			$message = 'Rule already added.';
   		} else {
   			if (isset($house_rule_id) && $house_rule_id !='') {
   				$update_status = $this->PropertyRulesModel->where('id','=',$house_rule_id)->update(['rules'=>$form_data['rules']]);

   				$status  = 'success';
   				$message = 'Property rules updated successfully.';

		      	/*if($update_status) {
   				$status  = 'success';
   				$message = 'Property rules updated successfully.';
		      	} else {
		          	$status  = 'error';
		          	$message = 'Error occure while updating rules.';
		      	}*/

   			} else {
   				$status  = 'error';
   				$message = 'Rule id should not be blank';
   			}
   		}
   		return $this->build_response($status, $message, $arr_data);
   	}

   	public function edit_rules(Request $request)
   	{
      	$arr_rules = $rules_data = $arr_data = [];
      	$status = $message = '';

     	$house_rule_id = $request->input('rule_id');
      	$obj_rules     = $this->PropertyRulesModel->where('id','=',$house_rule_id)->first();

      	if (isset($house_rule_id) && $house_rule_id != '') {
	      	if(isset($obj_rules) && count($obj_rules)>0) {
				
				$arr_rules           = $obj_rules->toArray();
				$rules_data['id']    = $arr_rules['id'];
				$rules_data['rules'] = $arr_rules['rules'];

				$status  = 'success';
				$message = 'Record get successfully';
	      	} else {
				$status  = 'erorr';
				$message = 'Record not found';
	      	}
	    } else {
				$status  = 'error';
				$message = 'Rule id should not be blank';
      	}
	    $arr_data['rules_data'] = $rules_data;
      	return $this->build_response($status,$message,$arr_data);
   	}

   	public function delete_rules(Request $request)
   	{
		$arr_rules = $arr_json = [];
		$status = $message = '';

      	$house_rule_id = $request->input('rule_id');
      	if (isset($house_rule_id) && $house_rule_id != '') {
	      	$delete_status = $this->PropertyRulesModel->where('id','=',$house_rule_id)->delete();
	      	if($delete_status) {
	         	$status  = 'success';
	         	$message = 'Property rule deleted successfully.';
	      	} else {
	         	$status  = 'error';
	         	$message = 'Error while delete a rule.';
	      	}
	    }
      	return $this->build_response($status,$message);
   	}

   	public function edit_property_step1(Request $request)
    {
        $arr_property = $arr_property_type = $arr_category = $property_data = $currency_data = $bed_data = $arr_data = [];
        $status = $message = '';

        $property_id = $request->input('property_id');
        if (isset($property_id) && $property_id != '') {
	        $arr_property_type  = get_property_type();
	        $currency_list      = get_currency();

	        if (isset($arr_property_type) && count($arr_property_type)>0) {
	    		foreach ($arr_property_type as $key => $value) {
					$property_type_data[$key]['id']   = $value['id'];
					$property_type_data[$key]['name'] = $value['name'];
	    		}
	    	}
	    	if (isset($currency_list) && count($currency_list)>0) {
	    		foreach ($currency_list as $key1 => $value1) {
					$currency_data[$key1]['id']            = $value1['id'];
					$currency_data[$key1]['currency']      = $value1['currency'];
					$currency_data[$key1]['currency_code'] = $value1['currency_code'];
	    		}
	    	}

	        $obj_property = $this->PropertyModel->where('id','=',$property_id)->with(['property_bed_arrangment'])->first();
	        if(isset($obj_property) && count($obj_property)>0) {
	        	$status  = 'success';
	         	$message = 'Record get successfully.';

		        $arr_property                             = $obj_property->toArray();
		        $property_data['property_id']             = $arr_property['id'];
		        $property_data['user_id']                 = $arr_property['user_id'];
		        $property_data['property_type']           = $arr_property['property_type_id'];
		        $property_data['property_type_slug']      = get_property_type_slug($arr_property['property_type_id']);
		        $property_data['property_name']           = $arr_property['property_name'];
		        $property_data['description']             = $arr_property['description'];
		        $property_data['address']                 = $arr_property['address'];
		        $property_data['city']                    = $arr_property['city'];
		        $property_data['state']                   = $arr_property['state'];
		        $property_data['country']                 = $arr_property['country'];
		        $property_data['postal_code']             = $arr_property['postal_code'];
		        $property_data['latitude']                = $arr_property['property_latitude'];
		        $property_data['longitude']               = $arr_property['property_longitude'];
		        $property_data['no_of_guest']             = $arr_property['number_of_guest'];
		        $property_data['no_of_bedrooms']          = $arr_property['number_of_bedrooms'];
		        $property_data['bathrooms']               = $arr_property['number_of_bathrooms'];
		        $property_data['no_of_beds']              = $arr_property['number_of_beds'];
		        $property_data['price']                   = $arr_property['price_per_night'];
		        $property_data['currency']                = $arr_property['currency'];

	            /*Changes by kavita*/
		        $property_data['property_working_status'] = $arr_property['property_working_status'];
		        $property_data['property_area']           = $arr_property['property_area'];
		        $property_data['total_plot_area']         = $arr_property['total_plot_area'];
		        $property_data['total_build_area']        = $arr_property['total_build_area'];
		        $property_data['custom_type']             = $arr_property['custom_type'];
		        $property_data['management']              = $arr_property['management'];
		        $property_data['good_storage']            = $arr_property['good_storage'];
		        $property_data['admin_area']              = $arr_property['admin_area'];
		        $property_data['build_type']              = $arr_property['build_type'];
		        $property_data['property_remark']         = $arr_property['property_remark'];
		        $property_data['price_per_sqft']          = $arr_property['price_per_sqft'];
		        $property_data['price_per']               = $arr_property['price_per'];
		        $property_data['price_per_office']        = $arr_property['price_per_office'];
		        $property_data['no_of_slots']             = $arr_property['no_of_slots'];
		        $property_data['no_of_employee']          = $arr_property['no_of_employee'];

		        $property_data['employee']                = $arr_property['employee'];
		        $property_data['room']                    = $arr_property['room'];
		        $property_data['desk']                    = $arr_property['desk'];
		        $property_data['cubicles']                = $arr_property['cubicles'];
		        $property_data['no_of_employee']          = $arr_property['no_of_employee'];
		        $property_data['no_of_room']              = $arr_property['no_of_room'];
		        $property_data['no_of_desk']              = $arr_property['no_of_desk'];
		        $property_data['no_of_cubicles']          = $arr_property['no_of_cubicles'];
		        $property_data['room_price']              = $arr_property['room_price'];
		        $property_data['desk_price']              = $arr_property['desk_price'];
		        $property_data['cubicles_price']          = $arr_property['cubicles_price'];
		        /*End*/

	            if(isset($arr_property['property_bed_arrangment']) && sizeof($arr_property['property_bed_arrangment'])>0) {
	            	foreach($arr_property['property_bed_arrangment'] as $key => $bed_arrangment) {
	            		if( $bed_arrangment['double_bed'] == 1) {
	            			$double_bed = "Yes";
	            		}
	            		else if( $bed_arrangment['double_bed'] == 2) {
	            			$double_bed = "No";
	            		}
	            		else if( $bed_arrangment['double_bed'] == 3) {
	            			$double_bed = "Multiple";
	            		}

	            		if( $bed_arrangment['single_bed'] == 1) {
	            			$single_bed = "Yes";
	            		}
	            		else if( $bed_arrangment['single_bed'] == 2) {
	            			$single_bed = "No";
	            		}
	            		else if( $bed_arrangment['single_bed'] == 3) {
	            			$single_bed = "Multiple";
	            		}

	            		if( $bed_arrangment['queen_bed'] == 1) {
	            			$queen_bed = "Yes";
	            		}
	            		else if( $bed_arrangment['queen_bed'] == 2) {
	            			$queen_bed = "No";
	            		}
	            		else if( $bed_arrangment['queen_bed'] == 3) {
	            			$queen_bed = "Multiple";
	            		}

	            		if( $bed_arrangment['sofa_bed'] == 1) {
	            			$sofa_bed = "Yes";
	            		}
	            		else if( $bed_arrangment['sofa_bed'] == 2) {
	            			$sofa_bed = "No";
	            		}
	            		else if( $bed_arrangment['sofa_bed'] == 3) {
	            			$sofa_bed = "Multiple";
	            		}

						$bed_data[$key]['property_id'] = $bed_arrangment['property_id'];
						$bed_data[$key]['double_bed']  = $bed_arrangment['double_bed'];
						$bed_data[$key]['single_bed']  = $bed_arrangment['single_bed'];
						$bed_data[$key]['queen_bed']   = $bed_arrangment['queen_bed'];
						$bed_data[$key]['sofa_bed']    = $bed_arrangment['sofa_bed'];
	            	}
	            }
	            $property_data['property_bed_arrangment'] = $bed_data;
	        }
	        else {
	         	$status  = 'error';
	         	$message = 'Record Not Found.';
	      	}
	    }
	    else {
         	$status  = 'error';
         	$message = 'Property id should not be blank.';
      	}

		$arr_data['property_data']      = $property_data;
		$arr_data['currency_data']      = $currency_data;
		$arr_data['property_type_data'] = $property_type_data;
		return $this->build_response($status,$message,$arr_data);
    }

    public function update_property_step1(Request $request)
    {
        $property_status = $property_image_status = $property_beds_arrangment = $latitude = $longitude = $city = $state = $country = '';
        $arr_rules = $form_data = $arr_property = $arr_images = $arr_bedrooms = $arr_data = $double_bed_arr = $queen_bed_arr = $single_bed_arr = $sofa_bed_arr = $res = array();

        $user_id   = $this->user_id;
        $form_data = $request->all();

        $property_slug = get_property_type_slug($form_data['property_type']);
        if (isset($user_id) && $user_id != '') {
			$arr_rules['property_name'] = "required";
			$arr_rules['description']   = "required";
			$arr_rules['property_type'] = "required";
			$arr_rules['address']       = "required";
			$arr_rules['currency']      = "required";
			$arr_rules['postal_code']   = "required";

	        if ($property_slug == 'warehouse') {
				$arr_rules['property_working_status'] = "required";
				$arr_rules['property_area']           = "required";
				$arr_rules['total_build_area']        = "required";
				$arr_rules['custom_type']             = "required";
				$arr_rules['management']              = "required";
				$arr_rules['good_storage']            = "required";
				$arr_rules['admin_area']              = "required";
				$arr_rules['price_per_sqft']          = "required";
				$arr_rules['build_type']              = "required";
	        }
	        else if ($property_slug == 'office-space') {
	            $arr_rules['property_working_status'] = "required";
	            $arr_rules['property_area']           = "required";
	            $arr_rules['total_build_area']        = "required";
	            $arr_rules['admin_area']              = "required";
	            $arr_rules['build_type']              = "required";
	        }
	        else {
				$arr_rules['no_of_guest']    = "required";
				$arr_rules['no_of_bedrooms'] = "required";
				$arr_rules['bathrooms']      = "required";
				$arr_rules['no_of_beds']     = "required";
				$arr_rules['price']          = "required";
	        }

	        $validator = Validator::make($request->all(),$arr_rules);
	        if($validator->fails()) {
				$status  = 'error';
				$message = 'Fill all required fields';
	        }

	        $property_id = isset($form_data['property_id'])?$form_data['property_id']:'';
	        if(isset($form_data['property_name'])) {
				$res = $this->PropertyModel->where('property_name',$form_data['property_name'])->where('id','!=',$property_id)->get();
	        }
	        if(count($res)>0) {
				$property_name_slug = str_slug($form_data['property_name'].count($res));
	        }
	        else {
				$property_name_slug = str_slug($form_data['property_name']);
	        }

	        if($form_data['address'] != '') {
				$latitude  = $request->input('latitude');
				$longitude = $request->input('longitude');
				$city      = $request->input('city');
				$state     = $request->input('state');
				$country   = $request->input('country');
				
				/*$cityclean = str_replace (" ", "+", $form_data['address']);
				$details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $cityclean . "&sensor=false";
				$ch = curl_init();
	            
	            curl_setopt($ch, CURLOPT_URL, $details_url);
	            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	            $content = curl_exec($ch);
	            curl_close($ch);
	            $metadata = json_decode($content, true); //json decoder
	            $result = array();

	            if(count($metadata['results'])) {
	                $result = $metadata['results'][0];
	                if(sizeof($result)>0) {
						$city      = $request->input('city') != '' && $request->input('city') != null ? $request->input('city') : $result['address_components'][0]['long_name'];
						$state     = $request->input('state') != '' && $request->input('state') != null ? $request->input('state') : $result['address_components'][2]['long_name'];
						$country   = $request->input('country') != '' &&$request->input('country') != null ? $request->input('country') : $result['address_components'][3]['long_name'];
						$latitude  = $request->input('lat') != '' && $request->input('lat') != null ? $request->input('lat') : $result['geometry']['location']['lat'];
						$longitude = $request->input('long') != '' && $request->input('long') != null ? $request->input('long') : $result['geometry']['location']['lng'];
	                }
	            }*/
	        }

	        /*Change by kavita*/
	        $currency_details                         = isset($form_data['currency']) ? get_currency($form_data['currency']) : '';
	        $obj_property                             = $this->PropertyModel->where('id','=',$property_id);
	        $old_no_of_bedrooms                       = isset($obj_property->no_of_bedrooms) ? $obj_property->no_of_bedrooms : '';
	        $arr_property['user_id']                  = $this->user_id;
	        $arr_property['category_id']              = isset($form_data['property_category'])?$form_data['property_category']:'';
	        $arr_property['property_type_id']         = isset($form_data['property_type'])?$form_data['property_type']:'';
	        $arr_property['property_name']            = isset($form_data['property_name'])?$form_data['property_name']:'';
	        $arr_property['description']              = isset($form_data['description'])?$form_data['description']:'';
	        $arr_property['address']                  = isset($form_data['address'])?$form_data['address']:'';
	        $arr_property['city']                     = isset($form_data['city'])?$form_data['city']:$city;
	        $arr_property['country']                  = isset($form_data['country'])?$form_data['country']:$country;
	        $arr_property['state']                    = isset($form_data['state'])?$form_data['state']:$state;
	        $arr_property['postal_code']              = isset($form_data['postal_code'])?$form_data['postal_code']:'';
	        $arr_property['property_latitude']        = $latitude;
	        $arr_property['property_longitude']       = $longitude;
	        $arr_property['number_of_guest']          = isset($form_data['no_of_guest'])?$form_data['no_of_guest']:'';
	        $arr_property['number_of_bedrooms']       = isset($form_data['no_of_bedrooms'])?$form_data['no_of_bedrooms']:'';
	        $arr_property['number_of_bathrooms']      = isset($form_data['bathrooms'])?$form_data['bathrooms']:'';
	        $arr_property['number_of_beds']           = isset($form_data['no_of_beds'])?$form_data['no_of_beds']:'';
	        $arr_property['price_per_night']          = isset($form_data['price'])?$form_data['price']:'';
	        $arr_property['currency']                 = isset($currency_details['currency'])?$currency_details['currency']:'';
	        $arr_property['currency_code']            = isset($currency_details['currency_code'])?$currency_details['currency_code']:'';
	        $arr_property['property_working_status']  = isset($form_data['property_working_status'])?$form_data['property_working_status']:'';
	        $arr_property['property_area']            = isset($form_data['property_area'])?$form_data['property_area']:'';
	        $arr_property['total_plot_area']          = isset($form_data['total_plot_area'])?$form_data['total_plot_area']:'';
	        $arr_property['total_build_area']         = isset($form_data['total_build_area'])?$form_data['total_build_area']:'';
	        $arr_property['custom_type']              = isset($form_data['custom_type'])?$form_data['custom_type']:'';
	        $arr_property['management']               = isset($form_data['management'])?$form_data['management']:'';
	        $arr_property['good_storage']             = isset($form_data['good_storage'])?$form_data['good_storage']:'';
	        $arr_property['admin_area']               = isset($form_data['admin_area'])?$form_data['admin_area']:'';
	        $arr_property['build_type']               = isset($form_data['build_type'])?$form_data['build_type']:'';
	        $arr_property['property_remark']          = isset($form_data['property_remark'])?$form_data['property_remark']:'';
	        $arr_property['price_per_sqft']           = isset($form_data['price_per_sqft'])?$form_data['price_per_sqft']:'';
	        $arr_property['price_per']                = isset($form_data['price_per'])?$form_data['price_per']:'';
	        $arr_property['price_per_office']         = isset($form_data['price_per_office'])?$form_data['price_per_office']:'';
	        $arr_property['no_of_slots']              = isset($form_data['no_of_slots'])?$form_data['no_of_slots']:'';
	        $arr_property['no_of_employee']           = isset($form_data['no_of_employee'])?$form_data['no_of_employee']:'';
	        $arr_property['available_no_of_employee'] = isset($form_data['no_of_employee'])?$form_data['no_of_employee']:'';
	        $arr_property['property_name_slug']       = $property_name_slug;
	        $arr_property['admin_status']             = '1';
	        $arr_property['employee']                 = isset($form_data['office_person'])?$form_data['office_person']:'off';
	        $arr_property['room']                     = isset($form_data['office_private_room'])?$form_data['office_private_room']:'off';
	        $arr_property['desk']                     = isset($form_data['office_dedicated_desk'])?$form_data['office_dedicated_desk']:'off';
	        $arr_property['cubicles']                 = isset($form_data['office_cubicles'])?$form_data['office_cubicles']:'off';
	        $arr_property['no_of_employee']           = isset($form_data['no_of_employee'])?$form_data['no_of_employee']:'';
	        $arr_property['no_of_room']               = isset($form_data['no_of_room'])?$form_data['no_of_room']:'';
	        $arr_property['no_of_desk']               = isset($form_data['no_of_desk'])?$form_data['no_of_desk']:'';
	        $arr_property['no_of_cubicles']           = isset($form_data['no_of_cubicles'])?$form_data['no_of_cubicles']:'';
	        $arr_property['room_price']               = isset($form_data['room_price'])?$form_data['room_price']:'';
	        $arr_property['desk_price']               = isset($form_data['desk_price'])?$form_data['desk_price']:'';
	        $arr_property['cubicles_price']           = isset($form_data['cubicles_price'])?$form_data['cubicles_price']:'';
	        $property_status                          = $obj_property->update($arr_property);
	        /*End*/
	      
	        if(isset($form_data['no_of_bedrooms'])) {
	            $delete_property = $this->PropertyBedsArrangmentModel->where('property_id',$property_id)->delete();
	        	$double_bed = json_decode($request->input('double_bed'));
		        if (isset($double_bed) && $double_bed != '') {
		        	foreach ($double_bed as $key => $value) {
			       		$double_bed_arr[$key] = $value->double_bed;
			       	}
		        }

			    $single_bed = json_decode($request->input('single_bed'));
		       	if (isset($double_bed) && count($single_bed)>0) {
			       	foreach ($single_bed as $key1 => $value1) {
			       		$single_bed_arr[$key1] = $value1->single_bed;
			       	}
			    }

		       	$queen_bed = json_decode($request->input('queen_bed'));
		       	if (isset($queen_bed) && count($queen_bed)>0 ) {
			       	foreach ($queen_bed as $key2 => $value2) {
			       		$queen_bed_arr[$key2] = $value2->queen_bed;
			       	}
			    }

		       	$sofa_bed = json_decode($request->input('sofa_bed'));
		       	if (isset($sofa_bed) && count($sofa_bed)>0) {
			       	foreach ($sofa_bed as $key3 => $value3) {
			       		$sofa_bed_arr[$key3] = $value3->sofa_bed;
			       	}
			    }

		        $temp_cnt = $form_data['no_of_bedrooms'];
	            for( $i = 0; $i < $temp_cnt; $i++ ) {
	              	if($form_data['double_bed'][$i] != '' && $form_data['single_bed'][$i] != '' && $form_data['queen_bed'][$i] != '' && $form_data['sofa_bed'][$i] != '') {
		                $j = $i + 1;
						$arr_bedrooms['double_bed']     = isset($double_bed_arr[$i]) ? $double_bed_arr[$i] : '';
						$arr_bedrooms['single_bed']     = isset($single_bed_arr[$i]) ? $single_bed_arr[$i] : '';
						$arr_bedrooms['queen_bed']      = isset($queen_bed_arr[$i]) ? $queen_bed_arr[$i] : '';
						$arr_bedrooms['sofa_bed']       = isset($sofa_bed_arr[$i]) ? $sofa_bed_arr[$i] : '';
						$arr_bedrooms['no_of_bedrooms'] = $j;
						$arr_bedrooms['property_id']    = isset($property_id) ? $property_id : '';
						$property_beds_arrangment       = $this->PropertyBedsArrangmentModel->create($arr_bedrooms);
	              	}
	            }
		    }
	        if($property_status || $property_beds_arrangment) {
	            $status  = 'success';
        		$message = 'First step of property is updated successfully.';
	        }
	        else {
	            $status  = 'error';
        		$message = 'Error occure while updating record of property.';
	        }
		}
		else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }
	    return $this->build_response($status,$message);
    }

    public function edit_property_step2(Request $request)
   	{
        $arr_aminities = $arr_property = $arr_data = $property_data = $images_data = $arr_property_aminities = [];
        $property_id   = '';

        $status  = 'error';
        $message = 'Record not found.';
        
        $property_id = $request->input('property_id');
        $user_id = $this->user_id;
        if (!$user_id) {
    		return $this->build_response("error","Invalid User");
    	} else {
	        if (isset($property_id) && $property_id != '') {
		        $obj_property  = $this->PropertyModel->where('id','=',$property_id)
													->with(['property_unavailability' => function($q1){
														$q1->select('id','property_id','type','from_date','to_date');
													},'property_rules' => function($q1){
														$q1->select('id','property_id','rules');
													},'property_aminities' => function($q1){
														$q1->select('property_id','aminities_id');
													},'property_images', 'all_amenities' => function($q2){
														$q2->select('id','aminity_name','propertytype_id');
													}])
													->first();

		        if(isset($obj_property) && count($obj_property) > 0) {
		            $arr_property = $obj_property->toArray();

		            //Change by kavita
		            $property_data['property_id']              = $arr_property['id'];
		            $property_data['property_unavailability']  = $arr_property['property_unavailability'];
		            $property_data['nearest_railway_station']  = $arr_property['nearest_railway_station'];
		            $property_data['nearest_national_highway'] = $arr_property['nearest_national_highway'];
		            $property_data['nearest_bus_stop']         = $arr_property['nearest_bus_stop'];
		            $property_data['working_hours']            = $arr_property['working_hours'];
		            $property_data['working_days']             = $arr_property['working_days'];
		            $property_data['property_rules']           = $arr_property['property_rules'];
		            // End

		            if (isset($arr_property['all_amenities']) && count($arr_property['all_amenities'])>0) {
		            	foreach ($arr_property['all_amenities'] as $amenities_key => $all_amenities) {
		            		$arr_property['all_amenities'][$amenities_key]['selected'] = 'false';
		            		foreach ($arr_property['property_aminities'] as $property_aminities) {
			            		if ($all_amenities['id'] == $property_aminities['aminities_id']) {
			            			$arr_property['all_amenities'][$amenities_key]['selected'] = 'true';
			            		}
		            		}
		            	}
		            }

		            $property_data['property_aminities'] = $arr_property['all_amenities'];
		            if (isset($arr_property['property_images']) && count($arr_property['property_images'])>0) {
		            	foreach ($arr_property['property_images'] as $ImageKey => $ImageValue) {
		            		$images_data[$ImageKey]['id'] 	 = $ImageValue['id'];
		            		$images_data[$ImageKey]['image'] = $this->property_image_public_path.$ImageValue['image'];
		            	}
		            }
		            $property_data['property_images'] = $images_data;
		        }
		        $status  = 'success';
	         	$message = 'Record get successfully.';
		    }
		    else {
				$status  = 'error';
				$message = 'Property id should not be blank.';
		    }
		    $arr_data['property_data'] = $property_data;
		    return $this->build_response($status, $message, $arr_data);
		}
   	}

   	public function update_property_step2(Request $request)
    {
        $arr_rules = $form_data = $arr_property = $arr_unavailibility = $arr_aminities = $old_to_date = $old_from_date_arr = $old_to_date_arr = array();
        $aminities_status = $unavailaibility_status = '';

        $form_data   = $request->all();
        $property_id = $form_data['property_id'];
        
        if (isset($property_id) && $property_id != '') {
	        /*Add Property images*/
	        $property_image_count = $this->PropertyImagesModel->where('property_id',$property_id)->count();
	        if ($property_image_count > 6) {
	            return $this->build_response('upload_limit', "You can not upload more than 6 images");
	        } else {
	        	if (count($request->file('property_images')) > 0 && $request->file('property_images') != null) {

                    $image_count = count($request->file('property_images'));

                    for ($i = 0; $i < $image_count; $i++) {

                        if ($request->file('property_images')[$i] != '') {
                            $file_extension = strtolower($request->file('property_images')[$i]->getClientOriginalExtension());                                    
                            if (in_array($file_extension,['png','jpg','jpeg','heic'])) {

                                $property_images = time().uniqid().'.'.$file_extension;                    
                                $isUpload = $request->file('property_images')[$i]->move($this->property_image_base_path, $property_images);
                                $insert_arr = array('property_id' => $property_id, 'image' => $property_images);
                                
                                if($isUpload) {
                                    $res = $this->PropertyImagesModel->create($insert_arr);
                                }
                            } else {
				            	return $this->build_response('error', "Invalid File type");
                            }
                        }
                    }
                }
		    }
	        /*add animities*/
	        $aminities = json_decode($request->input('aminities'));
	        if (isset($aminities) && count($aminities)>0) {
	            $this->PropertyAminitiesModel->where('property_id','=',$property_id)->delete();
	            
	            foreach ($aminities as $key => $value) {
	              $arr_aminities['property_id']  = $property_id;
	              $arr_aminities['aminities_id'] = $value->aminities;
	              $aminities_status = $this->PropertyAminitiesModel->create($arr_aminities);
	            }
	        }
	        /*add unavailibility*/
	      	$from_date = json_decode($request->input('from_date'));
	      	$to_date_arr = json_decode($request->input('to_date'));
	      	if (isset($to_date_arr) && count($to_date_arr)>0) {
	      		foreach ($to_date_arr as $ToDateKey => $ToDateValue) {
		      		$to_date[$ToDateKey] = $ToDateValue->to_date;
		      	}
	      	}

	      	if (isset($from_date) && isset($to_date)) {
	      		$this->PropertyUnavailabilityModel->where('property_id','=',$property_id)->delete();
	          	foreach ($from_date as $key1 => $value1) {
	            	$arr_unavailibility['property_id']   = $property_id;
	            	$arr_unavailibility['from_date']     = isset($value1->from_date) && !empty($value1->from_date) ? date('Y-m-d',strtotime($value1->from_date)) : '';
	            	$arr_unavailibility['to_date']       = isset($to_date[$key1]) && !empty($to_date[$key1]) ? date('Y-m-d',strtotime($to_date[$key1])) : '';
	            	$unavailaibility_status = $this->PropertyUnavailabilityModel->create($arr_unavailibility);
	          	}
	      	}

	        $arr_property['property_status']          = 1;
	        $arr_property['nearest_railway_station']  = isset($form_data['nearest_railway_station'])?$form_data['nearest_railway_station']:'';
	        $arr_property['nearest_national_highway'] = isset($form_data['nearest_national_highway'])?$form_data['nearest_national_highway']:'';
	        $arr_property['nearest_bus_stop']         = isset($form_data['nearest_bus_stop'])?$form_data['nearest_bus_stop']:'';
	        $arr_property['working_hours']            = isset($form_data['working_hours'])?$form_data['working_hours']:'';
	        $arr_property['working_days']             = isset($form_data['working_days'])?$form_data['working_days']:'';
   
    		$this->PropertyModel->where('id','=',$property_id)->update($arr_property);/*all steps of property are completed*/
           	$status   = 'success';
            $message  = 'property details are updated successfully.';
	    }
	    else {
	    	$status  = 'error';
         	$message = 'Property id should not be blank.';
	    }
	    return $this->build_response($status,$message);
    }

    public function delete_unavailbility(Request $request)
   	{
		$delete_status = $status = $message = '';
		
		$unavailbility_id = $request->input('unavailbility_id');
      	if(isset($unavailbility_id) && $unavailbility_id != "") {
          	
          	$obj_data = $this->PropertyUnavailabilityModel->where('id','=',$unavailbility_id)->first();
          	if($obj_data) {
            	$delete_status = $obj_data->delete();
          	}

          	if($delete_status) {
				$status  = 'success';
				$message = 'Unavailability details deleted successfully.';
	      	}
	      	else {
				$status  = 'error';
				$message = 'Error while deleteing an unavailability.';
	      	}
      	}
		else {
			$status  = 'error';
			$message = 'Unavailbility id should not be blank.';
	    }
      	return $this->build_response($status,$message);
   	}

   	public function delete_property_image(Request $request) {
      	$delete_status = '';
      	$arr_json = [];
      	$image_id = $request->input('image_id');

      	if(isset($image_id) && $image_id != "") {
          	$obj_property_image = $this->PropertyImagesModel->where('id',$image_id)->first();
          	if(isset($obj_property_image->image) && $obj_property_image->image != "") {
              	$path = $this->property_image_base_path.$obj_property_image->image;
              	if(file_exists($path)) {
					unlink($path);
              	}

              	$delete_status = $obj_property_image->delete();
              	if($delete_status) {
					$status  = 'success';
					$message = 'Property image deleted successfully.';
		      	}
		      	else {
					$status  = 'error';
					$message = 'Error while deleteing an image.';
		      	}
          	}
          	else {
				$status  = 'error';
				$message = 'Error while deleteing an image.';
	      	}
      	}
      	else {
			$status  = 'error';
			$message = 'Image id should not be blank.';
	    }
		return $this->build_response($status,$message);
   	}

   	public function my_property_listing()
   	{
   		$property_data = $arr_property = $arr_property_review = $reviw_avg = $arr_data = [];
		$price   = 0;
		$status  = 'error';
		$message = 'Record not found';
		
		$user_id = $this->user_id;
    	if (isset($user_id) && $user_id != '') {
			$arr_property = $this->ListingService->get_property_listing('','','','','','','','','','','','','','','','','','','',$user_id,'','','','','');

			if (isset($arr_property) && count($arr_property)>0) {
				$obj_pagination = $arr_property['arr_pagination'];
				$arr_pagination = $obj_pagination->toArray();

				$status  = 'success';
				$message = 'Records get successfully';

				$arr_data['total']         = $arr_pagination['total'];
				$arr_data['per_page']      = $arr_pagination['per_page'];
				$arr_data['current_page']  = $arr_pagination['current_page'];
				$arr_data['last_page']     = $arr_pagination['last_page'];
				$arr_data['next_page_url'] = $arr_pagination['next_page_url'];
				$arr_data['prev_page_url'] = $arr_pagination['prev_page_url'];
				$arr_data['from']          = $arr_pagination['from'];
				$arr_data['to']            = $arr_pagination['to'];
	            
	            if (isset($arr_property['property_list']) && count($arr_property['property_list'])>0) {
	            	foreach ($arr_property['property_list'] as $key => $row) {
	            		
	            		$property_type_slug = get_property_type_slug($row['property_type_id']);
	            		if($property_type_slug == 'warehouse') {
			                $price = $row['price_per_sqft'];
			            }
			            elseif($property_type_slug == 'office-space') {
			                $price = $row['price_per_office'];
			            }
			            else {
			                $price = $row['price_per_night'];
			            }

			            $property_full_name = isset($row['property_name']) ? $row['property_name'] : '';
			            $property_type_name = ucfirst(str_replace('-', ' ', $property_type_slug));
			            $property_full_name = $property_full_name.' - '.$property_type_name;

				        $property_data[$key]['property_id']        = $row['id'];
				        $property_data[$key]['property_type_id']   = $row['property_type_id'];
				        $property_data[$key]['property_type_slug'] = get_property_type_slug($row['property_type_id']);
				        $property_data[$key]['property_name_slug'] = $row['property_name_slug'];
				        $property_data[$key]['property_name']      = $property_full_name;
				        $property_data[$key]['number_of_guest']    = $row['number_of_guest'];
				        $property_data[$key]['number_of_bedrooms'] = $row['number_of_bedrooms'];
				        $property_data[$key]['number_of_beds']     = $row['number_of_beds'];
				        $property_data[$key]['price_per_night']    = $price;
				        $property_data[$key]['price_per']          = $row['price_per'];
				        $property_data[$key]['currency']           = $row['currency'];
				        $property_data[$key]['admin_status']       = $row['admin_status'];
				        $property_data[$key]['total_build_area']   = $row['total_build_area'];
				        $property_data[$key]['total_plot_area']    = $row['total_plot_area'];
				        $property_data[$key]['admin_area']         = $row['admin_area'];

						$property_data[$key]['employee']           = $row['employee'];
						$property_data[$key]['room']               = $row['room'];
						$property_data[$key]['desk']               = $row['desk'];
						$property_data[$key]['cubicles']           = $row['cubicles'];
						$property_data[$key]['no_of_employee']     = $row['no_of_employee'];
						$property_data[$key]['no_of_room']         = $row['no_of_room'];
						$property_data[$key]['no_of_desk']         = $row['no_of_desk'];
						$property_data[$key]['no_of_cubicles']     = $row['no_of_cubicles'];
						$property_data[$key]['room_price']         = $row['room_price'];
						$property_data[$key]['desk_price']         = $row['desk_price'];
						$property_data[$key]['cubicles_price']     = $row['cubicles_price'];

	            		if(isset($row['admin_status']) && $row['admin_status'] == 1)
							$property_data[$key]['admin_status_name'] = 'Pending';
	            		else if(isset($row['admin_status']) && $row['admin_status'] == 2)
	            			$property_data[$key]['admin_status_name'] = 'Approved';
	            		else if(isset($row['admin_status']) && $row['admin_status'] == 3)
	            			$property_data[$key]['admin_status_name'] = 'Rejected';
	            		else if(isset($row['admin_status']) && $row['admin_status'] == 4)
	            			$property_data[$key]['admin_status_name'] = 'Permanent Rejected';

	            		if(isset($row['property_image']) && $row['property_image'] != '') {
							$property_data[$key]['property_image'] = $this->property_image_public_path.$row['property_image'];
	            		}
	            		else {
							$property_data[$key]['property_image'] = url('/').'/front/images/Listing-page-no-image.jpg';
	            		}

	            		/*Get Property review average*/
	            		$reviw_avg = $this->ReviewRatingModel->where('property_id',$row['id'])->where('status', '1')->get();
	            		$avg = 0;

			            foreach($reviw_avg as $rev) {
							$avg += $rev['rating'];
			            }

			            if($avg>0) {
							$avg = round($avg/count($reviw_avg),1);
			            }
			            
						$property_data[$key]['review_avg']   = $avg;
						$property_data[$key]['no_of_review'] = count($reviw_avg);
	            	}
	            }
			}
			$arr_data['property_data'] = $property_data;
		}
		else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }
		return $this->build_response($status,$message,$arr_data);
   	}

   	public function delete_property(Request $request)
	{
		$status      = 'error';
		$message     = 'Record not found';
		$property_id = $request->input('property_id');

		$user_id     = $this->user_id;
    	if (isset($user_id) && $user_id != '') {
			if(isset($property_id) && $property_id != ''){
				$arr_property = $this->ListingService->delete_property($property_id, $user_id);
				if($arr_property == true) {
					$status  = 'success';
					$message = 'Property deleted successfully.';
				}
				else {
					$status  = 'error';
					$message = 'Soemthing went wrong. please try again.';
				}
			}
			else {
				$status  = 'error';
				$message = 'Property id should not be blank.';
			}
		}
		else {
			$status  = 'error';
			$message = 'Token expired, user not found.';
        }
		return $this->build_response($status,$message);
	}

    public function my_earning_listing(Request $request)
  	{
  		$earning_data = [];
  		if ($this->user_id == '') {
  			$status  = 'error';
            $message = 'Token expired, user not found.';
            return $this->build_response($status,$message);
  		} else {
			$from_date = $request->input('from_date');
			$to_date   = $request->input('to_date');

		    $user_table                 = $this->UserModel->getTable();
		    $prefixed_users_table       = DB::getTablePrefix().$this->UserModel->getTable();

		    $transaction_table          = $this->TransactionModel->getTable();
		    $prefixed_transaction_table = DB::getTablePrefix().$this->TransactionModel->getTable();

		    $property_table             = $this->PropertyModel->getTable();
		    $prefixed_property_table    = DB::getTablePrefix().$this->PropertyModel->getTable();

		    $booking_table              = $this->BookingModel->getTable();
		    $prefixed_booking_table     = DB::getTablePrefix().$this->BookingModel->getTable();

		    $obj_data = DB::table($transaction_table)
		                ->select(DB::raw( $prefixed_transaction_table.".transaction_id,".
								$prefixed_transaction_table.".transaction_date,".
		                        $prefixed_users_table.".first_name as owner_firstname,".
		                        $prefixed_users_table.".last_name as owner_lastname,".
		                        $prefixed_booking_table.".*,".
		                        $prefixed_property_table.".property_name,".
		                        $prefixed_property_table.".currency,".
		                        $prefixed_property_table.".currency_code"
		                    ))
		                ->where($prefixed_transaction_table.'.user_type','4')
		                ->where($prefixed_booking_table.'.booking_status','5')
		                ->where($prefixed_booking_table.'.property_owner_id',$this->user_id)
		                ->orderBy($prefixed_transaction_table.'.id','DESC')
		                ->Join($prefixed_users_table,$prefixed_users_table.".id",'=',$prefixed_transaction_table.'.user_id')
		                ->Join($prefixed_booking_table,$prefixed_booking_table.".id",'=',$prefixed_transaction_table.'.booking_id')
		                ->Join($prefixed_property_table,$prefixed_property_table.".id",'=',$prefixed_booking_table.'.property_id');

		    if($from_date != '' && $to_date != '') {
				$obj_data = $obj_data->whereBetween($prefixed_transaction_table.'.transaction_date',array(date('Y-m-d',strtotime($from_date)), date('Y-m-d',strtotime($to_date))));
		    }
		    $obj_data = $obj_data->paginate(10);

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

		        if (isset($arr_pagination) && count($arr_pagination) > 0) {
					$status  = 'success';
					$message = 'Records get successfully';

		      		foreach ($arr_pagination['data'] as $key => $value) {
						$earning_data[$key]['id']               = $value->id;
						$earning_data[$key]['transaction_id']   = $value->transaction_id;
						$earning_data[$key]['booking_id']       = $value->booking_id;
						$earning_data[$key]['property_name']    = $value->property_name;
						$earning_data[$key]['transaction_date'] = isset($value->transaction_date)?date('d M Y',strtotime($value->transaction_date)):'';
						$earning_data[$key]['property_amount']  = $value->property_amount;
						$earning_data[$key]['admin_commission'] = $value->admin_commission;
						$earning_data[$key]['currency']         = $value->currency;
						$earning_data[$key]['currency_code']    = $value->currency_code;

		      			if (isset($value->property_amount) && isset($value->admin_commission)) {
                            $pro_amount = $value->property_amount;
                            $commission = $value->admin_commission;
                            $host_earn  = $admin_earn = 0;
                            $admin_earn = ($commission/100)* $pro_amount;
                            $host_earn  = $pro_amount - $admin_earn;
                        }

                        $earning_data[$key]['host_earn'] = $host_earn;
                        if($value->currency_code != 'INR') {
							$earning_data[$key]['converttoINR'] = currencyConverter($value->currency_code,'INR',$host_earn);
                        } else {
							$earning_data[$key]['converttoINR'] = $host_earn;
                        }
		      		}
		      		$arr_data['earning_data'] = $earning_data;
		      		return $this->build_response($status,$message,$arr_data);
		        } else {
			    	$status  = 'error';
	            	$message = 'Records Not Found.';
	            	return $this->build_response($status,$message);
			    }
		    } else {
		    	$status  = 'error';
            	$message = 'Records Not Found.';
            	return $this->build_response($status,$message);
		    }
		}
  	}

	public function add_bank_account(Request $request)
    {
    	$user_id = $this->user_id;
    	if (!$user_id) {
            return $this->build_response("error", "Invalid user");
    	}

		$arr_rules['bank_name']      = "required";
		$arr_rules['account_number'] = "required";
		$arr_rules['ifsc_code']      = "required";
		$arr_rules['account_type']   = "required";
		$arr_rules['selected']       = "required";

        $msg = array('required' =>'Please enter :attribute');
        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if ($validator->fails()) {
			return $this->build_response("error", "Please fill all fields with valid input");
        }

        $form_data = $request->all();
        $does_exists = $this->BankDetailsModel->where('bank_name', $form_data['bank_name'])->where('account_number', $form_data['account_number'])->count()>0;
        if ($does_exists) {
			return $this->build_response("error", "This bank account already exists");
        }

        $user_default_bank = $this->BankDetailsModel->where('user_id', $this->user_id)->where('selected', '1')->count()>0;
        if($form_data['selected'] == '1') {
        	$this->BankDetailsModel->where('user_id', $this->user_id)->update(['selected' => '0']);
        }else if(!$user_default_bank) {
			$form_data['selected'] = '1';
        }

		$arr_in['bank_name']      = $form_data['bank_name'];
		$arr_in['account_number'] = $form_data['account_number'];
		$arr_in['ifsc_code']      = $form_data['ifsc_code'];
		$arr_in['account_type']   = $form_data['account_type'];
		$arr_in['user_id']        = $user_id;
		$arr_in['selected']       = $form_data['selected'];
        $bank_details = $this->BankDetailsModel->create($arr_in);
        if ($bank_details) {
            return $this->build_response("success", "Bank Details added successfully");
        } else {
            return $this->build_response("error", "Problem occurred while adding bank details");
        }
    }

    public function bank_account_list()
    {
    	$user_id = $this->user_id;
    	if (!$user_id) {
            return $this->build_response("error", "Invalid user");
    	}

    	$obj_bank_details = $this->BankDetailsModel->select('id','bank_name', 'account_number','ifsc_code','account_type','selected')->where('user_id', $user_id)->get();

		if (!$obj_bank_details) {
            return $this->build_response("error", "No bank details added yet");
		}
		$arr_bank_details = $obj_bank_details->toArray();

		if (isset($arr_bank_details) && count($arr_bank_details) > 0) {
			return $this->build_response("success", "Bank details fetched successfully", $arr_bank_details);
		} else {
			return $this->build_response("error", "No bank details added yet");
		}
    }

    public function update_bank_account(Request $request)
    {
    	$user_id = $this->user_id;
    	if (!$user_id) {
            return $this->build_response("error", "Invalid user");
    	}

		$arr_rules['bank_name']      = "required";
		$arr_rules['account_number'] = "required";
		$arr_rules['ifsc_code']      = "required";
		$arr_rules['account_type']   = "required";
		$arr_rules['selected']       = "required";

        $validator = Validator::make($request->all(), $arr_rules);
        if ($validator->fails()) {
            return $this->build_response("error", "Please fill all fields with valid input");
        }

        $form_data = $request->all();

        $does_exists = $this->BankDetailsModel->where('bank_name', $form_data['bank_name'])->where('account_number', $form_data['account_number'])->where('id', '!=', $form_data['id'])->count()>0;
        if($does_exists) {
            return $this->build_response("error", "This bank account already exists");
        }

        $user_default_bank = $this->BankDetailsModel->where('user_id', $user_id)->where('selected', "1")->count()>0;
        if($form_data['selected'] == '1') {
			$this->BankDetailsModel->where('user_id', $user_id)->where('id', '!=', $form_data['id'])->update(['selected' => '0']);
        } else if(!$user_default_bank) {
			$form_data['selected'] = "1";
        }

		$arr_up['bank_name']      = $form_data['bank_name'];
		$arr_up['account_number'] = $form_data['account_number'];
		$arr_up['ifsc_code']      = $form_data['ifsc_code'];
		$arr_up['account_type']   = $form_data['account_type'];
		$arr_up['selected']       = $form_data['selected'];
        $bank_details = $this->BankDetailsModel->where('id',$form_data['id'])->update($arr_up);
        return $this->build_response('success', 'Bank details updated successfully');
    }

	public function delete_bank_account(Request $request)
	{
		$user_id = $this->user_id;
    	if (!$user_id) {
			return $this->build_response("error", "Invalid user");
    	}

    	$bank_account_id = $request->input('bank_account_id');
    	if(!$bank_account_id) {
			return $this->build_response('error','Invalid bank account record');
	    } else {
			$delete_account = $this->BankDetailsModel->where('id', $bank_account_id)->delete();
			if($delete_account) {
				return $this->build_response('success','Bank details deleted successfully');
	        } else {
				return $this->build_response('error','Problem occurred while deleting bank account');
	        }
	    }
	}
}
