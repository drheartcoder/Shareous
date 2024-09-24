<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportQueryModel extends Model
{
    protected $table	= 'support_query';
	protected $fillable = [
			'user_id',
			'user_type',
			'support_user_id',
			'query_type_id',
			'support_level',
			'booking_id',
			'query_subject',
			'query_description',
			'attachment_file',
			'attachment_file_name',
			'status'			
		];
	
	public function user_details()
	{
		return $this->hasOne('App\Models\UserModel','id','user_id')->select('id','user_name','user_type','profile_image','address','notification_type','first_name','last_name','email','mobile_number','gender','birth_date','city','wallet_amount','notification_by_email','notification_by_sms','notification_by_push');
	}
	public function query_type_details()
	{
		return $this->belongsTo('App\Models\QueryTypeModel','query_type_id','id');
	}
	public function query_comments()
    {
        return $this->hasMany('App\Models\SupportQueryCommentModel','query_id','id');
    }
    public function query_chat()
    {
        return $this->hasOne('App\Models\SupportQueryCommentModel','query_id','id');
    }
	public function booking_details()
	{
		return $this->belongsTo('App\Models\BookingModel','booking_id','id');
	}
}
