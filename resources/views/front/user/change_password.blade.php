@extends('front.layout.master')                
@section('main_content')
    <!--Header section end here-->
    <div class="clearfix"></div>
    <div class="overflow-hidden-section">
    <div class="titile-user-breadcrum">
      <div class="container">
          <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions"><h1>Change Password</h1> <div class="clearfix"></div></div>
      </div> 
    </div>

    <div class="change-pass-bg main-hidden">
        <div class="container">
            <div class="row">
               <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 left-bar-dashboard">    
                 <div id="left-bar">
                     @include('front.layout.left_bar_host')                     
                 </div>
                </div>
                <form name="ChangePasswordForm" id="ChangePasswordForm" method="post" enctype="multipart/form-data" action="{{ url('/profile/update_password/') }}" >
                {{ csrf_field() }}
                
                <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                    @include('front.layout._flash_errors')
                    <div class="change-pass-bady">
                        <div class="form-group">
                            <input type="password" id="old_password" name="old_password" data-rule-required="true" value="{{ old('old_password') }}" />
                            <label for="old_password">Old Password</label>
                            <span class="calendar-icon" id="hide_oldpassword" style="cursor: pointer;"><i class="fa fa-eye"></i></span>
                            <span class="calendar-icon" id="show_oldpassword" style="display: none; cursor: pointer;"><i class="fa fa-eye-slash"></i></span>
                            <span class='error'>{{ $errors->first('old_password') }}</span>
                        </div>
                        
                        <div class="form-group">
                            <input type="password" id="new_password" name="new_password" data-rule-required="true" data-rule-minlength="6" data-msg-minlength="Please Enter at least 6 digits" data-rule-maxlength="30" data-msg-maxlength="Please Enter at most 30 digits" value="{{ old('new_password') }}" />
                            <label for="new_password">New Password</label>
                            <span class="calendar-icon" id="hide_newpassword" style="cursor: pointer;"><i class="fa fa-eye"></i></span>
                            <span class="calendar-icon" id="show_newpassword" style="display: none; cursor: pointer;"><i class="fa fa-eye-slash"></i></span>
                            <span class='error for-height' id="err_new_password">{{ $errors->first('new_password') }}</span>
                        </div>
                        
                        <div class="form-group">
                            <input type="password" id="confirm_password" name="confirm_password" data-rule-required="true" data-rule-equalto="#new_password" data-msg-equalto="Please enter the same value again." value="{{ old('confirm_password') }}"   />
                            <label for="confirm">Confirm Password</label>
                            <span class="calendar-icon" id="hide_repassword" style="cursor: pointer;"><i class="fa fa-eye"></i></span>
                            <span class="calendar-icon" id="show_repassword" style="display: none; cursor: pointer;"><i class="fa fa-eye-slash"></i></span>
                            <span class='error'>{{ $errors->first('confirm_password') }}</span>
                        </div>
                        <div class="change-pass-btn">
                          <button class="login-btn" type="submit" name="btn_submit" id="btn_submit">Save</button>
                        </div>
                        
                        <div class="clearfix"></div>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
    </div>

    
    <script>
        /*Price Range slider Start*/
        $(function () {
            $("#slider-price-range").slider({
                range: !0,
                min: 0,
                max: 20,
                values: [1, 10],
                slide: function (s, e) {
                    $("#slider_price_range_txt").html("<span class='slider_price_min'>  " + e.values[0] + " </span>  <span class='slider_price_max'>  " + e.values[1] + " </span>")
                }
            }), $("#slider_price_range_txt").html("<span class='slider_price_min'> " + $("#slider-price-range").slider("values", 0) + "</span>  <span class='slider_price_max'>  " + $("#slider-price-range").slider("values", 1) + "</span>")
        });
        /*Price Range Slider End*/
    </script>

    <script>
        $(".filter-btn").on("click", function () {
            $(".list-main-list").addClass("filter-open");
        });
        $(".back-filter-hide").on("click", function () {
            $(".list-main-list").removeClass("filter-open");
        });


        $("#hide_oldpassword").click(function(){
            $("#old_password").attr('type','text');

            $("#hide_oldpassword").css('display', 'none');
            $("#show_oldpassword").css('display', 'block');
        });

        $("#show_oldpassword").click(function(){
            $("#old_password").attr('type','password');

            $("#hide_oldpassword").css('display', 'block');
            $("#show_oldpassword").css('display', 'none');
        });
        
        $("#hide_newpassword").click(function(){
            $("#new_password").attr('type','text');

            $("#hide_newpassword").css('display', 'none');
            $("#show_newpassword").css('display', 'block');
        });

        $("#show_newpassword").click(function(){
            $("#new_password").attr('type','password');

            $("#hide_newpassword").css('display', 'block');
            $("#show_newpassword").css('display', 'none');
        });

        $("#hide_repassword").click(function(){
            $("#confirm_password").attr('type','text');

            $("#hide_repassword").css('display', 'none');
            $("#show_repassword").css('display', 'block');
        });

        $("#show_repassword").click(function(){
            $("#confirm_password").attr('type','password');

            $("#hide_repassword").css('display', 'block');
            $("#show_repassword").css('display', 'none');
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            jQuery('#ChangePasswordForm').validate(
            {
                ignore: [],
                 errorElement: 'div',
                 errorClass: 'error for-height',
                 highlight: function(element) { },
                 rules: {
                  "new_password": {
                    pattern: /(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^])(?=.*\d).*/,
                    minlength:6,
                }
            },
            messages: {
                new_password: {
                    pattern: "Password must contain at least (1) lowercase and (1) uppercase and (1) special character and greater than or equal to 6 character",

                },
            }
                                   
            });              
        });
        
    </script>
    @endsection
<!-- 
</body>

</html> -->