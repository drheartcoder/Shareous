<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Common\Services\ListingService;
use App\Models\FavouritePropertyModel;
use App\Models\ReviewRatingModel;

use Validator;
use Session;

class FavouriteController extends Controller
{
 	function __construct(ListingService $listing_service, FavouritePropertyModel $favourite_property_model,ReviewRatingModel $review_rating_model)
	{
		$this->arr_view_data               = [];
		$this->module_title                = 'Property';
		$this->module_view_folder          = 'front.property.favourite.';
		$this->FavouritePropertyModel      = $favourite_property_model;
		$this->auth                        = auth()->guard('users');
		$this->module_url_path             = url('/').'/property';
		$this->ListingService              = $listing_service;
		$this->property_image_public_path  = url('/').config('app.project.img_path.property_image');
		$this->property_image_base_path    = base_path().config('app.project.img_path.property_image');
		$this->ReviewRatingModel           = $review_rating_model;

		$this->user_id                     = '';
        $user                              = $this->auth->user();
          
        if($user)
        {
            $this->user_id = $user->id;
        }
	}

	public function index(Request $request)
	{
		$arr_property = $arr_property_review = [];

		$arr_property   = $this->ListingService->get_user_favorite_list($this->user_id);
		$obj_pagination = $arr_property['arr_pagination'];

		if($obj_pagination)
		{
			$arr_pagination = $obj_pagination->toArray();
		}

		$obj_property_review = $this->ReviewRatingModel->where('status', '1')->get();
        if(isset($obj_property_review) && $obj_property_review != null)
        {
	        $arr_property_review = $obj_property_review->toArray();
	    }

        if(count($arr_property['property_list']) > 0){
			foreach($arr_property['property_list'] as $key => $property){
				$total          = 0;
				$count          = 0;
				$tmp_str_rating = '';
				$no_reviews     = 0;

                if(isset($arr_property_review)){
                    foreach($arr_property_review as $rating) {
                        if($rating['property_id'] == $property['property_id']) {
                            $total += floatval($rating['rating']);
                            $count++;
                        }
                    }
                }

                if($count != 0 ){
                    $no_reviews = $total / $count;
                }
				$arr_property['property_list'][$key]['average_rating']         = $no_reviews;
				$arr_property['property_list'][$key]['total_property_reviews'] = $count;
			}
		}

		$this->arr_view_data['arr_property']               = $arr_property['property_list'];
		$this->arr_view_data['arr_property_review']        = $arr_property_review;
		$this->arr_view_data['obj_pagination']             = $obj_pagination;

		$this->arr_view_data['total']                      = isset($arr_pagination['total']) ? $arr_pagination['total']:'0';
		$this->arr_view_data['to']                         = isset($arr_pagination['to']) ? $arr_pagination['to']:'0';
		$this->arr_view_data['from']                       = isset($arr_pagination['from']) ? $arr_pagination['from']:'0';

		$this->arr_view_data['property_type']              = get_property_type();
		$this->arr_view_data['property_category']          = get_category();


		$this->arr_view_data['property_image_public_path'] = $this->property_image_public_path;
		$this->arr_view_data['property_image_base_path']   = $this->property_image_base_path;

		$this->arr_view_data['page_title']                 = 'List '.$this->module_title;
		$this->arr_view_data['module_url_path']            = $this->module_url_path;

		return view($this->module_view_folder.'index',$this->arr_view_data);
	}

	public function view($id=NULL)
	{
		$id     = base64_decode($id);
		if($id != null)
		{
			$this->arr_view_data['arr_property']  = $this->ListingService->get_property_details($id);
			$this->arr_view_data['arr_aminities'] = $this->ListingService->get_property_aminities($id);
			$this->arr_view_data['arr_rules']     = $this->ListingService->get_property_rules($id);
			$this->arr_view_data['arr_images']    = $this->ListingService->get_property_images($id);
		}

		$this->arr_view_data['page_title']                 = 'View '.$this->module_title;
		$this->arr_view_data['property_image_public_path'] = $this->property_image_public_path;
		$this->arr_view_data['property_image_base_path']   = $this->property_image_base_path;

		return view($this->module_view_folder.'view',$this->arr_view_data);
	}

	public function delete($id=null)
	{
		$id = base64_decode($id);
      	$delete_status = $this->FavouritePropertyModel->where('id','=',$id)->delete();

      	if($delete_status)
	    {
	        Session::flash('success','Property removed from favourite lists');
	    }
	    else
	    {
	       Session::flash('error','Error while removing property form favourite list.');
	    }
	      return redirect()->back();
	}
	  
}
