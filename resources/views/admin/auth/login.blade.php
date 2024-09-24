<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{ $site_settings['site_name'] or '' }} Admin Login</title>

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <!--base css styles-->
    <link rel="stylesheet" href="{{ url('/web_admin/assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('/web_admin/assets/font-awesome/css/font-awesome.min.css') }}">

    <!--page specific css styles-->
    <link rel="stylesheet" type="text/css" href="{{ url('/web_admin/assets/chosen-bootstrap/chosen.min.css') }}">
    <!--flaty css styles-->
    <link rel="stylesheet" href="{{ url('web_admin/css/flaty.css') }}">
    <link rel="stylesheet" href="{{ url('web_admin/css/flaty-responsive.css') }}">
    <link rel="shortcut icon" href="{{ url('/img/favicon.png') }}">

    <style type="text/css">
        .error
        {
            color: red;
        }

        .login-page:before{background: none!important}

        #form-login , #form-forgot
        {
            box-shadow    : 13px 21px 70px #000;
            border-radius : 15px;
        }

        #form-login > h4 , #form-forgot > h4
        {
            text-align: center;
        }
        
        .btn-lgn
        {
            background-color: #3f65a3!important;
            border-radius: 10px!important;
        }

        .btn-lgn:hover
        {
            background-color: #275980!important;
        }

        .login-msg 
        {
            border-radius: 11px;
            text-align: center;
        }
    </style>

</head>


<body class="login-page" style='background-image: url("{{url('/web_admin/images/5.jpg')}}") !important;background-repeat:repeat-x;background-size: 100%;' id="background">

    <!-- BEGIN Main Content -->
    <div class="login-wrapper">
        <!-- BEGIN Login Form -->
        <form name="form-login" id="form-login" method="POST" action="{{url($admin_panel_slug.'/validate_login')}}" @if(Session::has('success_password') | Session::has('error_password')) style="display:none" @endif>
            {{ csrf_field() }}

            <h4>LOGIN TO YOUR ACCOUNT</h4>
            <hr/>

        @if(Session::has('success_password'))
           <div class="alert alert-success alert-dismissible login-msg">
            {{ Session::get('success_password') }}
        </div>
        @endif
        @if(Session::has('success'))
           <div class="alert alert-success alert-dismissible login-msg">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ Session::get('success') }}
        </div>
        @endif
          @if(Session::has('error'))
            <div class="alert alert-danger alert-dismissible login-msg">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ Session::get('error') }}
            </div>
          @endif
                
            <div class="form-group ">
                <div class="controls">
                    <input type="text" name="email" id="email" class="form-control" data-rule-required="true" data-rule-email="" placeholder="Email" value="{{old('email')}}">
                    <span class="error">{{ $errors->first('email') }} </span>
                </div>
            </div>
            <div class="form-group">
                <div class="controls">
                    <input type="password" name="password" id="password" class="form-control" data-rule-required="true" placeholder="Password">
                    <span class="error">{{ $errors->first('password') }} </span>
                </div>
            </div>
            <div class="form-group">
                <div class="controls">
                    <button  class="btn btn-danger form-control btn-lgn" >Sign In</button>        
                </div>
            </div>
            <hr/>
            <p class="clearfix">
                <a href="#" class="goto-forgot pull-left">Forgot Password?</a>
            </p>
        </form>
        <!-- END Login Form -->

        <!-- BEGIN Forgot Password Form -->
        <form id="form-forgot" action="{{ url($admin_panel_slug.'/forgot_password') }}" method="post" @if(Session::has('success_password') | Session::has('error_password')) style="display:block;" @else style="display:none;" @endif>
           {{ csrf_field() }}

           <h4>GET BACK YOUR PASSWORD</h4>
           <hr/>
           @if(Session::has('success_password'))
           <div class="alert alert-success alert-dismissible login-msg">
            {{ Session::get('success_password') }}
        </div>
        @endif
        @if(Session::has('error_password'))
        <div class="alert alert-danger alert-dismissible login-msg">
            {{ Session::get('error_password') }}
        </div>
        @endif
        <div class="form-group">
            <div class="controls">
                <input type="text" placeholder="Email" class="form-control" data-rule-required="true" data-rule-email="true" name="email"/>
                <span class="error">{{ $errors->first('email') }} </span>
            </div>
        </div>
        <div class="form-group">
            <div class="controls">
                <button type="submit" class="btn btn-danger form-control btn-lgn">Recover</button>
            </div>
        </div>
        <hr/>
        <p class="clearfix">
            <a href="#" class="goto-login pull-left">← Back to login form</a>
        </p>
    </form>
    <!-- END Forgot Password Form -->
</div>
<!-- END Main Content -->

<!--basic scripts-->

<script>window.jQuery || document.write('<script src="{{ url('/web_admin/assets/jquery/jquery-2.1.4.min.js') }}"><\/script>')</script>
<script>window.jQuery || document.write('<script src="{{ url('/web_admin/assets/jquery/jquery-2.1.4.min.js') }}"><\/script>')</script>
<script src="{{ url('/web_admin/assets/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ url('/web_admin/assets/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ url('/web_admin/assets/jquery-cookie/jquery.cookie.js') }}"></script>
<script type="text/javascript" src="{{ url('/web_admin/assets/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ url('/web_admin/assets/jquery-validation/dist/additional-methods.min.js') }}"></script>
<script type="text/javascript" src="{{ url('/web_admin/assets/chosen-bootstrap/chosen.jquery.min.js') }}"></script>

<!--flaty scripts-->
<script src="{{ url('/web_admin/js/flaty.js') }}"></script>
<script src="{{ url('/web_admin/js/flaty-demo-codes.js') }}"></script>
<script src="{{ url('/web_admin/js/validation.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() 
    {
        var show_password_reset = '';
        if (show_password_reset) 
        {
            goToForm('forgot');
        }
        jQuery('#form-login').validate({
            ignore: [],

        });
    });

    function goToForm(form)
    {
        $('.login-wrapper > form:visible').fadeOut(500, function(){
            $('#form-' + form).fadeIn(500);
        });
    }

    $(function() 
    {
        $('.goto-login').click(function(){
            goToForm('login');
        });
        $('.goto-forgot').click(function(){
            goToForm('forgot');
        });
        $('.goto-register').click(function(){
            goToForm('register');
        });

        applyValidationToFrom($("#form-login"))
        applyValidationToFrom($("#form-forgot"))
    });
    $("#form-forgot").submit(function(){
        if($("#form-forgot").valid()){

    var doc_height = $(document).height();
    var doc_width  = $(document).width();
    var spinner_html = "<img src='{{url('/web_admin/images/spin.gif')}}'/>";
     $("body").append("<div id='global_processing_overlay'><div class='sk-folding-cube'>"+spinner_html+"</div></div>");
     $("#global_processing_overlay").height(doc_height)
                                   .css({
                                     'opacity' : 0.9,
                                     'position': 'fixed',
                                     'top': 0,
                                     'left': 0,
                                     'background-color': '#e6e6e6',
                                     'width': '100%',
                                     'z-index': 2147483647,
                                     'text-align': 'center',
                                     'vertical-align': 'middle',
                                     'margin': 'auto',
                                     'padding-top': '15%',
                                   });                             
            // alert();
        }
    });

</script>
</body>
</html>