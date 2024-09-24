<?php

namespace App\Http\Controllers\Support\Ticket;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Common\Services\NotificationService;
use App\Common\Services\MobileAppNotification;
use App\Common\Services\EmailService;
use App\Common\Services\SMSService;
use App\Models\QueryTypeModel;
use App\Models\SupportQueryModel;
use App\Models\SupportQueryCommentModel;
use App\Models\SupportTeamModel;
use Datatables;
use Validator;
use Session;
use Auth;
use Hash;
use Image;


class GenerateTicketController extends Controller
{
    public function __construct(
                                UserModel                $user_model,
                                EmailService             $email_service,
                                QueryTypeModel           $query_type_model,
                                SMSService               $sms_service,
                                NotificationService      $notification_service,
                                MobileAppNotification    $mobileappnotification_service,
                                SupportQueryModel        $support_query_model,
                                SupportQueryCommentModel $support_query_comment_model,
                                SupportTeamModel         $support_team_model
                                )
	{
		$this->arr_view_data             = [];
		$this->UserModel                 = $user_model;
		$this->support_panel_slug        = config('app.project.support_panel_slug');
		$this->support_url_path          = url(config('app.project.support_panel_slug'));
		$this->module_title              = "Generate Tickets";
		$this->module_view_folder        = "support.generate_ticket";
		$this->module_url_path           = $this->support_url_path."/ticket";
		$this->auth                      = auth()->guard('support');		
		$this->support_id                = isset($this->auth->user()->id)? $this->auth->user()->id:0;
		$this->module_icon               = "fa fa-ticket";
		$this->profile_image_public_path = url('/').config('app.project.img_path.user_profile_images');
		$this->profile_image_base_path   = public_path().config('app.project.img_path.user_profile_images');
		$this->query_image_public_path   = url('/').config('app.project.img_path.query_image');
        $this->query_image_base_path     = public_path().config('app.project.img_path.query_image');

        $this->EmailService              = $email_service;
        $this->SupportQueryModel         = $support_query_model;
        $this->SupportQueryCommentModel  = $support_query_comment_model;
        $this->SMSService                = $sms_service;
        $this->NotificationService       = $notification_service;
        $this->MobileAppNotification     = $mobileappnotification_service;
        $this->SupportTeamModel          = $support_team_model;
        $this->QueryTypeModel            = $query_type_model;
	}

    public function generate_ticket()
	{
		$obj_query_type      = $this->QueryTypeModel->where('status',1)->get();

        if(isset($obj_query_type) && $obj_query_type!=null)
        {
            $arr_query_type  = $obj_query_type->toArray(); 
        }

        $obj_user            = $this->UserModel->where('status',1)->orderBy('id','DESC')->get();

        if(isset($obj_user) && $obj_user!=null)
        {
            $arr_user        = $obj_user->toArray(); 
        }

        $this->arr_data['arr_user']              = $arr_user;
        $this->arr_data['arr_query_type']        = $arr_query_type;
        $this->arr_data['module_icon']           = $this->module_icon;
        $this->arr_data['module_title']          = $this->module_title;
        $this->arr_data['page_title']            = $this->module_title;
        $this->arr_data['module_url_path']       = $this->module_url_path;
        $this->arr_data['support_panel_slug']    = $this->support_panel_slug;

        return view($this->module_view_folder.'.generate_ticket',$this->arr_data);
	}

	public function get_user_type(Request $request)
    {
      $user_id   = $request->input('user_id'); 
      $arr_user  = $this->UserModel->where('id',$user_id)->get()->toArray();

      if(count($arr_user))
      {
         foreach ($arr_user as $key => $user) 
         {
                if($user['user_type']  == '4')
                {
                     $html_data ='<div class="form-group"><label class="col-sm-3 col-lg-2 control-label">User Type<i class="red">*</i></label><div class="col-sm-9 col-lg-3 controls"><select class="form-control" name="user_type" id="user_type" data-rule-required="true"><option value="">Select Type</option><option value="1" >Guest</option><option value="4" >Host</option></select><span class="help-block" id="err_user_type"></span></div></div>';  

                      $arr_json['status']       = 'success';
                      $arr_json['html_data']    = $html_data;
                      return response()->json($arr_json);
                }
          }
      }
    }

	public function store_ticket(Request $request)
    {

        $notification_url  =  url('/').'/ticket-listing';

        $arr_rules = $arr_data = $email_data = $notif_data = $arr_comment_data =array();
        $status    = FALSE;
        $arr_rules['user_id']              = "required";      
        $arr_rules['query_type_id']        = "required";
        $arr_rules['subject']              = "required";
        $arr_rules['query_description']    = "required";
        $arr_rules['attachment_file_name'] = "required";
           
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
           return redirect()->back()->withErrors($validator);
        }

