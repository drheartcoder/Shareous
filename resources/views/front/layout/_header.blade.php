<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="{{$meta_description or ''}}" />
    <meta name="keywords" content="{{$meta_keyword or ''}}" />
    <meta name="title" content="{{$meta_title or ''}}" />
    <meta name="author" content="" />
    <title>{{config('app.project.name')}} | {{$page_title or ''}}</title>
    <!-- ======================================================================== -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('/front/images/favicon.png')}}">
    <!-- Bootstrap CSS -->
    <link href="{{url('/front/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!--font-awesome-css-start-here-->
    <link href="{{url('/front/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <!--Custom Css-->
    <link href="{{url('/front/css/vacationhomerental.css')}}" rel="stylesheet" type="text/css" />
    <!-- Datepicker-->
    <link href="{{url('/front/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <!--    Flexslider -->
    <link href="{{url('/front/css/style.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('/front/css/sweetalert.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{url('/front/css/easy-responsive-tabs.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('/front/css/easy-responsive-tabs.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('/front/css/timepicker.css')}}" rel="stylesheet" type="text/css" />
    <!--multiselect css-->
    <!-- <link href="{{url('/front/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css" /> -->
    <link href="{{url('/front/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <!--Main JS-->
    <script type="text/javascript" src="{{url('/front/js/jquery-1.11.3.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/front/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/front/js/easyResponsiveTabs.js')}}"></script>
    <script type="text/javascript" src="{{url('/front/js/loader.js')}}"></script>
    <script type="text/javascript" src="{{url('/front/js/general.js')}}"></script>
    <script src="{{url('/front/js/bootstrap-timepicker.js')}}" type="text/javascript"></script>

    <link href="{{url('/front/css/custom.css')}}" rel="stylesheet" />
    <link href="{{url('/front/css/bootstrap-modal.css')}}" rel="stylesheet" />
    <link href="{{url('/front/css/loading_animate.css')}}" rel="stylesheet" />

    <link href="{{url('/front/css/kendo.common-material.min.css')}}" rel="stylesheet" />
    <link href="{{url('/front/css/kendo.material.min.css')}}" rel="stylesheet" />
    <script type="text/javascript" language="javascript" src="{{url('/front/js/bootstrap-datepicker.min.js')}}"></script> 
    <script type="text/javascript" language="javascript" src="{{url('/front/js/front-common.js')}}"></script> 

    <link href="{{url('/front/css/jquery.mCustomScrollbar.css')}}" rel="stylesheet" />
    <link href="{{url('/front/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{url('/front/css/swiper.min.css')}}">
    <!--Multiselect with checkbox-->
    <script type="text/javascript" src="{{url('/front/js/select2.full.js')}}"></script>
    <script type="text/javascript" src="{{url('/front/js/T-validations/masterT-validations.js')}}"></script>
    
    <script type="text/javascript">
        var SITE_URL    = "{{url('/')}}";     
        var csrf_token  = "{{ csrf_token() }}";
    </script>
    <!-- <script type="text/javascript" src="{{ url('/front/js/fb_auth.js') }}"></script> -->
    <script src="https://wchat.freshchat.com/js/widget.js"></script>
</head>

