<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Session;
use Flash;

class AuthenticateMiddleware
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
        $arr_except     = array();
        $this->auth     = auth()->guard('admin');
        $admin_path     = config('app.project.admin_panel_slug');
        $admin_url_path = url(config('app.project.admin_panel_slug'));

        $arr_except[] = $admin_path;
        $arr_except[] = $admin_path.'/login';
        $arr_except[] = $admin_path.'/process_login';
        $arr_except[] = $admin_path.'/forgot_password';
        $arr_except[] = $admin_path.'/process_forgot_password';
        $arr_except[] = $admin_path.'/validate_admin_reset_password_link';
        $arr_except[] = $admin_path.'/reset_password';
        
        /*-----------------------------------------------------------------
            Code for {enc_id} or {extra_code} in url
        ------------------------------------------------------------------*/
        $request_path = $request->route()->getCompiled()->getStaticPrefix();
        $request_path = substr($request_path,1,strlen($request_path));
        /*-----------------------------------------------------------------
                End
        -----------------------------------------------------------------*/

        /*if( $this->auth->user() == null ) {
            return redirect(url($admin_path));
        }*/

        if(!in_array($request_path, $arr_except)) {
            if($request_path != $admin_path.'/notification/get_notifications') {
                $arr_user = $tmp_data = [];

                if($this->auth->user() == null) {
                    return redirect(url($admin_path));
                }

                return $next($request);    
            }
            else {
                return $next($request);  
            }
        }
        else {
            return $next($request); 
        }
    }

   
}
