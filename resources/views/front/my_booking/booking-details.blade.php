@extends('front.layout.master')
@section('main_content')
<style type="text/css">
.btn-cancel { margin-bottom: 10px; }
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
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 left-bar-dashboard">@include('front.layout.left_bar_host')</div>
                <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                    @include('front.layout._flash_errors')

                    @if(isset($arr_booking) && !empty($arr_booking))
                    @foreach($arr_booking as $booking) 
                    <?php
                        $total = $count = $no_reviews = $gst = 0;
                        $tmp_str_rating = '';

                        if (isset($arr_property_review)) {
                            foreach($arr_property_review as $rating) {
                                if ($rating['property_id'] == $booking['property_details']['id']) {
                                    $total  += floatval($rating['rating']);
                                    $count++;
                                }
                            }
                        }

                        if ($count != 0) {
                            $no_reviews = number_format(( $total / $count ),1);
                        }

                        $property_name = isset($booking['property_details']['property_name']) ? ucwords($booking['property_details']['property_name']) : '';

                        $property_type_name = isset($booking['property_details']['property_type']['name']) ? '('.ucwords($booking['property_details']['property_type']['name']).')' : '';
                    ?>
                <div class="booking-details-top">
                   <div class="details-title-boob-wrapper">
                    <div class="details-title">{{ $property_name.' '.$property_type_name }}</div>

                    <div class="details-star-box">
                        <div class="start-details detils-booking">
                            <?php
                                $starNumber = $no_reviews;
                                for($x=1;$x<=$starNumber;$x++) {
                                    echo '<img src="'.url('/').'/front/images/star1.png" />';
                                }
                                if (strpos($starNumber,'.')) {
                                    echo '<img src="'.url('/').'/front/images/half-star.png" />';
                                    $x++;
                                }
                                while ($x <= 5) {
                                    echo '<img src="'.url('/').'/front/images/star2.png" />';
                                    $x++;
                                }
                            ?>
                            <div class="star-review-txt">(<?php echo  $no_reviews ;?>)</div>
                            <div class="details-location"> <i class="fa fa-map-marker"></i>{{ $booking['property_details']['address'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="transact-deta-back button">
                  <a href="{{ isset($previous_url) ? $previous_url : '' }}" class="bookib-detai-back"><i class="fa fa-long-arrow-left"></i> Back</a>
              </div>
              <div class="clearfix"></div>
          </div>
          <?php
                $html_status      = '';
                $id               = isset($booking['id']) ? $booking['id'] : '';
                $booking_id       = isset($booking['booking_id']) && $booking['booking_id'] !='' ? $booking['booking_id'] : 'B0';
                $status           = isset($booking['booking_status']) ? $booking['booking_status'] : '';
                $check_in         = isset($booking['check_in_date']) ? $booking['check_in_date'] : '';
                $today_date       = isset($current_date) ? $current_date : '';
                $cancelled_reason = isset($booking['cancelled_reason']) ? $booking['cancelled_reason'] : '';

                $cancel_btn = "display: block;";
                $review_btn = "display: block;";

                if($status == 1){ $html_status = 'accepted'; $status = 'accepted'; }
                else if($status == 2){ $html_status = 'accepted'; $status = 'accepted'; }
                else if($status == 3){ $html_status = 'awaiting'; $status = 'awaiting'; }
                else if($status == 4){ $html_status = 'cancelled-by'; $status = 'rejected'; }
                else if($status == 5 && $check_in >= $today_date ){ $html_status = ''; $status = ''; $review_btn = "display: none;";  }
                else if($status == 5 && $check_in < $today_date ){ $html_status = 'confirm'; $status = 'completed'; $cancel_btn = "display: none;"; }

                           if($status == 6){
                                if ( $booking['cancelled_by'] == null && !empty($cancelled_reason) && $cancelled_reason != null ) {
                                    $html_status = 'cancelled-by'; $status = 'cancelled by system';
                                }
                                else if ($booking['cancelled_by'] == '1') {
                                    $html_status = 'cancelled-by'; $status = 'cancelled by you'; 
                                }
                                else {
                                    $html_status = 'cancelled-by'; $status = 'cancelled by host'; 
                                }
                            }
                            if($status == 7) {
                                if ($booking['cancelled_by'] == '1') {
                                    $html_status = 'cancelled-by'; $status = 'requested by you'; 
                                }
                                else {
                                    $html_status = 'cancelled-by'; $status = 'requested by host';    
                                }
                            }

                            $booking_date = isset($booking['created_at']) ? $booking['created_at'] : '';

                            if (!empty($booking_date) && $booking_date != null) {
                                $booking_date = get_added_on_date($booking_date);
                            }

                            $check_in_date = isset($booking['check_in_date']) ? $booking['check_in_date'] : '';
                            if (!empty($check_in_date) && $check_in_date != null) {
                                $check_in_date = get_added_on_date($check_in_date);
                            }

                            $check_out_date = isset($booking['check_out_date']) ? $booking['check_out_date'] : '';
                            if (!empty($check_out_date) && $check_out_date != null) {
                                $check_out_date = get_added_on_date($check_out_date);
                            }

                            $currency           = isset($booking['property_details']['currency']) ? $booking['property_details']['currency'] : '';
                            $total_amount       = isset($booking['total_amount']) ? number_format($booking['total_amount'],'2','.','' ) : '';
                            $currency_code      = isset($booking['property_details']['currency_code']) ? $booking['property_details']['currency_code'] : '';
                            $refund_amount      = isset($booking['refund_amount']) ? number_format($booking['refund_amount'],'2','.','' ) : '';

                            if ($currency_code != 'INR' && $currency != '') {
                                $inr_currency = currencyConverter($currency_code, 'INR', $total_amount);
                                $single_currency = currencyConverter($currency_code, 'INR', '1');
                                
                            } else {
                                $inr_currency = $total_amount;
                                
                            }

                            $needed_amount = $inr_currency - $user_wallet;
                            $property_currency = currencyConverter('INR', $currency_code, $needed_amount);

                            $property_slug = isset($booking['property_details']['property_name_slug']) ? $booking['property_details']['property_name_slug'] : '';

                            $property_details_url =  url('/').'/property/view/'.$property_slug.'?booking_id='.base64_encode(isset($booking['id']) ? $booking['id'] : '');

                            $reject_reason = isset($booking['reject_reason']) ? $booking['reject_reason'] : '';

                            $session_currency = \Session::get('get_currency');
                            $currency_icon = \Session::get('get_currency_icon');

                            if( $session_currency != 'INR' ) {
                                $currency_refund_amount = currencyConverterAPI('INR', $session_currency, $refund_amount);
                                $currency_amount = currencyConverterAPI('INR', $session_currency, $total_amount);
                            }
                            else {
                                $currency_amount = $total_amount;
                                $currency_refund_amount = $refund_amount;
                            }

                            $property_type_slug = $booking['property_type_slug'];
                            if($property_type_slug == 'warehouse') {
                                $booking_for = 'No. of Slots';
                                $no_of_guest = isset($booking['selected_no_of_slots']) ? $booking['selected_no_of_slots'] : '';
                            }
                            elseif ($property_type_slug == 'office-space') {
                                
                                $no_of_employee = isset($booking['selected_of_employee']) ? $booking['selected_of_employee'] : '';
                                $no_of_room     = isset($booking['selected_of_room']) ? $booking['selected_of_room'] : '';
                                $no_of_desk     = isset($booking['selected_of_desk']) ? $booking['selected_of_desk'] : '';
                                $no_of_cubicles = isset($booking['selected_of_cubicles']) ? $booking['selected_of_cubicles'] : '';

                                $booking_for_employee = 'No. of People';
                                $booking_for_room     = 'No. of Room';
                                $booking_for_desk     = 'No. of Desk';
                                $booking_for_cubicles = 'No. of Cubicles';
                            }
                            else {
                                $booking_for = 'No. of Guests';
                                $no_of_guest = isset($booking['no_of_guest']) ? $booking['no_of_guest'] : '';
                            }
                            ?>
                            <style type="text/css">
                            .heading-user-title:hover
                            {
                                cursor: default;
                                text-decoration: none;
                            }
                        </style>
                        <div class="box-white-user">
                            <div class="row">
                                <div class="col-sm-12 col-md-8 col-lg-9">
                                    <div class="user-id-new"><span>Id:</span> {{ $booking_id }}</div>
                                    <!-- <div class="heading-user-title"></div> -->
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

                                        @if( $property_type_slug != 'office-space' )
                                        <div class="li-boxss">
                                            {{ $booking_for }}
                                            <span>{{ $no_of_guest }}</span>
                                        </div>
                                        @else
                                            @if( $no_of_employee != 0 )
                                                <div class="li-boxss">
                                                    {{ $booking_for_employee }}
                                                    <span>{{ $no_of_employee }}</span>
                                                </div>
                                            @endif
                                            @if( $no_of_room != 0 )
                                                <div class="li-boxss">
                                                    {{ $booking_for_room }}
                                                    <span>{{ $no_of_room }}</span>
                                                </div>
                                            @endif
                                            @if( $no_of_desk != 0 )
                                                <div class="li-boxss">
                                                    {{ $booking_for_desk }}
                                                    <span>{{ $no_of_desk }}</span>
                                                </div>
                                            @endif
                                            @if( $no_of_cubicles != 0 )
                                                <div class="li-boxss">
                                                    {{ $booking_for_cubicles }}
                                                    <span>{{ $no_of_cubicles }}</span>
                                                </div>
                                            @endif
                                        @endif

                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-3">
                                    <div class="txt-rightside">

                                        <div class="users-nw-books"> <span>Total</span> <br/> {!! $currency_icon !!}{{ number_format($currency_amount, 2, '.', '') }}</div>
                                        <input type="hidden" name="inr_currency" class="inr_currency" value="{{ $inr_currency }}">

                                        @if($currency_refund_amount!=0)

                                        <div class="subtitles"><span>Refund </span>
                                        {!! $currency_icon !!}{{ number_format($currency_refund_amount, 2, '.', '') }}</div>
                                        @endif

                                        @if(Session::get('user_type') != null &&  Session::get('user_type') == '1' && $booking['booking_status'] == '5')
                                            <a href="{{$property_details_url}}" style="{{ $review_btn }}"><button type="button" class="btn-cancel">Add Review & Rating</button></a>
                                        @endif
                                        @if(Session::get('user_type') != null &&  Session::get('user_type') == '1' && $booking['booking_status'] != '3' && $booking['booking_status'] != '4' && $booking['booking_status'] != '6' && $booking['booking_status'] != '7')
                                            <button type="button" class="btn-cancel" onclick='cancel_booking("{{ base64_encode($id) }}")' style="{{ $cancel_btn }}">Cancel</button>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="service-fee-bx detils">
                                        <div class="fee-bx-right">

                                        <?php
                                        //Change by kavita
                                        
                                        $property_area = isset( $booking['property_details']['property_area'] ) && !empty( $booking['property_details']['property_area'] ) ? $booking['property_details']['property_area'] : '0';
                                        $no_of_slots = isset( $booking['property_details']['no_of_slots'] ) && !empty( $booking['property_details']['no_of_slots'] ) ? $booking['property_details']['no_of_slots'] : '0';

                                        $selected_slots = isset( $booking['selected_no_of_slots'] ) && !empty( $booking['selected_no_of_slots'] ) ? $booking['selected_no_of_slots'] : '0';

                                        $selected_employee = isset( $booking['selected_of_employee'] ) && !empty( $booking['selected_of_employee'] ) ? $booking['selected_of_employee'] : '0';

                                        $selected_room = isset( $booking['selected_of_room'] ) && !empty( $booking['selected_of_room'] ) ? $booking['selected_of_room'] : '0';

                                        $selected_desk = isset( $booking['selected_of_desk'] ) && !empty( $booking['selected_of_desk'] ) ? $booking['selected_of_desk'] : '0';

                                        $selected_cubicles = isset( $booking['selected_of_cubicles'] ) && !empty( $booking['selected_of_cubicles'] ) ? $booking['selected_of_cubicles'] : '0';

                                        $selected_guest = isset( $booking['no_of_guest'] ) && !empty( $booking['no_of_guest'] ) ? $booking['no_of_guest'] : '0';

                                        $price_per_sqft = isset( $booking['property_details']['price_per_sqft'] ) && !empty( $booking['property_details']['price_per_sqft'] ) ? $booking['property_details']['price_per_sqft'] : '0';

                                        $price_per_office = isset( $booking['property_details']['price_per_office'] ) && !empty( $booking['property_details']['price_per_office'] ) ? $booking['property_details']['price_per_office'] : '0';

                                        $price_per_night = isset( $booking['property_details']['price_per_night'] ) && !empty( $booking['property_details']['price_per_night'] ) ? $booking['property_details']['price_per_night'] : '0';

                                        $no_of_days = isset( $booking['no_of_days'] ) && !empty( $booking['no_of_days'] ) ? $booking['no_of_days'] : '0';

                                        $total_night_price = isset( $booking['total_night_price'] ) && !empty( $booking['total_night_price'] ) ? $booking['total_night_price'] : '0';

                                        $total_amount = isset( $booking['total_amount'] ) && !empty( $booking['total_amount'] ) ? $booking['total_amount'] : '0';

                                        $currency_code = isset( $booking['property_details']['currency_code'] ) && !empty( $booking['property_details']['currency_code'] ) ? $booking['property_details']['currency_code'] : '';

                                        $room_amount     = isset( $booking['room_amount'] ) && !empty( $booking['room_amount'] ) ? $booking['room_amount'] : '0';
                                        $desk_amount     = isset( $booking['desk_amount'] ) && !empty( $booking['desk_amount'] ) ? $booking['desk_amount'] : '0';
                                        $cubicles_amount = isset( $booking['cubicles_amount'] ) && !empty( $booking['cubicles_amount'] ) ? $booking['cubicles_amount'] : '0';

                                        $gst_amount = isset( $booking['gst_amount'] ) && !empty( $booking['gst_amount'] ) ? $booking['gst_amount'] : '0';
                                        $gst = isset( $booking['gst'] ) && !empty( $booking['gst'] ) ? $booking['gst'] : '0';
                                        $service_fee = isset( $booking['service_fee'] ) && !empty( $booking['service_fee'] ) ? $booking['service_fee'] : '0';
                                        $service_fee_gst_amount = isset( $booking['service_fee_gst_amount'] ) && !empty( $booking['service_fee_gst_amount'] ) ? $booking['service_fee_gst_amount'] : '0';
                                        $coupen_code_amount = isset( $booking['coupen_code_amount'] ) && !empty( $booking['coupen_code_amount'] ) ? $booking['coupen_code_amount'] : '0';

                                        $service_fee_percentage = isset( $service_fee_percentage ) && !empty( $service_fee_percentage ) ? $service_fee_percentage : '0';

                                        if( $session_currency != $currency_code ){
                                            $price_per_sqft   = currencyConverterAPI($currency_code, $session_currency, $price_per_sqft);
                                            $price_per_night  = currencyConverterAPI($currency_code, $session_currency, $price_per_night);

                                            $room_amount     = currencyConverterAPI($currency_code, $session_currency, $room_amount);
                                            $desk_amount     = currencyConverterAPI($currency_code, $session_currency, $desk_amount);
                                            $cubicles_amount = currencyConverterAPI($currency_code, $session_currency, $cubicles_amount);
                                        }
                                        else {
                                            $price_per_sqft  = $price_per_sqft;
                                            $price_per_night = $price_per_night;

                                            $room_amount     = $room_amount;
                                            $desk_amount     = $desk_amount;
                                            $cubicles_amount = $cubicles_amount;
                                        }

                                        if( $session_currency != 'INR' ){
                                            $currency_amount        = currencyConverterAPI('INR', $session_currency, $total_night_price);
                                            $coupon_amount          = currencyConverterAPI('INR', $session_currency, $coupen_code_amount);
                                            $paid_amount            = currencyConverterAPI('INR', $session_currency, $total_amount);
                                            $gst_amount             = currencyConverterAPI('INR', $session_currency, $gst_amount);
                                            $service_fee            = currencyConverterAPI('INR', $session_currency, $service_fee);
                                            $service_fee_gst_amount = currencyConverterAPI('INR', $session_currency, $service_fee_gst_amount);
                                        }
                                        else {
                                            $currency_amount        = $total_night_price;
                                            $coupon_amount          = $coupen_code_amount;
                                            $paid_amount            = $total_amount;
                                            $gst_amount             = $gst_amount;
                                            $service_fee            = $service_fee;
                                            $service_fee_gst_amount = $service_fee_gst_amount;
                                        }

                                        $total_booking_amount = $currency_amount - ($gst_amount + $service_fee + $service_fee_gst_amount);

                                        if($property_type_slug == 'warehouse') {
                                            $area_per_slots = $property_area / $no_of_slots;

                                            $html_str = '(TA / TS) * SS * P * N = '.$currency_icon.' '.number_format($total_booking_amount,2,'.','');

                                            $gst = get_gst_data(0, 'warehouse');
                                        }
                                        elseif ($property_type_slug == 'office-space') {
                                            $html_str2 = $html_str3 = $html_str4 = $html_str5 = $html_str6 = $html_str7 = '';

                                            $html_str1 = 'N * ';

                                            if($selected_room != 0) {
                                                $html_str2 = '( SR * PR )';
                                            }

                                            if( $selected_room != 0 && $selected_desk != 0 ){
                                                $html_str3 = ' + ';
                                            }
                                            else if( $selected_room != 0 && $selected_cubicles != 0 ){
                                                $html_str3 = ' + ';
                                            }

                                            if($selected_desk != 0) {
                                                $html_str4 = '( SD * PD )';
                                            }

                                            if( $selected_desk != 0 && $selected_cubicles != 0 ){
                                                $html_str5 = ' + ';
                                            }
                                            else if( $selected_desk != 0 && $selected_cubicles == 0 ){
                                                $html_str5 = '';
                                            }

                                            if($selected_cubicles != 0) {
                                                $html_str6 = '( SC * PC )';
                                            }

                                            $html_str7 = ' = '.$currency_icon.' '.number_format($total_booking_amount, 2, '.', '');

                                            $html_str = $html_str1.$html_str2.$html_str3.$html_str4.$html_str5.$html_str6.$html_str7;

                                            $gst = get_gst_data(0, 'office-space');
                                        }
                                        else {
                                            $html_str = 'N * G * P = '.$currency_icon.' '.number_format($total_booking_amount,2,'.','');

                                            $gst = get_gst_data($price_per_night, 'other');
                                        }

                                        $total_service_amount = $booking['gst_amount'] / 100 * $booking['total_night_price'];

                                        if($currency_code != 'INR') {
                                            $total_convert_in_INR = currencyConverterAPI($currency_code, 'INR', $booking['total_amount']);
                                        }
                                        ?>

                                        <div class="fee-bx">
                                            <div class="service-fee-title" id="no_of_nights_title">No of Nights (N) : </div>
                                            <div class="service-fee-price" id="no_of_nights_desc">{{ $no_of_days }}</div>
                                        </div>

                                        @if($property_type_slug == 'warehouse')
                                            <div class="fee-bx">
                                                <div class="service-fee-title" id="total_area_title">Total Area (TA) : </div>
                                                <div class="service-fee-price" id="total_area_desc">{{ $property_area }} Sq.Ft</div>
                                            </div>

                                            <div class="fee-bx">
                                                <div class="service-fee-title" id="total_slot_title">Total No. Slot(s) (TS) : </div>
                                                <div class="service-fee-price" id="total_slot_desc">{{ $no_of_slots }} Slot(s)</div>
                                            </div>

                                            <div class="fee-bx">
                                                <div class="service-fee-title" id="selected_no_of_slots_title">Selected No. of Slot(s) (SS) : </div>
                                                <div class="service-fee-price" id="selected_no_of_slots_desc">{{ $selected_slots }} Slot(s)</div>
                                            </div>

                                            <div class="fee-bx">
                                                <div class="service-fee-title" id="property_price_title">Price Per Sq.Ft (P) : </div>
                                                <div class="service-fee-price" id="property_price_desc">{!! $currency_icon.' '.number_format($price_per_sqft, 2, '.', '') !!}</div>
                                            </div>

                                        @elseif($property_type_slug == 'office-space')
                                            
                                            @if( $selected_room != 0 )
                                                <div id="div_room">
                                                    <div class="fee-bx">
                                                        <div class="service-fee-title" id="selected_no_of_rooms_title">Selected No. of Room(s) (SR) : </div>
                                                        <div class="service-fee-price" id="selected_no_of_rooms_desc">{{ $selected_room }}</div>
                                                    </div>

                                                    <div class="fee-bx">
                                                        <div class="service-fee-title" id="price_per_room_title">Price Per Room (PR) : </div>
                                                        <div class="service-fee-price" id="price_per_room_desc">{!! $currency_icon.' '.number_format($room_amount, 2, '.', '') !!}</div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if( $selected_desk != 0 )
                                                <div id="div_desk">
                                                    <div class="fee-bx">
                                                        <div class="service-fee-title" id="selected_no_of_desk_title">Selected No. of Desk(s) (SD) : </div>
                                                        <div class="service-fee-price" id="selected_no_of_desk_desc">{{ $selected_desk }}</div>
                                                    </div>

                                                    <div class="fee-bx">
                                                        <div class="service-fee-title" id="price_per_desk_title">Price Per Desk (PD) : </div>
                                                        <div class="service-fee-price" id="price_per_desk_desc">{!! $currency_icon.' '.number_format($desk_amount, 2, '.', '') !!}</div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if( $selected_cubicles != 0 )
                                                <div id="div_cubicles">
                                                    <div class="fee-bx">
                                                        <div class="service-fee-title" id="selected_no_of_cubicles_title">Selected No. of Cubicle(s) (SC) : </div>
                                                        <div class="service-fee-price" id="selected_no_of_cubicles_desc">{{ $selected_cubicles }}</div>
                                                    </div>

                                                    <div class="fee-bx">
                                                        <div class="service-fee-title" id="price_per_cubicles_title">Price Per Cubicle (PC) : </div>
                                                        <div class="service-fee-price" id="price_per_cubicles_desc">{!! $currency_icon.' '.number_format($cubicles_amount, 2, '.', '') !!}</div>
                                                    </div>
                                                </div>
                                            @endif

                                        @else
                                            <div class="fee-bx">
                                                <div class="service-fee-title" id="no_of_guest_title">Selected No. of Guest(s) (G) : </div>
                                                <div class="service-fee-price" id="no_of_guest_desc">{{ $selected_guest }}</div>
                                            </div>

                                            <div class="fee-bx">
                                                <div class="service-fee-title" id="price_title">Price Per Night (P) : </div>
                                                <div class="service-fee-price" id="price_desc">{!! $currency_icon.' '.number_format($price_per_night, 2, '.', '') !!}</div>
                                            </div>
                                        @endif

                                        <div class="fee-bx">
                                            <div class="service-fee-title">Total Amount : </div>
                                            <div class="service-fee-price">
                                                <p class="text1">{!! $html_str !!}</p>
                                            </div>
                                        </div>

                                        <div class="fee-bx">
                                            <div class="service-fee-title gsttip-rela">GST Tax Price 
                                                <div class="quetion-tool-gsttip-block" id="gst_tax_percentage" style="display:none;">GST Tax : {{ $gst }} % 
                                                    <span class="gsttip-rela-close" id="gst_tax_percentage_close"></span>
                                                </div>
                                                <i class="fa fa-question-circle-o" id="show_gst_tax_percentage"></i> : 
                                            </div>
                                            <div class="service-fee-price">
                                                <p class="text1">{!! $currency_icon.' '.number_format($gst_amount, 2, '.', '') !!}</p>
                                            </div>
                                        </div>

                                        <div class="fee-bx">
                                            <div class="service-fee-title gsttip-rela">Service fee Price 
                                                <div class="quetion-tool-gsttip-block" id="service_fee_percentage" style="display:none;"> Service Fee : {{ $service_fee_percentage }} % 
                                                    <span class="gsttip-rela-close" id="service_fee_percentage_close"></span>
                                                </div>
                                                <i class="fa fa-question-circle-o" id="show_service_fee_percentage"></i> : 
                                            </div>
                                            <div class="service-fee-price">
                                                <p class="text1">{!! $currency_icon.' '.number_format($service_fee, 2, '.', '') !!}</p>
                                            </div>
                                        </div>

                                        @if ($coupen_code_amount != 0 && $coupen_code_amount != '')
                                            <div class="fee-bx">
                                                <div class="service-fee-title">Discount Amount : </div>
                                                <div class="service-fee-price">{!! $currency_icon.' '.number_format($coupon_amount, 2, '.', '') !!}</div>
                                            </div>
                                        @endif

                                        <div class="fee-bx">
                                            <div class="service-fee-title">Paid Amount : </div>
                                            <div class="service-fee-price">
                                                <p class="text1">{!! $currency_icon.' '.number_format($paid_amount, 2, '.', '') !!}</p>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="clr"></div>
                                </div>
                            </div>
                            @if(isset($booking['booking_status']) && !empty($booking['booking_status']) && $booking['booking_status'] == '4')
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="reason-fo-reject-wraper">
                                    <div class="reason-for-left">Reason For Rejection :</div>
                                    <div class="reason-for-right-text">{{ $reject_reason }}</div>
                                </div>
                            </div>
                            @endif
                            @if(isset($booking['booking_status']) && !empty($booking['booking_status']) && $booking['booking_status'] == '6' && !empty($cancelled_reason) && $cancelled_reason != null)
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="reason-fo-reject-wraper">
                                    <div class="reason-for-left">Cancelled Reason:</div>
                                    <div class="reason-for-right-text">{{ $cancelled_reason }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    @endforeach
                    @else
                    <div class="list-vactions-details">
                        <div class="content-list-vact">
                            <p>Sorry, we couldn't find any matches.</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!--reject booking popup start here-->
<div class="host-contact-popup upgrade payment">
    <div class="popup-inquiry-form">
        <div id=RejectPopup class="modal fade" data-backdrop="static" role=dialog>
            <div class=modal-dialog>
                <div class=modal-content>
                    <div class="modal-header black-close">
                        <button type=button class=close data-dismiss=modal>
                            <span class="contact-left-img popup-close nonebg"><img src="{{url('/')}}/front/images/popup-close-btn.png" alt=""></span>
                        </button>
                        <h4 class=modal-title>Booking Reject</h4>
                    </div>
                    <div class=modal-body>                           
                        <div class="payment-detail-tab-one">

                            <form name="frm_add_account" action="{{ $module_url_path }}/reject" id="frm_add_account" method="POST">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <textarea id="reason" name="reason" data-rule-required="true" data-rule-minlength="3"  data-rule-maxlength="50"  /></textarea>
                                            <label for="add-bank-name-id">Reason</label> 
                                            <span class='error help-block' id="err_reason">{{ $errors->first('reason') }}</span>    
                                        </div>
                                        <input type="hidden" name="booking_id" id="booking_id" />
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="change-pass-btn">
                                            <a class="login-btn cancel" data-dismiss=modal href="javascript:void(0)">Cancel</a>
                                            <input type="submit" class="login-btn" name="btn_submit_reject" value="Submit" id="btn_submit_reject">
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
<!--reject booking popup end here-->

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
                                            <span class='error help-block' id="err_cancel_reason">{{ $errors->first('reason') }}</span>    
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
        $("#btn_submit_reject").click(function() {
            var reason = $('#reason').val();
            if(reason == '') {
                $('#err_reason').show();
                $('#err_reason').html('Please enter cancel reason');
                $('#err_reason').fadeOut(8000);
                $('#reason').focus();
                return false;
            }
        });

        $("#btn_submit_cancel_process").click(function() {
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

    function payment_process(ref) {
        $(ref).hide();
        $(ref).next().show();

        var payment_amount = $(ref).attr('data-amount');
        var id      = $(ref).attr('data-id');
        var amount  = payment_amount * 100;
        var options = {
            "key": "{{ config('app.project.RAZOR_ID') }}",
            "amount": parseFloat(amount).toFixed(2), // 2000 paise = INR 20
            "name": "{{ config('app.project.name') }}",
            "description": "{{ config('app.project.description') }}",
            "image": "{{url('/')}}/front/images/logo.png",
            "handler": function (response) {
                if(response.razorpay_payment_id != null) {
                    var token = $('input[name="_token"]').val();
                    $.ajax({
                        'url':SITE_URL+'/my-booking/payment',
                        'type':'post',
                        'data':{_token: token, transaction_id: response.razorpay_payment_id, payment_amount: payment_amount, booking_id: id },
                        success:function(res) { location.reload(); }
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
                "ondismiss": function() {
                    $(ref).show();
                    $(ref).next().hide();
                }
            }
        };
        var rzp1 = new Razorpay(options);
        rzp1.open();
    }

    function reject_booking(id) {
        swal({
            title: "Are you sure",
            text: "Do you want to reject booking?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-reject",
            confirmButtonText: "Yes, reject it!",
            confirmButtonColor: "#ff4747",
            closeOnConfirm: false
        },
        function() {
            $('.cancel').click();
            $('#RejectPopup').modal();
            $("#booking_id").val(id);
        });
    }

    function cancel_booking(id) {
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
        function() {
            $('.cancel').click();
            $('#CancelPopup').modal();
            $("#cancel_booking_id").val(id);
        });
    }

    function wallet_payment(ref) {
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
        function(isConfirm) {
            if (isConfirm) {
                swal({ title: "Processing...!", text: "Please wait", buttons: false });

                var booking_id        = $(ref).attr('data-id');
                var booking_amount    = $(ref).attr('data-amount');
                var wallet_amount     = $(ref).attr('data-wallet');
                var needed_inr_amount = $(ref).attr('data-needed_inr');
                var needed_amount     = $(ref).attr('data-needed');
                var currency          = $(ref).attr('data-currency');

                if(currency == 'INR') {
                    msg_text = "You have insufficient balance in wallet. Please add "+ parseFloat(needed_inr_amount) +" INR amount to wallet or try through online payment";
                }
                else {
                    msg_text = "You have insufficient balance in wallet. Please add "+ parseFloat(needed_inr_amount) +" INR ("+ parseFloat(needed_amount) +' '+currency +") amount to wallet or try through online payment";
                }

                if(parseFloat(booking_amount) < parseFloat(wallet_amount)) {
                    var token = $('input[name="_token"]').val();
                    $.ajax({
                        'url':SITE_URL+'/my-booking/payment/wallet',
                        'type':'post',
                        'data':{_token: token, booking_amount: booking_amount, booking_id: booking_id, wallet_amount:wallet_amount },
                        success:function(res){
                            location.reload();
                        }
                    });
                }
                else {
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
            else {
                $(ref).show();
                $(ref).prev().hide();
            }
        });
    }

    $(document).on('click', '#show_gst_tax_percentage', function() {
        $("#gst_tax_percentage").css('display','block');
    });
    $(document).on('click', '#gst_tax_percentage_close', function() {
        $("#gst_tax_percentage").css('display','none');
    });

    $(document).on('click', '#show_service_fee_percentage', function() {
        $("#service_fee_percentage").css('display','block');
    });
    $(document).on('click', '#service_fee_percentage_close', function() {
        $("#service_fee_percentage").css('display','none');
    });
</script>
@endsection