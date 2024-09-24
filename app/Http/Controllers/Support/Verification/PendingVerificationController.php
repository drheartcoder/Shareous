<?php

namespace App\Http\Controllers\Support\Verification;

use Illuminate\Http\Request;
use App\Common\Services\EmailService;
use App\Common\Services\SMSService;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SupportTeamModel;
use App\Models\HostVerificationModel;
use App\Models\UserModel;
use App\Common\Services\NotificationService;
use App\Common\Services\MobileAppNotification;
use Datatables;
use DB;
use Session;

class PendingVerificationController extends Controller
{
	public function __construct(
								SupportTeamModel      $support_team_model,
								HostVerificationModel $host_verification_model,
								MobileAppNotification $mobileappnotification_service,
								UserModel             $user_model,
								EmailService          $email_service,
								SMSService            $sms_service,
								NotificationService   $notification_service
								)
	{
		$this->arr_view_data             = [];
		$this->support_panel_slug        = config('app.project.support_panel_slug');
		$this->support_url_path          = url(config('app.project.support_panel_slug'));
		$this->module_title              = "Verification Requests";
		$this->module_view_folder        = "support.verification_pending";
		$this->module_url_path           = $this->support_url_path."/verification";
		$this->auth                      = auth()->guard('support');
		$this->SupportTeamModel          = $support_team_model;
		$this->HostVerificationModel     = $host_verification_model;
		$this->UserModel                 = $user_model;
		$this->EmailService              = $email_service;
		$this->MobileAppNotification     = $mobileappnotification_service;
		$this->SMSService                = $sms_service;
		$this->support_id                = isset($this->auth->user()->id)? $this->auth->user()->id:0;
		$this->profile_image_public_path = url('/').config('app.project.img_path.user_profile_images');
		$this->profile_image_base_path   = public_path().config('app.project.img_path.user_profile_images');
		$this->id_proof_public_path      = url('/').config('app.project.img_path.user_id_proof');
		$this->id_proof_base_path        = public_path().config('app.project.img_path.user_id_proof');
		$this->photo_public_path         = url('/').config('app.project.img_path.user_photo');		
		$this->photo_base_path           = public_path().config('app.project.img_path.user_photo');
		$this->NotificationService       = $notification_service;
	}

	public function index()
	{
		$arr_data                                  = [];
		$this->arr_view_data['objects']            = $arr_data;
		$this->arr_view_data['module_icon']        = "fa fa-id-card-o";
		$this->arr_view_data['page_title']         = 'Pending '.str_plural($this->module_title);
		$this->arr_view_data['module_title']       = str_plural($this->module_title);
		$this->arr_view_data['module_url_path']    = $this->module_url_path;
		$this->arr_view_data['support_panel_slug'] = $this->support_panel_slug;

		return view($this->module_view_folder.'.index',$this->arr_view_data);
	}

