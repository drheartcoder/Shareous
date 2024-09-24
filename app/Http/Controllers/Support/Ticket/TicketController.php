<?php

namespace App\Http\Controllers\Support\Ticket;

use Illuminate\Http\Request;
use App\Common\Services\NotificationService;
use App\Common\Services\EmailService;
use App\Common\Services\SMSService;
use App\Common\Services\MobileAppNotification;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\SupportQueryModel;
use App\Models\SupportQueryCommentModel;
use App\Models\SupportTeamModel;
use App\Models\SupportLogModel;
use App\Models\UserModel;
use App\Models\BookingModel;
use App\Models\TransactionModel;
use App\Models\AdminModel;

use DB;
use Session;
use Datatables;
use Validator;
use Mail;
use TCPDF;
use PDF;

use Razorpay\Api\Api;
use Razorpay;

class TicketController extends Controller
{
	public function __construct(
									EmailService             $email_service,
									SMSService               $sms_service,
									NotificationService      $notification_service,
									MobileAppNotification    $mobile_app_notify,
									UserModel                $user_model,
									SupportQueryModel        $support_query_model,
									SupportLogModel          $support_log_model,
									SupportQueryCommentModel $support_query_comment_model,
									SupportTeamModel         $support_team_model,
									BookingModel             $booking_model,
									TransactionModel         $transaction_model,
									AdminModel               $admin_model
								)
	{
		$this->arr_view_data             = [];
		$this->support_panel_slug        = config('app.project.support_panel_slug');
		$this->support_url_path          = url(config('app.project.support_panel_slug'));
		$this->module_title              = "Tickets";
		$this->module_view_folder        = "support.ticket";
		$this->UserModel                 = $user_model;
		$this->SupportQueryModel         = $support_query_model;
		$this->SupportLogModel           = $support_log_model;
		$this->SupportQueryCommentModel  = $support_query_comment_model;
		$this->SupportTeamModel          = $support_team_model;
		$this->EmailService              = $email_service;
		$this->SMSService                = $sms_service;
		$this->NotificationService       = $notification_service;
		$this->MobileAppNotification     = $mobile_app_notify;
		$this->BookingModel              = $booking_model;
		$this->TransactionModel          = $transaction_model;
		$this->AdminModel                = $admin_model;
		$this->module_url_path           = $this->support_url_path."/ticket";
		
		$this->module_icon               = "fa fa-ticket";
		$this->profile_image_public_path = url('/').config('app.project.img_path.user_profile_images');
		$this->profile_image_base_path   = public_path().config('app.project.img_path.user_profile_images');

		$this->query_image_public_path   = url('/').config('app.project.img_path.query_image');
		$this->query_image_base_path     = public_path().config('app.project.img_path.query_image');

		$razorpay_credential             = get_razorpay_credential();
		$this->api                       = new Api($razorpay_credential['razorpay_id'], $razorpay_credential['razorpay_secret']);

		$this->auth                      = auth()->guard('support');
		$support                         = $this->auth->user();
		if($support) {
            $this->support_id            = $support->id;
            $this->support_first_name    = $support->first_name;
            $this->support_last_name     = $support->last_name;
            $this->support_user_name     = $support->user_name;
            $this->support_display_name  = $support->display_name;
            $this->support_profile_image = $support->profile_image;
        }
        else {
        	$this->support_id            = 0;
        	$this->support_first_name    = '';
        	$this->support_last_name     = '';
        	$this->support_user_name     = '';
        	$this->support_display_name  = '';
        	$this->support_profile_image = '';
        }

        $this->user_profile_image_public_path    = url('/').config('app.project.img_path.user_profile_images');
        $this->user_profile_image_base_path      = public_path().config('app.project.img_path.user_profile_images');
        $this->support_profile_image_public_path = url('/').config('app.project.img_path.support_profile_images');
        $this->support_profile_image_base_path   = public_path().config('app.project.img_path.support_profile_images');
	}

	public function index()
	{
		$arr_data = [];
		$this->arr_view_data['objects']            = $arr_data;
		$this->arr_view_data['module_icon']        = "fa fa-ticket";
		$this->arr_view_data['page_title']         = 'Manage Assign '.str_plural($this->module_title);
		$this->arr_view_data['module_title']       = str_plural($this->module_title);
		$this->arr_view_data['module_url_path']    = $this->module_url_path;
		$this->arr_view_data['support_panel_slug'] = $this->support_panel_slug;

		return view($this->module_view_folder.'.index',$this->arr_view_data);
	}

