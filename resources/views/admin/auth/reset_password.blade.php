<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{ $site_settings['site_name'] or '' }} Reset Password</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <!--base css styles-->
    <link rel="stylesheet" href="{{ url('/web_admin/assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('/web_admin/assets/font-awesome/css/font-awesome.min.css') }}">

    <!--page specific css styles-->
    <link rel="stylesheet" type="text/css" href="{{ url('/web_admin/assets/chosen-bootstrap/chosen.min.css') }}">

    <!--flaty css styles-->
    <link rel="stylesheet" href="{{ url('/web_admin/css/flaty.css') }}">
    <link rel="stylesheet" href="{{ url('/web_admin/css/flaty-responsive.css') }}">

    <link rel="shortcut icon" href="{{ url('/favicon.png') }}">

    <style type="text/css">
        .error
        {
            color: red;
        }
        .login-page:before{background: none!important}
        #form-reset_password
        {
            box-shadow    : 13px 21px 70px #000;
            border-radius : 15px;
        }
        
        .btn-lgn
        {
            background-color: #3f65a3!important;
            border-radius: 10px;
        }

        .btn-lgn:hover
        {
            background-color: #275980!important;
        }
    </style>

</head>

<body class="login-page" style='background-image: url("{{url('/web_admin/images/5.jpg')}}") !important;background-repeat:repeat-x;background-size: 100%;' id="background">
    <!-- BEGIN Main Content -->
    <div class="login-wrapper">
        <!-- BEGIN Login Form -->
        <form name="form-reset_password" id="form-reset_password" method="POST" action="{{url($admin_panel_slug.'/password_reset')}}">
            {{ csrf_field() }}

            <center>
                <h4> RESET PASSWORD</h4>
                <br/>
            </center>
            <hr/>
            @if(Session::has('success'))
            <div class="alert alert-success alert-dismissable fade in login-msg">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ Session::get('success') }}
            </div>
            @endif
            @if(Session::has('error'))
            <div class="alert alert-danger alert-dismissable fade in login-msg">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ Session::get('error') }}
            </div>
            @endif
            
            <div class="form-group ">
                <div class="controls">

                    <input type="password" name="password" class="form-control" id="new_password" data-rule-required="true" data-rule-minlength="6" placeholder="Password">
                    <span class="error">{{ $errors->first('password') }} </span>
                </div>
            </div>
            <div class="form-group">
                <div class="controls">
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" data-rule-required="true" data-rule-minlength="6" data-rule-equalto="#new_password" placeholder="Confirm Password">
                    <span class="error">{{ $errors->first('password_confirmation') }} </span>
                </div>
            </div>
            <div class="form-group">
                <div class="controls">
                    <input type="hidden" name="token" value="{{ $token or ''}}" />
                    <input type="hidden" name="email" value="{{ $password_reset['email'] or ''}}" />
                    <button type="submit" class="btn btn-info form-control btn-lgn">Change Password</button>
                </div>
            </div>
        </form>
    </div>
    <!-- END Login Form -->

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
    <script src="{{ url('/web_admin/js/jquery.validate.min.js') }}"></script>
    <script  src="{{ url('/web_admin/js/additional-methods.js') }}"></script>
    <script src="{{ url('/web_admin/js/flaty.js') }}"></script>
    <script src="{{ url('/web_admin/js/flaty-demo-codes.js') }}"></script>
    <script src="{{ url('/web_admin/js/validation.js') }}"></script>


    <script type="text/javascript">
        $(function()
        {
            applyValidationToFrom($("#form-reset_password"))
        });
        $(document).ready(function() 
        { 
          jQuery('#form-reset_password').validate({
            ignore: [],
            rules: {
                  "password": {
                    pattern: /(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^])(?=.*\d).*/,
                    minlength:6,
                }
            },
            messages: {
                password: {
                    pattern: "Your password must contain at least (1) lowercase and (1) uppercase and (1) special character and (1) letter, It should be greater than or equal to 6 character",

                },
            }
        });
      });

  </script>
</body>
</html>
