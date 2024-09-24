@extends('front.layout.master')                
@section('main_content')

<div class="clearfix"></div>
     <div class="overflow-hidden-section">
     <div class="titile-user-breadcrum">
      <div class="container">
          <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions"><h1>My Query</h1> <div class="clearfix"></div></div>
      </div> 
    </div>

<style>
        .error {
            color: red;
        }
    </style>
{{ csrf_field() }}

{{-- {{ dd($record['user_details']['profile_image']) }} --}}

    <div class="change-pass-bg">
        <div class="container">
            <div class="row">
                 <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 left-bar-dashboard">    
                 <div id="left-bar-host">
                     @include('front.layout.left_bar_host')
                 </div>
                </div>
                <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                    <div class="user-tarnsaction-main">
                        <div class="change-pass-bady host-trans-details-main">
                            <div class="view-content-details">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                        <div class="view-content-head">
                                            Ticket ID
                                        </div>
                                        <div class="view-content-txt">
                                            {{ $record['id'] or '' }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                        <div class="view-content-head">
                                            Category
                                        </div>
                                        <div class="view-content-txt">
                                            {{ $record['query_type_details']['query_type'] or '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="view-content-details">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                        <div class="view-content-head">
                                            Query
                                        </div>
                                        <div class="view-content-txt">
                                            {{ str_limit($record['query_subject'],25) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                        <div class="view-content-head">
                                            Date
                                        </div>
                                        <div class="view-content-txt">
                                            {{ $record['created_at'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="view-content-details">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                        <div class="view-content-head">
                                            Level
                                        </div>
                                        <div class="view-content-txt">
                                            {{ $record['support_level'] or '' }}
                                        </div>
                                    </div>                                    
                                </div>
                            </div>                            
                        </div> 

                        <div id="show_message"></div>
                        <div id="add_class">
                        <div class="change-pass-bady host-trans-details-main">
                            <div class="question-section">
                                <span>Q.</span> {{ $record['query_description'] or '' }}
                            </div>
                            <div class="clearfix"> </div>
                            <div class="my-query-chat-main">
                            @if(isset($record['query_comments']) && sizeof($record['query_comments'])>0)
                            @foreach($record['query_comments'] as $comments_user)     
                                <div class="@if($comments_user['support_user_id']==0 ) left-message-block @else left-message-block right-side @endif"  >
                                    <div class="left-message-profile">
                                        {{-- <img src="{{url('/front')}}/images/user-profile-img.jpg" alt="" class="mCS_img_loaded"> --}}

                                          @if(isset($record['user_details']['profile_image']) && !empty($record['user_details']['profile_image']))  
                                                <img src={{ $profile_image_public_img_path.$record['user_details']['profile_image']}} alt="" class="mCS_img_loaded" />
                                          @else
                                                <img src="{{url('/front')}}/images/user-profile-img.jpg" alt="post"  class="mCS_img_loaded"/>
                                          @endif  
                                    </div>
                                    <div class="left-message-content">
                                        <div class="review-sender-name">
                                            <span class="name-sender"></span> <span class="message-time">{{ $comments_user['created_at'] or '' }}</span>
                                        </div>                                    
                                        <div class="actual-message">
                                            {{ $comments_user['comment'] or '' }}
                                        </div>                                        
                                    </div>                                        
                                </div>
                                <div class="clearfix"> </div>
                             @endforeach
                             @else                     
                             @endif                           
                            </div>   
                            <div class="write-message-block">
                                <input name="chat_text" id="chat_text" placeholder="Write a Reply..." type="text" data-rule-required="true">
                                <span for="username" id="err_chat_text" class="error"></span>                                        
                                <button class="send-message-btn" id="submit_text" ><i class="fa fa-paper-plane"></i></button>
                            </div>
                        </div> 
                        </div>                      
                    </div>                    
                </div>
            </div>
        </div>
    </div>
    </div>
    
    

    <script>
        jQuery(document).ready(function()
        {
            jQuery("#submit_text").click(function()
            {
                var token = $("input[name='_token']").val(); 
                var chat_text = $('#chat_text').val();  
                var query_id = '{{ $id }}';
                var url = '{{ $module_path }}/store_query';
                // if(chat_text=="")
                // {
                //   $("#err_chat_text").html("This field is required"); 
                //   $("#chat_text").on('keyup',function(){ $("#err_chat_text").html("");});
                //   $("#chat_text").focus();
                //   return false;
                // } 

                $.ajax({                        
                           url: url,
                           method:"post",
                           data:{_token:token, chat_text:chat_text, query_id:query_id},                           
                           beforeSend:showProcessingOverlay(), 
                           success:function(response)
                           { 
                              hideProcessingOverlay();
                              if(response.status=='success')
                              {
                                var msg = makeStatusMessageHtml('success',response.message);
                                 $("#add_class").load(location.href + " #add_class");
                              }
                              else
                              {
                                var msg = makeStatusMessageHtml('danger',response.message);
                              }
                              $("#show_message").html(msg);
                           } 
                      });       
            }); 
        });


        function makeStatusMessageHtml(status, message)
        {
            str = '<div class="alert alert-'+status+'">'+
            '<a aria-label="close" data-dismiss="alert" class="close" href="#">'+'×</a>'+message+
            '</div>';
            return str;
        }
</script>
    @endsection