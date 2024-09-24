<?php

namespace App\Http\Middleware\Admin;

use Closure;
use App\Models\NotificationModel;

class AdminAuth
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
        $notification_count = 0;
        $request_path = $request->route()->getCompiled()->getStaticPrefix();
        $request_path = substr($request_path,1,strlen($request_path));   
        
        if($request_path != '')
        {
            $this->auth = auth()->guard('admin');  

            if($this->auth->user())
            {
                $arr_data['user_name']        = $this->auth->user()->user_name;
                $arr_data['profile_image']    = $this->auth->user()->profile_image;
                $arr_data['admin_panel_slug'] = config('app.project.admin_panel_slug');

                /*Get notification count*/
                $obj_notification = NotificationModel::where(['is_read'=>0,'receiver_type'=>2,'receiver_id'=>1])->orderBy('id','desc')->get();        
                if(isset($obj_notification) && $obj_notification!=null)
                {
                    $notification_count = count($obj_notification);
                }
                view()->share('notification_count',$notification_count);

                view()->share('shared_web_admin_details', $arr_data);
                
                return $next($request);
            }
            else
            { 
                return redirect('admin');
            }
        }
        else
        {
            return $next($request);
        }    
    }
}
