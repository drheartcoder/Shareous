<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Common\Services\EmailService;
use App\Common\Services\SMSService;
use App\Common\Services\NotificationService;
use App\Common\Services\MobileAppNotification;

use App\Models\AmenitiesModel;
use App\Models\PropertyModel;
use App\Models\PropertyImagesModel;
use App\Models\PropertyBedsArrangmentModel;
use App\Models\PropertyRulesModel;
use App\Models\PropertyAminitiesModel;
use App\Models\PropertyUnavailabilityModel;

use App\Common\Traits\MultiActionTrait;

use Validator;
use Session;
use Auth;
use Hash;
use DB;
use Datatables;

class PropertyController extends Controller
{
    //use MultiActionTrait;
    function __construct(   
                            PropertyModel               $property_model,
                            UserModel                   $user_model,
                            EmailService                $email_service,
                            SMSService                  $sms_service,
                            NotificationService         $notification_service,
                            MobileAppNotification       $mobileappnotification_service,
                            AmenitiesModel              $aminities,                         
                            PropertyImagesModel         $property_images_model,
                            PropertyBedsArrangmentModel $property_beds_arrangment_model,
                            PropertyRulesModel          $property_rules_model,
                            PropertyAminitiesModel      $property_aminities_model,
                            PropertyUnavailabilityModel $property_unavailibitity_model
                        )
    {
        $this->arr_data                    = [];
        $this->admin_panel_slug            = config('app.project.admin_panel_slug');
        $this->admin_url_path              = url(config('app.project.admin_panel_slug'));
        $this->property_image_public_path  = url('/').config('app.project.img_path.property_image');
        $this->property_image_base_path    = public_path().config('app.project.img_path.property_image');
        $this->module_url_path             = $this->admin_url_path."/property";
        $this->module_title                = "Properties";
        $this->module_view_folder          = "admin.property";
        $this->module_icon                 = "fa fa-home";
        $this->AmenitiesModel              = $aminities;
        $this->EmailService                = $email_service;
        $this->SMSService                  = $sms_service;
        $this->NotificationService         = $notification_service;
        $this->MobileAppNotification       = $mobileappnotification_service;
        $this->PropertyImagesModel         = $property_images_model;
        $this->PropertyBedsArrangmentModel = $property_beds_arrangment_model;
        $this->PropertyRulesModel          = $property_rules_model;
        $this->PropertyAminitiesModel      = $property_aminities_model;
        $this->PropertyUnavailabilityModel = $property_unavailibitity_model;
        $this->UserModel                   = $user_model;   
        $this->PropertyModel               = $property_model;   
        $this->BaseModel                   = $property_model;

    }

    public function all()
    {
        $arr_property = [];
             
        $this->arr_data['arr_property']     = $arr_property;
        $this->arr_data['admin_status']     = 0;
        $this->arr_data['page_title']       = str_singular("Manage All ".$this->module_title);
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_title']     = "All ".str_singular($this->module_title);
        $this->arr_data['page_icon']        = 'fa-list';
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
        return view($this->module_view_folder.'.index',$this->arr_data);
    }

    public function pending()
    {
        $arr_property = [];

        $this->arr_data['arr_property']     = $arr_property; 
        $this->arr_data['admin_status']     = 1;
        $this->arr_data['page_title']       = str_singular("Manage Pending ".$this->module_title);
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_title']     = "Pending ".str_singular($this->module_title);
        $this->arr_data['page_icon']        = 'fa-list';
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
        return view($this->module_view_folder.'.index',$this->arr_data);
    }

    public function confirmed()
    {
        $arr_property = [];

        $this->arr_data['arr_property']     = $arr_property; 
        $this->arr_data['admin_status']     = 2;
        $this->arr_data['page_title']       = str_singular("Manage Confirm ".$this->module_title);
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_title']     = "Confirm ".str_singular($this->module_title);
        $this->arr_data['page_icon']        = 'fa-list';
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
        return view($this->module_view_folder.'.index',$this->arr_data);
    }

    public function rejected()
    {
        $arr_property = [];

        $this->arr_data['arr_property']     = $arr_property; 
        $this->arr_data['admin_status']     = 3;
        $this->arr_data['page_title']       = str_singular("Manage Reject ".$this->module_title);
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_title']     = "Reject ".str_singular($this->module_title);
        $this->arr_data['page_icon']        = 'fa-list';
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
        return view($this->module_view_folder.'.index',$this->arr_data);
    }

