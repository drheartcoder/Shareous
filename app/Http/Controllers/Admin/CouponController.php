<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\CouponModel;
use App\Common\Traits\MultiActionTrait;

use App\Models\UserModel;
use App\Common\Services\NotificationService;
use App\Common\Services\MobileAppNotification;
use App\Common\Services\EmailService;
use App\Common\Services\SMSService;

use Validator;
use Session;
use Auth;
use Hash;
use DB;
use Datatables;

class CouponController extends Controller
{
 	use MultiActionTrait;
	
	public function __construct(
									CouponModel           $coupon_model,
									UserModel             $user_model,
									NotificationService   $notification_service,
									MobileAppNotification $mobileappnotification_service,
									EmailService          $email_service,
									SMSService            $sms_service
								)
	{
		$this->arr_data           			 = [];
		$this->admin_panel_slug   			 = config('app.project.admin_panel_slug');
		$this->admin_url_path     			 = url(config('app.project.admin_panel_slug'));		
		$this->module_url_path    			 = $this->admin_url_path."/coupon";
		$this->module_title       			 = "Coupon";
		$this->module_view_folder 			 = "admin.coupon";
		$this->module_icon        			 = "fa fa-trophy";
		$this->CouponModel  			 	 = $coupon_model;
		$this->BaseModel          			 = $coupon_model;
		$this->UserModel 		  			 = $user_model;
		$this->NotificationService 			 = $notification_service;
		$this->MobileAppNotification    	 = $mobileappnotification_service;
		$this->EmailService       			 = $email_service;
        $this->SMSService         			 = $sms_service;
	}

	public function index()
	{
		$arr_user = [];
		
		$this->arr_data['objects']          = $arr_user;
		$this->arr_data['page_title']       = "Manage ".$this->module_title;
		$this->arr_data['module_icon']      = $this->module_icon;
		$this->arr_data['module_title']     = $this->module_title;
		$this->arr_data['module_url_path']  = $this->module_url_path;
		$this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
		return view($this->module_view_folder.'.index',$this->arr_data);
	}

