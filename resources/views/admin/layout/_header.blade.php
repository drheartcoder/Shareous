<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{ isset($page_title)?$page_title:"" }} - {{ config('app.project.name') }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('/web_admin/img/logo.png')}}">
    <!--base css styles-->
    <link rel="stylesheet" href="{{ url('/web_admin/assets/bootstrap/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ url('/web_admin/css/new-font-awesome.min.css') }}">

    <!--page specific css styles-->
    <link rel="stylesheet" type="text/css" href="{{ url('/web_admin/assets/bootstrap-fileupload/bootstrap-fileupload.css') }}" />

    <!--flaty css styles-->
    <link rel="stylesheet" href="{{ url('/web_admin/css/flaty.css') }}">
    <link rel="stylesheet" href="{{ url('/web_admin/css/flaty-responsive.css') }}">

    <link rel="stylesheet" href="{{ url('/web_admin/assets/jquery-ui/jquery-ui.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ url('/web_admin/css/sweetalert.css') }}" />

    <link rel="stylesheet" type="text/css" href="{{ url('/web_admin/assets/bootstrap-switch/static/stylesheets/bootstrap-switch.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('/web_admin/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" />

    <link rel="stylesheet" type="text/css" href="{{ url('/web_admin/css/select2.min.css') }}" />

    <link rel="stylesheet" type="text/css" href="{{ url('/web_admin/css/custom.css') }}" />
{{-- 
    <link rel="stylesheet" type="text/css" href="{{ url('/web_admin/css/jquery-clockpicker.min.css') }}" />

    <link rel="stylesheet" type="text/css" href="{{ url('/web_admin/css/jquery.timepicker.css') }}" /> --}}

    <!-- Auto load email address -->
    <link rel="stylesheet" type="text/css" href="{{ url('/web_admin/assets/chosen-bootstrap/chosen.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('/web_admin/css/bootstrap-datetimepicker.min.css') }}" />

    <!--basic scripts-->
    <script src="{{ url('/web_admin/js/sweetalert.min.js') }}"></script>    

    <!-- This is custom js for sweetalert messages -->
    <script type="text/javascript" src="{{ url('/web_admin/js/sweetalert_msg.js') }}"></script>
    <!-- Ends -->
    
    <script>window.jQuery || document.write('<script src="{{ url('/web_admin/assets/jquery/jquery-2.1.4.min.js') }}"><\/script>')</script>
    <script src="{{ url('/web_admin/js/moment.min.js') }}"></script>
    <script src="{{ url('/web_admin/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{url('web_admin/assets/bootstrap-timepicker/js/bootstrap-timepicker.js')}}"></script>

    <script type="text/javascript" src="{{url('web_admin/assets/clockface/js/clockface.js')}}"></script>
    <script type="text/javascript" src="{{url('web_admin/assets/bootstrap-timepicker/js/bootstrap-timepicker.js')}}"></script>
    <script type="text/javascript" src="{{url('web_admin/assets/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>

    <script src="{{ url('/web_admin/assets/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ url('/web_admin/js/select2.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ url('/web_admin/assets/font-awesome/css/font-awesome-animation.min.css') }}" />
    <script src="{{ url('/web_admin/js/image_validation.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <style type="text/css">
    .pagination, .dataTables_filter {
        float: right !important;
    }
    .btn-custom {
        background-color: #3C5877!important;
    }
    .btn-cancel {
        background-color: #D3D3D3!important;
    }
    .btn-preview {
        background-color: #FF0000!important;
    }
    .dropdown-menu .divider {
        margin: 0;
    }
    .dropdown-menu > li > a {
        padding: 12px 5px;
    }
</style>
<link rel="stylesheet" type="text/css" href="{{ url('/web_admin/assets/data-tables/latest/dataTables.bootstrap.min.css') }}">

