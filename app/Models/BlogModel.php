<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogModel extends Model
{
    protected $table	= 'blogs';
	protected $fillable = [
							'blog_category_id',
							'title',
							'description',
							'blog_image',
							'status',
						  ];

	public function blog_category()
	{
		return $this->hasMany('App\Models\BlogCategoryModel','id','blog_category_id');
	}
	
	public function blog_view_count()
	{
		return $this->hasMany('App\Models\BlogIpAddressModel','blog_id','id');
	}	

	public function blog_comment_count()
	{
		return $this->hasMany('App\Models\BlogCommentsModel','blog_id','id');
	}	
}
