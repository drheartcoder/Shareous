<?php 
namespace App\Common\Services;

use Auth;
use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtUserService
{
	function __construct() {
	}
	
	public function generate_user_jwt_token( $user = null )
	{
		if($user != null) {
			try {
				$token = JWTAuth::fromUser($user);
				$jwt_response['user_token'] = $token;
				return $jwt_response;
			}
			catch (JWTException $e) {
				return false;
			}

			return false;
		}
		return false;
	}
}