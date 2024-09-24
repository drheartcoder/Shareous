<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Validator;
use Session;

class AuthController extends Controller
{
	function __construct(AdminModel $admin_model)
	{
    $this->arr_view_data      = [];
    $this->auth               = auth()->guard('admin');
    $this->module_title       = "Admin";
    $this->module_view_folder = "admin.auth";
    $this->admin_panel_slug   = config('app.project.admin_panel_slug');
    $this->module_url_path    = url($this->admin_panel_slug);
    $this->AdminModel         = $admin_model;

       /*----------------Admin Panel Theme Color Helper----------------*/
    $this->theme_color      	= theme_color_admin();
   }

	/*
    | Comment : logout admin authetication system
    | auther  :Sagar Pawar
    */

    public function login()
    {
        if($this->auth->user())
        {
            return redirect($this->module_url_path.'/dashboard');
        }
        $this->arr_view_data['module_title']      = $this->module_title." Login";
        $this->arr_view_data['theme_color']       = $this->theme_color;
        $this->arr_view_data['admin_panel_slug']   = $this->admin_panel_slug;
        return view($this->module_view_folder.'.login',$this->arr_view_data);
    }

    public function validate_login(Request $request)
    {
      $arr_rules      = array();
      $status         = false;

      $arr_rules['email']          = "required|email";
      $arr_rules['password']       = "required";

      $validator = validator::make($request->all(),$arr_rules);

      if ($validator->fails()) 
      {
        return back()->withErrors($validator)->withInput();
      }

      $obj_group_admin = $this->AdminModel->where('email',$request->only('email'))->first();

      if($obj_group_admin) 
      {
          if($this->auth->attempt($request->only('email', 'password')))
          {
              //Session::flash('success', 'Login successfully.');
              return redirect($this->module_url_path.'/dashboard');
          }
          else
          {
              Session::flash('error', 'Invalid login credential.');
          }
      }
      else
      {
          Session::flash('error','Invalid login credentials.');
      }
    
    return redirect()->back();
}

public function logout()
{
    $this->auth->logout();
    //Session::flush();
    return redirect($this->module_url_path.'/login');
}
}