    public function reject_permanant()
    {
        $arr_property = [];

        $this->arr_data['arr_property']     = $arr_property; 
        $this->arr_data['admin_status']     = 4;
        $this->arr_data['page_title']       = str_singular("Manage Permanant Rejected ".$this->module_title);
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_title']     = "Permanant Rejected ".str_singular($this->module_title);
        $this->arr_data['page_icon']        = 'fa-list';
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
        return view($this->module_view_folder.'.index',$this->arr_data);
    }

    public function load_data(Request $request)
    {
        $UserData = $final_array = [];
        $column   = '';
        $keyword  = $request->input('keyword');
        $property = $request->input('property');

        if ($request->input('order')[0]['column'] == 1) {
            $column = "id";
        }
        if ($request->input('order')[0]['column'] == 2) {
            $column = "property_name";
        }
        if ($request->input('order')[0]['column'] == 3) {
            $column = "email";
        }
        if ($request->input('order')[0]['column'] == 4) {
            $column = "address";
        }
        if ($request->input('order')[0]['column'] == 5) {
            $column = "created_at";
        }

        $order = strtoupper($request->input('order')[0]['dir']);
        $admin_status_id = $request->input('admin_status');

        if($admin_status_id == 0) {
            $arr_status_id = ['1','2','3','4'];
        }
        else {
            $arr_status_id = [$admin_status_id];
        }

        $property_table          = $this->PropertyModel->getTable();   
        $prefixed_property_table = DB::getTablePrefix().$this->PropertyModel->getTable();
            
        $user_table              = $this->UserModel->getTable();   
        $prefixed_user_table     = DB::getTablePrefix().$this->UserModel->getTable();

        $obj_data = DB::table($property_table)
                        ->select(DB::raw( 
                                $prefixed_property_table.".id as id,".
                                $prefixed_property_table.".user_id as user_id,".
                                $prefixed_property_table.".is_featured as is_featured,".
                                $prefixed_property_table.".property_name as property_name,".
                                $prefixed_property_table.".admin_status as admin_status,".
                                $prefixed_property_table.".address as address,".
                                "DATE_FORMAT(".$prefixed_property_table.".created_at, '%Y-%m-%d') as created_at,".
                                $prefixed_user_table.".id as user_id,".
                                $prefixed_user_table.".email as email"
                            ))
                        ->whereIn('admin_status',$arr_status_id)
                        ->where('property_status',1)
                        ->Join($prefixed_user_table,$prefixed_user_table.".id",' = ',$prefixed_property_table.'.user_id');

        if($property != ''){
            $obj_data = $obj_data->where($prefixed_property_table.'.id',base64_decode($property));   
        }

        if($keyword != '') {
            $obj_data = $obj_data->whereRaw("".$prefixed_property_table.".property_name LIKE '%".$keyword."%' OR ".$prefixed_property_table.".address LIKE '%".$keyword."%' OR ".$prefixed_user_table.".email LIKE '%".$keyword."%'");
        }            

        $count = count($obj_data->get());

        if($order == 'ASC' && $column == '') {
            $obj_data = $obj_data->orderBy('id','DESC')->limit($_GET['length'])->offset($_GET['start']);
        }

        if( $order != '' && $column != '' ) {
            $obj_data = $obj_data->orderBy($column,$order)->limit($_GET['length'])->offset($_GET['start']);
        }

        $UserData                = $obj_data->get();
        $resp['draw']            = $_GET['draw'];
        $resp['recordsTotal']    = $count;
        $resp['recordsFiltered'] = $count;
        $admin_status            = '' ;

        if(count($UserData) > 0) {
            $i = 0;
            foreach($UserData as $data) {
                if($admin_status_id == 0) {
                    $admin_status = 'NA';
                    if($data->admin_status != null) {
                        if($data->admin_status == 1) {
                            $admin_status = '<span class="badge badge-info" style="padding: 9px;">Pending</span>';
                        }
                        elseif($data->admin_status == 2) {
                            $admin_status = '<span class="badge badge-success" style="padding: 9px;">Confirm</span>';
                        }
                        elseif($data->admin_status == 3) {
                            $admin_status = '<span class="badge badge-warning" style="padding: 9px;">Rejected</span>';
                        }
                        elseif($data->admin_status == 4) {
                            $admin_status = '<span class="badge badge-important" style="padding: 9px;">Permanant Rejected</span>';
                        }
                    }
                    else {
                        $admin_status = 'NA';
                    }
                    
                    /*Changes by kavita*/
                    if ($data->is_featured == 'no') {
                        $featured_add_href  = $this->module_url_path.'/add-featured/'.base64_encode($data->id);
                        $is_featured_button = "<a class='btn btn-circle btn-danger btn-bordered show-tooltip' href='".$featured_add_href."' data-original-title='Unfeatured'><i class='fa fa-close' ></i></a>";
                    }
                    else {
                        $featured_remove_href = $this->module_url_path.'/remove-featured/'.base64_encode($data->id);
                        $is_featured_button   = "<a class='btn btn-circle btn-success btn-bordered show-tooltip' href='".$featured_remove_href."'  data-original-title='Featured'><i class='fa fa-check' ></i></a>";
                    }
                    /*End */

                    $built_view_href   = $this->module_url_path.'/view/'.base64_encode($data->id);
                    $built_view_button = "<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' href='".$built_view_href."'  data-original-title='View'><i class='fa fa-eye' ></i></a>";
                    $combile_buttons   = $built_view_button;
                }
                elseif($admin_status_id == 1) {
                    $built_view_href        = $this->module_url_path.'/view/'.base64_encode($data->id);
                    $built_confirm_href     = $this->module_url_path.'/change_status/'.base64_encode($data->id).'/2';
                    $built_reject_href      = $this->module_url_path.'/change_status/'.base64_encode($data->id).'/3';
                    $built_reject_perm_href = $this->module_url_path.'/change_status/'.base64_encode($data->id).'/4';

                    /*Changes by kavita*/
                    if ($data->is_featured == 'no') {
                        $featured_add_href  = $this->module_url_path.'/add-featured/'.base64_encode($data->id);
                        $is_featured_button = "<a class='btn btn-circle btn-danger btn-bordered show-tooltip' href='".$featured_add_href."' data-original-title='Unfeatured'><i class='fa fa-close' ></i></a>";
                    }
                    else {
                        $featured_remove_href = $this->module_url_path.'/remove-featured/'.base64_encode($data->id);
                        $is_featured_button   = "<a class='btn btn-circle btn-success btn-bordered show-tooltip' href='".$featured_remove_href."'  data-original-title='Featured'><i class='fa fa-check' ></i></a>";
                    }
                    /*End */
                   
                    $built_view_button        = "<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' href='".$built_view_href."'  data-original-title='View'><i class='fa fa-eye' ></i></a>";
                    $built_confirm_button     = '<a class="btn btn-sm btn-success" title="Confirm" href="'.$built_confirm_href.'" onclick="return confirm_action(this,event,\'Do you really want to confirm this property ?\')" >Confirm</a>';
                    $built_reject_button      = '<a class="btn btn-sm btn-warning show-tooltip" title="Reject" href="'.$built_reject_href.'" onclick="return confirm_action(this,event,\'Do you really want to reject this property ?\')" >Reject</a>';
                    $built_reject_perm_button = '<a class="btn btn-sm btn-danger show-tooltip" title="Permanant Reject" href="'.$built_reject_perm_href.'" onclick="return confirm_action(this,event,\'Do you really want to permanant reject this property ?\')" >Permanant Reject</a>';
                    $admin_status             = $built_confirm_button.' '.$built_reject_button.' '.$built_reject_perm_button;
                    $combile_buttons          = $built_view_button;
                }
                elseif($admin_status_id == 2)
                {
                     /*Changes by kavita*/
                    if ($data->is_featured == 'no') 
                    {
                       $featured_remove_href   = $this->module_url_path.'/add-featured/'.base64_encode($data->id);

                       $is_featured_button     = "<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' href='".$featured_remove_href."' data-original-title='Unfeatured'><i class='fa fa-close' ></i></a>";
                    }
                    else
                    {
                        $featured_add_href      = $this->module_url_path.'/remove-featured/'.base64_encode($data->id);
                        $is_featured_button = "<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' href='".$featured_add_href."' data-original-title='Featured'><i class='fa fa-check' ></i></a>";
                    }
                    /*End */

                    $built_view_href = $this->module_url_path.'/view/'.base64_encode($data->id);

                    $built_view_button = "<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' href='".$built_view_href."'  data-original-title='View'><i class='fa fa-eye' ></i></a>";
                      $combile_buttons = $built_view_button;

                      $admin_status = '<span class="badge badge-success" style="padding: 9px;">Confirmed</span>';
                }
                elseif($admin_status_id == 3)
                {
                   /*Changes by kavita*/
                     if ($data->is_featured == 'no') 
                    {
                       $featured_remove_href   = $this->module_url_path.'/add-featured/'.base64_encode($data->id);

                       $is_featured_button     = "<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' href='".$featured_remove_href."' data-original-title='Unfeatured'><i class='fa fa-close' ></i></a>";
                    }
                    else
                    {
                        $featured_add_href      = $this->module_url_path.'/remove-featured/'.base64_encode($data->id);
                        $is_featured_button = "<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' href='".$featured_add_href."' data-original-title='Featured'><i class='fa fa-check' ></i></a>";
                    }
                    /*End */

                    $built_view_href    = $this->module_url_path.'/view/'.base64_encode($data->id);

                    $built_view_button  = "<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' href='".$built_view_href."'  data-original-title='View'><i class='fa fa-eye' ></i></a>";
                    $combile_buttons    = $built_view_button;

                    $admin_status       = '<span class="badge badge-warning" style="padding: 9px;">Rejected</span>';
                }
                elseif($admin_status_id == 4)
                {
                    /*Changes by kavita*/
                    if ($data->is_featured == 'no') 
                    {
                       $featured_add_href      = $this->module_url_path.'/add-featured/'.base64_encode($data->id);
                       $is_featured_button     = "<a class='btn btn-circle btn-danger btn-bordered show-tooltip' href='".$featured_add_href."' data-original-title='Unfeatured'><i class='fa fa-close' ></i></a>";
                    }
                    else
                    {
                        $featured_remove_href   = $this->module_url_path.'/remove-featured/'.base64_encode($data->id);
                       
                        $is_featured_button     = "<a class='btn btn-circle btn-success btn-bordered show-tooltip' href='".$featured_remove_href."'  data-original-title='Featured'><i class='fa fa-check' ></i></a>";
                    }
                    /*End */
                    $built_view_href    = $this->module_url_path.'/view/'.base64_encode($data->id);

                    $built_view_button  = "<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' href='".$built_view_href."'  data-original-title='View'><i class='fa fa-eye' ></i></a>";
                    $combile_buttons    = $built_view_button;

                    $admin_status       = '<span class="badge badge-important" style="padding: 9px;">Permanant Rejected</span>';
                }   

                $final_array[$i][0] = "<input type='checkbox' name='checked_record[]' id='checked_record' class='checked_record' value='".base64_encode($data->id)."'/>";
                $final_array[$i][1] = $data->property_name;
                $final_array[$i][2] = $data->email;
                $final_array[$i][3] = isset($data->address) && $data->address != '' ? $data->address : 'N/A';
                $final_array[$i][4] = isset($data->created_at) && $data->created_at!='' ? get_added_on_date($data->created_at):'N/A';
                $final_array[$i][5] = $admin_status;
                if($admin_status_id == 2) {
                    $final_array[$i][6] = $is_featured_button;
                    $final_array[$i][7] = $combile_buttons;
                }
                else {
                    $final_array[$i][6] = $combile_buttons;
                }
                $i++;   
            }
        }

        $resp['data'] = $final_array;
        echo str_replace("\/", "/",  json_encode($resp));exit;
    }
  
