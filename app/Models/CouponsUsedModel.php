<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponsUsedModel extends Model
{
    protected $table	= 'coupons_used';
	protected $fillable = [
							'coupon_id',
							'user_id',
							'booking_id',
						  ];
}