	public function load_data(Request $request)
	{	
	    $UserData     = $final_array = $arr_data = [];
	    $column       = '';
	    $ticket_id    = $request->input('ticket_id');
	    $user_name    = $request->input('user_name');
	    $generated_on = $request->input('generated_on');

        if ($request->input('order')[0]['column'] == 1) {
            $column = "id";
        }
        if ($request->input('order')[0]['column'] == 2) {
            $column = "user_name";
        }
        if ($request->input('order')[0]['column'] == 3) {
            $column = "created_at";
        }

		$order                = strtoupper($request->input('order')[0]['dir']);
		$users_table          = $this->UserModel->getTable();
		$arr_search_column    = $request->input('column_filter');
		$support_query_table  = $this->SupportQueryModel->getTable();
		$prefixed_users_table = DB::getTablePrefix().$this->UserModel->getTable();
		$prefixed_query_table = DB::getTablePrefix().$this->SupportQueryModel->getTable();

		$support_details      = $this->SupportTeamModel->where('id',$this->support_id)->first(['id','support_level']);

		$obj_data = DB::table($support_query_table)
					->select(DB::raw( $prefixed_query_table.".id as id,".
						$prefixed_query_table.".created_at as created_at,".
						$prefixed_query_table.".status as status,".
						$prefixed_query_table.".query_subject,".
						$prefixed_users_table.".user_name as user_name,".
						$prefixed_users_table.".first_name as first_name"/*.
						$prefixed_users_table.".last_name as last_name,".
						$prefixed_users_table.".last_name as last_name,".
						$prefixed_users_table.".last_name as last_name,"*/
					))
					->where($prefixed_query_table.'.support_user_id',$this->support_id)
					->where($prefixed_query_table.'.status','=',2)
					->where($prefixed_query_table.'.support_level','=',$this->auth->user()->support_level)
					->Join($users_table,$users_table.".id",' = ',$prefixed_query_table.'.user_id');

	   	if(isset($ticket_id) && $ticket_id != "") {
			$obj_data = $obj_data->where($prefixed_query_table.'.id','LIKE', '%'.$ticket_id.'%');
		}

		if(isset($user_name) && $user_name != ""){
			$obj_data = $obj_data->where($prefixed_users_table.'.user_name','LIKE', '%'.$user_name.'%');
		}

		if(isset($generated_on) && $generated_on != ""){
			$obj_data = $obj_data->where($prefixed_query_table.'.created_at','LIKE', '%'.$generated_on.'%');
		}

        $count = count($obj_data->get());

        if($order == 'ASC' && $column == '') {
        	$obj_data = $obj_data->orderBy('id','DESC')->limit($_GET['length'])->offset($_GET['start']);
        }

        if( $order != '' && $column != '' ){
			$obj_data = $obj_data->orderBy($column,$order)->limit($_GET['length'])->offset($_GET['start']);
        }

        $UserData = $obj_data->get();
        $resp['draw']            = $_GET['draw'];
        $resp['recordsTotal']    = $count;
        $resp['recordsFiltered'] = $count;
       
        if(count($UserData)>0) {
            $i = 0;
            foreach($UserData as $row) {

            	$built_view_href         = $this->module_url_path.'/view/'.base64_encode($row->id);
				$built_change_level_href = $this->module_url_path.'/change_level/'.base64_encode($row->id);

				$build_view_action = '';
				$built_view_button = "<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' title='View' href='".$built_view_href."'  data-original-title='View'> <i class='fa fa-eye' ></i> </a>";

            	if(isset($support_details) && $support_details != null) {
					if($support_details->support_level == "L3" || $support_details->support_level == "L2") {
						$built_change_level_button = "<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' title='Change Level' href='javascript:void(0)' data-original-title='Change Level' onclick='change_level(".'"'.base64_encode($row->id).'"'.")'> <i class='fa fa-paper-plane' ></i></a>";
				
						$action_button = $built_view_button.'	'.$built_change_level_button;
					}
					else {
						$action_button = $built_view_button;
					}
				}
            	if(isset($row->status) && $row->status == 2) {
					$status = 'Assign';
				}
				
            	$final_array[$i][0] = $row->id;
                $final_array[$i][1] = $row->user_name;
                $final_array[$i][2] = $row->created_at;
                $final_array[$i][3] = $row->query_subject;
                $final_array[$i][4] = $status;
                $final_array[$i][5] = $action_button;
                $i++;
            }
        }

         $resp['data'] = $final_array;
         echo str_replace("\/", "/",  json_encode($resp));exit;	
	}

	public function view($id)
	{
		$booking_id = '';
		$arr_data = [];
		
		$id = base64_decode($id);
		$obj_query = $this->SupportQueryModel->where('id', $id)
											 ->with('user_details', 'query_type_details', 'booking_details.transaction_details', 'booking_details.property_details', 'booking_details.booking_by_user_details', 'booking_details.property_owner')
											 ->first();

		if( isset($obj_query) && $obj_query != null ) {
			$arr_data = $obj_query->toArray();
		}

		$support_details = $this->SupportTeamModel->where('id',$this->support_id)->first(['id','support_level']);
		if(isset($support_details) && $support_details != null && $support_details->support_level != "") {
			$support_level = $support_details->support_level;
		}
		
		$this->arr_view_data['arr_user']                  = $arr_data;
		$this->arr_view_data['support_level']             = $support_level;
		$this->arr_view_data['module_icon']               = $this->module_icon;
		$this->arr_view_data['page_icon']                 = "fa fa-eye";
		$this->arr_view_data['page_title']                = 'View Assign '.$this->module_title;
		$this->arr_view_data['module_title']              = str_plural($this->module_title);
		$this->arr_view_data['module_url_path']           = $this->module_url_path;
		$this->arr_view_data['support_panel_slug']        = $this->support_panel_slug;
		$this->arr_view_data['profile_image_public_path'] = $this->profile_image_public_path;
		$this->arr_view_data['profile_image_base_path']   = $this->profile_image_base_path;
		$this->arr_view_data['query_image_public_path']   = $this->query_image_public_path;
		$this->arr_view_data['query_image_base_path']     = $this->query_image_base_path;
		$this->arr_view_data['previous_page_url']         = $this->support_url_path."/ticket";
		$this->arr_view_data['previous_page_title']       = "Manage Assign Tickets";
		
		return view($this->module_view_folder.'.view',$this->arr_view_data);
	}

