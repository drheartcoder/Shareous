<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GstDataModel extends Model
{
    protected $table	= 'gst_data';
	protected $fillable = [
							'min_price',
							'max_price',
							'gst'
						  ];
}
