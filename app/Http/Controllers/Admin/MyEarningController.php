<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Common\Services\EmailService;
use App\Common\Services\SMSService;
use App\Common\Services\NotificationService;
use App\Models\BookingModel;
use App\Models\PropertyModel;
use App\Models\CouponModel;
use App\Models\TransactionModel;
use App\Common\Traits\MultiActionTrait;
use App\Models\AdminReportModel;

use Datatables;
use Validator;
use Session;
use Excel;
use Auth;
use Hash;
use PDF;
use DB;

class MyEarningController extends Controller
{
    //use MultiActionTrait;
    function __construct(   
                            BookingModel        $booking_model,
                            PropertyModel       $property_model,
                            UserModel           $user_model,
                            EmailService        $email_service,
                            SMSService          $sms_service,
                            NotificationService $notification_service,
                            CouponModel         $coupon_model,
                            AdminReportModel    $admin_report_model,
                            TransactionModel    $transaction_model
                        )
    {
        $this->arr_data            = [];
        $this->admin_panel_slug    = config('app.project.admin_panel_slug');
        $this->admin_url_path      = url(config('app.project.admin_panel_slug'));
        $this->module_url_path     = $this->admin_url_path."/my-earning";
        $this->module_title        = "My Earnings";
        $this->module_view_folder  = "admin.my_earning";
        $this->module_icon         = "fa fa-home";
        $this->EmailService        = $email_service;
        $this->SMSService          = $sms_service;
        $this->NotificationService = $notification_service;
        $this->PropertyModel       = $property_model;
        $this->UserModel           = $user_model;
        $this->AdminReportModel    = $admin_report_model;
        $this->BookingModel        = $booking_model;
        $this->TransactionModel    = $transaction_model;
        $this->BaseModel           = $booking_model;
        $this->CouponModel         = $coupon_model;
    }

    public function index()
    {

        $user_table                 = $this->UserModel->getTable();
        $prefixed_users_table       = DB::getTablePrefix().$this->UserModel->getTable();

        $transaction_table          = $this->TransactionModel->getTable();
        $prefixed_transaction_table = DB::getTablePrefix().$this->TransactionModel->getTable();

        $property_table             = $this->PropertyModel->getTable();
        $prefixed_property_table    = DB::getTablePrefix().$this->PropertyModel->getTable();

        $booking_table              = $this->BookingModel->getTable();
        $prefixed_booking_table     = DB::getTablePrefix().$this->BookingModel->getTable();

        $arr_property               = $arr_property_owner = [];

        $arr_property               = DB::table($transaction_table)
                                    ->select(DB::raw( 
                                            $prefixed_property_table.".id,".
                                            $prefixed_property_table.".property_name"
                                            
                                        ))
                                    ->where($prefixed_transaction_table.'.user_type','4')
                                    ->where($prefixed_booking_table.'.booking_status','5')
                                    ->groupBy($prefixed_property_table.'.id')
                                    ->Join($prefixed_users_table,$prefixed_users_table.".id",' = ',$prefixed_transaction_table.'.user_id')
                                    ->Join($prefixed_booking_table,$prefixed_booking_table.".id",' = ',$prefixed_transaction_table.'.booking_id')
                                    ->Join($prefixed_property_table,$prefixed_property_table.".id",' = ',$prefixed_booking_table.'.property_id')
                                    ->get();

        $arr_property_owner        = DB::table($transaction_table)
                                    ->select(DB::raw( 
                                            $prefixed_users_table.".id,".
                                            $prefixed_users_table.".first_name as owner_firstname,".
                                            $prefixed_users_table.".last_name as owner_lastname"
                                        ))
                                    ->where($prefixed_transaction_table.'.user_type','4')
                                    ->where($prefixed_booking_table.'.booking_status','5')
                                    ->groupBy($prefixed_users_table.'.id')
                                    ->Join($prefixed_users_table,$prefixed_users_table.".id",' = ',$prefixed_transaction_table.'.user_id')
                                    ->Join($prefixed_booking_table,$prefixed_booking_table.".id",' = ',$prefixed_transaction_table.'.booking_id')
                                    ->Join($prefixed_property_table,$prefixed_property_table.".id",' = ',$prefixed_booking_table.'.property_id')
                                    ->get();

        $this->arr_data['arr_property']        = $arr_property; 
        $this->arr_data['arr_property_owner']  = $arr_property_owner; 
        $this->arr_data['page_title']          = str_singular("Manage  ".$this->module_title);
        $this->arr_data['module_icon']         = $this->module_icon;
        $this->arr_data['module_title']        = "Manage".str_singular($this->module_title);
        $this->arr_data['page_icon']           = 'fa-list';
        $this->arr_data['module_url_path']     = $this->module_url_path;
        $this->arr_data['admin_panel_slug']    = $this->admin_panel_slug;
        return view($this->module_view_folder.'.index',$this->arr_data);
    }

