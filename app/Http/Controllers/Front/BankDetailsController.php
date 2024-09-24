<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Common\Services\NotificationService;
use App\Models\BankDetailsModel;
use Validator;
use Session;
use Image;
use Auth;
use Hash;


class BankDetailsController extends Controller
{
    public function __construct(
                                    NotificationService $notification_service,
                                    BankDetailsModel    $bank_details_model
                                )
	{
        $this->arr_view_data       = [];
        $this->module_title        = 'My Account';
        $this->module_view_folder  = 'front.bank-details-user';
        $this->NotificationService = $notification_service;
        $this->BankDetailsModel    = $bank_details_model;
        $this->module_url_path     = url('/bank_details');
        $this->auth                = auth()->guard('users');

		$user = $this->auth->user();
      	if($user) {
            $this->user_id         = $user->id;
            $this->user_first_name = $user->first_name;
            $this->user_last_name  = $user->last_name;
        } else {
            $this->user_id         = 0;
            $this->user_first_name = '';
            $this->user_last_name  = '';
        }

        $this->profile_image_public_path = url('/').config('app.project.img_path.user_profile_images');
        $this->profile_image_base_path   = public_path().config('app.project.img_path.user_profile_images');
	}

	public function index()
	{
		$arr_bank_details = [];
		$obj_bank_details = $this->BankDetailsModel->where('user_id',$this->user_id)->get();
		if(isset($obj_bank_details) && $obj_bank_details!=null) {
			$arr_bank_details = $obj_bank_details->toArray();
		}

		$this->arr_view_data['bank_details']              = $arr_bank_details;
		$this->arr_view_data['id']                        = $this->user_id;
		$this->arr_view_data['page_title']                = $this->module_title;
		$this->arr_view_data['module_url_path']	          = $this->module_url_path;		
		$this->arr_view_data['profile_image_public_path'] = $this->profile_image_public_path;
		$this->arr_view_data['profile_image_base_path']   = $this->profile_image_base_path;

		
		return view($this->module_view_folder.'.index',$this->arr_view_data);
	}

	public function store(Request $request)
	{
        $user_id = $this->user_id;

		$arr_rules['bank_name']      = "required";
		$arr_rules['account_number'] = "required";
		$arr_rules['ifsc_code']      = "required";
		$arr_rules['account_type']   = "required";

        $msg = array(
            'required' =>'Please enter :attribute',
        );

        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $form_data = $request->all();
        $does_exists = $this->BankDetailsModel->where('bank_name',$form_data['bank_name'])
        								      ->where('account_number',$form_data['account_number'])
        								      ->count()>0;

        if($does_exists) {
            return redirect()->back()->with('error','This Bank Details already exist.');
        }

        $user_default_bank = $this->BankDetailsModel->where('user_id',$this->user_id)
                                                    ->where('selected','1')
                                                    ->count()>0;

        if($user_default_bank == false) {
            $this->arr_view_data['selected'] = '1';
        }

        $this->arr_view_data['bank_name']      = $form_data['bank_name'];
        $this->arr_view_data['account_number'] = $form_data['account_number'];
        $this->arr_view_data['ifsc_code']      = $form_data['ifsc_code'];
        $this->arr_view_data['account_type']   = $form_data['account_type'];
        $this->arr_view_data['user_id']   	   = $form_data['user_id'];        

        $bank_details = $this->BankDetailsModel->create($this->arr_view_data);

        if($bank_details) {
            
            // Send admin notification starts
            $arr_built_content = array(
                                    'USER_NAME' => $this->user_first_name,
                                    'MESSAGE'   => "Bank Details added successfully by ".$this->user_first_name
                                );
            
            $arr_notify_data['notification_text']  = $arr_built_content;
            $arr_notify_data['notify_template_id'] = '9';
            $arr_notify_data['template_text']      = "Bank Details added successfully by ".$this->user_first_name;
            $arr_notify_data['sender_id']          = $this->user_id;
            $arr_notify_data['sender_type']        = '4';
            $arr_notify_data['receiver_id']        = '1';
            $arr_notify_data['receiver_type']      = '2';
            $arr_notify_data['url']                = url('/').'/admin/host';
            $notification_status                   = $this->NotificationService->send_notification($arr_notify_data);
            // Send admin notification ends

            return redirect()->back()->with('success','Bank Details added successfully.');            
        }
        else {
        	return redirect()->back()->with('error','Error while adding bank details.');
        }

	}

	public function delete($id)
	{
		$id = decrypt($id);
		$delete_account = $this->BankDetailsModel->where('id', $id)->delete();
        
		if($delete_account) {
            return redirect()->back()->with('success','Bank details deleted successfully.');            
        } else {
        	return redirect()->back()->with('error','Error while deleting bank details.');
        }
	}

    public function get_data($id)
    {
        $id = decrypt($id);

        $arr_bank_details = [];
        
        $obj_bank_details = $this->BankDetailsModel->where('id',$id)->first();
        if(isset($obj_bank_details) && $obj_bank_details != null) {
            $arr_bank_details = $obj_bank_details->toArray();
        }

        return response()->json($arr_bank_details);
    }

    public function update(Request $request)
    {
        $user_id = $this->user_id;

        $arr_rules['bank_name']      = "required";
        $arr_rules['account_number'] = "required";
        $arr_rules['ifsc_code']      = "required";
        $arr_rules['account_type']   = "required";

        $msg = array(
            'required' =>'Please enter :attribute',
        );

        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $form_data = $request->all();
        $does_exists = $this->BankDetailsModel->where('bank_name',$form_data['bank_name'])
                                              ->where('account_number',$form_data['account_number'])
                                              ->where('id','!=',$form_data['id'])
                                              ->count()>0;    

        if($does_exists) {
            return redirect()->back()->with('error','This Bank Details already exist.');
        }

        if($request->input('account_check') == 'on') {
            $this->BankDetailsModel->where('user_id',$user_id)->update([ 'selected' => '0' ]);
            $this->arr_view_data['selected'] = '1';
        }

        $this->arr_view_data['bank_name']      = $form_data['bank_name'];
        $this->arr_view_data['account_number'] = $form_data['account_number'];
        $this->arr_view_data['ifsc_code']      = $form_data['ifsc_code'];
        $this->arr_view_data['account_type']   = $form_data['account_type'];

        $bank_details = $this->BankDetailsModel->where('id',$form_data['id'])->update($this->arr_view_data);
        if($bank_details) {
            return redirect()->back()->with('success','Bank details updated successfully.');
        }
        else {
            return redirect()->back()->with('error','Error while updating bank details.');
        }
    }
}
