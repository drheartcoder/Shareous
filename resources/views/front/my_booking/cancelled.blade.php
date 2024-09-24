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
                                <a href="{{ url('/') }}/my-booking/completed"><li class="resp-tab-item">Completed</li></a>
                                <a href="{{ url('/') }}/my-booking/cancelled"><li class="resp-tab-item resp-tab-active">Cancelled</li></a>
                            </ul>
                            <div class="clearfix"></div>

                            <div class="resp-tabs-container margin">

                                @if(isset($arr_booking['data']) && !empty($arr_booking['data']))
                                    <div>
                                        @foreach($arr_booking['data'] as $property)
                                            <?php
                                                $html_status = $status =  '';
                                                $id = isset($property['id']) ? $property['id'] : '';
                                                $booking_id = isset($property['booking_id']) && $property['booking_id'] !='' ? $property['booking_id'] : 'B0';
                                                $title = isset($property['property_details']['property_name']) ? ucwords($property['property_details']['property_name']) : '';
                                                $property_type_name = isset($property['property_details']['property_type']['name']) ? '('.ucwords($property['property_details']['property_type']['name']).')' : '';
                                                $status = isset($property['booking_status']) ? $property['booking_status'] : '';

                                                $cancelled_reason = isset($property['cancelled_reason']) ? $property['cancelled_reason'] : '';

                                                $cancel_status = '';
                                                if ( $property['cancelled_by'] == null && !empty($cancelled_reason) && $cancelled_reason != null ) 
                                                {
                                                    if($status == 6){ $html_status = 'cancelled-by'; $cancel_status = 'cancelled by system'; }
                                                    if($status == 7){ $html_status = 'cancelled-by'; $cancel_status = 'requested by system'; }
                                                }
                                                else if ( $property['cancelled_by'] == '1') 
                                                {
                                                    if($status == 6){ $html_status = 'cancelled-by'; $cancel_status = 'cancelled by you'; }
                                                    if($status == 7){ $html_status = 'cancelled-by'; $cancel_status = 'requested by you'; }
                                                }
                                                else
                                                {
                                                    if($status == 6){ $html_status = 'cancelled-by'; $cancel_status = 'cancelled by host'; }
                                                    if($status == 7){ $html_status = 'cancelled-by'; $cancel_status = 'requested by host'; }
                                                }
                                                

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
                                                $refund_amount = isset($property['refund_amount']) ? number_format($property['refund_amount'],'2','.','' ) : '';

                                                $cancelled_date = isset($property['cancelled_date']) && $property['cancelled_date']!= '0000-00-00' ? get_added_on_date($property['cancelled_date']) : 'N/A';

                                                $session_currency = \Session::get('get_currency');
                                                $currency_icon = \Session::get('get_currency_icon');
                                                if( $session_currency != 'INR' ){
                                                    $currency_amount = currencyConverterAPI('INR', $session_currency, $total_amount);

                                                    $currency_refund_amount = currencyConverterAPI('INR', $session_currency, $refund_amount);
                                                }
                                                else {
                                                    $currency_amount = $total_amount;
                                                    $currency_refund_amount = $refund_amount;
                                                }
                                        
                                            ?>

                                            <div class="box-white-user">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-8 col-lg-9">
                                                        <div class="user-id-new"><span>Id:</span> {{ $booking_id }} </div>

                                                        <a href="{{ $module_url_path }}/booking-details/{{ base64_encode(isset($property['id']) ? $property['id'] : '') }}"> <div class="heading-user-title">{{ $title.' '.$property_type_name }}</div></a>
                                                        <div class="status {{ $html_status }}">{{ ucfirst($cancel_status) }}</div>

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
                                                             <div class="li-boxss">
                                                                Cancelled Date
                                                                <span>{{ $cancelled_date }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-4 col-lg-3">
                                                        <div class="txt-rightside">
                                                            <div class="users-nw-books"> <span>Total</span> <br/> {!! $currency_icon !!}{{ number_format($currency_amount, 2, '.', '') }}</div>
                                                            @if($currency_refund_amount!=0)
                                                            <div class="subtitles"><span>Refund </span>
                                                             {!! $currency_icon !!}{{ number_format($currency_refund_amount, 2, '.', '') }}</div>
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
                                        <p>Sorry!, we couldn't find any Cancelled Booking!.</p>
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

@endsection