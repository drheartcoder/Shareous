<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\PropertytypeModel;
use App\Common\Traits\MultiActionTrait;

use Validator;
use Session;
use Auth;
use Hash;

class PropertytypeController extends Controller
{
    use MultiActionTrait;
    public function __construct(PropertytypeModel $propertytype_model)
    {
        $this->arr_data           = [];
        $this->admin_panel_slug   = config('app.project.admin_panel_slug');
        $this->admin_url_path     = url(config('app.project.admin_panel_slug'));
        $this->module_url_path    = $this->admin_url_path."/propertytype";
        $this->module_title       = "Property Type";
        $this->module_view_folder = "admin.property_type";
        $this->module_icon        = "fa fa-home";
        $this->PropertytypeModel  = $propertytype_model;
        $this->BaseModel          = $propertytype_model;
    }
    public function index()
    {
        $arr_property_type = [];
        $obj_property_type = $this->BaseModel->orderBy('created_at', 'Desc')->get();
        
        if ($obj_property_type) {
            $arr_property_type = $obj_property_type->toArray();
        } 

        $this->arr_data['total']            = count($arr_property_type);
        $this->arr_data['blocked']          = $this->BaseModel->where('status', '0')->count();;
        $this->arr_data['objects']          = $arr_property_type;
        $this->arr_data['page_title']       = "Manage ".$this->module_title;
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_title']     = $this->module_title;
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;

        return view($this->module_view_folder.'.index',$this->arr_data);
    }
    public function create()
    {
        $arr_category        = [];
        $arr_parent_category = [];

        $this->arr_view_data['page_title']      = "Add ".$this->module_title;
        $this->arr_view_data['module_title']    = $this->module_title;
        $this->arr_view_data['module_url_path'] = $this->module_url_path;
        $this->arr_view_data['module_icon']      = $this->module_icon;
        $this->arr_view_data['admin_panel_slug'] = $this->admin_panel_slug;
        return view($this->module_view_folder.'.create',$this->arr_view_data);
    }
    public function store(Request $request)
    {
        $arr_rules['name'] = "required";
        $msg               = array('required' =>'Please enter :attribute');
        
        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $form_data = $request->all();

        $does_exists = $this->PropertytypeModel->where('name',$form_data['name'])->count()>0;

        if ($does_exists) {
            $validator->getMessageBag()->add('name', 'This property type already exist');
            return redirect()->back()->withErrors($validator);
        }

        $this->arr_view_data['name'] = $form_data['name'];
        $this->arr_view_data['status']           = 1;

        $propertytype = $this->PropertytypeModel->create($this->arr_view_data);

        if ($propertytype) {
            return redirect()->back()->with('success','Property Type added successfully');            
        } else {
            return redirect()->back()->with('error','Error while adding property type');
        }
    }

    public function edit($id)
    {
        $arr_propertytype    = [];
        ($id)? $id          = base64_decode($id):NULL;
        $obj_propertytype    = $this->PropertytypeModel->where('id', $id)->first();
        
        if (isset($obj_propertytype) && $obj_propertytype!="") {
            $arr_propertytype    =   $obj_propertytype->toArray();
        }        

        $this->arr_view_data['propertytype']         = $arr_propertytype;
        $this->arr_view_data['id']                   = base64_encode($id);
        $this->arr_view_data['page_title']           = "Edit ".$this->module_title;
        $this->arr_view_data['module_title']         = $this->module_title;
        $this->arr_view_data['module_url_path']      = $this->module_url_path;
        $this->arr_view_data['module_icon']          = $this->module_icon;
        $this->arr_view_data['admin_panel_slug']     = $this->admin_panel_slug;
        
        return view($this->module_view_folder.'.edit',$this->arr_view_data);
    }

    public function update(Request $request, $id)
    {
        $id      = base64_decode($id);
        $arr_rules['name'] = "required";
        $msg                           = array( 'required' =>'Please enter :attribute');
        $validator = Validator::make($request->all(), $arr_rules, $msg);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $form_data = $request->all();

        $does_exists = $this->PropertytypeModel->where('name',$form_data['name'])->where('id','!=',$id)->count()>0; 
        if ($does_exists) {
            $validator->getMessageBag()->add('name', 'Property Type already exist');
            return redirect()->back()->withErrors($validator);
        }

        $this->arr_view_data['name'] = $form_data['name'];        
       
        $propertytype = $this->PropertytypeModel->where('id',$id)->update($this->arr_view_data);       

        if ($propertytype) {
            return redirect()->back()->with('success','Property Type updated successfully');            
        } else {
            return redirect()->back()->with('error','Error while updating property type');
        }
    }
}
