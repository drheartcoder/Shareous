<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Common\Services\NotificationService;
use App\Common\Services\MobileAppNotification;
use App\Common\Services\EmailService;
use App\Common\Services\SMSService;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SupportQueryCommentModel;
use App\Models\SupportQueryModel;
use App\Models\SupportTeamModel;
use App\Models\QueryTypeModel;
use App\Models\UserModel;
use Validator;
use Session;
use Image;

class TicketController extends Controller
{
    public function __construct(
                                EmailService             $email_service,
                                SMSService               $sms_service,
                                NotificationService      $notification_service,
                                MobileAppNotification    $mobileappnotification_service,
    							SupportQueryModel        $support_query_model,
    							QueryTypeModel           $query_type_model,
    						    UserModel                $user_model,
                                SupportTeamModel         $support_team_model,
                                SupportQueryCommentModel $support_query_comment_model)
	{
		$this->array_view_data         = [];
		$this->module_title            = 'Ticket';
		$this->module_view_folder      = 'front.ticket';
		$this->module_url_path         = url('/ticket');
		$this->SupportQueryModel 	   = $support_query_model;
		$this->BaseModel               = $support_query_model;
        $this->SupportQueryCommentModel= $support_query_comment_model;
		$this->QueryTypeModel          = $query_type_model;
		$this->UserModel               = $user_model;
        $this->SupportTeamModel        = $support_team_model;
		$this->EmailService            = $email_service;
        $this->MobileAppNotification   = $mobileappnotification_service;
        $this->SMSService              = $sms_service;
        $this->NotificationService     = $notification_service;
		$this->query_image_public_path = url('/').config('app.project.img_path.query_image');
		$this->query_image_base_path   = public_path().config('app.project.img_path.query_image');
		$this->auth               	   = auth()->guard('users'); 
        
		$user = $this->auth->user();
        
      	if($user)
      	{
          	$this->user_id = $user->id;
      	}
	}

	public function index()
	{
		$arr_query_type = [];

		$obj_query_type = $this->QueryTypeModel->where('status',1)->get();
		if(isset($obj_query_type) && $obj_query_type!=null)
		{
            $arr_query_type = $obj_query_type->toArray();
		}

        $this->arr_view_data['arr_query_type']  = $arr_query_type;
        $this->arr_view_data['module_url_path'] = $this->module_url_path;
        $this->arr_view_data['page_title']      = 'Generate '.$this->module_title;
		return view($this->module_view_folder.'.generate_ticket',$this->arr_view_data);
	}

