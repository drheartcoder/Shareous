<?php

namespace App\Http\Middleware\Front;
use App\Models\SiteSettingsModel;
use App\Models\SleepingArrangementModel;
use App\Models\NotificationModel;
use App\Models\HostVerificationModel;
use App\Models\CurrencyModel;
use App\Models\TestimonialsModel;
use Closure;
use Session;
use DB;
class GeneralMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        /*check session has currency rate if not then set conversionrates*/
        if(Session::has('conversion_rates') == false) {
            store_currency_session('INR');
        }

        $arr_site_settings = [];
        $this->auth = auth()->guard('users');  

        /*route handling*/
        $current_url_route = app()->router->getCurrentRoute()->uri();

        $arr_except[] = 'login';
        $arr_except[] = 'process_login';
        $arr_except[] = 'sign_up';
        $arr_except[] = 'forgot_password';
        
        if($this->auth->user() && in_array($current_url_route,$arr_except)) {
            return redirect(url('/'));
        }

        $site_setting = SiteSettingsModel::select('*')->first();
        if(isset($site_setting) && $site_setting != null) {
            $arr_site_settings = $site_setting->toArray();
            if(isset($arr_site_settings['site_status']) && $arr_site_settings['site_status'] == 0) {
                return response(view('errors.under_maintenance'));
            }
        }

        // get all the currency data
        $obj_currency = CurrencyModel::select('*')->get();
        if(isset($obj_currency) && $obj_currency != null) {
            $arr_currency = $obj_currency->toArray();
            view()->share('arr_currency',$arr_currency);
        }

        $arr_property_type = [];
        $obj_property_type = DB::table('property_type')->select('*')->where('status',1)->get();
        if(isset($obj_property_type) && $obj_property_type != null) {
            $arr_property_type = $obj_property_type;
            view()->share('arr_property_type',$arr_property_type);
        }

        if($this->auth->user()) {
            $arr_data                  = [];
            $arr_data['display_name']  = $this->auth->user()->display_name;
            $arr_data['first_name']    = $this->auth->user()->first_name;
            $arr_data['last_name']     = $this->auth->user()->last_name;
            $arr_data['user_name']     = $this->auth->user()->user_name;
            $arr_data['profile_image'] = $this->auth->user()->profile_image;
            $arr_data['user_type']     = $this->auth->user()->user_type;
            $arr_data['user_id']       = $this->auth->user()->id;
            $arr_data['social_login']  = $this->auth->user()->social_login;
            $arr_data['email']         = $this->auth->user()->email;
            $arr_data['mobile_number'] = $this->auth->user()->mobile_number;
            $arr_data['country_code']  = $this->auth->user()->country_code;

            $profile_image_base_path   = public_path().config('app.project.img_path.user_profile_images');
            $profile_image_public_path = url('/').config('app.project.img_path.user_profile_images');

            view()->share('user_details', $arr_data);
            view()->share('user_image_base_path', $profile_image_base_path);
            view()->share('user_image_public_path', $profile_image_public_path);
            
            $obj_process_host = HostVerificationModel::where(['user_id' => $this->auth->user()->id])->where('status', 3)->count();
            if($obj_process_host > 0) {
                view()->share('in_process_host',"yes");
            }
            else {
                view()->share('in_process_host',"no");
            }

            /*Get notification count*/
            $obj_notification = NotificationModel::where(['is_read' => 0, 'receiver_type' => Session::get('user_type'), 'receiver_id' => $this->auth->user()->id])->orderBy('id','desc')->get();

            if(isset($obj_notification) && $obj_notification != null) {
                $notification_count = count($obj_notification);
            }
            view()->share('notification_count',$notification_count);
        }
        view()->share('arr_global_site_setting',$arr_site_settings);

        $sleeping_arrangement_arr = $sleeping_arrangement_obj = [];

        $sleeping_arrangement_obj = SleepingArrangementModel::where('is_active','1')->get();
        
        if(count($sleeping_arrangement_obj) > 0) {
            $sleeping_arrangement_arr = $sleeping_arrangement_obj->toArray();
        }

        view()->share('sleeping_arrangement_arr',$sleeping_arrangement_arr);        
        
        $arr_testimonials = [];
        $obj_testimonials = TestimonialsModel::where('status', '1')->orderByRaw("RAND()")->take(3)->get();
        if(isset($obj_testimonials) && $obj_testimonials != null) {
            $arr_testimonials = $obj_testimonials->toArray();
        }
        view()->share('arr_testimonials', $arr_testimonials);        

        $testimonial_image_public_img_path = url('/').config('app.project.img_path.testimonial_image');
        $testimonial_image_base_img_path   = public_path().config('app.project.img_path.testimonial_image');

        view()->share('testimonial_image_public_img_path',$testimonial_image_public_img_path);        
        view()->share('testimonial_image_base_img_path',$testimonial_image_base_img_path);

        return $next($request);
    }
}
