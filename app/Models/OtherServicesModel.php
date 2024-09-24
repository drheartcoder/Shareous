<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherServicesModel extends Model
{
    protected $table	= 'services';
	protected $fillable = [
							'name',
							'status',
						  ];
}
