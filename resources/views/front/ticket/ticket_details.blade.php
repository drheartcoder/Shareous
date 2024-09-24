@extends('front.layout.master')                
@section('main_content')

    <div class="clearfix"></div>
    <div class="overflow-hidden-section">
        <div class="titile-user-breadcrum">
            <div class="container">
                <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions">
                    <h1>Ticket Details</h1>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <div class="change-pass-bg">
            <div class="container">
                <div class="row">

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 left-bar-dashboard">
                        <div id="left-bar-host">@include('front.layout.left_bar_host')</div>
                    </div>

                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                        @if(isset($arr_ticket) && !empty($arr_ticket))
                            @foreach($arr_ticket as $ticket)  

                                @php
                                    $ticket_date = isset($ticket['created_at']) ? $ticket['created_at'] : '';

                                    if(!empty($ticket_date) && $ticket_date != null) {
                                        $ticket_date = get_added_on_date($ticket_date);
                                    }

                                    $ticket_id         = isset($ticket['id']) ? $ticket['id'] : 0;
                                    $query_type        = isset($ticket['query_type_details']['query_type']) ? $ticket['query_type_details']['query_type'] : '';
                                    $query_subject     = isset($ticket['query_subject']) ? $ticket['query_subject'] : '-';
                                    $query_description = isset($ticket['query_description']) ? $ticket['query_description'] : '-';
                                @endphp

                                <div class="box-white-user">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <div class="box-main-bx">
                                                <div class="li-boxss">
                                                    @if( $query_type != '' )
                                                        Query Type  : <span>{{ $query_type }}</span><br>
                                                    @endif
                                                    Subject     : <span>{{ $query_subject }}</span><br>
                                                    Explanation : <span>{{ $query_description }}</span><br>
                                                    Ticket Date : <span>{{ $ticket_date }}</span>
                                                </div>
                                            </div>
                                        </div>
                                      
                                        @if(isset($ticket['attachment_file']) && !empty($ticket['attachment_file']))
                                            <?php $extention = explode('.', $ticket['attachment_file']); ?>
                                            @if(in_array($extention['1'],['png','jpg','jpeg']))
                                                @if($ticket['attachment_file']!=null && file_exists($query_image_base_path.$ticket['attachment_file']))
                                                    <div class="col-sm-6 col-md-6 col-lg-12">
                                                        <div class="profile-img-block">
                                                            <a href="{{ $module_url_path.'/ticket-download/'.base64_encode($ticket_id) }}"><img src="{{ $query_image_public_path.$ticket['attachment_file'] }}" alt="" /></a>
                                                        </div>
                                                    </div>
                                                @endif
                                            @elseif(in_array($extention['1'],['pdf','PDF']))
                                                @if($ticket['attachment_file'] != null && file_exists($query_image_base_path.$ticket['attachment_file']))
                                                    <div class="col-sm-6 col-md-6 col-lg-12">
                                                        <div class="profile-img-block">
                                                            <a href="{{ $module_url_path.'/ticket-download/'.base64_encode($ticket_id) }}"><img src="{{ url('front/images/pdf.jpg') }}" alt="" /></a>
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                @if($ticket['attachment_file'] != null && file_exists($query_image_base_path.$ticket['attachment_file']))
                                                    <div class="col-sm-6 col-md-6 col-lg-12">
                                                        <div class="profile-img-block">
                                                            <a href="{{ $module_url_path.'/ticket-download/'.base64_encode($ticket_id) }}"><img src="{{ url('front/images/document-logo.jpg') }}" alt="" /></a>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif

                                    <div class="clearfix"></div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    @if(isset($arr_ticket_comments) && !empty($arr_ticket_comments))
                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                        <div class="change-pass-bady massage">
                            <div class="dash-white-main massage">
                                <div data-responsive-tabs class="verticalslide">                                    
                                    <div class="content">
                                        <section id="tabone">
                                            <div class="messages-section content-d append-chat-html">

                                                <?php
                                                    $arr_message_id = [];
                                                    $profile_image = url('/uploads').'/default-profile.png';
                                                ?>
                                                
                                                @foreach($arr_ticket_comments as $key => $chat)

                                                <?php
                                                    //dd( $chat );
                                                    if(isset($chat['id']) && $chat['id'] != '' ) {
                                                        $arr_message_id[] = strval($chat['id']);
                                                    }

                                                    $support_fname = isset( $chat['first_name'] ) ? $chat['first_name'] : '';
                                                    $support_lname = isset( $chat['last_name'] ) ? $chat['last_name'] : '';
                                                ?>

                                                @if(isset($chat) && !empty($chat) && $chat['support_user_id'] == $chat['comment_by'])
                                                    <div class="left-message-block">
                                                        <div class="left-message-profile">
                                                            <?php
                                                            if( isset($chat['profile_image']) && $chat['profile_image'] != '' && file_exists($support_profile_image_base_path.$chat['profile_image']) )
                                                                $profile_image = $support_profile_image_public_path.$chat['profile_image'];
                                                            ?>
                                                            <img src="{{ $profile_image }}" alt="" />
                                                        </div>
                                                        <div class="left-message-content">
                                                            <div class="actual-message">{{ $chat['comment'] }}</div>
                                                        </div>
                                                        <div class="messager-name-time-section">
                                                            <div class="message-time">{{ $support_fname.' '.$support_lname }}</div>
                                                            <div class="message-time message-date-time-section">{{ $chat['date'] }}</div>
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                @if(isset($arr_user_details) && !empty($arr_user_details) && $arr_user_details['id'] == $chat['comment_by'])
                                                    <div class="left-message-block right-message-block">
                                                        <div class="left-message-profile">
                                                            <img src="{{ isset($arr_user_details['profile_image_url']) ? $arr_user_details['profile_image_url'] : '' }}" alt="" />
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

                                            </div>
                                            <div class="write-message-block">
                                                <input type="text" id="message" name="message" placeholder="Write a Reply..." />
                                                <button class="send-message-btn" onclick="sendMessage()"><i class="fa fa-paper-plane"></i></button>
                                            </div>
                                        </section>

                                     </div>
                                    <div class="clr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

