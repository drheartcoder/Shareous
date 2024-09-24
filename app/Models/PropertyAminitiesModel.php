<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyAminitiesModel extends Model
{
    protected $table	= 'property_aminities';
	protected $fillable = [
							'property_id',
							'aminities_id',	
						  ];

		public function aminities()
		{
			return $this->belongsTo('App\Models\AmenitiesModel','aminities_id','id')->select('id','aminity_name','slug');
		}
}
