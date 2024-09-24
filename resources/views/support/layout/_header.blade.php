<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>{{ isset($page_title)?$page_title:"" }} - {{ config('app.project.name') }}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="icon" type="image/png" sizes="16x16" href="{{url('/web_support/img/logo.png')}}">
        <!--base css styles-->
        <link rel="stylesheet" href="{{ url('/web_support/assets/bootstrap/css/bootstrap.min.css') }}">

        <link rel="stylesheet" href="{{ url('/web_support/css/new-font-awesome.min.css') }}">

        <!--page specific css styles-->
        <link rel="stylesheet" type="text/css" href="{{ url('/web_support/assets/bootstrap-fileupload/bootstrap-fileupload.css') }}" />

        <!--flaty css styles-->
        <link rel="stylesheet" href="{{ url('/web_support/css/flaty.css') }}">
        <link rel="stylesheet" href="{{ url('/web_support/css/flaty-responsive.css') }}">

        <link rel="stylesheet" href="{{ url('/web_support/assets/jquery-ui/jquery-ui.min.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ url('/web_support/css/sweetalert.css') }}" />

        <link rel="stylesheet" type="text/css" href="{{ url('/web_support/assets/bootstrap-switch/static/stylesheets/bootstrap-switch.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ url('/web_support/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" />

        <link rel="stylesheet" type="text/css" href="{{ url('/web_support/css/select2.min.css') }}" />

        <!-- Auto load email address -->
        <link rel="stylesheet" type="text/css" href="{{ url('/web_support/assets/chosen-bootstrap/chosen.min.css') }}" />

        <!--basic scripts-->
        <script src="{{ url('/web_support/js/sweetalert.min.js') }}"></script>

        <!-- This is custom js for sweetalert messages -->
        <script type="text/javascript" src="{{ url('/web_support/js/sweetalert_msg.js') }}"></script>
        <!-- Ends -->
    
        <script>window.jQuery || document.write('<script src="{{ url('/web_support/assets/jquery/jquery-2.1.4.min.js') }}"><\/script>')</script>
        
        <script src="{{ url('/web_support/assets/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ url('/web_support/js/select2.min.js') }}"></script>
        <link rel="stylesheet" type="text/css" href="{{ url('/web_support/assets/font-awesome/css/font-awesome-animation.min.css') }}" />
        <script src="{{ url('/web_support/js/image_validation.js') }}"></script>
        <link rel="stylesheet" type="text/css" href="{{ url('/web_admin/assets/data-tables/latest/dataTables.bootstrap.min.css') }}">

        <style type="text/css">
            .pagination, .dataTables_filter {
                float: right !important;
                }
                .btn-custom
                {
                    background-color: #17181B!important; 
                }
                .btn-cancel
                {
                   background-color: #D3D3D3!important;                   
                }
                .btn-preview
                {
                   background-color: #FF0000!important;                   
                }
        </style>
<link rel="stylesheet" type="text/css" href="{{ url('/web_support/assets/data-tables/latest/dataTables.bootstrap.min.css') }}">
        <script>
             function isNumberKey(evt) {
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                
                if (charCode == 190 || charCode == 46 ) 
                  return true;
                
                if (charCode > 31 && (charCode < 48 || charCode > 57 )) 
                return false;
                
                return true;
              }
        </script>  

    </head>

    <body class="{{ support_body_color() }}">
    <?php
        $support_path = config('app.project.support_panel_slug');
    ?>
        

        <!-- BEGIN Navbar -->
        <div id="navbar" class="navbar {{ support_navbar_color() }}">
            <button type="button" class="navbar-toggle navbar-btn collapsed" data-toggle="collapse" data-target="#sidebar">
                <span class="fa fa-bars"></span>
            </button>
            <a class="navbar-brand" href="#">
                <small>
                    <i class="fa fa-desktop"></i>
                    {{ config('app.project.name') }} support
                </small>
            </a>

            <!-- BEGIN Navbar Buttons -->
            <ul class="nav flaty-nav pull-right">
                <li class="hidden-xs">
                    
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell anim-swing"></i>
                        <span class="badge badge-important notifyCount">{{$notification_count}}</span>
                        {{-- <span class="badge badge-important notifyCount">{{ 0 }}</span> --}}
                    </a>

                    <!-- BEGIN Notifications Dropdown -->
                    <ul class="dropdown-navbar dropdown-menu">
                        <li class="nav-header">
                            <i class="fa fa-warning"></i>
                             <span class="notifyCount">{{$notification_count}}</span> Notifications
                           {{--  <span class="notifyCount">{{ 0 }}</span> Notifications --}}
                        </li>

                        
                        <li class="notify">
                            <a href="{{ url('/')}}/{{ config('app.project.support_panel_slug') }}/notification">
                                <i class="fa fa-comment orange"></i>
                                <p>New Notification</p>
                                <span class="badge badge-warning notifyCount">{{$notification_count}}</span>
                                {{-- <span class="badge badge-warning notifyCount">{{ 0 }}</span> --}}
                            </a>
                        </li>
                    </ul>
                    <!-- END Notifications Dropdown -->
                </li>

              
                <!-- BEGIN Button Notifications -->
               
                <!-- END Button Messages -->

                <!-- BEGIN Button User -->

                <li class="user-profile">
                    <a data-toggle="dropdown" href="#" class="user-menu dropdown-toggle">
                        <?php 
                        $profile_img =  isset($shared_web_support_details['profile_image'])  ? $shared_web_support_details['profile_image'] : "";
                        ?> 
                    <img class="nav-user-photo" src="{{ isset($profile_img) && $profile_img!=''?url(config('app.project.img_path.support_profile_images').$profile_img):url('/uploads/default-profile.png')  }}" alt="">
                        <span class="hhh" id="user_info">
                          Welcome {{ $shared_web_support_details['user_name'] or '' }}
                        </span>
                        <i class="fa fa-caret-down"></i>
                    </a>

                    <!-- BEGIN User Dropdown -->
                    <ul class="dropdown-menu dropdown-navbar" id="user_menu">
                        <li>
                            <a href="{{ url($support_panel_slug)}}/profile/change_password" >
                                <i class="fa fa-key"></i>
                                Change Password
                            </a>    
                        </li>    
                        <li class="divider"></li>

                        <li>
                             <a href="{{ url('/').'/'.$support_path }}/logout "> 
                                <i class="fa fa-power-off"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                    <!-- BEGIN User Dropdown -->
                </li>
                <!-- END Button User -->
            </ul>
            <!-- END Navbar Buttons -->
        </div>
        <!-- END Navbar -->
        
        <!-- BEGIN Container -->
        {{ csrf_field() }}
        <div class="container {{ support_sidebar_color() }}" id="main-container">


<script type="text/javascript">

    SITE_URL = "{{url('/')}}";
    SITE_SUPPORT_URL = "{{url(config('app.project.support_panel_slug'))}}";

    var to_user_id  = 1;
    var token = $('input[name="_token"]').val();

    $(document).ready(function(){
        setInterval(function(){
            if(token != ''){
                $.ajax({
                    'url':SITE_SUPPORT_URL+'/notification/get_notifications_count',                    
                    'type':'post',
                    'data':{_token:token},
                    success:function(res)   
                    {
                        if(res.status == 'success') {
                            $('.notifyCount').html(res.count);
                        }
                        else {
                            location.reload();
                        }
                    }
                });
            }
        },5000);
    });
</script>
   