<?php

namespace App\Http\Controllers\Support;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\NotificationModel;
use App\Common\Traits\MultiActionTrait;

use Session;

class NotificationController extends Controller
{
	
	use MultiActionTrait;
    function __construct(NotificationModel $notification_model)
	{
		$this->NotificationModel  = $notification_model;
		$this->BaseModel          = $notification_model;
		$this->support_panel_slug = config('app.project.support_panel_slug');
		$this->module_url_path    = url($this->support_panel_slug."/notification");
		$this->module_title       = "Notification";
		$this->module_view_folder = "support.notification";
		$this->module_icon        = "fa-bell";
		$this->auth               = auth()->guard('support');
		$user                     = $this->auth->user();
        if($user)
        {
            $this->user_id = $user->id;
        }
	}
	
	public function index()
	{
		$arr_notification = array();
		$obj_data = $this->NotificationModel->where('receiver_type','3')->where('receiver_id', $this->user_id)->orderBy('id','desc')->get();
		if(isset($obj_data) && $obj_data!=null)
		{
			$arr_notification = $obj_data->toArray();
		}
		
		/*Update notification status as read*/
		$this->NotificationModel->where('receiver_type','3')->update(['is_read'=>'1']);
		
		$this->arr_view_data['arr_notification']   = $arr_notification;
		$this->arr_view_data['mdule_icon']         = $this->module_icon;
		$this->arr_view_data['page_title']         = $this->module_title;
		$this->arr_view_data['support_panel_slug'] = $this->support_panel_slug;
		$this->arr_view_data['page_name']          = "Manage ".str_plural($this->module_title);
		$this->arr_view_data['module_title']       = str_plural($this->module_title);
		$this->arr_view_data['module_url_path']    = $this->module_url_path;

		return view($this->module_view_folder.'.index',$this->arr_view_data);
	}

	public function get_count()
	{
		$count = $this->NotificationModel->where(['is_read' => '0','receiver_type' => '3','receiver_id' => $this->user_id])->count();
		$data['count']  = $count;
		$data['status'] = 'success';

		return response()->json($data);
	}

}