	public function load_data(Request $request)
	{
		$UserData          =  $final_array=[]; 
        $column            = '';

        $code              = $request->input('code'); 
        $discount_type     = $request->input('discount_type'); 
        $discount_account  = $request->input('discount_account'); 
        $global_expiry     = ($request->input('global_expiry') != '') ? date("Y-m-d", strtotime($request->input('global_expiry'))) : '';
        $auto_expiry       = ($request->input('auto_expiry') != '') ? date("H:i:s", strtotime($request->input('auto_expiry'))) : '';
        $coupon_use        = $request->input('coupon_use'); 

        if ($request->input('order')[0]['column'] == 1) {
            $column = "id";
        }

        if ($request->input('order')[0]['column'] == 2) {
            $column = "coupon_code";
        }

        if($request->input('order')[0]['column'] == 3) {
            $column = "discount_type";
        }

        if ($request->input('order')[0]['column'] == 4) {
            $column = "discount";
        }

        if ($request->input('order')[0]['column'] == 5) {
            $column = "global_expiry";
        }

        if ($request->input('order')[0]['column'] == 6) {
            $column = "auto_expiry";
        }

        if ($request->input('order')[0]['column'] == 7) {
            $column = "coupon_use";
        }

        $order = strtoupper($request->input('order')[0]['dir']);  

		$coupon_table          = $this->CouponModel->getTable();
		$prefixed_coupon_table = DB::getTablePrefix().$this->CouponModel->getTable();
		$arr_search_column     = $request->input('column_filter');
		
		$obj_data = DB::table($coupon_table)
						->select(DB::raw(
											$prefixed_coupon_table.".id as id,".
											$prefixed_coupon_table.".coupon_code as coupon_code,".
											$prefixed_coupon_table.".discount_type as discount_type,".
											$prefixed_coupon_table.".discount as discount,".
											$prefixed_coupon_table.".global_expiry as global_expiry,".
											$prefixed_coupon_table.".auto_expiry as auto_expiry,".
											$prefixed_coupon_table.".coupon_use as coupon_use,".
											$prefixed_coupon_table.".status as status"
										))
						->orderBy($prefixed_coupon_table.'.id','DESC');	

	    if (isset($code) && $code != ""){
			$obj_data = $obj_data->where($prefixed_coupon_table.'.coupon_code','LIKE', '%'.$code.'%');
		}

		if (isset($discount_type) && $discount_type != "") {
			$obj_data = $obj_data->where($prefixed_coupon_table.'.discount_type','LIKE', '%'.$discount_type.'%');
		}

		if (isset($discount_account) && $discount_account != "") {
			$obj_data = $obj_data->where($prefixed_coupon_table.'.discount','LIKE', '%'.$discount_account.'%');
		}	
		if(isset($global_expiry) && $global_expiry != "")
		{
			if(isset($auto_expiry) && $auto_expiry != ""){
				$obj_data = $obj_data->whereRaw($prefixed_coupon_table.".global_expiry = '".$global_expiry."' AND ".$prefixed_coupon_table.'.auto_expiry = "'.$auto_expiry.'"');
			}else{
				$obj_data = $obj_data->where($prefixed_coupon_table.'.global_expiry','=', $global_expiry);
			}
		}

		if (isset($coupon_use) && $coupon_use != "") {
			$obj_data = $obj_data->where($prefixed_coupon_table.'.coupon_use','LIKE', '%'.$coupon_use.'%');
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
        // dd(DB::getQueryLog());
        $resp['draw']            = $_GET['draw'];
        $resp['recordsTotal']    = $count;
        $resp['recordsFiltered'] = $count;
        $build_active_btn        = '' ; 

        if(count($UserData)>0){
            $i = 0;

            foreach($UserData as $row){
            	$build_view_action =''; 
            	$built_view_href   = $this->module_url_path.'/edit/'.base64_encode($row->id);

		     	$built_bank_details_href   = $this->module_url_path.'/delete/'.base64_encode($row->id);

                if($row->status != null && $row->status == "0"){
                    $build_active_btn = '<a class="btn btn-sm btn-danger" title="Block" href="'.$this->module_url_path.'/unblock/'.base64_encode($row->id).'" 
				   onclick="return confirm_action(this,event,\'Do you really want to Unblock this record ?\')" >Block</a>';
                }elseif($row->status != null && $row->status == "1"){
                    $build_active_btn = '<a class="btn btn-sm btn-success" title="Unblock" href="'.$this->module_url_path.'/block/'.base64_encode($row->id).'" onclick="return confirm_action(this,event,\'Do you really want to block this record ?\')" >Unblock</a>';      
                }

                $build_view_action .= '&nbsp;<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="'.$built_bank_details_href.'" onclick="return confirm_action(this,event,\'Do you really want to deactivate this record ?\')" ><i class="fa fa-trash" ></i></a>';

				$build_view_action .= "&nbsp;<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' href='".$built_view_href."' title='Edit' data-original-title='Edit'>
				<i class='fa fa-pencil-square-o'></i></a>";

                $final_array[$i][0] = "<input type='checkbox' name='checked_record[]' id='checked_record' class='checked_record' value='".base64_encode($row->id)."'/>";

                if($row->discount_type == 1){
                	$discount_type = 'Fix Amount';
                }else if($row->discount_type == 2){
                	$discount_type = 'Percentage';
                }

                if($row->coupon_use == 1){
                	$coupon_use = 'Min Amount';
                }else if($row->coupon_use == 2){
                	$coupon_use = 'User First Time';
                }else if($row->coupon_use == 3){
                	$coupon_use = 'Both';
                }

                $discount_amount = '';
                if($row->discount_type == 1) {
                	$discount_amount = number_format($row->discount,2);
                	$discount_amount = '<i class="fa fa-inr" aria-hidden="true"></i> '.$discount_amount;
                } else if($row->discount_type == 2) {
                	$discount_amount = number_format($row->discount,2);
                	$discount_amount = $discount_amount.' %';
                }

                $final_array[$i][1] = $row->coupon_code;
                $final_array[$i][2] = $discount_type;
                $final_array[$i][3] = $discount_amount;
                $final_array[$i][4] = (isset($row->global_expiry) && $row->global_expiry != '0000-00-00 00:00:00') ? date("d-M-Y",strtotime($row->global_expiry)) : 'N/A';
                $final_array[$i][5] = (isset($row->auto_expiry) && $row->auto_expiry != '00:00:00') ? date("H:i", strtotime($row->auto_expiry)) : 'N/A';
                $final_array[$i][6] = $build_active_btn;
                $final_array[$i][7] = $build_view_action;
                $i++;
            }
        }

        $resp['data'] = $final_array;
        echo str_replace("\/", "/",  json_encode($resp));exit;	  
	}

	public function create()
	{
		$this->arr_view_data['page_title']                 = "Add ".$this->module_title;
		$this->arr_view_data['module_title']               = $this->module_title;
		$this->arr_view_data['module_url_path']            = $this->module_url_path;
		$this->arr_view_data['page_icon']                  = 'fa-trophy';
		$this->arr_view_data['module_icon']                = $this->module_icon;
		$this->arr_view_data['admin_panel_slug']           = $this->admin_panel_slug;
		return view($this->module_view_folder.'.create',$this->arr_view_data);
	}

	public function store(Request $request)
	{
		$arr_rules['coupon_code']         	  = "required";
		$arr_rules['descriptions']            = "required";
		$arr_rules['discount_type']           = "required";		
		$arr_rules['global_expiry']           = "required";
		$arr_rules['auto_expiry']             = "required";
		// $arr_rules['coupon_type']             = "required";

		$msg = array('required' => 'Please enter :attribute');

		$validator = Validator::make($request->all(), $arr_rules, $msg);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput($request->all());
		}

		$coupon_code = $request->input('coupon_code', null);
		
		if ($coupon_code != null) {
			$is_exist = $this->CouponModel->where('coupon_code',$coupon_code)->count();

			if ($is_exist > 0) {
				$validator->getMessageBag()->add('coupon_code', $this->module_title.' code already exist');
				return redirect()->back()->withErrors($validator);
			}

			$form_data = $request->all();

			list($month, $date, $year) = explode('/',$form_data['global_expiry']);
			$global_expiry = date("Y-m-d", mktime(0, 0, 0, $month, $date, $year));	

			$discount = '';

			if ($form_data['discount_type'] == '1') {
	        	$discount = $form_data['fix-amount'];
	        } else {
	        	$discount = $form_data['percentage'];
	        }		

			$this->arr_view_data['coupon_code']   = $form_data['coupon_code'];
			$this->arr_view_data['descriptions']  = $form_data['descriptions'];
			$this->arr_view_data['discount_type'] = $form_data['discount_type'];
			$this->arr_view_data['discount'] 	  = $discount;
			$this->arr_view_data['global_expiry'] = $global_expiry;
			$this->arr_view_data['auto_expiry']   = $form_data['auto_expiry'];
			// $this->arr_view_data['coupon_use']    = $form_data['coupon_type'];						
			$this->arr_view_data['status']        = 1;

			$arr_coupon = $this->CouponModel->create($this->arr_view_data);

			if ($arr_coupon) {
				/*$obj_user_data = $this->UserModel->where('status', 1)->get();
				if($obj_user_data)
				{
					$arr_user = $obj_user_data->toArray();

					foreach($arr_user as $user_data)
					{
						$arr_built_content  = array(
			                                     'USER_NAME'    		 => isset($user_data['first_name']) ? $user_data['first_name'] : 'NA',
			                                     'NOTIFICATION_SUBJECT'  => "New Coupon added by Admin",
			                                     'MESSAGE'       		 => "New Coupon added by Admin"
			                                    );

				        $arr_notify_data['arr_built_content']   = $arr_built_content;   
				        $arr_notify_data['notify_template_id']  = '9';
				        $arr_notify_data['sender_id']           = '1';
				        $arr_notify_data['sender_type']         = '2';
				        $arr_notify_data['receiver_type']       = '1';
			            $arr_notify_data['receiver_id']         = $user_data['id'];
			            
			            $notification_status                    = $this->NotificationService->send_notification($arr_notify_data);

			            $type     = get_notification_type_of_user($user_data['id']);
						if (isset($type) && !empty($type)) {
						    // for mail 
						    if ($type['notification_by_email'] == 'on') {
						        $arr_built_content         = [
						                            'USER_NAME'        => isset($user_data['display_name'])?ucfirst($user_data['display_name']):'NA',   
						                            'Email'            => isset($user_data['email'])?ucfirst($user_data['email']):'NA' ,  
						                            'MESSAGE'          => "New Coupon added by Admin",
						                            'PROJECT_NAME'     => config('app.project.name')
						                         ];
						        $arr_mail_data                         = [];
						        $arr_mail_data['email_template_id']    = '13';
						        $arr_mail_data['arr_built_content']    = $arr_built_content;
						        $arr_mail_data['user']                 = ['email' => isset($user_data['email'])?ucfirst($user_data['email']):'NA', 'first_name' => isset($user_data['display_name'])?ucfirst($user_data['display_name']):'NA'];

						        $status                                = $this->EmailService->send_mail($arr_mail_data);
						    }

						    // for sms
						   	if ($type['notification_by_sms'] == 'on') {
						        $arr_sms_data                  = [];
						        $arr_sms_data['msg']           = "New Coupon added by Admin";
						        $arr_sms_data['mobile_number'] = isset($user_data['mobile_number'])?$user_data['mobile_number']:'';

						        $status                        = $this->SMSService->send_SMS($arr_sms_data);
						    }

						    // for push notificaion
						    if ($type['notification_by_push'] == 'on') {
								$headings = 'New Coupon added!';
								$content  = 'Admin have added New Coupon to site!';
								$user_id  = $user_data['id'];
								$status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
							}
						}
					}
				}*/

				return redirect()->back()->with('success','Coupon added successfully');            
			} else {
				return redirect()->back()->with('error','Error while adding coupon');
			}
		} else {
			return redirect()->back()->with('error','Error while adding coupon');
		}
	}

