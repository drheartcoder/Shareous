@extends('front.layout.master')                
@section('main_content')
    
    <div class="clearfix"></div>
    <div class="overflow-hidden-section">
        <div class="titile-user-breadcrum">
            <div class="container">
                <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions">
                    <h1>My Wallet</h1>
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

                    <?php
                        $user_id            = isset($arr_user['id']) ? $arr_user['id'] : '';
                        $user_first_name    = isset($arr_user['first_name']) ? $arr_user['first_name'] : '';
                        $user_last_name     = isset($arr_user['last_name']) ? $arr_user['last_name'] : '';
                        $user_mobile_number = isset($arr_user['mobile_number']) ? $arr_user['mobile_number'] : '';
                        $user_email         = isset($arr_user['email']) ? $arr_user['email'] : '';
                        $user_wallet_amount = isset($arr_user['wallet_amount']) ? $arr_user['wallet_amount'] : '';
                        $amount_needed      = (null !== \Request::input('amount_needed') ) ? \Request::input('amount_needed') : '';
                    ?>

                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                        @include('front.layout._flash_errors')
                        <div class="change-pass-bady for-wallet">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-6">
                                    <div class="price-payment-detail small-text">
                                        <span><i class="fa fa-inr" aria-hidden="true"></i>{{ number_format($user_wallet_amount, 2, '.', '') }}</span>
                                    </div>
                                    <div class="my-balance-payment">
                                        <span class="contact-left-img"></span>
                                        <span class="my-balance-payment-text">My Balance </span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-6">

                                    <div class="form-group">
                                        <input id="payment_amount" type="text" maxlength="16" value="{{ $amount_needed }}" />
                                        <label for="payment_amount">Enter Amount</label>
                                        <div id="err_payment_amount" style="display: none; color: red;"></div>
                                    </div>

                                    <div class="supgread-check">
                                        <div class="user_box1">
                                            <div class="check-box inline-checkboxs">
                                                <input id="chk_terms_conditions" class="filled-in" checked="checked" type="checkbox" />
                                                <label for="chk_terms_conditions"> Acceptance of <a href="{{url('terms-conditions')}}">Terms & Conditions</a></label>
                                                <div id="err_terms_conditions" style="display: none; color: red;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="viewmores-btn-main payment">
                                        <a href="javascript:void(0)" id="btn_confirm_payment" class="viewmores">Confirm Payment</a>
                                        <a href="javascript:void(0)" id="btn_process_payment" class="viewmores" style="display: none;"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></a>
                                    </div>

                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php
        $property_name_slug = (null !== \Request::input('property_name_slug') ) ? \Request::input('property_name_slug') : '';

        $razorpay_credentials = get_razorpay_credential();
        $razorpay_id          = (isset($razorpay_credentials) && $razorpay_credentials['razorpay_id'] != '') ? $razorpay_credentials['razorpay_id'] : config('app.razorpay_credentials.razorpay_id');
        $razorpay_secret      = (isset($razorpay_credentials) && $razorpay_credentials['razorpay_secret'] != '') ? $razorpay_credentials['razorpay_secret'] : config('app.razorpay_credentials.razorpay_secret');
    ?>

    <!-- <script src="https://checkout.razorpay.com/v1/checkout.js"></script> -->
    <script src="{{ url('/') }}/front/js/checkout.js"></script>
    <script>
    $(document).ready(function(){

        $('#payment_amount').keydown(function(){
            $(this).val($(this).val().replace(/[^\d]/,''));
            $(this).keyup(function(){
                $(this).val($(this).val().replace(/[^\d]/,''));
            });
        });

        $('#payment_amount').keypress(function(e) {
            if(e.keyCode == '13') {
                e.preventDefault();
                $('#btn_confirm_payment').click();
            }
        });

        document.getElementById('btn_confirm_payment').onclick = function(e){
            var payment_amount   = $('#payment_amount').val();
            var terms_conditions = $("#chk_terms_conditions").is(":checked");

            if(payment_amount == '') {
                $("#err_payment_amount").show();
                $("#err_payment_amount").html("Amount cannot be empty");
                $("#payment_amount").focus();
                $('#err_payment_amount').fadeOut(8000);
            }
            if(terms_conditions == false)
            {
                $("#err_terms_conditions").show();
                $("#err_terms_conditions").html("Terms & Conditions should be accepted");
                $("#chk_terms_conditions").focus();
                $('#err_terms_conditions').fadeOut(8000);
            }
            if(payment_amount != '' && terms_conditions == true)
            {
                $('#btn_confirm_payment').hide();
                $('#btn_process_payment').show();

                var options = {
                    "key": "{{ $razorpay_id }}",
                    "amount": payment_amount * 100, // 2000 paise = INR 20
                    "name": "{{ config('app.project.name') }}",
                    "description": "{{ config('app.project.description') }}",
                    //"image": "{{url('/')}}/front/images/logo.png",
                    "handler": function (response)
                    {
                        if(response.razorpay_payment_id != null)
                        {
                            var token = $('input[name="_token"]').val();
                            $.ajax({
                                'url':SITE_URL+'/wallet/payment/store',                    
                                'type':'post',
                                'data':{_token:token, transaction_id:response.razorpay_payment_id, payment_amount:payment_amount },
                                success:function(response)
                                {
                                    $("#payment_amount").val('');

                                    var property_name_slug = "{{ $property_name_slug }}";
                                    if( $.trim( property_name_slug ) != '' ) {
                                        window.location.href = '{{ url("/") }}/property/view/'+window.atob(property_name_slug)+'?wallet_payment=success';
                                    }
                                    else {
                                        window.location.href = '{{ url("/") }}/wallet';
                                        //location.reload();
                                    }
                                }
                            });
                        }
                    },
                    "prefill": {
                        "name": "{{ $user_first_name }}.' '.{{ $user_last_name }}",
                        "email": "{{ $user_email }}",
                        "contact": "{{ $user_mobile_number }}"
                    },
                    "notes": {
                        "address": "Hello World"
                    },
                    /*"theme": {
                        "color": "#ff4747"
                    },*/
                    "modal": {
                        "ondismiss": function(){
                            $('#btn_process_payment').hide();
                            $('#btn_confirm_payment').show();
                        }
                    }
                };

                var rzp1 = new Razorpay(options);

                rzp1.open();
                e.preventDefault();
            }
        }
    });
    </script>

@endsection
