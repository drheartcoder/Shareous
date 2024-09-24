@extends('front.layout.master')                
@section('main_content')
<!--Header section end here-->

<script src='https://www.google.com/recaptcha/api.js'></script>

<div class="login-bg-main singup">
    <div class="login-boxs">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 padding-o color-red-alpha pt-100">
                <h1 style="margin-top: 50px;">Sign up your account. There is no need to force the visitor to sign up from 
                    scratch and go through email confirmations and hoops all over again.</h1>
                <div class="login-txts-sing">Already Have an Account? <a href="{{ url('/login') }}">Login Now!</a></div>
            </div>
 
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 padding-o whiteboxs">
                <div class="login_box">
                    <form action="{{ url('process_signup') }}" method="post" id="frm_user_singup">
                        {{ csrf_field() }}
                        @include('front.layout._flash_errors')  
                        <div class="title_login">Sign Up</div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <input id="name" type="text" name="first_name" data-rule-required="true" data-rule-maxlength="50" data-rule-lettersonly="true" data-msg-maxlength="Enter no more than 50 characters." value="{{ old('first_name') }}" tabindex="1" />
                                            <label for="name">First Name</label>
                                            <div class="error">{{ $errors->first('first_name') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <input id="lastname" type="text" name="last_name" data-rule-required="true" data-rule-maxlength="50" data-rule-lettersonly="true" data-msg-maxlength="Enter no more than 50 characters." value="{{ old('last_name') }}" tabindex="2" />
                                            <label for="lastname">Last Name</label>
                                            <div class="error">{{ $errors->first('last_name') }}</div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <input type="hidden" name="userid" id="userid" value="">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <input id="username" type="text" name="user_name" id="user_name" data-rule-required="true" data-rule-maxlength="50" data-msg-maxlength="Enter no more than 50 characters." value="{{ old('user_name') }}" tabindex="3" />
                                            <label for="username">User Name</label>
                                            <div class="error" id="err_username" style="position: relative !important;">{{ $errors->first('user_name') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <input id="email" type="text" name="email" data-rule-required="true" data-rule-email="true" data-rule-maxlength="255" value="{{ old('email') }}" tabindex="4" />
                                            <label for="email">Email Id</label>
                                            <input type="hidden" id="is_email_valid" value="">
                                            <div class="error" id="err_email">{{ $errors->first('email') }}</div>
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <input id="password" type="password" name="password" data-rule-required="true" data-rule-pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^])(?=.*\d).*" data-msg-pattern="Invalid format" autocomplete="off" tabindex="5" />
                                            <label for="password">Password</label>
                                            <span class="calendar-icon" id="hide_password" style="cursor: pointer;"><i class="fa fa-eye"></i></span>
                                            <span class="calendar-icon" id="show_password" style="display: none; cursor: pointer;"><i class="fa fa-eye-slash"></i></span>
                                            <div class="error">{{ $errors->first('password') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <input id="pwdss" type="password" name="confirm_password" data-rule-required="true" data-rule-equalto="#password" autocomplete="off" tabindex="6" />
                                            <label for="pwdss">Re - Enter Password</label>
                                            <span class="calendar-icon" id="hide_repassword" style="cursor: pointer;"><i class="fa fa-eye"></i></span>
                                            <span class="calendar-icon" id="show_repassword" style="display: none; cursor: pointer;"><i class="fa fa-eye-slash"></i></span> 
                                            <div class="error">{{ $errors->first('confirm_password') }}</div>
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <i class="fa fa-exclamation-circle" style="color:red"></i> Password must contain at least (1) lowercase and (1) uppercase and (1) special character and greater than or equal to 6 character.
                                </div>
                            </div>

                            <?php
                            $location_countrycode = isset($user_location_countrycode) && !empty($user_location_countrycode) ? $user_location_countrycode : '';
                            ?>

                            <div class="col-xs-5 col-sm-4 col-md-4 col-lg-4 country-code">
                                <div class="form-group">
                                    <div class="select-style">
                                        <select id="country_code" name="country_code" data-rule-required="true" tabindex="7" >
                                            <option value="">Country Code</option>
                                            @if( isset( $arr_mobile_country_code ) && !empty( $arr_mobile_country_code ) )
                                                @foreach ($arr_mobile_country_code as $key => $country_code)
                                                    <option value="{{ '+'.$country_code['phonecode'] }}" @if( $location_countrycode == $country_code['iso'] ) selected @endif >{{ $country_code['iso3'].' (+'.$country_code['phonecode'].')' }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="error" id="err_country_code">{{ $errors->first('country_code') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-7 col-sm-8 col-md-8 col-lg-8 mobile-number" >
                                <div class="form-group">
                                    <input id="mobile_number" type="text" name="mobile_number" value="{{ old('mobile_number') }}" tabindex="8" />
                                    <label for="mobile">Mobile Number</label>
                                    <input type="hidden" id="is_mobile_valid" value="">
                                    <div class="error" id="err_mobile">{{ $errors->first('mobile_number') }}</div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group date-block select">
                                            <input id="datepicker" class="input-box age-box-input cander-cin calender-icn date-input" name="birth_date" data-rule-required="true" readonly="readonly" value="{{ old('birth_date') }}" tabindex="9" />
                                            <label for="datepicker">Date Of Birth</label>
                                            <span class="calendar-icon" style="z-index: 0;"><i class="fa fa-calendar"></i></span>
                                            <div class="error">{{ $errors->first('birth_date') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <div class="genders">Gender :</div>
                                            <div class="radio-btns">
                                                <div class="radio-btn">
                                                    <input id="male" name="gender" value="1" type="radio" {{ old('gender') == '1' ? "checked" : '' }} tabindex="10" >
                                                    <label for="male">Male</label>
                                                    <div class="clearfix"></div>     
                                                    <div class="check"></div>
                                                </div>
                                                <div class="radio-btn">
                                                    <input id="female" value="0" name="gender" type="radio" {{ old('gender') == '0' ? "checked" : '' }} tabindex="11" >
                                                    <label for="female">Female</label>
                                                    <div class="check">
                                                        <div class="inside"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>                                  
                                            <div class="error err_gender">{{ $errors->first('gender') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="user_box1">
                                    <div class="check-box inline-checkboxs singup-tp">
                                        <input id="guest_terms" class="filled-in" type="checkbox" value="true" name="terms" data-rule-required="true" data-msg-required="Please Accept Terms And Conditions" tabindex="12" />
                                        <label for="guest_terms">Accept Our <a href="{{ url('terms-conditions') }}" target="_blank"><b>Terms And Condition</b></a></label>
                                        <div class="error">{{ $errors->first('terms') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="user_box1">
                                    <div class="check-box inline-checkboxs singup-tp">
                                        <div class="g-recaptcha" data-sitekey="{{ config('app.project.RE_CAP_SITE') }}" data-size="invisible"></div>
                                        <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">    
                                        <div class="error" id=""><?php echo Session('g-recaptcha-response'); ?></div>
                                        <div class="error" id="error_captcha"><?php if(Session::has('Message')){ echo Session('Message'); } ?></div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="clearfix"></div>
                        <button class="login-btn mp40" name="submit" id="btnSignUp" value="submit" type="submit" tabindex="13" >Sign Up</button>
                        <div class="clearfix"></div>
                    </form>
                    <div class="login-txts-sing responsive-section">Already Have an Account? 
                        <a href="{{ url('/login') }}">Login Now! </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=popup-inquiry-form>
    <div id="myModal-signup" class="modal fade" data-replace="true" style="display: none;">
        <div class=modal-dialog>
            <div class=modal-content>
                <div class=modal-header>
                    <button type=button class=close data-dismiss=modal>
                        <span class="contact-left-img popup-close"></span>
                    </button>
                    <h4 class=modal-title>Verify your Mobile Number</h4>
                </div>
                <div class=modal-body>
                    <div class="sign-up-mob-img"><img src="{{url('/front')}}/images/sign-up-popup-mob-img.png" alt="" /> </div>
                    <div class="hello-mobile">Hello...John</div>
                    <div class="hello-mobile-sub">
                        <span>OTP has been send to </span> <span class="hello-mobile-sub-bold">+91 9823422562</span>
                    </div>
                    <div class="form-center">
                        <div class="form-group">
                            <div class="contact-left-img"></div>
                            <input id="otp" type="text" />
                            <label for="otp">Enter OTP</label>
                        </div>
                    </div>
                    <div class="sign-up-popu-btn">
                        <a class="login-btn verifyBtn" data-toggle="modal" href="#myModal-in" >Verify</a>
                    </div>
                    <div class=clearfix></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="popup-inquiry-form two">
    <div id="myModal-in" class="modal fade" data-replace="true" style="display: none;" >
        <div class=modal-dialog>
            <div class=modal-content>
                <div class=modal-header>
                    <button type=button class=close data-dismiss=modal>
                        <span class="contact-left-img popup-close"></span>
                    </button>
                    <h4 class=modal-title>Verification Completed</h4>
                </div>
                <div class=modal-body>
                    <div class="sign-up-mob-img"><img src="{{url('/front')}}/images/sign-up-popup-mob-img.png" alt="" /> </div>
                    <div class="hello-mobile">Congratulations ! </div>
                    <div class="hello-mobile-sub">
                        <span>Mobile Number has been Verified </span>
                    </div>
                    <div class="sign-up-popu-btn">
                        <a class="login-btn" data-toggle="modal" href="#myModal-in" >Ok</a>
                    </div>
                    <div class=clearfix></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        jQuery('#frm_user_singup').validate({
            ignore: [],
            errorElement: 'div',
            highlight: function(element) {},
            errorPlacement: function(error, element) 
            { 
                var name = $(element).attr("name");
                if (name === "gender") {
                    error.insertAfter('.err_gender');
                } else {
                    error.appendTo(element.parent());
                }
            },
            rules: {
                mobile_number: {
                    required: true,
                    minlength:7,
                    maxlength:14,
                    pattern: /^[0-9]{7,14}$/,
                },
                user_name: {
                    required: true,
                    minlength: 4,
                    maxlength: 32,
                    pattern: /^(?!.*[_.]{2})[a-z0-9][a-z0-9._]{2,14}[a-z0-9]$/i
                },
                gender :{
                    required: true
                } 
            },
            messages: {
                mobile_number: {
                    minlength: "Please enter at least 7 digits",
                    maxlength: "Please enter max 14 digits",
                    pattern: "Please enter valid mobile number",
                },
                user_name: {
                    minlength: "Must have minimum 4 characters",
                    maxlength: "Maximum 32 characters allowed",
                    pattern: "Only . and _ symbols are allowed"
                },
                gender: {
                    required: "Please select gender",
                }
            }
        });
    });

    $(document).ready(function() {
        jQuery('#form-frm_user_singup').validate();
    });
    
    $('#frm_user_singup').submit(function() {
        var captcha_response = grecaptcha.execute();
        if (captcha_response != '') {
            $("#hiddenRecaptcha").val('yes');
            return true;                    
        }                 
    });

    $(function () {
        var dt = new Date();
        dt.setFullYear(new Date().getFullYear()-18);

        $("#datepicker").datepicker({
            todayHighlight: true,       
            endDate: dt,
            autoclose: true,
            changeYear: true,
            changeMonth: true,
            clearBtn: true,
            startDate: "-100y",
            format:'dd-mm-yyyy',
        }).on('changeDate',function (){     
            $(this).parent().addClass('active'); 
        });
    });

    $("#hide_password").click(function(){
        $("#password").attr('type','text');

        $("#hide_password").css('display', 'none');
        $("#show_password").css('display', 'block');
    });

    $("#show_password").click(function(){
        $("#password").attr('type','password');

        $("#hide_password").css('display', 'block');
        $("#show_password").css('display', 'none');
    });

    $("#hide_repassword").click(function(){
        $("#pwdss").attr('type','text');

        $("#hide_repassword").css('display', 'none');
        $("#show_repassword").css('display', 'block');
    });

    $("#show_repassword").click(function(){
        $("#pwdss").attr('type','password');

        $("#hide_repassword").css('display', 'block');
        $("#show_repassword").css('display', 'none');
    });
</script>
@endsection
