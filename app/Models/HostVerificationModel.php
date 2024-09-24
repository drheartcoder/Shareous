<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostVerificationModel extends Model
{
	protected $table	= 'host_verification_request';
	protected $fillable = [
							'user_id',
							'support_user_id',
							'request_id',
							'id_proof',
							'id_proof_name',
							'photo',
							'photo_name',
							'status'
						  ];

	public function user_details()
	{
		return $this->hasOne('App\Models\UserModel','id','user_id');
	}
	public function bank_details()
	{
		return $this->belongsTo('App\Models\BankDetailsModel','user_id','user_id');
	}
}
