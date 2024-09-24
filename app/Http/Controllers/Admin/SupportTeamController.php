<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SupportTeamModel;
use App\Common\Traits\MultiActionTrait;
use App\Common\Services\EmailService;

use Validator;
use Session;
use Auth;
use Hash;

class SupportTeamController extends Controller
{
   use MultiActionTrait;
	
	public function __construct(SupportTeamModel $support_team_model, EmailService $email_service)
	{
        $this->arr_data           = [];
        $this->admin_panel_slug   = config('app.project.admin_panel_slug');
        $this->admin_url_path     = url(config('app.project.admin_panel_slug'));
        $this->module_url_path    = $this->admin_url_path."/support_team";
        $this->module_title       = "Support Team";
        $this->module_view_folder = "admin.support_team";
        $this->module_icon        = "fa-phone-square";
        $this->SupportTeamModel   = $support_team_model;
        $this->BaseModel          = $support_team_model;
        $this->EmailService       = $email_service;
	}

	public function index()
	{
      
		$arr_support_team = [];
		$obj_support_team = $this->BaseModel
										->orderBy('created_at','desc')->get();
		
		if($obj_support_team)
		{
			$arr_support_team = $obj_support_team->toArray();
		}	

		$this->arr_data['objects']          = $arr_support_team;
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

        $arr_data = [];
        $arr_rules['first_name']     = "required";
        $arr_rules['last_name']      = "required";
        $arr_rules['email']          = "required";
        $arr_rules['support_level']  = "required";

        $msg     = array(
				          'required' =>'Please enter :attribute',
				        );

        $validator = Validator::make($request->all(), $arr_rules, $msg);

        if($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $form_data = $request->all();

        $does_exists = $this->BaseModel
        								->where('email',$form_data['email'])
        								->count()>0;   	

        if($does_exists)
        {
            $validator->getMessageBag()->add('email', 'This email id already exist');
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        //$generate_unique_code = $this->generate_unique_code();

        $password            = _generate_password();
        $encrypted_password  = bcrypt($password);
        $user_name           = str_slug($form_data['first_name']).''.str_slug($form_data['last_name']);
        $slug                = str_slug($user_name);


        $this->arr_view_data['first_name']    = $form_data['first_name'];
        $this->arr_view_data['last_name']     = $form_data['last_name'];
        $this->arr_view_data['user_name']     = $user_name;
        $this->arr_view_data['email']         = $form_data['email'];
        $this->arr_view_data['support_level'] = $form_data['support_level'];
        $this->arr_view_data['password'] 	  = $encrypted_password;        
        $this->arr_view_data['status']        = 1;

        $supportteam = $this->BaseModel->create($this->arr_view_data);

        if($supportteam) 
        {
          /*  
            $arr_data['email_to']     = $form_data['email'];
            $arr_data['password']  = $password;
            $arr_data['user_name'] = ucfirst($form_data['first_name']);
            $this->EmailService->send_support_team_details_mail($arr_data);*/

            $url = '<td class="listed-btn"><a target="_blank" href=" '.url(config('app.project.support_panel_slug')).'">Do Login</a></td><br/>';
            $arr_built_content                     = [
                                    'USER_NAME'        => ucfirst($form_data['first_name']),   
                                    'EMAIL'            => $form_data['email'],   
                                    'PASSWORD'         => $password,   
                                    'SITE_URL'         => $url, 
                                    'PROJECT_NAME'     => config('app.project.name')
                                 ];
            $arr_mail_data                         = [];
            $arr_mail_data['email_template_id']    = '2';
            $arr_mail_data['arr_built_content']    = $arr_built_content;
            $arr_mail_data['user']                 = ['email' => $form_data['email'], 'first_name' => ucfirst($form_data['first_name'])];
            $this->EmailService->send_mail($arr_mail_data);

        	return redirect()->back()->with('success','Support Team added successfully');

           	/*$arr_data = $this->arr_view_data;
        	$arr_data_details = array_merge($arr_data,$password);			           	

        	$arr_notification_data = $this->send($arr_data_details); 
        	if($arr_sent_mail)
        	{            
            	return redirect()->back()->with('success','Support Team added successfully');
        	}
        	else
        	{
        		return redirect()->back()->with('error','Error while adding Support Team');
        	}*/

        }
        else
        {
        	return redirect()->back()->with('error','Error while adding Support Team');
        }
    }

   /* public function send($arr_data_details)
    {
    	$email 		= $arr_data_details['email'];
    	$password 	= $arr_data_details['password'];
    	$name 	= $arr_data_details['first_name'];

        $arr_data['subject']     = 'Support Team Add';
        $content                 = $request->input('message');
        $enquiry['email_id']     = $email;
        $enquiry['password']     = $password;
        $content                 = 'Hello'.$name;
        $content                 = 'Email Id: '.$email;
        $content                 = 'Password: '.$password;
        $content                 = view('admin.email.support_team',compact('content'))->render();
        $content                 = html_entity_decode($content);
        $send_mail               = Mail::send(array(),array(), function($message) use($email,$content,$arr_data) {
            $message->from('admin@shareous.com', 'shareous.com');
            $message->to($email, 'user')->subject($arr_data['subject'])->setBody($content, 'text/html');
        });

        if($send_mail)        
        {
            return redirect()->back()->with('success','Reply send successfully');
        }
        else
        {
            return redirect()->back()->with('error','Problem Occured While sending reply');
        }
        return redirect()->back();
    }*/

    public function edit($id)
    {
        $arr_support_team    = [];
        ($id)? $id          = base64_decode($id):NULL;
        $obj_support_team    = $this->BaseModel->where('id', $id)->first();

        if(isset($obj_support_team) && $obj_support_team!="")
        { 
            $arr_support_team    =   $obj_support_team->toArray();
        }        

        $this->arr_view_data['support_team']        = $arr_support_team;
        $this->arr_view_data['id']                   = base64_encode($id);
        $this->arr_view_data['page_title']           = "Edit ".$this->module_title;
        $this->arr_view_data['module_title']         = $this->module_title;
        $this->arr_view_data['module_url_path']      = $this->module_url_path;
        $this->arr_view_data['module_icon']  		 = $this->module_icon;
        $this->arr_view_data['admin_panel_slug'] 	 = $this->admin_panel_slug;
        
        return view($this->module_view_folder.'.edit',$this->arr_view_data);
    }

    public function update(Request $request,$id)
    {
    	$arr_support_team    = [];
        ($id)? $id          = base64_decode($id):NULL;

        $arr_rules['first_name']     = "required";
        $arr_rules['last_name']      = "required";
        $arr_rules['support_level']  = "required";

    	$msg                           = array(
									    	'required' =>'Please enter :attribute',
									        );
        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $form_data = $request->all();   

        $password = _generate_password();
        $encrypted_password = bcrypt($password);     

        $user_name  = str_slug($form_data['first_name']).''.str_slug($form_data['last_name']);

        $this->arr_view_data['first_name']    = $form_data['first_name'];
        $this->arr_view_data['user_name']     = $user_name;
        $this->arr_view_data['last_name']     = $form_data['last_name'];
        $this->arr_view_data['password'] 	  = $encrypted_password;        
        $this->arr_view_data['support_level'] = $form_data['support_level'];

        $supportteam = $this->BaseModel->where('id',$id)->update($this->arr_view_data);       

        if($supportteam) 
        {
            return redirect()->back()->with('success','Support Team updated successfully');            
        }
        else
        {
        	return redirect()->back()->with('error','Error while updating Support Team');
        }
    }
   

}
