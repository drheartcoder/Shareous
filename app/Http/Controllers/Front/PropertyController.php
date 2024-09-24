<?php
namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Common\Services\EmailService;
use App\Common\Services\SMSService;
use App\Common\Services\NotificationService;

use App\Models\PropertyModel;
use App\Models\FavouritePropertyModel;
use App\Models\PropertyImagesModel;
use App\Models\PropertyBedsArrangmentModel;
use App\Models\AmenitiesModel;
use App\Models\PropertyRulesModel;
use App\Models\PropertyAminitiesModel;
use App\Models\PropertyUnavailabilityModel;
use App\Models\NotificationModel;

use Validator;
use Session;
use Input;
use File;

class PropertyController extends Controller
{
   function __construct(
                        EmailService                $email_service,
                        SMSService                  $sms_service,
                        PropertyModel               $property_model,
                        FavouritePropertyModel      $favourite_property_model,
                        PropertyImagesModel         $property_images_model,
                        PropertyBedsArrangmentModel $property_beds_arrangment_model,
                        AmenitiesModel              $aminities,
                        PropertyRulesModel          $property_rules_model,
                        PropertyAminitiesModel      $property_aminities_model,
                        NotificationModel           $NotificationModel,
                        PropertyUnavailabilityModel $property_unavailibitity_model,
                        NotificationService         $notification_service
                      )
   {
        $this->arr_view_data               = [];
        $this->module_title                = 'Property';
        $this->module_view_folder          = 'front.property.';
        $this->auth                        = auth()->guard('users');
        $this->module_url_path             = url('/').'/property';
        $this->EmailService                = $email_service;
        $this->SMSService                  = $sms_service;
        $this->PropertyModel               = $property_model;
        $this->FavouritePropertyModel      = $favourite_property_model;
        $this->PropertyImagesModel         = $property_images_model;
        $this->PropertyBedsArrangmentModel = $property_beds_arrangment_model;
        $this->AmenitiesModel              = $aminities;
        $this->PropertyRulesModel          = $property_rules_model;
        $this->PropertyAminitiesModel      = $property_aminities_model;
        $this->PropertyUnavailabilityModel = $property_unavailibitity_model;
        $this->NotificationModel           = $NotificationModel;
        $this->NotificationService         = $notification_service;

        $this->property_image_base_path    = base_path().config('app.project.img_path.property_image');
        $this->property_image_public_path  = url('/').config('app.project.img_path.property_image');

        $user = $this->auth->user();
        if($user) {
            $this->user_id                 = $user->id;
            $this->user_first_name         = $user->first_name;
            $this->user_last_name          = $user->last_name;
        } else {
            $this->user_id                 = 0;
            $this->user_first_name         = '';
            $this->user_last_name          = '';
        }
   }

   public function index()
   {
      $this->arr_view_data['page_title']        = 'List '.$this->module_title;
      $this->arr_view_data['module_url_path']   = $this->module_url_path;
      return view($this->module_view_folder.'.index',$this->arr_view_data);
   }

   /*
	  Name : Rohini j
	  Date : 3-mar-2018
   	load a view for propery post
   */
   public function create_property_step1(Request $request)
   {
   		$arr_property_type  = $arr_category = $arr_property = [];
   		$arr_category       = get_category();
   		$arr_property_type  = get_property_type();
      $property_id        = get_incomplete_property($this->user_id);
      $currency_list      = get_currency();          
      if(isset($property_id) && $property_id!="")
      {
          Session::flash('error','First add details of previous property,After then only you can add new property.');
          return redirect($this->module_url_path.'/edit_step2/'.base64_encode($property_id));
      }

     // $this->arr_view_data['arr_property']     = $arr_property;
      $this->arr_view_data['currency_list']     = $currency_list;
   		$this->arr_view_data['arr_property_type'] = $arr_property_type;
   		$this->arr_view_data['arr_category'] 	    = $arr_category;
   		$this->arr_view_data['page_title']        = 'Add '.$this->module_title;
   		$this->arr_view_data['module_url_path']   = $this->module_url_path;
   		return view($this->module_view_folder.'.post_property_step1',$this->arr_view_data);
   }

   public function create_property_step2($enc_property_id)
   {
         $arr_aminities  = $arr_property = [];
         $property_id    = '';
         if(isset($enc_property_id) && $enc_property_id!="")
         {
            $property_id = base64_decode($enc_property_id);
         }
         $obj_property   = $this->PropertyModel->where('id','=',$property_id)->with(['property_images'])->first();
         $property_type_id    = isset($obj_property->property_type_id)?$obj_property->property_type_id:'';

         $arr_aminities   =  $this->get_aminities($property_type_id);
         $arr_rules       =  $this->get_rules($property_id);

         if($obj_property)
         {
            $arr_property = $obj_property->toArray();
         }
         $this->arr_view_data['property_image_public_path'] = $this->property_image_public_path; 
         $this->arr_view_data['property_image_base_path']   = $this->property_image_base_path; 
         $this->arr_view_data['arr_property']      = $arr_property;

         $this->arr_view_data['arr_rules']         = $arr_rules;
         $this->arr_view_data['enc_property_id']   = $enc_property_id;
         $this->arr_view_data['arr_aminities']     = $arr_aminities;
         $this->arr_view_data['page_title']        = 'Add '.$this->module_title;
         $this->arr_view_data['module_url_path']   = $this->module_url_path;
         return view($this->module_view_folder.'.post_property_step2',$this->arr_view_data);
   }

   public function get_aminities($id)
   {
   		$arr_aminities = $arr_json = [];
   		$obj_aminities = '';
   		if($id!="")
   		{
   			$obj_aminities    = $this->AmenitiesModel->where('propertytype_id','=',$id)->where('status','1')->get();
   			if($obj_aminities)
   			{
   				$arr_aminities = $obj_aminities->toArray();
   			}
   		}      
   		return $arr_aminities;
   }