    public function load_data(Request $request)
    {
        $keyword         = $request->input('keyword');
        $property_name   = $request->input('property_name'); 
        $property_owner  = $request->input('property_owner'); 
        $search_date     = $request->input('search_date'); 

        $UserData        =  $final_array = []; 
        $column          = '';
       
        if ($request->input('order')[0]['column'] == 1) 
        {
            $column = "transaction_id";
        }           
        if ($request->input('order')[0]['column'] == 2) 
        {
            $column = "booking_id";
        }     
        if ($request->input('order')[0]['column'] == 3) 
        {
            $column = "first_name";
        } 
         if ($request->input('order')[0]['column'] == 4) 
        {
            $column = "property_name";
        } 
        if ($request->input('order')[0]['column'] == 5) 
        {
            $column = "transaction_date";
        } 
        if ($request->input('order')[0]['column'] == 6) 
        {
            $column = "property_amount";
        }    
         if ($request->input('order')[0]['column'] == 7) 
        {
            $column = "property_amount";
        }   
        if ($request->input('order')[0]['column'] == 8) 
        {
            $column = "admin_commission";
        } 
        if ($request->input('order')[0]['column'] == 9) 
        {
            $column = "total_amount";
        } 

        $order = strtoupper($request->input('order')[0]['dir']);  

        $user_table                 = $this->UserModel->getTable();
        $prefixed_users_table       = DB::getTablePrefix().$this->UserModel->getTable();

        $transaction_table          = $this->TransactionModel->getTable();
        $prefixed_transaction_table = DB::getTablePrefix().$this->TransactionModel->getTable();

        $property_table             = $this->PropertyModel->getTable();
        $prefixed_property_table    = DB::getTablePrefix().$this->PropertyModel->getTable();

        $booking_table              = $this->BookingModel->getTable();
        $prefixed_booking_table     = DB::getTablePrefix().$this->BookingModel->getTable();

        $arr_data = [];
        $obj_data = DB::table($transaction_table)
                        ->select( DB::raw( 
                            $prefixed_transaction_table.".transaction_id,".
                            $prefixed_transaction_table.".transaction_date,".
                            $prefixed_users_table.".first_name as owner_firstname,".
                            $prefixed_users_table.".last_name as owner_lastname,".
                            $prefixed_booking_table.".*,".
                            $prefixed_property_table.".property_name,".
                            $prefixed_property_table.".currency,".
                            $prefixed_property_table.".currency_code"
                        ))
                        ->where($prefixed_transaction_table.'.user_type','4')
                        ->where($prefixed_booking_table.'.booking_status','5')
                        ->orderBy($prefixed_transaction_table.'.id','DESC')
                        ->Join($prefixed_users_table,$prefixed_users_table.".id",' = ',$prefixed_transaction_table.'.user_id')
                        ->Join($prefixed_booking_table,$prefixed_booking_table.".id",' = ',$prefixed_transaction_table.'.booking_id')
                        ->Join($prefixed_property_table,$prefixed_property_table.".id",' = ',$prefixed_booking_table.'.property_id');

        if($property_name!='')
        {
            $obj_data = $obj_data->where($prefixed_property_table.'.id',base64_decode($property_name));   
        }

        if($property_owner!='')
        {
            $obj_data = $obj_data->where($prefixed_booking_table.'.property_owner_id',base64_decode($property_owner));   
        }

        if($search_date!='')
        {
            $obj_data = $obj_data->where($prefixed_transaction_table.'.transaction_date',$search_date);   
        }

        if(isset($keyword) && $keyword != "")
        {
            $obj_data = $obj_data->whereRaw("".$prefixed_transaction_table.".transaction_id LIKE '%".$keyword."%' OR ".$prefixed_booking_table.".booking_id LIKE '%".$keyword."%' OR ".$prefixed_property_table.".property_name LIKE '%".$keyword."%'");  
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

        $BookingData  = $obj_data->get();

        $resp['draw']            = $_GET['draw'];
        $resp['recordsTotal']    = $count;
        $resp['recordsFiltered'] = $count;
        $build_active_btn        = '' ; 

         if(count($BookingData)>0)
            {
                $i = 0; //$total_amount=0;

                foreach($BookingData as $row)
                {
                    //dd( $row );
                    $transaction_id     = $row->transaction_id;
                    $transaction_date   = $row->transaction_date;
                 
                    $total_earn         = 0;

                    $admin_commission   = $row->admin_commission;
                    $property_price     = $row->property_amount;

                    $total_earn         =  ($admin_commission/100)* $row->total_night_price;

                    $property_name      = ''.wordwrap($row->property_name,40,"<br>\n").'';

                    $final_array[$i][0] = isset($transaction_id) && $transaction_id != ''?$transaction_id:"N/A";

                    $final_array[$i][1] = isset($row->booking_id) && $row->booking_id != ''?$row->booking_id:'N/A';

                    $final_array[$i][2] = isset($row->owner_firstname) && $row->owner_firstname != '' && isset($row->owner_lastname) && $row->owner_lastname != '' ? ucfirst($row->owner_firstname)."&nbsp;".ucfirst($row->owner_lastname):'N/A';

                    $final_array[$i][3] = isset($property_name) && $property_name != '' ? $property_name:'N/A';

                    $final_array[$i][4] = isset($transaction_date) && $transaction_date != '' ? date('d-M-Y',strtotime($transaction_date)) :"N/A";

                    $final_array[$i][5] = isset($row->property_amount) && $row->property_amount != '' ? '&#8377;'.$row->property_amount : 'N/A';

                    $final_array[$i][6] = isset($row->total_night_price) && $row->total_night_price != '' ? '&#8377;'.$row->total_night_price : 'N/A';

                    $final_array[$i][7] = isset($row->admin_commission) && $row->admin_commission != '' ? $row->admin_commission.'%':'N/A';

                    $final_array[$i][8] = '&#8377;'.number_format($total_earn, 2);

                    $i++;
                }
            }
            $resp['data'] = $final_array;
            echo str_replace("\/", "/",  json_encode($resp));exit;      
    }

    public function host_request()
    {
        $this->arr_data['page_title']          = str_singular("Manage Host Requests ");
        $this->arr_data['module_icon']         = $this->module_icon;
        $this->arr_data['module_title']        = "Manage Host Requests";
        $this->arr_data['page_icon']           = 'fa-list';
        $this->arr_data['module_url_path']     = $this->module_url_path;
        $this->arr_data['admin_panel_slug']    = $this->admin_panel_slug;
        return view($this->module_view_folder.'.host_request',$this->arr_data);
    }

    public function load_host_request_data(Request $request)
    {
       
        $UserData        =  $final_array = []; 
        $column          = '';
       
        if ($request->input('order')[0]['column'] == 1) 
        {
            $column = "report_id";
        }           
        if ($request->input('order')[0]['column'] == 2) 
        {
            $column = "first_name";
        }     
        if ($request->input('order')[0]['column'] == 3) 
        {
            $column = "fromdate";
        } 
        if ($request->input('order')[0]['column'] == 4) 
        {
            $column = "todate";
        } 
        if ($request->input('order')[0]['column'] == 5) 
        {
            $column = "report_user_type";
        } 
        if ($request->input('order')[0]['column'] == 6) 
        {
            $column = "total_amount";
        }  
        if ($request->input('order')[0]['column'] == 7) 
        {
            $column = "total_commission";
        }   
        if ($request->input('order')[0]['column'] == 8) 
        {
            $column = "total_amount";
        }    
        if ($request->input('order')[0]['column'] == 9) 
        {
            $column = "status";
        } 
        if ($request->input('order')[0]['column'] == 10) 
        {
            $column = "report_date";
        } 
        if ($request->input('order')[0]['column'] == 11) 
        {
            $column = "report_invoice";
        } 

        $order = strtoupper($request->input('order')[0]['dir']);  

        $admin_report_table           = $this->AdminReportModel->getTable();
        $prefixed_admin_report_table  = DB::getTablePrefix().$this->AdminReportModel->getTable();

        $user_table                   = $this->UserModel->getTable();
        $prefixed_users_table         = DB::getTablePrefix().$this->UserModel->getTable();

        $arr_data     = [];
        $obj_data     = DB::table($admin_report_table)
                                    ->select(DB::raw( $prefixed_admin_report_table.".*,".
                                            $prefixed_users_table.".first_name as host_firstname,".
                                            $prefixed_users_table.".last_name as host_lastname"
                                        ))
                                    ->orderBy($prefixed_admin_report_table.'.id','DESC')
                                    ->Join($prefixed_users_table,$prefixed_users_table.".id",' = ',$prefixed_admin_report_table.'.user_id');

        $count        = count($obj_data->get());

        if($order =='ASC' && $column=='')
        {
          $obj_data   = $obj_data->orderBy('id','DESC')->limit($_GET['length'])->offset($_GET['start']);
        }
        if( $order !='' && $column!='' )
        {
          $obj_data   = $obj_data->orderBy($column,$order)->limit($_GET['length'])->offset($_GET['start']);
        }

        $BookingData  = $obj_data->get();

        $resp['draw']            = $_GET['draw'];
        $resp['recordsTotal']    = $count;
        $resp['recordsFiltered'] = $count;
        $build_active_btn        = '' ; 

         if(count($BookingData)>0)
            {
                $i = 0;

                foreach($BookingData as $row)
                {
                    //dd( $row );
                    if($row->status != null && $row->status == "unpaid")
                    {
                        $build_active_btn = '<a class="btn btn-info btn-bordered btn-fill show-tooltip" title="paid" href="'.$this->module_url_path.'/paid/'.base64_encode($row->id).'" 
                       onclick="return confirm_action(this,event,\'Do you really want to pay this user ?\')" >Unpaid</a>';
                    }
                    else
                    {
                      $build_active_btn ='<a class="btn btn-success btn-bordered btn-fill show-tooltip" href="javascript:void(0);">Paid
                          </a>';
                    }
                   
                    $invoice_href        = url('/').'/uploads/invoice/'.$row->report_invoice;

                    $invoice_link        = "<a download='' target='_blank' class='icon-btn' href='".$invoice_href."' ><i class='fa fa-download' aria-hidden='true'></i></a>";

                    $final_array[$i][0]  = isset($row->report_id) && $row->report_id != ''?$row->report_id:'N/A';

                    $final_array[$i][1]  = isset($row->host_firstname) && $row->host_firstname != '' && isset($row->host_lastname) && $row->host_lastname != ''?ucfirst($row->host_firstname)."&nbsp;".ucfirst($row->host_lastname):'N/A';

                    $final_array[$i][2]  = isset($row->fromdate) && $row->fromdate != ''? date('d-M-Y',strtotime($row->fromdate)) :'N/A';

                    $final_array[$i][3]  = isset($row->todate) && $row->todate != ''? date('d-M-Y',strtotime($row->todate)) :'N/A';

                    $final_array[$i][4]  = isset($row->report_user_type) && $row->report_user_type != '' ? ucwords($row->report_user_type) : 'N/A';

                    $final_array[$i][5]  = isset($row->total_amount) && $row->total_amount != ''?'&#8377;'.number_format($row->total_amount,2):'N/A';

                    $final_array[$i][6]  = isset($row->total_commission) && $row->total_commission != '' ? number_format($row->total_commission,2).'%' : 'N/A';

                    $final_array[$i][7]  = isset($row->status) && $row->status != '' ? ucwords($row->status) : 'N/A';

                    $final_array[$i][8]  = isset($row->report_date) && $row->report_date != '' ? date('d-M-Y H:i:s',strtotime($row->report_date)) : 'N/A';

                    $final_array[$i][9]  = $invoice_link;

                    $final_array[$i][10] = $build_active_btn;

                    $i++;
                }
            }
            
            $resp['data'] = $final_array;
            echo str_replace("\/", "/",  json_encode($resp));exit;      
    }

    public function paid($id=NULL)
    {
        $id           = base64_decode($id);

        $arr_update['status'] = 'paid';

        $result     = $this->AdminReportModel->where('id',$id)->update($arr_update);

        if( $result)
        {
            Session::flash('success', ' Amount is successfully paid to this user.');
            return redirect()->back();
        }
        else
        {
            Session::flash('error','Error occur while paying amount.');
            return redirect()->back();
        }
    }

    public function export(Request $request)
    {
        $BookingData = [];

        $property_name  = $request->input('e_property_name');
        $property_owner = $request->input('e_property_owner');
        $search_date    = $request->input('e_search_date');
        $keyword        = $request->input('e_keyword');

        $user_table                 = $this->UserModel->getTable();
        $prefixed_users_table       = DB::getTablePrefix().$this->UserModel->getTable();

        $transaction_table          = $this->TransactionModel->getTable();
        $prefixed_transaction_table = DB::getTablePrefix().$this->TransactionModel->getTable();

        $property_table             = $this->PropertyModel->getTable();
        $prefixed_property_table    = DB::getTablePrefix().$this->PropertyModel->getTable();

        $booking_table              = $this->BookingModel->getTable();
        $prefixed_booking_table     = DB::getTablePrefix().$this->BookingModel->getTable();

        $arr_data = [];
        $obj_data = DB::table($transaction_table)
                        ->select( DB::raw( 
                            $prefixed_transaction_table.".transaction_id,".
                            $prefixed_transaction_table.".transaction_date,".
                            $prefixed_users_table.".first_name as owner_firstname,".
                            $prefixed_users_table.".last_name as owner_lastname,".
                            $prefixed_booking_table.".*,".
                            $prefixed_property_table.".property_name,".
                            $prefixed_property_table.".currency,".
                            $prefixed_property_table.".currency_code"
                        ))
                        ->where($prefixed_transaction_table.'.user_type','4')
                        ->where($prefixed_booking_table.'.booking_status','5')
                        ->orderBy($prefixed_transaction_table.'.id','DESC')
                        ->Join($prefixed_users_table,$prefixed_users_table.".id",' = ',$prefixed_transaction_table.'.user_id')
                        ->Join($prefixed_booking_table,$prefixed_booking_table.".id",' = ',$prefixed_transaction_table.'.booking_id')
                        ->Join($prefixed_property_table,$prefixed_property_table.".id",' = ',$prefixed_booking_table.'.property_id');

        if($property_name != '') {
            $obj_data = $obj_data->where($prefixed_property_table.'.id',base64_decode($property_name));   
        }

        if($property_owner != '') {
            $obj_data = $obj_data->where($prefixed_booking_table.'.property_owner_id',base64_decode($property_owner));   
        }

        if($search_date != '') {
            $obj_data = $obj_data->where($prefixed_transaction_table.'.transaction_date',$search_date);   
        }

        if(isset($keyword) && $keyword != "") {
            $obj_data = $obj_data->whereRaw("".$prefixed_transaction_table.".transaction_id LIKE '%".$keyword."%' OR ".$prefixed_booking_table.".booking_id LIKE '%".$keyword."%' OR ".$prefixed_property_table.".property_name LIKE '%".$keyword."%'");  
        }
        $BookingData = $obj_data->get();

        // dd( $BookingData );


        $format = "xlsx";

        if ($format == "xlsx") {
            $arr_tmp = array();
            
            if (count($BookingData)>0) {
                \Excel::create('MyEarning-'.date('Ymd').uniqid(), function($excel) use($BookingData) {
                    $excel->sheet('MyEarning', function($sheet) use($BookingData) {
                        
                        $sheet->cell('A1', function($cell) {
                            $cell->setValue('Generated on :'.date("d-m-Y H:i:s"));
                        });
                        
                        $sheet->row(2, array(
                            'Booking ID',
                            'Transaction ID',
                            'Property Owner',
                            'Property Name',
                            'Date',
                            'Property Amount',
                            'Total Paid Amount',
                            'Commission',
                            'Total Earning'
                        ));
                        
                        $i = 0;
                        $user_gender = $type = "-";

                        foreach ($BookingData as $key => $row) {

                            $total_earn        = 0;
                            $total_night_price = isset($row->total_night_price) && $row->total_night_price != '' ? $row->total_night_price : '0';
                            $admin_commission  = isset($row->admin_commission) && $row->admin_commission != '' ? $row->admin_commission : '0';
                            $total_earn        = ( $admin_commission / 100 ) * $total_night_price;

                            $arr_tmp[$key][] = isset($row->transaction_id) && $row->transaction_id != '' ? $row->transaction_id : "N/A";
                            $arr_tmp[$key][] = isset($row->booking_id) && $row->booking_id != '' ? $row->booking_id : 'N/A';
                            $arr_tmp[$key][] = isset($row->owner_firstname) && $row->owner_firstname != '' && isset($row->owner_lastname) && $row->owner_lastname != '' ? ucfirst($row->owner_firstname)." ".ucfirst($row->owner_lastname):'N/A';
                            $arr_tmp[$key][] = isset($row->property_name) && $row->property_name != '' ? $row->property_name:'N/A';
                            $arr_tmp[$key][] = isset($row->transaction_date) && $row->transaction_date != '' ? date('d-M-Y',strtotime($row->transaction_date)) :"N/A";
                            $arr_tmp[$key][] = isset($row->property_amount) && $row->property_amount != '' ? '₹'.$row->property_amount : '₹0';
                            $arr_tmp[$key][] = '₹'.$total_night_price;
                            $arr_tmp[$key][] = $admin_commission.'%';
                            $arr_tmp[$key][] = '₹'.number_format($total_earn, 2);
                        }

                        $sheet->rows($arr_tmp);                                      
                    });
                })->export('xlsx');

            } else {
                $userMsg = 'Error occure while making export due to no data to create xlsx file';
                Session::flash('error',$userMsg);
                return redirect()->back();
            }
        }
    }
}


