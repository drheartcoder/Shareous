<?php

namespace App\Http\Controllers\Support;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SupportQueryModel;
use App\Models\UserModel;
use App\Models\HostVerificationModel;
use App\Models\SupportLogModel;
use App\Models\SupportTeamModel;
use DB;
use Session;
use Datatables;
use Validator;


class DashboardController extends Controller
{
	public function __construct(  SupportQueryModel  	$support_query_model,
								  UserModel 			$user_model,
								  HostVerificationModel $host_verification_model,
							      SupportLogModel   	$support_log_model,
							      SupportTeamModel      $support_team_model)
	{
		$this->arr_view_data         = [];
		$this->module_title          = "Dashboard";
		$this->module_view_folder    = "support.dashboard";
		$this->SupportTeamModel      = $support_team_model;
		$this->SupportQueryModel     = $support_query_model;
		$this->HostVerificationModel = $host_verification_model;
		$this->UserModel             = $user_model;
		$this->SupportLogModel       = $support_log_model;
		$this->auth                  = auth()->guard('support');
		$this->support_id            = isset($this->auth->user()->id)? $this->auth->user()->id:0;
		$this->support_url_path      = url(config('app.project.support_panel_slug'));
		$this->module_url_path       = $this->support_url_path.'/dashboard';
	}

	public function index(Request $request)
	{
		$support_login_id = Session::get('user_id');
		$arr_tile_color   = array('tile-red','tile-green','tile-magenta','');
		$total_ticket     = $this->SupportQueryModel->count();
		$get_user         = $this->UserModel->where('user_type','1')->get();

		$obj_data = DB::table('host_verification_request')
								->where('host_verification_request.support_user_id','=',$this->support_id)
								->where('host_verification_request.status','=','3')
								->join("users","users.id",' = ','host_verification_request.user_id')
								->get();

		$obj_request = DB::table('host_verification_request')
								->where('host_verification_request.support_user_id','=',$this->support_id)
								->where('host_verification_request.status','=','1')
								->join("users","users.id",' = ','host_verification_request.user_id')
								->get();

		$obj_closed_ticket 	= DB::table('support_query')
								->where('support_query.support_user_id',$this->support_id)
								->where('support_query.status','=',3)
								->join("users","users.id",' = ','support_query.user_id')
								->get();

		$total_closed_ticket 	       = count($obj_closed_ticket);
		$total_verification_request    = count($obj_data);
		$accepted_verification_request = count($obj_request);
		
		/*$total_verification_request    = $this->HostVerificationModel->count();
		$total_closed_ticket 	       = $this->SupportQueryModel->where('status','3')->count();
		$accepted_verification_request = $this->HostVerificationModel->where('status','1')->count();*/
		
		$this->arr_view_data['get_user']                      = $get_user;
		$this->arr_view_data['arr_final_tile']   	          = array();		
		$this->arr_view_data['support_level']                 = isset($this->auth->user()->support_level)?$this->auth->user()->support_level:'L3';
		$this->arr_view_data['total_ticket']       	          = $total_ticket;
		$this->arr_view_data['total_closed_ticket']       	  = $total_closed_ticket;		
		$this->arr_view_data['total_verification_request']    = $total_verification_request;
		$this->arr_view_data['accepted_verification_request'] = $accepted_verification_request;
		$this->arr_view_data['page_title']                    = $this->module_title;
		$this->arr_view_data['module_title']     	          = $this->module_title;
		$this->arr_view_data['support_url_path']              = $this->support_url_path;
		$this->arr_view_data['support_panel_slug']            = $this->support_url_path;
		$this->arr_view_data['module_url_path']               = $this->module_url_path;
		$this->arr_view_data['arr_tile_color']   	          = $arr_tile_color;

		return view($this->module_view_folder.'.index',$this->arr_view_data);
	}
	public function verification(Request $request)
	{
		$arr_tile_color = array('tile-red','tile-green','tile-magenta','');
		$total_ticket 	               = $this->SupportQueryModel->count();
		$total_closed_ticket 	       = $this->SupportQueryModel->where('status','3')->count();
		$total_verification_request    = $this->HostVerificationModel->count();
		$accepted_verification_request = $this->HostVerificationModel->where('status','1')->count();

		$this->arr_view_data['arr_final_tile']   	= array();		
		$this->arr_view_data['support_level']       = isset($this->auth->user()->support_level)?$this->auth->user()->support_level:'L3';
		$this->arr_view_data['total_ticket']       	= $total_ticket;
		$this->arr_view_data['total_closed_ticket']       	  = $total_closed_ticket;		
		$this->arr_view_data['total_verification_request']    = $total_verification_request;
		$this->arr_view_data['accepted_verification_request'] = $accepted_verification_request;
		$this->arr_view_data['page_title']       	= $this->module_title;
		$this->arr_view_data['module_title']     	= $this->module_title;
		$this->arr_view_data['support_url_path']    = $this->support_url_path;
		$this->arr_view_data['support_panel_slug']  = $this->support_url_path;
		$this->arr_view_data['module_url_path']     = $this->module_url_path;
		$this->arr_view_data['arr_tile_color']   	= $arr_tile_color;

		return view($this->module_view_folder.'.verification',$this->arr_view_data);
	}