   public function get_aminities_arr($arr)
   {  
      $arr_aminities = $arr_json = $temp_arr = $amenity_arr = [];
      $obj_aminities = $html = '';

      if($arr != "")
      {
        $temp_arr = explode(',', $arr);
        if(count($temp_arr)>0)
        {
          foreach ($temp_arr as $key => $value) 
          {
            if($value!='')
            {
              array_push($amenity_arr, $value);
            }                  
          }

          $obj_aminities = $this->AmenitiesModel->whereIn('propertytype_id',$amenity_arr)->where('status','1')->get();
          if($obj_aminities)
          {
            $arr_aminities = $obj_aminities->toArray();
          } 
          //if(count(Request::get('room_category'))>0 && in_array($amenity['id'],Request::get('room_category'))){ echo 'checked=""'; }          
          if(count($arr_aminities)>0)
          {   
            $html.=' <select class="test" name="amenities[]" id="amenities" multiple="multiple">';  

            foreach($arr_aminities as $key_category1 => $amenity)
            {
               $aminity_name = isset($amenity['aminity_name'])? title_case($amenity['aminity_name']):"";
                $html.=' <option value="'.$amenity['id'].'">'.$aminity_name.'</option>';
                $html.='<label for="amenities'.$key_category1.'">'.$aminity_name.'</label>';
            }    
            $html.='</select>';
            
             /*foreach($arr_aminities as $key_category1 => $amenity)
            {
               $aminity_name = isset($amenity['aminity_name'])? title_case($amenity['aminity_name']):"";
                $html.='<div class="check-box inline-checkboxs">';
                    $html.='<input id="amenities'.$key_category1.'" class="filled-in amenities" type="checkbox" name="amenities[]" value="'.$amenity['id'].'" />';
                    $html.='<label for="amenities'.$key_category1.'">'.$aminity_name.'</label>';
                $html.='</div>';
            } */

            $arr_json['amenity_html']   =  $html;
            $arr_json['status']         =  'success';
            $arr_json['msg']            =  'Record get successfully';  
          }
          else
          {
            $arr_json['status']       = 'error';
            $arr_json['msg']          = 'Record not found';  
          }
          
        }        
      }
      else
      {
        $arr_json['status']   = 'error';
        $arr_json['msg']      = 'Record not found';  
      }  

      return json_encode($arr_json);
   }

  public function get_sleeping_arrangement(Request $request)
  {
    $no_of_bedrooms = $request->input('no_of_bedrooms');
    //$property_id    = $request->input('property_id');

    $get_sleeping_arrangement = $arr_property_beds = [];
    $str = '';

    /*if( isset($property_id) && !empty($property_id) && $property_id != '' )
    {
      $obj_property_beds = $this->PropertyBedsArrangmentModel->where('property_id', $property_id)->get();
      if($obj_property_beds)
      {
        $arr_property_beds = $obj_property_beds->toArray();
      }
    }*/
    
    if($no_of_bedrooms != '')
    {
      $get_sleeping_arrangement = get_sleeping_arrangement();
      
      if(count($get_sleeping_arrangement) > 0)
      {
        for($j = 0; $j < $no_of_bedrooms; $j++)
        { 
          $str .= '<div class="clearfix"></div><div class="addbeds-min-block no-padding">';
          $str .= '<div class="row">';
          $str .= '<div class="col-sm-6 col-md-3 col-lg-3">';
          $str .= '<div class="comments-title-bed-main">';
          $str .= '<div class="comments-title bed">Double</div><div class="form-group"><div class="select-style">';
          $str .= '<select id="Bed-one" name=double_bed[]><option value="">Select</option>';

          foreach($get_sleeping_arrangement as $key => $value) 
          {
            $str .= '<option value='.$value['id'].'>'.ucfirst($value['value']).'</option>';
          }
           
          $str .= '</select>';
          $str .= '</div></div></div></div>';

          $str .= '<div class="col-sm-6 col-md-3 col-lg-3">';
          $str .= '<div class="comments-title-bed-main">';
          $str .= '<div class="comments-title bed">Single</div><div class="form-group"><div class="select-style">';
          $str .= '<select id="Bed-two" name=single_bed[]><option value="">Select</option>';
          
          foreach($get_sleeping_arrangement as $key => $value) 
          {
            $str .= '<option value='.$value['id'].'>'.ucfirst($value['value']).'</option>';
          
          }
          $str .= '</select>';
          $str .= '</div></div></div></div>';
          $str .= '<div class="col-sm-6 col-md-3 col-lg-3">';
          $str .= '<div class="comments-title-bed-main">';
          $str .= '<div class="comments-title bed">Queen</div><div class="form-group"><div class="select-style">';
          $str .= '<select id="Bed-two" name=queen_bed[]><option value="">Select</option>';
        
          foreach($get_sleeping_arrangement as $key => $value) 
          {
            $str .= '<option value='.$value['id'].'>'.ucfirst($value['value']).'</option>';
          }

          $str .= '</select>';
          $str .= '</div></div></div></div>';

          $str .= '<div class="col-sm-6 col-md-3 col-lg-3">';
          $str .= '<div class="comments-title-bed-main">';
          $str .= '<div class="comments-title bed">Sofa Bed</div><div class="form-group"><div class="select-style">';
          $str .= '<select id="Bed-two" name=sofa_bed[]><option value="">Select</option>';
          
          foreach($get_sleeping_arrangement as $key => $value) 
          {
            $str .= '<option value='.$value['id'].'>'.ucfirst($value['value']).'</option>';
          }

          $str .= '</select>';
          $str .= '</div></div></div></div></div></div>';
        }
        
      }

      $data['string'] = $str;
      $data['status'] = 'success';
      $data['msg']    = 'record get successfully';
    }
    else
    {   
      $data['string'] = $str;
      $data['status'] = 'null';
      $data['msg']    = 'Number of bedrooms required';
    }

    echo json_encode($data);
    exit;
  }

