<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\AdminModel;

use Validator;
use Session;
use Image;
use Auth;
use Hash;

class ProfileController extends Controller
{
   	public function __construct(AdminModel $admin)
    {
        $this->arr_view_data                 = [];
        $this->admin_panel_slug              = config('app.project.admin_panel_slug');
        $this->admin_url_path                = url(config('app.project.admin_panel_slug'));
        $this->module_title                  = "Account Setting";
        $this->module_view_folder            = "admin.account_settings";
        $this->module_url_path               = $this->admin_url_path."/profile";
        $this->profile_image_public_img_path = url('/').config('app.project.img_path.admin_profile_images');
        $this->profile_image_base_img_path   = public_path().config('app.project.img_path.admin_profile_images');
        $this->auth                          = auth()->guard('admin');
        $this->AdminModel                    = $admin;
        $this->admin_id                      = isset($this->auth->user()->id)? $this->auth->user()->id:0;
        $this->admin_login_path              = $this->admin_url_path."/login";
    }

    public function index()
    {
        $arr_data = [];
        $arr_account_settings = array();
        $obj_data = $this->auth->user();

        if($obj_data) {
            $arr_data = $obj_data->toArray();
        }

        $this->arr_view_data['arr_data']                      = $arr_data;
        $this->arr_view_data['page_title']                    = str_plural($this->module_title);
        $this->arr_view_data['module_title']                  = str_plural($this->module_title);
        $this->arr_view_data['module_url_path']               = $this->module_url_path;
        $this->arr_view_data['profile_image_public_img_path'] = $this->profile_image_public_img_path;
        $this->arr_view_data['profile_image_base_img_path']   = $this->profile_image_base_img_path;
        $this->arr_view_data['admin_panel_slug']              = $this->admin_panel_slug;

        return view($this->module_view_folder.'.index',$this->arr_view_data);
    }

    public function update(Request $request)
    {
        $file_name = $filename = '';

        $arr_rules               = array();
        $arr_rules['user_name']  = "required";
        $arr_rules['first_name'] = "required";
        $arr_rules['last_name']  = "required";
        $arr_rules['email']      = "email|required";
        $arr_rules['contact']    = "required|min:7|max:16";

        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $arr_admin = $this->AdminModel->where('id',$this->admin_id)->get();

        $old_image = $request->input('oldimage');
        if($request->hasFile('image'))
        {            
            $file_extension = strtolower($request->file('image')->getClientOriginalExtension());

            if(in_array($file_extension,['png','jpg','jpeg'])) {
                $file       = $request->file('image');
                $filename   = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
                $path       = $this->profile_image_base_img_path . $filename;
                $isUpload   = Image::make($file->getRealPath())->resize(269, 239)->save($path);

                if($isUpload) {
                    if ($old_image != "" && $old_image != null) {
                        $profile_image = $this->profile_image_base_img_path.$old_image;
                        if(file_exists($profile_image)) {
                            unlink($profile_image);
                        }
                    }
                }
            }    
            else {
                $file_name = $old_image;
                Session::flash('error','Invalid File type, While creating '.str_singular($this->module_title));
                return redirect()->back();
            }
        }
        else {
            $file_name = $old_image;
        }
                
        $arr_data['user_name']     = trim($request->input('user_name'));
        $arr_data['first_name']    = trim($request->input('first_name'));
        $arr_data['last_name']     = trim($request->input('last_name'));
        $arr_data['email']         = $request->input('email');
        $arr_data['contact']       = $request->input('contact');
        $arr_data['profile_image'] = isset($filename)&&$filename!=''? $filename: $file_name;

        $obj_data = $this->AdminModel->where('id',$this->admin_id)->update($arr_data);
        if($obj_data)
        {
            $email = $request->input('email');
            if(isset($arr_admin[0]['email']) && !empty($arr_admin[0]['email'] && isset($email))) {
                if($arr_admin[0]['email'] == $email ) {
                    Session::flash('success',str_singular($this->module_title).' Updated Successfully'); 
                    return redirect()->back();
                }
                elseif($arr_admin[0]['email'] != $email ) {
                    $this->auth->logout();
                    Session::flash('success','Your email changed successfully.');
                    return redirect($this->admin_login_path);
                }
            }
        }
        else
        {
            Session::flash('error','Problem Occurred, While Updating '.str_singular($this->module_title));
            return redirect()->back();
        }
    }

    public function change_password()
    {
        $this->arr_view_data['page_title']       = "Change Password";
        $this->arr_view_data['module_title']     = "Change Password";
        $this->arr_view_data['module_url_path']  = $this->module_url_path.'/change_password';
        $this->arr_view_data['admin_panel_slug'] = $this->admin_panel_slug;

        return view($this->module_view_folder.'.change_password',$this->arr_view_data);
    }

    public function update_password(Request $request)
    {
        $arr_rules = array();
        $status = FALSE;

        $arr_rules['current_password'] = "required";
        $arr_rules['new_password']     = "required";
        $arr_rules['confirm_password'] = "required|same:new_password";        
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $current_password = $request->input('current_password');
        $new_password     = $request->input('new_password');
        $confirm_password = $request->input('confirm_password');

        if(Hash::check($current_password,$this->auth->user()->password))
        {
            if($current_password!=$new_password)
            {
                if($new_password == $confirm_password)
                {
                    $user_password = Hash::make($confirm_password);
                    $status = $this->AdminModel->where('id',$this->admin_id)->update(['password'=>$user_password]);

                    if($status)
                    {
                        $this->auth->logout();
                        Session::flash('success','Your password changed successfully.');
                        return redirect($this->admin_login_path);
                    }
                    else
                    {
                        Session::flash('error','Problem occured, while changing password');
                    }
                    return redirect()->back();
                }
                else
                {
                    Session::flash('error','New password and confirm password does not match.');
                    return redirect()->back();
                }
            }
            else
            {
                Session::flash('error','Sorry you not use current password as new password, Please enter another new password');
                return redirect()->back();
            }
        }
        else
        {
            $arr_data             = [];
            $obj_data             = $this->auth->user();
            $arr_account_settings = array();

            if($obj_data)
            {
                $arr_data = $obj_data->toArray();    
            }
            Session::flash('error',"Incorrect old password");
            return redirect()->back();
        }

        Session::flash('error','Problem occured, while changing password');
        return redirect()->back();
    }
}
