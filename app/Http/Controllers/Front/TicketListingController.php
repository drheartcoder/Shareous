<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Common\Services\NotificationService;
use App\Common\Services\EmailService;
use App\Common\Services\SMSService;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SupportQueryCommentModel;
use App\Models\SupportQueryModel;
use App\Models\SupportTeamModel;
use App\Models\QueryTypeModel;
use App\Models\UserModel;
use Validator;
use Session;
use Image;

class TicketListingController extends Controller
{
    public function __construct(
                                    EmailService             $email_service,
                                    SMSService               $sms_service,
                                    NotificationService      $notification_service,
                                    SupportQueryModel        $support_query_model,
                                    QueryTypeModel           $query_type_model,
                                    UserModel                $user_model,
                                    SupportTeamModel         $support_team_model,
                                    SupportQueryCommentModel $support_query_comment_model
                                )
    {
        $this->array_view_data          = [];
        $this->module_title             = 'Ticket-Listing';
        $this->module_view_folder       = 'front.ticket';
        $this->module_url_path          = url('/ticket-listing');
        $this->SupportQueryModel        = $support_query_model;
        $this->BaseModel                = $support_query_model;
        $this->SupportQueryCommentModel = $support_query_comment_model;
        $this->QueryTypeModel           = $query_type_model;
        $this->UserModel                = $user_model;
        $this->SupportTeamModel         = $support_team_model;
        $this->EmailService             = $email_service;
        $this->SMSService               = $sms_service;
        $this->NotificationService      = $notification_service;
        $this->auth                     = auth()->guard('users');
        
        $user = $this->auth->user();
        if($user) {
            $this->user_id            = $user->id;
            $this->user_first_name    = $user->first_name;
            $this->user_last_name     = $user->last_name;
            $this->user_user_name     = $user->user_name;
            $this->user_display_name  = $user->display_name;
            $this->user_profile_image = $user->profile_image;
        }

        $this->query_image_public_path           = url('/').config('app.project.img_path.query_image');
        $this->query_image_base_path             = public_path().config('app.project.img_path.query_image');
        $this->user_profile_image_public_path    = url('/').config('app.project.img_path.user_profile_images');
        $this->user_profile_image_base_path      = public_path().config('app.project.img_path.user_profile_images');
        $this->support_profile_image_public_path = url('/').config('app.project.img_path.support_profile_images');
        $this->support_profile_image_base_path   = public_path().config('app.project.img_path.support_profile_images');
    }

    public function index(Request $request)
    {
        $user_type      = Session::get('user_type');
        $user_id        = $this->user_id;
        $field_name     = trim($request->input('field_name'));
        $sort_by        = trim($request->input('sort_by'));
        $arr_query_type = [];

        $where_cnd = array('user_type' => $user_type , 'user_id' => $user_id);
        $obj_query = $this->SupportQueryModel->select(  'support_query.id',
                                                        'support_query.user_id',
                                                        'support_query.user_type',
                                                        'support_query.support_user_id',
                                                        'support_query.query_type_id as query_type_id',
                                                        'support_query.support_level',
                                                        'support_query.booking_id',
                                                        'support_query.query_subject',
                                                        'support_query.query_description',
                                                        'support_query.attachment_file',
                                                        'support_query.attachment_file_name',
                                                        'support_query.status',
                                                        'support_query.created_at',
                                                        'query_type.query_type'
                                                    )
                                                ->leftjoin('query_type','query_type.id','=','support_query.query_type_id')
                                                ->where($where_cnd);

        if($sort_by == null || $sort_by == "") {
            $obj_query = $obj_query->orderBy('support_query.id','DESC' );
        } else {
            if($field_name == 'query_type') {
                $obj_query = $obj_query->orderBy('query_type.'.$field_name,$sort_by);
            } else {
                $obj_query = $obj_query->orderBy('support_query.'.$field_name,$sort_by);
            }
        }

        $obj_query = $obj_query->paginate(10);

        if(isset($obj_query) && $obj_query != null) {
            $arr_ticket     = $obj_query->toArray();
            $page_link      = $obj_query->links();
            $obj_pagination = clone $obj_query;
        }

        $this->arr_view_data['page_link']       = $page_link;
        $this->arr_view_data['arr_ticket']      = $arr_ticket;
        $this->arr_view_data['module_url_path'] = $this->module_url_path;
        $this->arr_view_data['page_title']      = $this->module_title.'Listing';

        return view($this->module_view_folder.'.ticket_listing',$this->arr_view_data);
    }