   public function store_step1(Request $request)
   {
        $property_status = $property_image_status = $property_beds_arrangment = $property_name_slug = $latitude = $longitude = $city= $state = $country = '';
        $arr_rules = $form_data = $arr_property = $arr_property_image = $arr_images = $arr_bedrooms = array();

        $form_data     = $request->all();
        $property_slug = get_property_type_slug($form_data['property_type']); 

        $arr_rules['property_name'] = "required";
        $arr_rules['description']   = "required";
        $arr_rules['property_type'] = "required";
        $arr_rules['address']       = "required";
        $arr_rules['currency']      = "required";
        $arr_rules['postal_code']   = "required";

        if ($property_slug == 'warehouse') 
        {
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
        else if ($property_slug == 'office-space') 
        {
            $arr_rules['property_working_status'] = "required";
            $arr_rules['property_area']           = "required";
            $arr_rules['total_build_area']        = "required";
            $arr_rules['admin_area']              = "required";
            $arr_rules['build_type']              = "required";
        }
        else
        {
            $arr_rules['no_of_guest']    = "required";
            $arr_rules['no_of_bedrooms'] = "required";
            $arr_rules['bathrooms']      = "required";
            $arr_rules['no_of_beds']     = "required";
            $arr_rules['price']          = "required";
        }
       
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $property_id = get_incomplete_property();
        
        if(isset($property_id) && $property_id != "")
        {
          Session::flash('error','First add details of previous property,After then only you can add new property.');
          return redirect($this->module_url_path.'/edit_step2/'.base64_encode($property_id));
        }        
        if(isset($form_data['property_name']))
        {
          $res = $this->PropertyModel->where('property_name',$form_data['property_name'])->get();
          
          if(count($res)>0)
          {
            $property_name_slug = str_slug($form_data['property_name'].count($res));
          }
          else
          {
            $property_name_slug = str_slug($form_data['property_name']);
          }
        }
          
        if($form_data['address'] != '') 
        {
            $latitude    = $request->input('latitude');
            $longitude   = $request->input('longitude');
            $city        = $request->input('city');
            $state       = $request->input('state');
            $country     = $request->input('country');

            /*$cityclean   = str_replace (" ", "+", $form_data['address']);
            $details_url = "http://maps.googleapis.com/maps/api/geocode/json??key=AIzaSyBYfeB69IwOlhuKbZ1pAOwcjEAz3SYkR-o&address=" . $cityclean . "&sensor=false";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $details_url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

            $content  = curl_exec($ch);
            curl_close($ch);            
            $metadata = json_decode($content, true); //json decoder
            $result   = array();

            if( isset($metadata['results']) && sizeof($metadata['results'])>0)
            {
                $result = $metadata['results'][0];
                if(sizeof($result)>0)
                {
                  $latitude  = $request->input('latitude')!=''&&$request->input('latitude')!=null?$request->input('latitude'):isset($result['geometry']['location']['lat']) ? $result['geometry']['location']['lat'] : '0';
                  $longitude = $request->input('longitude')!=''&&$request->input('longitude')!=null?$request->input('longitude'):isset($result[0]['geometry']['location']['lng']) ? $result[0]['geometry']['location']['lng'] : '0';
                  $city      = $request->input('city')!=''&&$request->input('city')!=null?$request->input('city'):isset($result['address_components'][0]['long_name']) ? $result['address_components'][0]['long_name'] : '';
                  $state     = $request->input('state')!=''&&$request->input('state')!=null?$request->input('state'):isset($result['address_components'][2]['long_name']) ? $result['address_components'][2]['long_name'] : '';
                  $country   = $request->input('country')!=''&&$request->input('country')!=null?$request->input('country'):isset($result['address_components'][3]['long_name']) ? $result['address_components'][3]['long_name'] : '';
                }
            }*/          
        }
        
        $currency_id                              = $request->input('currency');
        $currency_details                         = isset($currency_id)?get_currency($currency_id):'';
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
        $arr_property['total_plot_area']          = '';
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

        $arr_property['available_no_of_employee'] = isset($form_data['no_of_employee'])?$form_data['no_of_employee']:'';
        $arr_property['available_no_of_slots']    = isset($form_data['no_of_slots'])?$form_data['no_of_slots']:'';
        $arr_property['property_name_slug']       = $property_name_slug;
        $arr_property['property_status']          = 2;
        $arr_property['admin_status']             = 1;

        $property_status = $this->PropertyModel->create($arr_property);

         if(isset($form_data['no_of_bedrooms']))
         {
            $no_of_bedrooms = $form_data['no_of_bedrooms'];
            for($i=0;$i<$no_of_bedrooms;$i++)
            {
              $j = $i + 1;
              if(isset($form_data['double_bed'][$i]) && isset($form_data['single_bed'][$i]) && isset($form_data['queen_bed'][$i]) && isset($form_data['sofa_bed'][$i]) && $form_data['double_bed'][$i]!='' && $form_data['single_bed'][$i] && $form_data['queen_bed'][$i] && $form_data['sofa_bed'][$i])
              {
                $arr_bedrooms['double_bed']     = isset($form_data['double_bed'][$i])?$form_data['double_bed'][$i]:'';
                $arr_bedrooms['single_bed']     = isset($form_data['single_bed'][$i])?$form_data['single_bed'][$i]:'';
                $arr_bedrooms['queen_bed']      = isset($form_data['queen_bed'][$i])?$form_data['queen_bed'][$i]:'';
                $arr_bedrooms['sofa_bed']       = isset($form_data['sofa_bed'][$i])?$form_data['sofa_bed'][$i]:'';
                $arr_bedrooms['no_of_bedrooms'] = $j;
                $arr_bedrooms['property_id']    = isset($property_status->id)?$property_status->id:'';
                $property_beds_arrangment       = $this->PropertyBedsArrangmentModel->create($arr_bedrooms);
              }  
            }
         }
         if($property_status || $property_beds_arrangment)
         {
            Session::flash('success','First step of property is created successfully.');
            return redirect($this->module_url_path.'/create_step2/'.base64_encode($property_status->id));
         }
         else
         {
            Session::flash('error','Error occure while adding record of property.');
            return redirect()->back();
         }
   }

  public function store_step2(Request $request)
  {
    $arr_rules = $form_data = $arr_property = $arr_unavailibility = $arr_aminities = array();
    $aminities_status = $unavailaibility_status = '';

    $form_data = $request->all();

    $property_id = isset($form_data['enc_property_id'])?base64_decode($form_data['enc_property_id']):'';
   
    if(isset($form_data['aminities']) && sizeof($form_data['aminities'])>0)
    {
      foreach ($form_data['aminities'] as $key => $value) 
      {
        if($value != "" && !empty($value))
        {
          $arr_aminities['property_id']  = $property_id;
          $arr_aminities['aminities_id'] = isset($value) ? $value : '';

          $aminities_status = $this->PropertyAminitiesModel->create($arr_aminities);
        }
      }
    }
    
    if(isset($form_data['from_date']) && count( $form_data['from_date'] ) != 0 && !empty($form_data['from_date']) && count( $form_data['to_date'] ) != 0 && !empty($form_data['to_date']))
    {
      foreach ($form_data['from_date'] as $key => $value) 
      {
        $from_date = isset($value) ? $value : '';
        $to_date   = isset($form_data['to_date'][$key]) ? $form_data['to_date'][$key] : '';

        if($from_date != "" && !empty($from_date) && $to_date != "" && !empty($to_date))
        {
          $arr_unavailibility['property_id'] = $property_id;
          $arr_unavailibility['from_date']   = isset($from_date) ? date('Y-m-d',strtotime(str_replace('/', '-', $from_date))) : '';
          $arr_unavailibility['to_date']     = isset($to_date) ? date('Y-m-d',strtotime(str_replace('/', '-', $to_date))) : '';
          $unavailaibility_status            = $this->PropertyUnavailabilityModel->create($arr_unavailibility);
        }
      }
    }

    $arr_property['property_status']          = 1;
    $arr_property['nearest_railway_station']  = isset($form_data['nearest-railway-station'])?$form_data['nearest-railway-station']:'';
    $arr_property['nearest_national_highway'] = isset($form_data['nearest-national-highway'])?$form_data['nearest-national-highway']:'';
    $arr_property['nearest_bus_stop']         = isset($form_data['nearest-bus-stop'])?$form_data['nearest-bus-stop']:'';
    $arr_property['working_hours']            = isset($form_data['working-hours'])?$form_data['working-hours']:'';
    $arr_property['working_days']             = isset($form_data['working-days'])?$form_data['working-days']:'';
  
    $this->PropertyModel->where('id','=',$property_id)->update($arr_property);/*all steps of property are completed*/

    // Send admin notification starts
    $arr_built_content = array(
                            'USER_NAME' => $this->user_first_name,
                            'MESSAGE'   => "New property added successfully by ".$this->user_first_name
                        );
    
    $arr_notify_data['notification_text']  = $arr_built_content;
    $arr_notify_data['notify_template_id'] = '9';
    $arr_notify_data['template_text']      = "New property added successfully by ".$this->user_first_name;
    $arr_notify_data['sender_id']          = $this->user_id;
    $arr_notify_data['sender_type']        = '1';
    $arr_notify_data['receiver_id']        = '1';
    $arr_notify_data['receiver_type']      = '2';
    $arr_notify_data['url']                = url('/').'/admin/property/all';
    $notification_status                   = $this->NotificationService->send_notification($arr_notify_data);
    // Send admin notification ends

    Session::flash('success','Property details are added successfully.');
    return redirect($this->module_url_path.'/listing');
  }

   public function edit_property_step1($enc_property_id)
   {
     $arr_property  = $arr_property_type  = $arr_category =[];

     $arr_category       = get_category();
     $arr_property_type  = get_property_type();
     $currency_list      = get_currency();

     $property_id   = '';
     if(isset($enc_property_id) && $enc_property_id!="")
     {
        $property_id = base64_decode($enc_property_id);
     }
     
     $obj_property  = $this->PropertyModel->where('id','=',$property_id)->with(['property_bed_arrangment'])->first();
     if($obj_property)
     {
        $arr_property = $obj_property->toArray();
     }
     //dd( $arr_property );
    
     $this->arr_view_data['currency_list']     = $currency_list;         
     $this->arr_view_data['arr_category']      = $arr_category;
     $this->arr_view_data['arr_property_type'] = $arr_property_type;
     $this->arr_view_data['arr_property']      = $arr_property;
     $this->arr_view_data['enc_property_id']   = $enc_property_id;
     $this->arr_view_data['page_title']        = 'Edit '.$this->module_title;
     $this->arr_view_data['module_url_path']   = $this->module_url_path;
     return view($this->module_view_folder.'.edit_property_step1',$this->arr_view_data);
   }

   public function edit_property_step2($enc_property_id)
   {
       $arr_aminities = $arr_property = [];
       $property_id   = '';
       if(isset($enc_property_id) && $enc_property_id!="")
       {
          $property_id = base64_decode($enc_property_id);
       }
      
       $obj_property = $this->PropertyModel->where('id','=',$property_id)
                                          ->with(['property_unavailability' => function($q1) {
                                              $q1->select('id','property_id','type','from_date','to_date');
                                          },'property_rules'=>function($q1) {
                                              $q1->select('id','property_id','rules');
                                          },'property_aminities'=>function($q1) {
                                              $q1->select('id','property_id','aminities_id');
                                          },'property_images'=>function(){}])
                                          ->first();
       if($obj_property)
       {
          $arr_property = $obj_property->toArray();
       }
       $tempArr = [];
       if(count($arr_property['property_aminities']) > 0)
       {
        foreach ($arr_property['property_aminities'] as $arr_propertyVal)
        {
          if(isset($arr_propertyVal['aminities_id']) && $arr_propertyVal['aminities_id']!='')
          {
            $tempArr[] = $arr_propertyVal['aminities_id'];
          }
        }
       }

       $property_type_id = isset($obj_property->property_type_id)?$obj_property->property_type_id:'';
       $arr_aminities    = $this->get_aminities($property_type_id);
      
       $this->arr_view_data['arr_property']               = $arr_property;
       $this->arr_view_data['enc_property_id']            = $enc_property_id;
       $this->arr_view_data['arr_aminities']              = $arr_aminities;
       $this->arr_view_data['page_title']                 = 'Edit '.$this->module_title;
       $this->arr_view_data['module_url_path']            = $this->module_url_path;
       $this->arr_view_data['tempArr']                    = $tempArr;
       $this->arr_view_data['property_image_public_path'] = $this->property_image_public_path;
       $this->arr_view_data['property_image_base_path']   = $this->property_image_base_path;

       return view($this->module_view_folder.'.edit_property_step2',$this->arr_view_data);
   }

   public function update_property_step1(Request $request)
   {
        $property_status = $property_image_status = $property_beds_arrangment = $latitude = $longitude = $city = $state = $country = '';
        $arr_rules = $form_data = $arr_property = $arr_images = array();
        
        $form_data = $request->all();
        $property_slug = get_property_type_slug($form_data['property_type']); 
        
        $arr_rules['property_name'] = "required";
        $arr_rules['description']   = "required";
        $arr_rules['property_type'] = "required";
        $arr_rules['address']       = "required";
        $arr_rules['currency']      = "required";
        $arr_rules['postal_code']   = "required";

        if ($property_slug == 'warehouse') 
        {
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
        else if ($property_slug == 'office-space') 
        {
          $arr_rules['property_working_status'] = "required";
          $arr_rules['property_area']           = "required";
          $arr_rules['total_build_area']        = "required";
          $arr_rules['admin_area']              = "required";
          //$arr_rules['price_per_office']        = "required";
          $arr_rules['build_type']              = "required";
        }
        else
        {
          $arr_rules['no_of_guest']    = "required";
          $arr_rules['no_of_bedrooms'] = "required";
          $arr_rules['bathrooms']      = "required";
          $arr_rules['no_of_beds']     = "required";
          $arr_rules['price']          = "required";
        }
       
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {       
            Session::flash('error','Some mandory field are missing.');
            return redirect()->back()->withErrors($validator)->withInput($request->all());  
        }

        $property_id = isset($form_data['enc_property_id'])?base64_decode($form_data['enc_property_id']):'';
      
        if(isset($form_data['property_name']))
        {
          $res = $this->PropertyModel->where('property_name',$form_data['property_name'])->where('id','!=',$property_id)->get();
          
          if(count($res)>0)
          {
            $property_name_slug = str_slug($form_data['property_name'].count($res));
          }
          else
          {
            $property_name_slug = str_slug($form_data['property_name']);
          }
        }
        
        if($form_data['address'] != '') 
        {
          $latitude  = $request->input('lat');
          $longitude = $request->input('long');
          $city      = $request->input('city');
          $state     = $request->input('state');
          $country   = $request->input('country');
  
          /*$cityclean = str_replace (" ", "+", $form_data['address']);
          $details_url = "http://maps.googleapis.com/maps/api/geocode/json??key=AIzaSyBYfeB69IwOlhuKbZ1pAOwcjEAz3SYkR-o&address=" . $cityclean . "&sensor=false";
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $details_url);
          curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

          $content = curl_exec($ch);
          curl_close($ch);            
          $metadata = json_decode($content, true); //json decoder           
          $result = array();
          if( isset($metadata['results']) && sizeof($metadata['results'])>0)
          {
              $result = $metadata['results'][0];                              
              if(sizeof($result)>0)
              {
                $latitude  = $request->input('latitude') != '' && $request->input('latitude') != null ? $request->input('latitude'):isset($result['geometry']['location']['lat']) ? $result['geometry']['location']['lat'] : '0';
                $longitude = $request->input('longitude')!=''&&$request->input('longitude')!=null?$request->input('longitude'):isset($result[0]['geometry']['location']['lng']) ? $result[0]['geometry']['location']['lng'] : '0';
                $city      = $request->input('city')!=''&&$request->input('city')!=null?$request->input('city'):isset($result['address_components'][0]['long_name']) ? $result['address_components'][0]['long_name'] : '';
                $state     = $request->input('state')!=''&&$request->input('state')!=null?$request->input('state'):isset($result['address_components'][2]['long_name']) ? $result['address_components'][2]['long_name'] : '';
                $country   = $request->input('country')!=''&&$request->input('country')!=null?$request->input('country'):isset($result['address_components'][3]['long_name']) ? $result['address_components'][3]['long_name'] : '';
              }
          }*/  
        }


        $currency_details                         = isset($form_data['currency'])?get_currency($form_data['currency']):'';
        $obj_property                             = $this->PropertyModel->where('id','=',$property_id);
        $old_no_of_bedrooms                       = isset($obj_property->no_of_bedrooms)?$obj_property->no_of_bedrooms:'';
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
        $arr_property['total_plot_area']          = '';
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
        $arr_property['employee']                 = isset($form_data['office_person'])?$form_data['office_person']:'off';
        $arr_property['room']                     = isset($form_data['office_private_room'])?$form_data['office_private_room']:'off';
        $arr_property['desk']                     = isset($form_data['office_dedicated_desk'])?$form_data['office_dedicated_desk']:'off';
        $arr_property['cubicles']                 = isset($form_data['office_cubicles'])?$form_data['office_cubicles']:'off';
        $arr_property['no_of_employee']           = isset($form_data['no_of_employee'])?$form_data['no_of_employee']:null;
        $arr_property['no_of_room']               = isset($form_data['no_of_room'])?$form_data['no_of_room']:null;
        $arr_property['no_of_desk']               = isset($form_data['no_of_desk'])?$form_data['no_of_desk']:null;
        $arr_property['no_of_cubicles']           = isset($form_data['no_of_cubicles'])?$form_data['no_of_cubicles']:null;
        $arr_property['room_price']               = isset($form_data['room_price'])?$form_data['room_price']:'';
        $arr_property['desk_price']               = isset($form_data['desk_price'])?$form_data['desk_price']:'';
        $arr_property['cubicles_price']           = isset($form_data['cubicles_price'])?$form_data['cubicles_price']:'';
        $arr_property['available_no_of_employee'] = isset($form_data['no_of_employee'])?$form_data['no_of_employee']:'';
        $arr_property['available_no_of_slots']    = isset($form_data['no_of_slots'])?$form_data['no_of_slots']:'';
        $arr_property['property_name_slug']       = $property_name_slug;
        $arr_property['admin_status']             = '1';
        $property_status                          = $obj_property->update($arr_property);

         if(isset($form_data['no_of_bedrooms']))
         {
            $this->PropertyBedsArrangmentModel->where('property_id',$property_id)->delete();
            $temp_cnt = $form_data['no_of_bedrooms'];

            for($i=0;$i<$temp_cnt;$i++)
            {
              if(isset($form_data['double_bed'][$i]) && isset($form_data['single_bed'][$i]) && isset($form_data['queen_bed'][$i]) && isset($form_data['sofa_bed'][$i]) && $form_data['double_bed'][$i]!='' && $form_data['single_bed'][$i]!='' && $form_data['queen_bed'][$i]!='' && $form_data['sofa_bed'][$i]!='')
              {
                $j = $i + 1;                
                $arr_bedrooms['double_bed']       = isset($form_data['double_bed'][$i])?$form_data['double_bed'][$i]:'';
                $arr_bedrooms['single_bed']       = isset($form_data['single_bed'][$i])?$form_data['single_bed'][$i]:'';
                $arr_bedrooms['queen_bed']        = isset($form_data['queen_bed'][$i])?$form_data['queen_bed'][$i]:'';
                $arr_bedrooms['sofa_bed']         = isset($form_data['sofa_bed'][$i])?$form_data['sofa_bed'][$i]:'';
                $arr_bedrooms['no_of_bedrooms']   = $j;
                $arr_bedrooms['property_id']      = isset($property_id)?$property_id:'';  
               
                $property_beds_arrangment         = $this->PropertyBedsArrangmentModel->create($arr_bedrooms);
              }  
            }
         }        
        
         /*update existing bed arrangement*/
         if(isset($form_data['old_double_bed']) && sizeof($form_data['old_double_bed'])>0)
         {
            foreach ($form_data['old_double_bed'] as $key => $value)
            {
              if($key!="")
              {
                 $this->PropertyBedsArrangmentModel->where('id','=',$key)->update(['double_bed'=>$value]);
              }
            }
         }
         if(isset($form_data['old_single_bed']) && sizeof($form_data['old_single_bed'])>0)
         {
            foreach ($form_data['old_single_bed'] as $key => $value)
            {
              if($key!="")
              {
                 $this->PropertyBedsArrangmentModel->where('id','=',$key)->update(['single_bed'=>$value]);
              }
            }
         }
         if(isset($form_data['old_queen_bed']) && sizeof($form_data['old_queen_bed'])>0)
         {
            foreach ($form_data['old_queen_bed'] as $key => $value)
            {
              if($key!="")
              {
                 $this->PropertyBedsArrangmentModel->where('id','=',$key)->update(['queen_bed'=>$value]);
              }
            }
         }
         if(isset($form_data['old_sofa_bed']) && sizeof($form_data['old_sofa_bed'])>0)
         {
            foreach ($form_data['old_sofa_bed'] as $key => $value)
            {
              if($key!="")
              {
                 $this->PropertyBedsArrangmentModel->where('id','=',$key)->update(['sofa_bed'=>$value]);
              }
            }
         }

         if($property_status || $property_beds_arrangment)
         {
            Session::flash('success','First step of property is updated successfully.');
            return redirect($this->module_url_path.'/edit_step2/'.base64_encode($property_id));
         }
         else
         {
            Session::flash('error','Error occure while updating record of property.');
            return redirect()->back();
         }
   }

  public function update_property_step2(Request $request)
  {
    $arr_rules = $form_data = $arr_property = $arr_unavailibility = $arr_aminities = array();
    $aminities_status = $unavailaibility_status = '';

    $form_data   = $request->all();
    $property_id = isset($form_data['enc_property_id'])?base64_decode($form_data['enc_property_id']):'';

    /*Add Property images*/
    $property_image_count = $this->PropertyImagesModel->where('property_id',$property_id)->count();

    /*add animities*/
    $obj_aminities = $this->PropertyAminitiesModel->where('property_id','=',$property_id)->first();
    if(isset($form_data['aminities']) && count($form_data['aminities'])>0)
    {
      if(count($obj_aminities)>0)
      {
        $this->PropertyAminitiesModel->where('property_id','=',$property_id)->delete();
      }

      foreach ($form_data['aminities'] as $key => $value)
      {
        $arr_aminities['property_id']  = $property_id;
        $arr_aminities['aminities_id'] = $value;
        $aminities_status = $this->PropertyAminitiesModel->create($arr_aminities);
      }
    }

    /*update existing unavailibility*/
    if(isset($form_data['old_from_date']) && isset($form_data['old_to_date']) && count( $form_data['old_from_date'] ) != 0 && !empty($form_data['old_from_date']) && count( $form_data['old_to_date'] ) != 0 && !empty($form_data['old_to_date']))
    {
      foreach ($form_data['old_from_date'] as $key => $value)
      {
        if(isset($key) && $key != "")
        {
          $from_date = isset($value)?date('Y-m-d',strtotime($value)):'';
          $to_date   = isset($form_data['old_to_date'][$key])?date('Y-m-d',strtotime($form_data['old_to_date'][$key])):'';

          if($from_date != "" && !empty($from_date) && $to_date != "" && !empty($to_date))
          {
            $arr_unavailibility['from_date'] = isset($from_date) ? date('Y-m-d',strtotime(str_replace('/','-',$from_date))) : '';
            $arr_unavailibility['to_date']   = isset($to_date) ? date('Y-m-d',strtotime(str_replace('/','-',$to_date))) : '';
            $unavailaibility_status = $this->PropertyUnavailabilityModel->where('id','=',$key)->update($arr_unavailibility);
          }
        }
      }
    }

    /*add unavailibility*/
    if(isset($form_data['from_date']) && isset($form_data['to_date']) && count( $form_data['from_date'] ) != 0 && !empty($form_data['from_date']) && count( $form_data['to_date'] ) != 0 && !empty($form_data['to_date']))
    {
      foreach ($form_data['from_date'] as $key => $value)
      {
        $from_date = isset($value) ? $value : '';
        $to_date   = isset($form_data['to_date'][$key]) ? $form_data['to_date'][$key] : '';

        if($from_date != "" && !empty($from_date) && $to_date != "" && !empty($to_date))
        {
          $arr_unavailibility['property_id'] = $property_id;
          $arr_unavailibility['from_date']   = isset($from_date) ? date('Y-m-d',strtotime(str_replace('/','-',$from_date))) : '';
          $arr_unavailibility['to_date']     = isset($to_date) ? date('Y-m-d',strtotime(str_replace('/','-',$to_date))) : '';
          $unavailaibility_status = $this->PropertyUnavailabilityModel->create($arr_unavailibility);
        }
      }
    }

    $arr_property['property_status']          = 1;
    $arr_property['nearest_railway_station']  = isset($form_data['nearest-railway-station'])?$form_data['nearest-railway-station']:'';
    $arr_property['nearest_national_highway'] = isset($form_data['nearest-national-highway'])?$form_data['nearest-national-highway']:'';
    $arr_property['nearest_bus_stop']         = isset($form_data['nearest-bus-stop'])?$form_data['nearest-bus-stop']:'';
    $arr_property['working_hours']            = isset($form_data['working-hours'])?$form_data['working-hours']:'';
    $arr_property['working_days']             = isset($form_data['working-days'])?$form_data['working-days']:'';
   
    $this->PropertyModel->where('id','=',$property_id)->update($arr_property);/*all steps of property are completed*/

    // Send admin notification starts
    $arr_built_content = array(
                                'USER_NAME' => $this->user_first_name,
                                'MESSAGE'   => "Property updated successfully by ".$this->user_first_name
                              );

    $arr_notify_data['notification_text']  = $arr_built_content;
    $arr_notify_data['notify_template_id'] = '9';
    $arr_notify_data['template_text']      = "Property updated successfully by ".$this->user_first_name;
    $arr_notify_data['sender_id']          = $this->user_id;
    $arr_notify_data['sender_type']        = '1';
    $arr_notify_data['receiver_id']        = '1';
    $arr_notify_data['receiver_type']      = '2';
    $arr_notify_data['url']                = url('/').'/admin/property/all';
    $notification_status                   = $this->NotificationService->send_notification($arr_notify_data);
    // Send admin notification ends

    Session::flash('success','property details are updated successfully.');
    return redirect($this->module_url_path.'/listing');
  }

   /*
      Rules function start from here
      rohini j
      date: 6-march-2018 
   */

   public function add_rules(Request $request)
   {
      //dd($request->all());
      $rule_status = '';
      $form_data = $arr_rules = $arr_json = $arr_rules_data = [];
      $form_data = $request->all();

      $arr_rules['property_id'] = isset($form_data['property_id'])?base64_decode($form_data['property_id']):'';
      $arr_rules['rules']       = isset($form_data['rules'])?$form_data['rules']:'';
        
      $check_rule_status        = $this->PropertyRulesModel->where('property_id',base64_decode($form_data['property_id']))->where('rules',$form_data['rules'])->first();    

      if(count($check_rule_status)>0)
      {
         $arr_json['status']  = 'error';
         $arr_json['message'] = 'Rule already added.';        
      }
      else
      {
        $rule_status = $this->PropertyRulesModel->create($arr_rules);
        if($rule_status)
        {
           $arr_json['status']  = 'success';
           $arr_json['message'] = 'Property rules added successfully.';
        }
        else
        {
           $arr_json['status']  = 'error';
           $arr_json['message'] = 'Error while adding rules.';
        }  
      }  
      

      $property_id     =  isset($form_data['property_id'])?base64_decode($form_data['property_id']):'';
      $arr_rules_data  =  $this->get_rules($property_id);
      $arr_json['arr_rules_data']  = $arr_rules_data;
      return response()->json($arr_json);
   }

   public function get_rules($property_id)
   {
        $arr_rules = [];
        $obj_rules = $this->PropertyRulesModel->where('property_id','=',$property_id)->get();
        if($obj_rules)
        {
            $arr_rules = $obj_rules->toArray();
        }
        return $arr_rules;
   }

   public function update_rules(Request $request)
   {
      $form_data = $arr_update = $arr_json = [];
      $form_data = $request->all();
      $house_rule_id = isset($form_data['enc_rule_id'])?base64_decode($form_data['enc_rule_id']):'';

      $check_update_status = $this->PropertyRulesModel->where('id','!=',$house_rule_id)->where('property_id','=',$form_data['property_id'])->where('rules','=',$form_data['rules'])->first();      

      if(count($check_update_status)>0)
      {
        $arr_json['status']  = 'error';
        $arr_json['message'] = 'Rule already added.';
      }
      else
      {
        $update_status = $this->PropertyRulesModel->where('id','=',$house_rule_id)->update(['rules'=>$form_data['rules']]);
        if($update_status)
        {
           $arr_json['status']  = 'success';
           $arr_json['message'] = 'Property rules updated successfully.';
        }
        else
        {
           $arr_json['status']  = 'error';
           $arr_json['message'] = 'Error occure while updating rules.';
        }  
      }
      
      return response()->json($arr_json);
   }

   public function edit_rules($enc_rule_id)
   {
      $arr_rules = $arr_json = [];
      $id        = base64_decode($enc_rule_id);
      $obj_rules = $this->PropertyRulesModel->where('id','=',$id)->first();
      if($obj_rules)
      {
         $arr_rules = $obj_rules->toArray();
      }
      $arr_json['rule'] = isset($arr_rules['rules'])?$arr_rules['rules']:'';
      return response()->json($arr_json);
   }

   public function delete_rules($enc_rule_id)
   {
      $arr_rules = $arr_json = [];
      $id            = base64_decode($enc_rule_id);
      $delete_status = $this->PropertyRulesModel->where('id','=',$id)->delete();
      if($delete_status)
      {
         $arr_json['status']  = 'success';
         $arr_json['message'] = 'Property rule deleted successfully.';
      }
      else
      {
         $arr_json['status']  = 'error';
         $arr_json['message'] = 'Error while delete a rule.';
      }
      return response()->json($arr_json);    
   }

   /*upload property images*/
   public function upload_property_image(Request $request)
   {
       $form_data = $arr_json = [];
       $property_image_status = '';
       
       $form_data = $request->all();
       $property_id = isset($form_data['enc_property_id'])?base64_decode($form_data['enc_property_id']):'';

       $property_image_count = $this->PropertyImagesModel->where('property_id',$property_id)->count();
       if($property_image_count>6) {
            $arr_json['status'] = 'upload_limit';
            $arr_json['msg']    = 'You can not upload more than 6 images.';
       }

       if(isset($form_data['property_image']) && count($form_data['property_image'])>0) {
            if($request->hasfile('property_image')) {
                if($request->file('property_image') != NULL) {
                    $file = $request->file('property_image');
                    $filename = rand(1111,9999);
                    $original_file_name = $file->getClientOriginalName();
                    $fileExt            = $file->getClientOriginalExtension();
                    $fileName           = sha1(uniqid().$filename.uniqid()).'.'.$fileExt;
                    if(in_array($fileExt,['png','jpg','jpeg','gif','JPG','PNG','JPEG','GIF'])) {
                        $property_image_status = $this->PropertyImagesModel->create(['property_id' => $property_id, 'image' => $fileName])->id;
                        $file->move($this->property_image_base_path,$fileName);
                    }
                    else {
                        $arr_json['status'] = 'error';
                        $arr_json['msg']    = 'Invalid image extension';
                    }
                }
            }
        }

        if($property_image_status) {
            $arr_json['status'] = 'success';
            $arr_json['msg']    = 'Image Uploaded successfully';
            $arr_json['insert_id'] = $property_image_status;
        }
        return response()->json($arr_json); 
   }

   public function delete_property_image($enc_id)
   {
      $arr_json = [];
      $delete_status = '';
      if(isset($enc_id) && $enc_id!="")
      {
          $image_id  = base64_decode($enc_id);
          $obj_property_image = $this->PropertyImagesModel->where('id',$image_id)->first();
          if(isset($obj_property_image->image) && $obj_property_image->image!="")
          {
              $path  = $this->property_image_base_path.$obj_property_image->image;
              if(file_exists($path))
              {
                unlink($path);
              }
              $delete_status = $obj_property_image->delete();
          }
      }
      if($delete_status)
      {
         Session::flash('success','Property image deleted successfully.');
      }
      else
      {
        Session::flash('error','Error while deleteing an image.');
      }
      return redirect()->back();
   }

   public function remove_property_image(Request $request)
   {
     $form_data = $arr_json = [];
     $form_data = $request->all();
     $image_id  = isset($form_data['image_id'])?$form_data['image_id']:'';

      if(isset($image_id) && $image_id!="")
      {
          $image_id  = $image_id;
          $obj_property_image = $this->PropertyImagesModel->where('id',$image_id)->first();
          if(isset($obj_property_image->image) && $obj_property_image->image!="")
          {
              $path  = $this->property_image_base_path.$obj_property_image->image;
              if(file_exists($path))
              {
                unlink($path);
              }
              $delete_status = $obj_property_image->delete();
          }
      }
      if($delete_status)
      {
            $arr_json['status']    = 'success';
            $arr_json['msg']       = 'Property image deleted successfully';
      }
      else
      {
            $arr_json['status']    = 'error';
            $arr_json['msg']       = 'Property image not deleted';
      }
      return response()->json($arr_json); 
   }

   public function favourite($property_id)
   {
      $favourite_status = '';
      $form_data = $arr_favourite  = $arr_json = $arr_favourite_data = [];
   
      $arr_favourite['property_id']     = base64_decode($property_id);
      $arr_favourite['user_id']         = $this->user_id;
      $arr_favourite['favourite_from']  = "property listing";

      $does_exists = $this->FavouritePropertyModel
                        ->where('property_id',$arr_favourite['property_id'])
                        ->where('user_id',$arr_favourite['user_id'])->get();

      if(count($does_exists)>0)
      {
         $where_cnd = array(
                'property_id' => $arr_favourite['property_id'],
                'user_id'     => $arr_favourite['user_id']
              );

            $del_favourite = $this->FavouritePropertyModel->where($where_cnd)->first()->delete();
            if($del_favourite)
            {
               $arr_json['property_id']  = $property_id;
               $arr_json['new_property_id']  = $arr_favourite['property_id'];
               $arr_json['favourite']    = 'remove'; 
               $arr_json['status']       = 'success';
               $arr_json['message']      = 'Property successfully removed from your favourite list';
            }
            else
            {
              $arr_json['property_id']  =  $property_id;
              $arr_json['new_property_id']  =  $arr_favourite['property_id'];
               $arr_json['status']      = 'error';
               $arr_json['message']     = 'Problem occur while removing property from favourite list';
            }
            return response()->json($arr_json); 
      }
      elseif(count($does_exists)==0)
      {
          $favourite_status = $this->FavouritePropertyModel->create($arr_favourite);
          if($favourite_status)
          {
            $arr_json['property_id']  =  $property_id;
             $arr_json['new_property_id']  =  $arr_favourite['property_id'];
             $arr_json['favourite']   = 'add'; 
             $arr_json['status']      = 'success';
             $arr_json['message']     = 'Property has been added in your favourite list.';
          }
          else
          {
             $arr_json['property_id']  =  $property_id;
              $arr_json['new_property_id']  =  $arr_favourite['property_id'];
             $arr_json['status']       = 'error';
             $arr_json['message']      = 'Error while adding in favourite list.';
          }

         return response()->json($arr_json);
      }
   }

   public function delete_unavailbility($enc_id)
   {
      $arr_json      = [];
      $delete_status = '';
      if(isset($enc_id) && $enc_id!="")
      {
          $id = base64_decode($enc_id);
          $obj_data = $this->PropertyUnavailabilityModel->where('id','=',$id)->first();
          if($obj_data)
          {
            $delete_status = $obj_data->delete();
          }
      }
      if($delete_status)
      {
         Session::flash('success','Unavailability details deleted successfully.');
      }
      else
      {
        Session::flash('error','Error while deleteing an unavailability.');
      }
      return redirect()->back();
   }
}
