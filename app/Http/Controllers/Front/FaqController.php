<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Models\FaqModel;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    public function __construct(FaqModel $faq_model)
	{
		$this->array_view_data    = [];
		$this->module_title       = 'FAQ';
		$this->module_view_folder = 'front.home';
		$this->module_url_path    = url('/faq');
		$this->FaqModel 		  = $faq_model;
		$this->BaseModel          = $faq_model;


	}
    public function index(Request $request)
    {
    	$arr_faq = $obj_pagination = [];

    	$obj_faq = null;
    	
		if($request->has('search') && !empty($request->input('search')))
		{   
    			$search = $request->input('search');
	        	$obj_faq = $this->FaqModel
	           							->where(function ($query) use($search) {
	           	                        	$query->where('question','LIKE','%'.$search.'%');
	           	                        	$query->orWhere('answer','LIKE','%'.$search.'%');	
	           	                        })
	           	                        ->where('status','=',1)	           	                        
	           	                        ->orderBy('id','desc')
	           							->select('id','question','answer','status','created_at')
	           							->paginate(10);
		}
		else
		{
			$obj_faq = $this->FaqModel->where('status','=',1)->orderBy('id','desc')->paginate(10);
		}

		if(isset($obj_faq) && $obj_faq!=null)
		{
			$arr_faq        = $obj_faq->toArray();
			$obj_pagination = clone $obj_faq;
		}

		$this->array_view_data['obj_pagination']	= $obj_pagination;
		$this->array_view_data['arr_faq']			= $arr_faq;
		$this->array_view_data['module_url_path']	= $this->module_url_path;		
    	$this->array_view_data['page_title'] 		= $this->module_title;

    	return view($this->module_view_folder.'.faq', $this->array_view_data);
    }
}
