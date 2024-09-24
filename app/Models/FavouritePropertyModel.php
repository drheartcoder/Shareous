<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavouritePropertyModel extends Model
{
    protected $table = 'my_favourite';
    protected $fillable = [
     							'user_id',
     							'property_id',
     							'favourite_from'	     						
     					  ]; 
}
