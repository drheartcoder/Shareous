<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SiteSettingsModel;
use Validator;

class SocialCredentialController extends Controller
{
    public function __construct(SiteSettingsModel $site_settings_model)
    {
	    $this->arr_view_data      = [];
    	$this->admin_url_path     = url(config('app.project.admin_panel_slug'));
	    $this->admin_panel_slug   = config('app.project.admin_panel_slug');
	    $this->module_url_path    = $this->admin_url_path."/social_credentials";
	    $this->module_view_folder = "admin.social_credentials";
	    $this->module_title       = "Social Credentials";
	    $this->SiteSettingsModel  = $site_settings_model;
	    $this->module_icon        = 'fa-key';
    }
    function index()
    {
    	$arr_credentials = [];
    	$obj_credentials = $this->SiteSettingsModel->first(['id','fb_client_id','fb_client_secret','fb_status','google_client_id','google_client_secret','google_api_credential','google_status','twitter_client_id','twitter_client_secret','twitter_status']);

    	if(isset($obj_credentials) && $obj_credentials!=null)
    	{
    		$arr_credentials = $obj_credentials->toArray();
    	}

    	$this->arr_view_data['record'] 			= $arr_credentials;
	    $this->arr_view_data['page_icon']       = $this->module_icon;
	    $this->arr_view_data['module_url_path'] = $this->module_url_path;
	    $this->arr_view_data['module_icon']     = $this->module_icon;
	    $this->arr_view_data['module_title']    = $this->module_title;
	    $this->arr_view_data['page_title']      = 'Manage '.$this->module_title;
	    $this->arr_view_data['module_url']      = $this->module_url_path;
	    $this->arr_view_data['admin_panel_slug']= $this->admin_panel_slug;
    	return view($this->module_view_folder.'.edit',$this->arr_view_data);
    }
    public function update(Request $request, $id)
	{
	    $id                                 = base64_decode($id);
	    $arr_rules['fb_client_id']       	= 'required';
	    $arr_rules['fb_client_secret']    	= 'required';
	    $arr_rules['fb_status']     		= 'required';
	    $arr_rules['google_client_id']      = 'required';
	    $arr_rules['google_client_secret']  = 'required';
	    $arr_rules['google_api_credential'] = 'required';
	    $arr_rules['google_status']    	    = 'required';
	    $arr_rules['twitter_client_id']     = 'required';
	    $arr_rules['twitter_client_secret'] = 'required';
	    $arr_rules['twitter_status']    	= 'required';	
	    $msg                                = array( 'required' =>'Please enter :attribute');
	    $validator                          = Validator::make($request->all(), $arr_rules, $msg);

	    if($validator->fails()) 
	    {
	        return redirect()->back()->withErrors($validator)->withInput($request->all());
	    }

	    $site_data  = $this->SiteSettingsModel->where('id',$id)->first();
	    
	    if($site_data)
	    {
	        $site_data->fb_client_id          = $request->input('fb_client_id' ,null);
	        $site_data->fb_client_secret      = $request->input('fb_client_secret' ,null);
	        $site_data->fb_status             = $request->input('fb_status' ,null);
	        $site_data->google_client_id      = $request->input('google_client_id', null);
	        $site_data->google_client_secret  = $request->input('google_client_secret', null);
	        $site_data->google_api_credential = $request->input('google_api_credential' ,null);
	        $site_data->google_status         = $request->input('google_status', null);
	        $site_data->twitter_client_id     = $request->input('twitter_client_id', null);
	        $site_data->twitter_client_secret = $request->input('twitter_client_secret', null);
	        $site_data->twitter_status        = $request->input('twitter_status', null);     	
	        $site_data->save();

	        return redirect()->back()->with('success','Record updated successfully');   
	    }
	    return redirect()->back()->with('error','Error while updating Record'); 
	}
}