	public function send(Request $request,$enc_id)
	{
		$arr_data = $arr_rules = $arr_comment_data = $arr_query = [];
		$id = base64_decode($enc_id);

		$arr_rules = array(
			'email_id' => 'required',
			'message'  => 'required',
		);

		$msg = array( 'required' => 'Please enter :attribute' );
		$validator = $validator = Validator::make($request->all(), $arr_rules, $msg);
		if($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput($request->all());
		}

		$user_email = $request->input('email_id');
		$reply      = $request->input('message');

		$obj_query = $this->SupportQueryModel->where('id',$id)->with('user_details')->first();
		if(isset($obj_query) && $obj_query!=null)
		{
			$arr_query = $obj_query->toArray();

			if(isset($arr_query) && sizeof($arr_query)>0 && is_array($arr_query))
			{
			
				$arr_built_content = array(
											'USER_NAME'     => isset($arr_query['user_details']['user_name']) ? $arr_query['user_details']['user_name'] : 'NA',
											'TICKET_ID'     => $arr_query['id'],
											'QUERY_SUBJECT' => $arr_query['query_subject'],
											'SITE_NAME'     => config('app.project.name')
										);

	            $arr_notify_data['arr_built_content']  = $arr_built_content;
	            $arr_notify_data['notify_template_id'] = '7';
	            $arr_notify_data['sender_id']          = $arr_query['support_user_id'];
	            $arr_notify_data['sender_type']        = '3';
	            $arr_notify_data['receiver_id']        = $arr_query['user_id'];
	            $arr_notify_data['receiver_type']      = $arr_query['user_type'];
	            $arr_notify_data['url']                = "/ticket-listing";
	            $notification_status = $this->NotificationService->send_notification($arr_notify_data);


				$type = get_notification_type_of_user($arr_query['user_id']);
				if(isset($type) && !empty($type)) {
					
					// for mail
					if($type['notification_by_email'] == 'on') {
    					$arr_built_content = [
							'USER_NAME'     => isset($arr_query['user_details']['user_name']) ? $arr_query['user_details']['user_name'] : 'NA',
							'EMAIL'         => isset($user_email) ? $user_email : 'NA',
							'TICKET_ID'     => isset($arr_query['id']) ? $arr_query['id'] : 'NA',
							'QUERY_SUBJECT' => isset($arr_query['query_subject']) ? $arr_query['query_subject'] : 'NA',
							'QUERY_REPLY'   => isset($reply) ? $reply : 'NA',
							'SITE_NAME'     => config('app.project.name')
						];

			            $arr_mail_data                      = [];
			            $arr_mail_data['email_template_id'] = '11';
			            $arr_mail_data['arr_built_content'] = $arr_built_content;
			            $arr_mail_data['user']              = [
			            	'email'      => isset($user_email) ? $user_email : 'NA',
			            	'first_name' => isset($arr_query['user_details']['user_name']) ? $arr_query['user_details']['user_name'] : 'NA'
			            ];

			            $this->EmailService->send_mail($arr_mail_data);		
    				}

    				// for sms
					if($type['notification_by_sms'] == 'on') {
				        $country_code = isset($arr_query['user_details']['country_code']) ? $arr_query['user_details']['country_code'] : '';
				        $mobile_number = isset($arr_query['user_details']['mobile_number']) ? $arr_query['user_details']['mobile_number'] : '';

				        $arr_sms_data                  = [];
				        $arr_sms_data['msg']           = "Support has replied on your ticket.";
				        $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
				        $this->SMSService->send_SMS($arr_sms_data);
				    }

				    // for push notification
					if($type['notification_by_push'] == 'on') {
						$headings = "Support has replied on your ticket.";
						$content  = "Support has replied on your ticket.";
						$user_id  = $arr_query['user_id'];
						$status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
					}

					/*code end*/
					return redirect()->back()->with('success','Reply send on user email account successfully');
				}
				else {
					return redirect()->back()->with('error','Problem occured while sending reply');
				}
			}
			else {
				return redirect()->back()->with('error','Problem occured while sending reply');
			}
		}
		else {
			return redirect()->back()->with('error','Problem occured while sending reply');		
		}
		return redirect()->back();
	}

	public function reject(Request $request,$enc_id)
	{
		$arr_data = $arr_rules = $arr_comment_data = $arr_query = [];
		$id = base64_decode($enc_id);

		$arr_rules = array(
			'email_id' => 'required',
			'message'  => 'required',
		);

		$msg = array( 'required' => 'Please enter :attribute' );
		$validator = $validator = Validator::make($request->all(), $arr_rules, $msg);
		if($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput($request->all());
		}

		$user_email = $request->input('email_id');
		$reply      = $request->input('message');

		$obj_query  = $this->SupportQueryModel->where('id', $id)->with('user_details')->first();
		if(isset($obj_query) && $obj_query != null) {
			$arr_query = $obj_query->toArray();

			$this->BookingModel->where('id', $arr_query['booking_id'])->update(['booking_status' => '5']);

			if(isset($arr_query) && sizeof($arr_query)>0 && is_array($arr_query)) {
				$arr_built_content = array(
										'USER_NAME'     => isset($arr_query['user_details']['user_name']) ? $arr_query['user_details']['user_name'] : 'NA',
										'TICKET_ID'     => $arr_query['id'],
										'QUERY_SUBJECT' => $arr_query['query_subject'],
										'SITE_NAME'     => config('app.project.name')
									);

	            $arr_notify_data['arr_built_content']  = $arr_built_content;
	            $arr_notify_data['notify_template_id'] = '7';
	            $arr_notify_data['sender_id']          = $arr_query['support_user_id'];
	            $arr_notify_data['sender_type']        = '3';
	            $arr_notify_data['receiver_id']        = $arr_query['user_id'];
	            $arr_notify_data['receiver_type']      = $arr_query['user_type'];
	            $arr_notify_data['url']                = "/ticket-listing";
	            $notification_status = $this->NotificationService->send_notification($arr_notify_data);

				$type = get_notification_type_of_user($arr_query['user_id']);
				if(isset($type) && !empty($type)) {
					// for mail
					if($type['notification_by_email'] == 'on') {
    					$arr_built_content = [
							'USER_NAME'     => isset($arr_query['user_details']['user_name']) ? $arr_query['user_details']['user_name'] : 'NA',
							'EMAIL'         => isset($user_email) ? $user_email : 'NA',
							'TICKET_ID'     => isset($arr_query['id']) ? $arr_query['id'] : 'NA',
							'QUERY_SUBJECT' => isset($arr_query['query_subject']) ? $arr_query['query_subject'] : 'NA',
							'QUERY_REPLY'   => isset($reply) ? $reply : 'NA',
							'SITE_NAME'     => config('app.project.name')
						];

			            $arr_mail_data                      = [];
			            $arr_mail_data['email_template_id'] = '11';
			            $arr_mail_data['arr_built_content'] = $arr_built_content;
			            $arr_mail_data['user']              = [
												            	'email'      => isset($user_email) ? $user_email : 'NA',
												            	'first_name' => isset($arr_query['user_details']['user_name']) ? $arr_query['user_details']['user_name'] : 'NA'
												            ];

			            $this->EmailService->send_mail($arr_mail_data);		
    				}

    				// for sms
					if($type['notification_by_sms'] == 'on') {
				        $country_code = isset($arr_query['user_details']['country_code']) ? $arr_query['user_details']['country_code'] : '';
				        $mobile_number = isset($arr_query['user_details']['mobile_number']) ? $arr_query['user_details']['mobile_number'] : '';

				        $arr_sms_data                  = [];
				        $arr_sms_data['msg']           = "Support has replied on your ticket.";
				        $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
				        $this->SMSService->send_SMS($arr_sms_data);
				    }

				    // for push notification
					if($type['notification_by_push'] == 'on') {
						$headings = "Support has replied on your ticket.";
						$content  = "Support has replied on your ticket.";
						$user_id  = $arr_query['user_id'];
						$status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
					}

					// close the ticket
					$this->close_ticket($enc_id);

					/*code end*/
					//return redirect()->back()->with('success','Reply send on user email account successfully');
					return redirect($this->module_url_path.'/closed_ticket')->with('success','Ticket closed successfully and email sent to user account');
				}
				else {
					//return redirect()->back()->with('error','Problem occured while sending reply');
					return redirect($this->module_url_path.'/closed_ticket')->with('success','Ticket closed successfully.');
				}
			}
			else {
				//return redirect()->back()->with('error','Problem occured while sending reply');
				return redirect()->back()->with('error','Problem occured while closing ticket');
			}
		}
		else {
			return redirect()->back()->with('error','Problem occured while closing ticket');		
		}
		return redirect()->back();
	}

