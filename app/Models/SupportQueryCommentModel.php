<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportQueryCommentModel extends Model
{
    protected $table	= 'support_query_comments';
	protected $fillable = [
							'query_id',
							'comment_by',
							'user_id',
							'user_type',
							'support_user_id',
							'is_read',
							'comment'							
						  ];

	public function user_details()
    {
        return $this->hasOne('App\Models\UserModel','id','user_id')->select('id','first_name','display_name','user_name','last_name','email','mobile_number','address','profile_image');
    }

    public function support_details()
    {
        return $this->hasOne('App\Models\SupportTeamModel','id','support_user_id')->select('id','user_name','first_name','last_name','email','support_level','contact','address','profile_image');
    }
}
