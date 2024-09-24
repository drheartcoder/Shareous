<?php 

namespace App\Common\Services;
use Illuminate\Http\Request;
use App\Models\EmailTemplateModel;
use Session;
use Mail;
use Auth;
use URL;


class Old_EmailService
{
	public function __construct()
	{
		$this->EmailTemplateModel = new EmailTemplateModel;
	}

	/*this function is for password broker email data*/
	public function get_forget_password_data($id=false,$token,$type,$user)
	{
		$arr_email_template_data = '';
		$obj_email_template_data = $this->EmailTemplateModel->where('id', $id)->first();
		
		if($obj_email_template_data)
		{
			$arr_email_template_data = $obj_email_template_data->toArray();
		}

		if(isset($type) && $type ==1)
		{
			$reset_link_url = URL::to('admin/password_reset/'.$token);
			$name           = 'ADMIN';
		}
		elseif(isset($type) && $type ==2)
		{
			$reset_link_url = URL::to('support/password_reset/'.$token);
			$name           = $user->first_name;	
		}
		elseif(isset($type) && $type ==3)
		{
			$reset_link_url = URL::to('/password_reset/'.$token);
			$name           = $user->first_name;	
		}

		$subject = isset($arr_email_template_data['template_subject'])? $arr_email_template_data['template_subject']: 'Forgot Password';
		$content = isset($arr_email_template_data['template_html'])?$arr_email_template_data['template_html']:'';
		$content = str_replace("##USER_NAME##",$name,$content);
		$content = str_replace("##SITE_URL##",$reset_link_url,$content);
		$content = str_replace("##SUBJECT##",$subject,$content);
		$content = html_entity_decode($content);

		return $content;
	}

	public function send_support_team_details_mail($arr_data=null)
	{
		$arr_email_template = [];

		if($arr_data!=null)
		{
			$obj_email_template = $this->EmailTemplateModel->where('id', 2)->first();

			if($obj_email_template)
			{
				$arr_email_template = $obj_email_template->toArray();
			}
			$arr_email['email_to']  = isset($arr_data['email_to'])?$arr_data['email_to']:'';
			$arr_email['user_name'] = isset($arr_data['user_name'])?$arr_data['user_name']:'';
			$arr_email['login_url'] = url('/support/login');
			$arr_email['subject'] = isset($arr_email_template['template_subject'])? $arr_email_template['template_subject'] : 'Your Account Details';

			$content       = isset($arr_email_template['template_html'])? $arr_email_template['template_html'] : '';
			$content       = str_replace("##SUBJECT##",$arr_email['subject'], $content);
			$content       = str_replace("##USER_NAME##",$arr_email['user_name'], $content);
			$content       = str_replace("##EMAIL##",$arr_email['email_to'], $content);
			$content       = str_replace("##PASSWORD##",$arr_data['password'], $content);
			$content       = str_replace("##SITE_URL##",$arr_email['login_url'], $content);
			$content       = view('admin.email.general', compact('content'))->render();

			$send_mail     = $this->send_mail($arr_email,$content);
			return $send_mail;
		}

	}

	function send_verification_user_mail($arr_data=null)
	{
			$obj_email_template = $this->EmailTemplateModel->where('id', 3)->first();

			if($obj_email_template)
			{
				$arr_email_template = $obj_email_template->toArray();
			}
			$arr_email['email_to']         = isset($arr_data['email'])?$arr_data['email']:'';
			$arr_email['user_name']        = isset($arr_data['user_name'])?$arr_data['user_name']:'';
			$arr_email['subject']          = isset($arr_email_template['template_subject'])? $arr_email_template['template_subject'] : 'Your Acoount Details';
			$arr_email['verification_url'] = url('/verify_account/'.$arr_data['user_id'].'/'.$arr_data['token']);

			$content       = isset($arr_email_template['template_html'])? $arr_email_template['template_html'] : '';
			$content       = str_replace("##SUBJECT##",$arr_email['subject'], $content);
			$content       = str_replace("##USER_NAME##",$arr_email['user_name'], $content);
			/*$content       = str_replace("##EMAIL##",$arr_email['email_to'], $content);*/
			/*$content       = str_replace("##PASSWORD##",$arr_data['password'], $content);*/
			$content       = str_replace("##SITE_URL##",$arr_email['verification_url'], $content);
			$content       = view('admin.email.general', compact('content'))->render();
						$send_mail     = $this->send_mail($arr_email,$content);
			return $send_mail;

	}


