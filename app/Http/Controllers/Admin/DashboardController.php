<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\SupportTeamModel;
use App\Models\PropertyModel;

class DashboardController extends Controller
{
	public function __construct(UserModel  			$user_model,
								SupportTeamModel 	$support_team_model,
							    PropertyModel       $property_model)
	{
		$this->arr_view_data      = [];
		$this->module_title       = "Dashboard";
		$this->module_view_folder = "admin.dashboard";
		$this->admin_url_path     = url(config('app.project.admin_panel_slug'));
		$this->UserModel          = $user_model;
		$this->SupportTeamModel   = $support_team_model;
		$this->PropertyModel      = $property_model;
	}

	public function index(Request $request)
	{
		$arr_tile_color        = array('tile-red','tile-green','tile-magenta','');
		$total_user 	       = $this->UserModel/*->where('user_type','=','1')*/->count();		
		$total_host 	       = $this->UserModel->where('user_type','=','4')->count();		
		$total_support_team    = $this->SupportTeamModel->count();
		$total_property_upload = $this->PropertyModel->where('property_status', 1)->count();


		$this->arr_view_data['total_user']            = $total_user;
		$this->arr_view_data['total_host']            = $total_host;
		$this->arr_view_data['total_support_team']    = $total_support_team;
		$this->arr_view_data['total_property_upload'] = $total_property_upload;
				
		$this->arr_view_data['arr_final_tile']   = array();
		$this->arr_view_data['page_title']       = $this->module_title;
		$this->arr_view_data['module_title']     = $this->module_title;
		$this->arr_view_data['admin_url_path']   = $this->admin_url_path;
		$this->arr_view_data['admin_panel_slug'] = $this->admin_url_path;
		$this->arr_view_data['arr_tile_color']   = $arr_tile_color;

		return view($this->module_view_folder.'.index',$this->arr_view_data);
	}
}