	public function close_ticket($id)
	{
		$arr_data = $arr_user = [];

		$id 	  = base64_decode($id);
		$status   = $this->SupportQueryModel->where('id', $id)->update(['status' => 3]); /*3 - closed*/

		if($status)        
		{
			$booking_id = 0;
			$obj_user = $this->SupportQueryModel->where('id',$id)->with('user_details')->first();
			if(isset($obj_user) && $obj_user != null)
			{
				$arr_user = $obj_user->toArray(); 
				$booking_id = isset($arr_user['booking_id']) ? $arr_user['booking_id'] : 0;
			}
			
			$type = get_notification_type_of_user($arr_user['user_id']);

			$arr_built_content = array(
	            'USER_NAME'     => isset($arr_user['user_details']['user_name']) ? $arr_user['user_details']['user_name'] : 'NA',
	            'TICKET_ID'     => $arr_user['id'],
	            'QUERY_SUBJECT' => $arr_user['query_subject'],
	            'SITE_NAME'     => config('app.project.name')
			);

	        $arr_notify_data['arr_built_content']   = $arr_built_content;
	        $arr_notify_data['notify_template_id']  = '5';
	        $arr_notify_data['sender_id']           = $arr_user['support_user_id'];
	        $arr_notify_data['sender_type']         = '3';
	        $arr_notify_data['receiver_id']         = $arr_user['user_id'];
	        $arr_notify_data['receiver_type']       = $arr_user['user_type'];
	        $arr_notify_data['url']                 = '/ticket-listing';
	        $notification_status = $this->NotificationService->send_notification($arr_notify_data);

			//$this->NotificationService->support_send_token_closed($arr_user['user_id'],$arr_user['id'],$arr_user['query_subject']);

	        /*change booking status if booking id is not 0*/
	        if(isset($booking_id) && $booking_id!=0)
	        {
	        	$this->BookingModel->where('id',$booking_id)->update(['booking_status'=>'6']);
	        	
	        }
			if(isset($type) && !empty($type))
			{
				// for mail
				if($type['notification_by_email'] == 'on')
				{
					$arr_data['arr_built_content']    = [
						'USER_NAME'     => isset($arr_user['user_details']['user_name']) ? ucfirst($arr_user['user_details']['user_name']) : 'NA',
						'Email'         => isset($arr_user['user_details']['email']) ? ucfirst($arr_user['user_details']['email']) : 'NA',
						'TICKET_ID'     => isset($arr_user['id']) ? $arr_user['id'] : 'NA',
						'QUERY_SUBJECT' => isset($arr_user['query_subject']) ? $arr_user['query_subject'] : 'NA',
						'PROJECT_NAME'  => config('app.project.name')
					];

					$arr_data['email_to']          = isset($arr_user['user_details']['email']) ? ucfirst($arr_user['user_details']['email']) : 'NA' ;
					$arr_data['user_name']         = isset($arr_user['user_details']['user_name']) ? ucfirst($arr_user['user_details']['user_name']) : 'NA';
					$arr_data['ticket_id']         = isset($arr_user['id']) ? $arr_user['id'] : 'NA';
					$arr_data['query_subject']     = isset($arr_user['query_subject']) ? $arr_user['query_subject'] : 'NA';
					$arr_data['email_template_id'] = 8;
						
					$this->EmailService->support_send_close_ticket_mail($arr_data);
				}

				// for sms
				if($type['notification_by_sms'] == 'on')
				{
			        $country_code = isset($arr_user['user_details']['country_code']) ? $arr_user['user_details']['country_code'] : '';
			        $mobile_number = isset($arr_user['user_details']['mobile_number']) ? $arr_user['user_details']['mobile_number'] : '';
			        $arr_sms_data                  = [];
			        $arr_sms_data['msg']           = "Support has closed your ticket.";
			        $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
			        $this->SMSService->send_SMS($arr_sms_data);
			    }

			    // for push notification
				if($type['notification_by_push'] == 'on')
				{
					$headings = "Support has closed your ticket.";
					$content  = "Support has closed your ticket.";
					$user_id  = $arr_user['user_id'];
					$status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
				}

				/*code end*/
				return redirect($this->module_url_path)->with('success','Ticket closed successfully and email sent to user account');
			}
			else
			{
				return redirect($this->module_url_path)->with('success','Ticket closed successfully.');
			}
		}
		else
		{
			return redirect($this->module_url_path)->with('error','Problem occured while closing ticket');
		}
		return redirect()->back();
	}

	public function change_level(Request $request,$id)
	{		
		$arr_data = [];
		$id = base64_decode($id);

		$support_details = $this->SupportTeamModel->where('id',$this->support_id)->first(['id','support_level']);
		
		if($id!=null)
		{
			if(isset($support_details) && $support_details!=null)
			{
				if($support_details->support_level == "L3")
				{
					$update_level = $this->SupportQueryModel->where('id',$id)->update(['support_user_id'=>0,'support_level'=>'L2','status'=>1]);
					$arr_data['support_level'] = 'L2';				
				}
				elseif($support_details->support_level == "L2")
				{
					$update_level = $this->SupportQueryModel->where('id',$id)->update(['support_user_id'=>0,'support_level'=>'L1','status'=>1]);
					$arr_data['support_level'] = 'L1';
				}
			}

			$arr_data['support_user_id'] = $this->support_id;
			$arr_data['query_id']        = isset($id)?$id:'';
			$this->SupportLogModel->create($arr_data);

			if($update_level && isset($support_details->support_level) && $support_details->support_level=="L3")
			{
				return redirect($this->module_url_path)->with('success','Ticket has been assign to middle level successfully.');
			}
			elseif($update_level && isset($support_details->support_level) && $support_details->support_level=="L2")
			{
				return redirect($this->module_url_path)->with('success','Ticket has been assign to higher level successfully.');
			}
			else
			{
				return redirect($this->module_url_path)->with('error','Problem occured, while assigning ticket to higher level.');
			}			
		}
	}

