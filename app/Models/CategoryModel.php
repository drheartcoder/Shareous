<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    protected $table	= 'categories';
	protected $fillable = [
		'category_name',
		'slug',
		'status',
	];

}
