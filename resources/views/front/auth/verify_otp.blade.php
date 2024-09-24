@extends('front.layout.master')                
@section('main_content')

<?php
$mobile_number = isset( $arr_user['mobile_number'] ) ? $arr_user['mobile_number'] : '';
$resend_otp_count = isset( $arr_user['resend_otp_count'] ) ? $arr_user['resend_otp_count'] : '';
?>

<div class="login-bg-main">
    <div class="login-boxs">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 padding-o color-red-alpha">
                <div class="login-txts-sing">Don't have an Account? <a href="{{url('/signup')}}">Sign Up Now!</a></div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 padding-o whiteboxs">
                <div class="login_box">

                    @if($mobile_number == '')
                        <form id="frm_verify_otp" method="post" action="{{ url('verify_otp/'.$enc_user_id) }}" >
                    @else
                        <form id="frm_verify_otp" method="post" action="{{ url('process_verify_otp') }}" >
                    @endif
                        {{csrf_field()}}
                        <input type="hidden" name="enc_user_id" id="enc_user_id" value="{{ $enc_user_id }}" >
                        <div class="title_login">Mobile Number Verification</div>
                        @include('front.layout._flash_errors')
                        <div class="top_login_status"></div>
                        @if($mobile_number == '')
                            <div class="form-group">
                                <div class="select-style">
                                    <select id="country_code" name="country_code" data-rule-required="true" >
                                        <option value="">Country Code</option>
                                        @if( isset( $arr_mobile_country_code ) && !empty( $arr_mobile_country_code ) )
                                            @foreach ($arr_mobile_country_code as $key => $country_code)
                                                <option value="{{ '+'.$country_code['phonecode'] }}">{{ '+'.$country_code['phonecode']." (".$country_code['iso3'].")" }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="error" id="err_country_code">{{ $errors->first('country_code') }}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input id="mobile_number" type="text" name="mobile_number" data-rule-required="true" value=""/>
                                <label for="mobile_otp">Mobile Number</label>
                                <div class="error">{{ $errors->first('mobile_number') }}</div>
                            </div>

                            <button class="login-btn mp40" type="submit" id="btn_mobile_verification" value="submit" name="submit"> Submit </button>
                        @else
                            <div class="form-group">
                                <input id="mobile_otp" type="text" name="mobile_otp" data-rule-required="true" value=""/>
                                <label for="mobile_otp">OTP</label> 
                                <div class="error">{{$errors->first('mobile_otp')}}</div>
                                @if($resend_otp_count < 3)
                                    <div class="forget-pass" style="display: none;"><a href="javascript: void(0);" autocomplete="off" onclick="javascript: resend_otp();" class="forgetpwd">Resend OTP</a></div>
                                @endif
                            </div>
                            <button class="login-btn mp40" type="submit" value="submit" id="btn_verify_otp" name="submit"> Verify </button>
                        @endif
                    </form>

                    <div class="clearfix"></div>
                    <div class="login-txts-sing responsive-section">Don't have an Account? <a href="{{url('/signup')}}">Sign Up Now!</a></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function(){
        $("#mobile_otp").closest('form-group').addClass('active');
        setTimeout(function(){ $(".forget-pass").css('display', 'block'); }, 30000);
    });

    $(document).ready(function(){
        jQuery('#frm_verify_otp').validate({
            ignore: [],
            errorElement: 'div',
            highlight: function(element) { },
            errorPlacement: function(error, element) 
            { 
                error.appendTo(element.parent());
            },
            rules: {
                @if($arr_user['mobile_number'] == '')
                    mobile_number: {
                        required: true,
                        minlength:7,
                        maxlength:14,
                        pattern: /^[0-9]{7,14}$/,
                        //pattern: /^\+[1-9]{1}[0-9]{3,14}$/
                    },
                @else
                    mobile_otp: {
                        required: true,
                        pattern: /^[0-9]{4}$/
                    }
                @endif
            },
            messages: {
                @if($arr_user['mobile_number'] == '')
                mobile_number: {
                    minlength: "Please enter at least 7 digits",
                    maxlength: "Please enter max 14 digits",
                    pattern: "Please enter mobile number",
                },
                @else
                mobile_otp: {
                    pattern: "Enter valid OTP"
                }
                @endif
            }
        });
    });
    $('#frm_verify_otp').on('submit',function(){
        var form = $('#frm_verify_otp');
        if(form.valid())
        {
            showProcessingOverlay();
        }
    });
    function GPLogin()
    {   
        var myParams = {                          
            'clientid' : '729094495229-o1irhighrr4h7g0ahl2t7n48ivesn71a.apps.googleusercontent.com',         
            'cookiepolicy' : 'single_host_origin',         
            'callback' : 'mycoddeSignIn',
            'approvalprompt':'force',
            'scope' : 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'       
        };
        gapi.auth.signIn(myParams);
    } 

    function mycoddeSignIn(result)
    { 
        if (result['status']['signed_in']) {
            var request = gapi.client.plus.people.get({
                'userId': 'me'
            });
            request.execute(function (resp) {
                var email = fName = '';
                if (resp['emails']) {
                    for (i = 0; i < resp['emails'].length; i++) {
                        if (resp['emails'][i]['type'] == 'account') {
                            email = resp['emails'][i]['value'];
                            fName = resp['displayName'];
                        }
                    }
                }

                if (email!="" && fName!="") {
                    $.ajax({
                        headers:{'X-CSRF-Token': csrf_token},
                        url:SITE_URL+'/gplogin',                         
                        data:{email:email,fName:fName},
                        type:'post',
                        dataType:'JSON',
                        beforeSend: function(){ showProcessingOverlay(); },
                        success:function(response)
                        {
                            if (response.status == "SUCCESS_VERIFY") {
                                window.location.href = SITE_URL+'/verify_otp/'+response.enc_user_id;
                            } else 
                            if (response.status == "SUCCESS") {
                                window.location.href = SITE_URL;
                            } else {
                                jQuery(".top_login_status").html("<div class='alert alert-danger'>"+response.msg+"</div>");
                                setTimeout(function()
                                {
                                    jQuery(".top_login_status").empty(); 
                                },5000);
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

    function onLoadCallback()
    {
        gapi.client.setApiKey('AIzaSyDhofvU6gxlDTvXXzLU2dA5DBVB7_kUakw');
        gapi.client.load('plus', 'v1',function(){});
    }

    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/client.js?onload=onLoadCallback';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();

    function resend_otp()
    {
        $(".forget-pass").css('display', 'none');

        var enc_user_id = $('#enc_user_id').val();
        $.ajax({
            headers:{'X-CSRF-Token': csrf_token},
            url:SITE_URL+'/resend_otp',                         
            data:{enc_user_id:enc_user_id},
            type:'post',
            dataType:'JSON',
            beforeSend: function() { showProcessingOverlay(); },
            success:function(response)
            {
                if (response.status == "SUCCESS") {
                    jQuery(".top_login_status").html("<div class='alert alert-success'>"+response.msg+"</div>");
                    setTimeout(function() { jQuery(".top_login_status").empty(); },5000);
                    if( response.count < 3 )
                    {
                        setTimeout(function(){ $(".forget-pass").css('display', 'block'); }, 30000);
                    }

                } else {
                    jQuery(".top_login_status").html("<div class='alert alert-danger'>"+response.msg+"</div>");
                    setTimeout(function() { jQuery(".top_login_status").empty(); },5000);
                }
                return false;          
            },
            complete: function(){ hideProcessingOverlay(); }
        });
    }
</script>
@endsection