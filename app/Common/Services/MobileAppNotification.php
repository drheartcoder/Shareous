<?php

namespace App\Common\Services;

use \Session;


class MobileAppNotification
{
	public function __construct(){
		//Local
		/*$this->OneSignalAppId = "OWNlMTY5NWUtYjVlOC00ZDZmLThmZmYtMTVkNjIwYzg5ZTVj";
		$this->OneSignalApiKey = "3b107ba3-8b40-4ae8-9f1a-aa9303e34e13";*/

		//live
		$this->OneSignalAppId  = "cc10b31b-8b3a-4882-b628-c749799d1933";
		$this->OneSignalApiKey = "MWEyYzIwYjQtYzNmYi00Nzk1LWIwYjEtZDc0NTg0MGE1OWRi";

		$onesignal_credentials = get_onesignal_credential();

		$this->OneSignalAppId  = (isset($onesignal_credentials) && $onesignal_credentials['onesignal_app_id'] != '') ? $onesignal_credentials['onesignal_app_id'] : config('app.onesignal_credentials.onesignal_app_id');
		$this->OneSignalApiKey = (isset($onesignal_credentials) && $onesignal_credentials['onesignal_api_key'] != '') ? $onesignal_credentials['onesignal_api_key'] : config('app.onesignal_credentials.onesignal_api_key');
	}

	public function send_app_notification($headings,$content,$user_id)
	{
		/*dd($headings,$content,$user_id,$this->OneSignalAppId,$this->OneSignalApiKey);*/

		if ($this->OneSignalAppId != '' && $this->OneSignalApiKey != '')
		{
			//$filters = array(//["field" => "tag", "key" => "id", "relation" => "=", "value" => $user_id],["field" => "tag", "key" => "user_id", "relation" => "=", "value" => $user_id],);

			$filters = array(array("field" => "tag", "key" => "user_id", "relation" => "=", "value" => $user_id));

			$fields = array(	
								'app_id' 	         => $this->OneSignalAppId,
								'headings'	         => array("en" => $headings),	
								'contents' 	         => array("en" => $content),
								'filters' 	         => $filters,
								//'included_segments'	 => 'Active Users',
								'content_available'  => true,
								'ios_badgeType'      => 'Increase',
								'ios_badgeCount' 	 => '1',
								'priority' 	         => 10,
							);

			$fields = json_encode($fields);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
													   'Authorization: Basic '.$this->OneSignalApiKey));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			$response = curl_exec($ch);
			curl_close($ch);
			//dd($response);
			if (isset($response)) 
			{
				$arr_responce = json_decode($response,true);
				if (isset($arr_responce['errors']) && sizeof($arr_responce['errors'])>0) 
				{
					$responce_data['status'] = 'ERROR';

					if (isset($arr_responce['errors'][0])) 
					{
						$responce_data['message']  = $arr_responce['errors'][0];
					}
					return $responce_data;
				}
				elseif (isset($arr_responce['recipients']) && $arr_responce['recipients']==0) 
				{
					$responce_data['status'] = 'ERROR';

					if (isset($arr_responce['errors'][0])) 
					{
						$responce_data['message']  = $arr_responce['errors'][0];
					}
					return $responce_data;
				}
				elseif (isset($arr_responce['id']) && $arr_responce['id']!="") 
				{
					$responce_data['status'] 	= 'SUCCESS';
					$responce_data['id'] 		= $arr_responce['id'];

					return $responce_data;
				}
			}
		}
		else
		{
			$responce_data['status'] 	= 'ERROR';
			$responce_data['message']   = 'Error in your notifications setup , please check notifications credentials.';

			return $responce_data;
		}

	}

}



?>