    public function get_property_data($request,$arr_status_id)
    {
      
        if(isset($arr_status_id) && sizeof($arr_status_id)>0)
        {
            $property_table = $this->PropertyModel->getTable();   
            $prefixed_property_table = DB::getTablePrefix().$this->PropertyModel->getTable();
            
            $user_table = $this->UserModel->getTable();   
            $prefixed_user_table = DB::getTablePrefix().$this->UserModel->getTable();

            $obj_property_data = DB::table($property_table)
                                            ->select(DB::raw( $prefixed_property_table.".id as id,".
                                                              $prefixed_property_table.".user_id as user_id,".
                                                              $prefixed_property_table.".property_name as property_name,".
                                                              $prefixed_property_table.".admin_status as admin_status,".
                                                              $prefixed_property_table.".country as country,".                  
                                                             // $prefixed_property_table.".created_at as created_at,".  
                                                              "DATE_FORMAT(".$prefixed_property_table.".created_at, '%Y-%m-%d') as created_at,".                
                                                              $prefixed_user_table.".id as user_id,".
                                                              $prefixed_user_table.".email as email"                                            
                                                            ))
                                           ->whereIn('admin_status',$arr_status_id)
                                           ->where('property_status',1)
                                           ->Join($prefixed_user_table,$prefixed_user_table.".id",' = ',$prefixed_property_table.'.user_id');


            $arr_search_column = $request->input('column_filter');

            if(isset($arr_search_column['q_property_name']) && $arr_search_column['q_property_name']!="")
            {
                $search_term = $arr_search_column['q_property_name'];
                $obj_property_data = $obj_property_data->where($prefixed_property_table.'.property_name','LIKE', '%'.$search_term.'%');   
            }

            if(isset($arr_search_column['q_country']) && $arr_search_column['q_country']!="")
            {
                $search_term = $arr_search_column['q_country'];
                $obj_property_data = $obj_property_data->where($prefixed_property_table.'.country','LIKE', '%'.$search_term.'%');   
            }

            if(isset($arr_search_column['q_email']) && $arr_search_column['q_email']!="")
            {
                $search_term = $arr_search_column['q_email'];
                $obj_property_data = $obj_property_data->where($prefixed_user_table.'.email','LIKE', '%'.$search_term.'%');
            }            

            return $obj_property_data;
        }
        
         return false;
    }


