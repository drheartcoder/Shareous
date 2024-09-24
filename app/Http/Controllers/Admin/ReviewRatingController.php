<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\PropertyModel;
use App\Models\ReviewRatingModel;
use App\Common\Traits\MultiActionTrait;
use Validator;
use Session;
use DB;
use Image;
use Datatables;


class ReviewRatingController extends Controller
{
 	use MultiActionTrait;
    function __construct(UserModel $user_model, ReviewRatingModel $review_rating_model , PropertyModel $property_model)
	{
		$this->arr_data                       = [];
		$this->admin_panel_slug               = config('app.project.admin_panel_slug');
		$this->admin_url_path                 = url(config('app.project.admin_panel_slug'));
		$this->profile_image_public_path      = url('/').config('app.project.img_path.user_profile_images');
		$this->profile_image_base_path        = public_path().config('app.project.img_path.user_profile_images');
		$this->host_id_proof_public_path      = url('/').config('app.project.img_path.user_photo');
		$this->host_id_proof_image_base_path  = public_path().config('app.project.img_path.user_photo');
		$this->host_profile_image_public_path = url('/').config('app.project.img_path.user_id_proof');
		$this->host_profile_image_base_path   = public_path().config('app.project.img_path.user_id_proof');

		$this->module_url_path                = $this->admin_url_path."/review-rating";
		$this->module_title                   = "Review & Ratings";
		$this->module_view_folder             = "admin.review_rating";
		$this->module_icon                    = "fa fa-star-half-empty";
	
		$this->PropertyModel                  = $property_model;	
		$this->UserModel           		      = $user_model;	
		$this->ReviewRatingModel              = $review_rating_model;	
		$this->BaseModel                      = $this->ReviewRatingModel;
	}

	public function index($id = NULL)
	{
        
        $this->arr_data['page_title']       = "Manage ".$this->module_title;
        $this->arr_data['module_icon']      = $this->module_icon;
        $this->arr_data['module_title']     = $this->module_title;
        $this->arr_data['module_url_path']  = $this->module_url_path;
        $this->arr_data['admin_panel_slug'] = $this->admin_panel_slug;
        return view($this->module_view_folder.'.index',$this->arr_data);
	}

