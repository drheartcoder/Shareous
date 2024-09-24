@extends('front.layout.master')
@section('main_content')
<style type="text/css">
    .btn-cancel {
    
    margin-bottom: 10px;
}
</style>

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
                                <a href="{{ url('/') }}/my-booking/confirmed"><li class="resp-tab-item">Upcoming</li></a>
                                <a href="{{ url('/') }}/my-booking/completed"><li class="resp-tab-item resp-tab-active">Completed</li></a>
                                <a href="{{ url('/') }}/my-booking/cancelled"><li class="resp-tab-item">Cancelled</li></a>
                            </ul>
                            <div class="clearfix"></div>

                            <div class="resp-tabs-container margin">

                                @if(isset($arr_booking['data']) && !empty($arr_booking['data']))

                                    <div>
                                        @foreach($arr_booking['data'] as $property)

                                            @php
                                                
                                                $is_review_exist = 'NO';
                                                if(isset($property['property_details']['guest_review_details']) && count($property['property_details']['guest_review_details'])>0)
                                                {
                                                    $is_review_exist = 'YES';
                                                }
                                                $id = isset($property['id']) ? $property['id'] : '';
                                                $booking_id = isset($property['booking_id']) && $property['booking_id'] !='' ? $property['booking_id'] : 'B0';

                                                $title = isset($property['property_details']['property_name']) ? ucwords($property['property_details']['property_name']) : '';

                                                $property_type_name = isset($property['property_details']['property_type']['name']) ? '('.ucwords($property['property_details']['property_type']['name']).')' : '';

                                                $status = isset($property['booking_status']) ? $property['booking_status'] : '';
                                                if($status == 5){ $html_status = 'confirm'; $status = 'completed'; }

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

                                                $property_slug = isset($property['property_details']['property_name_slug']) ? $property['property_details']['property_name_slug'] : '';

                                                $property_details_url =  url('/').'/property/view/'.$property_slug.'?booking_id='.base64_encode(isset($property['id']) ? $property['id'] : '');

                                                $session_currency = \Session::get('get_currency');
                                                $currency_icon = \Session::get('get_currency_icon');
                                                if( $session_currency != 'INR' ){
                                                    $currency_amount = currencyConverterAPI('INR', $session_currency, $total_amount);
                                                }
                                                else {
                                                    $currency_amount = $total_amount;
                                                }

                                            @endphp

                                            <div class="box-white-user">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-8 col-lg-9">
                                                        <div class="user-id-new"><span>Id:</span> {{ $booking_id }} </div>

                                                        <a href="{{ $module_url_path }}/booking-details/{{ base64_encode(isset($property['id']) ? $property['id'] : '') }}"> <div class="heading-user-title">{{ $title.' '.$property_type_name }}</div></a>

                                                        <div class="status {{ $html_status }}">{{ ucfirst($status) }}</div>

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
                                                            <div class="users-nw-books"> <span>Total</span> <br/> {!! $currency_icon !!}{{ number_format($currency_amount, 2, '.', '') }}</div>
                                                            @if(Session::get('user_type') != null && Session::get('user_type') != '4')
                                                                @if(isset($is_review_exist) && $is_review_exist == 'NO')
                                                                    <a href="{{$property_details_url}}"><button type="button" class="btn-cancel">Add Review & Rating</button></a>
                                                                @elseif(isset($is_review_exist) && $is_review_exist == 'YES')
                                                                    <a href="{{$property_details_url}}"><button type="button" class="btn-cancel">View Review & Rating</button></a>
                                                                @endif
                                                            @endif
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
                                        <!-- <div class="content-list-vact" style="color: red;font-size: 13px;">
                                            <p>Sorry!, we couldn't find any Completed Booking!.</p>
                                        </div> -->
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
                                            <textarea id="cancel_reason" name="cancel_reason" data-rule-required="true" data-rule-minlength="3"  data-rule-maxlength="50"  /></textarea>
                                            <label for="add-bank-name-id">Reason</label> 
                                            <span class='error help-block' id="err_cancel_reason">{{ $errors->first('reason') }}</span>    
                                        </div>

                                        <input type="hidden" name="cancel_subject" id="cancel_subject" value="Cancel Booking" />
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

    <script type="text/javascript">
        $(document).ready(function(){
            $("#btn_submit_cancel_process").click(function(){
                var cancel_reason = $('#cancel_reason').val();
                if(cancel_reason == '')
                {
                    $('#err_cancel_reason').show();
                    $('#err_cancel_reason').html('Please enter cancel reason');
                    $('#err_cancel_reason').fadeOut(8000);
                    $('#cancel_reason').focus();
                    return false;
                }
            });
        });

        function cancel_booking(id)
        {
            swal({
                title: "Are you sure",
                text: "Do you want to cancel booking?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-cancel",
                confirmButtonText: "Yes, cancel it!",
                confirmButtonColor: "#ff4747",
                closeOnConfirm: false
            },
            function()
            {
                $('.cancel').click();
                $('#CancelPopup').modal();
                $("#cancel_booking_id").val(id);
            });
        }
    </script>

@endsection