<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyBedsArrangmentModel extends Model
{
    protected $table	= 'property_beds_arrangment';
	protected $fillable = [
							'property_id','no_of_bedrooms','double_bed','single_bed','queen_bed','sofa_bed'
						  ];
}
