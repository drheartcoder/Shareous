<script src="{{url('/front/js/jquery-ui.js')}}" type="text/javascript"></script>
<!-- date picker js -->

<!--silder css & js start here-->
<script type="text/javascript" src="{{url('/front/js/jquery.flexisel.js')}}"></script>
<script type="text/javascript" src="{{url('/front/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script src="{{url('/front/js/kendo.all.min.js')}}"></script>

<footer>
    <div id="footer" class="sticky-stopper">
        <div class="footer-main-block">
            <div class="container">
                <div class="row">
                    <div class="footer-col-block">
                        <div class="col-sm-12 col-md-4 col-lg-4 abc">
                            <div class="footer_heading footer-col-head">
                                Company Info
                            </div>
                            <div class="menu_name points-footer">
                                <ul>
                                    <li><a href="{{url('/')}}">Home</a></li>
                                    <li><a href="{{url('/about-us')}}">About Us</a></li>
                                    <li><a href="{{url('/contact_us')}}">Contact Us</a></li>
                                    @if(!validate_login('users'))
                                    <li><a href="{{url('/login')}}">Login</a></li>
                                    @endif


                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4 abc">
                            <div class="footer_heading footer-col-head">
                                Learn More
                            </div>
                            <div class="menu_name points-footer">
                                <ul>
                                    <li><a href="{{url('faq')}}">FAQ</a></li>
                                    <li><a href="{{url('blog')}}">Blog</a></li>
                                    <li><a href="{{url('/how-it-works')}}">How It Works</a></li>
                                    <li><a href="{{url('/privacy-policy')}}">Privacy Policy</a></li>
                                    <li><a href="{{url('/terms-conditions')}}">Terms & Conditions </a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-4 col-lg-4 abc newsletter-section">
                            <div class="last-subscribe">
                                Newsletter
                            </div>
                            <div class="">

                                <form id="form-newsletter" name="form-newsletter" onsubmit="subscribeNewsletter(event)">
                                    {{ csrf_field() }}
                                    <p class="subtitles-p">Subscribe for latest stories and promotions</p>

                                    <div id="newsletter_op_status" style="display: none">
                                        <div class="alert alert-success" id="status_holder"></div>
                                    </div>

                                    <div class="subscri-block-new subscri-new-block">
                                        <input type="text" name="email" placeholder="Your Email..." data-rule-required="true" data-rule-email="true" />
                                        <button type="submit" class="sent-btns"><i class="fa fa-paper-plane"></i></button>
                                    </div>
                                </form>


                                <div class=" tile-joints">
                                    <ul class="social-footer">
                                        <li><a href="{{ isset($arr_global_site_setting['fb_url'])?$arr_global_site_setting['fb_url']:''}}" class="fb-color"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="{{ isset($arr_global_site_setting['twitter_url'])?$arr_global_site_setting['twitter_url']:''}}" class="twitter-color"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="{{ isset($arr_global_site_setting['linkedin_url'])?$arr_global_site_setting['linkedin_url']:''}}" class="pinterest-color"><i class="fa fa-linkedin"></i></a></li>
                                        <li><a href="{{ isset($arr_global_site_setting['instagram_url'])?$arr_global_site_setting['instagram_url']:''}}" class="instagram-color"><i class="fa fa-instagram"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>
                </div>
            </div>
            <div class="copyright-block">
                <i class="fa fa-copyright"></i> Copyright {{date('Y')}} - All right reserved. Powered by <a href="{{url('/')}}">{{config('app.project.name')}}</a>
            </div>
        </div>

        <a class="cd-top hidden-xs hidden-sm" href="#0">Top</a>
        <script type="text/javascript" language="javascript" src="{{url('/front/js/backtotop.js')}}"></script>
        <!--Footer Js Hide Show Start Here-->
        <script type="text/javascript">
            $(function () {
                $(".footer_heading").on("click", function () {
                    $(this).toggleClass("active");
                    $(this).next(".menu_name").slideToggle("slow");
                    $(this).parent(".abc").siblings().find(".menu_name").slideUp();
                    $(this).parent(".abc").siblings().children().removeClass("active")
                })
            });
        </script>
        <!--Footer Js Hide Show end Here-->
        <!-- Min Top Menu Start Here  -->
        <script type="text/javascript">
            /*header script end*/        
            function openNav() {
                document.getElementById("mySidenav").style.width = "250px";
                $("body").css({
                    "margin-left": "-250px",
                    "overflow-x": "hidden",
                    "transition": "margin-left .5s",
                    "position": "fixed"
                });
                $("#main").addClass("overlay");
            }
            function closeNav() {
                document.getElementById("mySidenav").style.width = "0";
                $("body").css({
                    "margin-left": "0px",
                    "transition": "margin-left .5s",
                    "position": "relative"
                });
                $("#main").removeClass("overlay");
            }
            /*header script end*/  
        </script>
        <!-- Min Top Menu Start End  -->
        <script>
            $(document).ready(function () {
                $('input, textarea, select').each(function () {
                    $(this).on('focus', function () {
                        $(this).parent('.form-group').addClass('active')
                    });
                    $('label').on('click', function () {
                        $(this).parent('.form-group').addClass('active')
                    });
                    $(this).on('blur', function () {
                        if ($(this).val().length == 0) {
                            $(this).parent('.form-group').removeClass('active')
                        }
                    });
                    if ($(this).val() != '') $(this).parent('.form-group').addClass('active')
                })
            });
        </script>
    </div>