	public function ticket_store(Request $request)
	{
        $url              = url()->previous();
        $notification_url = str_replace(url('/'), "", $url);
      
		$arr_rules = $arr_data = $email_data = $notif_data = $arr_comment_data =array();
        $status    = FALSE;

        $arr_rules['query_type_id']        = "required";
        $arr_rules['query_subject']        = "required";
        $arr_rules['query_description']    = "required";
        //$arr_rules['attachment_file_name'] = "required";
           
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if($request->hasFile('attachment_file_name')) {
        	$original_name  = strtolower($request->file('attachment_file_name')->getClientOriginalName());
            $file_extension = strtolower($request->file('attachment_file_name')->getClientOriginalExtension());

            if(in_array($file_extension,['png','jpg','jpeg'])) {
                $file     = $request->file('attachment_file_name');
                $filename = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
                $path     = $this->query_image_base_path . $filename;
                $isUpload = Image::make($file->getRealPath())->resize(168,168)->save($path);

            } elseif(in_array($file_extension,['pdf','doc','docx','txt','zip'])) {
                $file     = $request->file('attachment_file_name');
                $filename = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
                $path     = $this->query_image_base_path . $filename;
                $isUpload = $request->file('attachment_file_name')->move($this->query_image_base_path , $filename);

            } else {
                Session::flash('error','Invalid File type, While creating '.str_singular($this->module_title));
                return redirect()->back();

            }

            $arr_data['attachment_file_name'] = $original_name;
            $arr_data['attachment_file']      = $filename;
        }

        $arr_data['query_type_id']     = $request->input('query_type_id');
        $arr_data['query_subject']     = trim($request->input('query_subject'));
        $arr_data['query_description'] = trim($request->input('query_description'));
        $arr_data['user_id']           = $this->user_id;
        $arr_data['support_level']     = 'L3';
        $arr_data['user_type']         = Session::get('user_type');

        $status = $this->SupportQueryModel->create($arr_data);

        /*$arr_comment_data['query_id']        = $status->id;
        $arr_comment_data['comment_by']      = $this->user_id;
        $arr_comment_data['user_id']         = $this->user_id;
        $arr_comment_data['support_user_id'] = 0;
        $arr_comment_data['comment']         = (isset($arr_data['query_description'])) ? $arr_data['query_description'] : '';
        $this->SupportQueryCommentModel->create($arr_comment_data);*/

        $obj_guest = $this->UserModel->where('id',$this->user_id)->first();

        $arr_built_content = array(
                                    'USER_NAME' => $obj_guest->first_name,
                                    'SUBJECT'   => trim($request->input('query_subject'))
                                );

        $arr_notify_data['arr_built_content']  = $arr_built_content;
        $arr_notify_data['notify_template_id'] = '4';
        $arr_notify_data['sender_id']          = $this->user_id;
        $arr_notify_data['sender_type']        = Session::get('user_type');
        $arr_notify_data['receiver_type']      = '3';
        $arr_notify_data['url']                = $notification_url;

        $obj_support_team = $this->SupportTeamModel->where('support_level','L3')->get();
        if (count($obj_support_team) > 0) {
            foreach($obj_support_team as $row) {
                $arr_notify_data['receiver_id'] = $row->id;
                $this->NotificationService->send_notification($arr_notify_data);
            }
        }

        if (isset($obj_guest) && $obj_guest!=null) {
            // for email
            if ($obj_guest->notification_by_email == "on") {
                $arr_built_content = [
                                        'USER_NAME'     => ucfirst($obj_guest->first_name),
                                        'Email'         => $obj_guest->email,
                                        'TICKET_ID'     => $status->id,
                                        'QUERY_SUBJECT' => $status->query_subject,
                                        'PROJECT_NAME'  => config('app.project.name')
                                    ];
                                 
                $arr_mail_data                      = [];
                $arr_mail_data['email_template_id'] = '7';
                $arr_mail_data['arr_built_content'] = $arr_built_content;
                $arr_mail_data['user']              = [
                                                        'email'      => $obj_guest->email,
                                                        'first_name' => ucfirst($obj_guest->first_name)
                                                    ];
                $status = $this->EmailService->send_mail($arr_mail_data);
			}

            // for sms
            if ($obj_guest->notification_by_sms == "on") {
                
                $country_code  = isset($obj_guest->country_code) ? $obj_guest->country_code : '';
                $mobile_number = isset($obj_guest->mobile_number) ? $obj_guest->mobile_number : '';

                $arr_sms_data                  = [];
                $arr_sms_data['msg']           = str_singular($this->module_title).' id is sent on your email account successfully.';
                $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                $status = $this->SMSService->send_SMS($arr_sms_data);
            }

            // for push notification
            if ($obj_guest->notification_by_push == "on") {
                $headings = str_singular($this->module_title).' id is sent on your email account successfully';
                $content  = str_singular($this->module_title).' id is sent on your email account successfully';
                $user_id  = $this->user_id;
                $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
            }
        }

        if ($status) {
            Session::flash('success',str_singular($this->module_title).' id is sent on your email account successfully');
        } else {
            Session::flash('error','Problem occurred while updating '.str_singular($this->module_title));
        }     
        return redirect()->back(); 
	}

}