	/*Description - Function to Load list of ticket whose status is open*/
	/*Ticket status - 1- open, 2-assigned, 3 -closed*/
	public function load_ticket_data(Request $request)
	{	
		$request_id      = $request->input('request_id');
		$UserData        =  $final_array =[]; 
        $column          = '';

        $ticket_id       = $request->input('ticket_id'); 
        $user_name       = $request->input('user_name'); 
        $generated_on    = $request->input('generated_on'); 

        if ($request->input('order')[0]['column'] == 1) 
        {
            $column = "id";
        }           
        if ($request->input('order')[0]['column'] == 2) 
        {
            $column = "user_name";
        }     
        if ($request->input('order')[0]['column'] == 3) 
        {
            $column = "created_at";
        } 

        $order = strtoupper($request->input('order')[0]['dir']);  

		$arr_data               = [];
	    $users_table            = $this->UserModel->getTable();
		$arr_search_column      = $request->input('column_filter_tickets');
		$support_query_table    = $this->SupportQueryModel->getTable();
		$prefixed_users_table   = DB::getTablePrefix().$this->UserModel->getTable();
		$prefixed_query_table   = DB::getTablePrefix().$this->SupportQueryModel->getTable();

		$obj_data = DB::table($support_query_table)
							->select(DB::raw( $prefixed_query_table.".id as id,".
								$prefixed_query_table.".created_at as created_at,".
								$prefixed_query_table.".status as status,".
								$prefixed_query_table.".query_subject as query_subject,".
								$prefixed_users_table.".user_name as user_name,".
								$prefixed_users_table.".first_name as first_name,".
								$prefixed_users_table.".last_name as last_name"
								))
							->where($prefixed_query_table.'.status','=',1)
							->where($prefixed_query_table.'.support_level','=',$this->auth->user()->support_level)
							->Join($users_table,$users_table.".id",' = ',$prefixed_query_table.'.user_id');
				
		if(isset($ticket_id) && $ticket_id != "")
		{
			$obj_data = $obj_data->where($prefixed_query_table.'.id','LIKE', '%'.$ticket_id.'%');
		}

		if(isset($generated_on) && $generated_on != "")
		{
			$obj_data = $obj_data->where($prefixed_query_table.'.created_at','LIKE', '%'.$generated_on.'%');
		}

		if(isset($user_name) && $user_name != "")
		{
			$obj_data = $obj_data->where($prefixed_users_table.'.user_name','LIKE', '%'.base64_decode($user_name).'%');
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
            	$build_view_action =''; 

            	$build_view_action = "<a class='btn btn-primary btn-sm btn-assign' href='javascript:void(0)' data-original-title='Assign Me' onclick='accept_ticket(".'"'.base64_encode($row->id).'"'.")'>Assign Me</a>";

            	if(isset($row->status) && $row->status==1)
				{
					$status='Open';
				}
            	$final_array[$i][0] = $row->id;
                $final_array[$i][1] = $row->user_name;
                $final_array[$i][2] = get_added_on_date_time($row->created_at);
                $final_array[$i][3] = $row->query_subject;
                $final_array[$i][4] = $status;
                $final_array[$i][5] = $build_view_action;
                $i++;
            }
        }

         $resp['data'] = $final_array;
         echo str_replace("\/", "/",  json_encode($resp));exit;	
	}

	/*
	 Description -Function to assign tickets*/
	public function assign_ticket(Request $request,$ticket_id)
    {
    	$ticket_id = base64_decode($ticket_id);
    	/*Assign ticket to support & update status as assigned*/
    	$status = $this->SupportQueryModel->where('id',$ticket_id)->update(['status' => 2,'support_user_id' => $this->support_id]);
    	
    	$arr_data['support_user_id'] = $this->support_id;
		$arr_data['query_id']        = isset($ticket_id)?$ticket_id:'';
		$arr_data['support_level']   = $this->auth->user()->support_level;

		$this->SupportLogModel->create($arr_data);
    	if($status)
    	{
    		return redirect($this->support_url_path."/ticket")->with('success', 'Ticket assigned successfully.'); 		
    	}
    	else
    	{
    		Session::flash('error','Problem occured, while assigning ticket');  
    		return redirect()->back();		
    	}
    }
    /*
	 Description - Function to load verification request those who dont assign to support & having status pendig*/
    /*host_verification_request status 0-pending,1-accepted,2-rejected,3-process  */
    public function load_request_data(Request $request)
	{
		$UserData        =  $final_array =[]; 
        $column          = '';
        $request_id      = $request->input('request_id'); 
        $user_name       = $request->input('req_user_name');
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
         
        $order      = strtoupper($request->input('order')[0]['dir']);  

		$arr_data               = [];
		$status                 = '';
		$users_table            = $this->UserModel->getTable();
		$arr_search_column      = $request->input('column_filter_request');
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
							->where($prefixed_request_table.'.status','=','3')
							->where($prefixed_request_table.'.support_user_id','=','0')
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
                $built_view_button = "<a class='btn btn-primary btn-sm btn-assign' href='javascript:void(0)' data-original-title='Assign Me' onclick='accept_request(".'"'.base64_encode($row->id).'"'.")'>Assign Me</a>";
				$action_button = $built_view_button;

				if(isset($row->status) && $row->status==0)
				{
					$status='Pending';
				}

            	$final_array[$i][0] = $row->request_id;
                $final_array[$i][1] = $row->user_name;
                $final_array[$i][2] = $row->created_at;
                $final_array[$i][3] = $status;
                $final_array[$i][4] = $action_button;
                $i++;
            }
        }

        $resp['data'] = $final_array;
        echo str_replace("\/", "/",  json_encode($resp));exit;	
	}

	/*Function to assign verification request to support*/
	public function assign_request(Request $request,$id)
	{
		$id = base64_decode($id);
    	$status = $this->HostVerificationModel->where('id',$id)->update(['support_user_id'=>$this->support_id]);
    	if($status)
    	{
    		//Session::flash('success',' Verification Request assigned successfully');   
    		return redirect($this->support_url_path."/verification")->with('success', 'Verification Request assigned successfully.'); 		
    	}
    	else
    	{
    		Session::flash('error','Problem occured, while assigning verification request');  
    		return redirect()->back(); 		
    	}
    	
	}

}



