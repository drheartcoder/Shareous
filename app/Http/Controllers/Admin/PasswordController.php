<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;
use App\Models\AdminModel;
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
    protected $redirectTo = '/admin';
    protected $broker     = 'admin';
    public function __construct()
    {
        // $this->middleware('guest');
        $this->arr_view_data      = [];
        $this->module_title       = "Admin";
        $this->module_view_folder = "admin.auth";
        $this->auth               = auth()->guard('admin');
        $this->admin_panel_slug   = config('app.project.admin_panel_slug');
        $this->module_url_path    = url($this->admin_panel_slug);

        /*Important set auth.defaults.passwords to admin*/
        Config::set("auth.defaults.passwords","admin");
    }
    
    public function postEmail(Request $request)
    {
        $arr_credencials = [];
        $this->validate($request, ['email' => 'required|email']);

        $arr_credencials['email'] = $request->input('email');
        //$arr_credencials['guard']   = 'admin';

        $response = Password::sendResetLink($arr_credencials,function($m)
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

    public function getReset($token = null)
    {
        if (is_null($token)) 
        {
            return redirect($this->module_url_path)->with('error', 'Your reset password link has been expired.');
        }

        $password_reset = DB::table('admin_password_resets')->where('token',$token)->first();

        if($password_reset != NULL)
        {
            $this->arr_view_data['token']            = $token;
            $this->arr_view_data['password_reset']   = (array)$password_reset;
            $this->arr_view_data['admin_panel_slug'] = $this->admin_panel_slug;
            $this->arr_view_data['module_url_path']  = $this->module_url_path;

            return view('admin.auth.reset_password',$this->arr_view_data);    
        }
        else
        {
            return redirect($this->module_url_path)->with('error', 'Your password reset link was expired.');
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

            return redirect($this->module_url_path)->with('success','Your Password has been reset successfully');

            default:

            return redirect()->back()
            ->withInput($request->only('email'))
            ->with('error', trans($response))
            ->withErrors(['email' => trans($response)]);
        }
    }
}
