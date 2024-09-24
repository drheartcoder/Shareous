<?php 

namespace App\Common\Services;

class MailchimpService
{
	public function __construct()
	{

		$this->apiKey 		= null;
		$this->listId 		= null;
		$this->dataCenter 	= null;
		$this->url 			= null;

		$mailchimp_credentials = get_mailchimp_credential();

		$this->apiKey = (isset($mailchimp_credentials) && $mailchimp_credentials['mailchimp_api_key'] != '') ? $mailchimp_credentials['mailchimp_api_key'] : config('app.mailchimp_credentials.mailchimp_api_key');
    	$this->listId = (isset($mailchimp_credentials) && $mailchimp_credentials['mailchimp_list_id'] != '') ? $mailchimp_credentials['mailchimp_list_id'] : config('app.mailchimp_credentials.mailchimp_list_id');

    	$this->dataCenter = substr($this->apiKey, strpos($this->apiKey,'-')+1);
	    	$this->url = 'https://' . $this->dataCenter . '.api.mailchimp.com/3.0/lists/' . $this->listId;
		
	}

	public function subscribe($email=null)
	{
		if($email != null && $this->apiKey != null && $this->listId != null && $this->dataCenter != null && $this->url != null) {
		    
		    $url = $this->url.'/members/';

		    $json = json_encode([
		        'email_address' => $email,
		        'email_type'    => 'html',
		        'status' 		=> 'subscribed']);

		    $ch = curl_init($url);
		    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

		    $curl_responce = curl_exec($ch);
		    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		    curl_close($ch);
		    $arr_responce = json_decode($curl_responce,true);

		    if (isset($httpCode) && $httpCode!=200) {
		    	if ($httpCode==400 && isset($arr_responce['title'])) {
		    		if ($arr_responce['title']=='Member Exists') {
		    			return 'ALREADY_EXISTS';
		    		} elseif ($arr_responce['title']=='Invalid Resource') {
		    			return 'INVALID_EMAIL';
		    		}
		    	}
		    } else if($httpCode==200 && isset($arr_responce['status']) && $arr_responce['status']=='subscribed') {
		    	return 'SUBSCRIBED';
		    } else {
		    	return 'ERROR';
		    }
		} else {
			return 'ERROR';
		}
	}


	public function get_users_list()
	{
		$arr_responce = array();
		$arr_members  = array();

		if($this->apiKey!=null && $this->listId!=null && $this->dataCenter!=null && $this->url!=null) 
		{
			$data = array(
				'fields' => 'total_items,members.id,members.email_address,members.timestamp_opt,members.unique_email_id,members.status',
				'count' => 1000, // the number of lists to return, default - 10
				'sort_field'=>'timestamp_signup',
				'sort_dir'=>'DESC'
			);

		    $url = $this->url.'/members?'. http_build_query($data);

		    $ch = curl_init($url);
		    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		    $curl_responce = curl_exec($ch);
		    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		    curl_close($ch);

		    $arr_members = json_decode($curl_responce,true);
		   
		    if($httpCode==200 && isset($arr_members) && is_array($arr_members)>0 && sizeof($arr_members)>0)
		    {
		    	$arr_responce['status'] 		= 'SUCCESS';
		    	$arr_responce['message'] 		= 'Member list get successfully.';
		    	$arr_responce['arr_members'] 	= $arr_members;
		    }
		    else
		    {
		    	$arr_responce['status'] 		= 'ERROR';
		    	$arr_responce['message'] 		= 'Error while getting member list.';
		    }
		}
		else
		{
			$arr_responce['status'] 		= 'ERROR';
		    $arr_responce['message'] 		= 'You haave invalid api credentials settings.';
		}


		return $arr_responce;

	}

	function unsubscribe($subscriber_hash=null)
	{
		if($subscriber_hash!=null && $this->apiKey!=null && $this->listId!=null && $this->dataCenter!=null && $this->url!=null) 
		{
		    
		    $url = $this->url.'/members/'.$subscriber_hash;

		    $ch = curl_init($url);
		    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		    $curl_responce = curl_exec($ch);
		    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		    curl_close($ch);
		    $arr_responce = json_decode($curl_responce,true);

		    if (isset($httpCode) && $httpCode==204)
		    {
		    	return 'SUCCESS';	
		    }
		}
		
		return 'ERROR';
		
	}
}