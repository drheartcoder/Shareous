<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\BankDetailsModel;
use App\Common\Traits\MultiActionTrait;
use App\Models\TransactionModel;
use App\Models\BookingModel;
use App\Models\PropertyModel;

use Validator;
use Session;
use DB;
use Datatables;

class TransactionController extends Controller
{
   	use MultiActionTrait;
    function __construct(UserModel $user_model, BankDetailsModel $bankdetails_model,TransactionModel $transaction_model,BookingModel $booking_model,PropertyModel $property_model)
	{
		$this->arr_data                  = [];
		$this->admin_panel_slug          = config('app.project.admin_panel_slug');
		$this->admin_url_path            = url(config('app.project.admin_panel_slug'));
		$this->profile_image_public_path = url('/').config('app.project.img_path.user_profile_images');
		$this->profile_image_base_path   = public_path().config('app.project.img_path.user_profile_images');
		$this->module_url_path           = $this->admin_url_path."/transaction";
		$this->module_title              = "Transaction";
		$this->module_view_folder        = "admin.transaction";
		$this->module_icon               = "fa fa-user";
		$this->BankDetailsModel          = $bankdetails_model;	
		$this->TransactionModel    		   = $transaction_model;
		$this->UserModel           		   = $user_model;	
		$this->BaseModel                 = $user_model;
    $this->BookingModel              = $booking_model; 
    $this->PropertyModel             = $property_model;

		DB::enableQueryLog();
	}

	public function index()
	{
   
		$arr_user = [];

		$users_table                  = $this->UserModel->getTable();
		$prefixed_users_table         = DB::getTablePrefix().$this->UserModel->getTable();

		$transaction_table            = $this->TransactionModel->getTable();
		$prefixed_transaction_table   = DB::getTablePrefix().$this->TransactionModel->getTable();

		$arr_user = DB::table($transaction_table)
							->select(DB::raw(
									$prefixed_users_table.".user_name as user_name,".
									$prefixed_users_table.".id as id"	
								))
							->Join($prefixed_users_table,$prefixed_users_table.".id",' = ',$prefixed_transaction_table.'.user_id')
							->groupBy($prefixed_transaction_table.'.user_id')->get();
		
		$this->arr_data['arr_user']        = $arr_user;
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

    $user_id              = $request->input('user_id'); 
    $user_name            = $request->input('user_name'); 
    $transaction_id       = $request->input('transaction_id'); 
    $transaction_date     = $request->input('transaction_date'); 

    /*if ($request->input('order')[0]['column'] == 1) 
    {
        $column = "id";
    }  */         
    if ($request->input('order')[0]['column'] == 1) 
    {
        $column = "user_name";
    }     
    if ($request->input('order')[0]['column'] == 2) 
    {
        //$column = "transaction_id";
    } 
    if ($request->input('order')[0]['column'] == 3) 
    {
        //$column = "transaction_for";
    } 
    if ($request->input('order')[0]['column'] == 4) 
    {
        $column = "amount";
    }
    if ($request->input('order')[0]['column'] == 5) 
    {
        $column = "amount";
    } 
     if ($request->input('order')[0]['column'] == 6) 
    {
        $column = "transaction_date";
    }  

    $order = strtoupper($request->input('order')[0]['dir']);  

		$arr_data                     = [];

		$users_table                  = $this->UserModel->getTable();
		$prefixed_users_table         = DB::getTablePrefix().$this->UserModel->getTable();

		$transaction_table            = $this->TransactionModel->getTable();
		$prefixed_transaction_table   = DB::getTablePrefix().$this->TransactionModel->getTable();

    $transaction_table            = $this->TransactionModel->getTable();
    $prefixed_transaction_table   = DB::getTablePrefix().$this->TransactionModel->getTable();

		$arr_search_column            = $request->input('column_filter');

		$obj_data = DB::table($transaction_table)
							->select(DB::raw(
									$prefixed_users_table.".user_type as user_type,".
									$prefixed_users_table.".user_name as user_name,".
									$prefixed_transaction_table.".*"
								))
							->Join($prefixed_users_table,$prefixed_users_table.".id",' = ',$prefixed_transaction_table.'.user_id');
							//->orderBy($prefixed_transaction_table.'.id','DESC');

		if(isset($user_name) && $user_name != "")
		{
			$obj_data   = $obj_data->where($prefixed_transaction_table.'.user_id','=',base64_decode($user_name));
		}

		if(isset($user_id) && $user_id != "")
		{
			$obj_data   = $obj_data->where($prefixed_transaction_table.'.user_id','=',base64_decode($user_id));
		}

		if(isset($transaction_id) && $transaction_id != "")
		{
			$obj_data   = $obj_data->where($prefixed_transaction_table.'.transaction_id','LIKE', '%'.$transaction_id.'%');
		}

		if(isset($transaction_date) && $transaction_date != "")
		{
			$obj_data   = $obj_data->where($prefixed_transaction_table.'.transaction_date',date('Y-m-d',strtotime($transaction_date)));
		}

    $count = count($obj_data->get());
    if($order =='ASC' && $column=='')
    {
      $obj_data   = $obj_data->orderBy('id','DESC')->limit($_GET['length'])->offset($_GET['start']);
    }
    if( $order !='' && $column!='' )
    {
		  //dd($order,$column);
      $obj_data   = $obj_data->orderBy($column,$order)->limit($_GET['length'])->offset($_GET['start']);
    }

    $UserData     = $obj_data->get();

    $resp['draw']            = $_GET['draw'];
    $resp['recordsTotal']    = $count;
    $resp['recordsFiltered'] = $count;

    $build_active_btn        = '' ; 

    if(count($UserData) > 0)
      {
          $i = 0;

          foreach($UserData as $row)
          {
              /*if($row->booking_id == '0' && $row->payment_type == 'wallet')
              {*/
                    if($row->user_type == '1')
                    {
                      $amount = '&#8377;'.$row->amount;
                      $commission = "-";
                    }
                    else if($row->user_type == '4')
                    {
                      $amount = "-";
                      $commission = '&#8377;'.$row->amount;
                    }
             /* }*/

              $final_array[$i][0] = "<input type='checkbox' name='checked_record[]' id='checked_record' class='checked_record' value='".base64_encode($row->id)."'/>";

              $final_array[$i][1] = isset($row->user_name) && $row->user_name!=''?$row->user_name:"N/A";
              $final_array[$i][2] = $row->transaction_id;
              $final_array[$i][3] = $row->transaction_for;
            
              $final_array[$i][4] = $amount;
              $final_array[$i][5] = $commission;

              $final_array[$i][6] = isset($row->transaction_date) && $row->transaction_date != ''?date('d-M-Y',strtotime($row->transaction_date)):'N/A';
            
              $i++;
        }
      }

    $resp['data'] = $final_array;
    echo str_replace("\/", "/",  json_encode($resp));exit;		
	}

