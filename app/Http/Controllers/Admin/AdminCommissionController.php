<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SiteSettingsModel;

use Validator;
use Session;
use Auth;
use Hash;


class AdminCommissionController extends Controller
{
    public function __construct(SiteSettingsModel $admincommissionmodel)
	{
		$this->arr_data             = [];
		$this->admin_panel_slug     = config('app.project.admin_panel_slug');
		$this->admin_url_path       = url(config('app.project.admin_panel_slug'));
		$this->module_url_path      = $this->admin_url_path."/admin_commission";
		$this->module_title         = "Admin Commission";
		$this->module_view_folder   = "admin.admin_commission";
		$this->module_icon          = "fa-percent";
		$this->SiteSettingsModel 	= $admincommissionmodel;
		$this->BaseModel            = $admincommissionmodel;
	}

	public function index()
	{
		$arr_admin_commission                          = [];
	
		$obj_admin_commission = $this->BaseModel->first();
		
		if($obj_admin_commission)
		{
			$arr_admin_commission = $obj_admin_commission->toArray();
		}	

		$this->arr_view_data['arr_admin_commission']   = $arr_admin_commission;
		$this->arr_view_data['module_icon']            = $this->module_icon;
		$this->arr_view_data['page_title']             = "Manage ".$this->module_title;
		$this->arr_view_data['module_url_path']        = $this->module_url_path;
		$this->arr_view_data['admin_panel_slug']       = $this->admin_panel_slug;
		$this->arr_view_data['module_title']  	       = $this->module_title;
		
		return view($this->module_view_folder.'.index',$this->arr_view_data);
	}

	public function update(Request $request)
	{
		$file_name                    	= '';
		$arr_rules                    	= array();
		$arr_data                     	= array();
		$arr_rules['admin_commission']  = "required";
		//$arr_rules['gst']     			= "required";

		$validator = Validator::make($request->all(),$arr_rules);

		if($validator->fails()) {       
			return redirect()->back()->withErrors($validator)->withInput();  
		}

		$arr_data['admin_commission'] = $request->input('admin_commission');
		//$arr_data['gst']              = $request->input('gst');

		$obj_data = $this->BaseModel->first();
		
		if($obj_data) {
			$status_update = $obj_data->where('id',1)->update($arr_data);

			if ($status_update) {
				Session::flash('success',str_singular($this->module_title).' Updated Successfully');
			}
			else {
				Session::flash('error','Problem Occurred, While Updating '.str_singular($this->module_title));
			}
		}
		else {
			Session::flash('error','Problem Occurred, While Updating '.str_singular($this->module_title));
		}
		return redirect()->back();
	}
}
