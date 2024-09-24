<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Common\Services\ListingService;
use App\Common\Services\PropertyDatesService;
use App\Common\Services\NotificationService;
use App\Common\Services\SMSService;
use App\Common\Services\EmailService;
use App\Common\Services\MobileAppNotification;

use App\Models\UserModel;
use App\Models\HostVerificationModel;
use App\Models\ReviewRatingModel;
use App\Models\PropertyModel;
use App\Models\SupportQueryModel;
use App\Models\TransactionModel;
use App\Models\BookingModel;
use App\Models\PropertyUnavailabilityModel;
use App\Models\QueryTypeModel;
use App\Models\SupportQueryCommentModel;
use App\Models\SupportTeamModel;
use App\Models\FavouritePropertyModel;
use App\Models\AdminModel;

use Validator;
use TCPDF;
use Image;
use PDF;
use DB;

class GuestController extends Controller
{
    public function __construct(
                                UserModel                   $user_model,
                                HostVerificationModel       $host_verification_model,
                                TransactionModel            $transaction_model,
                                ListingService              $listing_service,
                                ReviewRatingModel           $review_rating_model,
                                PropertyModel               $property_model,
                                PropertyDatesService        $property_date_service,
                                BookingModel                $booking_model,
                                PropertyUnavailabilityModel $property_unavailability_model,
                                QueryTypeModel              $query_type_model,
                                NotificationService         $notification_service,
                                SMSService                  $sms_service,
                                SupportQueryCommentModel    $support_query_comment_model,
                                SupportTeamModel            $support_team_model,
                                EmailService                $email_service,
                                MobileAppNotification       $mobileappnotification_service,
                                SupportQueryModel           $support_query_model,
                                FavouritePropertyModel      $FavouritePropertyModel
                            )
    {
        $this->UserModel                     = $user_model;
        $this->HostVerificationModel         = $host_verification_model;
        $this->ReviewRatingModel             = $review_rating_model;
        $this->PropertyModel                 = $property_model;
        $this->SupportQueryModel             = $support_query_model;
        $this->TransactionModel              = $transaction_model;
        $this->BookingModel                  = $booking_model;
        $this->PropertyUnavailabilityModel   = $property_unavailability_model;
        $this->QueryTypeModel                = $query_type_model;
        $this->SupportQueryCommentModel      = $support_query_comment_model;
        $this->SupportTeamModel              = $support_team_model;
        $this->FavouritePropertyModel        = $FavouritePropertyModel;
        
        $this->ListingService                = $listing_service;
        $this->PropertyDatesService          = $property_date_service;
        $this->NotificationService           = $notification_service;
        $this->SMSService                    = $sms_service;
        $this->EmailService                  = $email_service;
        $this->MobileAppNotification         = $mobileappnotification_service;
        
        $this->user_id                       = validate_user_jwt_token();
        $this->TCPDF                         = new TCPDF();
        
        $this->id_proof_base_path            = public_path().config('app.project.img_path.user_id_proof');
        $this->photo_base_path               = public_path().config('app.project.img_path.user_photo');
        $this->property_image_public_path    = url('/').config('app.project.img_path.property_image');
        $this->property_image_base_path      = base_path().config('app.project.img_path.property_image');
        $this->profile_image_public_img_path = url('/').config('app.project.img_path.user_profile_images');
        $this->profile_image_base_img_path   = public_path().config('app.project.img_path.user_profile_images');
        $this->query_image_public_path       = url('/').config('app.project.img_path.query_image');
        $this->query_image_base_path         = public_path().config('app.project.img_path.query_image');
        $this->invoice_path_public_path      = url('/').config('app.project.img_path.invoice_path');
    }

