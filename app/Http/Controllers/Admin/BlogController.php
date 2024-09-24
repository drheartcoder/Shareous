<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\BlogModel;
use App\Models\BlogCategoryModel;
use App\Models\BlogCommentsModel;
use App\Common\Traits\MultiActionTrait;

use Validator;
use Session;
use Auth;
use Hash;
use Image;

class BlogController extends Controller
{
 	use MultiActionTrait;
	
	public function __construct(BlogModel $blog_model,BlogCategoryModel $blogcategory_model, BlogCommentsModel $blogcomments_model)
	{
		$this->arr_data           			 = [];
		$this->admin_panel_slug   			 = config('app.project.admin_panel_slug');
		$this->admin_url_path     			 = url(config('app.project.admin_panel_slug'));
		$this->blog_image_public_img_path    = url('/').config('app.project.img_path.blog_image');
		$this->blog_image_base_img_path      = public_path().config('app.project.img_path.blog_image');
		$this->module_url_path    			 = $this->admin_url_path."/blog";
		$this->module_title       			 = "Blog";
		$this->module_view_folder 			 = "admin.blog";
		$this->module_icon        			 = "fa fa-comment";
		$this->BlogCommentsModel  			 = $blogcomments_model;
		$this->BlogCategoryModel  			 = $blogcategory_model;
		$this->BlogModel  		  			 = $blog_model;
		$this->BaseModel          			 = $blog_model;
	}

	public function index()
	{
		$arr_blog = [];
		$obj_blog = $this->BaseModel
		->with('blog_category')
		->orderBy('id', 'Desc')
		->get();		
		
		if($obj_blog)
		{
			$arr_blog = $obj_blog->toArray();
		}	

		$this->arr_data['objects']                         = $arr_blog;
		$this->arr_data['page_title']                      = "Manage ".$this->module_title;
		$this->arr_data['module_icon']                     = $this->module_icon;
		$this->arr_data['module_title']                    = $this->module_title;
		$this->arr_view_data['blog_image_public_img_path'] = $this->blog_image_public_img_path;
		$this->arr_view_data['blog_image_base_img_path']   = $this->blog_image_base_img_path;
		$this->arr_data['module_url_path']                 = $this->module_url_path;
		$this->arr_data['admin_panel_slug']                = $this->admin_panel_slug;

		return view($this->module_view_folder.'.index',$this->arr_data);
	}
	public function create()
	{
		$arr_blog_category = [];
		$obj_blog_category = $this->BlogCategoryModel
		->where('status','=',1)
		->orderBy('id', 'Desc')
		->get();
		
		if($obj_blog_category)
		{
			$arr_blog_category = $obj_blog_category->toArray();
		}			

		$this->arr_view_data['blog_category']              = $arr_blog_category;
		$this->arr_view_data['page_title']                 = "Add ".$this->module_title;
		$this->arr_view_data['module_title']               = $this->module_title;
		$this->arr_view_data['module_url_path']            = $this->module_url_path;
		$this->arr_view_data['page_icon']                  = 'fa-plus-square-o';
		$this->arr_view_data['module_icon']                = $this->module_icon;
		$this->arr_view_data['blog_image_public_img_path'] = $this->blog_image_public_img_path;
		$this->arr_view_data['blog_image_base_img_path']   = $this->blog_image_base_img_path;
		$this->arr_view_data['admin_panel_slug']           = $this->admin_panel_slug;
		return view($this->module_view_folder.'.create',$this->arr_view_data);
	}

	public function store(Request $request)
	{

		$arr_rules['title']         	= "required";
		$arr_rules['descriptions']      = "required";
		$arr_rules['category_id']       = "required";       

		$msg                = array(
			'required' =>'Please enter :attribute',
			);

		$validator = Validator::make($request->all(), $arr_rules, $msg);
		if ($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput($request->all());
		}

		if($request->hasFile('blog_image'))
		{
			$file = $request->file('blog_image');
			$file_extension = strtolower($request->file('blog_image')->getClientOriginalExtension());
			if(in_array($file_extension,['png','jpg','jpeg']))
			{
				$file_name = time().uniqid().'.'.$file_extension;
				$path       = $this->blog_image_base_img_path . $file_name;
				$isUpload   = Image::make($file->getRealPath())->resize(767, 238)->save($path);
				$arr_data['blog_image'] = $file_name;
			}
			else
			{
				return redirect()->back()->with('error','Invalid File type, please select valid image file');  				
			}
		}

		$form_data = $request->all();

		$this->arr_view_data['title']            = $form_data['title'];
		$this->arr_view_data['description']      = $form_data['descriptions'];
		$this->arr_view_data['blog_category_id'] = $form_data['category_id'];
		$this->arr_view_data['blog_image']       = $file_name;
		$this->arr_view_data['status']           = 1;

		$blog_category = $this->BaseModel->create($this->arr_view_data);

		if($blog_category) 
		{
			return redirect('admin/blog')->with('success','Blog added successfully');            
		}
		else
		{
			return redirect()->back()->with('error','Error while adding blog');
		}
	}