	public function export(Request $request)
	{
			$user_id            = $request->input('e_user_id');
	    $user_name          = $request->input('e_user_name');
      $transaction_id     = $request->input('e_transaction_id');
      $transaction_date   = $request->input('e_transaction_date');
      $records            = $request->input('records');
			$arr_search_column  = $request->input('column_filter');

      $users_table                  = $this->UserModel->getTable();
      $prefixed_users_table         = DB::getTablePrefix().$this->UserModel->getTable();

      $transaction_table            = $this->TransactionModel->getTable();
      $prefixed_transaction_table   = DB::getTablePrefix().$this->TransactionModel->getTable();


      $property_table            = $this->PropertyModel->getTable();
      $prefixed_property_table   = DB::getTablePrefix().$this->PropertyModel->getTable();



			$obj_data = DB::table($transaction_table)
								->select(DB::raw(
										$prefixed_users_table.".user_type as user_type,".
										$prefixed_users_table.".user_name as user_name,".
										$prefixed_transaction_table.".*"
									))
								->Join($prefixed_users_table,$prefixed_users_table.".id",' = ',$prefixed_transaction_table.'.user_id')
								->orderBy($prefixed_transaction_table.'.id','DESC');

			if(isset($user_name) && $user_name != "")
			{
				$obj_data = $obj_data->where($prefixed_transaction_table.'.user_id','=',base64_decode($user_name));
			}

			if(isset($user_id) && $user_id != "")
			{
				$obj_data = $obj_data->where($prefixed_transaction_table.'.user_id','=',base64_decode($user_id));
			}

			if(isset($transaction_id) && $transaction_id != "")
			{
				$obj_data = $obj_data->where($prefixed_transaction_table.'.transaction_id','LIKE', '%'.$transaction_id.'%');
			}

			if(isset($transaction_date) && $transaction_date != "")
			{
				$obj_data = $obj_data->where($prefixed_transaction_table.'.transaction_date',date('Y-m-d',strtotime($transaction_date)));
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
            $obj_data = $obj_data->whereRaw($prefixed_transaction_table.'.id IN('.$final_str.')');
        }
      }

     

        
      $student_arr = $obj_data->get();  

            $format="xlsx";

            if($format=="xlsx")
            {
                $arr_tmp = array();
                
                if(count($student_arr)>0)
                {           
                     \Excel::create('Transaction-Report-'.date('Ymd').uniqid(), function($excel) use($student_arr) 
                      {
                          $excel->sheet('Transaction', function($sheet) use($student_arr) 
                          {
                              $sheet->cell('A1', function($cell) 
                              {
                                  $cell->setValue('Generated on :'.date("d-m-Y H:i:s"));
                              });
                              $sheet->row(2, array(
                                                       'user Name',
                                                       'Transaction Id',
                                                       'Transaction For',
                                                       'Total Amount',
                                                       'Commission',
                                                       'Transaction Date'
                                                  ));
                              $i=0;
                              
                              foreach($student_arr as $key => $ad)
                              {

                                if($ad->user_type == '1')
                                {
                                  $amount =  'INR '.$ad->amount;
                                  $commission = "-";
                                }
                                else if($ad->user_type == '4')
                                {
                                  $amount = "-";
                                  $commission = 'INR '.$ad->amount;
                                }
                                            
                                $arr_tmp[$key][]=$ad->user_name;
                                $arr_tmp[$key][]=$ad->transaction_id;
                                $arr_tmp[$key][]=$ad->transaction_for;
                                $arr_tmp[$key][]=$amount;
                                $arr_tmp[$key][]=$commission;
                                $arr_tmp[$key][]=get_added_on_date($ad->transaction_date);
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





}
