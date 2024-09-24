<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\SiteSettingsModel;
use Validator;
use Session;
use Image;

class SiteSettingController extends Controller
{
	/*
    Auther : Sagar Pawar
    Comments: Controller to manage site setting
    */
	public function __construct(SiteSettingsModel $site_settings) 
	{
		$this->arr_view_data      = [];
		$this->module_title       = "Site Settings";
		$this->module_view_folder = "admin.site_settings";
		$this->admin_panel_slug   = config('app.project.admin_panel_slug');
		$this->module_url_path    = url($this->admin_panel_slug);
		$this->SiteSettingsModel  = $site_settings;
		$this->logo_path          = url('/').config('app.project.img_path.logo_path');
		$this->logo_public_path   = public_path().config('app.project.img_path.logo_path');

	}

	public function index()
	{
		$arr_site_settings                        = [];
		$obj_site_settings                        = $this->SiteSettingsModel->first();
		($obj_site_settings)? $arr_site_settings  = $obj_site_settings->toArray():NULL;
		$this->arr_view_data['arr_site_settings'] = $arr_site_settings;
		$this->arr_view_data['module_icon']       = 'fa-cog';
		$this->arr_view_data['page_title']        = 'Site Setting';
		$this->arr_view_data['module_url_path']   = $this->module_url_path;
		$this->arr_view_data['admin_panel_slug']  = $this->admin_panel_slug;
		$this->arr_view_data['logo_path']         = $this->logo_path;
		$this->arr_view_data['logo_public_path']  = $this->logo_public_path;
		$this->arr_view_data['module_title']  = $this->module_title;
		return view($this->module_view_folder.'.index',$this->arr_view_data);
	}

	public function update(Request $request)
	{

	//	dd($request->all());
		$file_name                    = '';
		$arr_rules                    = array();
		$arr_data                     = array();
		$arr_rules['site_name']       = "required";
		$arr_rules['site_status']     = "required";
		$arr_rules['email_address']   = "required|email";
		$arr_rules['contact_number']  = "required|min:7|max:16";
		$arr_rules['address']         = "required";
		$arr_rules['meta_desc']       = "required";
		$arr_rules['meta_keyword']    = "required";
		$arr_rules['facebook_url']    = "required";
		$arr_rules['twitter_url']     = "required";
		$arr_rules['linkedin_url']   = "required";
		$arr_rules['instagram_url']   = "required";
		//$arr_rules['google_plus_url'] = "required";
		//$arr_rules['play_store_url']   = "required";
		//$arr_rules['app_store_url']   = "required";
		// $arr_rules['youtube_url']     = "required";

		$validator = Validator::make($request->all(),$arr_rules);

		if($validator->fails())
		{       
			return redirect()->back()->withErrors($validator)->withInput();  
		}

		$arr_data['site_name']    		 = trim($request->input('site_name'));
		$arr_data['site_status']         = $request->input('site_status',1);
		$arr_data['site_email_address']  = $request->input('email_address');
		$arr_data['site_contact_number'] = $request->input('contact_number');
		$arr_data['site_address']        = $request->input('address');
		$arr_data['meta_desc']       	   = $request->input('meta_desc');
		$arr_data['meta_keyword']        = $request->input('meta_keyword');
		$arr_data['fb_url']              = $request->input('facebook_url');
		$arr_data['twitter_url']       	 = $request->input('twitter_url');
		$arr_data['linkedin_url']        = $request->input('linkedin_url');
		$arr_data['instagram_url']       = $request->input('instagram_url');
		//$arr_data['google_plus_url']     = $request->input('google_plus_url');
		//$arr_data['old_logo']            = $old_logo = $request->input('old_logo');
		//$arr_data['play_store_url']      = $request->input('play_store_url');
		//$arr_data['app_store_url']       = $request->input('app_store_url');
		$arr_data['lat']                 = $request->input('lat');
		$arr_data['lon']                 = $request->input('lon');
		
		/*if($request->hasFile('logo'))
		{
			$file_extension = strtolower($request->file('logo')->getClientOriginalExtension());
			
			if(in_array($file_extension,['png','jpg','jpeg']))
			{
				$file_extension = strtolower($request->file('logo')->getClientOriginalExtension());
			
				if(in_array($file_extension,['png','jpg','jpeg']))
				{
					$file     = $request->file('logo');
					$filename = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
					$path     = $this->logo_public_path . $filename;
					$isUpload = Image::make($file->getRealPath())->resize(128, 120)->save($path);
					
					if($isUpload)
					{
			
						if ($old_logo!="" && $old_logo!=null) 
						{
			
							$profile_logo = $this->logo_public_path.$old_logo;
			
							if(file_exists($profile_logo))
							{
								unlink($profile_logo);
							}
						}
					}
				}
				else
				{
					$file_name = $old_logo;
					Flash::error('Invalid File type, While creating '.str_singular($this->module_title));
					return redirect()->back();
				}
			}
		}   
		else
		{
			$file_name = $old_logo;
		}
		$arr_data['site_logo'] = isset($filename)? $filename: $file_name;*/
		$obj_data              = $this->SiteSettingsModel->first();
		
		if($obj_data)
		{
			$status_update = $obj_data->update($arr_data);

			if ($status_update) 
			{
				Session::flash('success',str_singular($this->module_title).' Updated Successfully');
			}
			else
			{
				Session::flash('error','Problem Occurred, While Updating '.str_singular($this->module_title));
			}
		}
		else
		{
			Session::flash('error','Problem Occurred, While Updating '.str_singular($this->module_title));
		}

		return redirect()->back();
	}
}