	public function edit($id)
	{
		$arr_blog  = $arr_blog_category = [];
		($id)? $id = base64_decode($id):NULL;
		$obj_blog  = $this->BaseModel->where('id', $id)->first();

		$obj_blog_category = $this->BlogCategoryModel->where('status','=',1)->orderBy('id', 'Desc')->get();
		
		if($obj_blog_category)
		{
			$arr_blog_category = $obj_blog_category->toArray();
		}

		if(isset($obj_blog) && $obj_blog!="")
		{ 
			$arr_blog    =   $obj_blog->toArray();
		}


		$this->arr_view_data['blog']        		       = $arr_blog;
		$this->arr_view_data['blog_category']        	   = $arr_blog_category;
		$this->arr_view_data['id']                         = base64_encode($id);
		$this->arr_view_data['page_title']                 = "Edit ".$this->module_title;
		$this->arr_view_data['page_icon']                  = 'fa-plus-square-o';
		$this->arr_view_data['module_title']               = $this->module_title;
		$this->arr_view_data['module_url_path']            = $this->module_url_path;
		$this->arr_view_data['blog_image_public_img_path'] = $this->blog_image_public_img_path;
		$this->arr_view_data['blog_image_base_img_path']   = $this->blog_image_base_img_path;
		$this->arr_view_data['module_icon']  		       = $this->module_icon;
		$this->arr_view_data['admin_panel_slug'] 	       = $this->admin_panel_slug;

		return view($this->module_view_folder.'.edit',$this->arr_view_data);
	}

	public function update(Request $request, $id)
	{	

    	//dd($request->all());

		$id      = base64_decode($id);
		$arr_rules['title']         	= "required";
		$arr_rules['descriptions']      = "required";
		$arr_rules['category_id']       = "required"; 

		$msg    = array(
			'required' =>'Please enter :attribute',
			);
		$validator = Validator::make($request->all(), $arr_rules, $msg);

		if ($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput($request->all());
		}

		$file_name = '';
		$old_image = $request->input('oldimage');		

		if($request->hasFile('blog_image'))
		{
			$file = $request->file('blog_image');
			$file_extension = strtolower($request->file('blog_image')->getClientOriginalExtension());
			if(in_array($file_extension,['png','jpg','jpeg']))
			{
				$file_name = time().uniqid().'.'.$file_extension;
				$path       = $this->blog_image_base_img_path . $file_name;
				$isUpload   = Image::make($file->getRealPath())->resize(767, 238)->save($path);
				if($isUpload)
				{
					@unlink($this->blog_image_base_img_path.$old_image);
				}
			}
			else
			{
				return redirect()->back()->with('error','Invalid File type, please select valid image file');  				
			}
		}
		else
		{
			$file_name = $old_image;
		}

		$form_data = $request->all();

		$this->arr_view_data['title']            = $form_data['title'];
		$this->arr_view_data['description']      = $form_data['descriptions'];
		$this->arr_view_data['blog_category_id'] = $form_data['category_id'];
		$this->arr_view_data['blog_image']       = $file_name;


		$blog = $this->BaseModel->where('id',$id)->update($this->arr_view_data);       

		if($blog) 
		{
			return redirect('admin/blog')->with('success','Blog updated successfully');            
		}
		else
		{
			return redirect()->back()->with('error','Error while updating blog');
		}
	}

