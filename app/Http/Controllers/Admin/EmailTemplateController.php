<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Session;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\EmailTemplateModel;

class EmailTemplateController extends Controller
{
	public function __construct(EmailTemplateModel $email_template_model)
	{
		$this->arr_data           = [];
		$this->admin_panel_slug   = config('app.project.admin_panel_slug');
		$this->admin_url_path     = url(config('app.project.admin_panel_slug'));
		$this->module_url_path    = $this->admin_url_path."/email_template";
		$this->module_title       = "Email Template";
		$this->module_view_folder = "admin.email_template";
		$this->module_icon        = "fa fa-envelope-square";
		$this->BaseModel          = $email_template_model;
		$this->EmailTemplateModel = $email_template_model;
	}

	public function index()
	{
		$arr_email_teplate = [];
		$obj_email_teplate = $this->BaseModel->orderBy('id', 'desc')->get();
		
		if($obj_email_teplate)
		{
			$arr_email_teplate = $obj_email_teplate->toArray();
		}

		$this->arr_data['objects']          = $arr_email_teplate;
		$this->arr_data['page_title']       = "Manage ".$this->module_title;
		$this->arr_data['module_icon']      = $this->module_icon;
		$this->arr_data['module_title']     = $this->module_title;
		$this->arr_data['module_url_path']  = $this->module_url_path;
		$this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
		
		return view($this->module_view_folder.'.index',$this->arr_data);
	}

	public function view($id=null)
	{
		$arr_email_template = [];

		if($id!=null)
		{
			$id                 = base64_decode($id);	
			$obj_email_template = $this->BaseModel->where('id',$id)->first();
			$arr_email_template = $obj_email_template->toArray();
		}

		$this->arr_data['object']           = $arr_email_template;
		$this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
		
		return view($this->module_view_folder.'.view',$this->arr_data);
	}

	public function edit($id=null)
	{
		$arr_email_template = [];
		$arr_variales = '';

		if($id!=null)
		{
			$id           = base64_decode($id);	
			$obj_email_template = $this->BaseModel->where('id',$id)->first();
			$arr_email_template = $obj_email_template->toArray();
			
			if(isset($arr_email_template['template_variables']))
			{
				$arr_variales = explode('~', $arr_email_template['template_variables']);
			}

		}

		$this->arr_data['id']               = base64_encode($id);
		$this->arr_data['variables']        = $arr_variales;
		$this->arr_data['object']           = $arr_email_template;
		$this->arr_data['page_title']       = "Edit ".$this->module_title;
		$this->arr_data['module_icon']      = $this->module_icon;
		$this->arr_data['page_icon']        = 'fa-pencil-square-o';
		$this->arr_data['module_title']     = $this->module_title;
		$this->arr_data['module_url_path']  = $this->module_url_path;
		$this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
		
		return view($this->module_view_folder.'.edit',$this->arr_data);
		// return view('admin.email.sample');
	}

	public function update(Request $request, $id=null)
	{
		$arr_data  = [];
		$arr_rules = array();
		$status    = false;
		$filename  = '';

		$arr_rules['name']       = "required";
		$arr_rules['from']       = "required";
		$arr_rules['from_email'] = "required";
		$arr_rules['subject']    = "required";
		$arr_rules['body']       = "required";

		$validator = validator::make($request->all(),$arr_rules);

		if ($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();
		}

		if($id!=null)
		{
			$id                             = base64_decode($id);
			$arr_data['template_name']      = $request->input('name');	
			$arr_data['template_from']      = $request->input('from');	
			$arr_data['template_from_mail'] = $request->input('from_email');	
			$arr_data['template_subject']   = $request->input('subject');	
			$arr_data['template_html']      = $request->input('body');

			$status = $this->BaseModel->where('id', $id)->update($arr_data);
			
			if($status)
			{
				Session::flash('success', $this->module_title.' updated successfully.');
				return redirect($this->module_url_path);
			}
			Session::flash('error', 'Error while updating '.$this->module_title);
			return redirect()->back();
		}
		Session::flash('error', 'Error while updating '.$this->module_title);
		return redirect()->back();
	}
}
