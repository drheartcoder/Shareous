<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\FaqModel;
use App\Common\Traits\MultiActionTrait;
use Validator;
use Session;

class FaqController extends Controller
{
	use MultiActionTrait;
    function __construct(FaqModel $faq_model)
	{
		$this->arr_data                 = [];
		$this->admin_panel_slug         = config('app.project.admin_panel_slug');
		$this->admin_url_path           = url(config('app.project.admin_panel_slug'));
		$this->module_url_path          = $this->admin_url_path."/faq";
		$this->module_title             = "FAQ";
		$this->module_view_folder       = "admin.faq";
		$this->module_icon              = "fa fa-question-circle";
		$this->FaqModel           		= $faq_model;	
		$this->BaseModel                = $faq_model;
	}

	public function index()
	{
		$arr_faq = [];
		$obj_faq = $this->FaqModel->orderBy('created_at','desc')->get();
		if(isset($obj_faq) && $obj_faq!=null)
		{
			$arr_faq = $obj_faq->toArray();
		}
		$this->arr_data['arr_faq']			= $arr_faq;
		$this->arr_data['page_title']       = "Manage ".$this->module_title;
		$this->arr_data['module_icon']      = $this->module_icon;
		$this->arr_data['module_title']     = $this->module_title;
		$this->arr_data['module_url_path']  = $this->module_url_path;
		$this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
		return view($this->module_view_folder.'.index',$this->arr_data);
	}

	public function create()
	{
		$this->arr_data['page_title']        = "Add ".$this->module_title;
		$this->arr_data['module_icon']       = $this->module_icon;
		$this->arr_data['page_icon']         = 'fa-plus-square-o';
		$this->arr_data['module_title']      = $this->module_title;
		$this->arr_data['module_url_path']   = $this->module_url_path;
		$this->arr_data['admin_panel_slug']  = $this->admin_panel_slug;	
		return view($this->module_view_folder.'.create',$this->arr_data);
	}

	public function store(Request $request)
	{
		$msg ='';
		$arr_faq = $arr_rules  = $form_data = [];
		$arr_rules['question'] = "required";
    	$arr_rules['answer']   = "required";
    	$msg                   = array(
									    	'required' =>'Please enter :attribute',
									   );
        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $question        = $request->input('question', null);
		$answer          = $request->input('answer', null);
               
        $does_exists = $this->FaqModel->where('question',$question)->count()>0; 
        if($does_exists)
        {
            $validator->getMessageBag()->add('question', 'This question already exist');
            return redirect()->back()->withErrors($validator);
        }

        $arr_faq['question'] = trim($question);        
        $arr_faq['answer']   = trim($answer);        

        $status = $this->FaqModel->create($arr_faq);       
        if($status) 
        {
            return redirect()->back()->with('success','FAQ added successfully');            
        }
        else
        {
        	return redirect()->back()->with('error','Error while adding FAQ');
        }       	
        
        return redirect()->back()->with('error','Error while adding FAQ');
	}


	public function edit($id=null)
	{
		$arr_faq = [];

		if($id!=null)
		{
			$id          = base64_decode($id);	
			$obj_faq     = $this->FaqModel->where('id',$id)->first();
			$arr_faq     = $obj_faq->toArray();
		}

		$this->arr_data['id']                = base64_encode($id);
		$this->arr_data['faq']               = $arr_faq;
		$this->arr_data['page_title']        = "Edit ".$this->module_title;
		$this->arr_data['module_icon']       = $this->module_icon;
		$this->arr_data['page_icon']         = 'fa-pencil-square-o';
		$this->arr_data['module_title']      = $this->module_title;
		$this->arr_data['module_url_path']   = $this->module_url_path;
		$this->arr_data['admin_panel_slug']  = $this->admin_panel_slug;

		return view($this->module_view_folder.'.edit',$this->arr_data);
	}

	public function update(Request $request, $id)
    {
    	$msg                   = '';
    	$id                    = base64_decode($id);
    	$arr_rules['question'] = "required";
    	$arr_rules['answer']   = "required";

    	$msg                   = array(
									    	'required' =>'Please enter :attribute',
									   );
        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
 
        $question = $request->input('question');
        $answer   = $request->input('answer');

        $does_exists = $this->FaqModel
        								->where('question',$question)
        								->where('id','!=',$id)
        								->count()>0; 
        if($does_exists)
        {
            $validator->getMessageBag()->add('question', 'This question already exist');
            return redirect()->back()->withErrors($validator);
        }

        $this->arr_view_data['question'] = trim($question);        
        $this->arr_view_data['answer']   = trim($answer);        

        $status = $this->FaqModel->where('id',$id)->update($this->arr_view_data);       
        if($status) 
        {
        	Session::flash('success', $this->module_title.' updated successfully.');
			return redirect($this->module_url_path);         
        }
        else
        {
        	Session::flash('error','Error while updating FAQ '.$this->module_title);
			return redirect($this->module_url_path);
        }
    }
}
