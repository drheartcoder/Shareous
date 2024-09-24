<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCommentsModel extends Model
{
	protected $table	= 'blog_comments';
	protected $fillable = [
							'status',
							'blog_id',
							'user_id',
							'title',
							'comment',
							'created_at',
						  ];

	public function user_details()
	{
		return $this->hasMany('App\Models\UserModel','id','user_id');
	}

	public function blog_details()
	{
		return $this->hasMany('App\Models\BlogModel','id','blog_id');
	}
}