	public function view($id)
	{
		$id = base64_decode($id);

		$arr_blog_comments = $arr_blog = [];

		$obj_blog_comments = $this->BlogCommentsModel->with('user_details')->where('blog_id','=',$id)->get();


		if(isset($obj_blog_comments) && $obj_blog_comments!=null)
		{
			$arr_blog_comments   = $obj_blog_comments->toArray();			
		}

		$obj_blog = $this->BlogModel->where('id','=',$id)->first();

		if(isset($obj_blog) && $obj_blog!=null)
		{
			$arr_blog = $obj_blog->toArray();			
		}

		$this->array_view_data['blogs']	            			= $arr_blog;		
		$this->array_view_data['objects']	            		= $arr_blog_comments;		
		$this->array_view_data['module_url_path']	            = $this->module_url_path;	
		$this->array_view_data['profile_image_public_img_path'] = public_path().config('app.project.img_path.user_profile_images');
		$this->array_view_data['profile_image_public_img_path'] = url('/').config('app.project.img_path.user_profile_images');
		$this->array_view_data['blog_image_public_img_path']    = $this->blog_image_public_img_path;
		$this->array_view_data['blog_image_base_img_path']      = $this->blog_image_base_img_path;
		$this->array_view_data['module_icon']  		       		= $this->module_icon;
		$this->array_view_data['page_title'] 		            = $this->module_title;
		$this->array_view_data['module_title'] 		            = $this->module_title;
		$this->array_view_data['page_icon']              	    = 'fa-plus-square-o';
		$this->array_view_data['admin_panel_slug'] 	       		= $this->admin_panel_slug;

		return view($this->module_view_folder.'.blog-details', $this->array_view_data);
	}

	public function block($enc_id = FALSE)
	{
		if(!$enc_id)
		{
			return redirect()->back();
		}

		if($this->perform_block(base64_decode($enc_id),'blog_model'))
		{
			Session::flash('success', $this->module_title. ' blocked Successfully');
		}
		else
		{
			Session::flash('error', 'Problem Occured While '.$this->module_title.' Deactivation ');
		}

		return redirect()->back();
	}

	public function unblock($enc_id = FALSE)
	{
		if(!$enc_id)
		{
			return redirect()->back();
		}

		if($this->perform_unblock(base64_decode($enc_id),'blog_model'))
		{
			Session::flash('success', $this->module_title. ' Unblocked Successfully');
			return redirect()->back();
		}
		else
		{
			Session::flash('error', 'Problem Occured While '.$this->module_title.' Activation ');
		}

		return redirect()->back();
	}

	public function delete($enc_id = FALSE)
	{
		if(!$enc_id)
		{
			return redirect()->back();
		}

		if($this->perform_delete(base64_decode($enc_id),'blog_model'))
		{
			Session::flash('success', $this->module_title. ' deleted Successfully');
		}
		else
		{
			Session::flash('error', 'Problem Occured While '.$this->module_title.' deletation ');
		}

		return redirect()->back();
	}

	public function delete_view($enc_id = FALSE)
	{
//		dd($enc_id);
		if(!$enc_id)
		{
			return redirect()->back();
		}

		if($this->perform_delete(base64_decode($enc_id)))
		{
			Session::flash('success', $this->module_title. ' deleted Successfully');
		}
		else
		{
			Session::flash('error', 'Problem Occured While '.$this->module_title.' deletation ');
		}

		return redirect()->back();
	}

	public function block_view($enc_id = FALSE)
	{
		// dd('here');
		if(!$enc_id)
		{
			return redirect()->back();
		}

		if($this->perform_block(base64_decode($enc_id)))
		{
			Session::flash('success', $this->module_title. ' blocked Successfully');
		}
		else
		{
			Session::flash('error', 'Problem Occured While '.$this->module_title.' Deactivation ');
		}

		return redirect()->back();
	}

	public function unblock_view($enc_id = FALSE)
	{
		if(!$enc_id)
		{
			return redirect()->back();
		}

		if($this->perform_unblock(base64_decode($enc_id)))
		{
			Session::flash('success', $this->module_title. ' Unblocked Successfully');
			return redirect()->back();
		}
		else
		{
			Session::flash('error', 'Problem Occured While '.$this->module_title.' Activation ');
		}

		return redirect()->back();
	}

	public function perform_unblock($id, $model=null)
	{

		if($model){

			$responce = $this->BaseModel->where('id',$id)->first();
		}
		else
		{
			$responce = $this->BlogCommentsModel->where('id',$id)->first();
		}

		if($responce)
		{
			return $responce->update(['status'=>1]);
		}

		return FALSE;
	}

	public function perform_block($id, $model=null)
	{
		if($model){

			$responce = $this->BaseModel->where('id',$id)->first();
		}
		else
		{
			$responce = $this->BlogCommentsModel->where('id',$id)->first();
		}

		if($responce)
		{
			return $responce->update(['status'=>0]);
		}

		return FALSE;
	}

	public function perform_delete($id, $model=null)
	{

		if($model)
		{
			$delete= $this->BaseModel->where('id',$id)->delete();
		}
		else
		{
			$delete= $this->BlogCommentsModel->where('id',$id)->delete();
		}

		if($delete)
		{
			return TRUE;
		}

		return FALSE;
	}


}
