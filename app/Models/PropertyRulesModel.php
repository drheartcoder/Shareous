<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyRulesModel extends Model
{
  	protected $table = 'property_rules';
  	protected $fillable = [
							'property_id',
							'rules',
						  ];

}
