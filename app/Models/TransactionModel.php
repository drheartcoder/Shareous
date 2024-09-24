<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionModel extends Model
{
	protected $table    = 'transaction';

    protected $fillable = [                          
                            'id',
                            'payment_type',
                            'transaction_id',
                            'user_id',
                            'user_type',
                            'amount',
                            'booking_id',
                            'transaction_for',
                            'invoice',
                            'transaction_date',
                            'created_at',
                            'updated_at',
                         ];


     public function user_details()
    {
        return $this->hasOne('App\Models\UserModel','id','user_id')->select('id','user_name','user_type','profile_image','notification_type','first_name','last_name','email','mobile_number','wallet_amount');
    }

    public function booking_details()
    {
        return $this->hasOne('App\Models\BookingModel', 'id', 'booking_id');
    }
  
}