    public function load_data(Request $request)
	{
        $property_name  = $request->input('property_name');
            
		$UserData       =  $final_array =[]; 
        $column         = '';
  
        if ($request->input('order')[0]['column'] == 1) 
        {
            $column = "id";
        }           
        if ($request->input('order')[0]['column'] == 2) 
        {
            $column = "property_name";
        } 
        if ($request->input('order')[0]['column'] == 3) 
        {
            $column = "rating";
        } 
    
        $order = strtoupper($request->input('order')[0]['dir']);  

		$arr_data                 = [];
		$review_table             = $this->ReviewRatingModel->getTable();
	
		$prefixed_property_table  = DB::getTablePrefix().$this->PropertyModel->getTable();
		$obj_data                 = DB::table($review_table)
									->select(DB::raw( 
												$review_table.".*,".
												$prefixed_property_table.".property_name as property_name"
											))
									->Join($prefixed_property_table,$prefixed_property_table.".id",' = ',$review_table.'.property_id')
									->groupBy($review_table.'.property_id');
        if($property_name != '')
        {
            $obj_data = $obj_data->where($prefixed_property_table.'.property_name','LIKE', '%'.$property_name.'%'); 
        }
        
		$count        = count($obj_data->get());

        if($order =='ASC' && $column=='')
        {
          $obj_data   = $obj_data->orderBy('id','DESC')->limit($_GET['length'])->offset($_GET['start']);
        }
        if( $order !='' && $column!='' )
        {
          $obj_data   = $obj_data->orderBy($column,$order)->limit($_GET['length'])->offset($_GET['start']);
        }

        $UserData     = $obj_data->get();

        $resp['draw']            = $_GET['draw'];
        $resp['recordsTotal']    = $count;
        $resp['recordsFiltered'] = $count;
        $build_active_btn        = '' ; 

         if(count($UserData)>0)
            {
                $i = 0;

                foreach($UserData as $row)
                {
                    $obj_ratings        = $this->ReviewRatingModel->where('property_id','=',$row->property_id)->get();

                    $total          = 0;
                    $count          = 0;
                    $tmp_str_rating = ''; 
                    $count          = count($obj_ratings);

                    foreach ($obj_ratings as $rating) 
                    {
                          $total  += floatval($rating['rating']);
                    }

                    $no_reviews     = $total/$count;
                    for($j=1;$j<=$no_reviews;$j++)
                    {
                        $tmp_str_rating.='<img src="'.url('/').'/front/images/star1.png" />&nbsp;'; 
                    }

                    $whole           = floor($no_reviews);     
                    $fraction        = $no_reviews - $whole;

                    if($fraction >= 0.5)
                    {
                       $tmp_str_rating .= '<img src="'.url('/').'/front/images/half-star.png" />&nbsp;'; 
                    }
                    else if($fraction < 0.5 && $fraction > 0.0)
                    {
                      $tmp_str_rating  .= '<img src="'.url('/').'/front/images/star2.png" />&nbsp;';
                    }

                    $temp  = 5-$no_reviews;
                    
                    for($k = 1 ; $k <= $temp ; $k++)
                    { 
                        $tmp_str_rating.='<img src="'.url('/').'/front/images/star2.png" />&nbsp;';
                    }

                    $rating_stars = $tmp_str_rating;


                    if($row->status != null && $row->status == "0")
                    {
                    	$build_active_btn = '<a class="btn btn-sm btn-danger" title="Block" href="'.$this->module_url_path.'/property_review_unblock/'.base64_encode($row->property_id).'" 
					   onclick="return confirm_action(this,event,\'Do you really want to Unblock this record ?\')" >Block</a>';
                    }
                    elseif($row->status != null && $row->status == "1")
                    {
					   $build_active_btn = '<a class="btn btn-sm btn-success" title="Unblock" href="'.$this->module_url_path.'/property_review_block/'.base64_encode($row->property_id).'" onclick="return confirm_action(this,event,\'Do you really want to block this record ?\')" >Unblock</a>';
                    }

                    $build_view_action =''; 

                    $built_view_href    = $this->module_url_path.'/view/'.base64_encode($row->property_id);
			  
				    $build_view_action .= "&nbsp;<a class='btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip' href='".$built_view_href."' title='View' data-original-title='View'>
				    <i class='fa fa-eye' ></i></a>";

                    $final_array[$i][0] = "<input type='checkbox' name='checked_record[]' id='checked_record' class='checked_record' value='".base64_encode($row->property_id)."'/>";
                    $final_array[$i][1] = $row->property_name;
                    $final_array[$i][2] = $rating_stars;
                    $final_array[$i][3] = $build_active_btn;
                    $final_array[$i][4] = $build_view_action;
                    $i++;
                }
            }

            $resp['data'] = $final_array;
            echo str_replace("\/", "/",  json_encode($resp));exit;		
	}

	public function property_review_block($enc_id = FALSE)
	{
	    $obj_property_review = $this->ReviewRatingModel->where('property_id','=',base64_decode($enc_id))->get();

	    if(count($obj_property_review)>0)
        {
            foreach($obj_property_review as $row)
            {
               $update = $this->ReviewRatingModel->where('id',$row->id)->update(['status'=>'0']);
            }

            return redirect()->back()->with('success','Deactivated Successfully.');
        }
	}

	public function property_review_unblock($enc_id = FALSE)
	{
		$obj_property_review = $this->ReviewRatingModel->where('property_id','=',base64_decode($enc_id))->get();

	    if(count($obj_property_review)>0)
        {
            foreach($obj_property_review as $row)
            {
               $update = $this->ReviewRatingModel->where('id',$row->id)->update(['status'=>'1']);
            }
            return redirect()->back()->with('success','Activated Successfully.');
        }
	}

