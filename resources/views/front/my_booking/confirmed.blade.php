@extends('front.layout.master')
@section('main_content')

    <div class="clearfix"></div>
    <div class="overflow-hidden-section">
        <div class="titile-user-breadcrum">
            <div class="container">
                <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions">
                    <h1>My Bookings</h1>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        @if(isset($arr_user) && !empty($arr_user))
            @php
                $user_id            = isset($arr_user['id']) ? $arr_user['id'] : '';
                $user_first_name    = isset($arr_user['first_name']) ? $arr_user['first_name'] : '';
                $user_last_name     = isset($arr_user['last_name']) ? $arr_user['last_name'] : '';
                $user_mobile_number = isset($arr_user['mobile_number']) ? $arr_user['mobile_number'] : '';
                $user_email         = isset($arr_user['email']) ? $arr_user['email'] : '';
                $user_wallet        = isset($arr_user['wallet_amount']) ? $arr_user['wallet_amount'] : '';
            @endphp
        @endif

        <div class="change-pass-bg main-hidden">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 left-bar-dashboard">
                        @include('front.layout.left_bar_host')
                    </div>
                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                        @include('front.layout._flash_errors')
                        <div id="horizontalTab" class="tab-for-responsive">
                            <ul class="resp-tabs-list small tab-tree-cal">
                                <a href="{{ url('/') }}/my-booking/confirmed"><li class="resp-tab-item resp-tab-active"> Upcoming </li></a>
                                <a href="{{ url('/') }}/my-booking/completed"><li class="resp-tab-item"> Completed </li></a>
                                <a href="{{ url('/') }}/my-booking/cancelled"><li class="resp-tab-item"> Cancelled </li></a>
                            </ul>
                            <div class="clearfix"></div>

                            <div class="resp-tabs-container margin">
                                @if(isset($arr_booking['data']) && !empty($arr_booking['data']))
                                    <div>
                                        @foreach($arr_booking['data'] as $property)

                                            @php
                                                $id = isset($property['id']) ? $property['id'] : '';

                                                 $booking_id = isset($property['booking_id']) && $property['booking_id'] !='' ? $property['booking_id'] : 'B0';

                                                $property_id = isset($property['property_details']['id']) ? $property['property_details']['id'] : '';

                                                $title = isset($property['property_details']['property_name']) ? ucwords($property['property_details']['property_name']) : '';

                                                $property_type_name = isset($property['property_details']['property_type']['name']) ? '('.ucwords($property['property_details']['property_type']['name']).')' : '';

                                                // $status = isset($property['booking_status']) ? $property['booking_status'] : '';
                                                // if($status == 1 || $status == 2){ $html_status = 'accepted'; $status = 'accepted'; }

                                                $booking_date = isset($property['created_at']) ? $property['created_at'] : '';
                                                if(!empty($booking_date) && $booking_date != null)
                                                { $booking_date = get_added_on_date($booking_date); }

                                                $check_in_date = isset($property['check_in_date']) ? $property['check_in_date'] : '';
                                                if(!empty($check_in_date) && $check_in_date != null)
                                                { $check_in_date = get_added_on_date($check_in_date); }

                                                $check_out_date = isset($property['check_out_date']) ? $property['check_out_date'] : '';
                                                if(!empty($check_out_date) && $check_out_date != null)
                                                { $check_out_date = get_added_on_date($check_out_date); }

                                                $currency = isset($property['property_details']['currency']) ? $property['property_details']['currency'] : '';

                                                $total_amount = isset($property['total_amount']) ? number_format($property['total_amount'],'2','.','' ) : '';

                                                $currency_code = isset($property['property_details']['currency_code']) ? $property['property_details']['currency_code'] : '';

                                                $session_currency = \Session::get('get_currency');
                                                $currency_icon = \Session::get('get_currency_icon');
                                                if( $session_currency != 'INR' ){
                                                    $currency_amount = currencyConverterAPI('INR', $session_currency, $total_amount);
                                                }
                                                else {
                                                    $currency_amount = $total_amount;
                                                }

                                                if($currency_code != 'INR' && $currency != '') {
                                                    $inr_currency = currencyConverter($currency_code, 'INR', $total_amount);
                                                } else {
                                                    $inr_currency = $total_amount;
                                                }

                                                $needed_amount = $inr_currency - $user_wallet;
                                                $property_currency = currencyConverter('INR', $currency_code, $needed_amount);
                                            @endphp

                                            <div class="box-white-user">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-8 col-lg-9">
                                                        <div class="user-id-new"><span>Id:</span> {{ $booking_id }} </div>

                                                        <a href="{{ $module_url_path }}/booking-details/{{ base64_encode(isset($property['id']) ? $property['id'] : '') }}"> <div class="heading-user-title">{{ $title.' '.$property_type_name }}</div></a>

                                                        <div class="clearfix"></div>
                                                        <div class="box-main-bx">
                                                            <div class="li-boxss">
                                                                Booking Date
                                                                <span>{{ $booking_date }}</span>
                                                            </div>
                                                            <div class="li-boxss">
                                                                Check In
                                                                <span>{{ $check_in_date }}</span>
                                                            </div>
                                                            <div class="li-boxss">
                                                                Check Out
                                                                <span>{{ $check_out_date }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-4 col-lg-3">
                                                        <div class="txt-rightside">
                                                            <div class="users-nw-books"> <span>Total</span><br/> {!! $currency_icon !!}{{ number_format($currency_amount, 2, '.', '') }}</div>
                                                            <input type="hidden" name="inr_currency" class="inr_currency" value="{{ $inr_currency }}">
                                                            <button type="button" class="btn-cancel" onclick='cancel_booking("{{ base64_encode($id) }}")'>Cancel</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>

                                        @endforeach
                                    </div>

                                @else
                                    <div class="list-vactions-details">
                                        <div class="no-record-found"></div>
                                    </div>
                                @endif

                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(isset($obj_pagination) && $obj_pagination!=null)            
                    @include('front.common.pagination',['obj_pagination' => $obj_pagination])
                @endif

            </div>
        </div>
    </div>

    <!--cancel booking popup start here-->
    <div class="host-contact-popup upgrade payment">
        <div class="popup-inquiry-form">
            <div id=CancelPopup class="modal fade" data-backdrop="static" role=dialog>
                <div class=modal-dialog>
                    <div class=modal-content>
                        <div class="modal-header black-close">
                            <button type=button class=close data-dismiss=modal>
                                <span class="contact-left-img popup-close nonebg"><img src="{{url('/')}}/front/images/popup-close-btn.png" alt=""></span>
                            </button>
                            <h4 class=modal-title>Booking Cancel</h4>
                        </div>
                        <div class=modal-body>                           
                            <div class="payment-detail-tab-one">

                                <form name="frm_add_account" action="{{ url('/') }}/my-booking/cancel/process" id="frm_add_account" method="POST">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">

                                        <div class="form-group">
                                            <div class="select-style">
                                                <select id="cancel_subject" name="cancel_subject" data-rule-required="true">
                                                    <option value="">Select Cancel Reason</option>
                                                    <option value="Change/Cancellation of Itinerary">Change/Cancellation of Itinerary</option>
                                                    <option value="Unsatisfactory Reviews">Unsatisfactory Reviews</option>
                                                    <option value="Other Reason">Other Reason</option>
                                                </select>
                                                <div class="error" id="err_cancel_subject" style="color:red">{{ $errors->first('cancel_subject') }}</div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <textarea id="cancel_reason" name="cancel_reason" data-rule-required="true" data-rule-minlength="3"  data-rule-maxlength="50"  /></textarea>
                                            <label for="add-bank-name-id">Reason</label> 
                                            <div class='error' id="err_cancel_reason">{{ $errors->first('cancel_subject') }}</div>
                                        </div>

                                        <!-- <input type="hidden" name="cancel_subject" id="cancel_subject" value="Cancel Booking" /> -->
                                        <input type="hidden" name="cancel_booking_id" id="cancel_booking_id" />

                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="change-pass-btn">
                                            <a class="login-btn cancel" data-dismiss=modal href="javascript:void(0)">Cancel</a>
                                            <input type="submit" class="login-btn" name="btn_submit_cancel_process" value="Submit" id="btn_submit_cancel_process">
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

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#btn_submit_cancel_process").click(function(){
                var cancel_subject = $('#cancel_subject').val();
                var cancel_reason = $('#cancel_reason').val();
                if(cancel_subject == '') {
                    $('#err_cancel_subject').show();
                    $('#err_cancel_subject').html('Please select cancel subject');
                    $('#err_cancel_subject').fadeOut(8000);
                    $('#cancel_subject').focus();
                    return false;
                }
                if(cancel_reason == '') {
                    $('#err_cancel_reason').show();
                    $('#err_cancel_reason').html('Please enter cancel reason');
                    $('#err_cancel_reason').fadeOut(8000);
                    $('#cancel_reason').focus();
                    return false;
                }
            });
        });

        function payment_process(ref)
        {
            $(ref).hide();
            $(ref).next().show();

            var payment_amount = $(ref).attr('data-amount');

            var id = $(ref).attr('data-id');

            var amount = payment_amount * 100;

            var options = {
                "key": "{{ config('app.project.RAZOR_ID') }}",
                "amount": parseFloat(amount).toFixed(2), // 2000 paise = INR 20
                "name": "{{ config('app.project.name') }}",
                "description": "{{ config('app.project.description') }}",
                "image": "{{url('/')}}/front/images/logo.png",
                "handler": function (response)
                {
                    if(response.razorpay_payment_id != null)
                    {
                        
                        var token = $('input[name="_token"]').val();
                        $.ajax({
                            'url':SITE_URL+'/my-booking/payment',                    
                            'type':'post',
                            //'dataType':'json',
                            'data':{_token: token, transaction_id: response.razorpay_payment_id, payment_amount: payment_amount, booking_id: id,page:'list' },
                            success:function(res)
                            {
                               location.reload();
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
                "theme": {
                    "color": "#ff4747"
                },
                "modal": {
                    "ondismiss": function(){
                        $(ref).show();
                        $(ref).next().hide();
                    }
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
        }

        function cancel_booking(id)
        {
            swal({
                title: "Are you sure",
                text: "Do you want to cancel booking?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-cancel",
                confirmButtonText: "Yes, cancel it!",
                closeOnConfirm: false
            },
            function()
            {
                $('.cancel').click();
                $('#CancelPopup').modal();
                $("#cancel_booking_id").val(id);
            });
        }

        function wallet_payment(ref)
        {
            var msg_text = '';
            $(ref).hide();
            $(ref).prev().show();

            swal({
                title: "Are you sure",
                text: "Do you want to payment through wallet?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-cancel",
                confirmButtonText: "Yes, process payment!",
                confirmButtonColor: "#ff4747",
                closeOnConfirm: false
            },
            function(isConfirm)
            {
                if (isConfirm)
                {
                    swal({ title: "Processing...!", text: "Please wait", confirmButtonColor: "#ff4747", showConfirmButton: false, });

                    var booking_id        = $(ref).attr('data-id');
                    var booking_amount    = $(ref).attr('data-amount');
                    var wallet_amount     = $(ref).attr('data-wallet');
                    var needed_inr_amount = $(ref).attr('data-needed_inr');
                    var needed_amount     = $(ref).attr('data-needed');
                    var currency          = $(ref).attr('data-currency');

                    if(currency == 'INR')
                    {
                        msg_text = "You have insufficient balance in wallet. Please add "+ parseFloat(needed_inr_amount) +" INR amount to wallet or try through online payment";
                    }
                    else
                    {
                        msg_text = "You have insufficient balance in wallet. Please add "+ parseFloat(needed_inr_amount) +" INR ("+ parseFloat(needed_amount) +' '+currency +") amount to wallet or try through online payment";
                    }

                    if(parseFloat(booking_amount) < parseFloat(wallet_amount))
                    {
                        var token = $('input[name="_token"]').val();
                        $.ajax({
                            'url':SITE_URL+'/my-booking/payment/wallet',
                            'type':'post',
                            'data':{_token: token, booking_amount: booking_amount, booking_id: booking_id, wallet_amount:wallet_amount },
                            success:function(res)
                            {
                                location.reload();
                            }
                        });
                    }
                    else
                    {
                        swal({
                            title: 'Sorry !',
                            text: msg_text,
                            type: "warning",
                            customClass:'NewErrorMsgFont',
                            confirmButtonColor: "#ff4747",
                            confirmButtonText: 'Okay'
                        });

                        $(ref).show();
                        $(ref).prev().hide();
                    }
                }
                else
                {
                    $(ref).show();
                    $(ref).prev().hide();
                }
            });
        }
    </script>

@endsection