	public function support_send_document_approve_mail($arr_data=null)
	{
		$arr_email_template = [];

		if($arr_data!=null)
		{
			$obj_email_template = $this->EmailTemplateModel->where('id', 5)->first();

			if($obj_email_template)
			{
				$arr_email_template = $obj_email_template->toArray();
			}
			$arr_email['email_to']  = isset($arr_data['email_to'])?$arr_data['email_to']:'';
			$arr_email['user_name'] = isset($arr_data['user_name'])?$arr_data['user_name']:'';
			$arr_email['login_url'] = url('/support/login');
			$arr_email['subject'] = isset($arr_email_template['template_subject'])? $arr_email_template['template_subject'] : 'Your Account Details';

			$content       = isset($arr_email_template['template_html'])? $arr_email_template['template_html'] : '';
			$content       = str_replace("##SUBJECT##",$arr_email['subject'], $content);
			$content       = str_replace("##USER_NAME##",$arr_email['user_name'], $content);
			$content       = str_replace("##EMAIL##",$arr_email['email_to'], $content);
			$content       = str_replace("##SITE_URL##",$arr_email['login_url'], $content);
			$content       = view('admin.email.general', compact('content'))->render();

			$send_mail     = $this->send_mail($arr_email,$content);
			return $send_mail;
		}

	}

	public function support_send_document_rejection_mail($arr_data=null)
	{
		$arr_email_template = [];

		if($arr_data!=null)
		{
			$obj_email_template = $this->EmailTemplateModel->where('id', 6)->first();

			if($obj_email_template)
			{
				$arr_email_template = $obj_email_template->toArray();
			}
			$arr_email['email_to']  = isset($arr_data['email_to'])?$arr_data['email_to']:'';
			$arr_email['user_name'] = isset($arr_data['user_name'])?$arr_data['user_name']:'';
			$arr_email['login_url'] = url('/support/login');
			$arr_email['subject'] = isset($arr_email_template['template_subject'])? $arr_email_template['template_subject'] : 'Your Account Details';

			$content       = isset($arr_email_template['template_html'])? $arr_email_template['template_html'] : '';
			$content       = str_replace("##SUBJECT##",$arr_email['subject'], $content);
			$content       = str_replace("##USER_NAME##",$arr_email['user_name'], $content);
			$content       = str_replace("##EMAIL##",$arr_email['email_to'], $content);
			$content       = str_replace("##SITE_URL##",$arr_email['login_url'], $content);
			$content       = view('admin.email.general', compact('content'))->render();

			$send_mail     = $this->send_mail($arr_email,$content);
			return $send_mail;
		}

	}