	public function load_data(Request $request)
	{

		$UserData        =  $final_array =[]; 
        $column          = '';
        $request_id      = $request->input('request_id'); 
        $user_name       = $request->input('user_name'); 
        $requested_on    = $request->input('requested_on'); 

        if ($request->input('order')[0]['column'] == 1) 
        {
            $column = "request_id";
        }           
        if ($request->input('order')[0]['column'] == 2) 
        {
            $column = "user_name";
        }     
        if ($request->input('order')[0]['column'] == 3) 
        {
            $column = "created_at";
        } 

        $order                  = strtoupper($request->input('order')[0]['dir']);  
		$arr_data               = [];
		$status                 = '';
		$users_table            = $this->UserModel->getTable();
		$arr_search_column      = $request->input('column_filter');
		$request_table          = $this->HostVerificationModel->getTable();
		$prefixed_users_table   = DB::getTablePrefix().$this->UserModel->getTable();
		$prefixed_request_table = DB::getTablePrefix().$this->HostVerificationModel->getTable();

		$obj_data = DB::table($request_table)
							->select(DB::raw( $prefixed_request_table.".id as id,".
								$prefixed_request_table.".request_id as request_id,".
								$prefixed_request_table.".created_at as created_at,".
								$prefixed_request_table.".status as status,".
								$prefixed_users_table.".user_name as user_name,".
								$prefixed_users_table.".first_name as first_name,".
								$prefixed_users_table.".last_name as last_name"
								))
							->where($prefixed_request_table.'.support_user_id','=',$this->support_id)
							->where($prefixed_request_table.'.status','=','3')
							->Join($users_table,$users_table.".id",' = ',$prefixed_request_table.'.user_id');

	   	if(isset($request_id) && $request_id != "")
		{
			$obj_data = $obj_data->where($prefixed_request_table.'.request_id','LIKE', '%'.$request_id.'%');
		}

		if(isset($user_name) && $user_name != "")
		{
			$obj_data = $obj_data->where($prefixed_users_table.'.user_name','LIKE', '%'.$user_name.'%');
		}

		if(isset($requested_on) && $requested_on != "")
		{
			$obj_data = $obj_data->where($prefixed_request_table.'.created_at','LIKE', '%'.$requested_on.'%');
		}

		 $count        = count($obj_data->get());

        if($order =='ASC' && $column=='')
        {
          $obj_data   = $obj_data->orderBy('id','DESC')->limit($_GET['length'])->offset($_GET['start']);
        }

        if( $order !='' && $column!='' )
        {
          $obj_data   = $obj_data->orderBy($column,$order)->limit($_GET['length'])->offset($_GET['start']);
        }

        $UserData     = $obj_data->get();

        $resp['draw']            = $_GET['draw'];
        $resp['recordsTotal']    = $count;
        $resp['recordsFiltered'] = $count;
       
       	 if(count($UserData)>0)
        {
            $i = 0;
            foreach($UserData as $row)
            {
            	$built_view_href   = $this->module_url_path.'/view/'.base64_encode($row->id);
				$built_accept_href = $this->module_url_path.'/accept/'.base64_encode($row->id);
				$built_reject_href = $this->module_url_path.'/reject/'.base64_encode($row->id);

				$built_view_button = "<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' title='View' href='".$built_view_href."'  data-original-title='View'> <i class='fa fa-eye' ></i> </a>";

				$built_accept_button = "<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' title='Approve' href='javascript:void(0)' onclick='accept(".'"'.base64_encode($row->id).'"'.")'> <i class='fa fa-check-circle' ></i></a>";

				$built_reject_button = "<a class='btn btn-circle btn-to-danger btn-bordered btn-fill show-tooltip' title='Reject' href='javascript:void(0)' data-original-title='Reject' onclick='reject(".'"'.base64_encode($row->id).'"'.")'> <i class='fa fa-times' ></i></a>";

				$action_button = $built_view_button.'	'.$built_accept_button.'	'.$built_reject_button;

				if(isset($row->status) && $row->status==0)
				{
					$status = 'Pending';
				}

            	$final_array[$i][0] = $row->request_id;
                $final_array[$i][1] = $row->user_name;
                $final_array[$i][2] = get_added_on_date_time($row->created_at);
                $final_array[$i][3] = $status;
                $final_array[$i][4] = $action_button;
                $i++;
            }
        }

         $resp['data'] = $final_array;
         echo str_replace("\/", "/",  json_encode($resp));
         exit;	
	}

	public function view($id)
	{
		$arr_data = [];
		$id = base64_decode($id);

		$obj_verification = $this->HostVerificationModel->where('id',$id)
		
		->with(['bank_details'=>function($q1){
			$q1->where('selected',1);
		},'user_details'])
		->first();
		if(isset($obj_verification) && $obj_verification!=null)
		{
			$arr_data = $obj_verification->toArray();
		}

		$this->arr_view_data['arr_user']                  = $arr_data;
		$this->arr_view_data['module_icon']               = "fa fa-id-card-o";
		$this->arr_view_data['page_icon']                 = "fa fa-eye";
		$this->arr_view_data['page_title']                = 'View Pending '.str_singular($this->module_title);
		$this->arr_view_data['previous_page_title']       = 'Pending '.str_plural($this->module_title);
		$this->arr_view_data['module_title']              = str_plural($this->module_title);
		$this->arr_view_data['module_url_path']           = $this->module_url_path;
		$this->arr_view_data['support_panel_slug']        = $this->support_panel_slug;
		$this->arr_view_data['profile_image_public_path'] = $this->profile_image_public_path;
		$this->arr_view_data['profile_image_base_path']   = $this->profile_image_base_path;
		$this->arr_view_data['id_proof_base_path']   	  = $this->id_proof_base_path;
		$this->arr_view_data['id_proof_public_path']   	  = $this->id_proof_public_path;
		$this->arr_view_data['photo_public_path'] 		  = $this->photo_public_path;
		$this->arr_view_data['photo_base_path'] 		  = $this->photo_base_path;

		return view($this->module_view_folder.'.view',$this->arr_view_data);
	}