	/*
    | Function  : 
    | Author    : Deepak Arvind Salunke
    | Date      : 10/05/2018
    | Output    : Success or Error
    */
	public function refund_process(Request $request)
	{
		$refund = '';
		$arr_booking = $update_arr = $arr_json = [];

		$booking_id    = base64_decode($request->input('booking_id'));
		$ticket_id     = base64_decode($request->input('ticket_id'));
		$refund_amount = $request->input('refund_amount') * 100;
        $actual_refund_amount = $request->input('refund_amount');

		$obj_booking = $this->BookingModel->where('id', $booking_id)
										  ->with('transaction_details', 'booking_by_user_details', 'user_details')
										  ->first();
		if($obj_booking)
		{
			$arr_booking = $obj_booking->toArray();
		}

		if(isset($arr_booking['transaction_details']) && $arr_booking['payment_type'] == 'booking')
		{
			if( floatval($actual_refund_amount) <= floatval($arr_booking['total_amount']) )
			{
				try{
					$refund = $this->api->refund->create(
								array(
										'payment_id' => $arr_booking['transaction_details']['transaction_id'],
										'amount'     => $refund_amount
									)
								);
				}

				catch(\Exception $e){
					$arr_json['status'] = 'error';
					$arr_json['msg'] = $e->getMessage();
					return response()->json($arr_json);
				}

				if($refund)
		    	{
		    		/*$update_arr = array('status' => '3');
					$this->SupportQueryModel->where('id',$ticket_id)->update($update_arr);*/

					$refund_amount = $arr_booking['refund_amount'] + $actual_refund_amount;

		    		$obj_booking = $this->BookingModel->where('id',$booking_id)
		    										  ->update( array('booking_status' => '6','cancelled_date' => date('Y-m-d'), 'refund_amount' => $refund_amount) );

		    		$refund_transaction['payment_type']     = 'refund';
		    		$refund_transaction['transaction_id']   = $arr_booking['transaction_details']['transaction_id'];
		    		$refund_transaction['user_id']          = $arr_booking['booking_by_user_details']['id'];
		    		$refund_transaction['user_type']        = '1';
		    		$refund_transaction['amount']           = $actual_refund_amount;
		    		$refund_transaction['booking_id']       = $booking_id;
		    		$refund_transaction['transaction_for']  = 'Refund for cancel booking';
		    		$refund_transaction['transaction_date'] = date('Y-m-d');
		    		$refund_store = $this->TransactionModel->create( $refund_transaction );

		    		if($refund_store)
		    		{
		    			$refund_invoice = $this->generateRefundInvoice($refund_store->id, $arr_booking['booking_by_user_details']['id'], $arr_booking['property_owner_id'], 'guest');

		    			$this->TransactionModel->where('id',$refund_store->id)->update(['invoice' => $refund_invoice]);
		    		}

		    		//Send notification to admin
		            $arr_built_content_admin  = array(
                                    'USER_NAME' => isset($arr_booking['booking_by_user_details']['first_name']) ? ucfirst($arr_booking['booking_by_user_details']['first_name']) : 'NA',
                                    'MESSAGE'   => $actual_refund_amount." INR amount refund successfully to this booking id - ".$arr_booking['booking_id']
								);

		            $arr_notify_data_admin['arr_built_content']  = $arr_built_content_admin;
		            $arr_notify_data_admin['notify_template_id'] = '9';
		            $arr_notify_data_admin['template_text']      = $actual_refund_amount. " INR User Amount Refund successfully";
		            $arr_notify_data_admin['sender_id']          = Session::get('user_id');
		            $arr_notify_data_admin['sender_type']        = '3';
		            $arr_notify_data_admin['receiver_type']      = '2';
		            $arr_notify_data_admin['receiver_id']        = '1';
		            $notification_status = $this->NotificationService->send_notification($arr_notify_data_admin);


		            // For Guest Notification
		            if( $arr_booking['booking_by_user_details'] != null && !empty($arr_booking['booking_by_user_details']) )
		            {
		            	// For website notification
		            	$arr_built_content_guest = array(
	                                    	'USER_NAME' => isset($arr_booking['booking_by_user_details']['first_name']) ? ucfirst($arr_booking['booking_by_user_details']['first_name']) : 'NA',
	                                    	'SUBJECT'   => "Refund Amount."
	                                    );

			            $arr_notify_data_guest['arr_built_content']  = $arr_built_content_guest;
			            $arr_notify_data_guest['notify_template_id'] = '13';
			            $arr_notify_data_guest['sender_id']          = Session::get('user_id');
			            $arr_notify_data_guest['sender_type']        = '3';
			            $arr_notify_data_guest['receiver_type']      = '4';
			            $arr_notify_data_guest['receiver_id']        = $arr_booking['booking_by_user_details']['id'];
			            $notification_status_guest = $this->NotificationService->send_notification($arr_notify_data_guest);

			            // For email notification
			    		if($arr_booking['booking_by_user_details']['notification_by_email'] == 'on')
				        {
				            $arr_built_content_guest = [
				                                'USER_NAME'    => isset($arr_booking['booking_by_user_details']['first_name']) ? ucfirst($arr_booking['booking_by_user_details']['first_name']) : 'NA',
				                                'Email'        => isset($arr_booking['booking_by_user_details']['email']) ? ucfirst($arr_booking['booking_by_user_details']['email']) : 'NA',
				                                'PROJECT_NAME' => config('app.project.name')
				                            ];
				            $arr_mail_data_guest                      = [];
				            $arr_mail_data_guest['email_template_id'] = '17';
				            $arr_mail_data_guest['arr_built_content'] = $arr_built_content_guest;
				            $arr_mail_data_guest['user']              = [
					            	'email' => isset($arr_booking['booking_by_user_details']['email']) ? ucfirst($arr_booking['booking_by_user_details']['email']) : 'NA',
					            	'first_name' => isset($arr_booking['booking_by_user_details']['first_name']) ? ucfirst($arr_booking['booking_by_user_details']['first_name']) : 'NA'
								];

				            $status = $this->EmailService->send_mail($arr_mail_data_guest);
				        }

				        // For sms notification
				        if($arr_booking['booking_by_user_details']['notification_by_sms'] == 'on')
				        {
				            $country_code = isset($arr_booking['booking_by_user_details']['country_code']) ? $arr_booking['booking_by_user_details']['country_code'] : '';
				            $mobile_number = isset($arr_booking['booking_by_user_details']['mobile_number']) ? $arr_booking['booking_by_user_details']['mobile_number'] : '';

				            $arr_sms_data_guest                  = [];
				            $arr_sms_data_guest['msg']           = $actual_refund_amount." INR amount Is Refund successfully In Your Account";
				            $arr_sms_data_guest['mobile_number'] = $country_code.$mobile_number;
				            $status = $this->SMSService->send_SMS($arr_sms_data_guest);
				        }

				        // For push notification
				        if($arr_booking['booking_by_user_details']['notification_by_push'] == 'on')
				        {
				            $headings_guest = $actual_refund_amount." INR Amount Is Refund successfully In Your Account";
							$content_guest  = $actual_refund_amount." INR Amount Is Refund successfully In Your Account";
							$user_id_guest  = $arr_booking['booking_by_user_details']['id'];
							$status_guest   = $this->MobileAppNotification->send_app_notification($headings_guest, $content_guest, $user_id_guest);
				        }
		            }


					$arr_json['status'] = 'success';
					$arr_json['msg']    = 'Amount Is Refund successfully In Your Account';
		    	}
		    	else
		    	{
					$arr_json['status'] = 'error';
					$arr_json['msg']    = 'Something went wrong. Please try again';
		    	}
			}
			else
			{
				$arr_json['status'] = 'error';
				$arr_json['msg']    = 'Refund Amount Is InValid';
			}	
		}

		elseif(isset($arr_booking['transaction_details']) && $arr_booking['payment_type'] == 'wallet')
		{
			if($actual_refund_amount < $arr_booking['total_amount'])
			{
				$old_amount = $arr_booking['booking_by_user_details']['wallet_amount'];
				$total_amount = $actual_refund_amount + $old_amount;
				
				$obj_user = $this->UserModel->where('id',$arr_booking['property_booked_by'])
											->update(array('wallet_amount' => $total_amount));

				$obj_booking = $this->BookingModel->where('id', $booking_id)->update(array('booking_status' => '6','cancelled_date' => date('Y-m-d'), 'refund_amount' => $actual_refund_amount));

				if($obj_booking)
				{
					/*$update_arr = array('status' => '3');
					$this->SupportQueryModel->where('id',$ticket_id)->update($update_arr);*/

					$obj_booking = $this->BookingModel->where('id',$booking_id)
		    										  ->update( array('booking_status' => '6','cancelled_date' => date('Y-m-d'), 'refund_amount' => $actual_refund_amount) );

		    		$refund_transaction['payment_type']     = 'refund';
		    		$refund_transaction['transaction_id']   = $arr_booking['transaction_details']['transaction_id'];
		    		$refund_transaction['user_id']          = $arr_booking['booking_by_user_details']['id'];
		    		$refund_transaction['user_type']        = '1';
		    		$refund_transaction['amount']           = $actual_refund_amount;
		    		$refund_transaction['booking_id']       = $booking_id;
		    		$refund_transaction['transaction_for']  = 'Refund for cancel booking';
		    		$refund_transaction['transaction_date'] = date('Y-m-d');
		    		$refund_store = $this->TransactionModel->create( $refund_transaction );

		    		if($refund_store)
		    		{
		    			$refund_invoice = $this->generateRefundInvoice($refund_store->id, $arr_booking['booking_by_user_details']['id'], $arr_booking['property_owner_id'], 'guest');

		    			$this->TransactionModel->where('id',$refund_store->id)->update(['invoice' => $refund_invoice]);
		    		}


					//Send notification to admin
		            $arr_built_content_admin  = array(
						'USER_NAME' => isset($arr_booking['booking_by_user_details']['first_name']) ? ucfirst($arr_booking['booking_by_user_details']['first_name']) : 'NA',
						'MESSAGE'   => $actual_refund_amount." INR amount refund successfully to this booking id - ".$arr_booking['booking_id']
					);

		            $arr_notify_data_admin['arr_built_content']  = $arr_built_content_admin;
		            $arr_notify_data_admin['notify_template_id'] = '9';
		            $arr_notify_data_admin['template_text']      = $actual_refund_amount." INR User Amount Refund successfully";
		            $arr_notify_data_admin['sender_id']          = Session::get('user_id');
		            $arr_notify_data_admin['sender_type']        = '3';
		            $arr_notify_data_admin['receiver_type']      = '2';
		            $arr_notify_data_admin['receiver_id']        = '1';
		       		
		            $notification_status = $this->NotificationService->send_notification($arr_notify_data_admin);


					$arr_built_content  = array(
	                                    'USER_NAME' => isset($arr_booking['booking_by_user_details']['first_name'])?ucfirst($arr_booking['booking_by_user_details']['first_name']):'NA',
	                                    'SUBJECT'   => "Refund Amount."
                                    );

		            $arr_notify_data['arr_built_content']  = $arr_built_content;
		            $arr_notify_data['notify_template_id'] = '13';
		            $arr_notify_data['sender_id']          = Session::get('user_id');
		            $arr_notify_data['sender_type']        = '3';
		            $arr_notify_data['receiver_type']      = '1';
		            $arr_notify_data['receiver_id']        = $arr_booking['booking_by_user_details']['id'];
		            $notification_status = $this->NotificationService->send_notification($arr_notify_data);


		            // for email
			    	if($arr_booking['booking_by_user_details']['notification_by_email'] == "on")
			        {
			            $arr_built_content    = [
			                                'USER_NAME'    => isset($arr_booking['booking_by_user_details']['first_name'])?ucfirst($arr_booking['booking_by_user_details']['first_name']):'NA',
			                                'Email'        => isset($arr_booking['booking_by_user_details']['email'])?ucfirst($arr_booking['booking_by_user_details']['email']):'NA' ,
			                                'PROJECT_NAME' => config('app.project.name')
			                             ];
			            $arr_mail_data                      = [];
			            $arr_mail_data['email_template_id'] = '17';
			            $arr_mail_data['arr_built_content'] = $arr_built_content;
			            $arr_mail_data['user']              = [
		            		'email' => isset($arr_booking['booking_by_user_details']['email']) ? ucfirst($arr_booking['booking_by_user_details']['email']) : 'NA',
		            		'first_name' => isset($arr_booking['booking_by_user_details']['first_name']) ? ucfirst($arr_booking['booking_by_user_details']['first_name']) : 'NA'
			            										];

			            $status = $this->EmailService->send_mail($arr_mail_data);
			        }

			        // for sms
			        if($arr_booking['booking_by_user_details']['notification_by_sms'] == "on")
			        {
			            $country_code = isset($arr_booking['booking_by_user_details']['country_code']) ? $arr_booking['booking_by_user_details']['country_code'] : '';
			            $mobile_number = isset($arr_booking['booking_by_user_details']['mobile_number']) ? $arr_booking['booking_by_user_details']['mobile_number'] : '';

			            $arr_sms_data                  = [];
			            $arr_sms_data['msg']           = $actual_refund_amount." INR Amount Is Refund successfully In Your Account";
			            $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
			            $status = $this->SMSService->send_SMS($arr_sms_data);
			        }

			        // for push notification
					if($arr_booking['booking_by_user_details']['notification_by_push'] == 'on')
					{
						$headings = $actual_refund_amount." INR Amount Is Refund successfully";
						$content  = $actual_refund_amount." INR Amount Is Refund successfully In Your Account";
						$user_id  = $arr_booking['booking_by_user_details']['id'];
						$status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
					}

			       $arr_json['status'] = 'success';
			       $arr_json['msg']    = $actual_refund_amount.' INR Amount Is Refund successfully In Your Account';
				}
				else
				{
					$arr_json['status'] = 'error';
					$arr_json['msg']    = 'Something went wrong. Please try again';
				}
			}
			else
			{
				$arr_json['status'] = 'error';
				$arr_json['msg']    = 'Refund Amount Is InValid';
			}
		}

		return response()->json($arr_json);
    	
	} // end refund_process