	public function edit($id)
    {
        $arr_coupon      = [];
        isset($id) ? $id = base64_decode($id) : NULL;
        $obj_coupon      = $this->BaseModel->where('id', $id)->first();

        if(isset($obj_coupon) && $obj_coupon!="")
        { 
            $arr_coupon = $obj_coupon->toArray();
        }        

        $this->arr_view_data['coupon']        		 = $arr_coupon;
        $this->arr_view_data['id']                   = base64_encode($id);
        $this->arr_view_data['page_title']           = "Edit ".$this->module_title;
        $this->arr_view_data['module_title']         = $this->module_title;
        $this->arr_view_data['page_icon']            = 'fa-trophy';
        $this->arr_view_data['module_url_path']      = $this->module_url_path;
        $this->arr_view_data['module_icon']  		 = $this->module_icon;
        $this->arr_view_data['admin_panel_slug'] 	 = $this->admin_panel_slug;
        
        return view($this->module_view_folder.'.edit',$this->arr_view_data);
    }

	public function update(Request $request,$id)
    {
    	$arr_coupon     = [];
    	$discount 		= '';
        ($id)? $id      = base64_decode($id):NULL;

		$arr_rules['coupon_code']   = "required";
		$arr_rules['descriptions']  = "required";
		$arr_rules['discount_type'] = "required";		
		$arr_rules['global_expiry'] = "required";
		$arr_rules['auto_expiry']   = "required";
		// $arr_rules['coupon_type']         = "required";

    	$msg = array( 'required' =>'Please enter :attribute');
        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $form_data = $request->all();
        $does_exists = $this->BaseModel->where('coupon_code',$form_data['coupon_code'])->where('id','!=',$id)->count()>0; 
        
        if ($does_exists) {
            $validator->getMessageBag()->add('query_type', 'Coupon code already exist');
            return redirect()->back()->withErrors($validator);
        }

        if ($form_data['discount_type'] == '1') {
        	$discount = $form_data['fix-amount'];
        } else {
        	$discount = $form_data['percentage'];
        }

        list($month, $date, $year) = explode('/',$form_data['global_expiry']);
		$global_expiry = date("Y-m-d", mktime(0, 0, 0, $month, $date, $year));

    	$this->arr_view_data['coupon_code']   = $form_data['coupon_code'];
		$this->arr_view_data['descriptions']  = $form_data['descriptions'];
		$this->arr_view_data['discount_type'] = $form_data['discount_type'];
		$this->arr_view_data['discount'] 	  = $discount;
		$this->arr_view_data['global_expiry'] = $global_expiry;
		$this->arr_view_data['auto_expiry']   = $form_data['auto_expiry'];
		// $this->arr_view_data['coupon_use']    = $form_data['coupon_type'];

        $coupon = $this->BaseModel->where('id',$id)->update($this->arr_view_data);       

        if($coupon) {
            
        	Session::flash('success','Coupon updated successfully');
        	return redirect($this->module_url_path);

        } else {
        	return redirect()->back()->with('error','Error while updating Coupon');
        }
    }
}
