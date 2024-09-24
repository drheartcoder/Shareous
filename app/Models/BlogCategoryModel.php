<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategoryModel extends Model
{
    protected $table	= 'blog_category';
	protected $fillable = [
							'category_name',
							'slug',
							'status',
						  ];
}
