<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\NotificationTemplateModel;
use App\Common\Traits\MultiActionTrait;

use Validator;
use Session;
use Auth;
use Hash;

class NotificationTemplateController extends Controller
{
    use MultiActionTrait;
	
	public function __construct(NotificationTemplateModel $notification_template_model)
	{
        $this->arr_data                  = [];
        $this->admin_panel_slug          = config('app.project.admin_panel_slug');
        $this->admin_url_path            = url(config('app.project.admin_panel_slug'));
        $this->module_url_path           = $this->admin_url_path."/notifications";
        $this->module_title              = "Notifications Template";
        $this->module_view_folder        = "admin.notification_template";
        $this->module_icon               = "fa-bell";
        $this->NotificationTemplateModel = $notification_template_model;
        $this->BaseModel                 = $notification_template_model;
	}

	public function index()
	{
		$arr_notifications = [];
		
		$obj_notifications = $this->BaseModel->orderBy('id', 'Desc')->get();		

		if($obj_notifications)
		{
			$arr_notifications = $obj_notifications->toArray();
		}	

		$this->arr_data['objects']          = $arr_notifications;
		$this->arr_data['page_title']       = "Manage ".$this->module_title;
		$this->arr_data['module_icon']      = $this->module_icon;
		$this->arr_data['module_title']     = $this->module_title;
		$this->arr_data['module_url_path']  = $this->module_url_path;
		$this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;

		return view($this->module_view_folder.'.index',$this->arr_data);
	}

	public function create()
	{	
        $this->arr_view_data['page_title']       = "Add ".$this->module_title;
        $this->arr_view_data['module_title']     = $this->module_title;
        $this->arr_view_data['module_url_path']  = $this->module_url_path;
        $this->arr_view_data['module_icon']      = $this->module_icon;
        $this->arr_view_data['page_icon']        = $this->module_icon;
        $this->arr_view_data['admin_panel_slug'] = $this->admin_panel_slug;
        return view($this->module_view_folder.'.create',$this->arr_view_data);
	}

	public function store(Request $request)
    {
        $arr_rules['name']           	= "required";
        $arr_rules['subject']           = "required";
        $arr_rules['descriptions']      = "required";        
        $msg                            = array(
										            'required' =>'Please enter :attribute',
										       );

        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $form_data = $request->all();

        $does_exists = $this->BaseModel
        								->where('template_name',$form_data['name'])
        								->count()>0;   	

        if($does_exists)
        {
            $validator->getMessageBag()->add('name', 'This notifications already exist');
            return redirect()->back()->withErrors($validator);
        }

        $notifications     = strtolower(trim($form_data['name']));
		
        $this->arr_view_data['template_name']      = $notifications;
        $this->arr_view_data['template_subject']   = $form_data['subject'];
        $this->arr_view_data['template_text']      = $form_data['descriptions'];      

        $notifications_name = $this->BaseModel->create($this->arr_view_data);

        if($notifications_name) 
        {
            return redirect()->back()->with('success','Notifications Template added successfully');            
        }
        else
        {
        	return redirect()->back()->with('error','Error while adding notifications template');
        }
    }

    public function edit($id=null)
	{
		$arr_notifications = $arr_variables = [];

        ($id)? $id          = base64_decode($id):NULL;

        $obj_notifications    = $this->BaseModel->where('id', $id)->first();

        if(isset($obj_notifications) && $obj_notifications!="")
        { 
            $arr_notifications    =   $obj_notifications->toArray();

            if(isset($arr_notifications['template_variables']))
            {
                $arr_variables = explode('~', $arr_notifications['template_variables']);
            }
        } 


		$this->arr_data['id']           	= base64_encode($id);
        $this->arr_data['object']           = $arr_notifications;
		$this->arr_data['variables']        = $arr_variables;
		$this->arr_data['page_title']       = "Edit ".$this->module_title;
		$this->arr_data['module_icon']      = $this->module_icon;
		$this->arr_data['page_icon']        = 'fa-bell';
		$this->arr_data['module_title']     = $this->module_title;
		$this->arr_data['module_url_path']  = $this->module_url_path;
		$this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
		
		return view($this->module_view_folder.'.edit',$this->arr_data);
	}

	public function update(Request $request, $id)
    {
        $id                        = base64_decode($id);
        $arr_rules['name']         = "required";
        $arr_rules['subject']      = "required";
        $arr_rules['descriptions'] = "required";

    	$msg                       = array(
									    	'required' =>'Please enter :attribute',
									      );
        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $form_data = $request->all();

        $does_exists = $this->BaseModel
        								->where('template_name',$form_data['name'])      
                                        ->where('id','!=',$id)  								
        								->count()>0; 
        if($does_exists)
        {
            $validator->getMessageBag()->add('name', 'This notifications already exist');
            return redirect()->back()->withErrors($validator);
        }

       
        $notifications     = strtolower(trim($form_data['name']));

		$this->arr_view_data['template_name']      = $notifications;
        $this->arr_view_data['template_subject']   = $form_data['subject'];
        $this->arr_view_data['template_text']      = $form_data['descriptions'];      


        $notification_data = $this->BaseModel->where('id',$id)->update($this->arr_view_data);       

        if($notification_data) 
        {
            return redirect()->back()->with('success','Notifications updated successfully');            
        }
        else
        {
        	return redirect()->back()->with('error','Error while updating Notifications');
        }
    }
}
