<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ContactModel;
use App\Models\SiteSettingsModel;
use Validator;
use Session;

class ContactUsController extends Controller
{
    function __construct(ContactModel $contact_model,
    					 SiteSettingsModel $site_settings_model)
	{
		$this->arr_view_data      = [];
		$this->ContactModel       = $contact_model;
		$this->SiteSettingsModel  = $site_settings_model;		
		$this->module_view_folder = "front.home";
        $this->module_title       = 'Contact Us';

	}
	public function index()
	{
		$arr_site_setting = [];
		$obj_site_setting = $this->SiteSettingsModel->first();
		if(isset($obj_site_setting) && $obj_site_setting!=null)
		{
			$arr_site_setting = $obj_site_setting->toArray();
		}
		$this->arr_view_data['site_data']    = $arr_site_setting;
        $this->arr_view_data['page_title']   = $this->module_title;

		return view($this->module_view_folder.'.contact_us',$this->arr_view_data);
	}

	public function store(Request $request)
	{
        $arr_rules = $user_data  = [];
        $arr_rules['name']            = "required";
        $arr_rules['contact']         = "required";
        $arr_rules['email']           = "required|email";
        $arr_rules['subject']         = "required";
        $arr_rules['message']         = "required";
        $arr_rules['hiddenRecaptcha'] = "required";

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        { 
            return redirect()->back()->withErrors($validator)->withInput();  
        }
        else
        {
            $user_data['name']     = trim($request->input('name'));
            $user_data['contact']  = trim($request->input('contact'));            
            $user_data['email_id'] = trim($request->input('email'));
            $user_data['subject']  = trim($request->input('subject'));
            $user_data['message']  = trim($request->input('message'));

    		$status = $this->ContactModel->create($user_data);
    		if($status)
    		{
                Session::flash('success', 'Contact enquiry message send successfully.');
                return redirect()->back();
    		}
    		else
    		{
                Session::flash('error', 'Error while sending contact enquiry message.');
                return redirect()->back();   			
    		}
    	}
        Session::flash('error', 'Error while sending contact enquiry message.');       
        return redirect()->back();
    }





}