    public function multi_action(Request $request)
    {
        $arr_rules = array();
        $arr_rules['multi_action'] = "required";
        $arr_rules['checked_record'] = "required";

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            Flash::error('Please Select '.$this->module_title.' To Perform Multi Actions');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $multi_action = $request->input('multi_action');
        $checked_record = $request->input('checked_record');

        /* Check if array is supplied*/
        if(is_array($checked_record) && sizeof($checked_record)<=0)
        {
            Flash::error('Problem Occurred, While Doing Multi Action');
            return redirect()->back();
        }

        foreach ($checked_record as $key => $record_id) 
        {  
            if($multi_action=="2")
            {
                $this->multi_change_property_status($record_id,'2');
            } 
            elseif($multi_action=="3")
            {
                $this->multi_change_property_status($record_id,'3');
            }
            elseif($multi_action=="4")
            {
                $this->multi_change_property_status($record_id,'4');
            }
        }

        if($multi_action=="2")
        {
            return redirect($this->module_url_path.'/pending')->with('success',$this->module_title.' Confirmed Successfully');
        } 
        elseif($multi_action=="3")
        {
            return redirect($this->module_url_path.'/pending')->with('success',$this->module_title.' Rejected Successfully');
        }
        elseif($multi_action=="4")
        {
            return redirect($this->module_url_path.'/pending')->with('success',$this->module_title.' Permanantaly Rejected Successfully');
        }
    }

