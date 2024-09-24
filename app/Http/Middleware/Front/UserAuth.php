<?php

namespace App\Http\Middleware\Front;

use Closure;

class UserAuth
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
        $this->auth = auth()->guard('users');  

        if($this->auth->user()!=null)
        {
            $get_active = \DB::table('users')->where('id',$this->auth->user()->id)->where('status','1')->count();      
            if($get_active <= 0){
                return redirect(url('/logout'));
            } 
            return $next($request);
        }
        else
        { 
            return redirect(url('/'));
        }

    }
}
