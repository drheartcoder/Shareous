<?php

namespace App\Http\Controllers\Support;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SupportTeamModel;
use Validator;
use Session;

class AuthController extends Controller
{
	function __construct(SupportTeamModel $support_team_model)
	{
    $this->arr_view_data      = [];
    $this->auth               = auth()->guard('support');
    $this->module_title       = "Support";
    $this->module_view_folder = "support.auth";
    $this->support_panel_slug = config('app.project.support_panel_slug');
    $this->module_url_path    = url($this->support_panel_slug);
    $this->SupportTeamModel   = $support_team_model;

       /*----------------support Panel Theme Color Helper----------------*/
       // $this->theme_color 	= theme_color_support();

   }

	/*
    | Comment : logout support authetication system
    | auther  :Sagar Pawar
    */

    public function login()
    {
        
        if($this->auth->user())
        {
            return redirect($this->module_url_path.'/dashboard');
        }
        $this->arr_view_data['module_title']      = $this->module_title." Login";
        // $this->arr_view_data['theme_color']       = $this->theme_color;
        $this->arr_view_data['support_panel_slug']   = $this->support_panel_slug;
        return view($this->module_view_folder.'.login',$this->arr_view_data);
    }

    public function validate_login(Request $request)
    {
      $arr_rules      = array();
      $status         = false;

      $arr_rules['email']    = "required|email";
      $arr_rules['password'] = "required";

      $validator = validator::make($request->all(),$arr_rules);
      if ($validator->fails()) 
      {
        return back()->withErrors($validator)->withInput();
      }

      $obj_group_support = $this->SupportTeamModel->where('email',$request->only('email'))->first();
      if($obj_group_support) 
      {
          if($obj_group_support->status == 0) {
              Session::flash('error', 'Sorry! You are blocked by admin');
              return redirect()->back();
          }

          if($this->auth->attempt($request->only('email', 'password'))) {
              Session::put('user_id', $obj_group_support->id);
              Session::put('supportLogged',1);

              return redirect($this->module_url_path.'/dashboard');
          }
          else {
              Session::flash('error', 'Invalid login credential.');
          }
      }
      else {
          Session::flash('error','Invalid login credentials.');
      }
    
    return redirect()->back();
}

public function logout()
{
    //Session::flush();
    $this->auth->logout();
    return redirect($this->module_url_path.'/login');
}

}