	public function accept($id)
	{
		
		$id     = base64_decode($id);

		$obj_host = $this->HostVerificationModel->where('id',$id)->with('user_details')->first();
	

		$status = $this->HostVerificationModel->where('id',$id)->update(['status'=>1]);

		if($status)
		{		
			$obj_host = $this->HostVerificationModel->where('id',$id)->with('user_details')->first();
			
			/*Update host status as guest*/
			$this->UserModel->where('id',$obj_host->user_id)->update(['user_type'=>2]);

			$update_obj_user = $this->UserModel->where('id',$obj_host->user_id)->first();

			$type            = get_notification_type_of_user($obj_host['user_id']);

			//$this->NotificationService->account_verification_approvel($obj_host->user_id);

			$arr_built_content  = array(
                                         'USER_NAME'     => isset($obj_host['user_details']['first_name'])?$obj_host['user_details']['first_name']:'NA',
                                         'SITE_NAME'     => config('app.project.name')
                                        );

            $arr_notify_data['arr_built_content']   = $arr_built_content;   
            $arr_notify_data['notify_template_id']  = '1';          
         
            $arr_notify_data['sender_id']           = $obj_host['support_user_id'];
            $arr_notify_data['sender_type']         = '3';
            $arr_notify_data['receiver_id']         = $obj_host['user_id'];
            $arr_notify_data['receiver_type']       = '4';

            $notification_status                    = $this->NotificationService->send_notification($arr_notify_data);
			
			/*Send notification to guest*/
			$arr_notify_guest['arr_built_content']   = $arr_built_content;   
            $arr_notify_guest['notify_template_id']  = '1';          
         
            $arr_notify_guest['sender_id']           = $obj_host['support_user_id'];
            $arr_notify_guest['sender_type']         = '3';
            $arr_notify_guest['receiver_id']         = $obj_host['user_id'];
            $arr_notify_guest['receiver_type']       = '1';

            $notify_guest_status                    = $this->NotificationService->send_notification($arr_notify_guest);

			if(isset($type) && !empty($type))
			{
				// for mail
				if($type['notification_by_email'] == 'on')
				{
			    	$arr_built_content                     = [
	                                    'USER_NAME'        => isset($obj_host['user_details']['display_name'])?ucfirst($obj_host['user_details']['display_name']):'NA',   
	                                    'EMAIL'            =>  isset($obj_host['user_details']['email'])?ucfirst($obj_host['user_details']['email']):'NA',    
	                                    'PROJECT_NAME'     => config('app.project.name')
	                                 ];
		            $arr_mail_data                         = [];
		            $arr_mail_data['email_template_id']    = '5';
		            $arr_mail_data['arr_built_content']    = $arr_built_content;
		            $arr_mail_data['user']                 = ['email' => isset($obj_host['user_details']['email'])?ucfirst($obj_host['user_details']['email']):'NA', 'first_name' =>isset($obj_host['user_details']['display_name'])?ucfirst($obj_host['user_details']['display_name']):'NA'];

		            $this->EmailService->send_mail($arr_mail_data);	
			    }

			    // for sms
				if($type['notification_by_sms'] == 'on')
				{
			        $country_code = isset($obj_host['user_details']['country_code']) ? $obj_host['user_details']['country_code'] : '';
			        $mobile_number = isset($obj_host['user_details']['mobile_number']) ? $obj_host['user_details']['mobile_number'] : '';

			        $arr_sms_data                  = [];
			        $arr_sms_data['msg']           = str_singular($this->module_title).' approved successfully , document verification mail has been sent to user email account.';
			        $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
			        $this->SMSService->send_SMS($arr_sms_data);
			    }

			    // for push notification
				if($type['notification_by_push'] == 'on')
				{
					$headings = str_singular($this->module_title).' approved successfully';
					$content  = str_singular($this->module_title).' approved successfully , document verification mail has been sent to user email account.';
					$user_id  = $obj_host['user_id'];
					$status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
				}

				/*code end*/				
				Session::flash('success',str_singular($this->module_title).' approved successfully , document verification mail has been sent to user email account.');
			}
			else
			{
				Session::flash('success',str_singular($this->module_title).' approved successfully.');
			}
			return redirect($this->module_url_path);
		}
		else
		{			
			Session::flash('error','Problem occurred, while approving '.str_singular($this->module_title));			

			return redirect()->back();
		}

		return redirect()->back();
	}

