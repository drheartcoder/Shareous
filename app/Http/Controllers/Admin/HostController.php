<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\BankDetailsModel;
use App\Models\HostVerificationModel;
use App\Common\Traits\MultiActionTrait;
use App\Models\ReviewRatingModel;
use App\Models\PropertyModel;

use App\Common\Services\EmailService;
use App\Common\Services\SMSService;
use App\Common\Services\NotificationService;
use App\Common\Services\MobileAppNotification;

use Validator;
use Session;
use DB;
use Image;
use Datatables;

class HostController extends Controller
{
    //use MultiActionTrait;
    function __construct(
                            UserModel             $user_model,
                            BankDetailsModel      $bankdetails_model,
                            HostVerificationModel $hostverification_model,
                            ReviewRatingModel     $review_rating_model,
                            PropertyModel         $property_model,
                            EmailService          $email_service,
                            SMSService            $sms_service,
                            NotificationService   $notification_service,
                            MobileAppNotification $mobileappnotification_service
                        )
    {
        $this->arr_data                       = [];
        $this->admin_panel_slug               = config('app.project.admin_panel_slug');
        $this->admin_url_path                 = url(config('app.project.admin_panel_slug'));
        $this->profile_image_public_path      = url('/').config('app.project.img_path.user_profile_images');
        $this->profile_image_base_path        = public_path().config('app.project.img_path.user_profile_images');
        $this->host_id_proof_public_path      = url('/').config('app.project.img_path.user_id_proof');
        $this->host_id_proof_image_base_path  = public_path().config('app.project.img_path.user_id_proof');
        $this->host_profile_image_public_path = url('/').config('app.project.img_path.user_photo');
        $this->host_profile_image_base_path   = public_path().config('app.project.img_path.user_photo');

        $this->module_url_path                = $this->admin_url_path."/host";
        $this->module_title                   = "Host";
        $this->module_view_folder             = "admin.host";
        $this->module_icon                    = "fa fa-server";
        $this->HostVerificationModel          = $hostverification_model;
        $this->BankDetailsModel               = $bankdetails_model;
        $this->UserModel                      = $user_model;
        $this->BaseModel                      = $user_model;
        $this->ReviewRatingModel              = $review_rating_model;
        $this->PropertyModel                  = $property_model;
        $this->EmailService                   = $email_service;
        $this->SMSService                     = $sms_service;
        $this->NotificationService            = $notification_service;
        $this->MobileAppNotification          = $mobileappnotification_service;
    }

    public function index()
    {
        $arr_host = [];

        $users_table            = $this->UserModel->getTable();
        $prefixed_users_table   = DB::getTablePrefix().$this->UserModel->getTable();
        $arr_hostuser           = DB::table($users_table)
        ->select(DB::raw( $prefixed_users_table.".id as id,".
            $prefixed_users_table.".first_name as first_name,".
            $prefixed_users_table.".last_name as last_name,".
            $prefixed_users_table.".user_name as user_name,".
            $prefixed_users_table.".email as email,".
            $prefixed_users_table.".country_code as country_code,".
            $prefixed_users_table.".mobile_number as mobile_number,".
            $prefixed_users_table.".status as status"
        ))
        ->where('user_type','4')
        ->orderBy($prefixed_users_table.'.id','DESC')
        ->get();

        $this->arr_data['arr_host']         = $arr_hostuser;
        $this->arr_data['objects']          = $arr_host;
        $this->arr_data['page_title']       = "Manage ".$this->module_title;
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_title']     = $this->module_title;
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
        return view($this->module_view_folder.'.index',$this->arr_data);
    }

