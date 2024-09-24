<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Common\Services\MailchimpService;

use Session;

class NewsLetterController extends Controller
{
	public function __construct(MailchimpService $mailchimp_service)
	{
        $this->arr_data                  = [];
        $this->admin_panel_slug          = config('app.project.admin_panel_slug');
        $this->admin_url_path            = url(config('app.project.admin_panel_slug'));
        $this->module_title              = "Newsletter Subscriber";
        $this->module_view_folder        = "admin.news_letter";
        $this->module_icon               = "fa-envelope";
        $this->module_url_path           = $this->admin_url_path."/newsletter_subscriber";
        $this->MailchimpService 		 = $mailchimp_service;
        
	}


	public function index()
	{
		$arr_response = $arr_subscriber = array();

		$arr_response = $this->MailchimpService->get_users_list();

		if (isset($arr_response) && is_array($arr_response) && isset($arr_response['arr_members']['members']) && sizeof($arr_response['arr_members']['members'])>0) {
			$arr_subscriber = $arr_response['arr_members']['members'];
		}

		$this->arr_data['arr_subscriber']   = $arr_subscriber;
		$this->arr_data['page_title']       = "Manage ".$this->module_title;
		$this->arr_data['module_icon']      = $this->module_icon;
		$this->arr_data['module_title']     = $this->module_title;
		$this->arr_data['module_url_path']  = $this->module_url_path;
		$this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;

		return view($this->module_view_folder.'.index',$this->arr_data);
	}


	public function delete($id=null)
	{
		if (isset($id) && $id!=null) 
		{
			$response = $this->MailchimpService->unsubscribe($id);

			if ($response=='SUCCESS') 
			{
				return redirect($this->module_url_path)->with('success','your removed successfully from subscription list.');
			}
		}

		return redirect($this->module_url_path)->with('error','Error while removing user from subscription list.');
	}
}