<body>
    <div id="main"></div>
    <!--Header section start here-->
    <?php 
        $is_login     = validate_login('users');
        $request_seg  = Request::segment(1);
        $request_seg2 = Request::segment(2);
    ?>
    <header @if($request_seg == '') class="header-homes" @else class="inner-header" @endif>  <!--- @if(!$is_login && $request_seg !='property' && $request_seg2 != 'view') class="header-homes" @endif -->
        {{--  @if($request_seg =='property' && $request_seg2 == 'view') sticky @endif --}}
        <div id="header-home"></div>

        <div class="header header-home">
            <div class="logo-block">
                <a href="{{url('/')}}">
                    <img src="{{url('/')}}/front/images/logo.png" alt="" class="main-logo outer-logo-change" />
                    <img src="{{url('/')}}/front/images/logo-inner.png" alt="" class="main-logo inner-logo-change" />
                </a>
            </div>  
            
            
            <div class="logo-block-inner">
                <a href="{{url('/')}}">
                   <img src="{{url('/')}}/front/images/logo.png" alt="" class="main-logo outer-logo-inner" />
                   <img src="{{url('/')}}/front/images/logo-inner.png" alt="" class="main-logo inner-logo-change" />
                </a>
            </div>
            <span class="menu-icon" onclick="openNav()">&#9776;</span>
            <div class="innr-head-icon-main">
                <div class="innr-head-mn">
                    <a href="javascript:void(0)" class="inner-checkbox mobile-bell">
                        <i class="fa fa-bell"></i>
                        <span class="count-no"></span>
                    </a>
                </div>
            </div>
            
            <!--Menu Start-->
            <div id="mySidenav" class="sidenav">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <div class="banner-img-block">
                 <img src="{{url('/')}}/front/images/logo.png" alt="Logo" />
                    <div class="img-responsive-logo"></div>
                </div>

                <style type="text/css">
                    /*.currency_color_white { color: #FFF !important;  }*/
                    /*.currency_color_grey { color: #3c484c !important;  }*/
                </style>

                <ul class="min-menu">
                <?php $class = ''; ?>
                    @if($is_login )
                        <?php $class = 'currency_color_grey'; ?>

                        <li class="inner-icns-check displaynone after-login-menu">
                            <a href="{{url('/notifications')}}" class="bell-inner-checkbox">
                                <i class="fa fa-bell"></i>
                                <?php
                                    $notify_count = isset($notification_count) ? $notification_count : '';
                                    if ($notify_count == 0) {
                                        $notify_count = '0';
                                    }
                                    if(isset($notify_count) && $notify_count >99){
                                        $notify_count = '99+';
                                    }
                                ?>
                                <span class="count-no <?php if($notify_count > '1'){ echo 'active'; } ?>" id="notification-count-id">{{$notify_count}}</span>
                            </a>
                        </li>
                        <?php
                            if(isset($user_details['display_name']) && !empty($user_details['display_name'])) {
                                $user_name = $user_details['display_name'];
                            } else if(isset($user_details['user_name']) && !empty($user_details['user_name'])) {
                                $user_name = $user_details['user_name'];
                            } else {
                                $user_name = '';
                            }
                        ?>                        
                        <li class="sub-menu after-login-menu">
                            <div class="host-iser">
                                @if($is_login && Session::get('user_type') == '1') Guest
                                @elseif($is_login && $user_details['user_type'] == '4') Host
                                @endif
                            </div>
                            <a href="javascript:void(0)">{{ $user_name }}</a>
                            <ul class="su-menu">
                                @if($is_login && Session::get('user_type') == '1' && $user_details['user_type']=='4')
                                    <li><a class="change_user_type" >Goto Host Dashboard</a></li>
                                @elseif($is_login && Session::get('user_type') == '4')
                                    <li><a class="change_user_type" >Goto Guest Dashboard</a></li>
                                @endif
                                <li><a href="{{url('/profile')}}">Profile</a></li>
                                @if($user_details['social_login']=='no')
                                    <li><a href="{{url('/profile/change_password')}}">Change Password</a></li>
                                @endif
                                <li><a href="{{url('/logout')}}">Logout</a></li>
                            </ul>
                            
                        </li>
                        
                        @if($is_login && $user_details['user_type']=='1' && $in_process_host == 'no')
                            <li class="btn-become btn-homebecome"><a data-toggle=modal data-target="#host-verification">Become a Host</a></li>
                        @elseif($is_login && $user_details['user_type']=='1' && $in_process_host == 'yes')
                            <li class="btn-become btn-homebecome"><a id="processing_host">Become a Host</a></li>
                        @endif

                    @else
                        <?php if($request_seg !='property' && $request_seg2 != 'view') {
                                $class = 'currency_color_white';
                            } else {
                                $class = 'currency_color_grey';
                            } ?>
                        <li class="before-login-menu"><a href="{{url('/about-us')}}">About Us</a></li>
                        <li class="before-login-menu"><a href="{{url('/signup')}}">Sign Up</a></li>
                        <li class="before-login-menu"><a href="{{url('/login')}}">Log In</a></li>
                    @endif

                    @if(isset($arr_currency) && !empty($arr_currency))
                        <li class="currency-dropdown">
                            <?php 
                                if(null !== Session::get('get_currency') && !empty(Session::get('get_currency'))) {
                                    $selected_currency = Session::get('get_currency');
                                } else {
                                    Session::put('get_currency', 'INR');
                                    Session::put('get_currency_icon', '<i class="fa fa-inr" aria-hidden="true"></i>');
                                    store_currency_session('INR');
                                    $selected_currency = 'INR';
                                }
                            ?>
                            <select id="select_currency" class="{{ $class }}">
                                @foreach($arr_currency as $currency)
                                    <option value="{{ $currency['currency_code'] }}" @if($currency['currency_code'] == $selected_currency) selected @endif >{{ $currency['currency_code'] }}</option>
                                @endforeach
                            </select>
                            <span class="currency-drop-arrow-down"><i class="fa fa-angle-down"></i></span>
                        </li>
                    @endif
                </ul>

                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
        
        <div class="blank-div for-respon"></div>

        <!--Host contact popup start here-->
        <div class="host-contact-popup becone-a-host">
            <div class="popup-inquiry-form">
                <div id="host-verification" class="modal fade" data-backdrop="static" role="dialog">
                    <div class="modal-dialog payment-popu-main">
                        <div class=modal-content>
                            <div class="modal-header black-close">
                                <button type=button class=close data-dismiss=modal>
                                    <span class="contact-left-img popup-close"></span>
                                </button>
                                <h4 class=modal-title>Host Verification</h4>
                            </div>
                            <div class=modal-body>
                                <div class="row">

                                    <form id="form_verification" action="{{url('/verification/post_documets')}}" method="post" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="col-sm-6 col-md-6 col-lg-6">
                                            <div class="my-id-proof-main">
                                                <div class="my-id-proof-head">ID Proof</div>
                                                <div class="my-account-profile-img-block">
                                                    <div class="pro-img"><img src="{{ url('/') }}/front/images/plus-img-id-proof.jpg" class="img-responsive img-preview1" alt="" /></div>
                                                    <div class="update-pic-btns">
                                                        <input id="logo-id" name="id_proof" type="file" class="attachment_upload" data-rule-required="true">
                                                        <div id="logo-id-error" class="error"></div>
                                                    </div>
                                                </div>
                                                <div class="clerfix"></div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6 col-lg-6">
                                            <div class="my-id-proof-head">Photo</div>
                                            <div class="my-account-profile-img-block">
                                                <div class="pro-img"><img src="{{url('/')}}/front/images/plus-img.jpg" class="img-responsive img-preview12" alt="" /></div>
                                                <div class="update-pic-btns">
                                                    <input id="logo-id1" name="photo" type="file" class="attachment_upload" data-rule-required="true">
                                                    <div id="logo-id1-error" class="error"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-12"><span class="badge" style="background-color: red;"><i class="fa fa-exclamation-triangle" style="color: white;"></i> Note: </span> Only jpg | png | jpeg | pdf | doc | docx type of files alllowed<br />
                                        <div style="color: red; font-size: 13px;"id="err_other_image"></div></div>

                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <div class="check-box inline-checkboxs singup-tp space-left-modal">
                                                <input id="host_terms" class="filled-in" type="checkbox" value="true" name="terms" data-rule-required="true" data-msg-required="Please accept Terms and Conditions"/>
                                                <label for="host_terms">Accept our <a href="{{url('terms-conditions')}}" target="_blank"><b>Terms and Condition</b></a></label>
                                                <div class="error">{{$errors->first('terms')}}</div>
                                            </div>
                                        </div>
                                        <div class="clerfix"></div>
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <div class="sign-up-popu-btn">
                                                <button type="submit" id="verification_form_submit_btn" class="login-btn" >Verify</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class=clearfix></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>   
        <!--Host contact popup end here-->
