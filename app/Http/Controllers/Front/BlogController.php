<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\BlogModel;
use App\Models\BlogCommentsModel;
use App\Models\BlogCategoryModel;
use App\Models\BlogIpAddressModel;

use Session;
use Server;
use Auth;
use Validator;
use Hash;
use Image;
use Crypt;

class BlogController extends Controller
{
  	public function __construct(BlogModel $blog_model,BlogCategoryModel $blogcategory_model, BlogCommentsModel $blogcomments_model, BlogIpAddressModel $blogipaddress_Model)
	{
		$this->array_view_data               = [];
		$this->module_title                  = 'Blog';
		$this->module_view_folder            = 'front.blog';
		$this->blog_image_public_img_path    = url('/').config('app.project.img_path.blog_image');
		$this->blog_image_base_img_path      = public_path().config('app.project.img_path.blog_image');
		$this->profile_image_public_img_path = url('/').config('app.project.img_path.user_profile_images');
		$this->profile_image_base_img_path   = public_path().config('app.project.img_path.user_profile_images');
		$this->module_url_path               = url('/blog');
		$this->BlogIpAddressModel            = $blogipaddress_Model;
		$this->BlogCategoryModel             = $blogcategory_model;
		$this->BlogCommentsModel             = $blogcomments_model;
		$this->BlogModel  		             = $blog_model;
		
	}
    public function index($category=null)
    {
    	//dd('here');
    	$arr_blog = $obj_pagination = $arr_blog_category = $arr_blog_recent = [];

    	$obj_blog = $this->BlogModel->with('blog_category')->withCount('blog_view_count')->withCount('blog_comment_count');

    	if($category!=null)
    	{
	    	$obj_blog = $obj_blog->whereHas('blog_category', function($q) use($category){
	    		$q->where('slug', $category);
	    	});	
    	}
    	$obj_blog = $obj_blog->where('status','=',1)->paginate(3);

		if(isset($obj_blog) && $obj_blog!=null)
		{
			$arr_blog        = $obj_blog->toArray();
			$obj_pagination = clone $obj_blog;
		}

		$obj_blog_recent = $this->BlogModel->where('status','=',1)->orderBy('id','desc')->limit(3)->get(); ;

		if(isset($obj_blog_recent) && $obj_blog_recent!=null)
		{
			$arr_blog_recent   = $obj_blog_recent->toArray();			
		}

		$arr_blog_category = $this->get_category_count();
		// dd($arr_blog_category);

		$this->array_view_data['arr_blog_recent']	         = $arr_blog_recent;
		$this->array_view_data['obj_pagination']	         = $obj_pagination;
		$this->array_view_data['module_url_path']	         = $this->module_url_path;		
		$this->array_view_data['arr_blog']			         = $arr_blog;
		$this->array_view_data['arr_blog_category']	         = $arr_blog_category;
		$this->array_view_data['blog_image_public_img_path'] = $this->blog_image_public_img_path;
		$this->array_view_data['blog_image_base_img_path']   = $this->blog_image_base_img_path;
		$this->array_view_data['page_title'] 		         = $this->module_title;

    	return view($this->module_view_folder.'.blog', $this->array_view_data);
    }

    public function blog_details($id)
    {
		$user_ip = getUserIP();

    	$id = decrypt($id);    	

    	$arr_blog = $arr_blog_category = $arr_blog_recent = $arr_blog_comments = $arr_blog_view_count = [];

    	//Blog view comment count

    	$does_exists = $this->BlogIpAddressModel->where('blog_id','=',$id )->where('ip_address','=', $user_ip)->count()>0;   	
       
        if($does_exists==False)
        {
        	$this->arr_view_data['blog_id'] 	= $id;
        	$this->arr_view_data['ip_address']  = $user_ip;

        	$this->BlogIpAddressModel->create($this->arr_view_data);    
        }

        //Get all comment

    	$obj_blog_comments = $this->BlogCommentsModel->with('user_details')->where('blog_id','=',$id)->where('status','=',1)->get();


    	if(isset($obj_blog_comments) && $obj_blog_comments!=null)
		{
			$arr_blog_comments   = $obj_blog_comments->toArray();			
		}	

		//Get blog details

    	$obj_blog = $this->BlogModel->where('id','=',$id)->with('blog_category')->first();
    	
		if(isset($obj_blog) && $obj_blog!=null)
		{
			$arr_blog   = $obj_blog->toArray();			
		}	

		//Get recent blog for sidebar

		$obj_blog_recent = $this->BlogModel->where('status','=',1)->orderBy('id','desc')->limit(3)->get(); ;

		if(isset($obj_blog_recent) && $obj_blog_recent!=null)
		{
			$arr_blog_recent   = $obj_blog_recent->toArray();			
		}

		$arr_blog_view_count = $this->BlogIpAddressModel->where('blog_id','=',$id )->count();		
		
		$arr_blog_category = $this->get_category_count();

		$this->array_view_data['arr_blog']	         		    = $arr_blog;
		$this->array_view_data['arr_blog_view_count']	    	= $arr_blog_view_count;
		$this->array_view_data['arr_blog_count']	   		    = count($arr_blog_comments);
		$this->array_view_data['arr_blog_comments']	            = $arr_blog_comments;
		$this->array_view_data['arr_blog_recent']	            = $arr_blog_recent;
		$this->array_view_data['module_url_path']	            = $this->module_url_path;	
		$this->array_view_data['arr_blog_category']	            = $arr_blog_category;
		$this->array_view_data['profile_image_public_img_path'] = public_path().config('app.project.img_path.user_profile_images');
		$this->array_view_data['profile_image_public_img_path'] = url('/').config('app.project.img_path.user_profile_images');
		$this->array_view_data['blog_image_public_img_path']    = $this->blog_image_public_img_path;
		$this->array_view_data['blog_image_base_img_path']      = $this->blog_image_base_img_path;
		$this->array_view_data['page_title'] 		            = $this->module_title;

    	return view($this->module_view_folder.'.blog-details', $this->array_view_data);
    }

    function get_category_count()
    {
    	$arr_category = [];
    	$obj_blog_category = $this->BlogCategoryModel->where('status','=',1)->select('id','category_name','slug')
    																		->orderBy('id','desc')->get();

		if(isset($obj_blog_category) && $obj_blog_category!=null)
		{
			$arr_blog_category  = $obj_blog_category->toArray();
		}
		if(isset($arr_blog_category) && sizeof($arr_blog_category)>0)
		{
			foreach ($arr_blog_category as $key => $value) 
			{
				$arr_category[] = $value;
				$arr_category[$key]['blog_count'] = $this->BlogModel
				->where('blog_category_id','=',$value['id'])
				->where('status','1')
				->count();
			}
		}
		return $arr_category;
    }

    public function add_review(Request $request)
    {
    	//dd($request->all());
    	$arr_rules['title']           = "required";
    	$arr_rules['comment']         = "required";
        $msg                               = array(
            'required' =>'Please enter :attribute',
        );
        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $form_data = $request->all();

        $this->arr_view_data['user_id'] = $form_data['user_id'];
        $this->arr_view_data['blog_id'] = $form_data['blog_id'];
        $this->arr_view_data['title']   = $form_data['title'];
        $this->arr_view_data['comment'] = $form_data['comment'];

        $blog_comments = $this->BlogCommentsModel->create($this->arr_view_data);

        if($blog_comments) 
        {
            return redirect()->back()->with('success','Comments added successfully');            
        }
        else
        {
        	return redirect()->back()->with('error','Error while adding comments');
        }

    }    
   
}
