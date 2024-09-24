<?php

namespace App\Http\Controllers\Support;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;
use App\Models\SupportTeamModel;
use App\Common\Services\EmailService;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Session;
use Config;


class PasswordController extends Controller 
{
    /*
    Auther : Sagar Pawar
    Comments: Controller to manage forgot password process
    */
    use ResetsPasswords;
    private $auth;
    protected $redirectTo = '/support';
    protected $broker     = 'support';
    public function __construct(SupportTeamModel $support_team_model, EmailService $email_service)
    {
        $this->arr_view_data      = [];
        $this->module_title       = "Support";
        $this->module_view_folder = "support.auth";
        $this->auth               = auth()->guard('support');
        $this->support_panel_slug = config('app.project.support_panel_slug');
        $this->module_url_path    = url($this->support_panel_slug);
        $this->SupportTeamModel   = $support_team_model;
        $this->EmailService       = $email_service;

        /*Important set auth.defaults.passwords to support*/
        Config::set("auth.defaults.passwords","support");
    }
    
    public function postEmail(Request $request)
    {
        $arr_credencials = [];
        $this->validate($request, ['email' => 'required|email']);
        $arr_credencials['email'] = $request->input('email');
        //$arr_credencials['guard'] = 'support_team';

        $obj_user = $this->SupportTeamModel->where('email',$request->input('email'))->first();
        if($obj_user)
        {
            $response = Password::sendResetLink($arr_credencials, function($m)
            {
                $m->subject(config('app.project.name').' : Your Password Reset Link');
            });

            switch ($response)
            {
                case Password::RESET_LINK_SENT:
                Session::flash('success_password', 'We have e-mailed your password reset link!');
                return redirect()->back()->with('status', trans($response));

                case Password::INVALID_USER:
                Session::flash('invalid_email', true);
                Session::flash('error_password', trans($response));
                return redirect()->back();
            }
        }
        else
        {
            Session::flash('error_password', 'Sorry! Email id does not exists');
            return redirect()->back();
        }
    }

    public function getReset($token = null)
    {
        if (is_null($token)) 
        {
            return redirect($this->module_url_path)->with('error_password', 'Your reset password link has been expired.');
        }

        $password_reset = DB::table('support_password_resets')->where('token',$token)->first();
       
        if($password_reset != NULL)
        {
            $this->arr_view_data['token']            = $token;
            $this->arr_view_data['password_reset']   = (array)$password_reset;
            $this->arr_view_data['support_panel_slug'] = $this->support_panel_slug;
            $this->arr_view_data['module_url_path']  = $this->module_url_path;

            return view('support.auth.reset_password',$this->arr_view_data);    
        }
        else
        {
            return redirect($this->module_url_path)->with('error_password', 'Your password reset link was expired.');
        }
    }

    public function postReset(Request $request)
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

            return redirect($this->module_url_path)->with('success_password', 'Your Password has been reset successfully');

            default:

            return redirect()->back()
            ->withInput($request->only('email'))
            ->with('error', trans($response))
            ->withErrors(['email' => trans($response)]);
        }
    }
}