</header>

        <!--new profile image upload demo script start-->
        <script type="text/javascript">
            $(document).ready(function ()
            {
                var brand = document.getElementById('logo-id');
                brand.className = 'attachment_upload';
                brand.onchange = function () {
                    //document.getElementById('fakeUploadLogo').value = this.value.substring(12);
                };

                // Source: http://stackoverflow.com/a/4459419/6396981
                function readURL(input) {
                    if (input.files && input.files[0]) 
                    {
                        var reader = new FileReader();
                        var size = input.files[0].size;
                        var file_ext = (input.files[0].name).substr( (input.files[0].name.lastIndexOf('.') +1) ).toLowerCase();
                        console.log(size);
                        
                        if(size < 1000) {
                            //swal('File size must be equal or more than 1 KB');
                            $("#logo-id-error").html('File size must be equal/more than 1KB.')
                            return false;
                        }
                        if(size > 2097152) {
                            //swal('File size must not be more than 2 MB');
                            $("#logo-id-error").html('File size must not be more than 2MB.')
                            return false;
                        }
                        reader.onload = function (e) {
                            // console.log(jQuery.inArray(file_ext, ['jpg', 'jpeg', 'png']));
                            if (jQuery.inArray(file_ext, ['jpg', 'jpeg', 'png']) != -1) {
                                $('.img-preview1').attr('src', e.target.result);
                            } else {
                                $('.img-preview1').attr('src', SITE_URL+'/front/images/document-logo.jpg');
                            }

                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }
                $("#logo-id").change(function () {
                    readURL(this);
                });

                $(".change_user_type").click(function(){
                    var token = $('input[name="_token"]').val();

                    $.ajax({
                        'url':SITE_URL+'/profile/switch_user_type',                    
                        'type':'post',
                        'data':{_token:token},
                        success:function(response){
                            if(response.status == 'success') {
                                window.location.href = "{{ url('/') }}/profile";
                            }
                        }
                    });
                });

                $("#processing_host").click(function(){
                    swal({ title: "Processing...!", text: "Your request is already in process please wait for verification process to complete." });
                });
            });
        </script>

<script type="text/javascript">
    $(document).ready(function () {
        var brand = document.getElementById('logo-id1');
        brand.className = 'attachment_upload';
        brand.onchange = function () {
            //document.getElementById('fakeUploadLogo').value = this.value.substring(12);
        };

        // Source: http://stackoverflow.com/a/4459419/6396981
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader   = new FileReader();
                var size     = input.files[0].size;
                var file_ext = (input.files[0].name).substr( (input.files[0].name.lastIndexOf('.') +1) ).toLowerCase();

                if(size < 1000) {
                    //swal('File size must be equal or more than 1 KB');
                    $("#logo-id1-error").html('File size must be equal/more than 1KB.')
                    return false;
                }
                if(size > 2097152) {
                    //swal('File size must not be more than 2 MB');
                    $("#logo-id1-error").html('File size must not be more than 2MB.')
                    return false;
                }

                reader.onload = function (e) {
                    if (jQuery.inArray(file_ext, ['jpg', 'jpeg', 'png']) != -1) {
                        $('.img-preview12').attr('src', e.target.result);
                    } else {
                        $('.img-preview12').attr('src', SITE_URL+'/front/images/document-logo.jpg');
                    }
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
        
        $("#logo-id1").change(function () {
            readURL(this);
        });

    });
</script>

<!--Sticky Menu-->
<script type="text/javascript">
    $(window).scroll(function () {
        var scroll_top = $(this).scrollTop();
        if (scroll_top > 46) { //height of header
            $('.header').addClass('sticky');
        } else {
            $('.header').removeClass('sticky');
        }
    });
    
    $(".attachment_upload").change(function() {
        var val = $(this).val();

        switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
            case 'jpg': 
            case 'png':
            case 'jpeg':
            case 'pdf':
            case 'doc':
            case 'docx':
                $(this).next().html("");
                break;
            default:
                $(this).val('');
                $('.error').html("");
                $(this).next().html("Please upload valid image");
                break;
      }
  });
    $(document).ready(function() {
        jQuery('#form_verification').validate({
            ignore: [],
            errorElement: 'div',
            highlight: function(element) { },
            errorPlacement: function(error, element) {
                error.appendTo(element.parent());
            }
        }); 
    });
</script>
<!--Sticky Menu-->
<div class="blank-div"></div>

<!-- Change Currency for website starts -->
<script type="text/javascript">
    $(document).ready(function(){
        $("#select_currency").change(function(){
            var select_currency = $("#select_currency").val();

            if ($.trim(select_currency) != '') {
                $.ajax({
                    'url': '{{ url("/") }}/set_currency/'+select_currency,
                    'type': 'get',
                    success: function(response) {
                        if (response.status == 'success') {
                            location.reload();
                        }
                    }
                });
            }
        });
    });
</script>
<!-- Change Currency for website ends -->
@if( auth()->guard('users')->user() != null )
<script type="text/javascript">
    var SITE_URL = "{{ url('/') }}";
    var token    = $('input[name="_token"]').val();

    $(document).ready(function() {
        setInterval(function() {
            if (token != '') {
                $.ajax({
                    'url':SITE_URL+'/notifications/get_notifications_count',
                    'type':'post',
                    'data':{_token:token},
                    success:function(res) {
                        if ($.trim(res) != 'logout') {
                            if($.trim(res) > 0) {
                                if($.trim(res) > '99') {
                                    $('#notification-count-id').addClass('active');
                                    $('#notification-count-id').html('99+');
                                }
                                else {
                                    $('#notification-count-id').addClass('active');
                                    $('#notification-count-id').html(res);
                                }
                            } else {
                                $('#notification-count-id').removeClass('active');
                            }
                        } else {
                            window.location.href = SITE_URL;
                        }                
                    }
                });
            } else {
                window.location.href = SITE_URL;
            }
        },5000);
    });
</script>
@endif