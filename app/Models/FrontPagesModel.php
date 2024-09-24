<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontPagesModel extends Model
{
    protected $table = 'front_pages';

    protected $dates = ['created_at','updated_at','deleted_at'];

    protected $primaryKey = "id";

	protected $fillable = [
		'page_title',
		'page_description',
		'page_slug',
		'meta_keyword',
		'meta_title',
		'meta_description',
		'status'
	];
}
