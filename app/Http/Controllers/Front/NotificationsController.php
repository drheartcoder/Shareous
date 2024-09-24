<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\NotificationModel;
use App\Models\UserModel;

use Session;
use Auth;
//use Validator;
//use Hash;
//use Image;
//use Crypt;


class NotificationsController extends Controller
{
    function __construct(NotificationModel $notification_model,
                         UserModel         $user_model)
	{
		$this->auth                  		 = auth()->guard('users');
		$this->array_view_data               = [];
		$this->module_title                  = 'Notifications';
		$this->module_view_folder            = 'front.notifications';		
		$this->module_url_path               = url('/notifications');

		$this->profile_image_public_path     = url('/').config('app.project.img_path.user_profile_images');
		$this->profile_image_base_path       = public_path().config('app.project.img_path.user_profile_images');
        $this->admin_image_public_path       = url('/').config('app.project.img_path.admin_profile_images');
        $this->admin_image_base_path         = public_path().config('app.project.img_path.admin_profile_images');
        $this->support_image_public_path     = url('/').config('app.project.img_path.support_profile_images');
        $this->support_image_base_path       = public_path().config('app.project.img_path.support_profile_images');

		$this->NotificationModel             = $notification_model;
		$this->UserModel         			 = $user_model;

		$user = $this->auth->user();
      	if($user)
      	{
          	$this->user_id = $user->id;
      	}
	}

	public function index()
    {
    	$arr_notification = $arr_admin = $notify_arr = [];

        $user_type        = Session::get('user_type');

		$obj_notification = $this->NotificationModel->where('receiver_id',$this->user_id)
                                                    ->where('receiver_type',$user_type)
                                                    ->orderBy('id','desc')
                                                    ->paginate(10);
        if($obj_notification)
        {
            $arr_notification = $obj_notification->toArray();
            if(count($arr_notification)>0)
            {
                foreach ($arr_notification['data'] as $key => $value) 
                {
                    if($value['sender_type'] == '1' || $value['sender_type'] == '4')
                    {
                        $notify_arr[$key]['profile_image']  = get_profile_image('UserModel', $value['sender_id'], $this->profile_image_public_path, $this->profile_image_base_path);
                    } 
                    if($value['sender_type'] == '2')
                    {
                        $notify_arr[$key]['profile_image']  = get_profile_image('AdminModel', $value['sender_id'], $this->admin_image_public_path, $this->admin_image_base_path);
                    }
                    if($value['sender_type'] == '3')
                    {
                        $notify_arr[$key]['profile_image']  = get_profile_image('SupportTeamModel', $value['sender_id'], $this->support_image_public_path, $this->support_image_base_path);
                    }

                    $notify_arr[$key]['id']                 = $value['id'];
                    $notify_arr[$key]['sender_id']          = $value['sender_id'];
                    $notify_arr[$key]['receiver_id']        = $value['receiver_id'];
                    $notify_arr[$key]['notification_text']  = $value['notification_text'];
                    $notify_arr[$key]['is_read']            = $value['is_read'];
                    $notify_arr[$key]['sender_type']        = $value['sender_type'];
                    $notify_arr[$key]['receiver_type']      = $value['receiver_type'];
                    $notify_arr[$key]['created_at']         = $value['created_at'];
                    $notify_arr[$key]['url']                = $value['url'];

                }
            }

		}

        $this->NotificationModel->where('receiver_id', $this->user_id)->update(['is_read'=>1]);

        $this->array_view_data['arr_notification']           = $notify_arr;
        $this->array_view_data['obj_pagination']             = $obj_notification;

        $this->array_view_data['module_url_path']            = $this->module_url_path;
		$this->array_view_data['page_title'] 		         = $this->module_title;

    	return view($this->module_view_folder.'.index', $this->array_view_data);
    }

    public function notification_type($type)
    {
    	$arr_json = [];
    	$user_id = $this->user_id;

    	$notification_type = $this->UserModel->where('id',$user_id)->update(['notification_type' => $type]);     
    
        if($notification_type) 
        {
        	$arr_json['status'] = 'success';        	
        }
        else
        {
        	$arr_json['status'] = 'error';
        
        }
 		return response()->json($arr_json);

     }

    public function delete($id)
    {
        ($id)? $id                  = base64_decode($id):NULL;
        $is_delete                  = $this->NotificationModel->where('id',$id)->delete(); 
        if($is_delete)
        {
            Session::flash('success',str_singular($this->module_title).' '.'deleted successfully');
        }
        else
        {
            Session::flash('error','Problem occured while deleting '.str_singular($this->module_title));
        }
        return redirect()->back();
    }

    public function get_notification_count()
    {
        $count = $this->NotificationModel->where(['is_read'=>'0','receiver_id' => $this->user_id, 'receiver_type' => Session::get('user_type')])->count();
        return json_encode($count);
    }
}
