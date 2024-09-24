<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewRatingModel extends Model
{
     protected $table = 'review_rating';
     protected $fillable = [
     							'id',
     							'rating_user_id',
	     						'property_id',
	     						'booking_id',
	     						'rating',
	     						'message',
	     						'status',
	     						'created_at',
                                'updated_at'
	     						
                                
     						]; 

    public function user_details()
    {
        return $this->hasOne('App\Models\UserModel','id','rating_user_id')->select('first_name','last_name');
    }

    public function property_details()
    {
        return $this->hasOne('App\Models\PropertyModel','id','property_id');
    }


}
