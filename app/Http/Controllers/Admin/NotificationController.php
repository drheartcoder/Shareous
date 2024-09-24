<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\NotificationModel;
use App\Common\Traits\MultiActionTrait;

use Auth;

class NotificationController extends Controller
{
	use MultiActionTrait;
	
	function __construct(NotificationModel $notification_model)
	{
		$this->NotificationModel  = $notification_model;
		$this->BaseModel          = $notification_model;
		$this->admin_panel_slug   = config('app.project.admin_panel_slug');
		$this->admin_url_path     = url(config('app.project.admin_panel_slug'));
		$this->module_url_path    = url($this->admin_panel_slug."/notification");
		$this->module_title       = "Notification";
		$this->module_view_folder = "admin.notification";
		$this->module_icon        = "fa-bell";
		$this->auth               = auth()->guard('admin');
		$this->admin_login_path   = $this->admin_url_path."/login";
	}

	public function index()
	{
		$arr_notification = array();

		$obj_data = $this->BaseModel->where('receiver_type','2')->orderBy('id','desc')->get();
		if($obj_data != FALSE) {
			$arr_notification = $obj_data->toArray();
		}

		$this->NotificationModel->where('receiver_type','2')->update(['is_read'=>'1']);

		$this->arr_view_data['arr_notification'] = $arr_notification;
		$this->arr_view_data['mdule_icon']       = $this->module_icon;
		$this->arr_view_data['page_title']       = $this->module_title;
		$this->arr_view_data['admin_panel_slug'] = $this->admin_panel_slug;
		$this->arr_view_data['page_name']        = "Manage ".str_plural($this->module_title);
		$this->arr_view_data['module_title']     = str_plural($this->module_title);
		$this->arr_view_data['module_url_path']  = $this->module_url_path;

		return view($this->module_view_folder.'.index',$this->arr_view_data);
	}

	public function get_count()
	{
		$count = $this->NotificationModel->where(['is_read' => '0', 'receiver_type' => '2', 'receiver_id' => '1'])->count();
		$data['count']  = $count;
		$data['status'] = 'success';
		
		return response()->json($data);
	}
}
