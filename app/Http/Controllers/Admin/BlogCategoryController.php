<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\BlogCategoryModel;
use App\Common\Traits\MultiActionTrait;

use Validator;
use Session;
use Auth;
use Hash;

class BlogCategoryController extends Controller
{
    use MultiActionTrait;
	
	public function __construct(BlogCategoryModel $blogcategory)
	{
		$this->arr_data           = [];
		$this->admin_panel_slug   = config('app.project.admin_panel_slug');
		$this->admin_url_path     = url(config('app.project.admin_panel_slug'));
		$this->module_url_path    = $this->admin_url_path."/blog_category";
		$this->module_title       = "Blog Category";
		$this->module_view_folder = "admin.blog_category";
		$this->module_icon        = "fa fa-comment";
		$this->BlogCategoryModel  = $blogcategory;
		$this->BaseModel          = $blogcategory;
	}

	public function index()
	{
		$arr_blog_category = [];
		$obj_blog_category = $this->BaseModel
										->orderBy('created_at', 'desc')->get();
		
		if($obj_blog_category)
		{
			$arr_blog_category = $obj_blog_category->toArray();
		}	

		$this->arr_data['objects']          = $arr_blog_category;
		$this->arr_data['page_title']       = "Manage ".$this->module_title;
		$this->arr_data['module_icon']      = $this->module_icon;
		$this->arr_data['module_title']     = $this->module_title;
		$this->arr_data['module_url_path']  = $this->module_url_path;
		$this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;

		return view($this->module_view_folder.'.index',$this->arr_data);
	}

	public function create()
	{
        $this->arr_view_data['page_title']      = "Add ".$this->module_title;
        $this->arr_view_data['module_title']    = $this->module_title;
        $this->arr_view_data['module_url_path'] = $this->module_url_path;
        $this->arr_view_data['page_icon']       = 'fa-plus-square-o';
        $this->arr_view_data['module_icon']      = $this->module_icon;
        $this->arr_view_data['admin_panel_slug'] = $this->admin_panel_slug;
        return view($this->module_view_folder.'.create',$this->arr_view_data);
	}

	public function store(Request $request)
    {

        $arr_rules['category_name']         = "required";
        $msg                                = array(
            'required' =>'Please enter :attribute',
        );
        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $form_data = $request->all();

        $does_exists = $this->BaseModel
        								->where('category_name',$form_data['category_name'])
        								->count()>0;

        if($does_exists)
        {
            $validator->getMessageBag()->add('category_name', 'This blog category already exist');
            return redirect()->back()->withErrors($validator);
        }

        $category_name = strtolower(trim($form_data['category_name']));
        $slug  = str_slug($category_name);


        $this->arr_view_data['category_name'] = $category_name;
        $this->arr_view_data['slug'] 		  = $slug;
        $this->arr_view_data['status']        = 1;

        $blog_category = $this->BaseModel->create($this->arr_view_data);

        if($blog_category) 
        {
            return redirect()->back()->with('success','Blog category added successfully');            
        }
        else
        {
        	return redirect()->back()->with('error','Error while adding blog category');
        }
    }

    public function edit($id)
    {
        $arr_blog_category    = [];
        ($id)? $id            = base64_decode($id):NULL;
        $obj_blog_category    = $this->BaseModel->where('id', $id)->first();
        if(isset($obj_blog_category) && $obj_blog_category!="")
        { 
            $arr_blog_category    =   $obj_blog_category->toArray();
        }    

        $this->arr_view_data['blog_category']        = $arr_blog_category;
        $this->arr_view_data['id']                   = base64_encode($id);
        $this->arr_view_data['page_title']           = "Edit ".$this->module_title;
        $this->arr_view_data['page_icon']            = 'fa-plus-square-o';
        $this->arr_view_data['module_title']         = $this->module_title;
        $this->arr_view_data['module_url_path']      = $this->module_url_path;
        $this->arr_view_data['module_icon']  		 = $this->module_icon;
        $this->arr_view_data['admin_panel_slug'] 	 = $this->admin_panel_slug;
        
        return view($this->module_view_folder.'.edit',$this->arr_view_data);
    }

    public function update(Request $request, $id)
    {	
    	$id      = base64_decode($id);
    	$arr_rules['category_name'] = "required";
    	$msg                           = array(
									    	'required' =>'Please enter :attribute',
									        );
        $validator = Validator::make($request->all(), $arr_rules, $msg);
        
    	if ($validator->fails()) 
        {
        	return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $form_data = $request->all();

        $does_exists = $this->BaseModel
        								->where('category_name',$form_data['category_name'])
        								->where('id','!=',$id)
        								->count()>0; 
        if($does_exists)
        {
        	$validator->getMessageBag()->add('category_name', 'Blog category already exist');
            return redirect()->back()->withErrors($validator);
        }

        $category_name = strtolower(trim($form_data['category_name']));
        $slug  = str_slug($category_name);

        $this->arr_view_data['category_name'] = $category_name;        
        $this->arr_view_data['slug'] 		  = $slug;      
        
        $blog_category = $this->BaseModel->where('id',$id)->update($this->arr_view_data);       

        if($blog_category) 
        {
            return redirect()->back()->with('success','Blog category updated successfully');            
        }
        else
        {
        	return redirect()->back()->with('error','Error while updating blog category');
        }
    }
}
