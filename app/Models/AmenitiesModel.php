<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmenitiesModel extends Model
{
        protected $table	= 'amenities';
		protected $fillable = [
								'propertytype_id',
								'aminity_name',
								'slug',
								'status',
							  ];
		
		public function propertytype()
		{
			return $this->belongsTo('App\Models\PropertytypeModel','propertytype_id','id');
		}

		public function property()
		{
			return $this->belongsTo('App\Models\PropertyModel','propertytype_id','property_type_id');
		}
}
