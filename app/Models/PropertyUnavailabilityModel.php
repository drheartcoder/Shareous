<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyUnavailabilityModel extends Model
{
   protected $table ='property_unavailability';
   protected $fillable = ['property_id','booking_id','type','from_date','to_date'];
}
