<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ContactModel;
use App\Common\Traits\MultiActionTrait;

use Validator;
use Session;
use Auth;
use Hash;
use Mail;


class ContactController extends Controller
{
 	use MultiActionTrait;
	
	public function __construct(ContactModel $contact_model)
	{
		$this->arr_data           = [];
		$this->admin_panel_slug   = config('app.project.admin_panel_slug');
		$this->admin_url_path     = url(config('app.project.admin_panel_slug'));
		$this->module_url_path    = $this->admin_url_path."/contact";
		$this->module_title       = "Contact Enquiries";
		$this->module_view_folder = "admin.contact_enquiries";
		$this->module_icon        = "fa-phone";
		$this->ContactModel 	  = $contact_model;
		$this->BaseModel          = $contact_model;
	}

	public function index()
	{
		$arr_contact_enquiries = [];
		$obj_contact_enquiries = $this->BaseModel->orderBy('created_at','desc')->get();

		if($obj_contact_enquiries)
		{
			$arr_contact_enquiries = $obj_contact_enquiries->toArray();
		}	

		$this->arr_data['objects']          = $arr_contact_enquiries;
		$this->arr_data['page_title']       = "Manage ".$this->module_title;
		$this->arr_data['module_icon']      = $this->module_icon;
		$this->arr_data['module_title']     = $this->module_title;
		$this->arr_data['module_url_path']  = $this->module_url_path;
		$this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;

		return view($this->module_view_folder.'.index',$this->arr_data);
	}

	public function view($id)
    {
    	$arr_contact_enquiry = [];
    	$id = base64_decode($id);

        $view_enquiry = $this->BaseModel->where('id',$id)->update(['is_read_status'=>'1']);
		
		$obj_contact_enquiry 		 = $this->BaseModel->where('id','=',$id)->first();

		if($obj_contact_enquiry)
		{
			$arr_contact_enquiry = $obj_contact_enquiry->toArray();
		}

        $this->arr_view_data['contact_enquiry'] 	= $arr_contact_enquiry;
        $this->arr_view_data['page_title']          = "View ".$this->module_title;   
        $this->arr_view_data['page_icon']           = "fa-eye";
        $this->arr_view_data['module_title']        = $this->module_title;
        $this->arr_view_data['module_icon']         = $this->module_icon;
        $this->arr_view_data['admin_panel_slug'] 	= $this->admin_panel_slug;
        $this->arr_view_data['module_url_path']     = $this->module_url_path;

        return view($this->module_view_folder.'.view',$this->arr_view_data);
    }

    public function reply($id)
    {
        $arr_data = [];
        $id       = base64_decode($id);
        $obj_data = $this->BaseModel->where('id',$id)->first();
        if($obj_data)
        {
            $arr_data = $obj_data->toArray();
        }
        $this->arr_view_data['arr_contact_enquiry'] = $arr_data;
        $this->arr_view_data['id']                  = base64_encode($id);
        $this->arr_view_data['module_icon']         = $this->module_icon;
        $this->arr_view_data['page_title']          = str_singular("Reply ".$this->module_title);
        $this->arr_view_data['page_icon']           = 'fa-reply';
        $this->arr_view_data['module_title']        = $this->module_title;
        $this->arr_view_data['module_url_path']     = $this->module_url_path;
        $this->arr_view_data['admin_panel_slug'] 	= $this->admin_panel_slug;
        return view($this->module_view_folder.'.reply',$this->arr_view_data);
    }

    public function send(Request $request,$id)
    {

    	// /dd($request->all());
        $arr_data = $arr_rules = [];
        $id = base64_decode($id);
        $arr_rules = array(
            'email_id'   => 'required',
            'message' => 'required',
        );
        $msg       = array( 'required' =>'Please enter :attribute', );

        $validator = $validator = Validator::make($request->all(), $arr_rules, $msg);
        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
               
        $arr_data['subject']     = 'Contact Enquiry Response';
        $content                 = $request->input('message');
        $enquiry                 = $this->BaseModel->where(['id'=>$id])->first();
        ($enquiry)? $enquiry     = $enquiry->toArray():NULL;
        $content                 = str_replace("##EMAIL##",$enquiry['email_id'],$content);
        $content                 = view('front.email.general',compact('content'))->render();
        $content                 = html_entity_decode($content);
        $send_mail               = Mail::send(array(),array(), function($message) use($enquiry,$content,$arr_data) {
            $message->from('admin@shareous.com', 'shareous.com');
            $message->to($enquiry['email_id'], 'user')->subject($arr_data['subject'])->setBody($content, 'text/html');
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
    }
}
