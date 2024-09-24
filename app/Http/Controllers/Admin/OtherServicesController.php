<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\OtherServicesModel;
use App\Common\Traits\MultiActionTrait;

use Validator;
use Session;
use Auth;
use Hash;


class OtherServicesController extends Controller
{
 	use MultiActionTrait;
	
	public function __construct(OtherServicesModel $otherservices_model)
	{
		$this->arr_data           = [];
		$this->admin_panel_slug   = config('app.project.admin_panel_slug');
		$this->admin_url_path     = url(config('app.project.admin_panel_slug'));
		$this->module_url_path    = $this->admin_url_path."/other_services";
		$this->module_title       = "Other Services";
		$this->module_view_folder = "admin.other_services";
		$this->module_icon        = "fa-bars";
		$this->OtherServicesModel = $otherservices_model;
		$this->BaseModel          = $otherservices_model;
	}

	public function index()
	{
		$arr_other_services = [];
		$obj_other_services = $this->BaseModel
										->orderBy('created_at','desc')->get();
		
		if($obj_other_services)
		{
			$arr_other_services = $obj_other_services->toArray();
		}	

		$this->arr_data['objects']          = $arr_other_services;
		$this->arr_data['page_title']       = "Manage ".$this->module_title;
		$this->arr_data['module_icon']      = $this->module_icon;
		$this->arr_data['module_title']     = $this->module_title;
		$this->arr_data['module_url_path']  = $this->module_url_path;
		$this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;

		return view($this->module_view_folder.'.index',$this->arr_data);
	}

	public function create()
	{	
        $this->arr_view_data['page_title']      = "Add ".$this->module_title;
        $this->arr_view_data['module_title']    = $this->module_title;
        $this->arr_view_data['module_url_path'] = $this->module_url_path;
        $this->arr_view_data['module_icon']      = $this->module_icon;
        $this->arr_view_data['admin_panel_slug'] = $this->admin_panel_slug;
        return view($this->module_view_folder.'.create',$this->arr_view_data);
	}

	public function store(Request $request)
    {
        $arr_rules['name']           = "required";
        $msg                                = array(
            'required' =>'Please enter :attribute',
        );
        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $form_data = $request->all();

        $does_exists = $this->OtherServicesModel
        								->where('name',$form_data['name'])
        								->count()>0;   	

        if($does_exists)
        {
            $validator->getMessageBag()->add('name', 'This Service already exist');
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $this->arr_view_data['name'] = $form_data['name'];
        $this->arr_view_data['status']           = 1;

        $servicename = $this->OtherServicesModel->create($this->arr_view_data);

        if($servicename) 
        {
            return redirect()->back()->with('success','Services added successfully');            
        }
        else
        {
        	return redirect()->back()->with('error','Error while adding Services');
        }
    }

    public function edit($id)
    {
        $arr_other_services    = [];
        ($id)? $id          = base64_decode($id):NULL;
        $obj_other_services    = $this->OtherServicesModel->where('id', $id)->first();

        if(isset($obj_other_services) && $obj_other_services!="")
        { 
            $arr_other_services    =   $obj_other_services->toArray();
        }        

        $this->arr_view_data['otherservices']        = $arr_other_services;
        $this->arr_view_data['id']                   = base64_encode($id);
        $this->arr_view_data['page_title']           = "Edit ".$this->module_title;
        $this->arr_view_data['module_title']         = $this->module_title;
        $this->arr_view_data['module_url_path']      = $this->module_url_path;
        $this->arr_view_data['module_icon']  		 = $this->module_icon;
        $this->arr_view_data['admin_panel_slug'] 	 = $this->admin_panel_slug;
        
        return view($this->module_view_folder.'.edit',$this->arr_view_data);
    }

    public function update(Request $request, $id)
    {
    	$id      = base64_decode($id);
    	$arr_rules['name'] = "required";
    	$msg                           = array(
									    	'required' =>'Please enter :attribute',
									        );
        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $form_data = $request->all();

        $does_exists = $this->OtherServicesModel
        								->where('name',$form_data['name'])
        								->where('id','!=',$id)
        								->count()>0; 
        if($does_exists)
        {
            $validator->getMessageBag()->add('name', 'This service already exist');
            return redirect()->back()->withErrors($validator);
        }

        $this->arr_view_data['name'] = $form_data['name'];        

        $servicename = $this->OtherServicesModel->where('id',$id)->update($this->arr_view_data);       

        if($servicename) 
        {
            return redirect()->back()->with('success','Service updated successfully');            
        }
        else
        {
        	return redirect()->back()->with('error','Error while updating Service');
        }
    }


}
