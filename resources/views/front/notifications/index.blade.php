@extends('front.layout.master')                
@section('main_content')

    <!--Header section end here-->
    <div class="clearfix"></div>
    <div class="overflow-hidden-section">
        <div class="titile-user-breadcrum">
            <div class="container">
                <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions">
                    <h1>Notification</h1>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <div class="change-pass-bg main-hidden">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 left-bar-dashboard">    
                      @include('front.layout.left_bar_host')
                    </div>
                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                        <div id="show_response"></div>

                            <div class="change-pass-bady natification">
                                <div class="form-main-block">
                                  @if(isset($arr_notification) && sizeof($arr_notification)>0 && is_array($arr_notification))
                                    @foreach($arr_notification as $key=>$notification)
                                      <?php
                                        if(isset($notification['created_at']) && $notification['created_at'] != "0000-00-00 00:00:00" )
                                        {
                                          $date = get_added_on_date( $notification['created_at'] );  
                                          $time = get_formated_time( $notification['created_at'] );
                                        }
                                      ?>
                                      <div class="notification-block">
                                        <div class="contact-left-img" onclick='delete_record("{{base64_encode($notification['id'])}}")'></div>
                                        
                                        <div class="notification-icon">
                                          <img src="{{ isset($notification['profile_image']) ? $notification['profile_image'] : '' }}" alt="" />
                                        </div>

                                        <a href="{{ isset($notification['url']) ? url('/').$notification['url'] : '' }}">
                                          <div class="notification-content">
                                            <div class="noti-head">
                                              {{ isset($notification['notification_text']) ? $notification['notification_text'] : 'NA' }}
                                            </div>
                                            <div class="noti-head-content">
                                              {{ isset($date) ? $date : 'NA' }} at {{isset($time) ? $time : 'NA' }}
                                            </div>
                                          </div>
                                        </a>

                                        <div class="clr"></div>
                                      </div>
                                      
                                    @endforeach
                                  @else
                                    <div class="no-record-found"></div>
                                  @endif
                                </div>
                            </div>

                            @include('front.common.pagination', ['obj_pagination' => $obj_pagination])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

      $('input[name=notification_type]').change(function(){

          var notification_type = $( 'input[name=notification_type]:checked' ).val();

          var site_url = '{{$module_url_path}}';
          var url_path = site_url+'/notification_type/'+notification_type;                   

          $.ajax({
            type: "GET",
            url: url_path,            
            dataType: "json",
            success: function(res) {
                if(res.status == 'success' ){                
                  var msg = makeStatusMessageHtml('success','Thanks for Selecting Notifications type');
                       $("#show_response").html(msg);                           
                }  
                else
                {
                  var msg = makeStatusMessageHtml('error','You can not update receiving notification type');
                       $("#show_response").html(msg);                           
                }     


            }
        });      
      });

      function makeStatusMessageHtml(status, message)
      {
        str = '<div class="alert alert-'+status+'">'+
        '<a aria-label="close" data-dismiss="alert" class="close" href="#">'+'×</a>'+message+
        '</div>';
        return str;
      }


      function delete_record(id)
      {
        swal({
          title: "Are you sure",
          text: "Do you want to delete records?",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Yes, delete it!",
          closeOnConfirm: false
        },
        function(){
          location.href="{{url($module_url_path)}}/delete/"+id;
        });
      }
    </script>

@endsection