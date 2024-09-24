<?php
namespace App\Http\Middleware\Api;

use App\Http\Controllers\Controller;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class Authenticate extends Controller
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	
	public function handle($request, Closure $next)
	{		   
		try
		{
			$user = JWTAuth::parseToken()->authenticate();

			// This will regenerate the token and old token will be of no use
			// This is just to test token expiration
			// $newToken = JWTAuth::parseToken()->refresh();
			if($user)
			{
				if($user->status == '0')
				{
					return $this->build_response('error', "Your account blocked by admin, Please contact to admin");
				}

				return $next($request);
			}else
			{
				$msg = 'Invalid user token.';
				$json_arr['status'] 	= 'token_expire';
				$json_arr['message'] 	= $msg;

				return response()->json($json_arr,200,[],JSON_UNESCAPED_UNICODE);
			}
		}
		catch (JWTException $e)
		{	   
			$msg = $e->getMessage();
			$json_arr['status'] 	= 'token_expire';
			$json_arr['message'] 	= $msg;

			return response()->json($json_arr,200,[],JSON_UNESCAPED_UNICODE);
		}
		catch(\Exception $e)
		{
			$msg = $e->getMessage();
			$json_arr['status'] 	= 'error';
			$json_arr['message'] 	= $msg;

			return response()->json($json_arr,200,[],JSON_UNESCAPED_UNICODE);
		}
	}
}		

?>