</footer>
<script type="text/javascript" src="{{url('/front/js/sweetalert.min.js')}}"></script>
<script type="text/javascript" src="{{url('/front/js/sweetalert_msg.js')}}"></script>

<script type="text/javascript" src="{{url('/front/js/accordian.js')}}"></script>
<script src="{{url('/front/js/kendo.all.min.js')}}"></script>
<script src="{{ url('/front/js/image_validation.js') }}"></script>
<!--modal boostrap-->     
<script type="text/javascript" language="javascript" src="{{url('/front/js/modalmanager.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{url('/front/js/bootstrap-modal.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{url('/front/js/kendo.all.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{url('/front/js/jquery-ui.js')}}"></script>
<script type="text/javascript" src="{{ url('/front/js/jquery.validate.min.js') }}"></script>

<script type="text/javascript" src="{{url('/front/js/additional-methods.js')}}"></script>

<script type="text/javascript" language="javascript" src="{{url('/front/js/bootstrap-datepicker.min.js')}}"></script> 
<!-- date picker js -->

<script type="text/javascript">
    $('.date-input').each(function () {
        $(this).on('focus', function () {
            $('.date-block').addClass('active');
        });

        $(this).on('blur', function () {
            if ($(this).val().length == 0) {
                $('.date-block').removeClass('active');
            }
        });
        if ($(this).val() != '') $('.date-block').addClass('active');
    });

    $("#form-newsletter").validate({
        ignore: [],
        errorElement: 'div',
        highlight: function(element) { },
        errorPlacement: function(error, element) 
        { 
            error.insertAfter(element.parent());
        }
    });
    var url = "{{ url('/') }}";
    
    function subscribeNewsletter(event)
    {
        event.preventDefault();

        if($("#form-newsletter").valid())
        {
            
           $.ajax({
            url:url+"/subscribe_newsletter",
            type:'POST',
            data:$("#form-newsletter").serialize(),
            dataType:'json',
            success:function(response)
            {
              $("#form-newsletter")[0].reset();
              if(response.status=="SUCCESS")
              {
                $("#status_holder").removeClass("alert-danger").addClass('alert-success');
                $("#status_holder").html(response.msg);
                $("#newsletter_op_status").fadeIn();
            }
            else
            {
                $("#status_holder").removeClass("alert-success").addClass('alert-danger');
                $("#status_holder").html(response.msg);
                $("#newsletter_op_status").fadeIn();
            }

            setTimeout(function()
            {
                $("#newsletter_op_status").fadeOut();
                $("#status_holder").html("");
            },5000);
        }

    });
       }
   }
</script>
<?php
$freshchat_credentials = get_freshchat_credential();
$freshchat_api_token = (isset($freshchat_credentials) && $freshchat_credentials['freshchat_api_token'] != '') ? $freshchat_credentials['freshchat_api_token'] : config('app.freshchat_credentials.freshchat_api_token');
?>
<script>
  window.fcWidget.init({
    token: "{{ $freshchat_api_token }}",
    host: "https://wchat.freshchat.com",
    firstName: "{{ isset($user_details['first_name']) && !empty($user_details['first_name']) ? $user_details['first_name'] : '' }}",
    lastName: "{{ isset($user_details['last_name']) && !empty($user_details['last_name']) ? $user_details['last_name'] : '' }}",
    email: "{{ isset($user_details['email']) && !empty($user_details['email']) ? $user_details['email'] : '' }}",
    phone: "{{ isset($user_details['mobile_number']) && !empty($user_details['mobile_number']) ? $user_details['mobile_number'] : '' }}",
    phoneCountry: "{{ isset($user_details['country_code']) && !empty($user_details['country_code']) ? $user_details['country_code'] : '' }}"
});
</script>
</body>

</html>