@extends('support.layout.master') 
@section('main_content')
<!-- BEGIN Page Title -->
<div class="page-title">
  <div>
  </div>
</div>
<!-- END Page Title -->

<!-- BEGIN Breadcrumb -->
<div id="breadcrumbs">
  <ul class="breadcrumb">
    <li>
      <i class="fa fa-home"></i>
      <a href="{{ url($support_panel_slug.'/dashboard') }}">Dashboard</a>
    </li>
    <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa {{$module_icon}}"></i>
      <a href="{{ url($previous_page_url) }}">{{ $previous_page_title or ''}}</a>
    </span> 
    <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa {{$page_icon}}"></i>
    </span>
    <li class="active">{{ $page_title or ''}}</li>
  </ul>
</div>
<!-- END Breadcrumb -->

<!-- BEGIN Main Content -->
<div class="row">
  <div class="col-md-12">
    <div class="box {{support_navbar_color()}}">
      <div class="box-title">
        <h3>
          <i class="fa {{$page_icon}}"></i>
          {{ isset($page_title)?$page_title:"" }}
        </h3>
        <div class="box-tool">
          <a data-action="collapse" href="#"></a>
          <a data-action="close" href="#"></a>
        </div>
      </div>
      <div class="box-content">
        @include('support.layout._flash_errors') 
        <div class="row">
          <div class="col-xs-12 col-sm-8 col-md-9 col-lg-6 col-lg-offset-2">
            <div class="change-pass-bady massage">
                <div class="dash-white-main massage">
                    <div data-responsive-tabs="" class="verticalslide">
                        
                        <?php
                        $arr_message_id = [];

                        $profile_image = url('/uploads').'/default-profile.png';
                        $user_fname = isset($user_data['user_details']['first_name'])?$user_data['user_details']['first_name']:'';
                        $user_lname = isset($user_data['user_details']['last_name'])?$user_data['user_details']['last_name']:'';
                        $user_profile_img = isset($user_data['user_details']['profile_image'])?$user_data['user_details']['profile_image']:'';

                        if( file_exists($user_profile_image_base_path.$user_profile_img) ) {
                          $profile_image = $user_profile_image_public_path.$user_profile_img;
                        }
                        ?>

                        <div class="content">
                            <section id="tabone">
                                <div class="messages-section content-d append-chat-html">
                                    
                                  @if(isset($arr_ticket_comments) && !empty($arr_ticket_comments))
                                    @foreach($arr_ticket_comments as $key => $chat)

                                    <?php
                                        if(isset($chat['id']) && $chat['id'] != '' ) {
                                            $arr_message_id[] = strval($chat['id']);
                                        }
                                    ?>

                                    @if(isset($chat) && !empty($chat) && $chat['user_id'] == $chat['comment_by'])
                                    <div class="left-message-block">
                                        <div class="left-message-profile">
                                            <img src="{{ $profile_image }}" alt="">
                                        </div>
                                        <div class="left-message-content">
                                            <div class="actual-message">{{ $chat['comment'] }}</div>
                                        </div>
                                        <div class="messager-name-time-section">
                                            <div class="message-time">{{ $user_fname.' '.$user_lname }}</div>
                                            <div class="message-time message-date-time-section">{{ $chat['date'] }}</div>
                                        </div>
                                    </div>
                                    @endif

                                    @if(isset($arr_support_details) && !empty($arr_support_details) && $arr_support_details['id'] == $chat['comment_by'])
                                    <div class="left-message-block right-message-block">
                                        <div class="left-message-profile">
                                            <img src="{{ isset($arr_support_details['profile_image_url']) ? $arr_support_details['profile_image_url'] : '' }}" alt="">
                                        </div>
                                        <div class="left-message-content">
                                            <div class="actual-message">{{ $chat['comment'] }}</div>
                                        </div>
                                        <div class="messager-name-time-section">
                                            <div class="message-time">Me</div>
                                            <div class="message-time">{{ $chat['date'] }}</div>
                                        </div>
                                    </div>
                                    @endif

                                    @endforeach
                                  @endif

                                </div>
                                <div class="write-message-block">
                                    <input type="text" id="message" name="message" placeholder="Write a Reply...">
                                    <button class="send-message-btn" onclick="sendMessage()"><i class="fa fa-paper-plane"></i></button>
                                </div>
                            </section>

                         </div>
                        <div class="clr"></div>
                    </div>
                </div>
            </div>
        </div>
        </div>
      </div>
    </div>
  </div>