    public function multi_change_property_status($enc_property_id, $status)
    {
        $id = base64_decode($enc_property_id);
        $proeprty_name = "";

        $property_status  = $this->PropertyModel->where('id',$id)->update(['admin_status'=>$status]);

        if($property_status) 
        {
            $obj_user         = $this->PropertyModel->where('id',$id)->with('user_details')->first();
          
            if(isset($obj_user) && $obj_user!=null)
            {
                $arr_user = $obj_user->toArray();
            }
            if($status == 2)
            {
                $admin_status = 'Confirm';
            }

            if(isset($arr_user['property_name']) && $arr_user['property_name'] != ""){
            $proeprty_name = $arr_user['property_name'];
            } 

            $type     = get_notification_type_of_user($arr_user['user_id']);

            $arr_built_content      = array(
                                             'USER_NAME'      => isset($arr_user['user_details']['display_name'])?ucfirst($arr_user['user_details']['display_name']):'NA',
                                             'STATUS'         => $admin_status
                                            );
            $arr_notify_data['arr_built_content']   = $arr_built_content;   
            $arr_notify_data['notify_template_id']  = '6';          
            $arr_notify_data['user_id']             = $arr_user['user_id'];

            $arr_notify_data['sender_id']           = '1';
            $arr_notify_data['receiver_id']         = $arr_user['user_id'];
            $arr_notify_data['sender_type']         = '2';
            $arr_notify_data['receiver_type']       = '4';
            $arr_notify_data['url']                 = '/property/listing';

            $notification_status                    = $this->NotificationService->send_notification($arr_notify_data);

            if(isset($type) && !empty($type))
            {
                // for mail
                if($type['notification_by_email'] == 'on')
                {
                    $arr_built_content         = [
                                        'USER_NAME'        => isset($arr_user['user_details']['display_name'])?ucfirst($arr_user['user_details']['display_name']):'NA',   
                                        'Email'            => isset($arr_user['user_details']['email'])?ucfirst($arr_user['user_details']['email']):'NA' ,  
                                         'ID'              => isset($arr_user['id'])?$arr_user['id']:'NA',
                                        'STATUS'           => $admin_status,  
                                        'PROPERTY_NAME'    => $proeprty_name, 
                                        'PROJECT_NAME'     => config('app.project.name')
                                     ];
                    $arr_mail_data                         = [];
                    $arr_mail_data['email_template_id']    = '9';
                    $arr_mail_data['arr_built_content']    = $arr_built_content;
                    $arr_mail_data['user']                 = ['email' => isset($arr_user['user_details']['email'])?ucfirst($arr_user['user_details']['email']):'NA', 'first_name' => isset($arr_user['user_details']['display_name'])?ucfirst($arr_user['user_details']['display_name']):'NA'];

                    $status                                = $this->EmailService->send_mail($arr_mail_data);
                }

                // for sms
                if($type['notification_by_sms'] == 'on')
                {
                    $country_code  = isset($arr_user['user_details']['country_code']) ? $arr_user['user_details']['country_code'] :' ';
                    $mobile_number = isset($arr_user['user_details']['mobile_number']) ? $arr_user['user_details']['mobile_number'] : '';

                    $arr_sms_data                  = [];
                    $arr_sms_data['msg']           = "Property Status changed successfully";
                    $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                    $status                        = $this->SMSService->send_SMS($arr_sms_data);
                }

                // for push notification
                if($type['notification_by_push'] == 'on')
                {
                    $headings = 'Property Status changed successfully';
                    $content  = 'Property Status changed successfully.';
                    $user_id  = $arr_user['id'];
                    $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                }


                Session::flash('success', 'Property Status changed successfully');
                return redirect()->back();
            }
            else
            {
              
                Session::flash('success', 'Property Status changed successfully');
                return redirect()->back();
            }
        }
        else
        {
            Session::flash('error', 'Error while updating property status');
            return redirect()->back();
        }
    }

