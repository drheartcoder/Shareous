<?php

namespace App\Http\Controllers\Support\Verification;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SupportTeamModel;
use App\Models\HostVerificationModel;
use App\Models\UserModel;
use Datatables;
use DB;
use Session;

class RejectVerificationController extends Controller
{
    public function __construct(	SupportTeamModel 		$support_team_model, 
									HostVerificationModel 	$host_verification_model, 
									UserModel 				$user_model									
								)
	{
		$this->arr_view_data         = [];
		$this->support_panel_slug    = config('app.project.support_panel_slug');
		$this->support_url_path      = url(config('app.project.support_panel_slug'));
		$this->module_title          = "Verification Requests";
		$this->module_view_folder    = "support.verification_reject";
		$this->module_url_path       = $this->support_url_path."/verification";
		$this->auth                  = auth()->guard('support');
		$this->SupportTeamModel      = $support_team_model;
		$this->HostVerificationModel = $host_verification_model;
		$this->UserModel             = $user_model;
		$this->support_id            = isset($this->auth->user()->id)? $this->auth->user()->id:0;
		$this->profile_image_public_path         = url('/').config('app.project.img_path.user_profile_images');
		$this->profile_image_base_path           = public_path().config('app.project.img_path.user_profile_images');
		$this->id_proof_public_path  = url('/').config('app.project.img_path.user_id_proof');
		$this->id_proof_base_path    = public_path().config('app.project.img_path.user_id_proof');
		$this->photo_public_path     = url('/').config('app.project.img_path.user_photo');		
		$this->photo_base_path       = public_path().config('app.project.img_path.user_photo');
	}

	public function index()
	{
		$arr_data = [];
		$this->arr_view_data['objects']                       = $arr_data;
		$this->arr_view_data['module_icon']                   = "fa fa-id-card-o";
		$this->arr_view_data['page_title']                    = 'Rejected '.str_plural($this->module_title);
		$this->arr_view_data['module_title']                  = str_plural($this->module_title);
		$this->arr_view_data['module_url_path']               = $this->module_url_path;
		$this->arr_view_data['support_panel_slug']            = $this->support_panel_slug;

		return view($this->module_view_folder.'.index',$this->arr_view_data);
	}

	public function load_reject_data(Request $request)
	{
		$UserData        =  $final_array =[]; 
        $column          = '';

        $request_id      = $request->input('request_id'); 
        $user_name       = $request->input('user_name'); 
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

        $order                  = strtoupper($request->input('order')[0]['dir']); 

		$arr_data               = [];
		$status                 = '';
		$users_table            = $this->UserModel->getTable();
		$arr_search_column      = $request->input('column_filter');
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
					->where($prefixed_request_table.'.support_user_id','=',$this->support_id)
					->where($prefixed_request_table.'.status','=','2')
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

        if($order !='' && $column!='')
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

            	$built_view_href   = $this->module_url_path.'/view_reject_data/'.base64_encode($row->id);
				
				$built_view_button = "<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' title='View' href='".$built_view_href."'  data-original-title='View'> <i class='fa fa-eye' ></i> </a>";

				$action_button = $built_view_button;

				if(isset($row->status) && $row->status==2)
				{
					$status='Rejected';
				}

            	$final_array[$i][0] = $row->request_id;
                $final_array[$i][1] = $row->user_name;
                $final_array[$i][2] = get_added_on_date_time($row->created_at);
                $final_array[$i][3] = $status;
                $final_array[$i][4] = $action_button;
                $i++;
            }
        }

         $resp['data'] = $final_array;
         echo str_replace("\/", "/",  json_encode($resp));exit;	
	}

	public function view_reject_data($id)
	{
		$arr_data = [];
		$id = base64_decode($id);

		$obj_verification = $this->HostVerificationModel->where('id',$id)
								->with(['bank_details'=>function($q1){
									$q1->where('selected',1);
								},'user_details'])
								->first();
		if(isset($obj_verification) && $obj_verification!=null)
		{
			$arr_data     = $obj_verification->toArray();
		}

		$this->arr_view_data['arr_user']                  = $arr_data;
		$this->arr_view_data['module_icon']               = "fa fa-id-card-o";
		$this->arr_view_data['page_icon']                 = "fa fa-eye";
		$this->arr_view_data['page_title']                = 'View Rejected '.str_singular($this->module_title);  
		$this->arr_view_data['module_title']              = str_plural($this->module_title);
		$this->arr_view_data['module_url_path']           = $this->module_url_path;
		$this->arr_view_data['support_panel_slug']        = $this->support_panel_slug;
		$this->arr_view_data['profile_image_public_path'] = $this->profile_image_public_path;
		$this->arr_view_data['profile_image_base_path']   = $this->profile_image_base_path;
		$this->arr_view_data['id_proof_base_path']   	  = $this->id_proof_base_path;
		$this->arr_view_data['id_proof_public_path']   	  = $this->id_proof_public_path;
		$this->arr_view_data['photo_public_path'] 		  = $this->photo_public_path;
		$this->arr_view_data['photo_base_path'] 		  = $this->photo_base_path;
		$this->arr_view_data['previous_page_url']         = $this->support_url_path."/verification/reject_request";
		$this->arr_view_data['previous_page_title']       = "Rejected Verification Requests";

		return view($this->module_view_folder.'.view',$this->arr_view_data);
	}

}