         if($request->hasFile('attachment_file_name'))
        {
            $original_name  = strtolower($request->file('attachment_file_name')->getClientOriginalName());

            $file_extension = strtolower($request->file('attachment_file_name')->getClientOriginalExtension());

            if(in_array($file_extension,['png','jpg','jpeg']))
            {
                $file     = $request->file('attachment_file_name');
                $filename = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
                $path     = $this->query_image_base_path . $filename;
                $isUpload = Image::make($file->getRealPath())->resize(168,168)->save($path);     
            }
            elseif(in_array($file_extension,['pdf','doc','docx','txt']))
            {               
                $file     = $request->file('attachment_file_name');
                $filename = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
                $path     = $this->query_image_base_path . $filename;
                $isUpload = $request->file('attachment_file_name')->move($this->query_image_base_path , $filename);
            }
            else
            {
                Session::flash('error','Invalid File type, While creating '.str_singular($this->module_title));
                return redirect()->back();
            }
        }

        if($request->input('user_type') !='')
        {
            $arr_data['user_type']    = $request->input('user_type');
        }
        else
        { 
            $arr_data['user_type']   = "1";
        }

        $user_id = $request->input('user_id');
         
        $arr_data['query_type_id']           = $request->input('query_type_id');
        $arr_data['query_subject']           = trim($request->input('subject'));
        $arr_data['query_description']       = trim($request->input('query_description'));
        $arr_data['attachment_file_name']    = $original_name;
        $arr_data['attachment_file']         = $filename;
        $arr_data['user_id']                 = $user_id;
        $arr_data['support_level']           = 'L3';

        $status                              = $this->SupportQueryModel->create($arr_data);

        $arr_comment_data['query_id']        = $status->id;
        $arr_comment_data['comment_by']      = $user_id;
        $arr_comment_data['user_id']         = $user_id;
        $arr_comment_data['support_user_id'] = 0; 
        $arr_comment_data['comment']         = isset($arr_data['query_description'])?$arr_data['query_description']:'';
        $this->SupportQueryCommentModel->create($arr_comment_data);

        $obj_guest                           = $this->UserModel->where('id',$user_id)->first();

        $obj_support_team                    = $this->SupportTeamModel->where('support_level','L3')->get();

        $arr_built_content  = array(
                                     'USER_NAME'     => isset($obj_guest->first_name)?$obj_guest->first_name:'NA',
                                     'SUBJECT'       => trim($request->input('query_subject'))
                                    );
        $arr_notify_data['arr_built_content']   = $arr_built_content;   
        $arr_notify_data['notify_template_id']  = '4';   
        $arr_notify_data['sender_id']           = $user_id;
        $arr_notify_data['sender_type']         = $arr_data['user_type'];
        $arr_notify_data['receiver_type']       = '3';
        $arr_notify_data['url']                 = $notification_url;

        if(count($obj_support_team)>0)
        {
            foreach($obj_support_team as $row)
            {
                $arr_notify_data['receiver_id']         = $row->id;
                $notification_status                    = $this->NotificationService->send_notification($arr_notify_data);
            }
        }
        if(isset($obj_guest) && $obj_guest!=null)
        {
            // for email
            if($obj_guest->notification_by_email == "on")
            {
        
                $arr_built_content           = [
                                    'USER_NAME'        => isset($obj_guest->first_name)?ucfirst($obj_guest->first_name):'NA',   
                                    'Email'            => isset($obj_guest->email)?$obj_guest->email:'NA',   
                                    'TICKET_ID'        => isset($status->id)?$status->id:'NA',  
                                    'QUERY_SUBJECT'    => isset($status->query_subject)?$status->query_subject:'NA',  
                                    'PROJECT_NAME'     => config('app.project.name')
                                 ];
                                 
                $arr_mail_data                         = [];
                $arr_mail_data['email_template_id']    = '7';
                $arr_mail_data['arr_built_content']    = $arr_built_content;
                $arr_mail_data['user']                 = ['email' => isset($obj_guest->email)?$obj_guest->email:'NA', 'first_name' => isset($obj_guest->first_name)?ucfirst($obj_guest->first_name):'NA'];
                
                $status                                = $this->EmailService->send_mail($arr_mail_data);
            }
            
            // for sms
            if($obj_guest->notification_by_sms == "on")
            {
                $country_code  = isset($obj_guest->country_code) ? $obj_guest->country_code : '';
                $mobile_number = isset($obj_guest->mobile_number) ? $obj_guest->mobile_number : '';

                $arr_sms_data                  = [];
                $arr_sms_data['msg']           = 'Ticket id is sent on your email account successfully.';
                $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                $status                        = $this->SMSService->send_SMS($arr_sms_data);
            }

            // for push notification
            if($obj_guest->notification_by_push == "on")
            {
                $headings = 'Ticket Submit';
                $content  = 'Ticket id is sent on your email account successfully.';
                $user_id  = $user_id;
                $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
            }
        }
        
        if($status)
        {
            Session::flash('success','Ticket id is sent on your email account successfully.');
        }
        else
        {
            Session::flash('error','Problem Occurred, While Updating '.str_singular($this->module_title));
        }     
        return redirect()->back();

    }


	

}
