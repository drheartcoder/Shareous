@extends('front.layout.master')                
@section('main_content')
  <div class="login-bg-main">
      <div class="login-boxs">
          <div class="row">
              <form id="form-forgot" action="{{url('/password_reset')}}" method="post">
                {{ csrf_field() }}
                @include('front.layout._flash_errors') 
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 padding-o whiteboxs forgot">
                      <div class="login_box">
                          @include('front.layout._flash_errors')
                            <div class="title_login">Reset Password
                            </div>
                           
                            <div class="form-group wrong-error">
                                <input type="password" name="password" id="password" data-rule-required="true"  data-rule-minlength="6" data-msg-minlength="Please Enter at least 6 digits" data-rule-maxlength="30" data-msg-maxlength="Please Enter at most 30 digits"  />
                                <label for="password">New Password</label>
                                <span class="calendar-icon" id="hide_password" style="cursor: pointer;"><i class="fa fa-eye"></i></span>
                                <span class="calendar-icon" id="show_password" style="display: none; cursor: pointer;"><i class="fa fa-eye-slash"></i></span>
                                <spna class="error" id="password" style="color:red">{{ $errors->first('password')}}</span>
                            </div>
                           
                            <div class="clearfix"></div>

                            <div class="form-group wrong-error">
                                <input type="password" name="password_confirmation" id="password_confirmation" data-rule-required="true" data-rule-equalto="#password" data-msg-equalto="Please enter the same value again."  />
                                <label for="password_confirmation">Confirm Password</label>
                                <span class="calendar-icon" id="hide_repassword" style="cursor: pointer;"><i class="fa fa-eye"></i></span>
                                <span class="calendar-icon" id="show_repassword" style="display: none; cursor: pointer;"><i class="fa fa-eye-slash"></i></span>
                                <span class="error" id="password_confirmation" style="color:red">{{ $errors->first('password_confirmation')}}</span>
                            </div>
                          
                            <div class="clearfix"></div>
                            <input type="hidden" name="token" value="{{ $token or ''}}" />
                            <input type="hidden" name="email" value="{{ $password_reset['email'] or ''}}" />
                            <button class="login-btn spacemanus cent" style="width: 200px;" type="submit" name="btn_submit" id="btn_submit">Reset password</button>

                        </div>                  
                  </div>
              </form>
          </div>
      </div>
  </div>

<script type="text/javascript">
  $(document).ready(function()
  {
      jQuery('#form-forgot').validate(
      {
         ignore: [],
         errorElement: 'div',
         errorClass: 'error for-height',
         highlight: function(element) { },
         rules: {
                  "password": {
                    pattern: /(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^])(?=.*\d).*/,
                    minlength:6,
                }
            },
            messages: {
                password: {
                    pattern: "Password must contain at least (1) lowercase and (1) uppercase and (1) special character and greater than or equal to 6 character",

                },
            }
         // errorPlacement: function(error, element) 
         // { 
         //    error.appendTo(element.parent());
         // }
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
          $("#password_confirmation").attr('type','text');

          $("#hide_repassword").css('display', 'none');
          $("#show_repassword").css('display', 'block');
      });

      $("#show_repassword").click(function(){
          $("#password_confirmation").attr('type','password');

          $("#hide_repassword").css('display', 'block');
          $("#show_repassword").css('display', 'none');
      });
  });
</script>

  @endsection
