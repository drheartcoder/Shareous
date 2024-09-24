<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponModel extends Model
{
    protected $table	= 'coupons';
	protected $fillable = [
							'coupon_code',
							'coupon_type',
							'descriptions',
							'discount_type',
							'discount',
							'global_expiry',
							'auto_expiry',
							'coupon_use',
							'status',						
						  ];

	public function coupon_code_used()
    {
        return $this->hasMany('App\Models\CouponsUsedModel','coupon_id','id');
    }

}
