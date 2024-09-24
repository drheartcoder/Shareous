<?php

namespace App\Http\Middleware\Support;

use Closure;
use App\Models\NotificationModel;
class SupportAuth
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
       $arr_notification = [];

        /*-----------------------------------------------------------------
            Code for {enc_id} or {extra_code} in url
        ------------------------------------------------------------------*/
        $request_path = $request->route()->getCompiled()->getStaticPrefix();
        $request_path = substr($request_path,1,strlen($request_path));
        // dump($request_path);
        /*-----------------------------------------------------------------
                End
        -----------------------------------------------------------------*/        
       if($request_path != 'support/notification/get_notifications_count')
       {
           $this->auth = auth()->guard('support');

           if($this->auth->user() && $this->auth->user()->status == '1')
           {
                $arr_data['user_name']          = $this->auth->user()->user_name;
                $arr_data['profile_image']      = $this->auth->user()->profile_image;
                $arr_data['support_level']      = $this->auth->user()->support_level;
                $arr_data['support_panel_slug'] = config('app.project.support_panel_slug');

                /*Get notification count*/
                //$obj_notification = NotificationModel::where(['is_read'=>0,'user_type'=>3])->orderBy('id','desc')->get();

                $obj_notification = NotificationModel::where(['is_read'=>0,'receiver_type'=>3,'receiver_id'=>$this->auth->user()->id])->orderBy('id','desc')->get();

                if(isset($obj_notification) && $obj_notification!=null)
                {
                    $notification_count = count($obj_notification);
                }
                
                view()->share('notification_count',$notification_count);
                view()->share('shared_web_support_details', $arr_data);
                
                return $next($request);
            }
            else
            { 
                return redirect('support/logout');
            }
        }else{
            return $next($request);
        }
    }
}
