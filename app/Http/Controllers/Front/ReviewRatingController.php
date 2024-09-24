<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Common\Services\ListingService;
use App\Models\ReviewRatingModel;
use App\Models\PropertyModel;
use App\Http\Controllers\Controller;
use App\Models\UserModel;

use paginate;
use Session;
use DB;

class ReviewRatingController extends Controller
{
    public function __construct(UserModel $user_model,ListingService $listing_service, ReviewRatingModel $review_rating_model,PropertyModel $property_model)
    {
        $this->ListingService             = $listing_service;
        $this->PropertyModel              = $property_model;

        $this->arr_view_data              = [];
        $this->module_title               = 'My Review & Ratingt';
        $this->module_view_folder         = 'front.review_rating';
        $this->auth                       = auth()->guard('users');
        $this->module_url_path            = url('/').'/review-rating';
        $this->property_image_public_path = url('/').config('app.project.img_path.property_image');
        $this->property_image_base_path   = base_path().config('app.project.img_path.property_image');

        $this->ReviewRatingModel          = $review_rating_model;
        $this->UserModel                  = $user_model;
        
        $user = $this->auth->user();
        if($user) {
            $this->user_id = $user->id;
        }  
    }

    public function index()
    {   
        $page_link = '';
        $user_type = Session::get('user_type');

        if(isset($user_type) && $user_type == '1') {
            $arr_review = $arr_review_rating = [];
            $total = $count = $no_reviews = 0;

            $obj_property_review = $this->ReviewRatingModel
                                        ->select(
                                            'review_rating.*',
                                            'property.property_type_id',
                                            'property.property_name',
                                            'property.property_name_slug',
                                            'property_images.image',
                                            'property_type.name as property_type_name'
                                        )
                                        ->join('property','property.id','=','review_rating.property_id')
                                        ->join('property_images','property_images.property_id','=','review_rating.property_id')
                                        ->join('property_type','property_type.id','=','property.property_type_id')
                                        ->groupBy('review_rating.id','property_images.property_id')
                                        ->orderBy('review_rating.id','DESC')
                                        ->where('review_rating.status','1')
                                        ->where('rating_user_id',$this->user_id)
                                        ->paginate(5);

            if($obj_property_review) {
                $arr_property_review = $obj_property_review->toArray();
                $page_link = $obj_property_review->links();
            }

            $this->arr_view_data['arr_property_review']        = $arr_property_review;
            $this->arr_view_data['page_link']                  = $page_link;
            $this->arr_view_data['module_url_path']            = $this->module_url_path;
            $this->arr_view_data['page_title']                 = $this->module_title;
            $this->arr_view_data['page_title']                 = 'My Review & Rating';
            $this->arr_view_data['property_image_public_path'] = $this->property_image_public_path;
            $this->arr_view_data['property_image_base_path']   = $this->property_image_base_path;

            return view($this->module_view_folder.'.review_rating',$this->arr_view_data);
        }
    }

    public function host_review_rating()
    {
        $page_link = '';
        $user_type = Session::get('user_type');
        $temp_arr  = $result_arr = $arr_review_rating = [];

        if(isset($user_type) && $user_type == '4') {
            $arr_property_review = DB::table('review_rating')
                                    ->select(
                                                'review_rating.*',
                                                'property.property_type_id',
                                                'property.property_name',
                                                'property.user_id',
                                                'property.property_name_slug',
                                                'property_images.image',
                                                'property_type.name as property_type_name'
                                            )
                                    ->join('property','property.id','=','review_rating.property_id')
                                    ->join('property_images','property_images.property_id','=','review_rating.property_id')
                                    ->join('property_type','property_type.id','=','property.property_type_id')
                                    ->where('property.user_id',$this->user_id)
                                    ->groupBy('review_rating.id','property_images.property_id')
                                    ->orderBy('review_rating.id','DESC')
                                    ->paginate(5);

            if(isset($arr_property_review) && count($arr_property_review)) {
                $result_arr = $arr_property_review->toArray();
                $page_link = $arr_property_review->links();
            }

            $this->arr_view_data['page_link']                  = $page_link;
            $this->arr_view_data['arr_review_rating']          = $result_arr;
            $this->arr_view_data['module_url_path']            = $this->module_url_path;
            $this->arr_view_data['page_title']                 = $this->module_title;
            $this->arr_view_data['page_title']                 = 'My Review & Rating';
            $this->arr_view_data['property_image_public_path'] = $this->property_image_public_path;
            $this->arr_view_data['property_image_base_path']   = $this->property_image_base_path;

            return view($this->module_view_folder.'/host_review_rating',$this->arr_view_data);
        }
    }
}