	public function property_multi_action(Request $request)
	{
		$arr_rules = array();
        $arr_rules['multi_action']   = "required";
        $arr_rules['checked_record'] = "required";

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            Session::flash('Please Select '.$this->module_title.' To Perform Multi Actions');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $multi_action   = $request->input('multi_action');
        $checked_record = $request->input('checked_record');

        /* Check if array is supplied*/
        if(is_array($checked_record) && sizeof($checked_record)<=0)
        {          
            Session::flash('error', 'Problem Occurred, While Doing Multi Action');
            return redirect()->back();
        }

        foreach ($checked_record as $key => $record_id) 
        {  
        	$obj_property_review = $this->ReviewRatingModel->where('property_id','=',base64_decode($record_id))->get();

            if($multi_action == "delete")
            {
                $delete_status = $this->PropertyModel->where('id',$id)->delete();
                if($delete_status)
                {
                   Session::flash('success', $this->module_title. '  Deleted Successfully');
                }
                else
                {
                   Session::flash('error', 'Problem Occured While '.$this->module_title.' Deletion');
                }  
            } 
            elseif($multi_action == "activate")
            {
			    if(count($obj_property_review)>0)
		        {
		            foreach($obj_property_review as $row)
		            {
		               $this->ReviewRatingModel->where('id',$row->id)->update(['status'=>'1']);
		            }
		        }

               Session::flash('success', $this->module_title. ' unblocked Successfully');
            }
            elseif($multi_action == "deactivate")
            {
            	$obj_property_review = $this->ReviewRatingModel->where('property_id','=',base64_decode($record_id))->get();

			    if(count($obj_property_review)>0)
		        {
		            foreach($obj_property_review as $row)
		            {
		               $this->ReviewRatingModel->where('id',$row->id)->update(['status'=>'0']);
		            }
		        }
 
               Session::flash('success', $this->module_title. ' blocked Successfully');
            }
        }      
        return redirect()->back();
	}

	public function view(Request $request,$enc_id = NULL)
	{
        if($request->segment(2) == 'host')
        {
            $property_id = $enc_id;
            $obj_review  = $this->ReviewRatingModel->where('property_id','=',base64_decode($property_id))->get();
            $arr_review  = $obj_review->toArray();

            $this->arr_data['manage_review_rating'] =  $this->admin_url_path.'/host/review-rating/'.base64_encode($arr_review[0]['rating_user_id']); 
            $this->arr_data['manage_host'] = $this->admin_url_path.'/host';
        }
    
        $this->arr_data['page_title']                 = str_singular("View ".$this->module_title);
        $this->arr_data['module_icon']                = $this->module_icon;
        $this->arr_data['module_title']               = $this->module_title;
        $this->arr_data['page_icon']                  = 'fa-view';
        $this->arr_data['module_url_path']            = $this->module_url_path;
        $this->arr_data['admin_panel_slug']           = $this->admin_panel_slug;
       
        return view($this->module_view_folder.'.individual-rating',$this->arr_data);
	}

