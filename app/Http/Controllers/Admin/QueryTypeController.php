<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\QueryTypeModel;
use App\Common\Traits\MultiActionTrait;

use Validator;
use Session;
use Auth;
use Hash;

class QueryTypeController extends Controller
{
 	use MultiActionTrait;
	
	public function __construct(QueryTypeModel $querytype_model)
	{
		$this->arr_data           = [];
		$this->admin_panel_slug   = config('app.project.admin_panel_slug');
		$this->admin_url_path     = url(config('app.project.admin_panel_slug'));
		$this->module_url_path    = $this->admin_url_path."/query_type";
		$this->module_title       = "Query Type";
		$this->module_view_folder = "admin.query_type";
		$this->module_icon        = "fa-question";
		$this->QueryTypeModel 	  = $querytype_model;
		$this->BaseModel          = $querytype_model;
	}

	public function index()
	{
		$arr_query_type = [];
		$obj_query_type = $this->BaseModel
										->orderBy('created_at','desc')->get();		
		if($obj_query_type)
		{
			$arr_query_type = $obj_query_type->toArray();
		}	

		$this->arr_data['objects']          = $arr_query_type;
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
        $arr_rules['query_type']           = "required";
        $msg                                = array(
            'required' =>'Please enter :attribute',
        );
        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $form_data = $request->all();

        $does_exists = $this->BaseModel
        								->where('query_type',$form_data['query_type'])
        								->count()>0;   	

        if($does_exists)
        {
            $validator->getMessageBag()->add('query_type', 'This Query already exist');
            return redirect()->back()->withErrors($validator);
        }

        $this->arr_view_data['query_type'] = $form_data['query_type'];
        $this->arr_view_data['status']           = 1;

        $query_type_name = $this->BaseModel->create($this->arr_view_data);

        if($query_type_name) 
        {
            return redirect()->back()->with('success','Query added successfully');            
        }
        else
        {
        	return redirect()->back()->with('error','Error while adding Query');
        }
    }

     public function edit($id)
    {
        $arr_query_type    = [];
        ($id)? $id          = base64_decode($id):NULL;
        $obj_query_type    = $this->BaseModel->where('id', $id)->first();
        if(isset($obj_query_type) && $obj_query_type!="")
        { 
            $arr_query_type    =   $obj_query_type->toArray();
        }        

        $this->arr_view_data['querytype']        	 = $arr_query_type;
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
    	$arr_rules['query_type'] = "required";
    	$msg                           = array(
									    	'required' =>'Please enter :attribute',
									        );
        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $form_data = $request->all();

        $does_exists = $this->BaseModel
        								->where('query_type',$form_data['query_type'])
        								->where('id','!=',$id)
        								->count()>0; 
        if($does_exists)
        {
            $validator->getMessageBag()->add('query_type', 'This Query already exist');
            return redirect()->back()->withErrors($validator);
        }

        $this->arr_view_data['query_type'] = $form_data['query_type'];        

        $servicename = $this->BaseModel->where('id',$id)->update($this->arr_view_data);       

        if($servicename) 
        {
            return redirect()->back()->with('success','Query updated successfully');            
        }
        else
        {
        	return redirect()->back()->with('error','Error while updating Query');
        }
    }
}
