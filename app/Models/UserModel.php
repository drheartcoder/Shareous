<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class UserModel extends Model implements AuthenticatableContract,
                                    	AuthorizableContract,
                                    	CanResetPasswordContract
{
	    use Authenticatable, Authorizable, CanResetPassword;
		protected $hidden     = array('password', 'remember_token');
		protected $loginNames = ['email', 'user_name'];
		protected $table      = 'users';
		protected $fillable   = [
									'referral_id',
									'user_name',
									'user_type',
									'otp',
									'mobile_otp',
									'resend_otp_count',
									'email',
									'password',
									'otp_expired_time',
									'mobile_otp_expired_time',
									'verification_token',
									'display_name',
									'first_name',
									'last_name',
									'country_code',
									'mobile_number',
									'new_mobile_number',
									'gender',
									'birth_date',
									'address',
									'city',
									'commission',
									'profile_image',
									'refferal_amount',
									'wallet_amount',
									'social_login',
									'login_via',
									'id_proof',
									'photo',
									'via_social',
									'status',
									'referral_user_id',
									'is_email_verified',
									'is_mobile_verified',
									'notification_type',
									'gstin'
								];

}