    public function load_data(Request $request)
    {
        $UserData    =  $final_array=[]; 
        $column      = '';
        $user_name   = $request->input('user_name'); 
        $email       = $request->input('email'); 
        $keyword     = $request->input('keyword'); 

        if ($request->input('order')[0]['column'] == 1) {
            $column = "id";
        }
        if ($request->input('order')[0]['column'] == 2) {
            $column = "created_at";
        }
        if ($request->input('order')[0]['column'] == 3) {
            $column = "user_name";
        }
        if ($request->input('order')[0]['column'] == 4) {
            $column = "first_name";
        }
        if ($request->input('order')[0]['column'] == 5) {
            $column = "email";
        }
        if ($request->input('order')[0]['column'] == 6) {
            $column = "mobile_number";
        }
        if ($request->input('order')[0]['column'] == 7) {
            $column = "status";
        }
        /*if ($request->input('order')[0]['column'] == 8) {
            $column = "is_email_verified";
        }
        if ($request->input('order')[0]['column'] == 9) {
            $column = "is_mobile_verified";
        }*/

        $order = strtoupper($request->input('order')[0]['dir']);  

        $arr_data               = [];
        $users_table            = $this->UserModel->getTable();
        $prefixed_users_table   = DB::getTablePrefix().$this->UserModel->getTable();
        $arr_search_column      = $request->input('column_filter');

        $obj_data = DB::table($users_table)
                        ->select(DB::raw( $prefixed_users_table.".id as id,".
                            $prefixed_users_table.".first_name as first_name,".
                            $prefixed_users_table.".last_name as last_name,".
                            $prefixed_users_table.".user_name as user_name,".
                            $prefixed_users_table.".email as email,".
                            $prefixed_users_table.".country_code as country_code,".
                            $prefixed_users_table.".mobile_number as mobile_number,".
                            $prefixed_users_table.".status as status,".
                            $prefixed_users_table.".created_at as created_at,".
                            $prefixed_users_table.".is_email_verified as is_email_verified,".
                            $prefixed_users_table.".is_mobile_verified as is_mobile_verified"
                        ))
                        ->where('user_type','4')
                        ->orderBy($prefixed_users_table.'.id','DESC');

        if (isset($user_name) && $user_name != "") {
            $obj_data = $obj_data->where($users_table.'.id','=', base64_decode($user_name));
        }

        if (isset($email) && $email != "") {
            $obj_data = $obj_data->where($users_table.'.email','LIKE', '%'.$email.'%');
        }

        if(isset($keyword) && $keyword != "")
        {
            $obj_data = $obj_data->whereRaw("user_name LIKE '%".$keyword."%' OR first_name LIKE '%".$keyword."%' OR last_name LIKE '%".$keyword."%'  OR email LIKE '%".$keyword."%' OR country_code LIKE '%".$keyword."%' OR mobile_number LIKE '%".$keyword."%'");  
        }

        $count = count($obj_data->get());

        if ($order =='ASC' && $column=='') {
            $obj_data   = $obj_data->orderBy('id','DESC')->limit($_GET['length'])->offset($_GET['start']);
        }

        if ($order !='' && $column!='') {
            $obj_data = $obj_data->orderBy($column,$order)->limit($_GET['length'])->offset($_GET['start']);
        }

        $UserData = $obj_data->get();

        $resp['draw']            = $_GET['draw'];
        $resp['recordsTotal']    = $count;
        $resp['recordsFiltered'] = $count;
        $build_active_btn        = '' ; 

        if (count($UserData)>0) {
            $i = 0;

            foreach($UserData as $row) {
                
                if($row->status != null && $row->status == "0") {
                    $build_active_btn = '<a class="btn btn-sm btn-danger" title="Block" href="'.$this->module_url_path.'/unblock/'.base64_encode($row->id).'" 
                    onclick="return confirm_action(this,event,\'Do you really want to Unblock this host ?\')" >Block</a>';
                } elseif($row->status != null && $row->status == "1") {
                    $build_active_btn = '<a class="btn btn-sm btn-success" title="Unblock" href="'.$this->module_url_path.'/block/'.base64_encode($row->id).'" onclick="return confirm_action(this,event,\'Do you really want to block this host ?\')" >Unblock</a>';      
                }

                if ($row->is_email_verified != null && $row->is_email_verified == '0') {
                    $build_verify_btn = '<a class="btn btn-danger btn-sm show-tooltip call_loader" title="Verify"  href="'.$this->module_url_path.'/verify/'.base64_encode($row->id).'" onclick="return confirm_action(this,event,\'Do you really want to verify email for this host ?\')" ><i class="fa fa-square-o "></i></a>';
                } elseif($row->is_email_verified != null && $row->is_email_verified == '1') {
                    $build_verify_btn = '<a class="btn btn-success btn-sm show-tooltip call_loader" title="Un-Verify"  href="'.$this->module_url_path.'/unverify/'.base64_encode($row->id).'" onclick="return confirm_action(this,event,\'Do you really want to unverify email for this host ?\')" ><i class="fa fa-check-square-o "></i></a>';
                }

                if ($row->is_mobile_verified != null && $row->is_mobile_verified == '0') {
                    $build_verify_mobile_btn = '<a class="btn btn-danger btn-sm show-tooltip call_loader" title="Verify"  href="'.$this->module_url_path.'/verify_mobile/'.base64_encode($row->id).'" onclick="return confirm_action(this,event,\'Do you really want to verify mobile for this host ?\')" ><i class="fa fa-square-o "></i></a>';
                } elseif($row->is_mobile_verified != null && $row->is_mobile_verified == '1') {
                    $build_verify_mobile_btn = '<a class="btn btn-success btn-sm show-tooltip call_loader" title="Un-Verify"  href="'.$this->module_url_path.'/unverify_mobile/'.base64_encode($row->id).'" onclick="return confirm_action(this,event,\'Do you really want to unverify mobile for this host ?\')" ><i class="fa fa-check-square-o "></i></a>';
                }

                $build_view_action       = '';
                $built_view_href         = $this->module_url_path.'/view/'.base64_encode($row->id);
                $built_delete_href       = $this->module_url_path.'/delete/'.base64_encode($row->id);
                $built_bank_details_href = $this->module_url_path.'/bank_details/'.base64_encode($row->id);
                
                $transaction_href        = $this->admin_url_path.'/transaction?user_id='.base64_encode($row->id);
                $review_rating_href      = $this->module_url_path.'/review-rating/'.base64_encode($row->id);

                $new_booking_href        = $this->admin_url_path.'/booking/confirmed?host_user_id='.base64_encode($row->id);
                $completed_booking_href  = $this->admin_url_path.'/booking/completed?host_user_id='.base64_encode($row->id);
                $cancel_booking_href     = $this->admin_url_path.'/booking/cancel?host_user_id='.base64_encode($row->id);

                $build_view_action .= "&nbsp;<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' title='Bank Details' href='".$built_bank_details_href."'  data-original-title='View Bank Details'><i class='fa fa-university' ></i></a>";

                $build_view_action .= "&nbsp;<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' href='".$built_view_href."' title='View' data-original-title='View'><i class='fa fa-eye' ></i></a>";

                $build_view_action .= "&nbsp;<a class='btn btn-circle btn-to-danger btn-bordered btn-fill show-tooltip' title='Delete' href='".$built_delete_href."' onclick='return confirm_action(this,event,\"Do you really want to delete this host ?\")' ><i class='fa fa-trash'></i></a>";

                $build_view_action .= '&nbsp; <div class="btn-group dropup admin-drop"><button aria-expanded="false" data-toggle="dropdown" class="btn btn-info btn-outline dropdown-toggle waves-effect waves-light" type="button" title="More actions"> <i class="fa fa-list m-r-5"></i> <span class="caret"></span></button>
                <ul role="menu" class="dropdown-menu animated flipInY">
                <li><a href="'.$transaction_href.'"> <i class="ti-money"></i> Transactions</a></li>
                <li><a href="'.$review_rating_href.'"> <i class="ti-money"></i> Review & Ratings</a></li>
                <li><a href="'.$new_booking_href.'"> <i class="ti-money"></i> Confirmed Booking</a></li>
                <li><a href="'.$completed_booking_href.'"> <i class="ti-money"></i> Completed Booking</a></li>
                <li><a href="'.$cancel_booking_href.'"> <i class="ti-money"></i> Cancel Booking</a></li>
                </ul></div>';
                
                $country_code  = isset($row->country_code) && $row->country_code != '' ? $row->country_code : '';
                $mobile_number = isset($row->mobile_number) && $row->mobile_number != '' ? $row->mobile_number : 'N/A';

                $final_array[$i][0] = "<input type='checkbox' name='checked_record[]' id='checked_record' class='checked_record' value='".base64_encode($row->id)."'/>";
                $final_array[$i][1] = get_added_on_date($row->created_at);
                $final_array[$i][2] = isset($row->user_name) && $row->user_name!=''?$row->user_name:"N/A";
                $final_array[$i][3] = $row->first_name.' '.$row->last_name;
                $final_array[$i][4] = isset($row->email) && $row->email != ''?$row->email:'N/A';
                $final_array[$i][5] = $country_code.$mobile_number;
                $final_array[$i][6] = $build_active_btn;
                $final_array[$i][7] = $build_verify_btn;
                $final_array[$i][8] = $build_verify_mobile_btn;
                $final_array[$i][9] = $build_view_action;
                $i++;
            }
        }
        $resp['data'] = $final_array;
        echo str_replace("\/", "/",  json_encode($resp));exit;      
    }

