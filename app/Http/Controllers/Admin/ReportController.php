<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserModel;    
use App\Models\BookingModel;
use App\Models\PropertyModel;
use App\Models\CouponModel;
use App\Models\SupportQueryModel;
use App\Models\QueryTypeModel;
use App\Models\SupportTeamModel;

use Validator;
use Session;
use Excel;
use PDF;
use DB;



class ReportController extends Controller
{
    public function __construct(
                                    UserModel         $user_model,
                                    BookingModel      $booking_model,
                                    PropertyModel     $property_model,
                                    CouponModel       $coupon_model,
                                    SupportQueryModel $support_query_model,
                                    QueryTypeModel    $querytype_model,
                                    SupportTeamModel  $support_team_model
                                )
    {
        $this->UserModel                    = $user_model;
        
        $this->arr_view_data                = [];
        $this->module_title                 = "Report";
        $this->ride_module_title            = "User";
        $this->PropertyModel                = $property_model;
        $this->UserModel                    = $user_model;
        $this->BookingModel                 = $booking_model;
        $this->CouponModel                  = $coupon_model;
        $this->SupportQueryModel            = $support_query_model;
        $this->QueryTypeModel               = $querytype_model;
        $this->SupportTeamModel             = $support_team_model;
        //$this->rating_module_title        = "Rating";
        //$this->rider_module_title         = "Rider";
        //$this->driver_module_title        = "Driver";
        $this->module_view_folder           = "admin.reports";
        $this->module_icon                  = "fa-file-text";
        $this->theme_color                  = theme_color();
        $this->admin_panel_slug             = config('app.project.admin_panel_slug');
        $this->module_url_path              = url(config('app.project.admin_panel_slug')."/report");

        $this->user_profile_public_img_path = url('/').config('app.project.img_path.user_profile_images');
        $this->user_profile_base_img_path   = base_path().config('app.project.img_path.user_profile_images');
    }

    public function index($report_type = false)
    {
        $this->arr_view_data['report_type']     = $report_type;
        $this->arr_view_data['page_title']      = str_singular(ucfirst($report_type).'s '.$this->module_title);
        $this->arr_view_data['module_title']    = str_singular($this->module_title);
        $this->arr_view_data['module_url_path'] = $this->module_url_path;
        $this->arr_view_data['theme_color']     = $this->theme_color;
        $this->arr_view_data['module_icon']     = $this->module_icon;
        $this->arr_view_data['admin_panel_slug'] = $this->admin_panel_slug;

        if(isset($report_type) && $report_type == 'register')
        {
            return view($this->module_view_folder.'.register',$this->arr_view_data);
        }
        elseif(isset($report_type) && $report_type == 'booking')
        {
            return view($this->module_view_folder.'.booking',$this->arr_view_data);
        }
        elseif(isset($report_type) && $report_type == 'refund')
        {
            return view($this->module_view_folder.'.refund',$this->arr_view_data);
        } 
        elseif(isset($report_type) && $report_type == 'cancel')
        {
            return view($this->module_view_folder.'.cancel',$this->arr_view_data);
        } 
        elseif(isset($report_type) && $report_type == 'ticket')
        {
            return view($this->module_view_folder.'.ticket',$this->arr_view_data);
        }  
    }

    public function load_register_data(Request $request)
    {
        $UserData   = $final_array = [];
        $column     = '';

        $start_date = $request->input('start_date');
        $end_date   = $request->input('end_date');

        if ($request->input('order')[0]['column'] == 1) {
            $column = "id";
        }
        
        if ($request->input('order')[0]['column'] == 2) {
            $column = "email";
        }

        if ($request->input('order')[0]['column'] == 3) {
            $column = "mobile_number";
        }

        if ($request->input('order')[0]['column'] == 4) {
            $column = "address";
        }

        if ($request->input('order')[0]['column'] == 5) {
            $column = "created_at";
        }    
       
        $order = strtoupper($request->input('order')[0]['dir']);  

        $arr_data           = [];
        $arr_search_column  = $request->input('column_filter');

        $keyword_table      = $this->UserModel->getTable(); 
        $obj_data           = DB::table($keyword_table)->select(DB::raw( $keyword_table.".id as id,".
                                            $keyword_table.".user_type as user_type,".
                                            $keyword_table.".first_name as first_name,".
                                            $keyword_table.".last_name as last_name,".
                                            $keyword_table.".email as email,".
                                            $keyword_table.".mobile_number as mobile_number,".
                                            $keyword_table.".address as address,".
                                            "DATE_FORMAT(".$keyword_table.".created_at,'%Y-%m-%d') as created_at" 
                                            ))
                                        ->orderBy($keyword_table.'.id','DESC');
                                        
        if(isset($start_date) && isset($end_date) && $start_date != "" && $end_date != "") {
            $obj_data = $obj_data->whereBetween($keyword_table.'.created_at',array(date('Y-m-d',strtotime($start_date)), date('Y-m-d',strtotime($end_date))));
        }

        $count        = count($obj_data->get());
       
        if ($order =='ASC' && $column=='') {
          $obj_data   = $obj_data->orderBy('id','DESC')->limit($_GET['length'])->offset($_GET['start']);
        }

        if ($order !='' && $column!='' ) {
          $obj_data   = $obj_data->orderBy($column,$order)->limit($_GET['length'])->offset($_GET['start']);
        }

        $UserData     = $obj_data->get();

        $resp['draw']            = $_GET['draw'];
        $resp['recordsTotal']    = $count;
        $resp['recordsFiltered'] = $count;
        $build_active_btn        = '' ; 

         if(count($UserData)>0) {
                $i = 0;
                foreach($UserData as $row) {
                    $final_array[$i][0] = "<input type='checkbox' name='checked_record[]' id='checked_record' class='checked_record' value='".base64_encode($row->id)."'/>";

                    $final_array[$i][1] = $row->first_name."&nbsp;".$row->last_name;
                   
                    $final_array[$i][2] = isset($row->email) && $row->email != ''?$row->email:'N/A';
                    $final_array[$i][3] = isset($row->mobile_number) && $row->mobile_number != ''?$row->mobile_number:'N/A';
                    $final_array[$i][4] = isset($row->address)?$row->address:'N/A';
                    $final_array[$i][5] = isset($row->created_at) ? get_added_on_date($row->created_at) : 'N/A';
                    $i++;
                }
            }

            $resp['data'] = $final_array;
            echo str_replace("\/", "/",  json_encode($resp));exit;  
    }

