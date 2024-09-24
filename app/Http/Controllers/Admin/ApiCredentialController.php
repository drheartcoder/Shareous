<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ApiCredentialModel;

use Validator;

class ApiCredentialController extends Controller
{
	public function __construct(ApiCredentialModel $apicredential)
	{
	    $this->admin_url_path     = url(config('app.project.admin_panel_slug'));
	    $this->admin_panel_slug   = config('app.project.admin_panel_slug');
	    $this->module_url_path    = $this->admin_url_path."/api_credentials";
	    $this->module_view_folder = "admin.api_credentials";
	    $this->module_title       = "Api Credentials";
	    $this->module_icon        = 'fa-key';
	    $this->arr_view_data      = [];

	    $this->ApiCredentialModel     = $apicredential;
	}

	public function index()
	{
		$arr_record = [];
		$obj_record = $this->ApiCredentialModel->first();
	    
	    if ($obj_record) {
	        $arr_record = $obj_record->toArray();
	    }

		$this->arr_view_data['record']           = $arr_record;
		$this->arr_view_data['page_icon']        = $this->module_icon;
		$this->arr_view_data['id']               = $this->module_icon;
		$this->arr_view_data['module_url_path']  = $this->module_url_path;
		$this->arr_view_data['module_icon']      = $this->module_icon;
		$this->arr_view_data['module_title']     = $this->module_title;
		$this->arr_view_data['page_title']       = 'Manage '.$this->module_title;
		$this->arr_view_data['module_url']       = $this->module_url_path;
		$this->arr_view_data['admin_panel_slug'] = $this->admin_panel_slug;
	    return view($this->module_view_folder.'.index',$this->arr_view_data);    
	}

