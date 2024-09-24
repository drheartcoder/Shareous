<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\FrontPagesModel;
use App\Common\Traits\MultiActionTrait;
use Validator;
use Session;

class FrontPagesController extends Controller
{
	use MultiActionTrait;
	
	public function __construct(FrontPagesModel $front_pages_model)
	{
		$this->arr_data           = [];
		$this->admin_panel_slug   = config('app.project.admin_panel_slug');
		$this->admin_url_path     = url(config('app.project.admin_panel_slug'));
		$this->module_url_path    = $this->admin_url_path."/front_pages";
		$this->module_title       = "Front Pages";
		$this->module_view_folder = "admin.front_pages";
		$this->module_icon        = "fa fa-file";
		$this->FrontPagesModel    = $front_pages_model;
		$this->BaseModel          = $front_pages_model;
	}
	
	/*
    | Function  : List all the data
    | Author    : Deepak Arvind Salunke
    | Date      : 22/02/2018
    | Output    : Success or Error
    */

	public function index()
	{
		$arr_front_pages = [];
		$obj_front_pages = $this->BaseModel->orderBy('id', 'desc')->get();
		
		if ($obj_front_pages) {
			$arr_front_pages = $obj_front_pages->toArray();
		}
		
		$this->arr_data['total']            = count($arr_front_pages);
		$this->arr_data['blocked']          = $this->BaseModel->where('status', '0')->count();;
		$this->arr_data['objects']          = $arr_front_pages;
		$this->arr_data['page_title']       = "Manage ".$this->module_title;
		$this->arr_data['module_icon']      = $this->module_icon;
		$this->arr_data['module_title']     = $this->module_title;
		$this->arr_data['module_url_path']  = $this->module_url_path;
		$this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
		
		return view($this->module_view_folder.'.index',$this->arr_data);
	}
	/*
    | Function  : Show create form
    | Author    : Deepak Arvind Salunke
    | Date      : 22/02/2018
    | Output    : Success or Error
    */

	public function create()
	{
		$this->arr_data['page_title']       = "Add ".$this->module_title;
		$this->arr_data['module_icon']      = $this->module_icon;
		$this->arr_data['page_icon']      = 'fa-plus-square-o';
		$this->arr_data['module_title']     = $this->module_title;
		$this->arr_data['module_url_path']  = $this->module_url_path;
		$this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
		
		return view($this->module_view_folder.'.create',$this->arr_data);
	}

	/*
    | Function  : Store data
    | Author    : Deepak Arvind Salunke
    | Date      : 22/02/2018
    | Output    : Success or Error
    */

	public function store(Request $request)
	{

		$arr_rules      = array();
		$status         = false;

		$arr_rules['title']      	   = "required";
		$arr_rules['slug']       	   = "required";
		$arr_rules['meta_title']       = "required";
		$arr_rules['meta_keyword']     = "required";
		$arr_rules['meta_description'] = "required";
		$arr_rules['description']      = "required";

		$validator = validator::make($request->all(),$arr_rules);

		if ($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$title 				= $request->input('title', null);
		$slug 				= $request->input('slug', null);
		$meta_title 		= $request->input('meta_title', null);
		$meta_keyword 		= $request->input('meta_keyword', null);
		$meta_description 	= $request->input('meta_description', null);
		$description 		= $request->input('description', null);

		if($title!=null)
		{
			$title    = strtolower(trim($title));
			$slug     = str_slug($title);
			$is_exist = $this->FrontPagesModel->where('page_title',$title)->count();

			if($is_exist>0)
			{
				$validator->getMessageBag()->add('page_title', $this->module_title.' title already exist');
				return redirect()->back()->withErrors($validator);
			}

			$status = $this->FrontPagesModel->create(['page_title'=>$title, 'page_slug'=>$slug, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'meta_description' => $meta_description, 'page_description' => $description ]);

			if($status)
			{
				Session::flash('success', $this->module_title.' added successfully.');
				return redirect($this->module_url_path);
			}
			Session::flash('error', 'Error while adding '.$this->module_title);
			return redirect()->back();
		}
		Session::flash('error', 'Error while adding '.$this->module_title);
		return redirect()->back();
	}

	/*
    | Function  : Show seleted data
    | Author    : Deepak Arvind Salunke
    | Date      : 22/02/2018
    | Output    : Success or Error
    */

	public function edit($id=null)
	{
		$arr_front_pages = [];

		if($id!=null)
		{
			$id           = base64_decode($id);	
			$obj_front_pages = $this->FrontPagesModel->where('id',$id)->first();
			$arr_front_pages = $obj_front_pages->toArray();

		}

		$this->arr_data['id']           	= base64_encode($id);
		$this->arr_data['object']           = $arr_front_pages;
		$this->arr_data['page_title']       = "Edit ".$this->module_title;
		$this->arr_data['module_icon']      = $this->module_icon;
		$this->arr_data['page_icon']        = 'fa-pencil-square-o';
		$this->arr_data['module_title']     = $this->module_title;
		$this->arr_data['module_url_path']  = $this->module_url_path;
		$this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
		
		return view($this->module_view_folder.'.edit',$this->arr_data);
	}

	/*
    | Function  : Update selected data
    | Author    : Deepak Arvind Salunke
    | Date      : 22/02/2018
    | Output    : Success or Error
    */

	public function update(Request $request, $id=null)
	{
		$arr_front_pages = [];

		$arr_rules      = array();
		$status         = false;

		$arr_rules['title']      	   = "required";
		$arr_rules['meta_title']       = "required";
		$arr_rules['meta_keyword']     = "required";
		$arr_rules['meta_description'] = "required";
		$arr_rules['description']      = "required";

		$validator = validator::make($request->all(),$arr_rules);

		if ($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$title 				= $request->input('title', null);
		$meta_title 		= $request->input('meta_title', null);
		$meta_keyword 		= $request->input('meta_keyword', null);
		$meta_description 	= $request->input('meta_description', null);
		$description 		= $request->input('description', null);

		if($id!=null)
		{
			$id           = base64_decode($id);	
			$obj_category = $this->FrontPagesModel->where('id',$id)->first();
			$arr_category = $obj_category->toArray();
		}

		if($title != null && $meta_title != null && $meta_keyword != null  && $meta_description != null && $description != null )
		{	
			$status = $this->FrontPagesModel->where('id', $id)->update(['page_title'=>$title, 'meta_title' => $meta_title, 'meta_keyword' => $meta_keyword, 'meta_description' => $meta_description, 'page_description' => $description ]);
			
			if($status)
			{
				Session::flash('success', $this->module_title.' no updated successfully.');
				return redirect($this->module_url_path);
			}
			
			Session::flash('error', 'Error while updating '.$this->module_title);
			return redirect()->back();

		}
		Session::flash('error', 'Error while updating '.$this->module_title);
		return redirect()->back();
	}

}

