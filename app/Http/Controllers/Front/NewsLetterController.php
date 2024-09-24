<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Common\Services\MailchimpService;
use Validator;


class NewsLetterController extends Controller
{

	public function __construct(MailchimpService $mailchimp_service)
	{
		$this->MailchimpService = $mailchimp_service;
	}

    public function subscribe(Request $request)
    {

    	$arr_rules 		= [];
    	$arr_responce 	= array();
        $arr_rules['email'] ='required|email';

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            return response()->json(['status'=>'ERROR','msg'=>$validator->messages()->first()]);
        }

    	$email = $request->input('email',null);

    	$status = $this->MailchimpService->subscribe($email);

    	if (isset($status)) 
    	{
    		if ($status=='SUBSCRIBED') 
    		{
    			$arr_responce['status'] = 'SUCCESS';
    			$arr_responce['msg'] 	= 'Thank you ! You are successfully subscribed to our newsletter.';
    		}
    		elseif ($status=='ALREADY_EXISTS') 
    		{
    			$arr_responce['status'] = 'ERROR';
    			$arr_responce['msg'] 	= 'You are already subscribed to our newsletter.';
    		}
    		elseif ($status=='INVALID_EMAIL') 
    		{
    			$arr_responce['status'] = 'ERROR';
    			$arr_responce['msg'] 	= 'Please enter a valid email address.';
    		}
    		else
    		{
    			$arr_responce['status'] = 'ERROR';
    			$arr_responce['msg'] 	= 'Error while processing your request, please try after sometime.';	
    		}
    	}
    	else
    	{
    		$arr_responce['status'] = 'ERROR';
    		$arr_responce['msg'] 	= 'Error while processing your request, please try after sometime.';	
    	}

    	return response()->json($arr_responce);
    }
}