	public function generateRefundInvoice($transaction_id = false, $receiver_id = false, $sender_id = false, $user_type = false)
    {
        $data = $Senderdata = $ReceivedData = $arr_transaction = [];
        $html = $view = $FileName = '';

        if (isset($transaction_id) && $transaction_id != false && isset($receiver_id) && $receiver_id != false) {
            $obj_transaction = $this->TransactionModel->where('id',$transaction_id)->first();
            if ($obj_transaction) {
                $arr_transaction = $obj_transaction->toArray();
            }

            $receiver_user_data = $this->UserModel->where('id', $receiver_id)->first();
            
            if ($receiver_user_data) {
                $ReceivedData = $receiver_user_data->toArray();
            }

            $sender_user_data = $this->AdminModel->first();
            if ($sender_user_data) {
                $SenderData = $sender_user_data->toArray();
            }

            $data['logo']      = url('/front/images/logo-inner.png');
            $data['base_url']  = url('/');
            $data['user_type'] = 'guest';

            PDF::SetTitle(config('app.project.name'));
            PDF::AddPage();

            $view = view('invoice.booking_invoice_pdf')->with(['ReceivedData' => $ReceivedData, 'SenderData' => $SenderData, 'TrasactionData' => $arr_transaction, 'Data' => $data]);
          
            $html = $view->render();

            PDF::writeHTML($html, true, false, true, false, 'L');
            $FileName = 'RefundInvoice'.$transaction_id.$ReceivedData['id'].'.pdf';
            PDF::output(public_path('uploads/invoice/'.$FileName),'F');
            PDF::reset();
        }
        return $FileName;
    }

