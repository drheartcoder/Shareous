<?php

namespace App\Http\Controllers\Front;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Common\Services\EmailService;
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
		$this->arr_view_data['page_title']    = $this->module_title;
		return view('front.auth.forgot_password',$this->arr_view_data);  
	}
	public function postEmail(Request $request)
    {
        $arr_credencials = [];
        $this->validate($request,['email' => 'required|email']);

        $arr_credencials['email'] = $request->input('email');
        //$arr_credencials['guard'] = 'users';

        $obj_user = $this->UserModel->where('email',$request->input('email'))->first();
        if($obj_user)
        {
            //send password reset link
            $response = Password::sendResetLink($arr_credencials,function($m)
            {
                $m->subject(config('app.project.name').' : Your Password Reset Link');
            });

            switch ($response)
            {
                case Password::RESET_LINK_SENT:
                Session::flash('success', 'We have e-mailed your password reset link!');
                return redirect()->back()->with('status', trans($response));

                case Password::INVALID_USER:
                Session::flash('invalid_email', true);
                Session::flash('error', trans($response));
                return redirect()->back();
            }
        }
        else
        {
            Session::flash('error', 'Sorry! Email id does not exists');
            return redirect()->back();
        }
    }

    public function getResetPassword($token = null)
    {
        if(is_null($token)) 
        {
            return redirect($this->module_url_path)->with('error_login', 'Your reset password link has been expired.');
        }

        $password_reset = DB::table('users_password_resets')->where('token',$token)->first();
      
        if($password_reset != NULL)
        {
            $this->arr_view_data['token']            = $token;
            $this->arr_view_data['password_reset']   = (array)$password_reset;
            $this->arr_view_data['page_title']       = 'Reset Password';

            return view('front.auth.reset_password',$this->arr_view_data);    
        }
        else
        {
           return redirect($this->module_url_path)->with('error','Your password reset link was expired.');
        }
    }

    public function postResetPassword(Request $request)
    {
        $this->validate($request, [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {

            case Password::PASSWORD_RESET:

            return redirect('/login')->with('success','Your Password has been reset successfully');

            default:

            return redirect()->back()
            ->withInput($request->only('email'))
            ->with('error', trans($response))
            ->withErrors(['email' => trans($response)]);
        }
    }

}
