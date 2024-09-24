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

class NotificationService
{
	function __construct(
							NotificationTemplateModel $notification_template_model,
							NotificationModel         $notification_model,
							UserModel                 $user_model
						)
	{
		$this->NotificationTemplateModel = $notification_template_model;
		$this->NotificationModel         = $notification_model;
		$this->UserModel                 = $user_model;
		$this->site_name                 = config('app.project.name');
	}

	public function send_notification($arr_notification_data = FALSE)
	{
		if(isset($arr_notification_data) && sizeof($arr_notification_data)>0)
		{
			$obj_notification_data = $this->NotificationTemplateModel->where('id',$arr_notification_data['notify_template_id'])->first();
			
			if($obj_notification_data)
			{
				$arr_notification_arr = $obj_notification_data->toArray();

				if(isset($arr_notification_arr['template_text']))
				{
					$content = $arr_notification_arr['template_text'];				
						
					if(isset($arr_notification_data['notification_text']) && sizeof($arr_notification_data['notification_text'])>0)
					{
						foreach($arr_notification_data['notification_text'] as $key => $data)
						{
							$content = str_replace("##".$key."##",$data,$content);
						}
					}
				}
				
				$user_notification_data['notification_text'] = $content;
				$user_notification_data['sender_id']         = $arr_notification_data['sender_id'];
				$user_notification_data['receiver_id']       = $arr_notification_data['receiver_id'];
				$user_notification_data['sender_type']       = $arr_notification_data['sender_type'];
				$user_notification_data['receiver_type']     = $arr_notification_data['receiver_type'];
				$user_notification_data['is_read']           = '0';
				$user_notification_data['url']               = isset($arr_notification_data['url']) ? $arr_notification_data['url'] : '';
				
				$notification_status = $this->NotificationModel->create($user_notification_data);

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

		}
		return false; 
	}	

}