<script>
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;

        if (charCode == 190 || charCode == 46 ) 
            return true;

        if (charCode > 31 && (charCode < 48 || charCode > 57 )) 
            return false;

        return true;
    }

    SITE_URL = "{{url('/')}}";
    SITE_ADMIN_URL = "{{url(config('app.project.admin_panel_slug'))}}";
</script>  

</head>

<body class="{{ theme_body_color() }}">
    <?php $admin_path = config('app.project.admin_panel_slug'); ?>
    <!-- END Theme Setting -->

    <!-- BEGIN Navbar -->
    <div id="navbar" class="navbar {{ theme_navbar_color() }}">
        <button type="button" class="navbar-toggle navbar-btn collapsed" data-toggle="collapse" data-target="#sidebar">
            <span class="fa fa-bars"></span>
        </button>
        <a class="navbar-brand" href="#">
            <small>
                <i class="fa fa-desktop"></i>
                {{ config('app.project.name') }} Admin
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
                        {{-- <span class="notifyCount">{{ 0 }}</span> Notifications --}}
                    </li>

                    <li class="notify">
                        <a href="{{ url('/')}}/{{ config('app.project.admin_panel_slug') }}/notification">
                            <i class="fa fa-comment orange"></i>
                            <p>New Notification</p>
                            <span class="badge badge-warning notifyCount">{{$notification_count}}</span>
                            {{-- <span class="badge badge-warning notifyCount">{{ 0 }}</span> --}}
                        </a>
                    </li>
                </ul>
                <!-- END Notifications Dropdown -->
            </li>
            <!-- END Button Messages -->

            <!-- BEGIN Button User -->
            <li class="user-profile">
                <a data-toggle="dropdown" href="#" class="user-menu dropdown-toggle">
                    <?php
                    $profile_img =  isset($shared_web_admin_details['profile_image'])  ? $shared_web_admin_details['profile_image'] : ""; 
                    ?> 
                    <img class="nav-user-photo" src="{{ isset($profile_img) && $profile_img!=''?url(config('app.project.img_path.admin_profile_images').$profile_img):url('/uploads/admin_profile_image/default.png')  }}" alt="">
                    <span class="hhh" id="user_info">
                      Welcome {{$shared_web_admin_details['user_name'] or ''}}
                  </span>
                  <i class="fa fa-caret-down"></i>
              </a>

              <!-- BEGIN User Dropdown -->
              <ul class="dropdown-menu dropdown-navbar" id="user_menu">
                <li>
                    <a href="{{ url($admin_panel_slug)}}/profile/change_password" >
                        <i class="fa fa-key"></i>
                        Change Password
                    </a>    
                </li>    
                <li class="divider"></li>

                <li>
                   <a href="{{ url('/').'/'.$admin_path }}/logout "> 
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
<div class="container {{ theme_sidebar_color() }}" id="main-container">
    <script type="text/javascript">
        var to_user_id  = 1;
        var token = $('input[name="_token"]').val();

        $(document).ready(function() {
            setInterval(function() {
                if(token != '') {
                    $.ajax({
                        'url':SITE_ADMIN_URL+'/notification/get_notifications_count',
                        'type':'post',
                        'data':{_token:token},
                        success:function(res) {
                            if(res.status == 'success'){
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

    <!-- seraching leftbar menu starts-->
    <script >
        $(document).ready(function() {
            $('#search_menu').keyup(function() {
                searchLeftbar($(this).val());
            });
        });

        function searchLeftbar(inputVal) {
            var table = $('#leftbar_menu_list');
            var count = 0;

            table.find('li').each(function(index, row) {
                var allCells = $(row).find('a');
                if (allCells.length > 0) {
                    var found = false;
                    allCells.each(function(index, li) {
                        var regExp = new RegExp(inputVal, 'i');
                        if (regExp.test($(li).text())) {
                            found = true;
                            return false;
                        }
                    });

                    if (found == true) {
                        $(row).show();
                        count = count  + 1;
                    } else {
                        $(row).hide();
                    }
                }
            });
        }
    </script>
    <!--seraching leftbar menu ends-->