    public function view($id)
    {
        $id = base64_decode($id);
        $arr_host_details = $arr_bank_details = $arr_host_verified = [];

        $obj_host = $this->UserModel->where('id',$id)->first();
        if($obj_host)
        {
            $arr_host_details  = $obj_host->toArray();
        }
        
        $obj_host_verified = $this->HostVerificationModel->where('user_id',$id)->where('status',1)->first();
        if($obj_host_verified)  
        {
            $arr_host_verified = $obj_host_verified->toArray();
        }   

        /*$obj_bank_details = $this->BankDetailsModel->where('user_id',$id)->where('selected',1)->get();
        if($obj_bank_details)   
        {
            $arr_bank_details = $obj_bank_details->toArray();
        }*/           

        $this->arr_data['arr_bank_details']               = $arr_bank_details;
        $this->arr_data['arr_host_verified']              = $arr_host_verified;
        $this->arr_data['arr_user']                       = $arr_host_details;
        $this->arr_data['page_title']                     = "View ".$this->module_title;
        $this->arr_data['module_icon']                    = $this->module_icon;
        $this->arr_data['module_title']                   = $this->module_title;
        $this->arr_data['module_url_path']                = $this->module_url_path;
        $this->arr_data['admin_panel_slug']               = $this->admin_panel_slug;
        $this->arr_data['profile_image_public_path']      = $this->profile_image_public_path;
        $this->arr_data['profile_image_base_path']        = $this->profile_image_base_path;
        $this->arr_data['host_id_proof_public_path']      = $this->host_id_proof_public_path;
        $this->arr_data['host_id_proof_image_base_path']  = $this->host_id_proof_image_base_path;
        $this->arr_data['host_profile_image_public_path'] = $this->host_profile_image_public_path;
        $this->arr_data['host_profile_image_base_path']   = $this->host_profile_image_base_path;

        return view($this->module_view_folder.'.view',$this->arr_data);
    }