    public function reply($id)
	{   
		$user_id = $user_type = '';
		$arr_data  = [];

		$id        = base64_decode($id);
		$obj_query = $this->SupportQueryModel->where('id', $id)->with('user_details')->first();
		if($obj_query) {
			$arr_data = $obj_query->toArray();

			$user_id   = $arr_data['user_id'];
			$user_type = $arr_data['user_type'];
		}

		// Support Details
        $arr_support_details['id']            = $this->support_id;
        $arr_support_details['first_name']    = $this->support_first_name;
        $arr_support_details['last_name']     = $this->support_last_name;
        $arr_support_details['user_name']     = $this->support_user_name;
        $arr_support_details['display_name']  = $this->support_display_name;
        $arr_support_details['profile_image'] = $this->support_profile_image;

        // Support Profile Image
        $arr_support_details['profile_image_url'] = url('/uploads').'/default-profile.png';
        if(isset($arr_support_details['profile_image']) && $arr_support_details['profile_image'] != '' ) {
            if(file_exists($this->support_profile_image_base_path.$arr_support_details['profile_image'])) {
                $arr_support_details['profile_image_url'] = $this->support_profile_image_public_path.$arr_support_details['profile_image'];
            }   
        }

        $arr_ticket_comments = $this->get_previous_chat($id, $user_id, $user_type, $this->support_id);
        $this->read_unread_message($id, $user_id, $user_type, $this->support_id);


		$this->arr_view_data['query_id']                          = $id;
		$this->arr_view_data['user_id']                           = $user_id;
		$this->arr_view_data['user_type']                         = $user_type;
		$this->arr_view_data['arr_support_details']               = $arr_support_details;
		$this->arr_view_data['arr_ticket_comments']               = $arr_ticket_comments;

		$this->arr_view_data['user_data']                         = $arr_data;
		$this->arr_view_data['id']                                = base64_encode($id);
		$this->arr_view_data['module_icon']                       = $this->module_icon;
		$this->arr_view_data['page_title']                        = "Chat";
		$this->arr_view_data['page_icon']                         = 'fa-comments-o';
		$this->arr_view_data['module_title']                      = str_plural($this->module_title);
		$this->arr_view_data['module_url_path']                   = $this->module_url_path;
		$this->arr_view_data['support_panel_slug']                = $this->support_panel_slug;
		$this->arr_view_data['previous_page_url']                 = $this->support_url_path."/ticket";
		$this->arr_view_data['previous_page_title']               = "Manage Assign Tickets";

		$this->arr_view_data['user_profile_image_public_path']    = $this->user_profile_image_public_path;
		$this->arr_view_data['user_profile_image_base_path']      = $this->user_profile_image_base_path;
		$this->arr_view_data['support_profile_image_public_path'] = $this->support_profile_image_public_path;
		$this->arr_view_data['support_profile_image_base_path']   = $this->support_profile_image_base_path;

		return view($this->module_view_folder.'.chat',$this->arr_view_data);
	}


