<?php

namespace App\Common\Services;

use App\Models\EmailTemplateModel;
use App\Models\SiteSettingModel;
use App\Models\UserModel;

use \Session;
use \Mail;

class EmailService
{
	public function __construct(UserModel $user, EmailTemplateModel $email)
	{
		$this->EmailTemplateModel = $email;
		$this->UserModel 		  = $user;
		$this->BaseModel          = $this->EmailTemplateModel;
	}

	public function send_mail($arr_mail_data = FALSE)
	{
		if(isset($arr_mail_data) && sizeof($arr_mail_data)>0)
		{		
			$arr_email_template = []; 
			$obj_email_template = $this->EmailTemplateModel->where('id',$arr_mail_data['email_template_id'])->first();

			if ($obj_email_template) {
				$arr_email_template = $obj_email_template->toArray();
				$user               = $arr_mail_data['user'];
				
				if (isset($arr_email_template['template_html'])) {
					$content = $arr_email_template['template_html'];
					$subject = $arr_email_template['template_subject'];

					if (isset($arr_mail_data['arr_built_content']) && sizeof($arr_mail_data['arr_built_content'])>0) {
						foreach ($arr_mail_data['arr_built_content'] as $key => $data) {
							$content = str_replace("##".$key."##",$data,$content);
							$subject = str_replace("##".$key."##",$data,$subject);
						}
					}
					
					$content   = view('front.email.general',compact('content'))->render();
					$subject   = html_entity_decode($subject);
					$content   = html_entity_decode($content);

					

					try{
						$send_mail = Mail::send(array(), array(), function($message) use ($user, $arr_email_template, $content, $subject)
						{
							$name  = isset($user['first_name']) ? $user['first_name'] : "";
							$message->from($arr_email_template['template_from_mail'], $arr_email_template['template_from']);
							$message->to($user['email'], $name )						
									->subject($subject)
									->setBody($content, 'text/html');
						});		        	
						return $send_mail;
					}
		            catch(\Exception $e){
		                return 'Mail not send';
		            }
				}
			}
		}
		return false;    
	}

	public function send_invoice_mail($arr_mail_data = FALSE)
	{
		if(isset($arr_mail_data) && sizeof($arr_mail_data)>0)
		{					
			$arr_email_template = [];
			$obj_email_template = $this->EmailTemplateModel			
									   ->where('id',$arr_mail_data['email_template_id'])
									   ->first();

			if($obj_email_template)
			{
				$attachment 		= '';
				$arr_email_template = $obj_email_template->toArray();
				$user               = $arr_mail_data['user'];
				$attachment         = $arr_mail_data['attachment'];
				
				if(isset($arr_email_template['template_html'])) {
					$content = $arr_email_template['template_html'];

					if(isset($arr_mail_data['arr_built_content']) && sizeof($arr_mail_data['arr_built_content']) > 0) {
						foreach($arr_mail_data['arr_built_content'] as $key => $data) {
							$content = str_replace("##".$key."##",$data,$content);
						}
					}

					$content = view('front.email.general',compact('content'))->render();
					$content = html_entity_decode($content);

					try{
						$send_mail = Mail::send(array(), array(), function($message) use ($user, $arr_email_template, $content, $attachment)
						{
							$name = isset($user['first_name']) ? $user['first_name'] : "";
							$message->from($arr_email_template['template_from_mail'], $arr_email_template['template_from']);
							$message->to($user['email'], $name );
							if(isset($attachment) && $attachment != '') {
								$message->attach($attachment);
							}
							$message->subject($arr_email_template['template_subject'])
									->setBody($content, 'text/html');
						});	
						return $send_mail;
					}
		            catch(\Exception $e){
		                return 'Mail not send';
		            }
				}
			}
		}
		return false;    
	}
	public function send_admin_email($arr_mail_data = FALSE)
	{
		if(isset($arr_mail_data) && sizeof($arr_mail_data)>0)
		{
			$arr_email_template = [];
			$obj_email_template = $this->EmailTemplateModel
			->with(['translations' => function ($query) {
				$query->where('locale','en');
			}])
			->whereHas('translations' , function ($query) {
				$query->where('locale','en');
			})
			->where('id',$arr_mail_data['email_template_id'])
			->first();
			
			if($obj_email_template) {
				$arr_email_template = $obj_email_template->toArray();

				if(isset($arr_email_template['translations'][0]['template_html'])) {
					$content = $arr_email_template['translations'][0]['template_html'];

					if(isset($arr_mail_data['arr_built_content']) && sizeof($arr_mail_data['arr_built_content']) > 0) {
						foreach($arr_mail_data['arr_built_content'] as $key => $data) {
							$content = str_replace("##".$key."##",$data,$content);
						}
					}

					$content = view('front.email.general',compact('content'))->render();
					$content = html_entity_decode($content);
					$site_setting = SiteSettingModel::first();
					$arr_site_settings = [];

					if($site_setting) {
						$arr_site_settings = $site_setting->toArray();
					}

					$user = $arr_mail_data['user'];
					//$use_mail_id = $arr_mail_data['use_mail_id'];
					$to_email = $arr_site_settings['contact_email'];

					try {
						$send_mail = Mail::send(array(), array(), function($message) use ($user, $arr_email_template, $content, $to_email)
						{
							$name = isset($user['first_name']) ? $user['first_name'] : "";
							$message->from($user['email'], $name);
							$message->to($to_email, config('app.project.name') )
									->subject($arr_email_template['translations'][0]['template_subject'])
									->setBody($content, 'text/html');
						});
						return $send_mail;
					}
		            catch(\Exception $e){
		                return 'Mail not send';
		            }
				}
			}
		}
		return false;  
	}

