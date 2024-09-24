<?php

namespace App\Http\Controllers\Api;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Common\Services\EmailService;

use Validator;
use Session;
use Config;
use DB;

class PasswordController extends Controller
{
	use ResetsPasswords;
    private $auth;
    protected $redirectTo = '/front';
    protected $broker     = 'front';

    public function __construct(UserModel  $user_model,EmailService $email_service)
	{
		$this->arr_view_data      = [];
		$this->module_title       = "Forgot Password";
		$this->module_view_folder = "front.auth";
		$this->UserModel          = $user_model;
        $this->EmailService       = $email_service;
		$this->auth               = auth()->guard('users');
		$this->user_panel_slug    = 'forgot_password';
		$this->module_url_path    = url($this->user_panel_slug);
      
        /*Important set auth.defaults.passwords to front*/
        Config::set("auth.defaults.passwords","users");
	}
	public function index()
	{
        $this->arr_view_data['page_title'] = $this->module_title;
		return view('front.auth.forgot_password',$this->arr_view_data);  
	}

	public function postEmail(Request $request)
    {
        $arr_rules = $user_data = [];

        $arr_rules['email'] = 'required';
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails()) {
            $status  = 'error';
            $message = 'Fill all required fields.';
            return $this->build_response($status, $message);
        } else {
            
            $obj_user = $this->UserModel->where('email',$request->input('email'))->first();

            if (isset($obj_user) && count($obj_user) > 0) {
                
                if($obj_user->social_login == 'no' ) {

                    if($obj_user->status == 1 ) {

                        $arr_credencials['email'] = $request->input('email');

                        //send password reset link
                        $response = Password::sendResetLink($arr_credencials,function($m)
                        {
                            $m->subject(config('app.project.name').' : Your Password Reset Link');
                        });

                        switch ($response)
                        {
                            case Password::RESET_LINK_SENT:
                            $status  = 'success';
                            $message = 'Password sent successfully to your email id.';
                            return $this->build_response($status, $message);

                            case Password::INVALID_USER:
                            $status  = 'error';
                            $message = 'invalid email';
                            return $this->build_response($status, $message);
                        }
                    } else {
                        $status  = 'error';
                        $message = 'Your account blocked by admin';
                        return $this->build_response($status, $message);
                    }
                    
                } else {
                    $status  = 'error';
                    $message = "Password can't add to socail login account";
                    return $this->build_response($status, $message);
                }

            } else {
                $status  = 'error';
                $message = 'This email is not registered with us';
                return $this->build_response($status, $message);
            }
        }
    }
}