    public function review_rating($id)
    {
        $this->arr_data['sub_module_url_path']  = $this->module_url_path.'/review-rating/'.$id;

        $this->arr_data['page_title']       = "Manage Review & Ratings";
        $this->arr_data['section_title']    = "Manage ".$this->module_title;
        $this->arr_data['section_icon']     = $this->module_icon;
        $this->arr_data['module_icon']      = "fa-star-half-empty";
        $this->arr_data['module_title']     = $this->module_title;
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;

        return view($this->module_view_folder.'.review-rating',$this->arr_data);
    }

    public function load_review_ratings(Request $request)
    {
        $UserData       = $final_array=[]; 
        $column         = '';
        $user_id        = $request->input('user_id');
        $property_name  = $request->input('property_name');
        
        if ($request->input('order')[0]['column'] == 1) 
        {
            $column = "id";
        }           
        if ($request->input('order')[0]['column'] == 2) 
        {
            $column = "property_name";
        } 
        if ($request->input('order')[0]['column'] == 3) 
        {
            $column = "rating";
        } 
        
        $order = strtoupper($request->input('order')[0]['dir']);  

        $arr_data                 = [];
        $review_table             = $this->ReviewRatingModel->getTable();
        
        $prefixed_property_table  = DB::getTablePrefix().$this->PropertyModel->getTable();
        $obj_data                 = DB::table($review_table)
        ->select(DB::raw( 
            $review_table.".*,".
            $prefixed_property_table.".property_name as property_name"
        ))
        ->Join($prefixed_property_table,$prefixed_property_table.".id",' = ',$review_table.'.property_id')
        ->where($review_table.'.rating_user_id',base64_decode($user_id));

        if(isset($property_name) && $property_name != "")
        {
            $obj_data = $obj_data->where($prefixed_property_table.'.property_name','LIKE', '%'.$property_name.'%'); 
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
        $build_active_btn        = '' ; 

        if(count($UserData)>0)
        {
            $i = 0;

            foreach($UserData as $row)
            {
                $obj_ratings        = $this->ReviewRatingModel->where('property_id','=',$row->property_id)->get();

                $total          = 0;
                $count          = 0;
                $tmp_str_rating = ''; 
                $count          = count($obj_ratings);

                foreach ($obj_ratings as $rating) 
                {
                    $total  += floatval($rating['rating']);
                }

                $no_reviews     = $total/$count;
                for($j=1;$j<=$no_reviews;$j++)
                {
                    $tmp_str_rating.='<img src="'.url('/').'/web_admin/images/star1.png" />&nbsp;'; 
                }

                $whole           = floor($no_reviews);     
                $fraction        = $no_reviews - $whole;

                if($fraction >= 0.5)
                {
                    $tmp_str_rating .= '<img src="'.url('/').'/web_admin/images/half-star.png" />&nbsp;'; 
                }
                else if($fraction < 0.5 && $fraction > 0.0)
                {
                    $tmp_str_rating  .= '<img src="'.url('/').'/web_admin/images/star2.png" />&nbsp;';
                }

                $temp  = 5-$no_reviews;
                
                for($k = 1 ; $k <= $temp ; $k++)
                { 
                    $tmp_str_rating.='<img src="'.url('/').'/web_admin/images/star2.png" />&nbsp;';
                }

                $rating_stars = $tmp_str_rating;


                if($row->status != null && $row->status == "0")
                {
                    $build_active_btn = '<a class="btn btn-sm btn-danger" title="Block" href="'.$this->module_url_path.'/property_review_unblock/'.base64_encode($row->property_id).'" 
                    onclick="return confirm_action(this,event,\'Do you really want to Unblock this record ?\')" >Block</a>';
                }
                elseif($row->status != null && $row->status == "1")
                {
                    $build_active_btn = '<a class="btn btn-sm btn-success" title="Unblock" href="'.$this->module_url_path.'/property_review_block/'.base64_encode($row->property_id).'" onclick="return confirm_action(this,event,\'Do you really want to block this record ?\')" >Unblock</a>';
                }
                
                $build_view_action =''; 

                $built_view_href    = $this->module_url_path.'/review-rating/view/'.base64_encode($row->property_id);
                
                $build_view_action .= "&nbsp;<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' href='".$built_view_href."' title='View' data-original-title='View'>
                <i class='fa fa-eye' ></i></a>";

                $property_name        = ''.wordwrap($row->property_name,50,"<br>\n").'';

                $final_array[$i][0] = "<input type='checkbox' name='checked_record[]' id='checked_record' class='checked_record' value='".base64_encode($row->property_id)."'/>";
                $final_array[$i][1] = $property_name;
                $final_array[$i][2] = $rating_stars;
                $final_array[$i][3] = $build_active_btn;
                $final_array[$i][4] = $build_view_action;
                $i++;
            }
        }

        $resp['data'] = $final_array;
        echo str_replace("\/", "/",  json_encode($resp));exit;      
    }
    public function property_review_block($enc_id = FALSE)
    {
        $obj_property_review = $this->ReviewRatingModel->where('property_id','=',base64_decode($enc_id))->get();

        if(count($obj_property_review)>0)
        {
            foreach($obj_property_review as $row)
            {
                $update = $this->ReviewRatingModel->where('id',$row->id)->update(['status'=>'0']);
            }

            return redirect()->back()->with('success','Deactivated Successfully.');
        }
    }

    public function property_review_unblock($enc_id = FALSE)
    {
        $obj_property_review = $this->ReviewRatingModel->where('property_id','=',base64_decode($enc_id))->get();

        if(count($obj_property_review)>0)
        {
            foreach($obj_property_review as $row)
            {
                $update = $this->ReviewRatingModel->where('id',$row->id)->update(['status'=>'1']);
            }
            return redirect()->back()->with('success','Activated Successfully.');
        }
    }

    public function property_multi_action(Request $request)
    {
        $arr_rules                   = array();
        $arr_rules['multi_action']   = "required";
        $arr_rules['checked_record'] = "required";

        $validator  = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            Session::flash('Please Select '.$this->module_title.' To Perform Multi Actions');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $multi_action   = $request->input('multi_action');
        $checked_record = $request->input('checked_record');

        /* Check if array is supplied*/
        if(is_array($checked_record) && sizeof($checked_record)<=0)
        {          
            Session::flash('error', 'Problem Occurred, While Doing Multi Action');
            return redirect()->back();
        }

        foreach ($checked_record as $key => $record_id) 
        {  
            $obj_property_review = $this->ReviewRatingModel->where('property_id','=',base64_decode($record_id))->get();

            if($multi_action == "delete")
            {
                $resDelete = $this->perform_delete(base64_decode($record_id));    
                Session::flash('success', $this->module_title. ' Deleted Successfully');
            } 
            elseif($multi_action == "activate")
            {
                if(count($obj_property_review)>0)
                {
                    foreach($obj_property_review as $row)
                    {
                        $this->ReviewRatingModel->where('id',$row->id)->update(['status'=>'1']);
                    }
                }

                Session::flash('success', 'Review & Ratings unblocked Successfully');
            }
            elseif($multi_action == "deactivate")
            {
                $obj_property_review = $this->ReviewRatingModel->where('property_id','=',base64_decode($record_id))->get();

                if(count($obj_property_review)>0)
                {
                    foreach($obj_property_review as $row)
                    {
                        $this->ReviewRatingModel->where('id',$row->id)->update(['status'=>'0']);
                    }
                }
                
                Session::flash('success','Review & Ratings blocked Successfully');
            }
        }      
        return redirect()->back();
    }
    

    public function bank_details($id)
    {
        $id = base64_decode($id);
        $arr_bank_details = [];

        $obj_bank_details = $this->BankDetailsModel->where('user_id',$id)->get();   
        if($obj_bank_details)   
        {
            $arr_bank_details = $obj_bank_details->toArray();
        }       

        $this->arr_data['arr_bank_details'] = $arr_bank_details;
        $this->arr_data['page_title']       = $this->module_title." Bank Details";
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_icon_page'] = "fa-university";
        $this->arr_data['module_title']     = $this->module_title;
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;

        return view($this->module_view_folder.'.bank-details',$this->arr_data); 
    }

    public function verify($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_verify(base64_decode($enc_id)))
        {
            Session::flash('success','Email verified successfully');
        }
        else
        {
            Session::flash('error','Problem Occured While verifying email');
        }

        return redirect()->back();
    }

