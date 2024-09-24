<?php

namespace App\Common\Services;

use \Session;


class SMSService
{
	public function __construct()
	{
		$twilio_credentials = get_twilio_credential();

		$this->twilio_service_sid = (isset($twilio_credentials) && $twilio_credentials['twilio_service_sid'] != '') ? $twilio_credentials['twilio_service_sid'] : null;
		$this->twilio_sid         = (isset($twilio_credentials) && $twilio_credentials['twilio_sid'] != '') ? $twilio_credentials['twilio_sid'] : null;
		$this->twilio_token       = (isset($twilio_credentials) && $twilio_credentials['twilio_token'] != '') ? $twilio_credentials['twilio_token'] : null;
		$this->from_user_mobile   = (isset($twilio_credentials) && $twilio_credentials['from_user_mobile'] != '') ? $twilio_credentials['from_user_mobile'] : null;
	}

	public function send_SMS($arr_sms_data)
	{
		if(!isset($arr_sms_data) && empty($arr_sms_data)) 
		{
			return false;
		} 
		else 
		{
			$mobile_number = isset($arr_sms_data['mobile_number']) ? $arr_sms_data['mobile_number'] : '';
			$message_text  = isset($arr_sms_data['msg']) ? $arr_sms_data['msg'] : '';
		
			if ($mobile_number == '' && $message_text == '') 
			{
				return false;
			} 
			else 
			{
				if( isset( $this->twilio_service_sid ) && !empty( $this->twilio_service_sid ) && $this->twilio_service_sid == null )
				{
					$for   = 'messagingServiceSid';
					$value = $this->twilio_service_sid;
				}
				else
				{
					$for   = 'from';
					$value = $this->from_user_mobile;
				}

				try 
				{
					$client = new \Twilio\Rest\Client($this->twilio_sid, $this->twilio_token);
					$message = $client->messages->create(
															$mobile_number,
															array(
																	$for   => $value,
																	'body' => $message_text,
																)
														);
					if (isset($message)) 
					{
						return true;
					}
				} 
				catch (\Exception $e) 
				{
					return $e->getMessage();
				}
			}
		}
		return true;
	}

}



?>
