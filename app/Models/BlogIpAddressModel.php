<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogIpAddressModel extends Model
{
 	protected $table	= 'blog_ip_address';
	protected $fillable = [
							'blog_id',
							'ip_address',
						  ];	
}
