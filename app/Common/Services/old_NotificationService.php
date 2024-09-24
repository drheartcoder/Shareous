<?php 

namespace App\Common\Services;
use Illuminate\Http\Request;
use App\Models\NotificationTemplateModel;
use App\Models\NotificationModel;
use App\Models\UserModel;
use Session;
use Mail;
use Auth;
use URL;

class old_NotificationService
{
	function __construct(
						NotificationTemplateModel $notification_template_model,
						NotificationModel $notification_model,
						UserModel $user_model
						)
	{
		$this->NotificationTemplateModel = $notification_template_model;
		$this->NotificationModel         = $notification_model;
		$this->UserModel                 = $user_model;
		$this->site_name                 = config('app.project.name');
	}

	public function account_verification_approvel($user_id=null)
	{
		if($user_id)
		{
			$user_details          = $this->UserModel->where('id',$user_id)->first();
			$notification_id       = 1;
			$obj_notification_data = $this->NotificationTemplateModel->where('id',$notification_id)->first();
			
			if($obj_notification_data)
			{
				$arr_notification_data                       = $obj_notification_data->toArray();
				$content                                     = isset($arr_notification_data['template_text'])? $arr_notification_data['template_text']:'';
				$content                                     = str_replace('##USER##', $user_details->user_name, $content);
				$content                                     = str_replace('##SITE_NAME##', $this->site_name, $content);
				$user_notification_data['notification_text'] = $content;
				$user_notification_data['is_read']           = '0';
				$user_notification_data['user_id']           = isset($user_id)? $user_id:'';
				$notification_status                         = $this->NotificationModel->create($user_notification_data);

				if($notification_status)
				{
					return true;
				}
				else
				{
					return false;
				}

				return false;
			}

			return false;
		}
		return false;
	}

	public function account_verification_rejection($user_id=null)
	{
		if($user_id)
		{
			$user_details          = $this->UserModel->where('id',$user_id)->first();
			$notification_id       = 3;
			$obj_notification_data = $this->NotificationTemplateModel->where('id',$notification_id)->first();
			
			if($obj_notification_data)
			{
				$arr_notification_data                       = $obj_notification_data->toArray();
				$content                                     = isset($arr_notification_data['template_text'])? $arr_notification_data['template_text']:'';
				$content                                     = str_replace('##USER##', $user_details->user_name, $content);
				$content                                     = str_replace('##SITE_NAME##', $this->site_name, $content);
				$user_notification_data['notification_text'] = $content;
				$user_notification_data['is_read']           = '0';
				$user_notification_data['user_id']           = isset($user_id)? $user_id:'';
				$notification_status                         = $this->NotificationModel->create($user_notification_data);

				if($notification_status)
				{
					return true;
				}
				else
				{
					return false;
				}

				return false;
			}

			return false;
		}
		return false;
	}

	public function admin_new_user_notification()
	{
			$notification_id       = 2;
			$obj_notification_data = $this->NotificationTemplateModel->where('id',$notification_id)->first();
			
			if($obj_notification_data)
			{
				$arr_notification_data                       = $obj_notification_data->toArray();
				$content                                     = isset($arr_notification_data['template_text'])? $arr_notification_data['template_text']:'';
				$user_notification_data['notification_text'] = $content;
				$user_notification_data['is_read']           = '0';
				$user_notification_data['user_id']           = '0';
				$user_notification_data['user_type']         = '2';
				$notification_status                         = $this->NotificationModel->create($user_notification_data);

				if($notification_status)
				{
					return true;
				}
				else
				{
					return false;
				}

				return false;
			}

			return false;
	}

	public function ticket_generation($user_id=false,$arr_data)
	{
		$arr_notif = [];
		if($arr_data!=null)
		{			
			$notification_id       = 4;
			$user_details = $this->UserModel->where('id',$user_id)->first();

			$obj_notification_data = $this->NotificationTemplateModel->where('id',$notification_id)->first();
			if($obj_notification_data)
			{
				$arr_notification_data                       = $obj_notification_data->toArray();
				$content                                     = isset($arr_notification_data['template_text'])? $arr_notification_data['template_text']:'';
				$arr_notif['query_subject']      			 = isset($arr_data['query_subject'])?$arr_data['query_subject']:'';
				$content                                     = str_replace('##USER_NAME##', $user_details->user_name, $content);
				$content                                     = str_replace('##SUBJECT##', $arr_notif['query_subject'], $content);
				$user_notification_data['notification_text'] = $content;

				$user_notification_data['is_read']           = '0';
				$user_notification_data['user_id']           = '0';
				$user_notification_data['user_type']         = '3';
				$notification_status                         = $this->NotificationModel->create($user_notification_data);

				if($notification_status)
				{
					return true;
				}
				else
				{
					return false;
				}

				return false;
			}
			return false;
		}
		return false;
	}

