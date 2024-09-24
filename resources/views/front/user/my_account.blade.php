@extends('front.layout.master')                
@section('main_content')

<div class="clearfix"></div>
<div class="overflow-hidden-section">
    <div class="titile-user-breadcrum">
        <div class="container">
            <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions">
                <h1>My Account</h1>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="change-pass-bg">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 left-bar-dashboard">    
                   <div id="left-bar-host">                   
                       @include('front.layout.left_bar_host')
                   </div>
                </div>
               
                <form name="ProfileForm" id="ProfileForm" method="post" enctype="multipart/form-data" action="{{ url('/profile/update/'.base64_encode($id)) }}">
                    {{csrf_field()}}
                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                        @include('front.layout._flash_errors')                                                           
                        <div class="my-account-main">
                            <div class="change-pass-bady edit">                           
                                <div class="my-account-profile-img-block  error-cente">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="pro-img">
                                            @if(isset($user['profile_image']) && $user['profile_image'] != '')
                                                @if(strpos($user['profile_image'], 'http') !== false)
                                                    <?php $profile_img_src = $user['profile_image']; ?>
                                                @else
                                                    @if(file_exists($profile_image_base_path.$user['profile_image'] ))
                                                        <?php $profile_img_src = $profile_image_public_path.$user['profile_image']; ?>
                                                    @else
                                                        <?php $profile_img_src = url('/uploads').'/default-profile.png'; ?> 
                                                    @endif
                                                @endif
                                            @else
                                                <?php $profile_img_src = url('/uploads').'/default-profile.png'; ?> 
                                            @endif
                                            <img src="{{ $profile_img_src }}" class="img-responsive img-preview" id="img_preview" alt="{{$profile_img_src}}" >
                                        </div>
                                    </div>                                    
                                    <div class="update-pic-btns">
                                        <button type="button" class="up-btn"></button>
                                        <input id="profile_image" name="profile_image" type="file" @if($user['profile_image'] == '' && $user['profile_image']==null) {{-- data-rule-required="true"  --}} @endif class="attachment_upload" onchange="readURL(this)" >
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="error">
                                        <i class="red">NOTE!</i><br/>
                                        <i class="red">Click above image to upload file </i><br/>
                                        <i class="red">Allowed only jpg | jpeg | png image format </i>
                                    </div>
                                </div>

                                <div class="clerfix"></div>
                                <div class="clerfix"></div>
                                
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <input id="first_name" name="first_name" data-rule-required="true" type="text" onkeyup="chk_validation(this)" value="{{isset($user['first_name'])?ucfirst($user['first_name']):''}}" />
                                            <label for="first_name">First Name</label>
                                            <span class="error" id="first_name" style="color:red">{{$errors->first('first_name')}}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <input id="last_name" name="last_name" type="text" data-rule-required="true" value="{{isset($user['last_name'])?ucfirst($user['last_name']):''}}" onkeyup="chk_validation(this)" />
                                            <label for="last_name">Last Name</label>
                                            <span class="error" id="last_name" style="color:red">{{$errors->first('last_name')}}</span>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group verified">
                                            @if($user['is_email_verified'] == '1')     
                                            <button type="button" id="verified_btn" class="verified-icon-for-input"></button>
                                            @else
                                            <button type="button" class="verified-icon-for-input unveri"></button>
                                            @endif
                                            <button type="button" id="unverified_btn" class="verified-icon-for-input unveri" style="display: none"></button>
                                            <input id="email" name="email" type="text" data-rule-required="true" @if($user_details['social_login']!='yes') data-rule-email="true" @endif value="{{isset($user['email'])?$user['email']:''}}" placeholder=""  @if($user_details['social_login']=='yes') readonly=""  @endif/>
                                            <label for="email">Email Address</label>
                                            <span class="error" id="err_email" style="color:red">{{$errors->first('email')}}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                        
                                        <?php $selected_country_code = isset($user['country_code']) ? $user['country_code'] : ''; ?>

                                        <div class="col-sm-4 col-md-4 col-lg-4" style="padding: 0px;">
                                            <div class="form-group">
                                                <div class="select-style">
                                                    <select id="country_code" name="country_code" data-rule-required="true">
                                                        <option value="">Country Code</option>
                                                        @if( isset( $arr_mobile_country_code ) && !empty( $arr_mobile_country_code ) )
                                                            @foreach ($arr_mobile_country_code as $key => $country_code)
                                                                <option value="{{ '+'.$country_code['phonecode'] }}" @if( $selected_country_code == '+'.$country_code['phonecode'] ) selected @endif >{{ $country_code['iso3'].' (+'.$country_code['phonecode'].')' }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <span class="error" id="err_country_code">{{ $errors->first('country_code') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-8 col-md-8 col-lg-8" style="padding: 0px;">
                                            <div class="form-group verified">
                                                @if($user['is_mobile_verified'] == '1')     
                                                    <button type="button" id="verified_btn_mobile" class="verified-icon-for-input"></button>
                                                @else
                                                    <button type="button" class="verified-icon-for-input unveri"></button>
                                                @endif
                                                <button type="button" id="unverified_btn_mobile" class="verified-icon-for-input unveri" style="display: none"></button>
                                                <input type="text" id="mobile_number" name="mobile_number" value="{{ isset($user['mobile_number']) ? $user['mobile_number'] : '' }}" type="text"  data-rule-required="true" data-rule-pattern="[- +()0-9]+" data-rule-minlength="7" data-rule-maxlength="14" data-msg-minlength="Please Enter at least 7 digits" data-msg-maxlength="Please Enter at most 14 digits"/>
                                                <label for="mobile_number">Mobile Number</label>
                                                <span class="error" id="mobile_number_err" style="color:red">{{$errors->first('mobile_number')}}</span>
                                                <input type="hidden" id="is_mobile_verified" name="is_mobile_verified" value="{{ isset($user['is_mobile_verified']) ? $user['is_mobile_verified'] : '' }}" type="text" />
                                                <input type="hidden" id="old_mobile_number" name="old_mobile_number" value="{{ isset($user['mobile_number']) ? $user['mobile_number'] : '' }}" type="text" />
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                           <input id="autocomplete" onchange="addActiveClass();" name="address" type="text" data-rule-required="true" value="{{isset($user['address']) && $user['address'] != 'NA'?$user['address']:''}}" placeholder=""  />
                                           <label for="address">Address</label>
                                           <span class="error" id="address" style="color:red">{{$errors->first('address')}}</span>
                                       </div>
                                   </div>
                                
                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group" id="city_div">
                                        <input id="locality" name="city" type="text" data-rule-required="true" value="{{isset($user['city'])?$user['city']:''}}" maxlength="80" />
                                        <label for="city">City</label>
                                        <span class="error" id="city" style="color:red">{{$errors->first('city')}}</span>
                                    </div>
                                </div>

                                <?php $dob = isset($user['birth_date']) && $user['birth_date']!='0000-00-00'? date('d-m-Y', strtotime($user['birth_date'])):''; ?>
                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group inpu">
                                        <input id="datepicker" name="birth_date" data-rule-required="true" readonly="readonly" class="datepicker-input" type="text" value="{{ $dob }}" />
                                        <label for="datepicker">Date Of Birth</label>
                                        <span class="calendar-icon"><i class="fa fa-calendar"></i></span>
                                        <span class="error" id="birth_date" style="color:red">{{$errors->first('birth_date')}}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <input type="text" id="display_name" name="display_name" value="{{isset($user['display_name'])?$user['display_name']:'NA'}}" data-rule-required="true"  />
                                        <label for="display_name">Display Name</label>
                                        <span class="error" id="display_name" style="color:red">{{$errors->first('display_name')}}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <?php
                                        $user_social_login = isset($user['social_login']) ? $user['social_login'] : '';
                                        $user_name = isset($user['user_name']) && !empty($user['user_name']) ? $user['user_name'] : '';
                                        $is_social_login = isset($user['social_login']) && !empty($user['social_login']) ? $user['social_login'] : '';
                                        if($user_social_login == 'yes' && $user_name == '') {
                                            $text_type = '  data-rule-pattern="^(?=.{4,32}$)(?![_.-])(?!.*[_.]{2})[a-zA-Z0-9._-]+(?<![_.])$" data-msg-pattern="Must be 4-32 chars, alphanumeric and only _, . symbols are allowed"';
                                        } else/*if($user_social_login == 'no' && $user_name != 'NA')*/ {
                                            $text_type = 'readonly';
                                        } ?>
                                        <input type="text" id="user_name" name="user_name" value="{{$user_name}}" data-rule-required="true" data-rule-maxlength="255" {{ $text_type }} />
                                        <label for="user_name">User Name</label>
                                        <span class="error" id="err_user_name" style="color:red">{{$errors->first('user_name')}}</span>
                                        @if($is_social_login == 'yes')
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <i class="fa fa-warning" style="color:red"></i> User Name can't be change for social login
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <div class="genders">Gender : </div>
                                        <div class="radio-btns">
                                            <div class="radio-btn">
                                                <input id="male" name="gender" class="gender" type="radio" data-rule-required="true"  value="1" @if(isset($user['gender']) && $user['gender'] == "1") checked @endif @if($user['gender'] != '') disabled @endif>
                                                <label for="male">Male</label>
                                                <div class="check"></div>
                                            </div>

                                            <div class="radio-btn">
                                                <input id="female" name="gender" class="gender" type="radio" data-rule-required="true" value="0" @if(isset($user['gender']) && $user['gender'] == "0") checked @endif @if($user['gender'] != '') disabled @endif>
                                                <label for="female">Female</label>
                                                <div class="check">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div id="err_gender"></div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <input type="text" id="gstin" name="gstin" value="{{ isset($user['gstin']) ? $user['gstin'] : '' }}" maxlength="25" minlength="10" />
                                        <label for="gstin">GSTIN</label>
                                        <span class="error" style="color:red">{{ $errors->first('gstin') }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="notifica-head-radio">Notification Setting:</div>
                                    <div class="notifica-radio inline-radio-wrapper">
                                        <div class="form-group ">
                                            <div>
                                                <input id="notification_by_email" class="filled-in" name="notification_by_email" type="checkbox" @if(isset($user['notification_by_email']) && $user['notification_by_email'] == "on") checked="checked" @endif>
                                                <label for="notification_by_email">By Email</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div>
                                                <input id="notification_by_sms" class="filled-in" name="notification_by_sms" type="checkbox" @if(isset($user['notification_by_sms']) && $user['notification_by_sms'] == "on") checked="checked" @endif>
                                                <label for="notification_by_sms">By SMS</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div>
                                                <input id="notification_by_push" class="filled-in" name="notification_by_push" type="checkbox" @if(isset($user['notification_by_push']) && $user['notification_by_push'] == "on") checked="checked" @endif>
                                                <label for="notification_by_push">By Mobile Notification</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="clearfix"></div>
                            <div class="change-pass-btn">
                               <button class="login-btn" type="button" name="btn_submit" id="btn_submit" onclick="return validateFunction();">Update Profile</button>
                           </div>
                           <div class="clearfix"></div>
                           </div>
                       </div>
                    </div>
                </form>
            </div>
        </div>
</div>
</div>

<input type="hidden" id="image_size_limit" value="{{ config('app.project.img_upload_size') }}">

<!--cancel booking popup start here-->
    <div class="host-contact-popup upgrade payment" >
        <div class="popup-inquiry-form">
            <div id="VerifyPopup" class="modal fade" data-backdrop="static" role=dialog>
                <div class="modal-dialog payment-popu-main">
                    <div class=modal-content>
                        <div class="modal-header black-close">
                            <button type=button class=close data-dismiss=modal>
                                <span class="contact-left-img popup-close"></span>
                            </button>
                            <h4 class=modal-title>Verify OTP</h4>
                        </div>
                        <div class=modal-body>                           
                            <div class="payment-detail-tab-one">

                                <form name="frm_verify_otp" action="{{ url('/') }}/profile/verify_otp" id="frm_verify_otp" method="POST">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <input type="text" name="mobile_otp" id="mobile_otp" value="" data-rule-required="true" maxlength="4">
                                            <label for="add-bank-name-id">OTP</label> 
                                            <span class='error help-block' id="err_mobile_otp">{{ $errors->first('err_mobile_otp') }}</span>
                                            <div class="forget-pass" style="display: none;"><a href="javascript: void(0);" autocomplete="off" onclick="javascript: resend_otp();" class="forgetpwd">Resend OTP</a></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="change-pass-btn">
                                            <a class="login-btn cancel" data-dismiss=modal href="javascript:void(0)">Cancel</a>
                                            <input type="submit" class="login-btn" name="btn_submit_verify_otp" value="Submit" id="btn_submit_verify_otp">
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                        <div class=clearfix></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--cancel booking popup end here-->

<?php $twilio_credentials = get_twilio_credential();  ?>
<input type="hidden" id="twilio_sid" value="{{ (isset($twilio_credentials) && $twilio_credentials['twilio_sid'] != '') ? $twilio_credentials['twilio_sid'] : config('app.twilio_credentials.twilio_sid') }}">
<input type="hidden" id="twilio_token" value="{{ (isset($twilio_credentials) && $twilio_credentials['twilio_token'] != '') ? $twilio_credentials['twilio_token'] : config('app.twilio_credentials.twilio_token') }}">
<input type="hidden" id="from_user_mobile" value="{{ (isset($twilio_credentials) && $twilio_credentials['from_user_mobile'] != '') ? $twilio_credentials['from_user_mobile'] : config('app.twilio_credentials.from_user_mobile') }}">


<!--new profile image upload demo script start-->
<script type="text/javascript">
        $(document).ready(function () 
        {
            var image_size_limit_kb = $("#image_size_limit").val();
            var image_size_limit_mb = image_size_limit_kb * 1000000;

            var brand = document.getElementById('profile_image');
            brand.className = 'attachment_upload';
            brand.onchange = function () {
                //document.getElementById('fakeUploadLogo').value = this.value.substring(12);
            };

            //Source: http://stackoverflow.com/a/4459419/6396981
            function readURL(input) 
            {
                if(input.files && input.files[0]) 
                {
                    var reader  = new FileReader();
                    var logo_id = $('#profile_image').val();
                    var ext     = logo_id.substring(logo_id.lastIndexOf('.')+1);
                    var width   = $(this).width();
                    var height  = $(this).height();
                    var size    = input.files[0].size;

                    if(ext!='jpg' && ext!='png' && ext!='jpeg')
                    {
                        swal("Invalid Image Format",'Sorry,allowed extensions are: jpg, png, jpeg format.','error');
                        reader.onload = function (e) 
                        {
                            //callback(true,e.target.result);
                        }
                    }
                    else if(width<1400 && height<500)
                    {
                        swal("Invalid size",'Height and Width must be greater than or equal to 1400 X 500.','error');
                        reader.onload = function (e) 
                        {
                            //callback(true,e.target.result);
                        }
                    }
                    else if(size > image_size_limit_mb)
                    {
                        swal("Invalid size",'Max size allowed is '+ image_size_limit_kb +'mb.','error');
                        reader.onload = function (e)
                        {
                            //callback(true,e.target.result);
                        }
                    }
                    else
                    {
                        reader.onload = function (e) 
                        {
                            $('#img_preview').attr('src', e.target.result);
                        }
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#profile_image").change(function () 
            {
                readURL(this);
            });
        });

        var glob_autocomplete;
        var glob_component_form =
        {
            street_number: 'short_name',
            route: 'long_name',
            locality: 'long_name',
            sublocality: 'long_name',
            postal_code: 'short_name',
            country: 'long_name',
            administrative_area_level_1: 'long_name'
        };

        var glob_marker = false;
        var glob_map = false;
        var glob_options = {  };

        glob_options.types = [];

        function changeCountryRestriction(ref)
        {
            var country_code = $(ref).val();
            destroyPlaceChangeListener(autocomplete);
            initAutocomplete();
            glob_autocomplete = false;
            glob_autocomplete = initGoogleAutoComponent($('#autocomplete')[0],glob_options,glob_autocomplete);
        }

        function initAutocomplete()
        {
            glob_autocomplete = false;
            glob_autocomplete = initGoogleAutoComponent($('#autocomplete')[0],glob_options,glob_autocomplete);
        }

        function initGoogleAutoComponent(elem,options,autocomplete_ref)
        {
            autocomplete_ref = new google.maps.places.Autocomplete(elem,options);
            autocomplete_ref = createPlaceChangeListener(autocomplete_ref,fillInAddress);
            return autocomplete_ref;
        }

        function createPlaceChangeListener(autocomplete_ref,fillInAddress)
        {
            autocomplete_ref.addListener('place_changed', fillInAddress);
            return autocomplete_ref;
        }

        function destroyPlaceChangeListener(autocomplete_ref)
        {
            google.maps.event.clearInstanceListeners(autocomplete_ref);
        }

        function fillInAddress()
        {
            var place = glob_autocomplete.getPlace();
            for (var component in glob_component_form){
                $("#"+component).val("");
                $("#"+component).attr('disabled',false);
            }
            if(place.address_components.length > 0 ){
                $.each(place.address_components,function(index,elem){
                    var addressType = elem.types[0];
                    if(glob_component_form[addressType]) {
                      var val = elem[glob_component_form[addressType]];
                      $("#"+addressType).val(val) ;
                    }
                });
            }
        }

        $(function () {
            var dt = new Date();
            dt.setFullYear(new Date().getFullYear()-18);

            var dob = '{{$dob}}';

            if(dob == '') {
                $("#datepicker").datepicker({
                    todayHighlight: true,
                    //dateFormat: 'yyyy-mm-dd',
                    //endDate:new Date(),
                    endDate: dt,
                    autoclose: true,
                    format: 'dd-mm-yyyy',
                });    
            }
        });

        $(document).ready(function(){
            $('#user_name').on('blur',function(){
                var user_name = $('#user_name').val();
                $('#btn_submit').attr('disabled',true);
                if($.trim(user_name) != '')
                {
                    $.ajax({
                        'url'    :SITE_URL+'/profile/check_username',                    
                        'type':'post',
                        'dataType':'json',
                        'data':{_token:token, user_name:user_name },
                        success:function(res)
                        {
                            if (res == 'exist') {
                                $('#err_user_name').show();
                                $('#err_user_name').html('User Name already exist. Try different User Name');
                            } else {
                                $('#btn_submit').attr('disabled',false);
                            }
                        }
                    });
                }
            });

            $('#email').on('change',function() {
                $('#unverified_btn').show();
                $('#verified_btn').hide();
                var email = $('#email').val();
                if(email != '') {
                    $.ajax({
                        'url':SITE_URL+'/profile/check_email',                    
                        'type':'post',
                        'dataType':'json',
                        'data':{_token:token, email:email },
                        success:function(res)
                        {
                            if(res == 'exist') {
                                $('#email').focus();
                                $('#err_email').html('Email already exist. Try different email');
                                $('#btn_submit').attr('disabled','disabled');
                            }
                            else {
                                $('#err_email').html('');
                                $('#btn_submit').removeAttr("disabled");
                            }
                        }
                    });
                }
            });

        $('#mobile_number').on('change',function(){
            $('#unverified_btn_mobile').show();
            $('#verified_btn_mobile').hide();
            var country_code       = $('#country_code').val();
            var mobile_number      = $('#mobile_number').val();
            var is_mobile_verified = $('#is_mobile_verified').val();

            if (mobile_number != '') {
                $.ajax({
                    'url':SITE_URL+'/profile/check_mobile_number',
                    'type':'post',
                    'dataType':'json',
                    'data':{ _token: token, mobile_number: mobile_number, country_code: country_code },
                    success:function(res) {
                        if(res == 'exist') {
                            $('#mobile_number').focus();
                            $('#mobile_number_err').html('Mobile Number already exist.');
                            $('#btn_submit').attr('disabled','disabled');
                        } else {
                            $('#mobile_number_err').html('');
                            $('#btn_submit').removeAttr("disabled");
                        }
                    }
                });
            }
        });

        $('#btn_submit_verify_otp').on('click',function(){
            var country_code      = $('#country_code').val();
            var mobile_otp        = $('#mobile_otp').val();
            var new_mobile_number = $('#mobile_number').val();

            if(mobile_otp.trim() == '') {
                $('#err_mobile_otp').text("Please enter OTP received on your new mobile number");
                return false;
            }

            var data = new FormData();
            data.append('mobile_otp', mobile_otp);
            data.append('new_mobile_number', new_mobile_number);
            data.append('country_code', country_code);
            $.ajax({
                headers:{'X-CSRF-Token': csrf_token},     
                url: SITE_URL+"/profile/verify_otp",
                method:"POST",
                data:data,
                contentType: false,     
                cache: false,          
                processData:false, 
                beforeSend: function(){ showProcessingOverlay(); },  // function() { $("#loader-gif").show(); }, 
                success:function(response)
                {
                    if(response.status == "success") {
                        $('#err_mobile_otp').css("color", "green");
                        $('#err_mobile_otp').text(response.msg);
                        $('#old_mobile_number').val(new_mobile_number);
                        setTimeout(function(){ $('#VerifyPopup').modal('hide') }, 3000);
                        validateFunction();
                    } else {
                        $('#err_mobile_otp').text(response.msg);
                        return false;
                    }
                },
                complete: function() {
                    hideProcessingOverlay();
                }
            });
            return false;
        });
    });

    function  validateFunction()
    {
        var country_code       = $('#country_code').val();
        var mobile_number      = $('#mobile_number').val();
        var old_mobile_number  = $('#old_mobile_number').val();
        var is_mobile_verified = $('#is_mobile_verified').val();
        var mobile_pattern     = /^[0-9]{7,14}$/;
        //var mobile_pattern     = /^\+[1-9]{1}[0-9]{3,14}$/;
        
        /*if(mobile_number.substr(1) == '+'){
            mobile_number = mobile_number.substr(1);
        }
        else if(mobile_number.indexOf('+') == -1)
        {
            $('#mobile_number_err').focus();
            $('#mobile_number_err').text("Please enter country code");
            $('for[mobile_number]').text("Please enter country code");
            return false;
        }*/
        
        if($.trim(country_code) == '') {
            $('#country_code').focus();
            $('#err_country_code').html("Please select country code");
            return false;
        }

        if(mobile_number.trim() == '' || mobile_number.length < 7) {
            $('#mobile_number_err').focus();
            $('for[mobile_number]').text("Please enter min 7 digits");
            return false;
        } else  if(mobile_number.trim() != '' && mobile_number.length > 14) {
            $('#mobile_number_err').focus();
            if($('#mobile_number_err').text() == "" && $('for[mobile_number]').text() == ""){
            $('for[mobile_number]').text("Please enter max 14 digits");
            }
            return false;
        } else if(!mobile_pattern.test(mobile_number)){
            $('#mobile_number_err').focus();
            if($('#mobile_number_err').text() == "" && $('for[mobile_number]').text() == ""){
            $('for[mobile_number]').text("Please enter valid mobile number");
            }
            return false;
        } else if(mobile_number == old_mobile_number && is_mobile_verified == 1 && $.trim(country_code) != '') {
            $('#ProfileForm').submit();
        } else {

            var twilio_sid        = $('#twilio_sid').val();
            var twilio_token      = $('#twilio_token').val();
            var from_user_mobile  = $('#from_user_mobile').val();

            console.log(twilio_sid);
            console.log(twilio_token);
            console.log(from_user_mobile);

            var data = new FormData();
            data.append('country_code', country_code);
            data.append('mobile_number', mobile_number);
            $.ajax({
                headers:{'X-CSRF-Token': csrf_token},
                url: SITE_URL+"/profile/generate_otp",
                method:"POST",
                data:data,
                contentType: false,     
                cache: false,          
                processData:false, 
                beforeSend: function(){ showProcessingOverlay(); },
                success:function(response) {
                    if(response.status == 'error')
                    {
                        swal("Error","Mobile no. already exist, Try using other mobile number","error");
                        return false;
                    }
                    else if(response.status == 'success')
                    {
                        $('#VerifyPopup').modal();
                        if( response.count <= 3 )
                        {
                            setTimeout(function(){ $(".forget-pass").css('display', 'block'); }, 30000);
                        }
                        return false;
                    }
                },
                complete: function() {
                    hideProcessingOverlay();
                    $('#is_mobile_verified').val(1);
                }
            });
            return false;
        }
    }

    $(document).ready(function()
    {
        jQuery('#ProfileForm').validate(
        {
            ignore: [],
            errorElement: 'div',
            highlight: function(element) { },
            errorPlacement: function(error, element) 
            { 
                error.insertAfter(element);
                var name = $(element).attr("name");
                if(name === "gender") 
                {
                    error.insertAfter('#err_gender');
                } else {
                    error.insertAfter(element);
                }
                //error.appendTo(element.parent());
            },
            rules: {
                    mobile_number: {
                        required: true,
                        minlength:7,
                        maxlength:14,
                        pattern: /^[0-9]{7,14}$/,
                        //pattern: /^\+[1-9]{1}[0-9]{3,14}$/
                    }
                },
            messages: {
                mobile_number: {
                    minlength: "Please enter at least 7 digits",
                    maxlength: "Please enter max 14 digits",
                    pattern: "Please enter valid mobile number",
                }
            }
        });

        $('#btn_submit').click(function(){
            if (!$('input[name=gender]').is(':checked') == true) {
                $('#err_gender').html();
                $(".gender").on('change',function(){ $("#err_gender").html("");});                    
                return false;
            } else {
                return true;
            }
        });
    });

    function chk_validation(ref)
    {
        var yourInput = $(ref).val();
        re = /[0-9`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
        var isSplChar = re.test(yourInput);
        if(isSplChar) {
            var no_spl_char = yourInput.replace(/[0-9`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
            $(ref).val(no_spl_char);
        }
    }

    function addActiveClass()
    {
        $('#city_div').addClass('active');
    }


    function resend_otp()
    {
        $(".forget-pass").css('display', 'none');

        var country_code  = $('#country_code').val();
        var mobile_number = $('#mobile_number').val();

        var data = new FormData();
        data.append('country_code', country_code);
        data.append('mobile_number', mobile_number);
        $.ajax({
            headers:{'X-CSRF-Token': csrf_token},
            url: SITE_URL+"/profile/resend_otp",
            method:"POST",
            data:data,
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){ showProcessingOverlay(); },
            success:function(response)
            {
                if(response.status == "success") {
                    $('#err_mobile_otp').css("color", "green");
                    $('#err_mobile_otp').text("OTP has been sent to your entered mobile number");
                    setTimeout(function(){ $("#err_mobile_otp").text(''); }, 8000);
                    if( response.count <= 3 )
                    {
                        setTimeout(function(){ $(".forget-pass").css('display', 'block'); }, 30000);
                    }

                } else {
                    $('#err_mobile_otp').text("Something went wrong.");
                    setTimeout(function(){ $("#err_mobile_otp").text(''); }, 8000);
                    return false;
                }
                return false;          
            },
            complete: function(){ hideProcessingOverlay(); }
        });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyBYfeB69IwOlhuKbZ1pAOwcjEAz3SYkR-o&libraries=places&callback=initAutocomplete"
async defer></script>
<!--new profile image upload demo script end-->

@endsection