    public function ticket_details($id = NULL)
    {
        $arr_ticket = $arr_ticket_comments = [];
        $support_user_id = 0;

        // Ticket ID
        $id = base64_decode($id);

        // User Details
        $arr_user_details['id']            = $this->user_id;
        $arr_user_details['first_name']    = $this->user_first_name;
        $arr_user_details['last_name']     = $this->user_last_name;
        $arr_user_details['user_name']     = $this->user_user_name;
        $arr_user_details['display_name']  = $this->user_display_name;
        $arr_user_details['profile_image'] = $this->user_profile_image;

        // User Profile Image
        $arr_user_details['profile_image_url'] = url('/uploads').'/default-profile.png';
        if(isset($arr_user_details['profile_image']) && $arr_user_details['profile_image'] != '' ) {
            if(file_exists($this->user_profile_image_base_path.$arr_user_details['profile_image'])) {
                $arr_user_details['profile_image_url'] = $this->user_profile_image_public_path.$arr_user_details['profile_image'];
            }   
        }
        
        $obj_ticket = $this->SupportQueryModel->with('query_type_details')->where('id','=',$id)->get();
        if(isset($obj_ticket) && $obj_ticket != null) {
            $arr_ticket = $obj_ticket->toArray();

            $support_user_id = $arr_ticket[0]['support_user_id'];
        }

        $arr_ticket_comments = $this->get_previous_chat($id, $this->user_id, $support_user_id);

        $this->read_unread_message($id, $this->user_id, $support_user_id);

        $this->arr_view_data['query_id']                          = $id;
        $this->arr_view_data['support_user_id']                   = $support_user_id;

        $this->arr_view_data['arr_user_details']                  = $arr_user_details;
        $this->arr_view_data['arr_ticket']                        = $arr_ticket;
        $this->arr_view_data['arr_ticket_comments']               = $arr_ticket_comments;

        $this->arr_view_data['page_title']                        = $this->module_title;
        $this->arr_view_data['module_url_path']                   = $this->module_url_path;
        $this->arr_view_data['query_image_public_path']           = $this->query_image_public_path;
        $this->arr_view_data['query_image_base_path']             = $this->query_image_base_path;

        $this->arr_view_data['user_profile_image_public_path']    = $this->user_profile_image_public_path;
        $this->arr_view_data['user_profile_image_base_path']      = $this->user_profile_image_base_path;
        $this->arr_view_data['support_profile_image_public_path'] = $this->support_profile_image_public_path;
        $this->arr_view_data['support_profile_image_base_path']   = $this->support_profile_image_base_path;

        return view($this->module_view_folder.'.ticket_details',$this->arr_view_data);
    }


    public function ticket_download($id = NULL)
    {
        $id = base64_decode($id);

        $obj_ticket = $this->SupportQueryModel->with('query_type_details')->where('id','=',$id)->first();
        if(isset($obj_ticket) && $obj_ticket != null) {
            $arr_ticket = $obj_ticket->toArray();
        }

        if(isset($arr_ticket) && sizeof($arr_ticket) > 0) {
            if((isset($arr_ticket['attachment_file']) && $arr_ticket['attachment_file'] != '') && 
                file_exists($this->query_image_base_path.$arr_ticket['attachment_file'])) {
                $attachment_file = $this->query_image_base_path.$arr_ticket['attachment_file'];
                return \Response::download($attachment_file);
            }
        }

        Session::flash('error','Ticket Document not found,Please try again.');
        return redirect()->back();
    }





    public function store_chat(Request $request)
    {
        $arr_comment_data = [
                                'query_id'        => $request->input('query_id'),
                                'comment_by'      => $this->user_id,
                                'user_id'         => $this->user_id,
                                'user_type'       => Session::get('user_type'),
                                'support_user_id' => $request->input('support_user_id'),
                                'is_read'         => 0,
                                'comment'         => $request->input('comment'),
                            ];

        $obj_comment = $this->SupportQueryCommentModel->create($arr_comment_data);

        // Notification To User Starts
        $arr_built_content = array(
                                    'USER_NAME' => $this->user_first_name,
                                    'MESSAGE'   => "User has replied on your message."
                                );

        $arr_notify_data['arr_built_content']  = $arr_built_content;
        $arr_notify_data['notify_template_id'] = '9';
        $arr_notify_data['sender_id']          = $this->user_id;
        $arr_notify_data['sender_type']        = Session::get('user_type');
        $arr_notify_data['receiver_id']        = $request->input('support_user_id');
        $arr_notify_data['receiver_type']      = '3';
        $arr_notify_data['url']                = "/ticket";
        $notification_status = $this->NotificationService->send_notification($arr_notify_data);
        // Notification To User Ends

        $arr_response['status'] = 'success';
        $arr_response['msg']    = 'message send successfully.';
        $arr_response['id']     = isset($obj_comment->id) ? $obj_comment->id : 0;
        $arr_response['date']   = isset($obj_comment->created_at) ? date('d M, h:i A',strtotime($obj_comment->created_at)) : '';
        return $arr_response;
    }
    
