<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\TestimonialsModel;
use App\Common\Traits\MultiActionTrait;

use Validator;
use Session;
use Image;
use Response;

class TestimonialsController extends Controller
{
    use MultiActionTrait;
    function __construct(TestimonialsModel $testimonials_model)
    {
        $this->arr_data                 = [];
        $this->admin_panel_slug         = config('app.project.admin_panel_slug');
        $this->admin_url_path           = url(config('app.project.admin_panel_slug'));
        $this->module_url_path          = $this->admin_url_path."/testimonials";
        $this->module_title             = "Testimonials";
        $this->module_view_folder       = "admin.testimonials";
        $this->module_icon              = "fa fa-commenting";
        $this->TestimonialsModel        = $testimonials_model;   
        $this->BaseModel                = $testimonials_model;

        $this->testimonial_image_public_img_path = url('/').config('app.project.img_path.testimonial_image');
        $this->testimonial_image_base_img_path   = public_path().config('app.project.img_path.testimonial_image');
    }

    public function index()
    {
        $arr_testimonials = [];
        $obj_testimonials = $this->TestimonialsModel->orderBy('created_at','desc')->get();
        if(isset($obj_testimonials) && $obj_testimonials!=null)
        {
            $arr_testimonials = $obj_testimonials->toArray();
        }

        $this->arr_data['arr_testimonials'] = $arr_testimonials;
        $this->arr_data['page_title']       = "Manage ".$this->module_title;
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_title']     = $this->module_title;
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
        return view($this->module_view_folder.'.index',$this->arr_data);
    }

    public function create()
    {
        $this->arr_data['testimonial_image_public_img_path'] = $this->testimonial_image_public_img_path;
        $this->arr_data['testimonial_image_base_img_path']   = $this->testimonial_image_base_img_path;

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

        $arr_testimonials = $arr_rules  = $form_data = [];
        
        $arr_rules['title']   = "required";
        $arr_rules['message'] = "required";
        $msg                  = array('required' =>'Please enter :attribute', );

        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $title        = $request->input('title', null);
        $message      = $request->input('message', null);

        $old_image    = $request->input('oldimage');

        if($request->hasFile('image'))
        {            
            $file_extension = strtolower($request->file('image')->getClientOriginalExtension());
            
            if(in_array($file_extension,['png','jpg','jpeg']))
            {
                $file       = $request->file('image');
                $filename   = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
                $path       = $this->testimonial_image_base_img_path . $filename;
                $isUpload   = Image::make($file->getRealPath())->resize(269, 239)->save($path);

                if($isUpload)
                {

                    if ($old_image!="" && $old_image!=null) 
                    {
                        $image = $this->testimonial_image_base_img_path.$old_image;

                        if(file_exists($image))
                        {
                            unlink($image);
                        }
                    }
                }
            }    
            else
            {
                $file_name = $old_image;
                Session::flash('error','Invalid File type, While creating '.str_singular($this->module_title));
                return redirect()->back();
            }
        }        
        else
        {
            $file_name = $old_image;
        }

        $arr_testimonials['title']      = trim($title);        
        $arr_testimonials['message']    = trim($message);
        $arr_testimonials['image']      = isset($filename)? $filename: $file_name;

        $status = $this->TestimonialsModel->create($arr_testimonials);       
        if($status) 
        {
            return redirect()->back()->with('success','Testimonial added successfully');            
        }
        else
        {
            return redirect()->back()->with('error','Error while adding Testimonial');
        }           
        
        return redirect()->back()->with('error','Error while adding Testimonial');
    }

    public function view($id=null)
    {
        $arr_testimonials = [];

        if($id!=null)
        {
            $id                   = base64_decode($id);  
            $obj_testimonials     = $this->TestimonialsModel->where('id',$id)->first();
            $arr_testimonials     = $obj_testimonials->toArray();
        }

        $this->arr_data['testimonial_image_public_img_path'] = $this->testimonial_image_public_img_path;
        $this->arr_data['testimonial_image_base_img_path']   = $this->testimonial_image_base_img_path;

        $this->arr_data['id']                = base64_encode($id);
        $this->arr_data['testimonial']       = $arr_testimonials;
        $this->arr_data['page_title']        = "View ".$this->module_title;
        $this->arr_data['module_icon']       = $this->module_icon;
        $this->arr_data['page_icon']         = 'fa-eye';
        $this->arr_data['module_title']      = $this->module_title;
        $this->arr_data['module_url_path']   = $this->module_url_path;
        $this->arr_data['admin_panel_slug']  = $this->admin_panel_slug;

        return view($this->module_view_folder.'.view',$this->arr_data);
    }

    public function edit($id=null)
    {
        $arr_testimonials = [];

        if($id!=null)
        {
            $id          = base64_decode($id);  
            $obj_faq     = $this->TestimonialsModel->where('id',$id)->first();
            $arr_testimonials     = $obj_faq->toArray();
        }

        $this->arr_data['testimonial_image_public_img_path'] = $this->testimonial_image_public_img_path;
        $this->arr_data['testimonial_image_base_img_path']   = $this->testimonial_image_base_img_path;

        $this->arr_data['id']                = base64_encode($id);
        $this->arr_data['testimonial']       = $arr_testimonials;
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
        $arr_rules['title']    = "required";
        $arr_rules['message']  = "required";

        $msg                   = array('required' =>'Please enter :attribute',);

        $validator = Validator::make($request->all(), $arr_rules, $msg);
        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
 
        $title      = $request->input('title');
        $message    = $request->input('message');

        $old_image  = $request->input('oldimage');

        if($request->hasFile('image'))
        {            
            $file_extension = strtolower($request->file('image')->getClientOriginalExtension());
            
            if(in_array($file_extension,['png','jpg','jpeg']))
            {
                $file       = $request->file('image');
                $filename   = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
                $path       = $this->testimonial_image_base_img_path . $filename;
                $isUpload   = Image::make($file->getRealPath())->resize(269, 239)->save($path);

                if($isUpload)
                {

                    if ($old_image!="" && $old_image!=null) 
                    {
                        $image = $this->testimonial_image_base_img_path.$old_image;

                        if(file_exists($image))
                        {
                            unlink($image);
                        }
                    }
                }
            }    
            else
            {
                $file_name = $old_image;
                Session::flash('error','Invalid File type, While creating '.str_singular($this->module_title));
                return redirect()->back();
            }
        }        
        else
        {
            $file_name = $old_image;
        }

        $this->arr_view_data['title']   = trim($title);
        $this->arr_view_data['message'] = trim($message);
        $this->arr_view_data['image']   = isset($filename)? $filename: $file_name;

        $status = $this->TestimonialsModel->where('id',$id)->update($this->arr_view_data);       
        if($status) 
        {
            Session::flash('success', $this->module_title.' updated successfully.');
            return redirect($this->module_url_path);         
        }
        else
        {
            Session::flash('error','Error while updating Testimonial '.$this->module_title);
            return redirect($this->module_url_path);
        }
    }


    /*
    | Function  : Check duplicate username
    | Author    : Deepak Arvind Salunke
    | Date      : 04/05/2018
    | Output    : Success or Error
    */

    public function duplicate(Request $request)
    {
        $title = $request->input('title');
        $count = $this->TestimonialsModel->where('title', $title)->count();
        if($count > 0)
        {
            return Response::json('error');
        }
        else
        {
            return Response::json('success');
        }

    } // end duplicate
}