	public function support_send_ticket_generation_mail($arr_data=null)
	{
		$arr_email_template = [];

		if($arr_data!=null)
		{
			$obj_email_template = $this->EmailTemplateModel->where('id', 7)->first();

			if($obj_email_template)
			{
				$arr_email_template = $obj_email_template->toArray();
			}
			$arr_email['email_to']      = isset($arr_data['email_to'])?$arr_data['email_to']:'';
			$arr_email['user_name']     = isset($arr_data['user_name'])?$arr_data['user_name']:'';
			$arr_email['ticket_id']     = isset($arr_data['ticket_id'])?$arr_data['ticket_id']:'';
			$arr_email['query_subject'] = isset($arr_data['query_subject'])?$arr_data['query_subject']:'';
			//$arr_email['login_url']     = url('/ticket');
			
			$subject       = isset($arr_email_template['template_subject'])? $arr_email_template['template_subject'] : 'Your Account Details';

			$subject       = str_replace("##TICKET_ID##",$arr_email['ticket_id'], $subject);

			$arr_email['subject']       = isset($subject)? $subject : 'Your Account Details';

			$content       = isset($arr_email_template['template_html'])? $arr_email_template['template_html']:'';
			$content       = str_replace("##SUBJECT##",$arr_email['subject'], $content);
			$content       = str_replace("##USER_NAME##",$arr_email['user_name'], $content);
			$content       = str_replace("##EMAIL##",$arr_email['email_to'], $content);
			$content       = str_replace("##TICKET_ID##",$arr_email['ticket_id'], $content);
			$content       = str_replace("##QUERY_SUBJECT##",$arr_email['query_subject'], $content);
			//$content       = str_replace("##SITE_URL##",$arr_email['login_url'], $content);
			$content       = view('front.email.general', compact('content'))->render();

			$send_mail     = $this->send_mail($arr_email,$content);
			return $send_mail;
		}
	}

	
	public function support_send_close_ticket_mail($arr_data=null)
	{
		$arr_email_template = [];

		if($arr_data!=null)
		{
			$obj_email_template = $this->EmailTemplateModel->where('id', 8)->first();

			if($obj_email_template)
			{
				$arr_email_template = $obj_email_template->toArray();
			}
			$arr_email['email_to']      = isset($arr_data['email_to'])?$arr_data['email_to']:'';
			$arr_email['user_name']     = isset($arr_data['user_name'])?$arr_data['user_name']:'';
			$arr_email['ticket_id']     = isset($arr_data['ticket_id'])?$arr_data['ticket_id']:'';
			$arr_email['query_subject'] = isset($arr_data['query_subject'])?$arr_data['query_subject']:'';
			$subject       = isset($arr_email_template['template_subject'])? $arr_email_template['template_subject'] : 'Your Account Details';

			$subject       = str_replace("##TICKET_ID##",$arr_email['ticket_id'], $subject);

			$arr_email['subject']       = isset($subject)? $subject : 'Your Account Details';

			$content       = isset($arr_email_template['template_html'])? $arr_email_template['template_html']:'';
			$content       = str_replace("##SUBJECT##",$arr_email['subject'], $content);
			$content       = str_replace("##USER_NAME##",$arr_email['user_name'], $content);
			$content       = str_replace("##EMAIL##",$arr_email['email_to'], $content);
			$content       = str_replace("##TICKET_ID##",$arr_email['ticket_id'], $content);
			$content       = str_replace("##QUERY_SUBJECT##",$arr_email['query_subject'], $content);
			$content       = view('front.email.general', compact('content'))->render();
			$send_mail     = $this->send_mail($arr_email,$content);
			return $send_mail;
		}
	}

	public function admin_status_property($arr_data=null)
	{
		$arr_email_template = [];

		

		
		if($arr_data!=null)
		{
			$obj_email_template = $this->EmailTemplateModel->where('id', 9)->first();

			if($obj_email_template)
			{
				$arr_email_template = $obj_email_template->toArray();
			}
			$arr_email['email_to']      = isset($arr_data['email_to'])?$arr_data['email_to']:'';
			$arr_email['user_name']     = isset($arr_data['user_name'])?$arr_data['user_name']:'';
			$arr_email['status']     	= isset($arr_data['status'])?$arr_data['status']:'';
			$subject       = isset($arr_email_template['template_subject'])? $arr_email_template['template_subject'] : 'Your Property Status';

			//$subject       = str_replace("##STATUS##",$arr_email['status'], $subject);


			$arr_email['subject']       = isset($subject)? $subject : 'Your Property Status';

			$content       = isset($arr_email_template['template_html'])? $arr_email_template['template_html']:'';
			$content       = str_replace("##SUBJECT##",$arr_email['subject'], $content);
			$content       = str_replace("##USER_NAME##",$arr_email['user_name'], $content);
			$content       = str_replace("##EMAIL##",$arr_email['email_to'], $content);			
			$content       = str_replace("##STATUS##",$arr_email['status'], $content);			
			$content       = view('front.email.general', compact('content'))->render();
			$send_mail     = $this->send_mail($arr_email,$content);
			return $send_mail;
		}
	}

	public function admin_status_rejected_property($arr_data=null)
	{
		$arr_email_template = [];
		
		if($arr_data!=null)
		{
			$obj_email_template = $this->EmailTemplateModel->where('id', 10)->first();

			if($obj_email_template)
			{
				$arr_email_template = $obj_email_template->toArray();
			}
			$arr_email['email_to']      = isset($arr_data['email_to'])?$arr_data['email_to']:'';
			$arr_email['user_name']     = isset($arr_data['user_name'])?$arr_data['user_name']:'';
			$arr_email['reject_comment']= isset($arr_data['reject_comment'])?$arr_data['reject_comment']:'';
			$arr_email['status']     	= isset($arr_data['status'])?$arr_data['status']:'';
			$subject       = isset($arr_email_template['template_subject'])? $arr_email_template['template_subject'] : 'Your Property Status';
			
			$arr_email['subject']       = isset($subject)? $subject : 'Your Property Status';

			$content       = isset($arr_email_template['template_html'])? $arr_email_template['template_html']:'';
			$content       = str_replace("##SUBJECT##",$arr_email['subject'], $content);
			$content       = str_replace("##USER_NAME##",$arr_email['user_name'], $content);
			$content       = str_replace("##EMAIL##",$arr_email['email_to'], $content);
			$content       = str_replace("##COMMENT##",$arr_email['reject_comment'], $content);
			$content       = str_replace("##STATUS##",$arr_email['status'], $content);			
			$content       = view('front.email.general', compact('content'))->render();
			$send_mail     = $this->send_mail($arr_email,$content);
			return $send_mail;
		}
	}

