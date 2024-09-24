<?php
    $arr_facebook           = get_facebook_credential();
    $facebook_client_id     = $arr_facebook['facebook_client_id'];
    $facebook_client_secret = $arr_facebook['facebook_client_secret'];

    $arr_google             = get_google_credential();
    $google_client_id       = $arr_google['google_client_id'];
    $google_client_secret   = $arr_google['google_client_secret'];
?>

    @extends('front.layout.master')
    @section('main_content')

<?php
    $emailCookie = $checkedVal = '';
    $emailCookie = isset($_COOKIE['remember_me_user_name']) ? $_COOKIE['remember_me_user_name'] : '';

    if ($emailCookie != '') {
        $checkedVal = 'yes';
    } else {
        $checkedVal = 'no';
    }
?>

<script src='https://www.google.com/recaptcha/api.js'></script>

<div class="login-bg-main">
    <div class="login-boxs">
        <div class="row">
            
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 padding-o color-red-alpha">
                <h1>Login with Social <br> Media Account</h1>
                <a onclick="FBLogin()" type="submit" class="buttons-social fb-button"><i class="fa fa-facebook"></i> Login with Facebook</a>
                <a href="{{ url('/social_auth/twitter/init') }}" type="submit" class="buttons-social twitter-button"><i class="fa fa-twitter"></i> Login with Twitter</a>
                <a onclick="GPLogin()" type="submit" class="buttons-social google-plus-button"><i class="fa fa-google"></i> Login with Google</a>
                <div class="login-txts-sing">Don't have an Account? <a href="{{ url('/signup') }}">Sign Up Now!</a></div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 padding-o whiteboxs">
                <div class="login_box">
                    
                    <form id="frm_user_login" method="post" action="{{ url('/process_login') }}">
                        {{ csrf_field() }}
                        <div class="title_login">Login</div>
                        
                        @include('front.layout._flash_errors')
                        <div id="login_social_status"></div>

                        <div class="form-group">
                            <input id="user_name" type="text" name="user_name" data-rule-required="true" data-rule-maxlength="255" value="{{ $emailCookie }}"/>
                            <label for="user_name">User Name or Email id</label>
                        </div>

                        <div class="form-group">
                            <input id="pwd" type="password" name="password" data-rule-required="true" data-rule-minlength="6" value=""/>
                            <span class="calendar-icon" id="hide_password" style="cursor: pointer;"><i class="fa fa-eye"></i></span>
                            <span class="calendar-icon" id="show_password" style="display: none; cursor: pointer;"><i class="fa fa-eye-slash"></i></span> 
                            <label for="pwd">Password</label>
                        </div>

                        <div class="user_box1">
                            <div class="check-box inline-checkboxs">
                                <input id="filled-in-box2" class="filled-in" name="remember_me" type="checkbox" @if($checkedVal=='yes') checked @endif/>
                                <label for="filled-in-box2">Remember me</label>
                            </div>
                            <div class="forget-pass"><a href="{{ url('/forgot_password') }}" class="forgetpwd">Forgot password?</a></div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="form-group" style="margin-top: 20px;">
                            <div class="g-recaptcha" data-sitekey="{{ config('app.project.RE_CAP_SITE') }}" data-size="invisible"></div>
                            <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">    
                            <div class="error" id=""><?php echo Session('g-recaptcha-response'); ?></div>
                            <div class="error" id="error_captcha"><?php if(Session::has('Message')){ echo Session('Message'); } ?></div>
                        </div>
                        <button class="login-btn mp40" type="submit" value="submit" id="recaptcha-form-submit" name="submit">Login</button>
                    </form>

                    <div class="clearfix"></div>
                    <div class="responsive-title">Login with Social Media Account</div>
                    <ul class="social-footer responsive-section">
                        <li><a onclick="FBLogin()" type="submit" class="buttons-social fb-color" ><i class="fa fa-facebook"></i></a></li>
                        <li><a href="{{ url('/social_auth/twitter/init') }}" type="submit" class="buttons-social twitter-color"><i class="fa fa-twitter"></i></a></li>
                        <li><a onclick="GPLogin()" type="submit" class="buttons-social google-color"><i class="fa fa-google"></i></a></li>
                    </ul>
                    <div class="login-txts-sing responsive-section">Don't have an Account? <a href="{{ url('/signup') }}">Sign Up Now!</a></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#user_name").closest('form-group').addClass('active');
        $("#email").closest('form-group').addClass('active');
        $("#password").closest('form-group').addClass('active');
    });

    $("#hide_password").click(function(){
        $("#pwd").attr('type','text');

        $("#hide_password").css('display', 'none');
        $("#show_password").css('display', 'block');
    });

    $("#show_password").click(function(){
        $("#pwd").attr('type','password');

        $("#hide_password").css('display', 'block');
        $("#show_password").css('display', 'none');
    });

    $(document).ready(function(){
        jQuery('#frm_user_login').validate({
            ignore: [],
            errorElement: 'div',
            highlight: function(element) { },
            errorPlacement: function(error, element) {
                error.appendTo(element.parent());
            }
        });
    });

    $('#frm_user_login').submit(function(){
        //var captcha_response = grecaptcha.getResponse();
        var captcha_response = grecaptcha.execute();
        if (captcha_response != '') {
            $("#hiddenRecaptcha").val('yes');
            return true;
        }
    });

    // For Facebook
    function GPLogin()
    {  
        var myParams = {                          
            'clientid' : '{{ $google_client_id }}',
            'cookiepolicy' : 'single_host_origin',         
            'callback' : 'mycoddeSignIn',
            'approvalprompt':'force',
            'scope' : 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'
        };

        try {
            gapi.auth.signIn(myParams);
        } 
        catch (err) {
            $("#login_social_status").html("<div class='alert alert-danger'>Something went wrong with Google login. Try again or contact Admin</div>");
                    setTimeout(function() { $("#login_social_status").empty(); }, 10000);
        }
    } 


    function mycoddeSignIn(result) {
        if(result['status']['signed_in']) {
            var request = gapi.client.plus.people.get({
                'userId': 'me'
            });

            request.execute(function (resp){
                var email = fName = '';
                if(resp['emails']) {
                    for(i = 0; i < resp['emails'].length; i++) {
                        if(resp['emails'][i]['type'] == 'account') {
                            email = resp['emails'][i]['value'];
                            fName = resp['displayName'];
                            profile_pic = resp['image']['url'];
                        }
                    }
                } else {
                    $("#login_social_status").html("<div class='alert alert-danger'>Something went wrong with Google login. Try again or contact Admin</div>");
                    setTimeout(function() { $("#login_social_status").empty(); }, 10000);
                }

                if(email != "" && fName != "") {
                    $.ajax({
                        headers:{'X-CSRF-Token': csrf_token},
                        url:SITE_URL+'/gplogin',                         
                        data:{email: email,fName: fName, profile_pic: profile_pic},
                        type:'post',
                        dataType:'JSON',
                        beforeSend: function(){ showProcessingOverlay(); },
                        success:function(response) {
                            if (response.status == "SUCCESS_VERIFY") {
                                window.location.href = SITE_URL+'/verify_otp/'+response.enc_user_id;
                            } else if (response.status == "SUCCESS") {
                                window.location.href = SITE_URL;
                            } else {
                                console.log( response );
                                $("#login_social_status").html("<div class='alert alert-danger'>" + response.msg + "</div>");
                                setTimeout(function() {
                                    $("#login_social_status").empty();
                                }, 10000);
                            }
                            return false;                          
                        },
                        complete: function(){ hideProcessingOverlay(); }
                    });
                    return false;
                } 
            });
        }
    }

    function onLoadCallback() {
        gapi.client.setApiKey('AIzaSyDhofvU6gxlDTvXXzLU2dA5DBVB7_kUakw');
        gapi.client.load('plus', 'v1',function(){});
    }

    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/client.js?onload=onLoadCallback';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();



    // For Facebook
    window.fbAsyncInit = function() {
        FB.init({
            appId: '{{ $facebook_client_id }}',
            status: true,
            cookie: true,
            xfbml: true,
            version: 'v2.4'
        });
    };

    function FBLogin(redirect_url) {
        redirect_url = redirect_url ? redirect_url : false;
        FB.login(function(fb_response) {
            if (fb_response.authResponse) {
                FB.api('/me', 'get', {
                    fields: 'id,email,first_name,last_name, picture'
                }, function(profile_response) {
                    var email = profile_response.email;
                    var fb_user_id = profile_response.id;
                    var fname = profile_response.first_name;
                    var lname = profile_response.last_name;
                    var fb_token = FB.getAuthResponse()['accessToken'];
                    var dataObj = {
                        "fb_user_id": fb_user_id,
                        "email": email,
                        "fname": fname,
                        "lname": lname,
                        'fb_token': fb_token,
                        "_token": csrf_token
                    };
                    jQuery.ajax({
                        headers: {
                            'X-CSRF-Token': csrf_token
                        },
                        url: SITE_URL + '/fblogin',
                        type: 'POST',
                        data: dataObj,
                        dataType: 'json',
                        beforeSend: showProcessingOverlay(),
                        success: function(response) {
                            if (response.status == "SUCCESS_VERIFY") {
                                window.location.href = SITE_URL + '/verify_otp/' + response.enc_user_id;
                            } else if (response.status == "SUCCESS") {
                                if (redirect_url != false) {
                                    window.location.href = SITE_URL + redirect_url;
                                } else {
                                    window.location.href = SITE_URL;
                                }
                            } else {
                                console.log( response );
                                $("#login_social_status").html("<div class='alert alert-danger'>" + response.msg + "</div>");
                                setTimeout(function() {
                                    $("#login_social_status").empty();
                                }, 10000);
                            }
                            return false;
                        },
                        complete: hideProcessingOverlay()
                    });
                    return false;
                });
            }
        }, {
            scope: 'public_profile,email'
        });
    }

    function FBLogout() {
        FB.logout(function(response) {
            window.location.href = SITE_URL + '/logout';
        });
    }(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = SITE_URL + "/front/js/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

@endsection