<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\UserModel;
use App\Models\TransactionModel;
use App\Models\BookingModel;
use App\Models\PropertyModel;
use App\Models\AdminReportModel;
use App\Models\SiteSettingsModel;
use App\Common\Services\EmailService;
use App\Common\Services\SMSService;
use App\Common\Services\NotificationService;

use Session;
use DB;
use TCPDF;
use PDF;
use Validator;

class MyEarningController extends Controller
{
    public function __construct(
                                  UserModel           $user_model,
                                  SMSService          $sms_service,
                                  TransactionModel    $transaction_model,
                                  BookingModel        $booking_model,
                                  EmailService        $email_service,
                                  PropertyModel       $property_model,
                                  AdminReportModel    $admin_report_model,
                                  NotificationService $notification_service,
                                  SiteSettingsModel   $site_settings_model
                                )
  {
    $this->arr_view_data            = [];
    $this->module_title             = 'My Earnings';
    $this->module_view_folder       = 'front.my_earning';
    $this->module_url_path          = url('/').'/my-earning';
    $this->EmailService             = $email_service;
    $this->SMSService               = $sms_service;
    $this->UserModel                = $user_model;
    $this->TransactionModel         = $transaction_model;
    $this->PropertyModel            = $property_model;
    $this->AdminReportModel         = $admin_report_model;
    $this->SiteSettingsModel        = $site_settings_model;
    $this->NotificationService      = $notification_service;

    $this->invoice_path_public_path = url('/').config('app.project.img_path.invoice_path');
    $this->invoice_path_base_path   = public_path().config('app.project.img_path.invoice_path');

    $this->auth                     = auth()->guard('users'); 
    $user                           = $this->auth->user();

    $this->TCPDF                    = new TCPDF();
    $this->BookingModel             = $booking_model;
    if($user)
    {
        $this->user_id            = $user->id;
        $this->user_first_name    = $user->first_name;
        $this->user_last_name     = $user->last_name;
    }
  }
  /*
    | Function  : Show all the transaction according to the user type
    | Author    : Deepak Arvind Salunke
    | Date      : 26/04/2018
    | Output    : Success or Error
    */