	public function update(Request $request, $id)
	{
		$id = base64_decode($id);

		//dd( $request->all() );

	    $arr_rules['payment_mode'] = 'required';
	    if($request->input('payment_mode') == '1') {
			$arr_rules['razorpay_id']     = 'required';
			$arr_rules['razorpay_secret'] = 'required';
		} else {
			$arr_rules['razorpay_sandbox_id']     = 'required';
			$arr_rules['razorpay_sandbox_secret'] = 'required';
		}

	    $arr_rules['onesignal_api_mode'] = 'required';
	    if($request->input('onesignal_api_mode') == '1') {
			$arr_rules['onesignal_api_key'] = 'required';
			$arr_rules['onesignal_app_id']  = 'required';
		} else {
			$arr_rules['onesignal_sandbox_api_key'] = 'required';
			$arr_rules['onesignal_sandbox_app_id']  = 'required';
		}

	    $arr_rules['mailchimp_api_key'] = 'required';
	    $arr_rules['mailchimp_list_id'] = 'required';

	    $arr_rules['twilio_mode'] = 'required';
	    if($request->input('twilio_mode') == '1') {
			//$arr_rules['twilio_service_sid'] = 'required';
			$arr_rules['twilio_sid']         = 'required';
			$arr_rules['twilio_token']       = 'required';
			$arr_rules['from_user_mobile']   = 'required';
	    } else {
			//$arr_rules['twilio_test_service_sid'] = 'required';
			$arr_rules['twilio_test_sid']         = 'required';
			$arr_rules['twilio_test_token']       = 'required';
			$arr_rules['test_from_user_mobile']   = 'required';
	    }

	    $arr_rules['freshchat_api_mode'] = 'required';
	    if($request->input('freshchat_api_mode') == '1') {
			$arr_rules['freshchat_api_token']   = 'required';
			$arr_rules['freshchat_api_app_id']  = 'required';
			$arr_rules['freshchat_api_app_key'] = 'required';
		} else {
			$arr_rules['freshchat_api_test_token']   = 'required';
			$arr_rules['freshchat_api_test_app_id']  = 'required';
			$arr_rules['freshchat_api_test_app_key'] = 'required';
		}

		$arr_rules['facebook_api_mode'] = 'required';
	    if($request->input('facebook_api_mode') == '1') {
			$arr_rules['facebook_client_id']     = 'required';
			$arr_rules['facebook_client_secret'] = 'required';
		} else {
			$arr_rules['facebook_sandbox_client_id']     = 'required';
			$arr_rules['facebook_sandbox_client_secret'] = 'required';
		}

		$arr_rules['twitter_api_mode'] = 'required';
	    if($request->input('twitter_api_mode') == '1') {
			$arr_rules['twitter_client_id']     = 'required';
			$arr_rules['twitter_client_secret'] = 'required';
		} else {
			$arr_rules['twitter_sandbox_client_id']     = 'required';
			$arr_rules['twitter_sandbox_client_secret'] = 'required';
		}

		$arr_rules['google_api_mode'] = 'required';
	    if($request->input('google_api_mode') == '1') {
			$arr_rules['google_client_id']     = 'required';
			$arr_rules['google_client_secret'] = 'required';
		} else {
			$arr_rules['google_sandbox_client_id']     = 'required';
			$arr_rules['google_sandbox_client_secret'] = 'required';
		}

		$msg       = array('required' =>'Please enter :attribute');
		$validator = Validator::make($request->all(), $arr_rules, $msg);
	    if($validator->fails()) {
	        return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error','All fields are required.');
	    }

	    $api_data = $this->ApiCredentialModel->where('id', $id)->first();
	    
	    if($api_data) {
			$api_data->payment_mode                   = $request->input('payment_mode', null);
			$api_data->razorpay_id                    = $request->input('razorpay_id' ,null);
			$api_data->razorpay_secret                = $request->input('razorpay_secret' ,null);
			$api_data->razorpay_sandbox_id            = $request->input('razorpay_sandbox_id' ,null);
			$api_data->razorpay_sandbox_secret        = $request->input('razorpay_sandbox_secret', null);
			
			$api_data->onesignal_api_mode             = $request->input('onesignal_api_mode', null);
			$api_data->onesignal_api_key              = $request->input('onesignal_api_key', null);
			$api_data->onesignal_app_id               = $request->input('onesignal_app_id', null);
			$api_data->onesignal_sandbox_api_key      = $request->input('onesignal_sandbox_api_key', null);
			$api_data->onesignal_sandbox_app_id       = $request->input('onesignal_sandbox_app_id', null);
			
			$api_data->mailchimp_api_key              = $request->input('mailchimp_api_key', null);
			$api_data->mailchimp_list_id              = $request->input('mailchimp_list_id', null);
			
			$api_data->twilio_mode                    = $request->input('twilio_mode', null);
			$api_data->twilio_test_service_sid        = $request->input('twilio_test_service_sid', null);
			$api_data->twilio_test_sid                = $request->input('twilio_test_sid', null);
			$api_data->twilio_test_token              = $request->input('twilio_test_token', null);
			$api_data->test_from_user_mobile          = $request->input('test_from_user_mobile', null);
			$api_data->twilio_service_sid             = $request->input('twilio_service_sid', null);
			$api_data->twilio_sid                     = $request->input('twilio_sid', null);
			$api_data->twilio_token                   = $request->input('twilio_token', null);
			$api_data->from_user_mobile               = $request->input('from_user_mobile', null);
			
			$api_data->freshchat_api_mode             = $request->input('freshchat_api_mode', null);
			$api_data->freshchat_api_test_token       = $request->input('freshchat_api_test_token', null);
			$api_data->freshchat_api_test_app_id      = $request->input('freshchat_api_test_app_id', null);
			$api_data->freshchat_api_test_app_key     = $request->input('freshchat_api_test_app_key', null);
			$api_data->freshchat_api_token            = $request->input('freshchat_api_token', null);
			$api_data->freshchat_api_app_id           = $request->input('freshchat_api_app_id', null);
			$api_data->freshchat_api_app_key          = $request->input('freshchat_api_app_key', null);

			$api_data->facebook_api_mode              = $request->input('facebook_api_mode', null);
			$api_data->facebook_client_id             = $request->input('facebook_client_id', null);
			$api_data->facebook_client_secret         = $request->input('facebook_client_secret', null);
			$api_data->facebook_sandbox_client_id     = $request->input('facebook_sandbox_client_id', null);
			$api_data->facebook_sandbox_client_secret = $request->input('facebook_sandbox_client_secret', null);

			$api_data->twitter_api_mode               = $request->input('twitter_api_mode', null);
			$api_data->twitter_client_id              = $request->input('twitter_client_id', null);
			$api_data->twitter_client_secret          = $request->input('twitter_client_secret', null);
			$api_data->twitter_sandbox_client_id      = $request->input('twitter_sandbox_client_id', null);
			$api_data->twitter_sandbox_client_secret  = $request->input('twitter_sandbox_client_secret', null);

			$api_data->google_api_mode                = $request->input('google_api_mode', null);
			$api_data->google_client_id               = $request->input('google_client_id', null);
			$api_data->google_client_secret           = $request->input('google_client_secret', null);
			$api_data->google_sandbox_client_id       = $request->input('google_sandbox_client_id', null);
			$api_data->google_sandbox_client_secret   = $request->input('google_sandbox_client_secret', null);
			
	        $status = $api_data->save();

	        if ($status) {
	        	return redirect()->back()->with('success','Credentials updated successfully');   
	        }

	        return redirect()->back()->with('error','Error while credential settings'); 
	    }

	    return redirect()->back()->with('error','Error while credential settings'); 
	}
    
}
