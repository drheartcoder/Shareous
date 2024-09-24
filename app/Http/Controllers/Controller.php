<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public function build_response( $status = 'success',
                                    $message = "",
                                    $arr_data = [],
                                    $response_format = 'json',
                                    $response_code = 200)
    {
        if($response_format == 'json'){
            $arr_response = [
                'status'  => $status,
                'message' => $message
            ];

            if(sizeof($arr_data) > 0){
                $arr_response['data'] = $arr_data;
            }
            return response()->json($arr_response,$response_code,[],JSON_UNESCAPED_UNICODE);    
        }   
    }
}
