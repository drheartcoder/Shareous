@extends('front.layout.master')                
@section('main_content')

<script src='https://www.google.com/recaptcha/api.js'></script>

   <div class="clearfix"></div>
    <div class="title-common">
        <h1>Contact Us</h1>
    </div>
    
    <div class="contact-info-main">
        <div class="contact-info-left">
           <div class="contact-left-info-block">
            <div class="contact-top-containt">
                <div class="contact-top-head">Support</div>
                <div class="contact-top-sub-head">Behind Every Great Product Is a Great Support Team</div>
                <div class="contact-top-sub-containt">Experts provide consultation and on-site POS installation, account setup, hardware configuration, support and ongoing training for you and your staff.</div>
            </div>
            
                <div class="contact-left-img-main">
                    <div class="contact-left-img contact"></div>
                    <div class="contact-left-content">
                        <div class="contact-left-cont-head"> Address:</div>
                        <div class="contact-left-head-containt">{{isset($site_data['site_address'])?$site_data['site_address']:'' }}</div>
                    </div>
                </div>
                <div class="contact-left-img-main">
                    <div class="contact-left-img contact two"></div>
                    <div class="contact-left-content">
                        <div class="contact-left-cont-head"> Phone:</div>
                        <div class="contact-left-head-containt"> {{isset($site_data['site_contact_number'])?$site_data['site_contact_number']:'' }}</div>
                    </div>
                </div>
                <div class="contact-left-img-main no-margi">
                    <div class="contact-left-img contact three"></div>
                    <div class="contact-left-content no-mar">
                        <div class="contact-left-cont-head"> Email Address:</div>
                        <div class="contact-left-head-containt">{{isset($site_data['site_email_address'])?$site_data['site_email_address']:'' }}</div>
                       
                    </div>
                </div>
                
                <div class="clearfix"></div>
            </div>
        </div>

        <form name="ContactForm" id="ContactForm" method="post"  action="{{url('/contact_us/store')}}" data-parsley-validate >
        {{csrf_field()}}
            <div class="contact-info-right">
                <div class="contact-info-filds">
                    @include('front.layout._flash_errors')
                    <div class="contact-get-in-head">Get In Touch</div>
                    <div class="contact-get-in-cont">Experts provide consultation and on-site POS installation, account setup, hardware configuration.</div>
                    
                    <div class="form-group">
                        <input id="name" name="name" data-rule-required="true"  onkeyup="chk_validation(this)" value="{{old('name')}}" type="text" />
                        <label for="name">Full Name</label>
                        <span class="error" id="name" style="color:red">{{$errors->first('name')}}</span>
                    </div>
                    
                    <div class="form-group">
                        <input id="contact" name="contact" type="text" data-rule-required="true" data-rule-pattern="[- +()0-9]+" data-rule-minlength="7" data-rule-maxlength="16" data-msg-minlength="Please Enter at least 7 digits" data-msg-maxlength="Please Enter at most 16 digits" value="{{ old('contact') }}" />
                        <label for="contact">Contact Number</label>
                        <span class="error" id="contact" style="color:red">{{$errors->first('contact')}}</span>
                    </div>

                    <div class="form-group">
                        <input id="email" name="email" type="text" data-rule-required="true" data-rule-email="true" value="{{old('email')}}" />
                        <label for="email">Email Address</label>
                        <span class="error" id="email" style="color:red">{{$errors->first('email')}}</span>
                    </div>

                    <div class="form-group">
                        <input id="subject" name="subject" type="text" data-rule-required="true" value="{{old('subject')}}" data-rule-maxlength="100" data-msg-maxlength="Please enter at most 100 charachers" />
                        <label for="subject">Subject</label>
                        <span class="error" id="subject"  style="color:red">{{$errors->first('subject')}}</span>
                    </div>
                    
                    <div class="form-group">
                        <textarea  rows="2" class="text-area" id="message" name="message" data-rule-maxlength="300" data-msg-maxlength="Please enter at most 300 charachers" data-rule-required="true"></textarea>
                        <label for="message">Write Message</label>
                         <span class="error" id="message" style="color:red">{{$errors->first('message')}}</span>
                    </div>

                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="{{ config('app.project.RE_CAP_SITE') }}" data-size="invisible"></div>
                        <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">
                        <div class="error" id=""><?php echo Session('g-recaptcha-response'); ?></div>
                        <div class="error" id="error_captcha"><?php if(Session::has('Message')){ echo Session('Message'); } ?></div>
                    </div>

                    <div class="contact-btn">
                        <button class="login-btn" type="submit" name="btn_submit" id="btn_submit">Send Message</button>
                    </div>
                    <div class="clearfix"></div>

                </div>
            </div>
        </form>
        <div class="clearfix"></div>
    </div>

    <div class="contact-us-form-map-main">
        <div class="contact-us-map">
           <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCccvQtzVx4aAt05YnfzJDSWEzPiVnNVsY&q={{$site_data['site_address']}}&q={{$site_data['lat']}},{{$site_data['lon']}}"></iframe>
        </div>
    </div>
    <div class="clearfix"></div>

    <script type="text/javascript">
        $(document).ready(function() {
            jQuery('#ContactForm').validate({
                ignore: [],
                errorElement: 'div',
                highlight: function(element) { },
                errorPlacement: function(error, element) {
                    error.appendTo(element.parent());
                }
            });
        });

        $('#ContactForm').submit(function() {
            //var captcha_response = grecaptcha.getResponse();
            var captcha_response = grecaptcha.execute();
            if (captcha_response != '') {
                $("#hiddenRecaptcha").val('yes');
                return true;
            }
        });

        function chk_validation(ref) {
            var re = /[0-9`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
            var yourInput = $(ref).val();
            var isSplChar = re.test(yourInput);
            if(isSplChar) {
                var no_spl_char = yourInput.replace(/[0-9`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
                $(ref).val(no_spl_char);
            }
        }
    </script>
@endsection
  