<input type="hidden" name="arr_message_id" id="arr_message_id" value="{{ isset($arr_message_id) ? json_encode($arr_message_id) : json_encode([]) }}">

<script type="text/javascript">
    $('.append-chat-html').scrollTop($('.append-chat-html')[0].scrollHeight);

    var query_id        = '{{ isset($query_id) ? $query_id : '' }}';
    var support_user_id = '{{ isset($support_user_id) ? $support_user_id : '' }}';

    var support_profile_image_public_path = "{{ isset($support_profile_image_public_path) ? $support_profile_image_public_path : '' }}";
    var user_profile_image = "{{ isset($arr_user_details['profile_image_url']) ? $arr_user_details['profile_image_url'] : '' }}";


    
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
            data : { query_id : query_id, support_user_id : support_user_id },
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
                        
                        if (value['comment_by'] != undefined && value['comment_by'] == support_user_id) {
                            chat_html += '<div class="left-message-block">';
                            chat_html +=     '<div class="left-message-profile">';
                            chat_html +=         '<img src="'+support_profile_image_public_path+''+value.profile_image+'" alt="" />';
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
                            chat_html +=         '<img src="'+user_profile_image+'" alt="" />';
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
                    
                    if (value['comment_by'] != undefined && value['comment_by'] == support_user_id) {
                        chat_html += '<div class="left-message-block">';
                        chat_html +=     '<div class="left-message-profile">';
                        chat_html +=         '<img src="'+support_profile_image_public_path+''+value.profile_image+'" alt="" />';
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
                        chat_html +=         '<img src="'+user_profile_image+'" alt="" />';
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
            chat_html +=         '<img src="'+user_profile_image+'" alt="" />';
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
        var _token   = "{{ csrf_token() }}";
        var obj_data = { query_id : query_id, support_user_id : support_user_id, comment : message, _token : _token };
        
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

@endsection