    public function change_property_status($enc_property_id, $status)
    {
        $id = base64_decode($enc_property_id);
        $proeprty_name = "";
        
        $notification_title = '';

        if($status == 3 || $status == 4)
        {   
            $obj_property_data = $this->PropertyModel
                                            ->with('user_details','property_type','category','property_rules','property_images','property_unavailability','property_aminities.aminities','property_bed_arrangment')
                                            ->where('id',$id)
                                            ->first();
            if($obj_property_data) {
                $arr_property_data = $obj_property_data->toArray();
            }

            $this->arr_data['id']                         = $enc_property_id;
            $this->arr_data['status']                     = $status;
            $this->arr_data['page_title']                 = str_singular("Reject ".$this->module_title);
            $this->arr_data['module_icon']                = $this->module_icon;
            $this->arr_data['module_title']               = "Reject ".str_singular($this->module_title);
            $this->arr_data['page_icon']                  =  'fa-view';
            $this->arr_data['module_url_path']            =  $this->module_url_path;
            $this->arr_data['admin_panel_slug']           =  $this->admin_panel_slug;
            $this->arr_data['arr_property_data']          =  $arr_property_data;
            $this->arr_data['property_image_public_path'] =  $this->property_image_public_path;
            $this->arr_data['property_image_base_path']   =  $this->property_image_base_path;
            
            return view($this->module_view_folder.'.view',$this->arr_data);
        }

        $property_status  = $this->PropertyModel->where('id',$id)->update(['admin_status'=>$status]);
       
        if($property_status) 
        {
            $obj_user         = $this->PropertyModel->where('id',$id)->with('user_details')->first();
            
            if(isset($obj_user) && $obj_user!=null)
            {
                $arr_user = $obj_user->toArray();
            }

            if(isset($arr_user['property_name']) && $arr_user['property_name'] != ""){
            $proeprty_name = $arr_user['property_name'];
            } 


            if($status == 2)
            {
                $admin_status = 'Confirm';
                $notification_title = "Your Property Confirmed by Admin";
            }

            $type = get_notification_type_of_user($arr_user['user_id']);

            $arr_built_content      = array(
                                             'USER_NAME'      => isset($arr_user['user_details']['display_name'])?ucfirst($arr_user['user_details']['display_name']):'NA',
                                             'STATUS'         => $admin_status
                                            );
            $arr_notify_data['arr_built_content']   = $arr_built_content;   
            $arr_notify_data['notify_template_id']  = '6';          
            $arr_notify_data['user_id']             = $arr_user['user_id'];

            $arr_notify_data['sender_id']           = '1';
            $arr_notify_data['receiver_id']         = $arr_user['user_id'];
            $arr_notify_data['sender_type']         = '2';
            $arr_notify_data['receiver_type']       = '4';
            $arr_notify_data['url']                 = '/property/listing';
            $notification_status                    = $this->NotificationService->send_notification($arr_notify_data);

            if(isset($type) && !empty($type))
            {
               // for mail 
                if($type['notification_by_email'] == 'on')
                {
                    $arr_built_content         = [
                                        'USER_NAME'        => isset($arr_user['user_details']['display_name'])?ucfirst($arr_user['user_details']['display_name']):'NA',   
                                        'Email'            => isset($arr_user['user_details']['email'])?ucfirst($arr_user['user_details']['email']):'NA' ,  
                                         'ID'              => isset($arr_user['id'])?$arr_user['id']:'NA',
                                        'STATUS'           => $admin_status,  
                                        'PROPERTY_NAME'    => $proeprty_name,  
                                        'PROJECT_NAME'     => config('app.project.name')
                                     ];
                    $arr_mail_data                         = [];
                    $arr_mail_data['email_template_id']    = '9';
                    $arr_mail_data['arr_built_content']    = $arr_built_content;
                    $arr_mail_data['user']                 = ['email' => isset($arr_user['user_details']['email'])?ucfirst($arr_user['user_details']['email']):'NA', 'first_name' => isset($arr_user['user_details']['display_name'])?ucfirst($arr_user['user_details']['display_name']):'NA'];


                    $status                                = $this->EmailService->send_mail($arr_mail_data);
                }

                //for sms
                if($type['notification_by_sms'] == 'on')
                {
                    $country_code = isset($arr_user['user_details']['country_code']) ? $arr_user['user_details']['country_code'] : '';
                    $mobile_number = isset($arr_user['user_details']['mobile_number']) ? $arr_user['user_details']['mobile_number'] : '';

                    $arr_sms_data                  = [];
                    $arr_sms_data['msg']           = "Property Status changed successfully";
                    $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                    $status                        = $this->SMSService->send_SMS($arr_sms_data);
                }

                // for push notifictaion
                if($type['notification_by_push'] == 'on')
                {
                    $headings = $notification_title;/*'Property Status changed successfully'*/
                    $content  = $notification_title;/*'Property Status changed successfully'*/
                    $user_id  = isset($arr_user['user_id']) ? $arr_user['user_id'] :0;
                    $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                }

                return redirect($this->module_url_path.'/confirmed')->with('success','Property confirmed successfully');
            }
            else
            {
              
                return redirect($this->module_url_path.'/confirmed')->with('success','Property confirmed successfully.');
            }
        }
        else
        {
            return redirect($this->module_url_path.'/pending')->with('error','Error while updating property status');
        }
    }

