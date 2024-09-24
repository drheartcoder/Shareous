<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertytypeModel extends Model
{
	protected $table	= 'property_type';
	protected $fillable = [
							'name',
							'status',
						  ];
}
