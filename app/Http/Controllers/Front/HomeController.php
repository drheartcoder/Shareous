<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\PropertyModel;
use App\Models\TestimonialsModel;
use App\Models\ReviewRatingModel;

use DB;
use Session;

class HomeController extends Controller
{
    public function __construct(PropertyModel $property_model,TestimonialsModel $testimonials_model, ReviewRatingModel $review_rating_model)
    {
        $this->array_view_data                   = [];
        $this->module_title                      = 'Home';
        $this->module_view_folder                = 'front.home.';
        $this->PropertyModel                     = $property_model;
        $this->TestimonialsModel                 = $testimonials_model;

        $this->testimonial_image_public_img_path = url('/').config('app.project.img_path.testimonial_image');
        $this->testimonial_image_base_img_path   = public_path().config('app.project.img_path.testimonial_image');

        $this->property_image_base_path          = base_path().config('app.project.img_path.property_image');
        $this->property_image_public_path        = url('/').config('app.project.img_path.property_image');

        $this->ReviewRatingModel                 = $review_rating_model;
    }

    public function index()
    {   
        $arr_popular_prop = [];

        // unset prvious search critearias
        Session::forget('BookingRequestData');
        // end unset prvious search critearias
        
        $arr_testimonials = $arr_featured_data = $arr_property_review = $arr_popular_prop = [];

        $this->array_view_data['page_title'] = $this->module_title;
        $arr_featured_data = DB::table('property')->select('*')
                                                  ->where('is_featured','yes')
                                                  ->where('admin_status','2')
                                                  ->orderByRaw("RAND()")
                                                  ->orderBy('property.id','DESC')
                                                  ->limit(6)
                                                  ->get();

        $obj_testimonials = $this->TestimonialsModel->where('status', '1')->orderByRaw("RAND()")->take(3)->get();
        if(isset($obj_testimonials) && $obj_testimonials!=null) {
            $arr_testimonials    = $obj_testimonials->toArray();
        }

        $obj_property_review     = $this->ReviewRatingModel->where('status', '1')->get();
        if (isset($obj_property_review) && $obj_property_review != null) {
            $arr_property_review = $obj_property_review->toArray();
        }

        $obj_popular_prop = $this->PropertyModel->select('id', 'property_name', 'description', 'property_name_slug')
                                                ->with(['property_images' => function($q_pi) {
                                                    $q_pi->select('property_id', 'image')->orderBy('id', 'RAND()');
                                                }])
                                                ->where('admin_status','2')
                                                ->has('property_images')
                                                ->orderBy('id','DESC')
                                                ->limit(8)
                                                ->get();
        if($obj_popular_prop)
        {
            $arr_popular_prop = $obj_popular_prop->toArray();
        }
        
        $this->module_url_path                                      = url('/').'/property';
        $this->array_view_data['home_url_path']                     = url('/');
        $this->array_view_data['module_url_path']                   = $this->module_url_path;
        $this->array_view_data['arr_popular_prop']                  = $arr_popular_prop;
        $this->array_view_data['arr_property_review']               = $arr_property_review;
        $this->array_view_data['arr_featured_data']                 = $arr_featured_data;
        $this->array_view_data['arr_testimonials']                  = $arr_testimonials;
        $this->array_view_data['testimonial_image_public_img_path'] = $this->testimonial_image_public_img_path;
        $this->array_view_data['testimonial_image_base_img_path']   = $this->testimonial_image_base_img_path;
        $this->array_view_data['property_image_public_path']        = $this->property_image_public_path ;
        $this->array_view_data['property_image_base_path']          = $this->property_image_base_path ;
        $this->array_view_data['page_title']                        = $this->module_title;

        return view($this->module_view_folder.'index', $this->array_view_data);
    }

    public function checklogin()
    {
        if (auth()->guard('users')->user()==null) {
            if (Session::get('notification_url') =='' && !empty(Session::get('notification_url'))) {
                Session::set('notification_url','home');
            } else {
                Session::put('notification_url','');
                Session::set('notification_url','home');
            }

            echo 'success';
            exit;
        }     
    }

   /*
    | Function  : switch website currency
    | Author    : Deepak Arvind Salunke
    | Date      : 02/06/2018
    | Output    : Success or Error
    */

    public function set_currency($currency = false)
    {
        $arr_json['status'] = 'error';
        $currency = ($currency != '') ? $currency : 'INR';

        if( $currency == 'USD' ) {
            $currency_icon = '<i class="fa fa-usd" aria-hidden="true"></i>';
        }
        else if( $currency == 'EUR' ) {
            $currency_icon = '<i class="fa fa-eur" aria-hidden="true"></i>';
        }
        else {
            $currency_icon = '<i class="fa fa-inr" aria-hidden="true"></i>';
        }

        Session::forget('get_currency');
        Session::set('get_currency', $currency);

        Session::forget('get_currency_icon');
        Session::set('get_currency_icon', $currency_icon);

        Session::forget('conversion_rates');

        if (store_currency_session($currency)) {
            $arr_json['status'] = 'success';
        }

        return response()->json($arr_json);
    }
}
