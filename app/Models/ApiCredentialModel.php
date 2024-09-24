<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiCredentialModel extends Model
{
 	protected $table = 'api_details';
	protected $fillable = [
							'razorpay_id',
							'razorpay_secret',
							'razorpay_sandbox_id',
							'razorpay_sandbox_secret',
							'payment_mode',
							'onesignal_api_mode',
							'onesignal_api_key',							
							'onesignal_app_id',
							'onesignal_sandbox_api_key',							
							'onesignal_sandbox_app_id',
							'mailchimp_api_key',
							'mailchimp_list_id',
							'twilio_mode',
							'twilio_test_service_sid',
							'twilio_test_sid',
							'twilio_test_token',
							'test_from_user_mobile',
							'twilio_service_sid',
							'twilio_sid',
							'twilio_token',
							'from_user_mobile',
							'freshchat_api_mode',
							'freshchat_api_test_token',
							'freshchat_api_test_app_id',
							'freshchat_api_test_app_key',
							'freshchat_api_token',
							'freshchat_api_app_id',
							'freshchat_api_app_key',
							'facebook_api_mode',
							'facebook_client_id',
							'facebook_client_secret',
							'facebook_sandbox_client_id',
							'facebook_sandbox_client_secret',
							'twitter_api_mode',
							'twitter_client_id',
							'twitter_client_secret',
							'twitter_sandbox_client_id',
							'twitter_sandbox_client_secret',
							'google_api_mode',
							'google_client_id',
							'google_client_secret',
							'google_sandbox_client_id',
							'google_sandbox_client_secret',
						];
}
