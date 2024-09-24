<?php

namespace App\Http\Controllers\Support\Ticket;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\SupportQueryModel;
use Datatables;
use DB;


class TicketClosedController extends Controller
{
    public function __construct(UserModel  $user_model,
								SupportQueryModel $support_query_model)
	{
		$this->arr_view_data             = [];
		$this->UserModel                 = $user_model;
		$this->SupportQueryModel         = $support_query_model;
		$this->support_panel_slug        = config('app.project.support_panel_slug');
		$this->support_url_path          = url(config('app.project.support_panel_slug'));
		$this->module_title              = "Tickets";
		$this->module_view_folder        = "support.ticket_closed";
		$this->module_url_path           = $this->support_url_path."/ticket";
		$this->auth                      = auth()->guard('support');		
		$this->support_id                = isset($this->auth->user()->id)? $this->auth->user()->id:0;
		$this->module_icon               = "fa fa-ticket";
		$this->profile_image_public_path = url('/').config('app.project.img_path.user_profile_images');
		$this->profile_image_base_path   = public_path().config('app.project.img_path.user_profile_images');
		$this->query_image_public_path   = url('/').config('app.project.img_path.query_image');
		$this->query_image_base_path     = public_path().config('app.project.img_path.query_image');
	}

    public function closed_ticket()
	{
		$arr_data = [];
		$this->arr_view_data['objects']                       = $arr_data;
		$this->arr_view_data['module_icon']                   = "fa fa-ticket";
		$this->arr_view_data['page_title']                    = 'Manage Closed '.str_plural($this->module_title);
		$this->arr_view_data['module_title']                  = str_plural($this->module_title);
		$this->arr_view_data['module_url_path']               = $this->module_url_path;
		$this->arr_view_data['support_panel_slug']            = $this->support_panel_slug;

		return view($this->module_view_folder.'.index',$this->arr_view_data);
	}

	public function load_closed_ticket(Request $request)
	{	
		
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
            
        $order                  = strtoupper($request->input('order')[0]['dir']);  

        $arr_data               = [];
		$status                 = '';
		$users_table            = $this->UserModel->getTable();
		$arr_search_column      = $request->input('column_filter');
		$support_query_table    = $this->SupportQueryModel->getTable();
		$prefixed_users_table   = DB::getTablePrefix().$this->UserModel->getTable();
		$prefixed_query_table   = DB::getTablePrefix().$this->SupportQueryModel->getTable();

		$obj_data = DB::table($support_query_table)
					->select(DB::raw( $prefixed_query_table.".id as id,".
						$prefixed_query_table.".created_at as created_at,".
						$prefixed_query_table.".status as status,".
						$prefixed_query_table.".query_subject,".
						$prefixed_users_table.".user_name as user_name,".
						$prefixed_users_table.".first_name as first_name,".
						$prefixed_users_table.".last_name as last_name"
					))
					->where($prefixed_query_table.'.support_user_id',$this->support_id)
					->where($prefixed_query_table.'.status','=',3)

					->Join($users_table,$users_table.".id",' = ',$prefixed_query_table.'.user_id');
					
	   	if(isset($ticket_id) && $ticket_id != "")
		{
			$obj_data = $obj_data->where($prefixed_query_table.'.id','LIKE', '%'.$ticket_id.'%');
		}

		if(isset($user_name) && $user_name != "")
		{
			$obj_data = $obj_data->where($prefixed_users_table.'.user_name','LIKE', '%'.$user_name.'%');
		}

		if(isset($generated_on) && $generated_on != "")
		{
			$obj_data = $obj_data->where($prefixed_query_table.'.created_at','LIKE', '%'.$generated_on.'%');
		}

		$count = count($obj_data->get());

        if($order =='ASC' && $column == '')
        {
        	$obj_data = $obj_data->orderBy('id','DESC')->limit($_GET['length'])->offset($_GET['start']);
        }

        if( $order != '' && $column != '' )
        {
        	$obj_data = $obj_data->orderBy($column,$order)->limit($_GET['length'])->offset($_GET['start']);
        }

        $UserData = $obj_data->get();

        $resp['draw']            = $_GET['draw'];
        $resp['recordsTotal']    = $count;
        $resp['recordsFiltered'] = $count;

         if(count($UserData)>0)
        {
            $i = 0;
            foreach($UserData as $row)
            {
            	$built_view_href = $this->module_url_path.'/view_closed_ticket/'.base64_encode($row->id);
			
				$built_view_button = "<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' title='View' href='".$built_view_href."'  data-original-title='View'> <i class='fa fa-eye' ></i> </a>";
            	
            	if(isset($row->status) && $row->status==3)
				{
					$status = 'Closed';
				}

            	$final_array[$i][0] = $row->id;
                $final_array[$i][1] = $row->user_name;
                $final_array[$i][2] = get_added_on_date_time($row->created_at);
                $final_array[$i][3] = $row->query_subject;
                $final_array[$i][4] = $status;
                $final_array[$i][5] = $built_view_button;
                $i++;
            }
        }
         $resp['data'] = $final_array;
         echo str_replace("\/", "/",  json_encode($resp));exit;	
	}

	public function view_closed_ticket($id)
	{
		$arr_data = [];
		$id = base64_decode($id);
		
		/*$obj_query = $this->SupportQueryModel->where('id',$id)
											->with('user_details','query_type_details', 'booking_details.transaction_details')
											->first();*/

		$obj_query = $this->SupportQueryModel->where('id', $id)
											 ->with('user_details', 'query_type_details', 'booking_details.transaction_details', 'booking_details.property_details', 'booking_details.booking_by_user_details', 'booking_details.property_owner')
											 ->first();

		if(isset($obj_query) && $obj_query!=null)
		{
			$arr_data = $obj_query->toArray();
		}

		$this->arr_view_data['arr_user']                  = $arr_data;
		$this->arr_view_data['module_icon']               = $this->module_icon;
		$this->arr_view_data['page_icon']                 = "fa fa-eye";
		$this->arr_view_data['page_title']                = 'View Closed '.$this->module_title;
		$this->arr_view_data['module_title']              = str_plural($this->module_title);
		$this->arr_view_data['module_url_path']           = $this->module_url_path;
		$this->arr_view_data['support_panel_slug']        = $this->support_panel_slug;
		$this->arr_view_data['profile_image_public_path'] = $this->profile_image_public_path;
		$this->arr_view_data['profile_image_base_path']   = $this->profile_image_base_path;
		$this->arr_view_data['query_image_public_path']   = $this->query_image_public_path;
		$this->arr_view_data['query_image_base_path']     = $this->query_image_base_path;
		$this->arr_view_data['previous_page_url']         = $this->support_url_path."/ticket/closed_ticket";
		$this->arr_view_data['previous_page_title']       = "Manage Closed Tickets";
		
		return view($this->module_view_folder.'.view',$this->arr_view_data);
	}


}