	public function support_send_token_closed($user_id=null,$ticket_id=null,$query_subject=null)
	{
		if($user_id)
		{
			$user_details          = $this->UserModel->where('id',$user_id)->first();

			$notification_id       = 5;
			$obj_notification_data = $this->NotificationTemplateModel->where('id',$notification_id)->first();
			$ticket_id             = isset($ticket_id)?$ticket_id:'NA';
			$query_subject         = isset($query_subject)?$query_subject:'NA';
			
			if($obj_notification_data)
			{
				$arr_notification_data                       = $obj_notification_data->toArray();
				$content                                     = isset($arr_notification_data['template_text'])? $arr_notification_data['template_text']:'';

				$content                                     = str_replace('##USER_NAME##', $user_details->display_name, $content);
				$content                                     = str_replace('##TICKET_ID##', $ticket_id, $content);
				$content                                     = str_replace('##QUERY_SUBJECT##', $query_subject, $content);
				$content                                     = str_replace('##SITE_NAME##', $this->site_name, $content);

				$user_notification_data['notification_text'] = $content;
				$user_notification_data['is_read']           = '0';
				$user_notification_data['user_type']         = '1';
				$user_notification_data['user_id']           = isset($user_id)? $user_id:'';
				$notification_status                         = $this->NotificationModel->create($user_notification_data);

				if($notification_status)
				{
					return true;
				}
				else
				{
					return false;
				}

				return false;
			}

			return false;
		}
		return false;
	}

	public function admin_property_status($user_id=false,$arr_data)
	{
		$arr_notif = [];
		if($arr_data!=null)
		{			
			$notification_id       = 6;
			$user_details = $this->UserModel->where('id',$user_id)->first();

			$obj_notification_data = $this->NotificationTemplateModel->where('id',$notification_id)->first();
			if($obj_notification_data)
			{
				$arr_notification_data                       = $obj_notification_data->toArray();
				$content                                     = isset($arr_notification_data['template_text'])? $arr_notification_data['template_text']:'';
				
				$content                                     = str_replace('##USER_NAME##', $user_details->user_name, $content);
				$content                                     = str_replace('##STATUS##',$arr_data, $content);
				$user_notification_data['notification_text'] = $content;

				$user_notification_data['is_read']           = '0';
				$user_notification_data['user_id']           = isset($user_id)?$user_id:'0';
				$user_notification_data['user_type']         = '3';
				$notification_status                         = $this->NotificationModel->create($user_notification_data);

				if($notification_status)
				{
					return true;
				}
				else
				{
					return false;
				}

				return false;
			}
			return false;
		}
		return false;
	}

	public function support_send_ticket_reply($user_id=null,$ticket_id=null,$query_subject=null)
	{
		$user_notification_data = [];
		if($user_id)
		{
			$user_details          = $this->UserModel->where('id',$user_id)->first();

			$notification_id       = 7;
			$obj_notification_data = $this->NotificationTemplateModel->where('id',$notification_id)->first();
			$ticket_id             = isset($ticket_id)?$ticket_id:'NA';
			$query_subject         = isset($query_subject)?$query_subject:'NA';

			if($obj_notification_data)
			{
				$arr_notification_data                       = $obj_notification_data->toArray();
				$content                                     = isset($arr_notification_data['template_text'])? $arr_notification_data['template_text']:'';

				$content                                     = str_replace('##USER_NAME##', $user_details->display_name, $content);
				$content                                     = str_replace('##TICKET_ID##', $ticket_id, $content);
				$content                                     = str_replace('##QUERY_SUBJECT##', $query_subject, $content);
				$content                                     = str_replace('##SITE_NAME##', $this->site_name, $content);

				$user_notification_data['notification_text'] = $content;
				$user_notification_data['is_read']           = '0';
				$user_notification_data['user_type']         = '1';
				$user_notification_data['user_id']           = isset($user_id)? $user_id:'';
				$notification_status                         = $this->NotificationModel->create($user_notification_data);

				if($notification_status)
				{
					return true;
				}
				else
				{
					return false;
				}

				return false;
			}

			return false;
		}
		return false;
	}

}