<?php

use App\Models\ApiCredentialModel;

function get_mailchimp_credential()
{
	$arr_mailchimp = array();
	$obj_mailchimp = ApiCredentialModel::first(['mailchimp_api_key','mailchimp_list_id']);

	if (isset($obj_mailchimp) && $obj_mailchimp != null) {
		if (isset($obj_mailchimp->mailchimp_api_key) && isset($obj_mailchimp->mailchimp_list_id)) {
			$arr_mailchimp['mailchimp_api_key'] = $obj_mailchimp->mailchimp_api_key;
			$arr_mailchimp['mailchimp_list_id'] = $obj_mailchimp->mailchimp_list_id;
		}
	}
	return $arr_mailchimp;
}

function get_onesignal_credential()
{
	$arr_onesignal = array();
	$obj_onesignal = ApiCredentialModel::first(['onesignal_api_key','onesignal_app_id', 'onesignal_api_mode', 'onesignal_sandbox_api_key', 'onesignal_sandbox_app_id']);

	if (isset($obj_onesignal) && $obj_onesignal != null) {
		if ($obj_onesignal->onesignal_api_mode == '2') {			// SANDBOX
			$arr_onesignal['onesignal_api_key'] = $obj_onesignal->onesignal_sandbox_api_key;
			$arr_onesignal['onesignal_app_id']  = $obj_onesignal->onesignal_sandbox_app_id;
		} else {
			$arr_onesignal['onesignal_api_key'] = $obj_onesignal->onesignal_api_key;
			$arr_onesignal['onesignal_app_id']  = $obj_onesignal->onesignal_app_id;
		}
	}
	return $arr_onesignal;
}

function get_twilio_credential()
{
	$arr_twilio = array();
	$obj_twilio = ApiCredentialModel::first(['twilio_mode','twilio_sid', 'twilio_token', 'twilio_test_sid', 'twilio_test_token', 'from_user_mobile', 'test_from_user_mobile', 'twilio_test_service_sid', 'twilio_service_sid']);

	if (isset($obj_twilio) && $obj_twilio != null) {
		if ($obj_twilio->twilio_mode == '2') {			// SANDBOX
			$arr_twilio['twilio_service_sid'] = $obj_twilio->twilio_test_service_sid;
			$arr_twilio['twilio_sid']         = $obj_twilio->twilio_test_sid;
			$arr_twilio['twilio_token']       = $obj_twilio->twilio_test_token;
			$arr_twilio['from_user_mobile']   = $obj_twilio->test_from_user_mobile;
		} else {
			$arr_twilio['twilio_service_sid'] = $obj_twilio->twilio_service_sid;
			$arr_twilio['twilio_sid']         = $obj_twilio->twilio_sid;
			$arr_twilio['twilio_token']       = $obj_twilio->twilio_token;
			$arr_twilio['from_user_mobile']   = $obj_twilio->from_user_mobile;
		}
	}
	return $arr_twilio;
}

function get_freshchat_credential()
{
	$arr_freshchat = array();
	$obj_freshchat = ApiCredentialModel::first(['freshchat_api_token', 'freshchat_api_mode', 'freshchat_api_test_token']);

	if (isset($obj_freshchat) && $obj_freshchat != null) {
		if ($obj_freshchat->freshchat_api_mode == '2') {			// SANDBOX
			$arr_freshchat['freshchat_api_token'] = $obj_freshchat->freshchat_api_test_token;
		} else {
			$arr_freshchat['freshchat_api_token'] = $obj_freshchat->freshchat_api_token;
		}
	}
	return $arr_freshchat;
}

function get_razorpay_credential()
{
	$arr_razorpay = array();
	$obj_razorpay = ApiCredentialModel::first(['razorpay_id','razorpay_secret', 'payment_mode', 'razorpay_sandbox_id', 'razorpay_sandbox_secret']);

	if (isset($obj_razorpay) && $obj_razorpay != null) {
		if ($obj_razorpay->payment_mode == '2') {			// SANDBOX
			$arr_razorpay['razorpay_id']     = $obj_razorpay->razorpay_sandbox_id;
			$arr_razorpay['razorpay_secret'] = $obj_razorpay->razorpay_sandbox_secret;
		} else {
			$arr_razorpay['razorpay_id']     = $obj_razorpay->razorpay_id;
			$arr_razorpay['razorpay_secret'] = $obj_razorpay->razorpay_secret;
		}
	}
	return $arr_razorpay;
}

function get_facebook_credential()
{
	$arr_facebook = array();
	$obj_facebook = ApiCredentialModel::first(['facebook_client_id','facebook_client_secret', 'facebook_sandbox_client_id', 'facebook_sandbox_client_secret', 'facebook_api_mode']);

	if (isset($obj_facebook) && $obj_facebook != null) {
		if ($obj_facebook->facebook_api_mode == '2') {			// SANDBOX
			$arr_facebook['facebook_client_id']     = $obj_facebook->facebook_sandbox_client_id;
			$arr_facebook['facebook_client_secret'] = $obj_facebook->facebook_sandbox_client_secret;
		} else {
			$arr_facebook['facebook_client_id']     = $obj_facebook->facebook_client_id;
			$arr_facebook['facebook_client_secret'] = $obj_facebook->facebook_client_secret;
		}
	}
	return $arr_facebook;
}

function get_twitter_credential()
{
	$arr_twitter = array();
	$obj_twitter = ApiCredentialModel::first(['twitter_client_id','twitter_client_secret', 'twitter_sandbox_client_id', 'twitter_sandbox_client_secret', 'twitter_api_mode']);

	if (isset($obj_twitter) && $obj_twitter != null) {
		if ($obj_twitter->twitter_api_mode == '2') {			// SANDBOX
			$arr_twitter['twitter_client_id']     = $obj_twitter->twitter_sandbox_client_id;
			$arr_twitter['twitter_client_secret'] = $obj_twitter->twitter_sandbox_client_secret;
		} else {
			$arr_twitter['twitter_client_id']     = $obj_twitter->twitter_client_id;
			$arr_twitter['twitter_client_secret'] = $obj_twitter->twitter_client_secret;
		}
	}
	return $arr_twitter;
}

function get_google_credential()
{
	$arr_google = array();
	$obj_google = ApiCredentialModel::first(['google_client_id','google_client_secret', 'google_sandbox_client_id', 'google_sandbox_client_secret', 'google_api_mode']);

	if (isset($obj_google) && $obj_google != null) {
		if ($obj_google->google_api_mode == '2') {			// SANDBOX
			$arr_google['google_client_id']     = $obj_google->google_sandbox_client_id;
			$arr_google['google_client_secret'] = $obj_google->google_sandbox_client_secret;
		} else {
			$arr_google['google_client_id']     = $obj_google->google_client_id;
			$arr_google['google_client_secret'] = $obj_google->google_client_secret;
		}
	}
	return $arr_google;
}

?>