	public function reject($id)
	{
		
		$id     = base64_decode($id);
		$status = $this->HostVerificationModel->where('id',$id)->update(['status'=>2]);
		$request_details = $this->HostVerificationModel->where('id',$id)->first();
		
		if($status)
		{
			$obj_host = $this->HostVerificationModel->where('id',$id)->with('user_details')->first();
			$type     = get_notification_type_of_user($obj_host['user_id']);


			$arr_built_content  = array(
                                         'USER_NAME'     => isset($obj_host['user_details']['first_name'])?$obj_host['user_details']['first_name']:'NA',
                                         'SITE_NAME'     => config('app.project.name')
                                        );

            $arr_notify_data['arr_built_content']   = $arr_built_content;   
            $arr_notify_data['notify_template_id']  = '3';          
         
            $arr_notify_data['sender_id']           = $obj_host['support_user_id'];
            $arr_notify_data['sender_type']         = '3';
            $arr_notify_data['receiver_id']         = $obj_host['user_id'];
            $arr_notify_data['receiver_type']       = '1';

            $notification_status                    = $this->NotificationService->send_notification($arr_notify_data);


			if(isset($type) && !empty($type))
			{
			    // for mail
				if($type['notification_by_email'] == 'on')
				{
			    	/*email sending code start*/
					$arr_built_content                     = [

	                                    'USER_NAME'        => isset($obj_host['user_details']['display_name'])?ucfirst($obj_host['user_details']['display_name']):'NA',   
	                                    'EMAIL'            => isset($obj_host['user_details']['email'])?ucfirst($obj_host['user_details']['email']):'NA',   
	                                    'PROJECT_NAME'     => config('app.project.name')
	                                 ];
		            $arr_mail_data                         = [];
		            $arr_mail_data['email_template_id']    = '6';
		            $arr_mail_data['arr_built_content']    = $arr_built_content;
		            $arr_mail_data['user']                 = ['email' => isset($obj_host['user_details']['email'])?ucfirst($obj_host['user_details']['email']):'NA', 'first_name' =>isset($obj_host['user_details']['display_name'])?ucfirst($obj_host['user_details']['display_name']):'NA'];

		            $this->EmailService->send_mail($arr_mail_data);	
			    }

			    // for sms
				if($type['notification_by_sms'] == 'on')
				{
			        $country_code = isset($obj_host['user_details']['country_code']) ? $obj_host['user_details']['country_code'] : '';
			        $mobile_number = isset($obj_host['user_details']['mobile_number']) ? $obj_host['user_details']['mobile_number'] : '';

			        $arr_sms_data                  = [];
			        $arr_sms_data['msg']           = 'Support has rejected your document.';
			        $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
			        $this->SMSService->send_SMS($arr_sms_data);
			    }

			    // for push notification
				if($type['notification_by_push'] == 'on')
				{
					$headings = 'Support has rejected your document.';
					$content  = 'Support has rejected your document.';
					$user_id  = $obj_host['user_id'];
					$status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
				}

				/*code end*/				
				Session::flash('success',str_singular($this->module_title).' rejected successfully , document verification mail has been sent to user email account.');
			}
			else
			{
				Session::flash('success',str_singular($this->module_title).' rejected successfully');
			}

			return redirect($this->module_url_path);			
		}
		else
		{
			Session::flash('error','Problem occurred, while rejecting '.str_singular($this->module_title));
			return redirect()->back();
		}

		return redirect()->back();
	}
}
