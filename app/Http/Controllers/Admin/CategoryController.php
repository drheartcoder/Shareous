<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\CategoryModel;
use App\Common\Traits\MultiActionTrait;
use Validator;
use Session;

class CategoryController extends Controller
{
	use MultiActionTrait;
	
	public function __construct(CategoryModel $category_model)
	{
		$this->arr_data           = [];
		$this->admin_panel_slug   = config('app.project.admin_panel_slug');
		$this->admin_url_path     = url(config('app.project.admin_panel_slug'));
		$this->module_url_path    = $this->admin_url_path."/categories";
		$this->module_title       = "Category";
		$this->module_view_folder = "admin.categories";
		$this->module_icon        = "fa fa-tags";
		$this->CategoryModel      = $category_model;
		$this->BaseModel          = $category_model;
	}
	
	public function index()
	{
		$arr_categories = [];
		$obj_categories = $this->BaseModel->orderBy('created_at','desc')->get();
		
		if($obj_categories)
		{
			$arr_categories = $obj_categories->toArray();
		}

		$this->arr_data['total']            = count($arr_categories);
		$this->arr_data['blocked']          = $this->BaseModel->where('status', '0')->count();;
		$this->arr_data['objects']          = $arr_categories;
		$this->arr_data['page_title']       = "Manage ".$this->module_title;
		$this->arr_data['module_icon']      = $this->module_icon;
		$this->arr_data['module_title']     = $this->module_title;
		$this->arr_data['module_url_path']  = $this->module_url_path;
		$this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
		
		return view($this->module_view_folder.'.index',$this->arr_data);
	}

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

	public function store(Request $request)
	{

		$arr_rules      = array();
		$status         = false;

		$arr_rules['name']       = "required";

		$validator = validator::make($request->all(),$arr_rules);

		if ($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();
		}
		$name = $request->input('name', null);


		if($name!=null)
		{
			$name     = strtolower(trim($name));
			$slug     = str_slug($name);
			$is_exist = $this->CategoryModel->where('category_name',$name)->count();

			if($is_exist>0)
			{
				$validator->getMessageBag()->add('name', $this->module_title.' name already exist');
				return redirect()->back()->withErrors($validator);
			}

			$status = $this->CategoryModel->create(['category_name'=>$name, 'slug'=>$slug]);

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

	public function edit($id=null)
	{
		$arr_category = [];

		if($id!=null)
		{
			$id           = base64_decode($id);	
			$obj_category = $this->CategoryModel->where('id',$id)->first();
			$arr_category = $obj_category->toArray();

		}

		$this->arr_data['id']           = base64_encode($id);
		$this->arr_data['object']           = $arr_category;
		$this->arr_data['page_title']       = "Edit ".$this->module_title;
		$this->arr_data['module_icon']      = $this->module_icon;
		$this->arr_data['page_icon']        = 'fa-pencil-square-o';
		$this->arr_data['module_title']     = $this->module_title;
		$this->arr_data['module_url_path']  = $this->module_url_path;
		$this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
		
		return view($this->module_view_folder.'.edit',$this->arr_data);
	}

	public function update(Request $request, $id=null)
	{
		$arr_category = [];


		$arr_rules      = array();
		$status         = false;

		$arr_rules['name']       = "required";

		$validator = validator::make($request->all(),$arr_rules);

		if ($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();
		}
		$name = $request->input('name', null);

		if($id!=null)
		{
			$id           = base64_decode($id);	
			$obj_category = $this->CategoryModel->where('id',$id)->first();
			$arr_category = $obj_category->toArray();

		}
		if($name!=null)
		{
			$name     = strtolower(trim($name));
			if($arr_category['category_name']!=$name)
			{
				$is_exist = $this->CategoryModel->where('category_name',$name)->count();
				if($is_exist>0)
				{
					$validator->getMessageBag()->add('name', $this->module_title.' name already exist');
					return redirect()->back()->withErrors($validator);
				}
				$status = $this->CategoryModel->where('id', $id)->update(['category_name'=>$name]);
				if($status)
				{
					Session::flash('success', $this->module_title.' updated successfully.');
					return redirect($this->module_url_path);
				}
				
				Session::flash('error', 'Error while updating '.$this->module_title);
				return redirect()->back();

			}

					Session::flash('success', $this->module_title.' updated successfully.');
			return redirect()->back();
		}
		Session::flash('error', 'Error while updating '.$this->module_title);
		return redirect()->back();
	}

}

