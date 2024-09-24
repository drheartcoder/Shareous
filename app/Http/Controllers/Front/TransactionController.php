<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\UserModel;
use App\Models\TransactionModel;
use App\Models\BookingModel;
use App\Models\PropertyModel;

use Session;
use DB;
use Flash;

class TransactionController extends Controller
{
    public function __construct(
    							UserModel        $user_model,
    							TransactionModel $transaction_model,
    							BookingModel     $booking_model,
    							PropertyModel    $property_model
    						)
	{
		$this->arr_view_data       		= [];
		$this->module_title        		= 'My Transaction';
		$this->module_view_folder  		= 'front.transaction';
	    $this->module_url_path  		= url('/').'/transactions';

		$this->UserModel 		   		= $user_model;
		$this->TransactionModel    		= $transaction_model;
		$this->PropertyModel            = $property_model;

		$this->invoice_path_public_path = url('/').config('app.project.img_path.invoice_path');
		$this->invoice_path_base_path   = public_path().config('app.project.img_path.invoice_path');

		$this->auth                		= auth()->guard('users'); 
		$user 					   		= $this->auth->user();
		$this->BookingModel             = $booking_model;
      
      	if($user) {
          	$this->user_id 		   		= $user->id;
          	$this->user_first_name 		= $user->first_name;
          	$this->user_last_name  		= $user->last_name;
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
		$date            = $request->input('search_date');
		$keyword         = trim($request->input('keyword'));
		$field_name      = trim($request->input('field_name'));
		$sort_by         = trim($request->input('sort_by'));

		$arr_transaction = $querystring = [];
		       
		$querystring     = array(
									'search_date' => $date,
									'keyword'     => $keyword,
									'field_name'  => $field_name,
									'sort_by'     => $sort_by
								);

		$user_type       = Session::get('user_type');

		$obj_transaction = $this->TransactionModel
								->select(
											'transaction.*',
											"P.currency AS currency",
						                	"P.currency_code AS currency_code"
										)
						        ->leftJoin('booking AS B', "B.id",' = ','transaction.booking_id')
								->leftJoin("property AS P","P.id",' = ','B.property_id')
								->where('transaction.user_id', $this->user_id)
								->where('transaction.user_type', $user_type);

		if($sort_by == null || $sort_by == "") {
			$obj_transaction = $obj_transaction->orderBy('transaction.id','DESC' );
		} else {
			$obj_transaction = $obj_transaction->orderBy($field_name,$sort_by);
		}

		if($user_type == '1') {
			$payment_type     = ['booking','wallet','refund'];
			$obj_transaction  = $obj_transaction->whereIn('transaction.payment_type',$payment_type);
		} else if($user_type == '4') {
			$obj_transaction  = $obj_transaction->where('transaction.payment_type',"booking"); 
		}

		if($date != '') {
			$search_date      =  date('Y-m-d',strtotime($date));
			$obj_transaction  = $obj_transaction->where('transaction_date','LIKE','%'.$search_date.'%'); 
		}

		if($keyword != '') {
			$obj_transaction  = $obj_transaction->whereRaw("( transaction_id LIKE '%".$keyword."%' OR transaction_for LIKE '%".$keyword."%' OR amount LIKE '%".$keyword."%' ) ");  
		}

		// dd($obj_transaction->toSql());
		$obj_transaction = $obj_transaction->paginate(10);
											
		if($obj_transaction) {
			$obj_transaction->appends($querystring)->render();	
			$obj_transaction->setPath(url('/transactions'));
			$page_link        = $obj_transaction->links();
			$arr_transaction  = $obj_transaction->toArray();			
		}

		$this->arr_view_data['page_link']				 = $page_link;
		$this->arr_view_data['arr_transaction'] 		 = $arr_transaction;
		$this->arr_view_data['invoice_path_public_path'] = $this->invoice_path_public_path;
		$this->arr_view_data['invoice_path_base_path']   = $this->invoice_path_base_path;
		$this->arr_view_data['page_title'] 				 = $this->module_title;
		$this->arr_view_data['module_url_path'] 		 = $this->module_url_path;
		
		if($user_type == '1') {
			$view_blade = '.guest_transaction';
		} elseif($user_type == '4') {
			$view_blade = '.host_transaction';
		}

		return view($this->module_view_folder.$view_blade,$this->arr_view_data);
	} // end index

	
	public function export(Request $request)
	{
		$arr_tmp         = array();
		$date            = $request->input('e_search_date');
		$keyword         = $request->input('e_keyword');

		$user_type       = Session::get('user_type');
		$obj_transaction = $this->TransactionModel->where('user_id', $this->user_id)
												  ->where('user_type', $user_type)
												  ->orderBy('id', 'DESC');

		if($user_type == '1') {
			$payment_type    = ['booking','wallet','refund'];
			$obj_transaction = $obj_transaction->whereIn('payment_type',$payment_type);
		} else if($user_type == '4') {
			$obj_transaction = $obj_transaction->where('payment_type',"booking");
		}

		if($date != '') {
			$search_date     = date('Y-m-d',strtotime($date));
			$obj_transaction = $obj_transaction->where('transaction_date','LIKE', '%'.$search_date.'%');
		}

		if($keyword != '') {
			$obj_transaction = $obj_transaction->whereRaw("( transaction_id LIKE '%".$keyword."%' OR transaction_for LIKE '%".$keyword."%' ) ");
		}	

		$obj_transaction = $obj_transaction->get();

		$format = "xlsx";

        if($format == "xlsx") {
            if(isset($obj_transaction) && count($obj_transaction) > 0) {
                \Excel::create('TRANSACTION_REPORT-'.date('Ymd').uniqid(), function($excel) use($obj_transaction) {
                    $excel->sheet('Transaction', function($sheet) use($obj_transaction) {
                        $sheet->cell('A1', function($cell) {
							$cell->setValue('Generated on :'.date("d-m-Y H:i:s"));
                        });

						$sheet->row(2, array(
												'Transaction Id',
												'Transaction For',
												'Amount (INR)',
												'Payment Using',
												'Transaction Date'
											));
						$i = 0;
						
						foreach($obj_transaction as $key => $ad) {
                            $arr_tmp[$key][] = isset($ad->transaction_id) ? $ad->transaction_id : '';
                            $arr_tmp[$key][] = isset($ad->transaction_for) ? $ad->transaction_for : '';
                            $arr_tmp[$key][] = isset($ad->amount) ? number_format($ad->amount, 2, '.', '') : '0';
                            $arr_tmp[$key][] = isset($ad->payment_type) ? ucwords($ad->payment_type) : '';
                            $arr_tmp[$key][] = isset($ad->transaction_date) ? get_added_on_date($ad->transaction_date) : '';
						}
						$sheet->rows($arr_tmp);
					});
				})->export('xlsx');
            } else {
                Session::flash('error', 'Error occure while making export due to no data to create xlsx file.');	      
                return redirect()->back();
            }
        }
        Session::flash('error', 'Error occure while making export due to no data to create xlsx file.');	      
		return redirect()->back(); 
	}

	public function transaction_details($id = NULL)
	{
		$arr_transaction = [];
		$id = base64_decode($id);

		$transaction_table          = $this->TransactionModel->getTable();
		$booking_table              = $this->BookingModel->getTable();
		$property_table             = $this->PropertyModel->getTable();
		$prefixed_transaction_table = DB::getTablePrefix().$this->TransactionModel->getTable();
		$prefixed_booking_table     = DB::getTablePrefix().$this->BookingModel->getTable();
		$prefixed_property_table    = DB::getTablePrefix().$this->PropertyModel->getTable();
                             
		$arr_transaction = DB::table($prefixed_transaction_table)
			                ->select(
				                $prefixed_transaction_table.".transaction_id",
				                $prefixed_transaction_table.".transaction_date",
				                $prefixed_transaction_table.".amount",
				                $prefixed_transaction_table.".payment_type",
				                $prefixed_transaction_table.".transaction_for",
				                $prefixed_transaction_table.".invoice",
				                $prefixed_booking_table.".check_in_date",
				                $prefixed_booking_table.".check_out_date",
				                $prefixed_booking_table.".created_at",
				                $prefixed_property_table.".currency",
				                $prefixed_property_table.".currency_code"
				            )
			                ->LeftJoin($prefixed_booking_table, $prefixed_booking_table.".id",'=',$prefixed_transaction_table.'.booking_id')
			                ->LeftJoin($prefixed_property_table, $prefixed_property_table.".id",'=', $prefixed_booking_table.'.property_id')
			              	->where($prefixed_transaction_table.'.id', $id)
			                ->first();

		$this->arr_view_data['transaction']              = $arr_transaction;
		$this->arr_view_data['page_title']               = $this->module_title;
		$this->arr_view_data['module_url_path']          = $this->module_url_path;
		$this->arr_view_data['invoice_path_base_path']   = $this->invoice_path_base_path;
		$this->arr_view_data['invoice_path_public_path'] = $this->invoice_path_public_path;

		return view($this->module_view_folder.'.transaction_details',$this->arr_view_data);
	}

}