<input type="hidden" name="arr_message_id" id="arr_message_id" value="{{ isset($arr_message_id) ? json_encode($arr_message_id) : json_encode([]) }}">

<script type="text/javascript">
    $('.append-chat-html').scrollTop($('.append-chat-html')[0].scrollHeight);

    var query_id  = '{{ isset($query_id) ? $query_id : '' }}';
    var user_id   = '{{ isset($user_id) ? $user_id : '' }}';
    var user_type = '{{ isset($user_type) ? $user_type : '' }}';

    var user_profile_image_public_path = "{{ isset($user_profile_image_public_path) ? $user_profile_image_public_path : '' }}";
    var support_profile_image = "{{ isset($arr_support_details['profile_image_url']) ? $arr_support_details['profile_image_url'] : '' }}";
    
    var arr_tmp_chat_message_id = $('#arr_message_id').val();
    var arr_chat_message_id = [];

    if(arr_tmp_chat_message_id != '') {
        arr_chat_message_id  = JSON.parse(arr_tmp_chat_message_id);
    }

    $(document).ready(function() {
        setTimeout(function() {
            start_auto_load_chat_history();
        }, 2000);
    });

    function start_auto_load_chat_history() {
        setInterval(function() {
            load_current_chat_history() 
        }, 5000);
    }

    function load_current_chat_history() {
        $.ajax({
            url : '{{ $module_url_path }}/get_current_chat_messages',
            type : "GET",
            dataType: 'JSON',
            data : { query_id : query_id, user_id : user_id, user_type : user_type },
            success:function(response){
                if(response.status == 'success'){
                    build_chat_html(response.data);
                }
            },
            error:function(response){
            }
        });

    }

    function  build_chat_html(response) {
        
        if(arr_chat_message_id.length > 0) {

            response.forEach(function (value, index) {
                if (value != undefined && value.id!=undefined) {
                    
                    if($.inArray(String(value.id), arr_chat_message_id) == -1){
                        var chat_html = '';
                        
                        if (value['comment_by'] != undefined && value['comment_by'] == user_id) {
                            chat_html += '<div class="left-message-block">';
                            chat_html +=     '<div class="left-message-profile">';
                            chat_html +=         '<img src="'+user_profile_image_public_path+''+value.profile_image+'" alt="">';
                            chat_html +=     '</div>';
                            chat_html +=     '<div class="left-message-content">';
                            chat_html +=         '<div class="actual-message">'+value.comment+'</div>';
                            chat_html +=     '</div>';
                            chat_html +=     '<div class="messager-name-time-section">';
                            chat_html +=         '<div class="message-time">'+value.first_name+' '+value.last_name+'</div>';
                            chat_html +=         '<div class="message-time message-date-time-section">'+value.date+'</div>';
                            chat_html +=     '</div>';
                            chat_html += '</div>';
                        }
                        else{
                            chat_html += '<div class="left-message-block right-message-block">';
                            chat_html +=     '<div class="left-message-profile">';
                            chat_html +=         '<img src="'+support_profile_image+'" alt="">';
                            chat_html +=     '</div>';
                            chat_html +=     '<div class="left-message-content">';
                            chat_html +=         '<div class="actual-message">'+value.comment+'</div>';
                            chat_html +=     '</div>';
                            chat_html +=     '<div class="messager-name-time-section">';
                            chat_html +=         '<div class="message-time">Me</div>';
                            chat_html +=         '<div class="message-time">'+value.date+'</div>';
                            chat_html +=     '</div>';
                            chat_html += '</div>';
                        }
                        arr_chat_message_id.push(String(value.id));
                        $('.append-chat-html').append(chat_html);
                        $('.append-chat-html').scrollTop($('.append-chat-html')[0].scrollHeight);
                    }
                }
            });
        }
        else
        {
            var chat_html = '';
            response.forEach(function (value, index) {
                if (value != undefined && value.id!=undefined) {
                    
                    arr_chat_message_id.push(String(value.id));
                    
                    if (value['comment_by'] != undefined && value['comment_by'] == user_id) {
                        chat_html += '<div class="left-message-block">';
                        chat_html +=     '<div class="left-message-profile">';
                        chat_html +=         '<img src="'+user_profile_image_public_path+''+value.profile_image+'" alt="">';
                        chat_html +=     '</div>';
                        chat_html +=     '<div class="left-message-content">';
                        chat_html +=         '<div class="actual-message">'+value.comment+'</div>';
                        chat_html +=     '</div>';
                        chat_html +=     '<div class="messager-name-time-section">';
                        chat_html +=         '<div class="message-time">'+value.first_name+' '+value.last_name+'</div>';
                        chat_html +=         '<div class="message-time message-date-time-section">'+value.date+'</div>';
                        chat_html +=     '</div>';
                        chat_html += '</div>';
                    }
                    else{
                        chat_html += '<div class="left-message-block right-message-block">';
                        chat_html +=     '<div class="left-message-profile">';
                        chat_html +=         '<img src="'+support_profile_image+'" alt="">';
                        chat_html +=     '</div>';
                        chat_html +=     '<div class="left-message-content">';
                        chat_html +=         '<div class="actual-message">'+value.comment+'</div>';
                        chat_html +=     '</div>';
                        chat_html +=     '<div class="messager-name-time-section">';
                        chat_html +=         '<div class="message-time">Me</div>';
                        chat_html +=         '<div class="message-time">'+value.date+'</div>';
                        chat_html +=     '</div>';
                        chat_html += '</div>';
                    }
                }
            });

            $('.append-chat-html').html('');
            $('.append-chat-html').append(chat_html);
            $('.append-chat-html').scrollTop($('.append-chat-html')[0].scrollHeight);

        }
        
    }

    function sendMessage(){

        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        var currentdate = new Date();
        var time = currentdate.toLocaleString([], { hour: '2-digit', minute: '2-digit' });
        var datetime = currentdate.getDate() + " " + monthNames[currentdate.getMonth()]  + ", " + time;

        var message = '';
        var tmp_message = $('#message').val();
        message = $.trim(tmp_message);
        
        if(message != ''){

            ajax_send_messsage(message);

            var chat_html = '';

            chat_html += '<div class="left-message-block right-message-block">';
            chat_html +=     '<div class="left-message-profile">';
            chat_html +=         '<img src="'+support_profile_image+'" alt="">';
            chat_html +=     '</div>';
            chat_html +=     '<div class="left-message-content">';
            chat_html +=         '<div class="actual-message">'+message+'</div>';
            chat_html +=     '</div>';
            chat_html +=     '<div class="messager-name-time-section">';
            chat_html +=         '<div class="message-time">Me</div>';
            chat_html +=         '<div class="message-time">'+datetime+'</div>';
            chat_html +=     '</div>';
            chat_html += '</div>';

            $('.append-chat-html').append(chat_html);
            $('#message').val('');
            $('.append-chat-html').scrollTop($('.append-chat-html')[0].scrollHeight);
        }
    }
    
    $('#message').keyup(function(e) {
        if(e.keyCode == 13) {
            sendMessage();
        }
    });

    var isRunning = null;

    function ajax_send_messsage(message) {
        var _token = "{{ csrf_token() }}";

        var obj_data = { query_id : query_id, user_id : user_id, user_type : user_type, comment : message, _token : _token };
        
        isRunning = $.ajax({
            url      : '{{ $module_url_path }}/store_chat',
            type     : "POST",
            dataType : 'JSON',
            data     : obj_data,
            beforeSend:function(response) {
                $('#message').attr("disabled");
                if(isRunning != null) {
                    return false;
                }
            },
            success:function(response) {
                isRunning = null;
                $('#message').removeAttr("disabled");
                if(response.status == 'success') {
                    if(response.id != undefined && response.id != 0) {
                        arr_chat_message_id.push(String(response.id));
                    }
                }
            },
            error:function(response) {
              isRunning = null;  
              $('#message').removeAttr("disabled");
            }
        });
    }
</script>

@stop