  public function index(Request $request)
  { 
    $from_date                    = $request->input('from_date');
    $to_date                      = $request->input('to_date');

    $user_type                    = Session::get('user_type');
    
    $user_table                   = $this->UserModel->getTable();
    $prefixed_users_table         = DB::getTablePrefix().$this->UserModel->getTable();

    $transaction_table            = $this->TransactionModel->getTable();
    $prefixed_transaction_table   = DB::getTablePrefix().$this->TransactionModel->getTable();

    $property_table               = $this->PropertyModel->getTable();
    $prefixed_property_table      = DB::getTablePrefix().$this->PropertyModel->getTable();

    $booking_table                = $this->BookingModel->getTable();
    $prefixed_booking_table       = DB::getTablePrefix().$this->BookingModel->getTable();

    $arr_transaction = [];
    $obj_data        = DB::table($transaction_table)
                                ->select(DB::raw( $prefixed_transaction_table.".transaction_id,".
                                        $prefixed_transaction_table.".transaction_date,".
                                        $prefixed_transaction_table.".amount,".
                                        $prefixed_users_table.".first_name as owner_firstname,".
                                        $prefixed_users_table.".last_name as owner_lastname,".
                                        $prefixed_booking_table.".*,".
                                        $prefixed_property_table.".property_name,".
                                        $prefixed_property_table.".currency,".
                                        $prefixed_property_table.".currency_code"
                                    ))
                                ->where($prefixed_transaction_table.'.user_type','4')
                                ->where($prefixed_booking_table.'.booking_status','5')
                                ->where($prefixed_booking_table.'.property_owner_id',$this->user_id)
                                ->orderBy($prefixed_transaction_table.'.id','DESC')
                                ->Join($prefixed_users_table,$prefixed_users_table.".id",' = ',$prefixed_transaction_table.'.user_id')
                                ->Join($prefixed_booking_table,$prefixed_booking_table.".id",' = ',$prefixed_transaction_table.'.booking_id')
                                ->Join($prefixed_property_table,$prefixed_property_table.".id",' = ',$prefixed_booking_table.'.property_id');

    if($from_date != '' && $to_date != '')
    {
      $obj_data = $obj_data->whereBetween($prefixed_transaction_table.'.transaction_date',array(date('Y-m-d',strtotime($from_date)), date('Y-m-d',strtotime($to_date))));
    }

    $obj_data =  $obj_data->paginate(10);
                                                  
    if($obj_data)
    {
      $obj_data->setPath(url('/my-earning'));
      $page_link        = $obj_data->links();
      $arr_transaction  = $obj_data->toArray();
    }

    $this->arr_view_data['page_link']                = $page_link;
    $this->arr_view_data['arr_transaction']          = $arr_transaction;
    $this->arr_view_data['invoice_path_public_path'] = $this->invoice_path_public_path;
    $this->arr_view_data['invoice_path_base_path']   = $this->invoice_path_base_path;
    $this->arr_view_data['page_title']               = $this->module_title;
    $this->arr_view_data['module_url_path']          = $this->module_url_path;
    
    
    return view($this->module_view_folder.'.index',$this->arr_view_data);
  } // end index

  
  public function export(Request $request)
  {
    $from_date = $request->input('e_from_date');
    $to_date   = $request->input('e_to_date');

    $user_table                 = $this->UserModel->getTable();
    $prefixed_users_table       = DB::getTablePrefix().$this->UserModel->getTable();

    $transaction_table          = $this->TransactionModel->getTable();
    $prefixed_transaction_table = DB::getTablePrefix().$this->TransactionModel->getTable();

    $property_table             = $this->PropertyModel->getTable();
    $prefixed_property_table    = DB::getTablePrefix().$this->PropertyModel->getTable();

    $booking_table              = $this->BookingModel->getTable();
    $prefixed_booking_table     = DB::getTablePrefix().$this->BookingModel->getTable();

    $arr_transaction = [];
    $obj_data = DB::table($transaction_table)
                  ->select(DB::raw( $prefixed_transaction_table.".transaction_id,".
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
                  ->where($prefixed_booking_table.'.property_owner_id',$this->user_id)
                  ->orderBy($prefixed_transaction_table.'.id','DESC')
                  ->Join($prefixed_users_table,$prefixed_users_table.".id",' = ',$prefixed_transaction_table.'.user_id')
                  ->Join($prefixed_booking_table,$prefixed_booking_table.".id",' = ',$prefixed_transaction_table.'.booking_id')
                  ->Join($prefixed_property_table,$prefixed_property_table.".id",' = ',$prefixed_booking_table.'.property_id');

    if($from_date != '' && $to_date != '') {
        $obj_data = $obj_data->whereBetween($prefixed_transaction_table.'.transaction_date',array(date('Y-m-d',strtotime($from_date)), date('Y-m-d',strtotime($to_date))));
    }

    $student_arr = $obj_data->get();
    $format = "xlsx";

      if($format == "xlsx") {
          $arr_tmp = array();
          if(count($student_arr)>0) {
             \Excel::create('MY_EARNING_REPORT-'.date('Ymd').uniqid(), function($excel) use($student_arr) {
                  $excel->sheet('MyEarning', function($sheet) use($student_arr) {
                    $sheet->cell('A1', function($cell) {
                        $cell->setValue('Generated on :'.date("d-m-Y H:i:s"));
                    });

                    $sheet->row(2, array(
                            'Transaction Id',
                            'Booking Id',
                            'Property Name',
                            'Date',
                            'Property Amount',
                            'Commission',
                            'Total Earning'
                        ) );

                    $i = 0;

                    foreach($student_arr as $key => $ad) {
                        $pro_amount = $ad->property_amount;
                        $commission = $ad->admin_commission;
                        $host_earn  = $admin_earn = 0;
                        $admin_earn = ($commission/100)* $pro_amount;
                        $host_earn  = $pro_amount - $admin_earn;

                        $arr_tmp[$key][] = $ad->transaction_id;
                        $arr_tmp[$key][] = $ad->booking_id;
                        $arr_tmp[$key][] = $ad->property_name;
                        $arr_tmp[$key][] = $ad->transaction_date;
                        $arr_tmp[$key][] = number_format($ad->property_amount,2)." INR";
                        $arr_tmp[$key][] = $ad->admin_commission."%";
                        $arr_tmp[$key][] = number_format($host_earn,2)." INR";
                    }

                    $sheet->rows($arr_tmp);     
                });
              })->export('xlsx');
          }
          else {
            Session::flash('error','Error occure while making export due to no data to create xlsx file.');
            return redirect()->back();
          }
      }
  }

  public function transaction_details($id = NULL)
  {
      $id                         = base64_decode($id);
      $transaction_table          = $this->TransactionModel->getTable();
      $booking_table              = $this->BookingModel->getTable();
      $property_table             = $this->PropertyModel->getTable();
      $prefixed_transaction_table = DB::getTablePrefix().$this->TransactionModel->getTable();
      $prefixed_booking_table     = DB::getTablePrefix().$this->BookingModel->getTable();
      $prefixed_property_table    = DB::getTablePrefix().$this->PropertyModel->getTable();
      $arr_transaction            = DB::table($prefixed_transaction_table)
                                      ->select(
                                          $prefixed_transaction_table.".transaction_id",
                                          $prefixed_transaction_table.".transaction_date",
                                          $prefixed_transaction_table.".amount",
                                          $prefixed_transaction_table.".payment_type",
                                          $prefixed_transaction_table.".transaction_for",
                                          $prefixed_booking_table.".check_in_date",
                                          $prefixed_booking_table.".check_out_date",
                                          $prefixed_booking_table.".created_at",
                                          $prefixed_property_table.".currency"
                                      )
                                      ->Join($prefixed_booking_table,$prefixed_booking_table.".id",' = ',$prefixed_transaction_table.'.booking_id')
                                      ->Join($prefixed_property_table,$prefixed_property_table.".id",' = ',$prefixed_booking_table.'.property_id')
                                      ->where($prefixed_transaction_table.'.id',$id)
                                      ->get();

    $this->arr_view_data['arr_transaction'] = $arr_transaction;
    $this->arr_view_data['page_title']      = $this->module_title;
    $this->arr_view_data['module_url_path'] = $this->module_url_path;
    
    return view($this->module_view_folder.'.transaction_details',$this->arr_view_data);
  }

  public function request_to_admin(Request $request)
  {
        $from_date = $request->input('e_report_from_date');
        $to_date   = $request->input('e_report_to_date');

        $arr_rules                       = array();
        $arr_rules['e_report_from_date'] = "required";
        $arr_rules['e_report_to_date']   = "required";
     
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        } 

        $arr_where_cnd['fromdate'] = date('Y-m-d',strtotime($from_date));
        $arr_where_cnd['todate']   = date('Y-m-d',strtotime($to_date));
        $arr_where_cnd['user_id']  = $this->user_id;

        $arr_report = $this->AdminReportModel->where($arr_where_cnd)->get();

        if(count($arr_report))
        {
            Session::flash('error','This report is already generated to admin.');
            return redirect()->back();
        }
         
        $user_table                 = $this->UserModel->getTable();
        $prefixed_users_table       = DB::getTablePrefix().$this->UserModel->getTable();

        $transaction_table          = $this->TransactionModel->getTable();
        $prefixed_transaction_table = DB::getTablePrefix().$this->TransactionModel->getTable();

        $property_table             = $this->PropertyModel->getTable();
        $prefixed_property_table    = DB::getTablePrefix().$this->PropertyModel->getTable();

        $booking_table              = $this->BookingModel->getTable();
        $prefixed_booking_table     = DB::getTablePrefix().$this->BookingModel->getTable();

        $arr_transaction = [];
        $obj_data        = DB::table($transaction_table)
                                    ->select(DB::raw( $prefixed_transaction_table.".transaction_id,".
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
                                    ->where($prefixed_booking_table.'.property_owner_id',$this->user_id)
                                    ->orderBy($prefixed_transaction_table.'.id','DESC')
                                    ->Join($prefixed_users_table,$prefixed_users_table.".id",' = ',$prefixed_transaction_table.'.user_id')
                                    ->Join($prefixed_booking_table,$prefixed_booking_table.".id",' = ',$prefixed_transaction_table.'.booking_id')
                                    ->Join($prefixed_property_table,$prefixed_property_table.".id",' = ',$prefixed_booking_table.'.property_id');

    if($from_date != '' && $to_date != '')
    {
      $obj_data = $obj_data->whereBetween($prefixed_transaction_table.'.transaction_date',array(date('Y-m-d',strtotime($from_date)), date('Y-m-d',strtotime($to_date))));
    }

    $report_arr = $obj_data->get(); 

    if(count($report_arr) > 0)
    {
        $total_amount = $total_commission = 0;

        foreach($report_arr as $key => $row)
        {
            $total_amount     += $row->total_amount;
            //$total_commission += $row->admin_commission;
        }

        $owner_name = isset($row->owner_firstname) && $row->owner_firstname != '' && isset($row->owner_lastname) && $row->owner_lastname != '' ? ucfirst($row->owner_firstname)." ".ucfirst($row->owner_lastname) : 'N/A';

        $report_data['user_id']          = $this->user_id;
        $report_data['username']         = $owner_name;
        $report_data['fromdate']         = date('Y-m-d',strtotime($from_date));
        $report_data['todate']           = date('Y-m-d',strtotime($to_date));
        $report_data['total_amount']     = $total_amount;
        $report_data['report_user_type'] = 'host';
        $report_data['total_commission'] = $row->admin_commission;

        $result = $this->AdminReportModel->create($report_data);

        if ($result) 
        {
          $inserted_id  = $result->id;
          $report_id    = 'R'.str_pad($inserted_id, 6, "0", STR_PAD_LEFT);

          $result       = $this->AdminReportModel->where('id','=',$inserted_id)->update(array('report_id' =>  $report_id));  

          $SenderData   = $this->UserModel->where('id',$this->user_id)->select('first_name','last_name','id','email','address','mobile_number')->first();
     
          $ReceivedData = $this->SiteSettingsModel->select('site_name','site_address','site_contact_number','site_email_address')->first();

          $data['logo']     = "";
          $data['base_url'] = url('/');

          $this->TCPDF->SetTitle(config('app.project.name'));
          $this->TCPDF->AddPage();       
                  
          $html  = "";
          $view  = "";
  
          $view  = view('invoice.request_to_admin_pdf')->with(['ReceivedData'=>$ReceivedData,'SenderData'=>$SenderData,'obj_data' => $report_arr,'Data'=>$data,'total_amount'=>$total_amount,'report_id'=>$report_id]);
         
          $html  = $view->render();                
          
          $this->TCPDF->writeHTML($html, true, false, true, false, 'L');
          $FileName       = 'User_Report'.$inserted_id.'.pdf';        
          $this->TCPDF->output(public_path('uploads/invoice/'.$FileName),'F');  
          
          $report_invoice = public_path('uploads/invoice/'.$FileName);
        
          $this->AdminReportModel->where('id','=',$inserted_id)->update(array('report_invoice' =>  $FileName));
       
          $arr_built_content  = array(
                                      'USER_NAME' => isset($SenderData->first_name) ? $SenderData->first_name : 'NA',
                                      'MESSAGE'   => $SenderData->first_name.' '.$SenderData->last_name.' has send request to pay for his booking',
                                      'SUBJECT'   => "Request To Admin"
                                    );

          $arr_notify_data['arr_built_content']  = $arr_built_content;
          $arr_notify_data['notify_template_id'] = '12';
          $arr_notify_data['sender_id']          = $this->user_id;
          $arr_notify_data['sender_type']        = '4';
          $arr_notify_data['receiver_type']      = '2';
          $arr_notify_data['receiver_id']        = '1';
          $arr_notify_data['url']                = '';
          $notification_status                   = $this->NotificationService->send_notification($arr_notify_data);
        
           Session::flash('success','Request To Admin Send Successfully.');
           return redirect()->back();

        }
        else
        {
           Session::flash('error','Problem occur while sending request to admin.');
          return redirect()->back();

        }

    }
    else
    {
          Session::flash('error','No Booking Data Is Available.');
          return redirect()->back();
    }


  }

}