	public function support_send_ticket_reply($arr_data=null)
	{
		$arr_email_template = $arr_email =[];

		if($arr_data!=null)
		{
			$obj_email_template = $this->EmailTemplateModel->where('id', 11)->first();

			if($obj_email_template)
			{
				$arr_email_template = $obj_email_template->toArray();
			}
			$arr_email['email_to']      = isset($arr_data['email_to'])?$arr_data['email_to']:'';
			$arr_email['user_name']     = isset($arr_data['user_name'])?$arr_data['user_name']:'';
			$arr_email['ticket_id']     = isset($arr_data['ticket_id'])?$arr_data['ticket_id']:'';
			$arr_email['query_subject'] = isset($arr_data['query_subject'])?$arr_data['query_subject']:'';
			$arr_email['query_reply']   = isset($arr_data['query_reply'])?$arr_data['query_reply']:'';
			$subject       = isset($arr_email_template['template_subject'])? $arr_email_template['template_subject'] : 'Your Account Details';

			$subject       = str_replace("##TICKET_ID##",$arr_email['ticket_id'], $subject);

			$arr_email['subject']       = isset($subject)? $subject : 'Your Account Details';

			$content       = isset($arr_email_template['template_html'])? $arr_email_template['template_html']:'';
			$content       = str_replace("##SUBJECT##",$arr_email['subject'], $content);
			$content       = str_replace("##USER_NAME##",$arr_email['user_name'], $content);
			$content       = str_replace("##EMAIL##",$arr_email['email_to'], $content);
			$content       = str_replace("##TICKET_ID##",$arr_email['ticket_id'], $content);
			$content       = str_replace("##QUERY_SUBJECT##",$arr_email['query_subject'], $content);
			$content       = str_replace("##QUERY_REPLY##",$arr_email['query_reply'], $content);
			$content       = view('front.email.general', compact('content'))->render();
			$send_mail     = $this->send_mail($arr_email,$content);
			return $send_mail;
		}
	}

	function social_registration_mail($arr_data)
	{	
			$obj_email_template = $this->EmailTemplateModel->where('id', 3)->first();

			if($obj_email_template)
			{
				$arr_email_template = $obj_email_template->toArray();
			}
			$arr_email['email_to']         = isset($arr_data['email'])?$arr_data['email']:'';
			$arr_email['user_name']        = ucwords($arr_data['fname'].' '.$arr_data['lname']);
			$arr_email['subject']          = isset($arr_email_template['template_subject'])? $arr_email_template['template_subject'] : 'Your Acoount Details';			

			$content       = isset($arr_email_template['template_html'])? $arr_email_template['template_html'] : '';
			$content       = str_replace("##SUBJECT##",$arr_email['subject'], $content);
			$content       = str_replace("##USER_NAME##",$arr_email['user_name'], $content);
			$content       = str_replace("##EMAIL##",$arr_email['email_to'], $content);
			$content       = str_replace("##PASSWORD##",$arr_data['plain_text_password'], $content);			
			$content       = view('admin.email.general', compact('content'))->render();
						$send_mail     = $this->send_mail($arr_email,$content);
			return $send_mail;
	}


	public function send_mail($arr_email,$content)
	{
    	$from_email                    = 'admin@shareous.com';//get_global_site_mail_address();
    	$project_name                  = config('app.project.name');
    	$send_mail = Mail::send(array(), array(),function($message) use($arr_email,$content,$from_email,$project_name)
    	{
    		$message->from($from_email,$project_name);
    		$message->to($arr_email['email_to'],$arr_email['user_name'])
    		->subject($arr_email['subject'])
    		->setBody($content, 'text/html');
    	});

    	return $send_mail;
    }



}