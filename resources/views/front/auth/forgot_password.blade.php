@extends('front.layout.master')                
@section('main_content')
  <div class="login-bg-main">
      <div class="login-boxs">
          <div class="row">
              <form id="form-forgot" action="{{url('/forgot_password_email')}}" method="post">
                {{ csrf_field() }}
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 padding-o whiteboxs forgot">
                      <div class="login_box">
                          
                          @include('front.layout._flash_errors')
                          
                            <div class="title_login">Forgot Password
                            <p>Enter the email address associated with your account, and we'll email you a link to reset your password.</p>
                            </div>
                            <div class="form-group wrong-error">
                                <input type="text" name="email" id="email" data-rule-required="true" data-rule-email="true" />
                                <label for="email">Enter Registered Email Address</label>
                                <div class="error" id="email" style="color:red">{{ $errors->first('email')}}</div>
                            </div>
                           
                            <div class="clearfix"></div>
                            <div class="row">
                                
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 right">
                                  <button class="login-btn spacemanus forgott" type="submit" name="btn_submit" id="btn_submit">Send Reset Link</button>
                                 {{-- <a class="login-btn spacemanus forgott" href="login.html">Send Reset Link</a> --}}
                                </div>
                                
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <a href="{{url('/login')}}" class="back-btnss forgott-center"><img src="{{url('front/images/arrow-png.png')}}" alt="" /> Back to Login</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>                        
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
         highlight: function(element) { },
         errorPlacement: function(error, element) 
         { 
            error.appendTo(element.parent());
         }
      });
  });

      $("#form-forgot").submit(function()
      {
        if($("#form-forgot").valid())
        {
          var doc_height = $(document).height();
          var doc_width  = $(document).width();
          var spinner_html = "<img src='{{url('/front/images/spin.gif')}}'/>";
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
        }
    });
</script>

  @endsection
