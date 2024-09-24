<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\FrontPagesModel;


class FrontPagesController extends Controller
{
	public function __construct(FrontPagesModel $front_pages_model)
	{
		$this->array_view_data    = [];
		$this->module_title       = 'Front Pages';
		$this->module_view_folder = 'front.front_pages.';

		$this->FrontPagesModel    = $front_pages_model;
	}

    /*
    | Function  : Get page data
    | Author    : Deepak Arvind Salunke
    | Date      : 22/02/2018
    | Output    : Success or Error
    */

    public function index($slug)
    {
    	$arr_front_pages = [];

    	if(isset($slug) && $slug != null)
    	{
    		$obj_front_pages = $this->FrontPagesModel->where('page_slug', $slug)->where('status', 1)->first();
    		if($obj_front_pages!=FALSE)
    		{
    			$arr_front_pages = $obj_front_pages->toArray();

    			if(sizeof($arr_front_pages) > 0)
    			{
    				$this->array_view_data['meta_title'] = $arr_front_pages['meta_title'];
    				$this->array_view_data['meta_keyword'] = $arr_front_pages['meta_keyword'];
    				$this->array_view_data['meta_description'] = $arr_front_pages['meta_description'];

    				$this->array_view_data['page_title'] = ucwords($arr_front_pages['page_title']);
    				$this->array_view_data['page_data'] = $arr_front_pages;

	    			return view($this->module_view_folder.'index', $this->array_view_data);
    			}
    		}
            else
            {
                return response()->view('errors.404page', [], 404);
            }
    	}
    	else
    	{
    		return redirect()->back();
    	}
    }
}
