<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\HostVerificationModel;
use Image;
use Validator;
use Session;

class VerificationController extends Controller
{
	function __construct(HostVerificationModel $host_verification_model)
	{
		$this->HostVerificationModel = $host_verification_model;
		$this->auth                  = auth()->guard('users');

		$this->id_proof_public_path  = url('/').config('app.project.img_path.user_id_proof');
		$this->id_proof_base_path    = public_path().config('app.project.img_path.user_id_proof');
		
		$this->photo_public_path     = url('/').config('app.project.img_path.user_photo');
		$this->photo_base_path       = public_path().config('app.project.img_path.user_photo');
	}

	
	public function post_documets(Request $request) {
		$obj_user          = $this->auth->user();
		$arr_data          = [];
		$arr_rules         = array();
		$isUpload_id_proof = false;
		$isUpload_photo    = false;

		$arr_rules['id_proof'] = "required";
		$arr_rules['photo']    = "required";
		$arr_rules['terms']    = "required";

		$validator = validator::make($request->all(),$arr_rules);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}

		$id_proof = $request->file('id_proof', null);
		$photo    = $request->file('photo', null);

		if ($id_proof != null && $photo != null) {
			$id_proof_name          = sha1(uniqid().uniqid()) . '.' . $id_proof->getClientOriginalExtension();
			$path                   = $this->id_proof_base_path;
			$id_proof_original_name = $request->file('id_proof')->getClientOriginalName();
			$isUpload_id_proof      = $request->file('id_proof')->move($path , $id_proof_name);

			$photo_name             = sha1(uniqid().uniqid()) . '.' . $photo->getClientOriginalExtension();
			$path                   = $this->photo_base_path;
			$photo_original_name    = $request->file('photo')->getClientOriginalName();
			$isUpload_photo         = $request->file('photo')->move($path , $photo_name);
		}

		$dose_exist = $this->HostVerificationModel->where(['user_id'=> $obj_user->id, 'status'=>'0'])->count();

		if ($dose_exist > 0) {
			Session::flash('error', 'Your request is already in process please wait for verification process.');
			return redirect('/profile');
		}

		if ($isUpload_id_proof && $isUpload_photo) {
			$arr_data['user_id']       = $obj_user->id;
			$arr_data['request_id']    = get_request_id();
			$arr_data['id_proof']      = $id_proof_name;
			$arr_data['id_proof_name'] = $id_proof_original_name;
			$arr_data['photo']         = $photo_name;
			$arr_data['photo_name']    = $photo_original_name;
			$arr_data['status']        = '3';
			$user_request              = $this->HostVerificationModel->create($arr_data);

			Session::flash('success', 'Your request for verification is accepted please wait until verification process');
			return redirect('/profile');
		}

		Session::flash('error', 'Error while uploading your document for verification.');
		return redirect('/profile');
	}
}