	public function store_chat(Request $request)
    {
        $arr_comment_data = [
                                'query_id'        => $request->input('query_id'),
                                'comment_by'      => $this->support_id,
                                'user_id'         => $request->input('user_id'),
                                'user_type'       => $request->input('user_type'),
                                'support_user_id' => $this->support_id,
                                'is_read'         => 0,
                                'comment'         => $request->input('comment'),
                            ];

        $obj_comment = $this->SupportQueryCommentModel->create($arr_comment_data);


        // Notification To User Starts
        $arr_built_content = array(
									'USER_NAME' => $this->support_first_name,
                                    'MESSAGE'   => "Support has replied on your ticket."
                                );

        $arr_notify_data['arr_built_content']  = $arr_built_content;
        $arr_notify_data['notify_template_id'] = '9';
        $arr_notify_data['sender_id']          = $this->support_id;
        $arr_notify_data['sender_type']        = '3';
        $arr_notify_data['receiver_id']        = $request->input('user_id');
        $arr_notify_data['receiver_type']      = $request->input('user_type');
        $arr_notify_data['url']                = "/ticket-listing";
        $notification_status = $this->NotificationService->send_notification($arr_notify_data);

		$type = get_notification_type_of_user($request->input('user_id'));
		if(isset($type) && !empty($type)) {
		    
		    // for push notification
			if($type['notification_by_push'] == 'on') {
				$headings = "Support has replied on your ticket.";
				$content  = "Support has replied on your ticket.";
				$user_id  = $request->input('user_id');
				$status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
			}
		}
		// Notification To User Ends

        $arr_response['status'] = 'success';
        $arr_response['msg']    = 'message send successfully.';
        $arr_response['id']     = isset($obj_comment->id) ? $obj_comment->id : 0;
        $arr_response['date']   = isset($obj_comment->created_at) ? date('d M, h:i A',strtotime($obj_comment->created_at)) : '';
        return $arr_response;
    }
    
    public function get_current_chat_messages(Request $request)
    {
        $query_id        = (int) $request->input('query_id');
        $user_id         = (int) $request->input('user_id');
        $user_type       = (int) $request->input('user_type');
        $support_user_id = (int) $this->support_id;

        $this->read_unread_message($query_id, $user_id, $user_type, $support_user_id);

        $select_query = '';

        $select_query = "SELECT 
                            support_query_comments.id,
                            support_query_comments.query_id,
                            support_query_comments.user_id,
                            support_query_comments.user_type,
                            support_query_comments.support_user_id, 
                            support_query_comments.is_read,
                            support_query_comments.comment,
                            support_query_comments.comment_by,
                            DATE_FORMAT(support_query_comments.created_at,'%d %b, %h:%i %p') as date,
                            users.user_name,
                            users.user_type,
                            users.first_name,
                            users.last_name,
                            users.email,
                            users.mobile_number,
                            users.address,
                            users.profile_image
                        FROM support_query_comments
                        LEFT JOIN users
                        ON support_query_comments.user_id = users.id
                        WHERE support_query_comments.query_id = $query_id
                        AND support_query_comments.user_id = $user_id
                        AND support_query_comments.user_type = $user_type
                        AND support_query_comments.support_user_id = $support_user_id
                        ORDER BY support_query_comments.id ASC";
        
        $arr_ticket_comments = [];
        if($select_query != '')
        {
            $obj_ticket_comments = \DB::select($select_query);

            if(isset($obj_ticket_comments) && sizeof($obj_ticket_comments) > 0) {
                $arr_ticket_comments = json_decode(json_encode($obj_ticket_comments), true);
            }
        }
        
        $arr_response = [];

        if(isset($arr_ticket_comments) && sizeof($arr_ticket_comments)>0)
        {
            $arr_response['status'] = 'success';
            $arr_response['msg']    = 'support chat available';
            $arr_response['data']   = $arr_ticket_comments;
            return $arr_response;
        }
        
        $arr_response['status'] = 'error';
        $arr_response['msg']    = 'support chat not available';
        $arr_response['data']   = $arr_ticket_comments;
        return $arr_response;

    }

    public function get_previous_chat($query_id, $user_id, $user_type, $support_user_id)
    {
        $select_query = '';

        if($query_id != '' && $user_id != '' && $user_type != '' && $support_user_id != '')
        {
            $select_query = "SELECT 
                                support_query_comments.id,
                                support_query_comments.query_id,
                                support_query_comments.user_id,
                                support_query_comments.user_type,
                                support_query_comments.support_user_id, 
                                support_query_comments.is_read,
                                support_query_comments.comment,
                                support_query_comments.comment_by,
                                DATE_FORMAT(support_query_comments.created_at,'%d %b, %h:%i %p') as date,
                                users.user_name,
	                            users.user_type,
	                            users.first_name,
	                            users.last_name,
	                            users.email,
	                            users.mobile_number,
	                            users.address,
	                            users.profile_image
                            FROM support_query_comments
                            LEFT JOIN users
                            ON support_query_comments.user_id = users.id
                            WHERE support_query_comments.query_id = $query_id
                            AND support_query_comments.user_id = $user_id
                            AND support_query_comments.user_type = $user_type
                            AND support_query_comments.support_user_id = $support_user_id
                            ORDER BY support_query_comments.id ASC";
            
            $arr_ticket_comments = [];
            if($select_query != '')
            {
                $obj_ticket_comments =  \DB::select($select_query);

                if(isset($obj_ticket_comments) && sizeof($obj_ticket_comments) > 0) {
                    $arr_ticket_comments = json_decode(json_encode($obj_ticket_comments), true);
                }
            }
            return $arr_ticket_comments;
        }
        return [];
    }

    public function read_unread_message($query_id, $user_id, $user_type, $support_user_id)
    {
        if($query_id != '' && $user_id != '' && $user_type != '' && $support_user_id != '')
        {
            return $this->SupportQueryCommentModel->where('query_id', $query_id)
                                                  ->where('user_id', $user_id)
                                                  ->where('user_type', $user_type)
                                                  ->where('support_user_id', $support_user_id)
                                                  ->where('comment_by', $support_user_id)
                                                  ->update(['is_read' => '1']);
        }
        return true;
    }


	/*public function reply($id)
	{   
		$arr_data  = [];

		$id        = base64_decode($id);
		$obj_query = $this->SupportQueryModel->where('id',$id)->with('user_details')->first();
		if($obj_query)
		{
			$arr_data = $obj_query->toArray();
		}

		$this->arr_view_data['user_data']            = $arr_data;
		$this->arr_view_data['id']                   = base64_encode($id);
		$this->arr_view_data['module_icon']          = $this->module_icon;
		$this->arr_view_data['page_title']           = "Send reply";
		$this->arr_view_data['page_icon']            = 'fa-reply';
		$this->arr_view_data['module_title']         = str_plural($this->module_title);
		$this->arr_view_data['module_url_path']      = $this->module_url_path;
		$this->arr_view_data['support_panel_slug'] 	 = $this->support_panel_slug;
		$this->arr_view_data['previous_page_url']    = $this->support_url_path."/ticket";
		$this->arr_view_data['previous_page_title']  = "Manage Assign Tickets";

		return view($this->module_view_folder.'.reply',$this->arr_view_data);
	}*/
}
