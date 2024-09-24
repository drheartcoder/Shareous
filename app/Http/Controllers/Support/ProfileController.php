<?php

namespace App\Http\Controllers\Support;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SupportTeamModel;
use Validator;
use Session;
use Auth;
use Hash;
use Image;

class ProfileController extends Controller
{
    public function __construct(SupportTeamModel $support_team_model)
    {
        $this->arr_view_data                 = [];
        $this->support_panel_slug            = config('app.project.support_panel_slug');
        $this->support_url_path              = url(config('app.project.support_panel_slug'));
        $this->module_title                  = "Account Setting";
        $this->module_view_folder            = "support.account_settings";
        $this->module_url_path               = $this->support_url_path."/profile";       
        $this->auth                          = auth()->guard('support');
        $this->SupportTeamModel              = $support_team_model;
        $this->support_id                    = isset($this->auth->user()->id)? $this->auth->user()->id:0;
        $this->profile_image_public_path     = url('/').config('app.project.img_path.support_profile_images');
        $this->profile_image_base_path       = public_path().config('app.project.img_path.support_profile_images');
    }

    public function index()
    {
        $arr_account_settings = array();
        $arr_data  = [];
        $obj_data  = $this->auth->user();

        if($obj_data)
        {
            $arr_data = $obj_data->toArray();    
        }

        $this->arr_view_data['arr_data']                      = $arr_data;
        $this->arr_view_data['page_title']                    = str_plural($this->module_title);
        $this->arr_view_data['module_title']                  = str_plural($this->module_title);
        $this->arr_view_data['module_url_path']               = $this->module_url_path;
        $this->arr_view_data['profile_image_public_path'] 	  = $this->profile_image_public_path;
        $this->arr_view_data['profile_image_base_path']   	  = $this->profile_image_base_path;
        $this->arr_view_data['support_panel_slug']            = $this->support_panel_slug;

        return view($this->module_view_folder.'.index',$this->arr_view_data);
    }

    public function update(Request $request)
    {
        $arr_rules = $arr_data = array();
        $arr_rules['user_name']          = "required";
        $arr_rules['first_name']         = "required";
        $arr_rules['last_name']          = "required";
        $arr_rules['contact']            = "required|min:7|max:16";
        $arr_rules['address']         	 = "required";
        $arr_rules['city']               = "required";
        $arr_rules['gender']             = "required";

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {       
            return redirect()->back()->withErrors($validator)->withInput();  
        }
    
        $old_image = $request->input('oldimage');

        if($request->hasFile('image'))
        {
            $file_extension = strtolower($request->file('image')->getClientOriginalExtension());
     
            if(in_array($file_extension,['png','jpg','jpeg']))
            {
                $file       = $request->file('image');
                $filename   = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
                $path       = $this->profile_image_base_path . $filename;
                $isUpload   = Image::make($file->getRealPath())->resize(269, 239)->save($path);

                if($isUpload)
                {
                    if ($old_image!="" && $old_image!=null) 
                    {
                        $profile_image = $this->profile_image_base_path.$old_image;

                        if(file_exists($profile_image))
                        {
                            unlink($profile_image);
                        }
                    }
                }                
                else
                {
                    $file_name = $old_image;
                    Flash::error('Invalid File type, While creating '.str_singular($this->module_title));
                    return redirect()->back();
                }
        	}
        }   
        else
        {
            $file_name=$old_image;
        }

        $arr_data['user_name']     = trim($request->input('user_name'));
        $arr_data['first_name']    = trim($request->input('first_name'));
        $arr_data['last_name']     = trim($request->input('last_name'));
        $arr_data['contact']       = trim($request->input('contact'));
        $arr_data['profile_image'] = isset($filename)? $filename: $file_name;
        $arr_data['gender']        = $request->input('gender');
        $arr_data['address']       = trim($request->input('address'));
        $arr_data['city']     	   = trim($request->input('city'));

        $obj_data                  = $this->SupportTeamModel->where('id',$this->support_id)->update($arr_data);
        
        if($obj_data)
        {
            Session::flash('success',str_singular($this->module_title).' Updated Successfully');
        }
        else
        {
            Session::flash('error','Problem Occurred, While Updating '.str_singular($this->module_title));
        }
        return redirect()->back();
    }


    public function change_password()
    {
        $this->arr_view_data['page_title']         = " Change Password";
        $this->arr_view_data['module_title']       = " Change Password";
        $this->arr_view_data['module_url_path']    = $this->module_url_path.'/change_password';
        $this->arr_view_data['support_panel_slug'] = $this->support_panel_slug;
        return view($this->module_view_folder.'.change_password',$this->arr_view_data);
    }

    public function update_password(Request $request)
    {     
        $arr_rules = $arr_data = array();
        $status = FALSE;
        $arr_rules['current_password'] = "required";
        $arr_rules['new_password']     = "required";
        $arr_rules['confirm_password'] = "required|same:new_password";        
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
           return redirect()->back()->withErrors($validator);
        }

        $current_password  =  $request->input('current_password');
        $new_password      =  $request->input('new_password');
        $confirm_password  =  $request->input('confirm_password');

        if(Hash::check($current_password,$this->auth->user()->password))
        {
            if($current_password!=$new_password)
            {
                if($new_password == $confirm_password)
                {
                    $user_password = Hash::make($confirm_password);
                    $status = $this->SupportTeamModel->where('id',$this->support_id)->update(['password'=>$user_password]);

                    if($status)
                    {
                        Session::flash('success','Your password changed successfully.');
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
