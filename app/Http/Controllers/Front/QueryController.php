<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\SupportQueryModel;
use App\Models\SupportQueryCommentModel;

use session;

class QueryController extends Controller
{
	function __construct(SupportQueryModel $support_query_model, SupportQueryCommentModel $support_query_comment_model)
	{
		$this->array_view_data          = [];	
		$this->module_title             = "My Query";
		$this->module_view_folder       = 'front.query.';
		$this->SupportQueryModel        = $support_query_model;
		$this->SupportQueryCommentModel = $support_query_comment_model;
		$this->profile_image_public_img_path = url('/').config('app.project.img_path.user_profile_images');
		$this->profile_image_base_img_path   = public_path().config('app.project.img_path.user_profile_images');
		$this->auth                     = auth()->guard('users');
		$this->user_id                  = 0;
		$this->module_path              = url('/query');
		if($this->auth->user())
		{
			$this->user_id = $this->auth->user()->id;
		}
	}
	public function index()
	{
		$arr_query  = [];
		$user_type  = Session::get('user_type');

		$obj_query  = $this->SupportQueryModel->with('query_type_details')->where('user_id',$this->user_id)->where('user_type',$user_type)->paginate('10');

		if($obj_query)
		{
			$arr_query = $obj_query->toArray();
		}

		$this->array_view_data['obj_pagination'] = $obj_query;
		$this->array_view_data['module_path']    = $this->module_path;
		$this->array_view_data['records']        = isset($arr_query['data'])? $arr_query['data']:'';
		$this->array_view_data['page_title']     = $this->module_title;
		return view($this->module_view_folder.'index', $this->array_view_data);
	}

	public function view($id=null)
	{
		$arr_query = [];
		$id        = base64_decode($id);

		$obj_query = $this->SupportQueryModel->with('user_details')
											 ->with('query_type_details')
											 ->with('query_comments')
											 ->where('id', $id)
											 ->first();
		
		if($obj_query)
		{
			$arr_query = $obj_query->toArray();	
		}

		//dd($arr_query);

		$this->array_view_data['module_path']                   = $this->module_path;
		$this->array_view_data['id']                            = base64_encode($id);
		$this->array_view_data['profile_image_public_img_path'] = public_path().config('app.project.img_path.user_profile_images');
		$this->array_view_data['profile_image_public_img_path'] = url('/').config('app.project.img_path.user_profile_images');
		$this->array_view_data['record']                        = $arr_query;
		$this->array_view_data['page_title']                    = 'View Query';
		return view($this->module_view_folder.'view', $this->array_view_data);
	}

	function store_query(Request $request)
	{	
		$rule_status = '';
      	$form_data = $arr_rules = $arr_json = $arr_rules_data = [];
      	$form_data = $request->all();

      	$user_id = $this->user_id;

      	$arr_rules['query_id']   = isset($form_data['query_id'])?base64_decode($form_data['query_id']):'';
      	$arr_rules['comment']    = isset($form_data['chat_text'])?$form_data['chat_text']:'';
      	$arr_rules['user_id']    = $user_id;
      	$arr_rules['comment_by'] = $user_id;
      	$rule_status = $this->SupportQueryCommentModel->create($arr_rules);
      	if($rule_status)
      	{
        	$arr_json['status']  = 'success';
         	$arr_json['message'] = 'your query has been added successfully.';
     	}
      	else
      	{
        	$arr_json['status']  = 'error';
         	$arr_json['message'] = 'Error while adding query.';
      	}      	
      	return response()->json($arr_json);
	}

}