    public function unverify($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_unverify(base64_decode($enc_id)))
        {
            Session::flash('success','Email unverified successfully');
        }
        else
        {
            Session::flash('error','Problem occured while unverifying email');
        }

        return redirect()->back();
    }
    public function verify_mobile($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_mobile_verify(base64_decode($enc_id)))
        {
            Session::flash('success','Mobile number verified successfully');
        }
        else
        {
            Session::flash('error','Problem occured while verifying mobile number');
        }

        return redirect()->back();
    }

    public function unverify_mobile($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_mobile_unverify(base64_decode($enc_id)))
        {
            $headings = 'Mobile unverify by admin';
            $content  = 'Your mobile number is unverify by admin, Please contact to admin.';
            $user_id  = base64_decode($enc_id);
            $status   = $this->MobileAppNotification->send_app_notification($headings, $content, base64_decode($enc_id));

            Session::flash('success',' Mobile number unverified successfully');
        }
        else
        {
            Session::flash('error','Problem Occured while unverifying mobile number');
        }

        return redirect()->back();
    }

    public function  perform_verify($id)
    {   
        $static_page = $this->BaseModel->where('id',$id)->first();
        
        if($static_page)
        {
            return $static_page->update(['is_email_verified'=>'1']);
        }

        return FALSE;
    } 
    public function  perform_unverify($id)
    {   
        $static_page = $this->BaseModel->where('id',$id)->first();
        
        if($static_page)
        {
            return $static_page->update(['is_email_verified'=>'0']);
        }

        return FALSE;
    }
     public function  perform_mobile_verify($id)
    {   
        $static_page = $this->BaseModel->where('id',$id)->first();
        
        if($static_page)
        {
            return $static_page->update(['is_mobile_verified'=>'1']);
        }

        return FALSE;
    } 
    public function  perform_mobile_unverify($id)
    {   
        $static_page = $this->BaseModel->where('id',$id)->first();
        
        if($static_page)
        {
            return $static_page->update(['is_mobile_verified'=>'0']);
        }

        return FALSE;
    }


    public function multi_action(Request $request)
    {
        $arr_rules = array();
        $arr_rules['multi_action']   = "required";
        $arr_rules['checked_record'] = "required";

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            Session::flash('Please Select '.$this->module_title.' To Perform Multi Actions');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $multi_action   = $request->input('multi_action');
        $checked_record = $request->input('checked_record');

        /* Check if array is supplied*/
        if(is_array($checked_record) && sizeof($checked_record)<=0)
        {          
            Session::flash('error', 'Problem Occurred, While Doing Multi Action');
            return redirect()->back();
        }

        foreach ($checked_record as $key => $record_id) 
        {  
            if($multi_action=="delete")
            {
                $resDelete = $this->perform_delete(base64_decode($record_id));    
                Session::flash('success', $this->module_title. ' Deleted Successfully');
            } 
            elseif($multi_action=="activate")
            {
               $resActive = $this->perform_unblock(base64_decode($record_id)); 
               Session::flash('success', $this->module_title. ' unblocked Successfully');
            }
            elseif($multi_action=="deactivate")
            {
               $resDeactive = $this->perform_block(base64_decode($record_id));   
               Session::flash('success', $this->module_title. ' blocked Successfully');
            }
        }      
        return redirect()->back();
    }

    public function unblock($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }
        if($this->perform_unblock(base64_decode($enc_id)))
        {
            
            Session::flash('success', $this->module_title. ' Unblocked Successfully');
            return redirect()->back();
        }
        else
        {
            Session::flash('error', 'Problem Occured While '.$this->module_title.' Activation ');
        }
        return redirect()->back();
    }

    public function block($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_block(base64_decode($enc_id)))
        {
            $headings = 'blocked account by admin';
            $content  = 'Your account is blocked by admin, Please contact to admin.';
            $user_id  = base64_decode($enc_id);
            $status   = $this->MobileAppNotification->send_app_notification($headings, $content, base64_decode($enc_id));
            
            Session::flash('success', $this->module_title. ' blocked Successfully');
        }
        else
        {
            Session::flash('error', 'Problem Occured While '.$this->module_title.' Deactivation ');
        }
        return redirect()->back();
    }

    public function delete($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_delete(base64_decode($enc_id)))
        {
            Session::flash('success', $this->module_title. ' Deleted Successfully');
        }
        else
        {
            Session::flash('error', 'Problem Occured While '.$this->module_title.' Deletion ');
        }
        return redirect()->back();
    }


    public function perform_unblock($id)
    {
        $responce = $this->BaseModel->where('id',$id)->first();          
        if($responce)
        {
            return $responce->update(['status'=>'1']);
        }
        return FALSE;
    }

    public function perform_block($id)
    {
        $responce = $this->BaseModel->where('id',$id)->first();
        if($responce)
        {
            return $responce->update(['status'=>'0']);
        }
        return FALSE;
    }

    public function perform_delete($id)
    {
        $delete = $this->BaseModel->where('id',$id)->delete();
        
        if($delete)
        {
            return TRUE;
        }

        return FALSE;
    }

}
