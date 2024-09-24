<?php
use App\Models\CategoryModel;
use App\Models\PropertytypeModel;
use App\Models\PropertyModel;
use App\Models\FavouritePropertyModel;
use App\Models\CurrencyModel;
use App\Models\SleepingArrangementModel;
use App\Models\UserModel;
use App\Models\ReviewRatingModel;
use App\Models\GstDataModel;
use App\Models\SacDataModel;

use Tymon\JWTAuth\Exceptions\JWTException;

function validate_user_jwt_token()
{
	try {
		$user  = JWTAuth::parseToken()->authenticate(); 			
		if ($user && isset($user->id)) {
			return $user->id;
		} else {
			return 0;
		}
	}
	catch(JWTException $e) {
		return 0;
	}
	return 0;
}

function verify_fb_token($fb_access_token)
{
	$fb_req = curl_init();
	curl_setopt($fb_req,CURLOPT_URL,"https://graph.facebook.com/me?access_token=".$fb_access_token);
	curl_setopt($fb_req,CURLOPT_RETURNTRANSFER,1);
	$response = curl_exec($fb_req);
	curl_close($fb_req);
	$arr_decoded_dara =  json_decode($response,TRUE);
	return !isset($arr_decoded_dara['error']);
}

/*Get user*/
function get_user_details($id)
{
    $obj_user = [];
	if($id != '') {
    	$obj_user = UserModel::where('id',$id)->first();
    	if(isset($obj_user) && $obj_user != null) {
    		return $obj_user;
    	}
	}
	return $obj_user;
}

function get_sleeping_arrangement()
{
	$sleeping_arrangement_arr = $sleeping_arrangement_obj = [];

  	$sleeping_arrangement_obj = SleepingArrangementModel::where('is_active','1')->get();
  	if(count($sleeping_arrangement_obj) > 0) {
    	$sleeping_arrangement_arr = $sleeping_arrangement_obj->toArray();
  	}
  	return $sleeping_arrangement_arr;
}

function get_sleeping_arrangment_name($id = false)
{
	$sleeping_arrangement_arr = [];

	if($id != false) {
		$sleeping_arrangement_arr = SleepingArrangementModel::where('id',$id)->first();
		if(count($sleeping_arrangement_arr) > 0) {
			return $sleeping_arrangement_arr->value;
		} else {
			return null;
		}
	}
  	return null;
}

function get_category($id = false)
{
	$arr_data = [];
	$obj_data = '';
	
	if($id != false) {
		$obj_data = CategoryModel::where('id','=',$id)->select('id','category_name','slug','status')->first();
	} else {
		$obj_data = CategoryModel::where('status','=',1)->select('id','category_name','slug','status')->get();
	}

	if($obj_data) {
		$arr_data = $obj_data->toArray();
	}
	return $arr_data;
}

function get_currency($currency_id = false)
{
	$arr_currency = [];
	$obj_currency = '';

	if($currency_id != null) {
		$obj_currency = CurrencyModel::where('id',$currency_id)->first();	
	} else {
		$obj_currency = CurrencyModel::get();
	}

	if(isset($obj_currency) && $obj_currency != null) {
		$arr_currency = $obj_currency->toArray();
	}
	return $arr_currency;
}

function get_property_type($id = false)
{
	$arr_data = [];
	$obj_data = '';
	
	if($id != false) {
		$obj_data = PropertytypeModel::where('id','=',$id)->select('id','name','status')->first();
	} else {
		$obj_data = PropertytypeModel::where('status','=',1)->select('id','name','status')->get();
	}

	if($obj_data) {
		$arr_data = $obj_data->toArray();
	}
	return $arr_data;
}

function get_incomplete_property($id = false)
{
	$property_id = '';
	if($id != false) {
		$obj_data = PropertyModel::where('user_id', $id)->where('property_status','=',2)->select('id','property_status')->first();
		if($obj_data) {
			$property_id = isset($obj_data->id) ? $obj_data->id : '';
		}
	}
	return $property_id;
}

function get_user_favorite_property_id($id = null)
{
	$arr_favorite = [];
	
	if($id == null | $id == 0) {
		return $arr_favorite;
	}

	$obj_favorite = FavouritePropertyModel::where('user_id', $id)->get(['property_id']);
	if($obj_favorite) {
		$obj_favorite = $obj_favorite->pluck('property_id');
		$arr_favorite = $obj_favorite->toArray();
	}
	return $arr_favorite;
}