    public function register_export(Request $request)
    {
        $start_date = $request->input('e_start_date');
        $end_date   = $request->input('e_end_date');
        $records    = $request->input('records');

        $arr_search_column = $request->input('column_filter');
        $keyword_table     = $this->UserModel->getTable();
        
        $obj_data = DB::table($keyword_table)->select(DB::raw( $keyword_table.".id as id,".
                        $keyword_table.".user_type as user_type,".
                        $keyword_table.".user_name as user_name,".
                        $keyword_table.".display_name as display_name,".
                        $keyword_table.".first_name as first_name,".
                        $keyword_table.".last_name as last_name,".
                        $keyword_table.".email as email,".
                        $keyword_table.".mobile_number as mobile_number,".
                        $keyword_table.".address as address,".
                        $keyword_table.".gender as gender,".
                        $keyword_table.".birth_date as birth_date,".
                        "DATE_FORMAT(".$keyword_table.".created_at,'%Y-%m-%d') as created_at" 
                    ))
                    ->orderBy($keyword_table.'.id','DESC');
        
        if(isset($start_date) && isset($end_date) && $start_date != "" && $end_date != "" ) {
            $obj_data = $obj_data->whereBetween($keyword_table.'.created_at',array(date('Y-m-d',strtotime($start_date)), date('Y-m-d',strtotime($end_date))));
        }

        $temp_str = $final_str = ''; $temp_arr=[];
        if(isset($records) && $records != "") {
            $temp_str = explode(',', $records);
            
            if(isset($temp_str) && count($temp_str)) {
                foreach ($temp_str as $key => $value) {
                    if($value!='') {
                        array_push($temp_arr,base64_decode($value));
                    }
                }
                $final_str =  implode(',', $temp_arr);
                $obj_data = $obj_data->whereRaw($keyword_table.'.id IN('.$final_str.')');
            }
        }
        $student_arr  = $obj_data->get();  
        //dd($student_arr);
        $format = "xlsx";

        if ($format == "xlsx") {
            $arr_tmp = array();
            
            if (count($student_arr)>0) {
                \Excel::create('Registration_REPORT-'.date('Ymd').uniqid(), function($excel) use($student_arr) {
                    $excel->sheet('Registration', function($sheet) use($student_arr) {
                        
                        $sheet->cell('A1', function($cell) {
                            $cell->setValue('Generated on :'.date("d-m-Y H:i:s"));
                        });
                        
                        $sheet->row(2, array(
                            'User Type',
                            'Name',
                            'User Name',
                            'Display Name',
                            'Email',
                            'Mobile No',
                            'Address',
                            'Gender',
                            'Date of Birth',
                            'Registration Date'
                        ));
                        
                        $i = 0;
                        $type = "-";
                        $user_gender = "-";

                        foreach ($student_arr as $key => $ad) {
                            
                        	$first_name = isset($ad->first_name) && !empty($ad->first_name) ? ucfirst($ad->first_name) : '';
                        	$last_name = isset($ad->last_name) && !empty($ad->last_name) ? ucfirst($ad->last_name) : '';
                            $user_type = isset($ad->user_type) && !empty($ad->user_type) ? $ad->user_type : '';
                            if($user_type == 4) {
                                $type = "Host";
                            }
                            else if($user_type == 1) {
                                $type = "Guest";
                            }

                            $gender = isset($ad->gender) && !empty($ad->gender) ? $ad->gender : '';
                            if($gender == 0) {
                                $user_gender = "Female";
                            }
                            else if($gender == 1) {
                                $user_gender = "Male";
                            }

                            $arr_tmp[$key][] = $type;
                            $arr_tmp[$key][] = $first_name." ".$last_name;
                            $arr_tmp[$key][] = isset($ad->user_name) && !empty($ad->user_name) ? $ad->user_name : '';
                            $arr_tmp[$key][] = isset($ad->display_name) && !empty($ad->display_name) ? $ad->display_name : '';
                            $arr_tmp[$key][] = isset($ad->email) && !empty($ad->email) ? $ad->email : '';
                            $arr_tmp[$key][] = isset($ad->mobile_number) && !empty($ad->mobile_number) ? $ad->mobile_number : '';
                            $arr_tmp[$key][] = isset($ad->address) && !empty($ad->address) ? $ad->address : '';
                            $arr_tmp[$key][] = $user_gender;
                            $arr_tmp[$key][] = isset($ad->birth_date) && !empty($ad->birth_date) ? get_added_on_date($ad->birth_date) : '-';
                            $arr_tmp[$key][] = isset($ad->created_at) && !empty($ad->created_at) ? get_added_on_date($ad->created_at) : '-';
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

    public function reports(Request $request)
    {
        $arr_user = $start_date = $end_date = $flag = $type = $arr_transaction = $report_type = [];

        $arr_rules['start_date'] = "required";
        $arr_rules['end_date']   = "required";
        $arr_rules['type']       = "required";

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $start_date = $request->input('start_date');
        $end_date   = $request->input('end_date');
        
        $start_date1 =  (date('Y-m-d', strtotime($start_date)));
        $end_date1   =  (date('Y-m-d', strtotime($end_date)));

        if(isset($request->report_type) && $request->report_type == 'register')
        {         

           $report_type = $request->report_type;
           $keyword_table  = $this->UserModel->getTable(); 
           $query          = DB::table($keyword_table)->select(DB::raw( $keyword_table.".id as id,".
                                            $keyword_table.".user_type as user_type,".
                                            $keyword_table.".first_name as first_name,".
                                            $keyword_table.".last_name as last_name,".
                                            $keyword_table.".email as email,".
                                            $keyword_table.".mobile_number as mobile_number,".
                                            $keyword_table.".address as address,".

                                            "DATE_FORMAT(".$keyword_table.".created_at,'%Y-%m-%d') as created_at" 
                                            ));


           $type       = $request->input('type');                 

           if($type == 'guest' )
           {
                if($start_date1==$end_date1)
                {                     
                    $query->whereDate('created_at','=',$start_date1)->where('user_type' , '1');     
                }
                else
                {   
                  $query->whereDate('created_at',">=",$start_date1)
                        ->whereDate('created_at',"<=",$end_date1)   
                        ->where('user_type' , '1');             
                }

              //  dd($query->get());

                $flag = 'guest';  
           }
           else
           {    
                if($start_date1==$end_date1)
                    { 
                        $query->whereDate('created_at','=',$start_date1)->where('user_type' , '2');  
                    }
                    else
                    {
                      $query->whereDate('created_at',">=",$start_date1)
                            ->whereDate('created_at',"<=",$end_date1)
                            ->where('user_type' , '2');                                     
                    }

                    $flag = 'host';     
           }

            $obj_user = $query->whereNull('deleted_at')->get();
            if ($obj_user) 
            {
                $arr_user = json_decode(json_encode($obj_user),true);
            }           
           
            $this->arr_view_data['type']            = $type;
            $this->arr_view_data['report_type']     = $report_type;
            $this->arr_view_data['module_icon']     = $this->module_icon;
            $this->arr_view_data['flag']            = $flag;
            $this->arr_view_data['arr_user']        = $arr_user;
            $this->arr_view_data['start_date']      = $start_date;
            $this->arr_view_data['end_date']        = $end_date;
            $this->arr_view_data['page_title']      = 'Users '.str_singular($this->module_title);
            $this->arr_view_data['module_title']    = str_singular($this->module_title);
            $this->arr_view_data['module_url_path'] = $this->module_url_path;     
            $this->arr_view_data['admin_panel_slug'] = $this->admin_panel_slug;       

            return view($this->module_view_folder.'.register',$this->arr_view_data);
        }
    }

    public function download(Request $request)
    {        

        $form_data = $arr_user = $data= [];

        $fname = $lname = $email = $mobile_no = $address = $registration_date = "";

        $form_data = $request->all();       

        $payment_status             = isset($form_data['type']) ? $form_data['type'] : 0;
       
            $start_date     = isset($form_data['start_date']) ? $form_data['start_date'] : '';
            $end_date       = isset($form_data['end_date']) ? $form_data['end_date'] : '';
            $user_type      = isset($form_data['type']) ? $form_data['type'] : '';
 
            if($user_type == "guest")
            {
                $type = 1;
            }
            elseif($user_type == "host")
            {
                $type = 2;
            }
           

            $start_date1    =  (date('Y-m-d', strtotime($start_date)));
            $end_date1      =  (date('Y-m-d', strtotime($end_date)));

            $obj_user       =   $this->UserModel->whereDate('created_at',">=",$start_date1)
                                       ->whereDate('created_at',"<=",$end_date1)   
                                       ->where('user_type',$type)->get();
                             
            if($obj_user)
            {
                $arr_user = $obj_user->toArray();
            }
            

            if($form_data['export_type']== 'XLS')
            { 
                if(isset($arr_user) && !empty($arr_user))
                {
                    foreach($arr_user as $key=>$value)
                    {
                        $fname              = isset($value['first_name']) ? $value['first_name'] : 'NA';
                        $lname              = isset($value['last_name']) ? $value['last_name'] : 'NA';
                        $data['Serial no.'] = $key+1;
                        $data['Name']       = $fname.' '.$lname;
                        $data['Email']      = (isset($value['email']) && $value['email']!="") ? $value['email'] : 'NA';
                        $data['Mobile No.'] = (isset($value['mobile_number']) && $value['mobile_number']!="") ? $value['mobile_number'] : 'NA';
                        $data['Address']    = (isset($value['address']) && $value['address']!="") ? $value['address'] : 'NA';
                        $data['Date']       = (isset($value['created_at']) && $value['created_at']!="") ? get_added_on_date($value['created_at']) : 'NA';

                        array_push($this->arr_view_data, $data);   
                    }
                }

                $data = $this->arr_view_data;       
                $type = 'xls';

                return Excel::create('Users', function($excel) use ($data) {

                     // Set the title
                    $excel->setTitle('Users Backup');

                    // Chain the setters
                    $excel->setCreator('Shareous')
                    ->setCompany('Shareous');

                    // Call them separately
                    $excel->setDescription('Users Backup');

                    $excel->sheet('Users', function($sheet) use ($data)
                    {
                        $sheet->fromArray($data);
                    });
                })->download($type);
            }
            elseif($form_data['export_type'] == 'PDF')
            {
               /*if(isset($arr_user) && !empty($arr_user))
                {
                    foreach($arr_user as $key=>$value)
                    {
                        $fname              = isset($value['first_name']) ? $value['first_name'] : 'NA';
                        $lname              = isset($value['last_name']) ? $value['last_name'] : 'NA';
                        $data['Serial no.'] = $key+1;
                        $data['Name']       = $fname.' '.$lname;
                        $data['Email']      = (isset($value['email']) && $value['email']!="") ? $value['email'] : 'NA';
                        $data['Mobile No.'] = (isset($value['mobile_number']) && $value['mobile_number']!="") ? $value['mobile_number'] : 'NA';
                        $data['Address']    = (isset($value['address']) && $value['address']!="") ? $value['address'] : 'NA';
                        $data['Date']       = (isset($value['created_at']) && $value['created_at']!="") ? get_added_on_date($value['created_at']) : 'NA';

                        array_push($this->arr_view_data, $data);   
                    }
                }
                $data = $this->arr_view_data;       
                $type = 'pdf';

                return Excel::create('Users', function($excel) use ($data) {

                     // Set the title
                    $excel->setTitle('Users Backup');

                    // Chain the setters
                    $excel->setCreator('Shareous')
                    ->setCompany('Shareous');

                    // Call them separately
                    $excel->setDescription('Users Backup');

                    $excel->sheet('Users', function($sheet) use ($data)
                    {
                        $sheet->fromArray($data);
                    });
                })->download($type);*/

                $html ='';
                $html.= '<!DOCTYPE html>
                            <html><head>
                                    <style>
                                        table {
                                            font-family: arial, sans-serif;
                                            border-collapse: collapse;
                                            width: 100%;
                                        }

                                        td, th {
                                            border: 1px solid #dddddd;
                                            text-align: left;
                                            padding: 8px;
                                        }

                                        tr:nth-child(even) {
                                            background-color: #dddddd;
                                        }
                                    </style>
                                </head><body><table>
                                        <caption><h2>'.ucfirst($user_type).'</caption></h2><br>
                                            <tr>
                                                <th>Sr. No</th>';
                                                     
                                                          $html.= '<th>Name</th>
                                                                    <th>Email</th>
                                                                    <th>Mobile No.</th>
                                                                    <th>Address</th>
                                                                    <th>Registration Date</th></tr>';
                                                      

                                                       foreach ($arr_user as $key => $user) 
                                                       {
                                                            $html.=    '<tr><td>'.($key+1).'</td>
                                                                            <td>'.$user['first_name'].' '.$user['last_name'].'</td>
                                                                            <td>'.$user['email'].'</td>
                                                                            <td>'.$user['mobile_number'].'</td>
                                                                            <td>'.$user['address'].'</td>
                                                                            <td>'.get_added_on_date($user['created_at']).'</td>
                                                                        </tr>';
                                                       }
                            $html.='</table></body></html>';

                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($html);
                return $pdf->download('users');
          }
    }  

    public function booking()
    {
      
        $property_table          = $this->PropertyModel->getTable();   
        $prefixed_property_table = DB::getTablePrefix().$this->PropertyModel->getTable();

        $user_table              = $this->UserModel->getTable();   
        $prefixed_user_table     = DB::getTablePrefix().$this->UserModel->getTable();

        $booking_table           = $this->BookingModel->getTable();   
        $prefixed_booking_table  = DB::getTablePrefix().$this->BookingModel->getTable();

        $arr_owner     = $arr_booked_by =  $arr_property = [];

        $arr_owner     = DB::table($booking_table)
                             ->select(DB::raw(  
                                                  $prefixed_user_table.".id,".
                                                  $prefixed_user_table.".first_name as owner_firstname,".
                                                  $prefixed_user_table.".last_name as owner_lastname"
                                             ))
                             ->Join($prefixed_user_table,$prefixed_user_table.".id",' = ',$prefixed_booking_table.'.property_owner_id')
                             ->groupby('property_owner_id')->get();

        $arr_booked_by = DB::table($booking_table)
                             ->select(DB::raw(  
                                              $prefixed_user_table.".id,".
                                              $prefixed_user_table.".first_name as booked_by_firstname,".
                                              $prefixed_user_table.".last_name as booked_by_lastname"
                                         ))
                             ->Join($prefixed_user_table,$prefixed_user_table.".id",' = ',$prefixed_booking_table.'.property_booked_by')
                             ->groupby('property_booked_by')->get();


        $arr_property = DB::table($booking_table)
                             ->select(DB::raw(  
                                              $prefixed_property_table.".id,".
                                              $prefixed_property_table.".property_name"
                                         ))
                             ->Join($prefixed_property_table,$prefixed_property_table.".id",' = ',$prefixed_booking_table.'.property_id')
                             ->groupby('property_id')->get();

        $this->arr_data['arr_property']     = $arr_property;
        $this->arr_data['arr_owner']        = $arr_owner; 
        $this->arr_data['arr_booked_by']    = $arr_booked_by; 
      
        $this->arr_data['page_title']       = "Manage Booking Report";
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_title']     = $this->module_title;
        $this->arr_data['module_url_path']  = $this->module_url_path."/booking";
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;

        return view($this->module_view_folder.'.booking_report',$this->arr_data);        
    }

    public function load_booking_data(Request $request)
    {
        $column             = '';
        $UserData           = $final_array = [];
        $property_name      = $request->input('property_name');
        $property_owner_id  = $request->input('property_owner_id');
        $property_booked_id = $request->input('property_booked_id');
        $booking_date       = $request->input('booking_date');
       
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
            $column = "first_name";
        } 
        if ($request->input('order')[0]['column'] == 4) 
        {
            $column = "first_name";
        }    
        if ($request->input('order')[0]['column'] == 5) 
        {
            $column = "coupon_code";
        } 
        if ($request->input('order')[0]['column'] == 6) 
        {
            $column = "property_amount";
        }
        if ($request->input('order')[0]['column'] == 8) 
        {
            $column = "gst_amount";
        }
        if ($request->input('order')[0]['column'] == 9) 
        {
            $column = "total_night_price";
        }
        if ($request->input('order')[0]['column'] == 10) 
        {
            $column = "coupen_code_amount";
        }
        if ($request->input('order')[0]['column'] == 11) 
        {
            $column = "total_amount";
        }
        
        $order = strtoupper($request->input('order')[0]['dir']);
        $admin_status_id = $request->input('admin_status');
        
        $arr_status_id = ['1','2','3','4','5','6','7'];

        $property_table          = $this->PropertyModel->getTable();
        $prefixed_property_table = DB::getTablePrefix().$this->PropertyModel->getTable();

        $booking_table           = $this->BookingModel->getTable();
        $prefixed_booking_table  = DB::getTablePrefix().$this->BookingModel->getTable();

        $coupon_table            = $this->CouponModel->getTable();
        $prefixed_coupon_table   = DB::getTablePrefix().$this->CouponModel->getTable();

        $user_table              = $this->UserModel->getTable();
        $prefixed_user_table     = DB::getTablePrefix().$this->UserModel->getTable();

        $obj_data                = DB::table($booking_table)
                                    ->select(DB::raw(  
                                                        $prefixed_booking_table.".*,".
                                                        $prefixed_property_table.".property_name as property_name,".
                                                        $prefixed_property_table.".currency as property_currency,".
                                                        $prefixed_property_table.".currency_code as property_currency_code,".
                                                        $prefixed_coupon_table.".coupon_code as coupon_code,".
                                                        $prefixed_user_table.".first_name as owner_firstname,".
                                                        $prefixed_user_table.".last_name as owner_lastname,".
                                                        "DATE_FORMAT(".$prefixed_booking_table.".created_at,'%Y-%m-%d') as booking_date"
                                                    ))
                                    ->whereIn('booking_status',$arr_status_id)
                                    ->Join($prefixed_property_table,$prefixed_property_table.".id",' = ',$prefixed_booking_table.'.property_id')
                                    ->leftJoin($prefixed_coupon_table,$prefixed_coupon_table.".id",' = ',$prefixed_booking_table.'.coupon_code_id')
                                    ->leftJoin($prefixed_user_table,$prefixed_user_table.".id",' = ',$prefixed_booking_table.'.property_owner_id');
      
        if($property_name!='')
        {
            $obj_data = $obj_data->where($prefixed_booking_table.'.property_id',base64_decode($property_name));
        }  
        if($property_owner_id!='')
        {
            $obj_data = $obj_data->where($prefixed_booking_table.'.property_owner_id',base64_decode($property_owner_id));
        }
        if($property_booked_id !='')
        {
            $obj_data = $obj_data->where($prefixed_booking_table.'.property_booked_by',base64_decode($property_booked_id));
        }
        if($booking_date !='')
        {
            $book_date = date('Y-m-d',strtotime($booking_date));
            $obj_data  = $obj_data->where($prefixed_booking_table.'.created_at','LIKE', '%'.$book_date.'%');
        }
        
        $count = count($obj_data->get());

        if($order =='ASC' && $column=='')
        {
            $obj_data = $obj_data->orderBy('id','DESC')->limit($_GET['length'])->offset($_GET['start']);
        }
        if( $order !='' && $column!='' )
        {
            $obj_data = $obj_data->orderBy($column,$order)->limit($_GET['length'])->offset($_GET['start']);
        }

        $UserData = $obj_data->get();

        $resp['draw']            = $_GET['draw'];
        $resp['recordsTotal']    = $count;
        $resp['recordsFiltered'] = $count;
        $booking_status          = '' ; 

        if(count($UserData)>0)
        {
            $i = 0;

            foreach($UserData as $data)
            {
                $property_booked_by = [];
                $property_booked_by = isset( $data->property_booked_by ) && !empty( $data->property_booked_by ) ? $data->property_booked_by : '0';
                $property_booked_by = get_user_details($property_booked_by);

                $owner_firstname     = isset( $data->owner_firstname ) && !empty( $data->owner_firstname ) ? $data->owner_firstname : '';
                $owner_lastname      = isset( $data->owner_lastname ) && !empty( $data->owner_lastname ) ? $data->owner_lastname : '';
                $property_owner_name = $owner_firstname."&nbsp;".$owner_lastname;

                $first_name = isset( $property_booked_by->first_name ) && !empty( $property_booked_by->first_name ) ? $property_booked_by->first_name : '';
                $last_name  = isset( $property_booked_by->last_name ) && !empty( $property_booked_by->last_name ) ? $property_booked_by->last_name : '';
                $booked_by  = $first_name."&nbsp;".$last_name;

                if(!empty($data->coupon_code))
                {
                    $coupon_code = $data->coupon_code;
                }
                else
                {
                    $coupon_code = "-";
                }

                $booking_info        = "";


                $check_in_date  = get_added_on_date($data->check_in_date);
                $check_out_date = get_added_on_date($data->check_out_date);
                $booking_date   = get_added_on_date($data->booking_date);

                $booking_info       .= "<b>Coupon Code:</b><br>".$coupon_code."<br><b>Check In Date:</b><br>".$check_in_date."<br><b>Check Out Date:</b><br>".$check_out_date."<br><b>No Of Days:</b>&nbsp;".$data->no_of_days."<br><b>Booking Date</b><br>".$booking_date;


                if($data->booking_status != null && $data->booking_status == "4")
                {
                    $booking_info .= "<br><b>Reason For Rejection</b><br>".$data->reject_reason;
                }

                $amount = "<b>Property Amount: </b><br><i class='fa fa-inr' aria-hidden='true'></i> ".$data->property_amount."<br><b>GST: </b><br>".$data->gst_amount." %<br><b>Per Night Price: </b><br><i class='fa fa-inr' aria-hidden='true'></i> ".$data->total_night_price."<br><b>Coupon Code Amount: </b><br><i class='fa fa-inr' aria-hidden='true'></i> ".$data->coupen_code_amount."<br><b>Total Amount: </b><br><i class='fa fa-inr' aria-hidden='true'></i> ".$data->total_amount;

      
                    $booking_status = 'NA';

                    if($data->booking_status != null)
                    {
                        if($data->booking_status == 1)
                        {  
                            $booking_status = '<span class="badge badge-success" style="padding: 9px;">Accepted</span>';
                        }
                        elseif($data->booking_status == 2)
                        {
                            $booking_status = '<span class="badge badge-success" style="padding: 9px;">Confirmed</span>';
                        }
                        elseif($data->booking_status == 3)
                        {
                            $booking_status = '<span class="badge badge-info"  style="padding: 9px;">Awaiting</span>';
                        }
                        elseif($data->booking_status == 4)
                        {
                            $booking_status = '<span class="badge badge-info"  style="padding: 9px;">Rejected</span>';
                        }
                        elseif($data->booking_status == 5)
                        {
                            $booking_status = '<span class="badge badge-warning" style="padding: 9px;">Completed</span>';
                        }
                        elseif($data->booking_status == 6)
                        {
                            $booking_status = '<span class="badge badge-important" style="padding: 9px;">Cancel</span>';
                        }
                         elseif($data->booking_status == 7)
                        {
                            $booking_status = '<span class="badge badge-important" style="padding: 9px;">Requested</span>';
                        }
                    }
                    else
                    {
                        $booking_status = 'NA';
                    }
              
                $final_array[$i][0] = "<input type='checkbox' name='checked_record[]' id='checked_record' class='checked_record' value='".base64_encode($data->id)."'/>";

                $final_array[$i][1] = isset($data->property_name) && !empty($data->property_name) ? $data->property_name : '';
                $final_array[$i][2] = $property_owner_name;
                $final_array[$i][3] = $booked_by;
                $final_array[$i][4] = $booking_info;
                $final_array[$i][5] = $amount;
                $final_array[$i][6] = $booking_status;
                $i++;   

            }
        }

        $resp['data'] = $final_array;
        echo str_replace("\/", "/",  json_encode($resp));exit;
    }

    public function booking_export(Request $request)
    {
            $property_name      = $request->input('e_property_name');
            $property_owner_id  = $request->input('e_property_owner_id');
            $property_booked_id = $request->input('e_property_booked_id');
            $booking_date       = $request->input('e_booking_date');
            $records            = $request->input('records');
            $arr_search_column  = $request->input('column_filter');

            $property_table          = $this->PropertyModel->getTable();   
            $prefixed_property_table = DB::getTablePrefix().$this->PropertyModel->getTable();

            $booking_table           = $this->BookingModel->getTable();   
            $prefixed_booking_table  = DB::getTablePrefix().$this->BookingModel->getTable();

            $coupon_table            = $this->CouponModel->getTable();   
            $prefixed_coupon_table   = DB::getTablePrefix().$this->CouponModel->getTable();

            $user_table              = $this->UserModel->getTable();   
            $prefixed_user_table     = DB::getTablePrefix().$this->UserModel->getTable();

            $arr_status_id           = ['1','2','3','4','5','6','7']; 

            $obj_data                = DB::table($booking_table)
                                       ->select(DB::raw(  
                                                          $prefixed_booking_table.".*,".
                                                          $prefixed_property_table.".property_name as property_name,".
                                                          $prefixed_coupon_table.".coupon_code as coupon_code,".
                                                          $prefixed_user_table.".first_name as owner_firstname,".
                                                          $prefixed_user_table.".last_name as owner_lastname,".
                                                          "DATE_FORMAT(".$prefixed_booking_table.".created_at,'%Y-%m-%d') as booking_date"
                                                        ))
                                       ->whereIn('booking_status',$arr_status_id)
                                       ->Join($prefixed_property_table,$prefixed_property_table.".id",' = ',$prefixed_booking_table.'.property_id')
                                       ->leftJoin($prefixed_coupon_table,$prefixed_coupon_table.".id",' = ',$prefixed_booking_table.'.coupon_code_id')
                                       ->leftJoin($prefixed_user_table,$prefixed_user_table.".id",' = ',$prefixed_booking_table.'.property_owner_id');


          
            if($property_name!='')
            {
                $obj_data  = $obj_data->where($prefixed_booking_table.'.property_id',base64_decode($property_name));   
            }  
            if($property_owner_id!='')
            {
                $obj_data  = $obj_data->where($prefixed_booking_table.'.property_owner_id',base64_decode($property_owner_id)); 
            }
            if($property_booked_id !='')
            {
                $obj_data  = $obj_data->where($prefixed_booking_table.'.property_booked_by',base64_decode($property_booked_id));  
            }

            if($booking_date !='')
            {
                $book_date = date('Y-m-d',strtotime($booking_date));
                $obj_data  = $obj_data->where($prefixed_booking_table.'.created_at','LIKE', '%'.$book_date.'%'); 
            }

            $temp_str = $final_str = ''; $temp_arr=[];
            if(isset($records) && $records != "")
            {
                $temp_str = explode(',', $records);
                
                if(isset($temp_str) && count($temp_str))
                {
                    foreach ($temp_str as $key => $value) 
                    {
                      if($value!='')
                      {
                        array_push($temp_arr,base64_decode($value));
                      }
                    }
                    $final_str =  implode(',', $temp_arr);
                    $obj_data = $obj_data->whereRaw($prefixed_booking_table.'.id IN('.$final_str.')');
                }
            }

            $student_arr   = $obj_data/*->limit(10)*/->get(); 

           // dd($student_arr); 

            $format="xlsx";

            if($format=="xlsx")
            {
                $arr_tmp = array();
                
                if(count($student_arr)>0)
                {           
                     \Excel::create('Booking_REPORT-'.date('Ymd').uniqid(), function($excel) use($student_arr) 
                      {
                          $excel->sheet('Booking', function($sheet) use($student_arr) 
                          {
                              $sheet->cell('A1', function($cell) 
                              {
                                  $cell->setValue('Generated on :'.date("d-m-Y H:i:s"));
                              });
                              $sheet->row(2, array(
                                                       'Property Name',
                                                       'Owner Name',
                                                       'Booked By',
                                                       'Coupon Code',
                                                       'Check In Date',
                                                       'Check Out Date',
                                                       'No Of Days',
                                                       'Booking Date',
                                                       'Property Amount (in INR)',
                                                       'GST (in INR)',
                                                       'Per Night Price (in INR)',
                                                       'Coupon Code Amount (in INR)',
                                                       'Total Amount (in INR)',
                                                       'Reason For Rejection',
                                                       'Status'     
                                                  ));
                              $i = 0;

                              foreach($student_arr as $key => $ad)
                              {
                                    $property_booked_by_id = isset($ad->property_booked_by) && $ad->property_booked_by != null ? $ad->property_booked_by : "";

                                    $property_booked_by  = get_user_details($property_booked_by_id);

                                    $first_name = isset($property_booked_by->first_name) && $property_booked_by->first_name != null ? $property_booked_by->first_name : "";  
                                    $last_name = isset($property_booked_by->last_name) && $property_booked_by->last_name ? $property_booked_by->last_name : "";

                                    $booked_by = $first_name." ".$last_name;

                                    $owner_firstname = isset($ad->owner_firstname) && $ad->owner_firstname ? $ad->owner_firstname : "";
                                    $owner_lastname  = isset($ad->owner_lastname) && $ad->owner_lastname ? $ad->owner_lastname : "";

                                    $owner_full_name = $owner_firstname.' '.$owner_lastname;
                                    $arr_tmp[$key][] = isset($ad->property_name) ? $ad->property_name : "";
                                    $arr_tmp[$key][] = $owner_full_name;
                                    $arr_tmp[$key][] = $booked_by;
                                    $arr_tmp[$key][] = isset($ad->coupon_code) && $ad->coupon_code != '' ? $ad->coupon_code : 'N/A';
                                    $arr_tmp[$key][] = isset($ad->check_in_date) ? get_added_on_date($ad->check_in_date) : "";
                                    $arr_tmp[$key][] = isset($ad->check_out_date) ? get_added_on_date($ad->check_out_date) : "";
                                    $arr_tmp[$key][] = $ad->no_of_days;
                                    $arr_tmp[$key][] = isset($ad->booking_date) ? get_added_on_date($ad->booking_date) : "";
                                    $arr_tmp[$key][] = $ad->property_amount;
                                    $arr_tmp[$key][] = $ad->gst_amount;
                                    $arr_tmp[$key][] = $ad->total_night_price;
                                    $arr_tmp[$key][] = $ad->coupen_code_amount;
                                    $arr_tmp[$key][] = $ad->total_amount;
                                    $arr_tmp[$key][] = isset($ad->reject_reason) && $ad->reject_reason != '' ? $ad->reject_reason : 'N/A';

                                    if(isset($ad->booking_status) && $ad->booking_status != null)
                                    { 
                                        if($ad->booking_status == 1)    
                                        {  
                                           $arr_tmp[$key][]="Accepted";
                                        }
                                        elseif($ad->booking_status == 2)
                                        {
                                           $arr_tmp[$key][]="Confirmed";
                                        }
                                        elseif($ad->booking_status == 3)
                                        {
                                           $arr_tmp[$key][]="Awaiting";
                                        }
                                         elseif($ad->booking_status == 4)
                                        {
                                           $arr_tmp[$key][]="Rejected";
                                        }
                                        elseif($ad->booking_status == 5)
                                        {
                                           $arr_tmp[$key][]="Completed";
                                        }
                                        elseif($ad->booking_status == 6)
                                        {
                                           $arr_tmp[$key][]="Cancel";
                                        }
                                        elseif($ad->booking_status == 7)
                                        {
                                           $arr_tmp[$key][]="Requested";
                                        }
                                    }
                                    else
                                    {
                                        $arr_tmp[$key][]='NA';
                                    }  
                              } 
                              
                              $sheet->rows($arr_tmp);                                      
                          });
                      })->export('xlsx');
                }
                else
                {
                    $userMsg = 'Error occure while making export due to no data to create xlsx file';                    
                    Session::flash('error',$userMsg);
                    return redirect()->back();
                }
            }
    }

    public function cancellation()
    {
        $property_table          = $this->PropertyModel->getTable();   
        $prefixed_property_table = DB::getTablePrefix().$this->PropertyModel->getTable();

        $user_table              = $this->UserModel->getTable();   
        $prefixed_user_table     = DB::getTablePrefix().$this->UserModel->getTable();

        $booking_table           = $this->BookingModel->getTable();   
        $prefixed_booking_table  = DB::getTablePrefix().$this->BookingModel->getTable();

        $arr_owner     = $arr_booked_by =  $arr_property = [];

        $arr_status_id  = ['6','7'];

        $arr_owner     = DB::table($booking_table)
                             ->select(DB::raw(  
                                                  $prefixed_user_table.".id,".
                                                  $prefixed_user_table.".first_name as owner_firstname,".
                                                  $prefixed_user_table.".last_name as owner_lastname"
                                             ))
                             ->Join($prefixed_user_table,$prefixed_user_table.".id",' = ',$prefixed_booking_table.'.property_owner_id')
                             ->whereIn($prefixed_booking_table.'.booking_status',$arr_status_id)
                             ->groupby('property_owner_id')->get();

        $arr_booked_by = DB::table($booking_table)
                             ->select(DB::raw(  
                                              $prefixed_user_table.".id,".
                                              $prefixed_user_table.".first_name as booked_by_firstname,".
                                              $prefixed_user_table.".last_name as booked_by_lastname"
                                         ))
                             ->Join($prefixed_user_table,$prefixed_user_table.".id",' = ',$prefixed_booking_table.'.property_booked_by')
                             ->whereIn($prefixed_booking_table.'.booking_status',$arr_status_id)
                             ->groupby('property_booked_by')->get();


        $arr_property = DB::table($booking_table)
                             ->select(DB::raw(  
                                              $prefixed_property_table.".id,".
                                              $prefixed_property_table.".property_name"
                                         ))
                             ->Join($prefixed_property_table,$prefixed_property_table.".id",' = ',$prefixed_booking_table.'.property_id')
                             ->whereIn($prefixed_booking_table.'.booking_status',$arr_status_id)
                             ->groupby('property_id')->get();

        

        $this->arr_data['arr_property']     = $arr_property;
        $this->arr_data['arr_owner']        = $arr_owner; 
        $this->arr_data['arr_booked_by']    = $arr_booked_by; 
      
        $this->arr_data['page_title']       = "Manage Cancellation Report";
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_title']     = $this->module_title;
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;

        return view($this->module_view_folder.'.cancellation_report',$this->arr_data);        
    }


    public function load_cancellation_data(Request $request)
    {
        $UserData               =  $final_array=[]; 
        $column                 = '';
        $property_name          = $request->input('property_name');
        $property_owner_id      = $request->input('property_owner_id');
        $property_booked_id     = $request->input('property_booked_id'); 
        $booking_date           = $request->input('booking_date'); 
       
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
            $column = "first_name";
        } 
        if ($request->input('order')[0]['column'] == 4) 
        {
            $column = "first_name";
        }    
        if ($request->input('order')[0]['column'] == 5) 
        {
            $column = "coupon_code";
        } 
        if ($request->input('order')[0]['column'] == 6) 
        {
            $column = "property_amount";
        }
        if ($request->input('order')[0]['column'] == 8) 
        {
            $column = "gst_amount";
        }
        if ($request->input('order')[0]['column'] == 9) 
        {
            $column = "total_night_price";
        }
        if ($request->input('order')[0]['column'] == 10) 
        {
            $column = "coupen_code_amount";
        }
        if ($request->input('order')[0]['column'] == 11) 
        {
            $column = "total_amount";
        }
        
        $order           = strtoupper($request->input('order')[0]['dir']);  

        $admin_status_id = $request->input('admin_status');

        
        $arr_status_id  = ['6','7'];
       
       
        $property_table          = $this->PropertyModel->getTable();   
        $prefixed_property_table = DB::getTablePrefix().$this->PropertyModel->getTable();

        $booking_table           = $this->BookingModel->getTable();   
        $prefixed_booking_table  = DB::getTablePrefix().$this->BookingModel->getTable();

        $coupon_table            = $this->CouponModel->getTable();   
        $prefixed_coupon_table   = DB::getTablePrefix().$this->CouponModel->getTable();

        $user_table              = $this->UserModel->getTable();   
        $prefixed_user_table     = DB::getTablePrefix().$this->UserModel->getTable();

        $obj_data                = DB::table($booking_table)
                                   ->select(DB::raw(  
                                                      $prefixed_booking_table.".*,".
                                                      $prefixed_property_table.".property_name as property_name,".
                                                      $prefixed_coupon_table.".coupon_code as coupon_code,".
                                                      $prefixed_user_table.".first_name as owner_firstname,".
                                                      $prefixed_user_table.".last_name as owner_lastname,".
                                                      "DATE_FORMAT(".$prefixed_booking_table.".created_at,'%Y-%m-%d') as booking_date"
                                                    ))
                                   
                                   ->whereIn($prefixed_booking_table.'.booking_status',$arr_status_id) 
                                   ->Join($prefixed_property_table,$prefixed_property_table.".id",' = ',$prefixed_booking_table.'.property_id')
                                   ->leftJoin($prefixed_coupon_table,$prefixed_coupon_table.".id",' = ',$prefixed_booking_table.'.coupon_code_id')
                                   ->leftJoin($prefixed_user_table,$prefixed_user_table.".id",' = ',$prefixed_booking_table.'.property_owner_id');

        if($property_name!='')
        {
            $obj_data = $obj_data->where($prefixed_booking_table.'.property_id',base64_decode($property_name));   
        }  
        if($property_owner_id!='')
        {
            $obj_data = $obj_data->where($prefixed_booking_table.'.property_owner_id',base64_decode($property_owner_id)); 
        }
        if($property_booked_id !='')
        {
            $obj_data = $obj_data->where($prefixed_booking_table.'.property_booked_by',base64_decode($property_booked_id));  
        }

        if($booking_date !='')
        {
            $book_date = date('Y-m-d',strtotime($booking_date));
            $obj_data  = $obj_data->where($prefixed_booking_table.'.created_at','LIKE', '%'.$book_date.'%'); 
        }
                           
        $count = count($obj_data->get());

        if($order =='ASC' && $column=='')
        {
            $obj_data = $obj_data->orderBy('id','DESC')->limit($_GET['length'])->offset($_GET['start']);
        }
        if( $order !='' && $column!='' )
        {
            $obj_data = $obj_data->orderBy($column,$order)->limit($_GET['length'])->offset($_GET['start']);
        }

        $UserData = $obj_data->get();

        $resp['draw']            = $_GET['draw'];
        $resp['recordsTotal']    = $count;
        $resp['recordsFiltered'] = $count;
        $booking_status          = '' ; 

        if(count($UserData)>0)
        {
            $i = 0;

            foreach($UserData as $data)
            {
                $property_booked_by  = [];
                $property_booked_by  = get_user_details($data->property_booked_by);
                
                $property_owner_name = $data->owner_firstname."&nbsp;".$data->owner_lastname;

                $booked_by           = isset($property_booked_by->first_name) ? $property_booked_by->first_name." ".$property_booked_by->last_name : 'NA';

                if(!empty($data->coupon_code))
                {
                    $coupon_code     = $data->coupon_code;
                }
                else
                {
                    $coupon_code     = "-";
                }
                $booking_info        = "";
                $booking_info       .= "<b>Coupon Code:</b><br>".$coupon_code."<br><b>Check In Date:</b><br>".get_added_on_date($data->check_in_date)."<br><b>Check Out Date:</b><br>".get_added_on_date($data->check_out_date)."<br><b>No Of Days:</b>&nbsp;".$data->no_of_days."<br><b>Booking Date</b><br>".get_added_on_date($data->booking_date);

                if($data->booking_status != null && $data->booking_status == "4")
                {
                    $booking_info   .= "<br><b>Reason For Rejection</b><br>".$data->reject_reason;
                }
               
                $amount = "<b>Property Amount</b><br>₹ ".$data->property_amount."<br><b>GST</b><br>".$data->gst_amount." %<br><b>Per Night Price</b><br>₹ ".$data->total_night_price."<br><b>Coupon Code Amount</b><br>₹ ".$data->coupen_code_amount."<br><b>Total Amount</b><br>₹ ".$data->total_amount;

                    $booking_status = 'NA';

                    if($data->booking_status != null)
                    {
                        if($data->booking_status == 1)
                        {  
                            $booking_status = '<span class="badge badge-success" style="padding: 9px;">Accepted</span>';
                        }
                        elseif($data->booking_status == 2)
                        {
                            $booking_status = '<span class="badge badge-success" style="padding: 9px;">Confirmed</span>';
                        }
                        elseif($data->booking_status == 3)
                        {
                            $booking_status = '<span class="badge badge-info" style="padding: 9px;">Awaiting</span>';
                        }
                        elseif($data->booking_status == 4)
                        {
                            $booking_status = '<span class="badge badge-info" style="padding: 9px;">Rejected</span>';
                        }
                        elseif($data->booking_status == 5)
                        {
                            $booking_status = '<span class="badge badge-warning" style="padding: 9px;">Completed</span>';
                        }
                        elseif($data->booking_status == 6)
                        {
                            $booking_status = '<span class="badge badge-important" style="padding: 9px;">Cancel</span>';
                        }
                        elseif($data->booking_status == 7)
                        {
                            $booking_status = '<span class="badge badge-important" style="padding: 9px;">Requested</span>';
                        }
                    }
                    else
                    {
                        $booking_status = 'NA';
                    }
              

                $final_array[$i][0]    = "<input type='checkbox' name='checked_record[]' id='checked_record' class='checked_record' value='".base64_encode($data->id)."'/>";

                $final_array[$i][1] = $data->property_name;
                $final_array[$i][2] = $property_owner_name;
                $final_array[$i][3] = $booked_by;
                $final_array[$i][4] = $booking_info;
                $final_array[$i][5] = $amount;
                $final_array[$i][6] = $booking_status;
                $i++;   

            }
        }

        $resp['data'] = $final_array;
        echo str_replace("\/", "/",  json_encode($resp));exit;
    }

    public function cancellation_export(Request $request)
    {

            $property_name      = $request->input('e_property_name');
            $property_owner_id  = $request->input('e_property_owner_id');
            $property_booked_id = $request->input('e_property_booked_id');
            $booking_date       = $request->input('e_booking_date');
            $records            = $request->input('records');
            $arr_search_column  = $request->input('column_filter');

            $property_table          = $this->PropertyModel->getTable();   
            $prefixed_property_table = DB::getTablePrefix().$this->PropertyModel->getTable();

            $booking_table           = $this->BookingModel->getTable();   
            $prefixed_booking_table  = DB::getTablePrefix().$this->BookingModel->getTable();

            $coupon_table            = $this->CouponModel->getTable();   
            $prefixed_coupon_table   = DB::getTablePrefix().$this->CouponModel->getTable();

            $user_table              = $this->UserModel->getTable();   
            $prefixed_user_table     = DB::getTablePrefix().$this->UserModel->getTable();

            $arr_status_id           = ['6','7']; 

            $obj_data                = DB::table($booking_table)
                                       ->select(DB::raw(  
                                                          $prefixed_booking_table.".*,".
                                                          $prefixed_property_table.".property_name as property_name,".
                                                          $prefixed_coupon_table.".coupon_code as coupon_code,".
                                                          $prefixed_user_table.".first_name as owner_firstname,".
                                                          $prefixed_user_table.".last_name as owner_lastname,".
                                                          "DATE_FORMAT(".$prefixed_booking_table.".created_at,'%Y-%m-%d') as booking_date"
                                                        ))
                                       ->whereIn($prefixed_booking_table.'.booking_status',$arr_status_id)
                                       ->Join($prefixed_property_table,$prefixed_property_table.".id",' = ',$prefixed_booking_table.'.property_id')
                                       ->leftJoin($prefixed_coupon_table,$prefixed_coupon_table.".id",' = ',$prefixed_booking_table.'.coupon_code_id')
                                       ->leftJoin($prefixed_user_table,$prefixed_user_table.".id",' = ',$prefixed_booking_table.'.property_owner_id');
          
            if($property_name!='')
            {
                $obj_data  = $obj_data->where($prefixed_booking_table.'.property_id',base64_decode($property_name));   
            }  
            if($property_owner_id!='')
            {
                $obj_data  = $obj_data->where($prefixed_booking_table.'.property_owner_id',base64_decode($property_owner_id)); 
            }
            if($property_booked_id !='')
            {
                $obj_data  = $obj_data->where($prefixed_booking_table.'.property_booked_by',base64_decode($property_booked_id));  
            }

            if($booking_date !='')
            {
                $book_date = date('Y-m-d',strtotime($booking_date));
                $obj_data  = $obj_data->where($prefixed_booking_table.'.created_at','LIKE', '%'.$book_date.'%'); 
            }

            $temp_str = $final_str = ''; $temp_arr=[];
            if(isset($records) && $records != "")
            {
                $temp_str = explode(',', $records);
                
                if(isset($temp_str) && count($temp_str))
                {
                    foreach ($temp_str as $key => $value) 
                    {
                      if($value!='')
                      {
                        array_push($temp_arr,base64_decode($value));
                      }
                    }
                    $final_str =  implode(',', $temp_arr);
                    $obj_data = $obj_data->whereRaw($prefixed_booking_table.'.id IN('.$final_str.')');
                }
            }

            $student_arr   = $obj_data->get(); 

        
            $format="xlsx";

            if($format=="xlsx")
            {
                $arr_tmp = array();
                
                if(count($student_arr)>0)
                {           
                     \Excel::create('Booking_REPORT-'.date('Ymd').uniqid(), function($excel) use($student_arr) 
                      {
                          $excel->sheet('Booking', function($sheet) use($student_arr) 
                          {
                              $sheet->cell('A1', function($cell) 
                              {
                                  $cell->setValue('Generated on :'.date("d-m-Y H:i:s"));
                              });
                              $sheet->row(2, array(
                                                       'Property Name',
                                                       'Owner Name',
                                                       'Booked By',
                                                       'Coupon Code',
                                                       'Check In Date',
                                                       'Check Out Date',
                                                       'No Of Days',
                                                       'Booking Date',
                                                       'Property Amount (in INR)',
                                                       'GST Amount (in INR)',
                                                       'Per Night Price (in INR)',
                                                       'Coupon Code Amount (in INR)',
                                                       'Total Amount (in INR)',
                                                       'Reason For Rejection',
                                                       'Status'     
                                                  ));
                              $i = 0;

                              foreach($student_arr as $key => $ad)
                              {
                                    $property_booked_by  = get_user_details($ad->property_booked_by);
                                    $booked_by           = isset($property_booked_by->first_name) ? $property_booked_by->first_name." ".$property_booked_by->last_name : 'NA';

                                    $arr_tmp[$key][] = $ad->property_name;
                                    $arr_tmp[$key][] = $ad->owner_firstname." ".$ad->owner_lastname;
                                    $arr_tmp[$key][] = $booked_by;
                                    $arr_tmp[$key][] = isset($ad->coupon_code) && $ad->coupon_code != ''?$ad->coupon_code:'N/A';
                                    $arr_tmp[$key][] = get_added_on_date($ad->check_in_date);
                                    $arr_tmp[$key][] = get_added_on_date($ad->check_out_date);
                                    $arr_tmp[$key][] = $ad->no_of_days;
                                    $arr_tmp[$key][] = $ad->booking_date;
                                    $arr_tmp[$key][] = $ad->property_amount;
                                    $arr_tmp[$key][] = $ad->gst_amount;
                                    $arr_tmp[$key][] = $ad->total_night_price;
                                    $arr_tmp[$key][] = $ad->coupen_code_amount;
                                    $arr_tmp[$key][] = $ad->total_amount;
                                    $arr_tmp[$key][] = isset($ad->reject_reason) && $ad->reject_reason != ''?$ad->reject_reason:'N/A';

                                    if($ad->booking_status != null)
                                    {                                        
                                        if($ad->booking_status == 1)
                                        {  
                                           $arr_tmp[$key][]="Accepted";
                                        }
                                        elseif($ad->booking_status == 2)
                                        {
                                           $arr_tmp[$key][]="Confirmed";
                                        }
                                        elseif($ad->booking_status == 3)
                                        {
                                           $arr_tmp[$key][]="Awaiting";
                                        }
                                         elseif($ad->booking_status == 4)
                                        {
                                           $arr_tmp[$key][]="Rejected";
                                        }
                                        elseif($ad->booking_status == 5)
                                        {
                                           $arr_tmp[$key][]="Completed";
                                        }
                                        elseif($ad->booking_status == 6)
                                        {
                                           $arr_tmp[$key][]="Cancel";
                                        }
                                        elseif($ad->booking_status == 7)
                                        {
                                            $arr_tmp[$key][]="Requested";
                                        }
                                    }
                                    else
                                    {
                                        $arr_tmp[$key][]='NA';
                                    }    
                              } 
                              $sheet->rows($arr_tmp);                                      
                          });
                      })
                    //->export('xlsx');
                    ->export('csv');
                }
                else
                {
                    $userMsg = 'Error occure while making export due to no data to create csv file';
                    Session::flash('error',$userMsg);
                    return redirect()->back();
                }
            }
    }

    public function ticket(Request $request)
    {
        $support_query_table           = $this->SupportQueryModel->getTable();   
        $prefixed_support_query_table  = DB::getTablePrefix().$this->SupportQueryModel->getTable();

        $query_type_table              = $this->QueryTypeModel->getTable();   
        $prefixed_query_type_table     = DB::getTablePrefix().$this->QueryTypeModel->getTable();

        $support_team_table            = $this->SupportTeamModel->getTable();   
        $prefixed_support_team_table   = DB::getTablePrefix().$this->SupportTeamModel->getTable();

        $user_table                    = $this->UserModel->getTable();   
        $prefixed_user_table           = DB::getTablePrefix().$this->UserModel->getTable();

        $arr_generated_by     = $arr_query_type =  $arr_support_user = [];

        $arr_generated_by     = DB::table($support_query_table)
                             ->select(DB::raw(  
                                                  $prefixed_user_table.".id,".
                                                  $prefixed_user_table.".first_name as generated_by_firstname,".
                                                  $prefixed_user_table.".last_name as generated_by_lastname"
                                             ))
                             ->Join($prefixed_user_table,$prefixed_user_table.".id",' = ',$support_query_table.'.user_id')
                             ->groupby($support_query_table.'.user_id')->get();


         $arr_query_type     = DB::table($support_query_table)
                             ->select(DB::raw(  
                                                  $prefixed_query_type_table.".id,".
                                                  $prefixed_query_type_table.".query_type"
                                                 
                                             ))
                             ->Join($prefixed_query_type_table,$prefixed_query_type_table.".id",' = ',$support_query_table.'.query_type_id')
                             ->groupby($support_query_table.'.query_type_id')->get(); 

        $arr_support_user     = DB::table($support_query_table)
                             ->select(DB::raw(  
                                                  $prefixed_support_team_table.".id,".
                                                  $prefixed_support_team_table.".first_name as support_firstname,".
                                                  $prefixed_support_team_table.".last_name as support_lastname"
                                                 
                                             ))
                             ->Join($prefixed_support_team_table,$prefixed_support_team_table.".id",' = ',$support_query_table.'.support_user_id')
                             ->groupby($support_query_table.'.support_user_id')->get();

        $this->arr_data['arr_generated_by']     = $arr_generated_by;
        $this->arr_data['arr_query_type']       = $arr_query_type; 
        $this->arr_data['arr_support_user']     = $arr_support_user; 
        $this->arr_data['page_title']       = "Manage Ticket Report";
        $this->arr_data['ticket_type']       = $request->input('type');
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_title']     = $this->module_title;
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;

        return view($this->module_view_folder.'.ticket_report',$this->arr_data);        
    }

    public function load_ticket_data(Request $request)
    {
        $UserData         =  $final_array=[]; 
        $column           = '';
        $generated_by     = $request->input('generated_by');
        $query_type       = $request->input('query_type');
        $support_user     = $request->input('support_user'); 
        $ticket_type      = $request->input('ticket_type'); 
       
        if ($request->input('order')[0]['column'] == 1) 
        {
            $column = "user_id";
        }           
        if ($request->input('order')[0]['column'] == 2) 
        {
            $column = "query_type_id";
        }     
        if ($request->input('order')[0]['column'] == 3) 
        {
            $column = "support_user_id";
        } 
        if ($request->input('order')[0]['column'] == 4) 
        {
            $column = "query_subject";
        }    
        if ($request->input('order')[0]['column'] == 5) 
        {
            $column = "query_description";
        } 
        if ($request->input('order')[0]['column'] == 6) 
        {
            $column = "created_at";
        }
       
        $order           = strtoupper($request->input('order')[0]['dir']);  

        $support_query_table           = $this->SupportQueryModel->getTable();   
        $prefixed_support_query_table  = DB::getTablePrefix().$this->SupportQueryModel->getTable();

        $query_type_table              = $this->QueryTypeModel->getTable();   
        $prefixed_query_type_table     = DB::getTablePrefix().$this->QueryTypeModel->getTable();

        $support_team_table            = $this->SupportTeamModel->getTable();   
        $prefixed_support_team_table   = DB::getTablePrefix().$this->SupportTeamModel->getTable();

        $user_table                    = $this->UserModel->getTable();   
        $prefixed_user_table           = DB::getTablePrefix().$this->UserModel->getTable();

        $obj_data                = DB::table($support_query_table)
                                   ->select(DB::raw(  
                                                      $support_query_table.".*,".
                                                      $prefixed_query_type_table.".query_type ,".
                                                      $prefixed_support_team_table.".first_name as support_firstname,".
                                                      $prefixed_support_team_table.".last_name as support_lastname,".
                                                      $prefixed_user_table.".first_name as generated_by_firstname,".
                                                      $prefixed_user_table.".last_name as generated_by_lastname,".
                                                      "DATE_FORMAT(".$support_query_table.".created_at,'%Y-%m-%d') as ticket_date"
                                                    ))
                                   ->leftJoin($prefixed_support_team_table,$prefixed_support_team_table.".id",' = ',$support_query_table.'.support_user_id')
                                   ->leftJoin($prefixed_query_type_table,$prefixed_query_type_table.".id",' = ',$support_query_table.'.query_type_id')
                                   ->leftJoin($prefixed_user_table,$prefixed_user_table.".id",' = ',$support_query_table.'.user_id');
       
        if ($ticket_type == 'closed') 
        {
            $obj_data = $obj_data->where($prefixed_support_query_table.'.status','3');  
        }
        if ($ticket_type == 'open') 
        {
            $obj_data = $obj_data->where($prefixed_support_query_table.'.status','1');  
        }                            
        if($generated_by!='')
        {
            $obj_data = $obj_data->where($prefixed_support_query_table.'.user_id',base64_decode($generated_by));   
        }  
        if($query_type!='')
        {
            $obj_data = $obj_data->where($prefixed_support_query_table.'.query_type_id',base64_decode($query_type)); 
        }
        if($support_user !='')
        {
            $obj_data = $obj_data->where($prefixed_support_query_table.'.support_user_id',base64_decode($support_user));  
        }
             
        $count         = count($obj_data->get());

        if($order =='ASC' && $column=='')
        {
          $obj_data    = $obj_data->orderBy('id','DESC')->limit($_GET['length'])->offset($_GET['start']);
        }
        if( $order !='' && $column!='' )
        {
          $obj_data    = $obj_data->orderBy($column,$order)->limit($_GET['length'])->offset($_GET['start']);
        }

        $UserData      = $obj_data->get();

        $resp['draw']            = $_GET['draw'];
        $resp['recordsTotal']    = $count;
        $resp['recordsFiltered'] = $count;
        $booking_status          = '' ; 

        if(count($UserData)>0)
        {
            $i = 0;

            foreach($UserData as $data)
            {
                if($data->status == '1')
                {
                    $status = "Open";
                }
                else if($data->status == '2')
                {
                     $status = "Assigned";
                }
                else if($data->status == '3')
                {
                     $status = "Closed";
                }
              
                $final_array[$i][0] = "<input type='checkbox' name='checked_record[]' id='checked_record' class='checked_record' value='".base64_encode($data->id)."'/>";

                $final_array[$i][1] = isset($data->generated_by_firstname) && $data->generated_by_lastname != '' && $data->generated_by_firstname != ''?$data->generated_by_firstname."&nbsp;".$data->generated_by_lastname:'N/A';
                $final_array[$i][2] = isset($data->query_type) && $data->query_type!=''?$data->query_type:"N/A";
                $final_array[$i][3] = isset($data->support_firstname) && $data->support_lastname != '' && $data->support_firstname != ''?$data->support_firstname."&nbsp;".$data->support_lastname:'N/A';
                $final_array[$i][4] = $data->query_subject;
                $final_array[$i][5] = $data->query_description;
                $final_array[$i][6] = isset($data->ticket_date) && $data->ticket_date != '' ? get_added_on_date($data->ticket_date) : "N/A";
                $final_array[$i][7] = $status;
                $i++;   
            }
        }

        $resp['data'] = $final_array;
        echo str_replace("\/", "/",  json_encode($resp));exit;
    }

    public function ticket_export(Request $request)
    {
        $generated_by = $request->input('e_generated_by');
        $query_type   = $request->input('e_query_type');
        $support_user = $request->input('e_support_user');
        $records      = $request->input('records');
                //DB::enableQueryLog();
        $support_query_table           = $this->SupportQueryModel->getTable();   
        $prefixed_support_query_table  = DB::getTablePrefix().$this->SupportQueryModel->getTable();

        $query_type_table              = $this->QueryTypeModel->getTable();   
        $prefixed_query_type_table     = DB::getTablePrefix().$this->QueryTypeModel->getTable();

        $support_team_table            = $this->SupportTeamModel->getTable();   
        $prefixed_support_team_table   = DB::getTablePrefix().$this->SupportTeamModel->getTable();

        $user_table                    = $this->UserModel->getTable();   
        $prefixed_user_table           = DB::getTablePrefix().$this->UserModel->getTable();

        $obj_data                = DB::table($support_query_table)
                                   ->select(DB::raw(  
                                                      $support_query_table.".*,".
                                                      $prefixed_query_type_table.".query_type ,".
                                                      $prefixed_support_team_table.".first_name as support_firstname,".
                                                      $prefixed_support_team_table.".last_name as support_lastname,".
                                                      $prefixed_user_table.".first_name as generated_by_firstname,".
                                                      $prefixed_user_table.".last_name as generated_by_lastname,".
                                                      "DATE_FORMAT(".$support_query_table.".created_at,'%Y-%m-%d') as ticket_date"
                                                    ))
                                   ->leftJoin($prefixed_support_team_table,$prefixed_support_team_table.".id",' = ',$support_query_table.'.support_user_id')
                                   ->leftJoin($prefixed_query_type_table,$prefixed_query_type_table.".id",' = ',$support_query_table.'.query_type_id')
                                   ->leftJoin($prefixed_user_table,$prefixed_user_table.".id",' = ',$support_query_table.'.user_id');

        if($generated_by!='')
        {
            $obj_data = $obj_data->where($prefixed_support_query_table.'.user_id',base64_decode($generated_by));   
        }  
        if($query_type!='')
        {
            $obj_data = $obj_data->where($prefixed_support_query_table.'.query_type_id',base64_decode($query_type)); 
        }
        if($support_user !='')
        {
            $obj_data = $obj_data->where($prefixed_support_query_table.'.support_user_id',base64_decode($support_user));  
        }

        $temp_str = $final_str = ''; $temp_arr=[];
        if(isset($records) && $records != "")
        {
            $temp_str = explode(',', $records);
            
            if(isset($temp_str) && count($temp_str))
            {
                foreach ($temp_str as $key => $value) 
                {
                  if($value!='')
                  {
                    array_push($temp_arr,base64_decode($value));
                  }
                }  
                //dump($temp_arr);              
                $final_str =  implode(',', $temp_arr);
                $obj_data = $obj_data->whereRaw($prefixed_support_query_table.'.id IN('.$final_str.')');
            }
        }
        $student_arr   = $obj_data->get();         
        $format="xlsx";

        if($format=="xlsx")
        {
            $arr_tmp = array();
            
            if(count($student_arr)>0)
            {           
                 \Excel::create('TICKET_REPORT-'.date('Ymd').uniqid(), function($excel) use($student_arr) 
                  {
                      $excel->sheet('Ticket', function($sheet) use($student_arr) 
                      {
                          $sheet->cell('A1', function($cell) 
                          {
                              $cell->setValue('Generated on :'.date("d-m-Y H:i:s"));
                          });
                          $sheet->row(2, array(
                                                   'Generated By',
                                                   'Query Type',
                                                   'Support User',
                                                   'Subject',
                                                   'Description',
                                                   'Date',
                                                   'Status'     
                                              ));
                          $i = 0;

                          foreach($student_arr as $key => $ad)
                          {

                            $support_user    = isset($ad->support_firstname) && $ad->support_lastname != '' && $ad->support_firstname != ''?$ad->support_firstname.' '.$ad->support_lastname:'N/A';
                            $arr_tmp[$key][] = isset($ad->generated_by_firstname) && $ad->generated_by_lastname != '' && $ad->generated_by_firstname != ''?$ad->generated_by_firstname." ".$ad->generated_by_lastname:'N/A';
                            $arr_tmp[$key][] = isset($ad->query_type) && $ad->query_type!=''?$ad->query_type:"N/A";
                            $arr_tmp[$key][] = $support_user;
                            $arr_tmp[$key][] = $ad->query_subject;
                            $arr_tmp[$key][] = $ad->query_description;;
                            $arr_tmp[$key][] = isset($ad->ticket_date) && $ad->ticket_date != '' ? get_added_on_date($ad->ticket_date) : "N/A";

                            if($ad->status == 1)
                            {  
                               $arr_tmp[$key][]="Open";
                            }
                            elseif($ad->status == 2)
                            {
                               $arr_tmp[$key][]="Assigned";
                            }
                            elseif($ad->status == 3)
                            {
                               $arr_tmp[$key][]="Close";
                            }
                              
                          } 
                          $sheet->rows($arr_tmp);                                      
                      });
                  })->export('xlsx');
            }
            else
            {
                $userMsg = 'Error occure while making export due to no data to create xlsx file';                
                Session::flash('error',$userMsg);
                return redirect()->back();
            }
        }
    }
    public function ticket_statistics()
    {
        $arr_total_no_tickets =    [];

        /*All Tickets count*/
        $support_query_table           = $this->SupportQueryModel->getTable();   
        $prefixed_support_query_table  = DB::getTablePrefix().$this->SupportQueryModel->getTable();

        $query_type_table              = $this->QueryTypeModel->getTable();   
        $prefixed_query_type_table     = DB::getTablePrefix().$this->QueryTypeModel->getTable();

        $support_team_table            = $this->SupportTeamModel->getTable();   
        $prefixed_support_team_table   = DB::getTablePrefix().$this->SupportTeamModel->getTable();

        $user_table                    = $this->UserModel->getTable();   
        $prefixed_user_table           = DB::getTablePrefix().$this->UserModel->getTable();

        $obj_data                = DB::table($support_query_table)
                                   ->select(DB::raw(  
                                                      $support_query_table.".*,".
                                                      $prefixed_query_type_table.".query_type ,".
                                                      $prefixed_support_team_table.".first_name as support_firstname,".
                                                      $prefixed_support_team_table.".last_name as support_lastname,".
                                                      $prefixed_user_table.".first_name as generated_by_firstname,".
                                                      $prefixed_user_table.".last_name as generated_by_lastname,".
                                                      "DATE_FORMAT(".$support_query_table.".created_at,'%Y-%m-%d') as ticket_date"
                                                    ))
                                   ->Join($prefixed_support_team_table,$prefixed_support_team_table.".id",' = ',$support_query_table.'.support_user_id')
                                   ->leftJoin($prefixed_query_type_table,$prefixed_query_type_table.".id",' = ',$support_query_table.'.query_type_id')
                                   ->leftJoin($prefixed_user_table,$prefixed_user_table.".id",' = ',$support_query_table.'.user_id');

        $arr_total_no_tickets = $obj_data ->get();

        /*Answerd Ticket Count*/

        $support_query_table           = $this->SupportQueryModel->getTable();   
        $prefixed_support_query_table  = DB::getTablePrefix().$this->SupportQueryModel->getTable();

        $query_type_table              = $this->QueryTypeModel->getTable();   
        $prefixed_query_type_table     = DB::getTablePrefix().$this->QueryTypeModel->getTable();

        $support_team_table            = $this->SupportTeamModel->getTable();   
        $prefixed_support_team_table   = DB::getTablePrefix().$this->SupportTeamModel->getTable();

        $user_table                    = $this->UserModel->getTable();   
        $prefixed_user_table           = DB::getTablePrefix().$this->UserModel->getTable();

        $obj_answer_data                = DB::table($support_query_table)
                                   ->select(DB::raw(  
                                                      $support_query_table.".*,".
                                                      $prefixed_query_type_table.".query_type ,".
                                                      $prefixed_support_team_table.".first_name as support_firstname,".
                                                      $prefixed_support_team_table.".last_name as support_lastname,".
                                                      $prefixed_user_table.".first_name as generated_by_firstname,".
                                                      $prefixed_user_table.".last_name as generated_by_lastname,".
                                                      "DATE_FORMAT(".$support_query_table.".created_at,'%Y-%m-%d') as ticket_date"
                                                    ))
                                   ->Join($prefixed_support_team_table,$prefixed_support_team_table.".id",' = ',$support_query_table.'.support_user_id')
                                   ->leftJoin($prefixed_query_type_table,$prefixed_query_type_table.".id",' = ',$support_query_table.'.query_type_id')
                                   ->leftJoin($prefixed_user_table,$prefixed_user_table.".id",' = ',$support_query_table.'.user_id')
                                   ->where($prefixed_support_query_table.'.status','3');

        $arr_answer_tickets = $obj_answer_data->get();

         /*Unanswerd Ticket Count*/

        $support_query_table           = $this->SupportQueryModel->getTable();   
        $prefixed_support_query_table  = DB::getTablePrefix().$this->SupportQueryModel->getTable();

        $query_type_table              = $this->QueryTypeModel->getTable();   
        $prefixed_query_type_table     = DB::getTablePrefix().$this->QueryTypeModel->getTable();

        $support_team_table            = $this->SupportTeamModel->getTable();   
        $prefixed_support_team_table   = DB::getTablePrefix().$this->SupportTeamModel->getTable();

        $user_table                    = $this->UserModel->getTable();   
        $prefixed_user_table           = DB::getTablePrefix().$this->UserModel->getTable();

        $obj_unanswer_data                = DB::table($support_query_table)
                                   ->select(DB::raw(  
                                                      $support_query_table.".*,".
                                                      $prefixed_query_type_table.".query_type ,".
                                                      $prefixed_support_team_table.".first_name as support_firstname,".
                                                      $prefixed_support_team_table.".last_name as support_lastname,".
                                                      $prefixed_user_table.".first_name as generated_by_firstname,".
                                                      $prefixed_user_table.".last_name as generated_by_lastname,".
                                                      "DATE_FORMAT(".$support_query_table.".created_at,'%Y-%m-%d') as ticket_date"
                                                    ))
                                   ->Join($prefixed_support_team_table,$prefixed_support_team_table.".id",' = ',$support_query_table.'.support_user_id')
                                   ->leftJoin($prefixed_query_type_table,$prefixed_query_type_table.".id",' = ',$support_query_table.'.query_type_id')
                                   ->leftJoin($prefixed_user_table,$prefixed_user_table.".id",' = ',$support_query_table.'.user_id')
                                   ->where($prefixed_support_query_table.'.status','1');

        $arr_unanswer_tickets = $obj_unanswer_data->get();
      

        $this->arr_data['total_no_tickets']      = count($arr_total_no_tickets);
        $this->arr_data['total_answer_tickets']  = count($arr_answer_tickets);
        $this->arr_data['total_unanswer_tickets']  = count($arr_unanswer_tickets);
        $this->arr_data['page_title']            = "Ticket Statistics";
        $this->arr_data['module_icon']           = 'fa-ticket';
        $this->arr_data['module_title']          = $this->module_title;
        $this->arr_data['module_url_path']       = $this->module_url_path;
        $this->arr_data['admin_panel_slug']      = $this->admin_panel_slug;

        return view($this->module_view_folder.'.tickets_statistics',$this->arr_data);     
    }
}