    public function change_property_reject_status(Request $request, $enc_property_id, $status)
    {
        $id = base64_decode($enc_property_id);      
        $arr_rules  = [];
        $arr_rules['reject_comment'] = "required";      
        $msg        = array(
                                'required' =>'Please enter :attribute',
                           );

        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $reject_comment    = $request->input('reject_comment');

        $notification_title = '';

        $property_reject_status = $this->PropertyModel->where('id',$id)->update(['admin_comment'=>$reject_comment]);

        if($property_reject_status)
        {
            $property_status = $this->PropertyModel->where('id',$id)->update(['admin_status'=>$status]);

            if($property_status) 
            {
              
                    $obj_user = $this->PropertyModel->where('id',$id)->with('user_details')->first();

                    if(isset($obj_user) && $obj_user!=null)
                    {
                        $arr_user = $obj_user->toArray();
                    }

                    if($status == 3)
                    {
                        $admin_status = 'Rejected';
                        $notification_title = "Your Property Rejected by Admin";
                        
                    }
                    elseif($status == 4)
                    {
                        $admin_status = 'Permanantaly Rejected';
                        $notification_title = "Your Property Permanantaly Rejected by Admin";
                    }

                    $type     = get_notification_type_of_user($arr_user['user_id']);

                    $arr_built_content  = array(
                                             'USER_NAME'      => isset($arr_user['user_details']['display_name'])?ucfirst($arr_user['user_details']['display_name']):'NA',
                                             'STATUS'         => $admin_status
                                            );

                    $arr_notify_data['arr_built_content']   = $arr_built_content;   
                    $arr_notify_data['notify_template_id']  = '6';          
                    $arr_notify_data['user_id']             = $arr_user['user_id'];
                    $arr_notify_data['sender_id']           = '1';
                    $arr_notify_data['receiver_id']         = $arr_user['user_id'];
                    $arr_notify_data['sender_type']         = '2';
                    $arr_notify_data['receiver_type']       = '4';
                    $arr_notify_data['url']                 = '/property/listing';

                    $notification_status                    = $this->NotificationService->send_notification($arr_notify_data);

                    if(isset($type) && !empty($type))
                    {
                        // for mail 
                        if($type['notification_by_email'] == 'on')
                        {
                            /*email sending code start*/
                            $arr_built_content         = [

                                        'USER_NAME'        => isset($arr_user['user_details']['display_name'])?ucfirst($arr_user['user_details']['display_name']):'NA',   
                                        'Email'            => isset($arr_user['user_details']['email'])?ucfirst($arr_user['user_details']['email']):'NA' ,  
                                        'STATUS'           => $admin_status, 
                                        'COMMENT'          => isset($reject_comment) ? $reject_comment:'N/A',
                                        'PROJECT_NAME'     => config('app.project.name')
                                    ];

                            $arr_mail_data                         = [];
                            $arr_mail_data['email_template_id']    = '10';
                            $arr_mail_data['arr_built_content']    = $arr_built_content;
                            $arr_mail_data['user']                 = ['email' => isset($arr_user['user_details']['email'])?ucfirst($arr_user['user_details']['email']):'NA', 'first_name' => isset($arr_user['user_details']['display_name'])?ucfirst($arr_user['user_details']['display_name']):'NA'];

                            $status                                = $this->EmailService->send_mail($arr_mail_data);
                        }

                        // for sms
                        if($type['notification_by_sms'] == 'on')
                        {
                            $country_code  = isset($arr_user['user_details']['country_code']) ? $arr_user['user_details']['country_code'] : '';
                            $mobile_number = isset($arr_user['user_details']['mobile_number']) ? $arr_user['user_details']['mobile_number'] : '';

                            $arr_sms_data                  = [];
                            $arr_sms_data['msg']           = "Property Status changed successfully";
                            $arr_sms_data['mobile_number'] = $country_code.$mobile_number;

                            $status                        = $this->SMSService->send_SMS($arr_sms_data);
                        }

                        // for push notificaion
                        if($type['notification_by_push'] == 'on')
                        {
                            $headings = $notification_title;/*'Property Status changed successfully'*/
                            $content  = $notification_title;/*'Property Status changed successfully'*/
                            $user_id  = isset($arr_user['user_id']) ? $arr_user['user_id'] :0;
                            $status   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                        }
                        
                        return redirect($this->module_url_path.'/pending')->with('success','Property rejected successfully');
                    }
                    else
                    {
                       
                        return redirect($this->module_url_path.'/pending')->with('success','Property rejected successfully.');
                    }
            }               
        }
        else
        {
            return redirect($this->module_url_path.'/pending')->with('error','Error while updating property status');
        }
    }