    public function get_current_chat_messages(Request $request)
    {
        $query_id        = (int) $request->input('query_id');
        $user_id         = (int) $this->user_id;
        $user_type       = (int) Session::get('user_type');
        $support_user_id = (int) $request->input('support_user_id');

        $this->read_unread_message($query_id, $user_id, $support_user_id);

        $select_query = '';

        $select_query = "SELECT 
                            support_query_comments.id,
                            support_query_comments.query_id,
                            support_query_comments.user_id,
                            support_query_comments.user_type,
                            support_query_comments.support_user_id, 
                            support_query_comments.is_read,
                            support_query_comments.comment,
                            support_query_comments.comment_by,
                            DATE_FORMAT(support_query_comments.created_at,'%d %b, %h:%i %p') as date,
                            support_team.user_name,
                            support_team.first_name,
                            support_team.last_name,
                            support_team.email,
                            support_team.support_level,
                            support_team.contact,
                            support_team.address,
                            support_team.profile_image
                        FROM support_query_comments
                        LEFT JOIN support_team
                        ON support_query_comments.support_user_id = support_team.id
                        WHERE support_query_comments.query_id = $query_id
                        AND support_query_comments.user_id = $user_id
                        AND support_query_comments.user_type = $user_type
                        AND support_query_comments.support_user_id = $support_user_id
                        ORDER BY support_query_comments.id ASC";
        
        $arr_ticket_comments = [];
        if($select_query != '')
        {
            $obj_ticket_comments = \DB::select($select_query);

            if(isset($obj_ticket_comments) && sizeof($obj_ticket_comments) > 0) {
                $arr_ticket_comments = json_decode(json_encode($obj_ticket_comments), true);
            }
        }
        
        $arr_response = [];

        if(isset($arr_ticket_comments) && sizeof($arr_ticket_comments)>0)
        {
            $arr_response['status'] = 'success';
            $arr_response['msg']    = 'support chat available';
            $arr_response['data']   = $arr_ticket_comments;
            return $arr_response;
        }
        
        $arr_response['status'] = 'error';
        $arr_response['msg']    = 'support chat not available';
        $arr_response['data']   = $arr_ticket_comments;
        return $arr_response;

    }

    public function get_previous_chat($query_id, $user_id, $support_user_id)
    {
        $select_query = '';

        if($query_id != '' && $user_id != '' && $support_user_id != '')
        {
            $user_type = Session::get('user_type');

            $select_query = "SELECT 
                                support_query_comments.id,
                                support_query_comments.query_id,
                                support_query_comments.user_id,
                                support_query_comments.user_type,
                                support_query_comments.support_user_id, 
                                support_query_comments.is_read,
                                support_query_comments.comment,
                                support_query_comments.comment_by,
                                DATE_FORMAT(support_query_comments.created_at,'%d %b, %h:%i %p') as date,
                                support_team.user_name,
                                support_team.first_name,
                                support_team.last_name,
                                support_team.email,
                                support_team.support_level,
                                support_team.contact,
                                support_team.address,
                                support_team.profile_image
                            FROM support_query_comments
                            LEFT JOIN support_team
                            ON support_query_comments.support_user_id = support_team.id
                            WHERE support_query_comments.query_id = $query_id
                            AND support_query_comments.user_id = $user_id
                            AND support_query_comments.user_type = $user_type
                            AND support_query_comments.support_user_id = $support_user_id
                            ORDER BY support_query_comments.id ASC";
            
            $arr_ticket_comments = [];
            if($select_query != '')
            {
                $obj_ticket_comments =  \DB::select($select_query);

                if(isset($obj_ticket_comments) && sizeof($obj_ticket_comments) > 0) {
                    $arr_ticket_comments = json_decode(json_encode($obj_ticket_comments), true);
                }
            }
            return $arr_ticket_comments;
        }
        return [];
    }

    public function read_unread_message($query_id, $user_id, $support_user_id)
    {
        if($query_id != '' && $user_id != '' && $support_user_id != '')
        {
            $user_type = Session::get('user_type');

            return $this->SupportQueryCommentModel->where('query_id', $query_id)
                                                  ->where('user_id', $user_id)
                                                  ->where('user_type', $user_type)
                                                  ->where('support_user_id', $support_user_id)
                                                  ->where('comment_by', $support_user_id)
                                                  ->update(['is_read' => '1']);
        }
        return true;
    }
}
