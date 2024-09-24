<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueryTypeModel extends Model
{
    protected $table	= 'query_type';
	protected $fillable = [
							'query_type',
							'status',
						  ];

}