	public function load_individual_rating(Request $request)
	{
		$UserData      =  $final_array=[]; 
        $column        = '';

        $property_id   = $request->input('property_id'); 
      
        if ($request->input('order')[0]['column'] == 1) 
        {
            $column = "id";
        }      
        if ($request->input('order')[0]['column'] == 1) 
        {
            $column = "first_name";
        }   
        if ($request->input('order')[0]['column'] == 1) 
        {
            $column = "property_name";
        }        
        if ($request->input('order')[0]['column'] == 2) 
        {
            $column = "message";
        } 
        if ($request->input('order')[0]['column'] == 3) 
        {
            $column = "rating";
        } 
      
        $order = strtoupper($request->input('order')[0]['dir']);  

		$arr_data                 = [];
		$review_table             = $this->ReviewRatingModel->getTable();
		$prefixed_users_table     = DB::getTablePrefix().$this->UserModel->getTable();
		$prefixed_property_table  = DB::getTablePrefix().$this->PropertyModel->getTable();

		$obj_data                 = DB::table($review_table)
									->select(DB::raw( 
												$review_table.".*,".
										        $prefixed_users_table.".first_name as first_name,".
												$prefixed_users_table.".last_name as last_name,".
												$prefixed_property_table.".property_name as property_name"
											))
									->where($review_table.'.property_id',base64_decode($property_id))
									->Join($prefixed_users_table,$prefixed_users_table.".id",' = ',$review_table.'.rating_user_id')
									->Join($prefixed_property_table,$prefixed_property_table.".id",' = ',$review_table.'.property_id')
									->orderBy($review_table.'.id','DESC');

		$count        = count($obj_data->get());

        if($order =='ASC' && $column=='')
        {
          $obj_data   = $obj_data->orderBy('id','DESC')->limit($_GET['length'])->offset($_GET['start']);
        }
        if( $order !='' && $column!='' )
        {
          $obj_data   = $obj_data->orderBy($column,$order)->limit($_GET['length'])->offset($_GET['start']);
        }

        $UserData     = $obj_data->get();

        $resp['draw']            = $_GET['draw'];
        $resp['recordsTotal']    = $count;
        $resp['recordsFiltered'] = $count;
        $build_active_btn        = '' ; 

         if(count($UserData) > 0)
            {
                $i = 0;

                foreach($UserData as $row)
                {
                    //start

                    $obj_ratings    = $this->ReviewRatingModel->where('property_id','=',$row->property_id)->get();

                    $arr_ratings    = $obj_ratings->toArray();
                    
                    $total          = 0;
                    $count          = 0;
                    $tmp_str_rating = ''; 
                    $count          = count($arr_ratings);

                    $no_reviews     = $arr_ratings[0]['rating'];

                    for($j=1;$j<=$no_reviews;$j++)
                    {
                        $tmp_str_rating.='<img src="'.url('/').'/front/images/star1.jpg" />&nbsp;'; 
                    }

                    $whole           = floor($no_reviews);     
                    $fraction        = $no_reviews - $whole;

                    if($fraction >= 0.5)
                    {
                       $tmp_str_rating  .= '<img src="'.url('/').'/front/images/half-star.jpg" />&nbsp;'; 
                    }
                    else if($fraction < 0.5 && $fraction > 0.0)
                    {
                      $tmp_str_rating   .= '<img src="'.url('/').'/front/images/star2.jpg" />&nbsp;';
                    }

                    $temp  = 5-$no_reviews;
                    
                    for($k = 1 ; $k <= $temp ; $k++)
                    { 
                        $tmp_str_rating .='<img src="'.url('/').'/front/images/star2.jpg" />&nbsp;';
                    }
                    
                    $rating_stars        = $tmp_str_rating;

                    //end

                    if($row->status != null && $row->status == "0")
                    {
                       $build_active_btn = '<a class="btn btn-sm btn-danger" title="Block" href="'.$this->module_url_path.'/unblock/'.base64_encode($row->id).'" 
					   onclick="return confirm_action(this,event,\'Do you really want to Unblock this record ?\')" >Block</a>';
                    }
                    elseif($row->status != null && $row->status == "1")
                    {
					   $build_active_btn = '<a class="btn btn-sm btn-success" title="Unblock" href="'.$this->module_url_path.'/block/'.base64_encode($row->id).'" onclick="return confirm_action(this,event,\'Do you really want to block this record ?\')" >Unblock</a>';
                    }

                    $build_view_action   = ''; 
                    $built_view_href     = $this->module_url_path.'/delete/'.base64_encode($row->id);
			  
                    $build_view_action   = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Delete" href="'.$built_view_href.'" onclick="return confirm_action(this,event,\'Do you really want to block this record ?\')" ><i class="fa fa-trash" ></i></a>';
              

                    $final_array[$i][0]  = "<input type='checkbox' name='checked_record[]' id='checked_record' class='checked_record' value='".base64_encode($row->id)."'/>";

                    $message       = ''.wordwrap($row->message,60,"<br>\n").'';
                    $property_name = ''.wordwrap($row->property_name,50,"<br>\n").'';

                    $final_array[$i][1] = $row->first_name."&nbsp;".$row->last_name;
                    $final_array[$i][2] = $property_name;
                    $final_array[$i][3] = $message;
                    $final_array[$i][4] = $rating_stars;
                    $final_array[$i][5] = $build_active_btn;
                    $final_array[$i][6] = $build_view_action;
                    $i++;
                }
            }

            $resp['data'] = $final_array;
            echo str_replace("\/", "/",  json_encode($resp));exit;		
	}


}