    public function view($enc_property_id)
    {

        $arr_property_data = [];
        if($enc_property_id != false && $enc_property_id != "")
        {
            $id = base64_decode($enc_property_id);

            $obj_property_data = $this->PropertyModel
                                            ->with('user_details','property_type','category','property_rules','property_images','property_unavailability','property_aminities.aminities','property_bed_arrangment')
                                            ->where('id',$id)
                                            ->first();
            if($obj_property_data)
            {
                $arr_property_data = $obj_property_data->toArray();
            }
        }   

        $this->arr_data['enc_property_id']            = $enc_property_id;
        $this->arr_data['arr_property_data']          = $arr_property_data;
        $this->arr_data['page_title']                 = str_singular("View ".$this->module_title);
        $this->arr_data['module_icon']                = $this->module_icon;
        $this->arr_data['module_title']               = "View ".str_singular($this->module_title);
        $this->arr_data['page_icon']                  = 'fa-view';
        $this->arr_data['module_url_path']            = $this->module_url_path;
        $this->arr_data['admin_panel_slug']           = $this->admin_panel_slug;
        $this->arr_data['property_image_public_path'] = $this->property_image_public_path;
        $this->arr_data['property_image_base_path']   = $this->property_image_base_path;

        return view($this->module_view_folder.'.view',$this->arr_data);
    }
    /*work by kavita*/
    public function add_featured($enc_property_id= false)
    {
       $arr_updata_data = [];
       $id = base64_decode($enc_property_id);
       if ($id != '') 
       {
           $arr_updata_data['is_featured'] = 'yes';
           $res =  $this->PropertyModel->where('id',$id)->update($arr_updata_data);
           if ($res) 
           {
               return redirect()->back()->with('success','Property successfully added in featured list');
           }
           else
           {
               return redirect()->back()->with('error','Error occured while adding property in featured list');
           }
       }
       else
       {
           return redirect()->back();
       }
    }
    public function remove_featured($enc_property_id= false)
    {
       $arr_updata_data = [];
       $id = base64_decode($enc_property_id);
       if ($id != '') 
       {
           $arr_updata_data['is_featured'] = 'no';
           $res =  $this->PropertyModel->where('id',$id)->update($arr_updata_data);
           if ($res) 
           {
               return redirect()->back()->with('success','Property successfully remove from featured list ');
           }
           else
           {
               return redirect()->back()->with('error','Error occured while removing property from featured list');
           }
       }
       else
       {
           return redirect()->back();
       }
    }
     /*end */
}