	public function send_notification_mail($arr_mail_data = FALSE)
	{
		if(isset($arr_mail_data) && sizeof($arr_mail_data) > 0){
			$arr_email_template = []; $subject='';	

			$obj_email_template = $this->EmailTemplateModel
										->with(['translations'])
										->where('id',$arr_mail_data['email_template_id'])
										->first();
			
			if($obj_email_template) {
				$arr_email_template = $obj_email_template->toArray();
				$user               = $arr_mail_data['user'];

				$preference = $this->check_language_preference($user['id']);
				$arr_email_template['translations'] = $this->arrange_locale_wise($arr_email_template['translations']);

				if(isset($arr_email_template['translations']) && sizeof($arr_email_template['translations'])>0
						&& isset($preference)) {
					foreach($arr_email_template['translations'] as $key => $template) {
						if($template['locale'] == $preference) {
							$subject = $template['template_subject'];
							$content = $template['template_html'];
						}
					}
				}

				if(isset($arr_mail_data['arr_built_content']) && sizeof($arr_mail_data['arr_built_content']) > 0){
					foreach($arr_mail_data['arr_built_content'] as $key => $data){
						$content = str_replace("##".$key."##",$data,$content);
					}
				}
				
				$content = view('front.email.general',compact('content'))->render();
				$content = html_entity_decode($content);

				try {
					$send_mail = Mail::send(array(), array(), function($message) use ($user, $arr_email_template, $content, $subject)
					{
						$name = isset($user['name']) ? $user['name']:"";
						$message->from($arr_email_template['template_from_mail'], $arr_email_template['template_from']);
						$message->to($user['email'], $name );
						$message->subject($subject);
						$message->setBody($content, 'text/html');
					});		        	
					return $send_mail;
				}
	            catch(\Exception $e){
	                return 'Mail not send';
	            }
			}
		}
		return false;  		
	}

	public function check_language_preference($user_id=false)
	{
		$preference = 'en';
		if($user_id!=false)
		{
			$obj_user = $this->UserModel->where('id',$user_id)->first();
			if($obj_user)
			{
				$preference = isset($obj_user->preferred_language) && $obj_user->preferred_language != "" ? $obj_user->preferred_language : 'en';
			}
		}
		return $preference;
	}

	public function arrange_locale_wise(array $arr_data)
	{
		if(sizeof($arr_data)>0)
		{
			foreach ($arr_data as $key => $data) 
			{
				$arr_tmp = $data;
				unset($arr_data[$key]);

				$arr_data[$data['locale']] = $data;                    
			}

			return $arr_data;
		}
		else
		{
			return [];
		}
	}

	public function support_send_close_ticket_mail($arr_mail_data = FALSE)
	{

		if( isset($arr_mail_data) && sizeof($arr_mail_data) > 0 ) {
			$arr_email_template = []; 
			$obj_email_template = $this->EmailTemplateModel->where('id',$arr_mail_data['email_template_id'])->first();
				
			if($obj_email_template) {
				$arr_email_template = $obj_email_template->toArray();
				$user['first_name'] = $arr_mail_data['user_name'];			
				$user['email']      = $arr_mail_data['email_to'];			
				$ticket_id          = $arr_mail_data['ticket_id'];			
				$query_subject      = $arr_mail_data['query_subject'];			

				if(isset($arr_email_template['template_html'])) {
					$content = $arr_email_template['template_html'];
					$subject = $arr_email_template['template_subject'];

					if(isset($arr_mail_data['arr_built_content']) && sizeof($arr_mail_data['arr_built_content']) > 0) {
						foreach($arr_mail_data['arr_built_content'] as $key => $data) {
							$content = str_replace("##".$key."##",$data,$content);
							$subject = str_replace("##".$key."##",$data,$subject);
						}

					}
					$content = str_replace("##SUBJECT##",$subject,$content);
					$content = view('front.email.general',compact('content'))->render();
					$subject = html_entity_decode($subject);
					$content = html_entity_decode($content);

					try {
						$send_mail = Mail::send(array(), array(), function($message) use ($user, $arr_email_template, $content, $subject)
						{
							$name = isset($user['first_name']) ? $user['first_name'] : "";
							$message->from($arr_email_template['template_from_mail'], $arr_email_template['template_from']);
							$message->to($user['email'], $name )						
							->subject($subject)
							->setBody($content, 'text/html');
						});		        	
						return $send_mail;
					}
		            catch(\Exception $e){
		                return 'Mail not send';
		            }
				}
			}
		}
		return false;    
	}

	

	
}?>