    public function become_host(Request $request)
    {
        $isUpload_id_proof = $isUpload_photo = false;
        $arr_data = $arr_rules = $obj_user = [];

        $user_id  = $this->user_id ;
        $obj_user = $this->UserModel->where('id',$user_id)->first();

        if (isset($user_id) && $user_id != '') {
            $arr_rules['id_proof'] = "required";
            $arr_rules['photo']    = "required";

            $validator = validator::make($request->all(),$arr_rules);
            if ($validator->fails()) {
                $status  = 'error';
                $message = 'fill all required fields';
            }
            else {
                $id_proof = $request->file('id_proof', null);
                $photo    = $request->file('photo', null);

                if($id_proof != null && $photo != null) {
                    $id_proof_name          = sha1(uniqid().uniqid()) . '.' . $id_proof->getClientOriginalExtension();
                    $path                   = $this->id_proof_base_path;
                    $id_proof_original_name = $request->file('id_proof')->getClientOriginalName();
                    $isUpload_id_proof      = $request->file('id_proof')->move($path , $id_proof_name);

                    $photo_name             = sha1(uniqid().uniqid()) . '.' . $photo->getClientOriginalExtension();
                    $path                   = $this->photo_base_path;
                    $photo_original_name    = $request->file('photo')->getClientOriginalName();
                    $isUpload_photo         = $request->file('photo')->move($path , $photo_name);
                }

                $dose_exist = $this->HostVerificationModel->where(['user_id'=> $obj_user->id, 'status'=>'0'])->count();
                if($dose_exist>0) {
                    $status  = 'error';
                    $message = 'Your request is alredy in process please wait for verification process.';
                }
                else {
                    if($isUpload_id_proof && $isUpload_photo) {
                        $arr_data['user_id']       = $obj_user->id;
                        $arr_data['request_id']    = get_request_id();
                        $arr_data['id_proof']      = $id_proof_name;
                        $arr_data['id_proof_name'] = $id_proof_original_name;
                        $arr_data['photo']         = $photo_name;
                        $arr_data['photo_name']    = $photo_original_name;
                        $arr_data['status']        = '3';
                        $user_request              = $this->HostVerificationModel->create($arr_data);

                        $status  = 'success';
                        $message = 'Your request for verification is accepted please wait until verification process';
                    }
                    else {
                        $status  = 'success';
                        $message = 'Error while uploading your document for verification.';
                    }
                }
            }
        } else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }
        return $this->build_response($status,$message);
    }


    public function get_host_process(Request $request)
    {
        $arr_data = $arr_rules = $obj_user = $user_detail = $status = $message = [];
        $user_id = $this->user_id;

        if (isset($user_id) && $user_id != '') {
            $obj_user = $this->UserModel->where('id',$user_id)->select('id', 'user_type')->first();
            if($obj_user) {
                $arr_user = $obj_user->toArray();
                $user_detail['user_type'] = $arr_user['user_type'];
            }

            $obj_process_host = $this->HostVerificationModel->where(['user_id' => $user_id])->where('status', 3)->count();
            if ($obj_process_host > 0) {
                $user_detail['in_process_host'] = "yes";
            }
            else {
                $user_detail['in_process_host'] = "no";
            }
        }
        return $this->build_response($status, $message, $user_detail);
    }

    public function my_favourite_listing(Request $request)
    {
        $user_id = $this->user_id;
        $status = $message = $fav_details = $arr_property = $arr_fav = [];

        if(isset($user_id) && $user_id != '') {
            $user_currency = $request->input('user_currency', 'INR');
            $arr_property = $this->ListingService->get_user_favorite_list($user_id);
            
            if(isset($arr_property) && count($arr_property) >0) {
                $obj_paginate                 = $arr_property['arr_pagination'];
                $arr_pagination               = $obj_paginate->toArray();

                $fav_details['total']         = $arr_pagination['total'];
                $fav_details['per_page']      = $arr_pagination['per_page'];
                $fav_details['current_page']  = $arr_pagination['current_page'];
                $fav_details['last_page']     = $arr_pagination['last_page'];
                $fav_details['next_page_url'] = $arr_pagination['next_page_url'];
                $fav_details['prev_page_url'] = $arr_pagination['prev_page_url'];
                $fav_details['from']          = $arr_pagination['from'];
                $fav_details['to']            = $arr_pagination['to'];
                $arr_property_data            = $arr_property['property_list'];
               
                if (isset($arr_property_data) && count($arr_property_data)>0) {
                    $status  = 'success';
                    $message = 'Records get successfully';

                    foreach ($arr_property_data as $key => $value) {
                        $property_type_id   = isset($value['property_type_id']) ? $value['property_type_id'] : 0;
                        $property_type_slug = get_property_type_slug($property_type_id);
                        $converted_price    = $price = 0;

                        if($property_type_slug == 'warehouse') {
                            if(isset($value['currency_code']) && isset($value['price_per_sqft'])) {
                                $converted_price = currencyConverterAPI($value['currency_code'], $user_currency, $value['price_per_sqft']);
                                $price = $value['price_per_sqft'];
                            }
                        }
                        else if($property_type_slug == 'office-space') {
                            if(isset($value['currency_code']) && isset($value['price_per_office'])) {
                                $converted_price = currencyConverterAPI($value['currency_code'],$user_currency,$value['price_per_office']);
                                $price = $value['price_per_office'];
                            }
                        }
                        else {
                            if(isset($value['currency_code']) && isset($value['price_per_night'])) {
                                $converted_price = currencyConverterAPI($value['currency_code'], $user_currency, $value['price_per_night']);
                                $price = $value['price_per_night'];
                            }
                        }   

                        $property_full_name = isset($value['property_name']) ? $value['property_name'] : '';
                        $property_type_name = ucfirst(str_replace('-', ' ', $property_type_slug));
                        $property_full_name = $property_full_name.' - '.$property_type_name;

                        $arr_fav[$key]['property_id']         = $value['property_id'];
                        $arr_fav[$key]['property_name']       = $property_full_name;
                        $arr_fav[$key]['property_image']      = $this->property_image_public_path.$value['property_image'];

                        $arr_fav[$key]['number_of_guest']     = $value['number_of_guest'];
                        $arr_fav[$key]['number_of_bedrooms']  = $value['number_of_bedrooms'];
                        $arr_fav[$key]['number_of_bathrooms'] = $value['number_of_bathrooms'];
                        $arr_fav[$key]['number_of_beds']      = $value['number_of_beds'];
                        $arr_fav[$key]['price_per_night']     = $price;
                        $arr_fav[$key]['property_name_slug']  = $value['property_name_slug'];
                        $arr_fav[$key]['currency']            = $value['currency'];
                        $arr_fav[$key]['property_type_slug']  = $property_type_slug;
                        $arr_fav[$key]['price_per']           = isset($value['price_per']) ? $value['price_per'] :'';

                        $arr_fav[$key]['converted_price']     = number_format($converted_price,2);
                        $user_currency_data                   = get_currency_detail($user_currency);
                        $arr_fav[$key]['user_currency']       = $user_currency_data['currency'];
                        $arr_fav[$key]['currency']            = $user_currency_data['currency'];

                        $arr_property_review = $this->ReviewRatingModel->where('status', '1')->where('property_id',$value['property_id'])->get()->toArray();

                        $arr_fav[$key]['number_of_guest']     = $value['number_of_guest'];
                        $arr_fav[$key]['number_of_bedrooms']  = $value['number_of_bedrooms'];
                        $arr_fav[$key]['number_of_bathrooms'] = $value['number_of_bathrooms'];
                        $arr_fav[$key]['number_of_beds']      = $value['number_of_beds'];

                        $arr_fav[$key]['property_area']       = $value['property_area'];
                        $arr_fav[$key]['total_plot_area']     = $value['total_plot_area'];
                        $arr_fav[$key]['total_build_area']    = $value['total_build_area'];
                        $arr_fav[$key]['admin_area']          = $value['admin_area'];
                        $arr_fav[$key]['price_per']           = isset($value['price_per']) ? $value['price_per'] :'';

                        $arr_fav[$key]['price_per_night']     = $price;
                        $arr_fav[$key]['property_name_slug']  = $value['property_name_slug'];
                        $arr_fav[$key]['property_type_slug']  = $property_type_slug;
                        $arr_fav[$key]['currency']            = $value['currency'];

                        $arr_fav[$key]['employee']            = $value['employee'];
                        $arr_fav[$key]['room']                = $value['room'];
                        $arr_fav[$key]['desk']                = $value['desk'];
                        $arr_fav[$key]['cubicles']            = $value['cubicles'];
                        $arr_fav[$key]['no_of_employee']      = $value['no_of_employee'];
                        $arr_fav[$key]['no_of_room']          = $value['no_of_room'];
                        $arr_fav[$key]['no_of_desk']          = $value['no_of_desk'];
                        $arr_fav[$key]['no_of_cubicles']      = $value['no_of_cubicles'];
                        $arr_fav[$key]['room_price']          = $value['room_price'];
                        $arr_fav[$key]['desk_price']          = $value['desk_price'];
                        $arr_fav[$key]['cubicles_price']      = $value['cubicles_price'];

                        $arr_fav[$key]['converted_price']     = number_format($converted_price,2);
                        $user_currency_data                   = get_currency_detail($user_currency);
                        $arr_fav[$key]['user_currency']       = $user_currency_data['currency'];
                        $arr_fav[$key]['currency']            = $user_currency_data['currency'];

                        $arr_property_review = $this->ReviewRatingModel->where('status', '1')->where('property_id',$value['property_id'])->get()->toArray();

                        $total = $count = 0;
                        $tmp_str_rating = '';
                             
                        if(isset($arr_property_review)) {
                            foreach($arr_property_review as $rating) {
                                if($rating['property_id'] == $value['property_id']) {
                                    $total += floatval($rating['rating']);
                                    $count++;
                                }
                            }
                        }

                        if($count != 0) {
                            $reviews = number_format(($total/$count),1);
                        }
                        else {
                            $reviews = 0;
                        }
                        $arr_fav[$key]['no_of_reviews']  = $count;
                        $arr_fav[$key]['average_rating'] = $reviews;
                    }
                }
                else {
                    $status  = 'error';
                    $message = 'No record found';
                }
                $fav_details['fev_details'] = $arr_fav;
            }
            else {
                $status  = 'error';
                $message = 'No record found';
            }
        }
        else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }
        return $this->build_response($status,$message,$fav_details);
    }

    public function ticket_listing(Request $request)
    {
        $status = $message = $arr_query = $json_data = $ticket_data = [];

        $chat_enable = 0;

        $user_id = $this->user_id;
        if(isset($user_id) && $user_id !='') {
            $user_type = $request->input('user_type');
            $where_cnd = array('user_type' => $user_type , 'user_id' => $user_id);
            $obj_query = $this->SupportQueryModel->with('query_type_details','query_chat')->where($where_cnd)->orderBy('id', 'DESC')->paginate(10);
            if(isset($obj_query) && count($obj_query)>0) {
                $status     = 'success';
                $message    = 'Records get successfully.';
                $arr_ticket = $obj_query->toArray();

                $json_data['total']         = $arr_ticket['total'];
                $json_data['per_page']      = $arr_ticket['per_page'];
                $json_data['current_page']  = $arr_ticket['current_page'];
                $json_data['last_page']     = $arr_ticket['last_page'];
                $json_data['next_page_url'] = $arr_ticket['next_page_url'];
                $json_data['prev_page_url'] = $arr_ticket['prev_page_url'];
                $json_data['from']          = $arr_ticket['from'];
                $json_data['to']            = $arr_ticket['to'];

                foreach ($arr_ticket['data'] as $key => $value) {
                    
                    if( $value['query_chat'] != null) {
                        $chat_enable = 1;
                        $support_user_id = $value['query_chat']['support_user_id'];
                    } else if( $value['query_chat'] == null) {
                        $chat_enable = 0;
                        $support_user_id = 0;
                    }

                    $ticket_data[$key]['id']                = $value['id'];
                    $ticket_data[$key]['query_subject']     = $value['query_subject'];
                    $ticket_data[$key]['query_description'] = $value['query_description'];
                    $ticket_data[$key]['created_at']        = isset($value['created_at']) ? $value['created_at'] : '';
                    $ticket_data[$key]['chat_enable']       = $chat_enable;
                    $ticket_data[$key]['support_user_id']   = $support_user_id;
                    
                    if ($value['attachment_file'] != '') {
                        $ticket_data[$key]['attachment_file'] = $this->query_image_public_path.$value['attachment_file'];
                    } else {
                        $ticket_data[$key]['attachment_file'] = url('/').'/front/images/Listing-page-no-image.jpg';
                    }
                    $ticket_data[$key]['query_type'] = $value['query_type_details']['query_type'];
                }
                $json_data['ticket_data'] = $ticket_data;

            } else {
                $status  = 'error';
                $message = 'No record found';
            }

        } else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }
         return $this->build_response($status,$message,$json_data);          
    }

    public function my_review_ratings(Request $request)
    {
        $status = $message = $json_data = $arr_property_listing =$arr_property_review = $review_data = $arr_pagination = $arr_review_rating = $arr_property_review = [];

        $user_id = $this->user_id ;
        if(isset($user_id) && $user_id !='') {
            $user_type = $request->input('user_type');
            
            if($user_type == '1') {   
                $obj_property_review = $this->ReviewRatingModel
                                            ->select('review_rating.*',
                                                    'property.property_name_slug',
                                                    'property.property_name',
                                                    'property_images.image')
                                            ->join('property','property.id','=','review_rating.property_id')
                                            ->join('property_images','property_images.property_id','=','review_rating.property_id')
                                            ->groupBy('review_rating.id','property_images.property_id')
                                            ->orderBy('review_rating.id','DESC')
                                            ->where('status','1')
                                            ->where('rating_user_id',$this->user_id)
                                            ->paginate(5);

                if(isset($obj_property_review) && count($obj_property_review)>0) {
                    $status  = 'success';
                    $message = 'Records get successfully.';

                    $obj_pagination = $obj_property_review;
                    $arr_pagination = $obj_pagination->toArray();

                    $json_data['total']         = $arr_pagination['total'];
                    $json_data['per_page']      = $arr_pagination['per_page'];
                    $json_data['current_page']  = $arr_pagination['current_page'];
                    $json_data['last_page']     = $arr_pagination['last_page'];
                    $json_data['next_page_url'] = $arr_pagination['next_page_url'];
                    $json_data['prev_page_url'] = $arr_pagination['prev_page_url'];
                    $json_data['from']          = $arr_pagination['from'];
                    $json_data['to']            = $arr_pagination['to'];

                    foreach ($arr_pagination['data'] as $key => $value) {
                        $review_data[$key]['id']             = $value['id'];
                        $review_data[$key]['booking_id']     = $value['booking_id'];
                        $review_data[$key]['property_id']    = $value['property_id'];
                        $review_data[$key]['property_name']  = $value['property_name'];
                        $review_data[$key]['rating']         = $value['rating'];
                        $review_data[$key]['message']        = $value['message'];
                        $review_data[$key]['date']           = date('d M Y',strtotime($value['created_at']));
                        $review_data[$key]['property_image'] = $this->property_image_public_path.$value['image'];
                    }
                    $json_data['review_data'] = $review_data;
                }
                else {
                    $status  = 'error';
                    $message = 'No record found';
                }
            } elseif($user_type == '4') {
                $obj_property_review = $this->ReviewRatingModel
                                            ->select('review_rating.*',
                                                    'property.property_name_slug',
                                                    'property.property_name',
                                                    'property_images.image')
                                            ->join('property','property.id','=','review_rating.property_id')
                                            ->join('property_images','property_images.property_id','=','review_rating.property_id')
                                            ->groupBy('review_rating.id','property_images.property_id')
                                            ->orderBy('review_rating.id','DESC')
                                            ->where('status','1')
                                            ->where('property.user_id',$this->user_id)
                                            ->paginate(5);
                
                if(isset($obj_property_review) && count($obj_property_review) > 0) {
                    $status  = 'success';
                    $message = 'Records get successfully.';

                    $obj_pagination = $obj_property_review;
                    $arr_pagination = $obj_pagination->toArray();

                    $json_data['total']         = $arr_pagination['total'];
                    $json_data['per_page']      = $arr_pagination['per_page'];
                    $json_data['current_page']  = $arr_pagination['current_page'];
                    $json_data['last_page']     = $arr_pagination['last_page'];
                    $json_data['next_page_url'] = $arr_pagination['next_page_url'];
                    $json_data['prev_page_url'] = $arr_pagination['prev_page_url'];
                    $json_data['from']          = $arr_pagination['from'];
                    $json_data['to']            = $arr_pagination['to'];
                   
                    foreach ($arr_pagination['data'] as $key => $property) {
                        $property_review_data[$key]['id']             = $property['id'];
                        $property_review_data[$key]['booking_id']     = $property['booking_id'];
                        $property_review_data[$key]['property_id']    = $property['property_id'];
                        $property_review_data[$key]['property_name']  = $property['property_name'];
                        $property_review_data[$key]['rating']         = $property['rating'];
                        $property_review_data[$key]['message']        = $property['message'];
                        $property_review_data[$key]['date']           = date('d M Y',strtotime($property['created_at']));
                        $property_review_data[$key]['property_image'] = $this->property_image_public_path.$property['image'];
                    }
                    $json_data['review_data'] = $property_review_data;
                } else {
                    $status  = 'error';
                    $message = 'No record found';
                }
            }
        } else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }
        return $this->build_response($status,$message,$json_data);   
    }

    public function view_review(Request $request)
    {
        $status = $message = $json_data = $review_data = $arr_pagination = $arr_review_rating = $arr_property_review = [];

        $user_id = $this->user_id;
        if(isset($user_id) && $user_id != '') {
            $user_type   = $request->input('user_type');
            $property_id = $request->input('property_id');
          
            $review_table          = $this->ReviewRatingModel->getTable();
            $user_table            = $this->UserModel->getTable();
            $prefixed_review_table = DB::getTablePrefix().$this->ReviewRatingModel->getTable();
            $prefixed_user_table   = DB::getTablePrefix().$this->UserModel->getTable();

            $obj_review_rating = DB::table($prefixed_review_table)
                                    ->select($prefixed_review_table.".*",
                                            $prefixed_user_table.".first_name",
                                            $prefixed_user_table.".last_name")
                                    ->Join($prefixed_user_table,$prefixed_user_table.".id",' = ',$review_table.'.rating_user_id')
                                    ->where($prefixed_review_table.'.status','1')
                                    ->where($prefixed_review_table.'.property_id',$property_id)
                                    ->get();

            if(isset($obj_review_rating) && count($obj_review_rating)>0) {
                $status  = 'success';
                $message = 'Records get successfully.';

                foreach($obj_review_rating as $rating) {
                    $json_data['rating']     = $rating->rating;
                    $json_data['message']    = $rating->message;
                    $json_data['first_name'] = $rating->first_name;
                    $json_data['last_name']  = $rating->last_name;
                    $json_data['created_at'] = date('h:i A - M d, Y',strtotime($rating->created_at));
                }
            } else {
                $status  = 'error';
                $message = 'No record found';
            }
        } else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }
        return $this->build_response($status,$message,$json_data);
    }

    public function transaction_listing(Request $request)
    {
        $status = $message = $json_data = $review_data = $transaction_data = $arr_pagination = $arr_review_rating = $arr_property_review = [];

        $user_id = $this->user_id;

        if(isset($user_id) && $user_id !='') {
            $user_type = $request->input('user_type');
            $date      = $request->input('search_date');
            $keyword   = trim($request->input('keyword'));

            $obj_transaction = $this->TransactionModel
                                    ->with([
                                        'booking_details' => function($q_b) {
                                            $q_b->select('id', 'property_id', 'check_in_date', 'check_out_date', 'created_at');
                                        },
                                        'booking_details.property_details' => function($q_p) {
                                            $q_p->select('id', 'currency', 'currency_code');
                                        }
                                    ])
                                    ->where('user_id', $user_id)
                                    ->where('user_type', $user_type)
                                    ->orderBy('transaction.id', 'DESC');

            if ($user_type =='1') {
                $payment_type = ['booking','wallet'];
                $obj_transaction = $obj_transaction->whereIn('transaction.payment_type',$payment_type);
            } elseif($user_type == '4') {
                $obj_transaction = $obj_transaction->where('transaction.payment_type',"booking");
            }

            if ($date != '') {
                $search_date     = date('Y-m-d',strtotime($date));
                $obj_transaction = $obj_transaction->where('transaction_date','LIKE', '%'.$search_date.'%');
            }

            if ($keyword != '') {
                $obj_transaction = $obj_transaction->whereRaw("transaction_id LIKE '%".$keyword."%' OR transaction_for LIKE '%".$keyword."%'");
            }

            $obj_transaction = $obj_transaction->paginate(10); 
            if(isset($obj_transaction) && count($obj_transaction)>0) {
                $status  = 'success';
                $message = 'Records get successfully.';

                $arr_transaction            = $obj_transaction->toArray();
                $json_data['total']         = $arr_transaction['total'];
                $json_data['per_page']      = $arr_transaction['per_page'];
                $json_data['current_page']  = $arr_transaction['current_page'];
                $json_data['last_page']     = $arr_transaction['last_page'];
                $json_data['next_page_url'] = $arr_transaction['next_page_url'];
                $json_data['prev_page_url'] = $arr_transaction['prev_page_url'];
                $json_data['from']          = $arr_transaction['from'];
                $json_data['to']            = $arr_transaction['to'];

                foreach($arr_transaction['data'] as $key => $transaction) {
                    $booking_details  = $transaction['booking_details'];
                    $property_details = $booking_details['property_details'];

                    $transaction_data[$key]['id']             = $transaction['id'];
                    $transaction_data[$key]['transaction_id'] = $transaction['transaction_id'];
                    
                    if($transaction['payment_type'] == 'booking') {
                        $transaction_data[$key]['currency']      = $property_details['currency'];
                        $transaction_data[$key]['currency_code'] = $property_details['currency_code'];
                    } else {
                        $transaction_data[$key]['currency']      = "₹";
                        $transaction_data[$key]['currency_code'] = "INR";
                    }
                    
                    $transaction_data[$key]['amount']           = $transaction['amount'];
                    $transaction_data[$key]['transaction_for']  = $transaction['transaction_for'];
                    $transaction_data[$key]['payment_type']     = $transaction['payment_type'];
                    $transaction_data[$key]['transaction_date'] = date("d-M-Y", strtotime($transaction['transaction_date']));
                    $transaction_data[$key]['invoice']          = $this->invoice_path_public_path.$transaction['invoice'];
                    $transaction_data[$key]['check_in_date']    = (isset($booking_details) ? date("d-M-Y", strtotime($booking_details['check_in_date'])) : '');
                    $transaction_data[$key]['check_out_date']   = (isset($booking_details) ? date("d-M-Y", strtotime($booking_details['check_out_date'])) : '');
                    $transaction_data[$key]['booking_date']     = (isset($booking_details) ? date("d-M-Y", strtotime($booking_details['created_at'])) : '');
                }
                $json_data['transaction_data'] = $transaction_data;
            } else {
                $status  = 'error';
                $message = 'No record found';
            }
        } else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }
        return $this->build_response($status,$message,$json_data);   
    }

    public function transaction_details(Request $request)
    {
        $status = $message = $json_data = $arr_transaction = $transaction_data = $arr_pagination = $arr_review_rating = $arr_property_review = [];

        $user_id = $this->user_id ;
        if(isset($user_id) && $user_id !='') {
            $id = $request->input('id');

            $transaction_table          = $this->TransactionModel->getTable();
            $booking_table              = $this->BookingModel->getTable();
            $property_table             = $this->PropertyModel->getTable();
            $prefixed_transaction_table = \DB::getTablePrefix().$this->TransactionModel->getTable();
            $prefixed_booking_table     = \DB::getTablePrefix().$this->BookingModel->getTable();
            $prefixed_property_table    = \DB::getTablePrefix().$this->PropertyModel->getTable();

            $obj_transaction            = \DB::table($prefixed_transaction_table)
                                            ->select(
                                                $prefixed_transaction_table.".transaction_id",
                                                $prefixed_transaction_table.".transaction_date",
                                                $prefixed_transaction_table.".amount",
                                                $prefixed_transaction_table.".payment_type",
                                                $prefixed_transaction_table.".transaction_for",
                                                $prefixed_booking_table.".check_in_date",
                                                $prefixed_booking_table.".check_out_date", 
                                                $prefixed_booking_table.".created_at",
                                                $prefixed_property_table.".currency",
                                                $prefixed_property_table.".currency_code")
                                            ->LeftJoin($prefixed_booking_table,$prefixed_booking_table.".id",' = ',$prefixed_transaction_table.'.booking_id')
                                            ->LeftJoin($prefixed_property_table,$prefixed_property_table.".id",' = ',$prefixed_booking_table.'.property_id')
                                            ->where($prefixed_transaction_table.'.id',$id)
                                            ->get();
      
            if(isset($obj_transaction) && count($obj_transaction)>0) {
                $status  = 'success';
                $message = 'Records get successfully.';

                foreach ($obj_transaction as $key => $value) {
                    $transaction_data[$key]['transaction_id']   = $value->transaction_id;
                    $transaction_data[$key]['payment_type']     = $value->payment_type;
                    $transaction_data[$key]['transaction_for']  = $value->transaction_for;
                    $transaction_data[$key]['transaction_date'] = get_added_on_date($value->transaction_date);

                    if(isset($value->payment_type) && $value->payment_type == 'booking') {
                        $transaction_data[$key]['check_in_date']  = get_added_on_date($value->check_in_date);
                        $transaction_data[$key]['check_out_date'] = get_added_on_date($value->check_out_date);
                        $transaction_data[$key]['created_at']     = get_added_on_date($value->created_at);

                        if(isset($value->currency_code) && $value->currency_code == 'INR') {
                            $amount = isset($value->amount) ? number_format($value->amount,'2','.','' ) : '';
                        }
                        else {
                            $amount = isset($value->amount) ? number_format(currencyConverter($value->currency_code, 'INR', $value->amount),'2','.','' ) : '';
                        }
                        $total_amount = $value->currency.$amount;
                    } else {
                        $amount       = isset($transaction->amount) ? $transaction->amount : '';
                        $total_amount = "₹".$amount;
                    }
                    $transaction_data[$key]['amount'] = $total_amount;
                }
                $json_data['transaction_data'] = $transaction_data;
            } else {
                $status  = 'error';
                $message = 'No record found';
            }
        } else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }
        return $this->build_response($status,$message,$json_data);   
    }

    public function get_query_type(Request $request)
    {
        $json_data = $arr_query_type = $query_type_data = [];

        $user_id = $this->user_id;
        if(isset($user_id) && $user_id != '') {
            $obj_query_type = $this->QueryTypeModel->where('status',1)->get();

            if(isset($obj_query_type) && $obj_query_type != null) {
                $status  = 'success';
                $message = 'Records get successfully.';
                $arr_query_type = $obj_query_type->toArray();

                foreach ($arr_query_type as $key => $value) {
                    $query_type_data[$key]['id'] = $value['id'];
                    $query_type_data[$key]['query_type'] = $value['query_type'];
                }
                $json_data['query_type_data'] = $query_type_data;
            } else {
                $status  = 'error';
                $message = 'No record found';
            }
        } else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }
        return $this->build_response($status,$message,$json_data);  
    }

    public function generate_ticket(Request $request)
    {
        $json_data = $arr_query_type = $query_type_data = [];
        $res = '';

        $user_id = $this->user_id ;
        if(isset($user_id) && $user_id != '') {
            $arr_rules = [];
            $user_type = $request->input('user_type');
            
            $arr_rules['query_type_id']        = "required";
            $arr_rules['query_subject']        = "required";
            $arr_rules['query_description']    = "required";
            $arr_rules['attachment_file_name'] = "required";

            $validator = Validator::make($request->all(),$arr_rules);
            if($validator->fails()) {
                $status  = 'error';
                $message = 'fill all required fields';    
            }
            else {
                $arr_data['query_type_id']        = trim($request->input('query_type_id'));;
                $arr_data['query_subject']        = trim($request->input('query_subject'));;
                $arr_data['query_description']    = trim($request->input('query_description'));;
                $arr_data['attachment_file_name'] = trim($request->input('attachment_file_name'));;

                if($request->hasFile('attachment_file_name')) {
                    $original_name  = strtolower($request->file('attachment_file_name')->getClientOriginalName());
                    $file_extension = strtolower($request->file('attachment_file_name')->getClientOriginalExtension());

                    if (in_array($file_extension,['png','jpg','jpeg'])) {
                        $file     = $request->file('attachment_file_name');
                        $filename = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
                        $path     = $this->query_image_base_path . $filename;
                        $isUpload = Image::make($file->getRealPath())->resize(168,168)->save($path);
                    } else if (in_array($file_extension,['pdf','doc','docx','txt', 'zip'])) {
                        $file     = $request->file('attachment_file_name');
                        $filename = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
                        $path     = $this->query_image_base_path . $filename;
                        $isUpload = $request->file('attachment_file_name')->move($this->query_image_base_path , $filename);
                    } else {
                        return $this->build_response('error', "Invalid File type ".$file_extension." while creating ticket");
                    }
                }

                $arr_data['attachment_file'] = $filename;
                $arr_data['user_id']         = $user_id;
                $arr_data['support_level']   = 'L3';
                $arr_data['user_type']       = $user_type;
                $result = $this->SupportQueryModel->create($arr_data);

                /*$arr_comment_data['query_id']        = $result->id;
                $arr_comment_data['comment_by']      = $user_id;
                $arr_comment_data['user_id']         = $user_id;
                $arr_comment_data['support_user_id'] = 0;
                $arr_comment_data['comment']         = isset($arr_data['query_description']) ? $arr_data['query_description'] : '';
                $this->SupportQueryCommentModel->create($arr_comment_data);*/

                $obj_guest        = $this->UserModel->where('id',$user_id)->first();
                $obj_support_team = $this->SupportTeamModel->where('support_level','L3')->get();

                $arr_built_content = array(
                                            'USER_NAME' => isset($obj_guest->first_name) ? $obj_guest->first_name : 'NA',
                                            'SUBJECT' => trim($request->input('query_subject'))
                                    );

                $arr_notify_data['arr_built_content']  = $arr_built_content;
                $arr_notify_data['notify_template_id'] = '4';
                $arr_notify_data['sender_id']          = $user_id;
                $arr_notify_data['sender_type']        = $user_type;
                $arr_notify_data['receiver_type']      = '3';
                //$arr_notify_data['url']                 = $notification_url;

                if(count($obj_support_team)>0) {
                    foreach($obj_support_team as $row) {
                        $arr_notify_data['receiver_id'] = $row->id;
                        $notification_status            = $this->NotificationService->send_notification($arr_notify_data);
                    }
                }

                if(isset($obj_guest) && $obj_guest != null) {
                    // for email
                    if($obj_guest->notification_by_email == "on") {
                        $arr_built_content = [
                                            'USER_NAME'     => isset($obj_guest->first_name)?ucfirst($obj_guest->first_name):'NA',
                                            'Email'         => isset($obj_guest->email)?$obj_guest->email:'NA',
                                            'TICKET_ID'     => isset($status->id)?$status->id:'NA',
                                            'QUERY_SUBJECT' => isset($status->query_subject)?$status->query_subject:'NA',
                                            'PROJECT_NAME'  => config('app.project.name')
                                        ];
                                         
                        $arr_mail_data                      = [];
                        $arr_mail_data['email_template_id'] = '7';
                        $arr_mail_data['arr_built_content'] = $arr_built_content;
                        $arr_mail_data['user']              = [ 'email' => isset($obj_guest->email) ? $obj_guest->email : 'NA',
                            'first_name' => isset($obj_guest->first_name) ? ucfirst($obj_guest->first_name) : 'NA' ];
                        $result = $this->EmailService->send_mail($arr_mail_data);
                    }
                    // for sms
                    if($obj_guest->notification_by_sms == "on") {
                        $country_code  = isset($obj_guest->country_code) ? $obj_guest->country_code : '';
                        $mobile_number = isset($obj_guest->mobile_number) ? $obj_guest->mobile_number : '';

                        $arr_sms_data                  = [];
                        $arr_sms_data['msg']           = 'Ticket id is sent on your email account successfully.';
                        $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                        $result                        = $this->SMSService->send_SMS($arr_sms_data);
                    }
                    // for push notification
                    if($obj_guest->notification_by_push == "on") {
                        $headings = 'Ticket id is sent on your email account successfully.';
                        $content  = 'Ticket id is sent on your email account successfully.';
                        $result   = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                    }
                }
                if($result) {
                    $status  = 'success';
                    $message = 'Ticket id is sent on your email account successfully.';
                }
                else {
                    $status  = 'error';
                    $message = 'Problem Occurred, While Creating Ticket.';
                }
            }
        } else {
            $status  = 'error';
            $message = 'Token expired, user not found.';
        }
        return $this->build_response($status,$message);  
    }

    public function add_to_favourite(Request $request)
    {
        $favourite_status = '';
        $form_data = $arr_favourite = $arr_json = $arr_favourite_data = $does_exists = [];

        $property_id = $request->input('property_id');
        $arr_favourite['property_id']    = $property_id;
        $arr_favourite['user_id']        = $this->user_id;
        $arr_favourite['favourite_from'] = "property listing";

        $does_exists = $this->FavouritePropertyModel->where('property_id',$arr_favourite['property_id'])->where('user_id',$arr_favourite['user_id'])->get();

        if (count($does_exists) > 0) {
            $del_favourite = $this->FavouritePropertyModel->where('property_id',$arr_favourite['property_id'])->where('user_id',$this->user_id)->delete();
            if ($del_favourite) {
                $status  = 'success';
                $message = 'Property successfully removed from your favourite list';
            } else {
                $status  = 'error';
                $message = 'Problem occur while removing property from favourite list';
            }                
        } elseif (count($does_exists) == 0) {
            $favourite_status = $this->FavouritePropertyModel->create($arr_favourite);
            if($favourite_status) {
                $status  = 'success';
                $message = 'Property has been added in your favourite list.';
            } else {
                $status  = 'error';
                $message = 'Error while adding in favourite list.';
            }
        }
        return $this->build_response($status,$message);
    }

    public function add_money(Request $request)
    {
        $notification_url =  '/wallet';
        
        $old_amount       = '';
        $transaction_id   = $request->input('transaction_id');
        $payment_amount   = $request->input('payment_amount');
        
        if ($this->user_id) {
            if ((isset($transaction_id) && $transaction_id != '') && (isset($payment_amount) && $payment_amount != '')) {
                $data['transaction_id']   = $transaction_id;
                $data['payment_type']     = 'wallet';
                $data['user_id']          = $this->user_id;
                $data['user_type']        = '1';
                $data['amount']           = $payment_amount;
                $data['transaction_for']  = "Amount added in wallet";
                $data['transaction_date'] = date('Y-m-d H:i:s');

                $obj_transaction = $this->TransactionModel->create($data);
                if ($obj_transaction) {
                    $obj_user_data = $this->UserModel->where('id', $this->user_id)->first();
                    $invoice       = $this->generateInvoice($obj_transaction->id);

                    $this->TransactionModel->where('id',$obj_transaction->id)->update(['invoice' => $invoice]);
                    if ($obj_user_data) {
                        $arr_user   = $obj_user_data->toArray();
                        $old_amount = $arr_user['wallet_amount'];
                    }

                    $arr_built_content = array(
                                            'USER_NAME' => isset($this->user_first_name) ? $this->user_first_name : 'NA',
                                            'SUBJECT'   => "Amount added in wallet successfully"
                                        );
                    
                    $arr_notify_data['arr_built_content']  = $arr_built_content;   
                    $arr_notify_data['notify_template_id'] = '8';
                    $arr_notify_data['sender_id']          = '1';
                    $arr_notify_data['sender_type']        = '2';
                    $arr_notify_data['receiver_type']      = '1';
                    $arr_notify_data['receiver_id']        = $this->user_id;
                    $arr_notify_data['url']                = $notification_url;
                    $this->NotificationService->send_notification($arr_notify_data);

                    $user_data['wallet_amount'] = $payment_amount + $old_amount;
                    $obj_user     = $this->UserModel->where('id', $this->user_id)->update($user_data);
                    if ($obj_user) {
                        $type = get_notification_type_of_user($this->user_id);
                        
                        if (isset($type) && !empty($type)) {
                            
                            // for mail
                            if ($type['notification_by_email'] == 'on') {
                                $arr_built_content = [
                                                    'USER_NAME'            => isset($arr_user['display_name'])?ucfirst($arr_user['display_name']) : 'NA',   
                                                    'Email'                => isset($arr_user['email'])?ucfirst($arr_user['email']) : 'NA' ,  
                                                    'MESSAGE'              => "Amount added to wallet successfully",
                                                    'PROJECT_NAME'         => config('app.project.name'),
                                                    'NOTIFICATION_SUBJECT' => 'Notification'
                                                ];
                                $arr_mail_data                      = [];
                                $arr_mail_data['email_template_id'] = '13';
                                $arr_mail_data['arr_built_content'] = $arr_built_content;
                                $arr_mail_data['user']              = ['email' => isset($arr_user['email']) ? ucfirst($arr_user['email']) : 'NA', 'first_name' => isset($arr_user['display_name']) ? ucfirst($arr_user['display_name']) : 'NA'];
                                $arr_mail_data['attachment']        = public_path('uploads/invoice/'.$invoice);
                                $this->EmailService->send_invoice_mail($arr_mail_data);
                            }

                            // for sms 
                            if ($type['notification_by_sms'] == 'on') {
                                
                                $country_code  = isset($arr_user['country_code']) ? $arr_user['country_code'] : '';
                                $mobile_number = isset($arr_user['mobile_number']) ? $arr_user['mobile_number'] : '';

                                $arr_sms_data                  = [];
                                $arr_sms_data['msg']           = "Amount added to wallet successfully";
                                $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
                                $this->SMSService->send_SMS($arr_sms_data);
                            }

                            // for push notification
                            if ($type['notification_by_push'] == 'on') {
                                $headings = 'Amount added to wallet successfully';
                                $content  = 'Amount added to wallet successfully.';
                                $user_id  = $this->user_id;
                                $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
                            }
                        }
                        return $this->build_response("success", "Amount added to wallet successfully");
                    } else {
                        return $this->build_response("error", "Something went wrong. Please try again");
                    }
                } else {
                    return $this->build_response("error", "Something went wrong. Please try again");
                }
            } else {
                return $this->build_response("error","Invalid request");
            }
        } else {
            return $this->build_response("error","Invalid user");
        }
    }

    public function generateInvoice($transaction_id = false)
    {
        $data = $guest_data = $arr_transaction = [];
        $FileName = '';

        if (isset($transaction_id) && $transaction_id != false) {
            $obj_transaction = $this->TransactionModel->where('id',$transaction_id)->first();
            if($obj_transaction) {
                $arr_transaction = $obj_transaction->toArray();
            }

            $obj_user_data = $this->UserModel->where('id', $this->user_id)->first();
            if($obj_user_data) {
                $guest_data = $obj_user_data->toArray();
            }

            $data['logo']     = url('/front/images/logo-inner.png');
            $data['base_url'] = url('/');

            PDF::SetTitle(config('app.project.name'));
            PDF::SetAuthor(config('app.project.name'));
            PDF::SetCreator(PDF_CREATOR);
            PDF::SetSubject('Wallet Invoice');

            // set margins
            PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);

            // set auto page breaks
            PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set JPEG quality
            PDF::setJPEGQuality(100);


            $view1 = view('invoice.wallet_invoice')->with(['guest_data'=>$guest_data, 'transaction_data'=>$arr_transaction, 'data'=>$data]);
            $html1 = $view1->render();

            $view2 = view('invoice.wallet_invoice2')->with(['guest_data'=>$guest_data, 'transaction_data'=>$arr_transaction, 'data'=>$data]);
            $html2 = $view2->render();


            // First Page 
            PDF::AddPage();
            $html1 = $view1->render();
            PDF::writeHTML($html1, true, false, true, false, 'L');

            // Second Page 
            PDF::AddPage();
            $html2 = $view2->render();
            PDF::writeHTML($html2, true, false, true, false, 'L');


            $FileName = 'Invoice'.$transaction_id.'.pdf';
            PDF::output(public_path('uploads/invoice/'.$FileName),'F');
            //return PDF::output('name.pdf','I');
            PDF::reset();
        }
        return $FileName;
    }

    public function wallet_balance(Request $request)
    {
        if ($this->user_id) {
            $arr_user_data = $this->UserModel->select('wallet_amount')->where('id', $this->user_id)->first();
            return $this->build_response("success","", number_format($arr_user_data['wallet_amount'],2,'.',''));
        } else {
            return $this->build_response("error","Invalid user");
        }
    }
}
