<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\AmenitiesModel;
use App\Models\PropertytypeModel;
use App\Common\Traits\MultiActionTrait;
use Validator;
use Session;
use Image;

class AmenitiesController extends Controller
{
    use MultiActionTrait;
	
	function __construct(AmenitiesModel 	$amenities_model,
					     PropertytypeModel  $property_type_model)
	{
		$this->arr_data                 = [];
		$this->admin_panel_slug         = config('app.project.admin_panel_slug');
		$this->admin_url_path           = url(config('app.project.admin_panel_slug'));
		$this->module_url_path          = $this->admin_url_path."/amenities";
		$this->module_title             = "Aminity";
		$this->module_view_folder       = "admin.amenities";
		$this->module_icon              = "fa fa-copy";
		$this->AmenitiesModel           = $amenities_model;
		$this->BaseModel                = $amenities_model;
		$this->PropertytypeModel        = $property_type_model;
	}

	public function index()
	{
		$arr_amenities = [];
		$obj_amenities = $this->BaseModel->with('propertytype', 'property.property_aminities')
										 ->orderBy('created_at', 'desc')->get();
		
		if(isset($obj_amenities) && $obj_amenities!=null)
		{
			$arr_amenities = $obj_amenities->toArray();
		}

		$this->arr_data['objects']          = $arr_amenities;
		$this->arr_data['page_title']       = "Manage ".str_plural($this->module_title);
		$this->arr_data['module_icon']      = $this->module_icon;
		$this->arr_data['module_title']     = $this->module_title;
		$this->arr_data['module_url_path']  = $this->module_url_path;
		$this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
		
		return view($this->module_view_folder.'.index',$this->arr_data);
	}

	public function create()
	{
		$arr_propertytype = [];
		$obj_propertytype = $this->PropertytypeModel->get();
		if(isset($obj_propertytype) && $obj_propertytype!=null)
		{
			$arr_propertytype = $obj_propertytype->toArray();
		}

		$this->arr_data['parent_propertytype'] 	= $arr_propertytype;
		$this->arr_data['page_title']        	= "Add ".$this->module_title;
		$this->arr_data['module_icon']       	= $this->module_icon;
		$this->arr_data['page_icon']         	= 'fa-plus-square-o';
		$this->arr_data['module_title']      	= $this->module_title;
		$this->arr_data['module_url_path']   	= $this->module_url_path;
		$this->arr_data['admin_panel_slug']  	= $this->admin_panel_slug;
		
		return view($this->module_view_folder.'.create',$this->arr_data);
	}

	public function store(Request $request)
	{
		$arr_rules      = array();
		$status         = false;
		$filename = '';

		$arr_rules['amenity_name']        = "required";
		$arr_rules['propertytype_id']	  = "required";

		$validator = validator::make($request->all(),$arr_rules);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$propertytype_id = $request->input('propertytype_id', null);
		$amenities_name  = $request->input('amenity_name', null);

		foreach ($amenities_name as $key => $value) {
			if ($value!="") {
				$name = strtolower(trim($value));
				$slug = str_slug($name);

				$is_exist = $this->AmenitiesModel->where('propertytype_id',$propertytype_id)->where('aminity_name',$name)->count();

				if ($is_exist>0) {
					$validator->getMessageBag()->add('amenity_name', $this->module_title.' name already exist');
					return redirect()->back()->withErrors($validator);
				}
				
				$status = $this->AmenitiesModel->create(['aminity_name'=>$name, 'slug'=>$slug,'propertytype_id'=>$propertytype_id]);
			} else {
				$validator->getMessageBag()->add('amenity_name',' Please add all amenities');
				return redirect()->back()->withErrors($validator);
			}
		}
		
		if ($status) {
			Session::flash('success', $this->module_title.' added successfully.');
			return redirect($this->module_url_path);
		}

		Session::flash('error', 'Error while adding '.$this->module_title);
		return redirect()->back();
	}

	public function edit($id=null)
	{
		$arr_aminity = $arr_propertytype = [];

		if($id != null) {
			$id          = base64_decode($id);	
			$obj_aminity = $this->AmenitiesModel->where('id',$id)->first();
			$arr_aminity = $obj_aminity->toArray();
		}

		$obj_propertytype = $this->PropertytypeModel->get();

		if (isset($obj_propertytype) && $obj_propertytype!=null) {
			$arr_propertytype = $obj_propertytype->toArray();
		}

		$this->arr_data['parent_propertytype']  = $arr_propertytype;
		$this->arr_data['id']                	= base64_encode($id);
		$this->arr_data['object']            	= $arr_aminity;
		$this->arr_data['page_title']        	= "Edit ".$this->module_title;
		$this->arr_data['module_icon']       	= $this->module_icon;
		$this->arr_data['page_icon']         	= 'fa-pencil-square-o';
		$this->arr_data['module_title']      	= $this->module_title;
		$this->arr_data['module_url_path']   	= $this->module_url_path;
		$this->arr_data['admin_panel_slug']  	= $this->admin_panel_slug;

		return view($this->module_view_folder.'.edit',$this->arr_data);
	}

	public function update(Request $request, $id=null)
	{

		$arr_aminity              		= [];
		$arr_rules                		= array();
		$status                   		= false;
		$arr_rules['name']        		= "required";
		$arr_rules['propertytype_id'] 	= "required";

		$validator = validator::make($request->all(),$arr_rules);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$name        		= $request->input('name', null);
		$propertytype_id 	= $request->input('propertytype_id', null);

		if ($id!=null) {
			$id          = base64_decode($id);	
			$obj_aminity = $this->AmenitiesModel->where('id',$id)->first();
			$arr_aminity = $obj_aminity->toArray();
		}

		if($name != null) {
			$name  = strtolower(trim($name));
			
			if($arr_aminity['aminity_name']!=$name || $arr_aminity['propertytype_id']!=$propertytype_id) {
				$is_exist = $this->AmenitiesModel->where('propertytype_id',$propertytype_id)->where('aminity_name',$name)->count();
				
				if($is_exist > 0) {
					$validator->getMessageBag()->add('name', $this->module_title.' name already exist');
					return redirect()->back()->withErrors($validator);
				}
				$status = $this->AmenitiesModel->where('id', $id)->update(['aminity_name'=>$name,'propertytype_id'=>$propertytype_id]);
		
				if ($status) {
					Session::flash('success', $this->module_title.' updated successfully.');
					return redirect($this->module_url_path);
				}
				
				Session::flash('error', 'Error while updating '.$this->module_title);
				return redirect()->back();
			}

			Session::flash('success', $this->module_title.' updated successfully.');
			return redirect($this->module_url_path);
		}
		Session::flash('error', 'Error while updating '.$this->module_title);
		return redirect()->back();
	}
}