function get_property_details($id = false)
{
	$arr_property = [];
	$obj_property = '';
	
	if($id != false) {
		$obj_property = PropertyModel::where('id','=',$id)->first();
		if($obj_property) {
			$arr_property = $obj_property->toArray();
		}
	}
	return $arr_property;
}

function get_property_image($property_id)
{
	$arr_image = DB::table('property_images')->select('image')->where('property_id',$property_id)->first();
	if (isset($arr_image)) {
		return $arr_image->image;
	} else {
		return '';
	}
}

function check_favorite_property($user_id = false,$property_id = false)
{
	$arr_favorite = [];
	if($user_id != false && $property_id != false) {
		$arr_favorite = FavouritePropertyModel::where('user_id','=',$user_id)->where('property_id','=',$property_id)->first();
		if (count($arr_favorite) > 0) {
			return $arr_favorite;
		} else {
			return $arr_favorite;
		}
	}
}

function get_GST_tax()
{
	$get_data = [];
	$get_data = DB::table('site_settings')->first();	

	return $get_data;
}

function get_review_details($id = false)
{
	$arr_favorite = [];
	if($id != false) {
		$arr_favorite = ReviewRatingModel::where('id','=',$id)->first();
		if (count($arr_favorite) > 0) {
			return $arr_favorite;
		} else {
			return $arr_favorite;
		}
	}
}

function get_property_type_slug($property_type_id)
{
	$arr_property = PropertyTypeModel::select('name')->where('id',$property_type_id)->first();
	if (isset($arr_property)) {
		return strtolower(trim(str_slug($arr_property->name, '-')));
	} else {
		return '';
	}
}

function check_host_gst($property_id = false)
{
	$apply_gst = "no";

	$obj_property = PropertyModel::select('user_id')->where('id', $property_id)->with('user_details')->first();
	if ($obj_property) {
		$arr_property = $obj_property->toArray();

		$gstin = $arr_property['user_details']['gstin'];

		if( !empty($gstin) && $gstin != null ) {
			$apply_gst = "yes";
		}
	}

	return $apply_gst;
}

function get_gst_data($price = false, $property_type = false)
{
	$gst = 0;

	if( $price != false && $property_type != false) {

		$obj_gstdata = GstDataModel::select('gst')
						->where('property_type', $property_type)
						->whereRaw(" ('".$price."' BETWEEN min_price AND max_price) OR ('".$price."' BETWEEN min_price AND max_price) OR ( min_price BETWEEN '".$price."' AND '".$price."') OR ( max_price BETWEEN '".$price."' AND '".$price."') ")
						->first();

		if( $obj_gstdata ) {
			$arr_gstdata = $obj_gstdata->toArray();

			$gst = $arr_gstdata['gst'];
		}
	}

	return $gst;
}

function get_service_fee($price = false)
{
	$data = [];

	if( $price != false) {
		$site_settings = DB::table('site_settings')->select('admin_commission','gst')->first();
		$service_fee = ( $site_settings->admin_commission / 100 ) * $price;

		$service_fee_gst_amount = ( $site_settings->gst / 100 ) * $service_fee;

		$data['service_fee_percentage']     = $site_settings->admin_commission;
		$data['service_fee_gst_percentage'] = $site_settings->gst;
		$data['service_fee']                = number_format( ($service_fee - $service_fee_gst_amount) , 2, '.', '');
		$data['service_fee_gst_amount']     = number_format($service_fee_gst_amount, 2, '.', '');
	} else {
		$data['service_fee_percentage']     = 0;
		$data['service_fee_gst_percentage'] = 0;
		$data['service_fee']                = 0;
		$data['service_fee_gst_amount']     = 0;
	}

	return $data;
}

function get_sac($slug = false)
{
	$sac = '';
	if( $slug != false) {
		$get_data = DB::table('sac_data')->select('sac','name')->where('name',$slug)->first();
	}

	if( empty($sac) ) {
		$get_data = DB::table('sac_data')->select('sac','name')->where('name','shareous')->first();
	}

	if( $get_data ) {
		$sac = $get_data->sac;
	}

	return $sac;
}

?>