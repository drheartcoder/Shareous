<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class SupportTeamModel extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    protected $hidden = array('password');
    use Authenticatable, Authorizable, CanResetPassword;

    protected $table 	= 'support_team';
    protected $fillable = [
    						'user_name',
    						'first_name',
    						'last_name',
    						'email',
    						'password',
    						'support_level',
    						'contact',
    						'profile_image',
    						'address',
    						'city',
    						'gender',
    						'status',
    					  ];  
}
