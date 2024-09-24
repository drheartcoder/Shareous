<style type="text/css">
	.Highlighted a{
		background-color : Green !important;
		background-image :none !important;
		color: White !important;
		font-weight:bold !important;
		font-size: 12pt;
	}
</style>

<?php
	$user_id = $user_first_name = $user_last_name = $user_mobile_number = $user_email = $user_wallet = '';
	if(isset($arr_user) && !empty($arr_user)) {
		$user_id            = isset($arr_user['id']) ? $arr_user['id'] : '';
		$user_first_name    = isset($arr_user['first_name']) ? $arr_user['first_name'] : '';
		$user_last_name     = isset($arr_user['last_name']) ? $arr_user['last_name'] : '';
		$user_mobile_number = isset($arr_user['mobile_number']) ? $arr_user['mobile_number'] : '';
		$user_email         = isset($arr_user['email']) ? $arr_user['email'] : '';
		$user_wallet        = isset($arr_user['wallet_amount']) ? $arr_user['wallet_amount'] : '';
	}

	$property_name_slug = isset($arr_property['property_name_slug']) ? $arr_property['property_name_slug'] : '';

	$price_per_sqft   = isset($arr_property['price_per_sqft']) ? $arr_property['price_per_sqft'] : '';
	$no_of_slots      = isset($arr_property['no_of_slots']) ? $arr_property['no_of_slots'] : '';
	$property_area    = isset($arr_property['property_area']) ? $arr_property['property_area'] : '';
	$price_per_office = isset($arr_property['price_per_office']) ? $arr_property['price_per_office'] : '';
	$price_per_night  = isset($arr_property['price_per_night']) ? $arr_property['price_per_night'] : '';

	$employee = isset( $arr_property['employee'] ) ? $arr_property['employee'] : '';
    $room     = isset( $arr_property['room'] ) ? $arr_property['room'] : '';
    $desk     = isset( $arr_property['desk'] ) ? $arr_property['desk'] : '';
    $cubicles = isset( $arr_property['cubicles'] ) ? $arr_property['cubicles'] : '';

    $no_of_employee = isset( $arr_property['no_of_employee'] ) ? $arr_property['no_of_employee'] : '';
    $no_of_room     = isset( $arr_property['no_of_room'] ) ? $arr_property['no_of_room'] : '';
    $no_of_desk     = isset( $arr_property['no_of_desk'] ) ? $arr_property['no_of_desk'] : '';
    $no_of_cubicles = isset( $arr_property['no_of_cubicles'] ) ? $arr_property['no_of_cubicles'] : '';

    $room_price     = isset( $arr_property['room_price'] ) ? $arr_property['room_price'] : '';
    $desk_price     = isset( $arr_property['desk_price'] ) ? $arr_property['desk_price'] : '';
    $cubicles_price = isset( $arr_property['cubicles_price'] ) ? $arr_property['cubicles_price'] : '';

    $current_currency = isset($arr_property['currency']) ? $arr_property['currency'] : '';
?>

<div class="col-sm-4 col-md-4 col-lg-4">    
	<div class="request-booking-frm margin topi detais-top-adjest" id="booking_prices_div">
		<form action="{{ $module_url_path }}/book_property" method="post" name="frm_book_property" id="frm_book_property">
			{{ csrf_field() }}
			
			<input type="hidden" name="enc_property_id" id="enc_property_id" value="{{ isset( $arr_property['id'] ) ? base64_encode( $arr_property['id'] ) : '' }}">
			<input type="hidden" name="arr_property_dates" id="arr_property_dates" value="{{ isset( $arr_unavailble_dates ) ? base64_decode( $arr_unavailble_dates ) : '' }}">
			<?php $property_type_slug = get_property_type_slug($arr_property['property_type_id']); ?>
            <input type="hidden" name="property_type_slug" id="property_type_slug" value="{{ $property_type_slug }}">
			
			<div class="per-night-bx">
				@if($property_type_slug == 'warehouse')
					<div class="per-night-title">From {{ session_currency( $price_per_sqft, $property_id ) }} <span>per Sq.Ft</span> </div>
				@elseif($property_type_slug == 'office-space')
				    <?php
	                    $room_price_html = $desk_price_html = $cubicles_price_html = '';

	                    if( $room == 'on' ) {
	                        $room_price_html ='<div class="price-lar-inner">'.session_currency( $room_price, $property_id).'<span>/room</span></div>';
	                    }

	                    if( $desk == 'on' ) {
	                        $desk_price_html ='<div class="price-lar-inner">'.session_currency( $desk_price, $property_id).'<span>/desk</span></div>';
	                    }

	                    if( $cubicles == 'on' ) {
	                        $cubicles_price_html ='<div class="price-lar-inner">'.session_currency( $cubicles_price, $property_id).'<span>/cubicles</span></div>';
	                    }
	                ?>
					<div class="per-night-title">From {!! $room_price_html !!} {!! $desk_price_html !!} {!! $cubicles_price_html !!}</div>
				@else
					<div class="per-night-title">From {{ session_currency($price_per_night, $property_id) }} <span>per night</span> </div>
				@endif
			</div>

			<?php
				$available_no_of_slots = $arr_property['available_no_of_slots'];
				$check_in = $check_out = $session_no_of_guest = $session_property_type_slug = $session_available_no_of_slots = $session_available_no_of_employee = '';

	            if(base64_decode(Request::segment(4)) != null && base64_decode(Request::segment(4)) != '' && strlen(base64_decode(Request::segment(4))) == '10') {
	            	Session::put('BookingRequestData.checkin'  , base64_decode(Request::segment(4)));
	            }

	            if(base64_decode(Request::segment(5)) != null && base64_decode(Request::segment(5)) != '' && strlen(base64_decode(Request::segment(5))) == '10') {
	                Session::put('BookingRequestData.checkout' , base64_decode(Request::segment(5)));
	            }
	            
	            if(base64_decode(Request::segment(6)) != null && base64_decode(Request::segment(6)) != ''){
	            	Session::put('BookingRequestData.guests'  , base64_decode(Request::segment(6)));
	            }

				if (Session::get('BookingRequestData') != null) {
					if (Session::get('BookingRequestData.checkin') != '' && Session::get('BookingRequestData.checkin') != '00-00-0000') {
						$check_in  = date('d-m-Y',strtotime(Session::get('BookingRequestData.checkin')));
					}
					if (Session::get('BookingRequestData.checkout')!='' && Session::get('BookingRequestData.checkout')!='00-00-0000') {

						$check_out = date('d-m-Y',strtotime(Session::get('BookingRequestData.checkout')));
					}
					if (Session::get('BookingRequestData.guests') != '') {
						$session_no_of_guest = Session::get('BookingRequestData.guests');
					}

					$session_property_type_slug       = Session::get('BookingRequestData') != null ? Session::get('BookingRequestData.property_type_slug') : "";
					
					$session_available_no_of_slots    = Session::get('BookingRequestData') != null ? Session::get('BookingRequestData.available_no_of_slots') : "";
					
					$session_available_no_of_employee = Session::get('BookingRequestData') != null ? Session::get('BookingRequestData.available_no_of_employee') : "";
					$session_available_no_of_room = Session::get('BookingRequestData') != null ? Session::get('BookingRequestData.available_no_of_room') : "";
					$session_available_no_of_desk = Session::get('BookingRequestData') != null ? Session::get('BookingRequestData.available_no_of_desk') : "";
					$session_available_no_of_cubicles = Session::get('BookingRequestData') != null ? Session::get('BookingRequestData.available_no_of_cubicles') : "";
				}

				$wallet_payment = (null !== \Request::input('wallet_payment') ) ? \Request::input('wallet_payment') : '';
			?>

			<div class="availability-bx">
				<div class="availability-txt">Enter dates to check availability and total price </div>
				<div class="chek-in-bx">
					<div class="check-in-title">Check In</div>
					<div class="form-group date-block select chek-in">
						<input id="start-date" data-rule-required="true" name="start_date" type="text" placeholder="Select Date" value="{{ $check_in != '' ? $check_in : ""}}" autocomplete="off" readonly />
						<div class="error" id="err_start_date"></div>
						<span class="calendar-icon"><i class="fa fa-calendar"></i></span>
					</div>
				</div>

				<div class="chek-in-bx">
					<div class="check-in-title">Check Out</div>
					<div class="form-group date-block select chek-in">
						<input id="end-date" data-rule-required="true" name="end_date" type="text" placeholder="Select Date" value="{{ $check_out != '' ? $check_out : "" }}"  autocomplete="off" readonly />
						<div class="error" id="err_end_date"></div>
						<span class="calendar-icon"><i class="fa fa-calendar"></i></span>
					</div>
				</div>

				@if($property_type_slug == 'warehouse')
					<div class="chek-in-bx">
						<div class="check-in-title">No. of Slots</div>
						<div class="form-group">
							<div class="select-style">
								<select name="available_no_of_slots" id="available_no_of_slots">
									<option value="">No Slots Available</option>
								</select>
								<div class="error" id="err_available_no_of_slots"></div>
							</div>
						</div>
					</div>
				@elseif($property_type_slug == 'office-space')
					@if( $employee == 'on' )
						<div class="chek-in-bx">
							<div class="check-in-title">No. of Person</div>
							<div class="form-group">
								<div class="select-style">
									<select name="available_no_of_employee" id="available_no_of_employee">
										<option value="">No Person Available</option>
									</select>
									<div class="error" id="err_available_no_of_employee"></div>
								</div>
							</div>
						</div>
					@endif
					@if( $room == 'on' )
						<div class="chek-in-bx">
							<div class="check-in-title">No. of Room</div>
							<div class="form-group">
								<div class="select-style">
									<select name="available_no_of_room" id="available_no_of_room">
										<option value="">No Room Available</option>
									</select>
									<div class="error" id="err_available_no_of_room"></div>
								</div>
							</div>
						</div>
					@endif
					@if( $desk == 'on' )
						<div class="chek-in-bx">
							<div class="check-in-title">No. of Desk</div>
							<div class="form-group">
								<div class="select-style">
									<select name="available_no_of_desk" id="available_no_of_desk">
										<option value="">No Desk Available</option>
									</select>
									<div class="error" id="err_available_no_of_desk"></div>
								</div>
							</div>
						</div>
					@endif
					@if( $cubicles == 'on' )
						<div class="chek-in-bx">
							<div class="check-in-title">No. of Cubicles</div>
							<div class="form-group">
								<div class="select-style">
									<select name="available_no_of_cubicles" id="available_no_of_cubicles">
										<option value="">No Cubicles Available</option>
									</select>
									<div class="error" id="err_available_no_of_cubicles"></div>
								</div>
							</div>
						</div>
					@endif
				@else
					<div class="chek-in-bx">
						<div class="check-in-title">No.of Guest</div>
						<div class="form-group">
							<div class="select-style">
								<select name="no_of_guest" id="no_of_guest">
									<option value="">No Guest Available</option>
								</select>
								<div class="error" id="err_no_of_guest"></div>
							</div>
						</div>
					</div>
				@endif

				<div class="chek-in-bx">
					<div class="check-in-title">Coupon Code</div>
					<div class="form-group no-red">
						<input type="hidden" id="is_coupon_code" name="is_coupon_code" value="" />
						<input type="hidden" id="txt_coupon_code" name="coupon_code" />
						<input type="text" id="coupon_code" placeholder="Enter Coupon Code" autocomplete="off" maxlength="10" />
						<div class="error" id="err_coupon_code" style="display: none;">Enter coupon code to apply</div>
						<button type="button" class="coupon-code-close-righ" id="remove_coupon_code" style="display: none;"><span></span></button>
						<button class="coupon-code-close-righ" type="button" id="apply_coupon_code" style="display: none; color: #fff;"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
					</div>
				</div>
				<div class="clearfix"></div>

			</div>
			
			<?php $style = 'display:none'; ?>
			@if( Session::get('BookingRequestData') != null && Session::get('BookingRequestData.checkin') != '' && Session::get('BookingRequestData.checkout') != '')
				<?php $style = 'display:block'; ?>
			@endif

			@if(validate_login('users'))
				<?php $is_login = '1'; ?>
			@else
				<?php $is_login = '0'; ?>
			@endif

			<input type="hidden" name="is_login" id="is_login" value="{{ $is_login }}">
			<div class="service-fee-bx two" id="amount_div_id" style="display: none;">
				<?php
					$currency_code = isset($arr_property['currency_code']) ? $arr_property['currency_code'] : '';
					$currency      = isset($arr_property['currency']) ? $arr_property['currency'] : '';
					
					$number_of_nights = $price_per_night = $total_night_price = $total_payable_amount = $total_convert_in_INR = $convert_single = '';

					if(Session::get('BookingRequestData') != null && Session::get('BookingRequestData.checkin') != '') {
						$number_of_nights     = Session::get('BookingRequestData.arr_calculation_result.number_of_nights');
						$price_per_night      = Session::get('BookingRequestData.arr_calculation_result.price_per_night');
						$total_night_price    = Session::get('BookingRequestData.arr_calculation_result.total_night_price');
						$total_payable_amount = Session::get('BookingRequestData.arr_calculation_result.total_payble_amount');

						if($currency_code != "INR") {
							$convert_single       = currencyConverter($currency_code, 'INR', '1');
							$total_convert_in_INR = currencyConverter($currency_code, 'INR', $total_payable_amount);
						}
					}
				?>

				<div class="fee-bx">
					<div class="service-fee-title" id="no_of_nights_title">No. of Night(s) (N) : </div>
					<div class="service-fee-price" id="no_of_nights_desc"></div>
				</div>

				@if($property_type_slug == 'warehouse')
					
					<div class="fee-bx">
						<div class="service-fee-title" id="total_area_title">Total Area (TA) : </div>
						<div class="service-fee-price" id="total_area_desc"></div>
					</div>

					<div class="fee-bx">
						<div class="service-fee-title" id="total_slot_title">Total No. Slot(s) (TS) : </div>
						<div class="service-fee-price" id="total_slot_desc"></div>
					</div>

					<div class="fee-bx">
						<div class="service-fee-title" id="selected_no_of_slots_title">Selected No. of Slot(s) (SS) : </div>
						<div class="service-fee-price" id="selected_no_of_slots_desc"></div>
					</div>

					<div class="fee-bx">
						<div class="service-fee-title" id="property_price_title">Price Per Sq.Ft (P) : </div>
						<div class="service-fee-price" id="property_price_desc"></div>
					</div>

				@elseif($property_type_slug == 'office-space')

					@if( $room == 'on' )
						<div id="div_room" style="display: none;">
							<div class="fee-bx">
								<div class="service-fee-title" id="selected_no_of_rooms_title">Selected No. of Room(s) (SR) : </div>
								<div class="service-fee-price" id="selected_no_of_rooms_desc"></div>
							</div>

							<div class="fee-bx">
								<div class="service-fee-title" id="price_per_room_title">Price Per Room (PR) : </div>
								<div class="service-fee-price" id="price_per_room_desc"></div>
							</div>
						</div>
					@endif

					@if( $desk == 'on' )
						<div id="div_desk" style="display: none;">
							<div class="fee-bx">
								<div class="service-fee-title" id="selected_no_of_desk_title">Selected No. of Desk(s) (SD) : </div>
								<div class="service-fee-price" id="selected_no_of_desk_desc"></div>
							</div>

							<div class="fee-bx">
								<div class="service-fee-title" id="price_per_desk_title">Price Per Desk (PD) : </div>
								<div class="service-fee-price" id="price_per_desk_desc"></div>
							</div>
						</div>
					@endif

					@if( $cubicles == 'on' )
						<div id="div_cubicles" style="display: none;">
							<div class="fee-bx">
								<div class="service-fee-title" id="selected_no_of_cubicles_title">Selected No. of Cubicle(s) (SC) : </div>
								<div class="service-fee-price" id="selected_no_of_cubicles_desc"></div>
							</div>

							<div class="fee-bx">
								<div class="service-fee-title" id="price_per_cubicles_title">Price Per Cubicle (PC) : </div>
								<div class="service-fee-price" id="price_per_cubicles_desc"></div>
							</div>
						</div>
					@endif

				@else
					<div class="fee-bx">
						<div class="service-fee-title" id="no_of_guest_title">Selected No. of Guest(s) (G) : </div>
						<div class="service-fee-price" id="no_of_guest_desc"></div>
					</div>

					<div class="fee-bx">
						<div class="service-fee-title" id="price_title">Price Per Night (P) : </div>
						<div class="service-fee-price" id="price_desc"></div>
					</div>
				@endif

				<div class="fee-bx">
					<div class="service-fee-title">Total Charges : </div>
					<div class="service-fee-price">
						<div id="total_charges_of_night">
							<p class="text1">{{ $number_of_nights }} * {{ $number_of_nights }} = {{ $total_night_price }} {{ $currency_code }}</p>
						</div>
					</div>
				</div>

				<div id="gst_tax_price"></div>
				<div id="service_fee_price"></div>

				<div id="single_currency_code"></div>
				<div id="total_convert_amount"></div>

				<div class="fee-bx">
					<div class="service-fee-title">Coupon Discount : </div>
					<div class="service-fee-price" id="discount_amount"> 0</div>
					<input type="hidden" id="txt_discount_amount" name="discount_amount" />
				</div>

				<div class="fee-bx total-bx">
					<div class="service-fee-price no-float total-amo">
						<span class="total-amo-left-ali">Total Amount : </span>
						<span id="total_payble_amt"></span>
						<input type="hidden" name="total_payble_amt" id="total_payble_amt_txt" placeholder="Amount Auto Calculated" readonly disabled value="{{ Session::get('BookingRequestData') != null ? Session::get('BookingRequestData.arr_calculation_result.total_payble_amount') : '' }}" />
					</div>
				</div>
				<div class="clr"></div>
			</div>       
			<div class="write-review-btn write-btn">
				<div class="send-review-brn width">
					@if( auth()->guard('users')->user() != null )
						<input type="button" class="review-send" value="Book now" id="btn_book_property">
					@else
						<a class="review-send" id="btn_before_login">Book now</a>
					@endif
				</div>
				<div class="refund-txt"></div>
			</div>
		</form>                          
	</div>
</div>

<div id="loader-gif" style="display: none;"><img src="{{ url('/') }}/front/images/spin.gif"></div>

<div class="host-contact-popup becone-a-host">
	<div class="popup-inquiry-form" >
		<div id="pay-with-option" class="modal fade" data-backdrop="static" role="dialog">
			<div class="modal-dialog payment-popu-main">
				<div class=modal-content>
					<div class=modal-header>
						<button type=button class=close data-dismiss=modal>
							<span class="contact-left-img popup-close"></span>
						</button>
						<h4 class=modal-title>Pay With</h4>
					</div>
					<div class=modal-body>                        
						<form id="form_verification" action="{{url('/verification/post_documets')}}" method="post" enctype="multipart/form-data">
							{{csrf_field()}}
							<div class="my-id-proof-main payme">               
								<div class="my-account-profile-img-block left">
									<a href="javascript:void(0)" class="btn_process_payment btn-pays margin-b loader" style="display: none; height: 16;"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></a>

									<a href="javascript:void(0)" data-id="{{ $arr_property['id'] }}" data-wallet="{{ $user_wallet!='' ? number_format($user_wallet, 2, '.', '') : '' }}" data-currency_code="{{ $arr_property['currency_code'] }}" data-booking_id="" data-amount="" data-amount_inr="" data-needed="" data-needed_inr="" data-payable="" data-payable_inr="" data-used_coupon_id="" class="btn_confirm_wallet_payment btn-pays" onclick="wallet_payment(this)" title="Pay from wallet" >
										<img class="payme-first-img" src="{{url('/front/images/wallet-pay-icon.png')}}" /><span class="clearfix"></span>
									</a>

									<div class="pay-with-popu-ammount" id="current_wallet_amount">0.00</div>
								</div>
								<div class="pay-with-name-bottom left"> Wallet </div>
							</div>

							<div class="my-id-proof-main payme"> 
								<div class="my-account-profile-img-block">
									<a href="javascript:void(0)" class="btn_process_payment btn-pays margin-b loader" style="display: none; height: 16;"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></a>
									
									<a href="javascript:void(0)" data-id="{{ $arr_property['id'] }}" data-wallet="{{ $user_wallet!='' ? number_format($user_wallet, 2, '.', '') : '' }}" data-currency_code="{{ $arr_property['currency_code'] }}" data-booking_id="" data-amount="" data-amount_inr="" data-needed="" data-needed_inr="" data-payable="" data-payable_inr="" data-used_coupon_id="" onclick="payment_process(this)" class="btn_confirm_payment btn-pays">
									<img src="{{url('/front/images/online-pay-icon.png')}}" title="Pay Online" /><span class="clearfix"></span></a>

								</div>
								<div class="pay-with-name-bottom"> Online </div>
							</div>
						</form>
						<div class=clearfix></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="host-contact-popup upgrade payment">
	<div class="popup-inquiry-form">
		<div id=CancelPopup class="modal fade" data-backdrop="static" role=dialog>
			<div class=modal-dialog>
				<div class=modal-content>
					<div class="modal-header black-close">
						<button type=button class=close data-dismiss=modal>
							<span class="contact-left-img popup-close nonebg"><img src="{{ url('/') }}/front/images/popup-close-btn.png" alt=""></span>
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
											<textarea id="cancel_reason" name="cancel_reason" data-rule-required="true" data-rule-minlength="3" data-rule-maxlength="50" /></textarea>
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

<input type="hidden" name="price_per_sqft"      id="price_per_sqft"      value="{{ $price_per_sqft      }}">
<input type="hidden" name="no_of_slots"         id="no_of_slots"         value="{{ $no_of_slots         }}">
<input type="hidden" name="property_area"       id="property_area"       value="{{ $property_area       }}">
<input type="hidden" name="price_per_office"    id="price_per_office"    value="{{ $price_per_office    }}">
<input type="hidden" name="price_per_night"     id="price_per_night"     value="{{ $price_per_night     }}">
<input type="hidden" name="session_no_of_guest" id="session_no_of_guest" value="{{ $session_no_of_guest }}">
<input type="hidden" name="wallet_payment"      id="wallet_payment"      value="{{ $wallet_payment      }}">

<?php 
	$razorpay_credentials = get_razorpay_credential();
	$razorpay_id          = (isset($razorpay_credentials) && $razorpay_credentials['razorpay_id'] != '') ? $razorpay_credentials['razorpay_id'] : config('app.razorpay_credentials.razorpay_id');
	$razorpay_secret      = (isset($razorpay_credentials) && $razorpay_credentials['razorpay_secret'] != '') ? $razorpay_credentials['razorpay_secret'] : config('app.razorpay_credentials.razorpay_secret');
?>

<script src="{{url('/')}}/front/js/razorpay-checkout.js"></script>
<script type="text/javascript">
	function payment_process(ref)
	{
		showProcessingOverlay();
		$(ref).hide();
		$(ref).next().show();

		var id             = $(ref).attr('data-booking_id');
		var amount         = $(ref).attr('data-amount');
		var amount_inr     = $(ref).attr('data-amount_inr');
		var needed         = $(ref).attr('data-needed');
		var needed_inr     = $(ref).attr('data-needed_inr');
		var payable        = $(ref).attr('data-payable');
		var payable_inr    = $(ref).attr('data-payable_inr');
		var currency_code  = $(ref).attr('data-currency_code');
		var used_coupon_id = $(ref).attr('data-used_coupon_id');
		var amount         = amount_inr * 100;
		
		var options        = {
			"key": "{{ $razorpay_id }}",
			"amount": amount, // 2000 paise = INR 20
			"name": "{{ config('app.project.name') }}",
			"description": "{{ config('app.project.description') }}",
			/*"image": "{{ url('/') }}/front/images/logo.png",*/
			"handler": function (response)
			{
				if(response.razorpay_payment_id != null) {
					$.ajax({
						'url':SITE_URL+'/my-booking/payment',                    
						'type':'post',
						'dataType':'json',
						'data':{ _token: csrf_token, transaction_id: response.razorpay_payment_id, payment_amount: amount_inr, booking_id: id, used_coupon_id: used_coupon_id, page:'details' },
						beforeSend: showProcessingOverlay(),
						success:function(res)
						{
							if(res.status == 'success') {
								swal({title:"Property booked", text:res.message, type:"success"},
								function(){
									window.location.href = "{{ url('/') }}/my-booking/confirmed";
								});
							} else {
								swal({title:"Error", text:res.message, type:"error"},
								function(){ 
									location.reload();
								});

							}
						},
						complete: function() { hideProcessingOverlay(); }
					});
				} else {
					hideProcessingOverlay();
					swal("Error", "Error from payment", "error");
				}
			},
			"prefill": {
				"name": "{{ $user_first_name }} {{ $user_last_name }}",
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
				"ondismiss": function() {
					hideProcessingOverlay();
					$(ref).show();
					$(ref).next().hide();
				}
			}
		};
		var rzp1 = new Razorpay(options);
		rzp1.open();
	}

	$(document).ready(function() {
		$("#btn_submit_cancel_process").click(function() {
			var cancel_reason = $('#cancel_reason').val();
			if (cancel_reason == '') {
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
			closeOnConfirm: false
		},
		function() {
			$('.cancel').click();
			$('#CancelPopup').modal();
			$("#cancel_booking_id").val(id);
		});
	}

	function wallet_payment(ref)
	{
		$(ref).hide();
		$(ref).prev().show();

		var msg_text                 = '';
		var available_no_of_slots    = '';
		var available_no_of_employee = '';
		var available_no_of_room     = '';
		var available_no_of_desk     = '';
		var available_no_of_cubicles = '';
		var no_of_guest              = '';

		var property_name_slug = "{{ $property_name_slug }}";
		var property_id        = $('#enc_property_id').val();
		var type_slug          = $('#property_type_slug').val();
		var start_date         = $('#start-date').val();
		var end_date           = $('#end-date').val();

		if( type_slug == 'warehouse' ) {
			available_no_of_slots = $("#available_no_of_slots").val();
		} else if( type_slug == 'warehouse' ) {
			available_no_of_employee = $("#available_no_of_employee").val();
			available_no_of_room     = $("#available_no_of_room").val();
			available_no_of_desk     = $("#available_no_of_desk").val();
			available_no_of_cubicles = $("#available_no_of_cubicles").val();
		} else {
			no_of_guest = $("#no_of_guest").val();
		}

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
				swal({ title: "Processing...!", text: "Please wait", confirmButtonColor: "#ff4747", showConfirmButton: false, });

				var booking_id     = $(ref).attr('data-booking_id');
				var amount         = $(ref).attr('data-amount');
				var amount_inr     = $(ref).attr('data-amount_inr');
				var needed         = $(ref).attr('data-needed');
				var needed_inr     = $(ref).attr('data-needed_inr');
				var payable        = $(ref).attr('data-payable');
				var payable_inr    = $(ref).attr('data-payable_inr');
				var currency_code  = $(ref).attr('data-currency_code');
				var wallet_amount  = $(ref).attr('data-wallet');
				var used_coupon_id = $(ref).attr('data-used_coupon_id');

				if (currency_code == 'INR') {
					msg_text = "You have insufficient balance in wallet. Please add "+ parseFloat(needed_inr) +" INR amount to wallet or try through online payment";
				} else {
					msg_text = "You have insufficient balance in wallet. Please add "+ parseFloat(needed_inr) +" INR ("+ parseFloat(needed) +' '+currency_code +") amount to wallet or try through online payment";
				}

				if (parseFloat(amount_inr) <= parseFloat(wallet_amount)) {
					$.ajax({
						'url':SITE_URL+'/my-booking/payment/wallet',
						'type':'post',
						'data':{_token: csrf_token, amount: amount, amount_inr:amount_inr, booking_id: booking_id, wallet_amount:wallet_amount, used_coupon_id: used_coupon_id },
						'dataType':'json',
						success:function(res) {
							if(res.status == 'success') {
								swal({ title:"Property booked", text:res.message, type:"success" },
								function() {
									window.location.href = "{{ url('/') }}/my-booking/confirmed";
								});
							} else {
								swal({ title:"Error", text:res.message, type:"error" },
								function() {
									location.reload();
								});
							}
						}
					});
				} else {

					swal({
						title: 'Sorry !',
						text: msg_text,
						type: "warning",
						customClass:'NewErrorMsgFont',
						showConfirmButton: true,
						showCancelButton: true,
						confirmButtonColor: "#ff4747",
						cancelButtonColor: '#ff4747',
						confirmButtonText: 'Goto Wallet',
						cancelButtonText: 'Okay',
						closeOnConfirm: false,
					},
					function() {
						window.location.href = "{{ url('/') }}/wallet?amount_needed="+needed_inr+"&property_name_slug="+window.btoa(property_name_slug);
					});
					$(ref).show();
					$(ref).prev().hide();
				}
			} else {
				$(ref).show();
				$(ref).prev().hide();
			}
		});
	}
</script>

<script type="text/javascript">   
	$(document).ready(function() {

		calculate_property_rate();
		get_available_slots();

		var obj = $.parseJSON($('#arr_property_dates').val());
		var arr_unavailable_dates = [];
		if(obj.arr_unavailable_dates.length != undefined && obj.arr_unavailable_dates.length > 0) {
			arr_unavailable_dates = convert_date_format(obj.arr_unavailable_dates);
		}

		var checkin = $("#start-date").val();
        var checkout = $("#end-date").val();

        if( $.trim(checkin) != '' ) {
            var checkinAr   = checkin.split('-');
            var checkinDate = checkinAr[1] + '/' + checkinAr[0].slice(-2) + '/' + checkinAr[2];
            var start_date  = new Date(checkinDate);
        } else {
            var start_date = new Date();
        }

        var future_year = new Date();
            future_year.setFullYear(new Date().getFullYear()+20);

		$("#start-date").datepicker({
			todayHighlight: true,
			autoclose: true,
			format: 'dd-mm-yyyy',
			startDate: start_date,
			endDate: future_year,
			datesDisabled: arr_unavailable_dates,
			clearBtn: true,
		}).on('hide', function(e) {
            var selected = $(this).val();
            if(selected != '') {
                $('#end-date').focus()
            }
        }).on("keydown", function(e) {
            if (e.which == 13) {
                var selected = $(this).val();
                if(selected != '') {
                    $('#end-date').focus();
                }
            }
        });

		start_date.setDate(start_date.getDate() + 1);
		$("#end-date").datepicker({
			autoclose: true,
			format: 'dd-mm-yyyy',
			startDate: start_date,
			endDate: future_year,
			datesDisabled: arr_unavailable_dates,
			clearBtn: true,
		});

		$("#start-date").change(function() {
            $("#err_start_date").html('');
            $("#end-date").val('');
            $("#end-date").datepicker('remove');
            
            var date     = $(this).val();
            var dateAr   = date.split('-');
            var newDate  = dateAr[1] + '/' + dateAr[0].slice(-2) + '/' + dateAr[2];
            var new_date = newDate;
            var end_date = new Date(new_date);

            end_date.setDate(end_date.getDate() + 1);
            $("#end-date").datepicker({
				autoclose: true,
				startDate: end_date,
				endDate: future_year,
				datesDisabled: arr_unavailable_dates,
				clearBtn: true,
				format: 'dd-mm-yyyy',
			});
        });

		$("#start-date").change(function(){
			var property_type_slug = $('#property_type_slug').val();
			if ($.trim(property_type_slug) == 'warehouse') {
				get_available_slots();
				$('#amount_div_id').hide();
			}
			else if ($.trim(property_type_slug) == 'office-space') {
				get_available_slots();
				$('#amount_div_id').hide();
			}
			else if ($.trim(property_type_slug) != 'warehouse' && $.trim(property_type_slug) != 'office-space') {
				get_available_slots();
				$('#amount_div_id').hide();
			}
		});

		$("#end-date").change(function() {
			$("#err_end_date").html('');
			var property_type_slug = $('#property_type_slug').val();
			if ($.trim(property_type_slug) == 'warehouse') {
				get_available_slots();
				$('#amount_div_id').hide();
			}
			else if ($.trim(property_type_slug) == 'office-space') {
				get_available_slots();
				$('#amount_div_id').hide();
			}
			else if ($.trim(property_type_slug) != 'warehouse' && $.trim(property_type_slug) != 'office-space') {
				get_available_slots();
				$('#amount_div_id').hide();
			}
		});

		$('#no_of_guest').on('change', function () {
			$("#err_no_of_guest").html('');
			calculate_property_rate();
		});

		$('#available_no_of_slots').on('change', function () {
			$("#err_available_no_of_slots").html('');
			calculate_property_rate();
		});

		$('#available_no_of_employee').on('change', function () {
			$("#err_available_no_of_employee").html('');
			calculate_property_rate();
		});

		$('#available_no_of_room').on('change', function () {
			$("#err_available_no_of_room").html('');
			calculate_property_rate();
		});

		$('#available_no_of_desk').on('change', function () {
			$("#err_available_no_of_desk").html('');
			calculate_property_rate();
		});

		$('#available_no_of_cubicles').on('change', function () {
			$("#err_available_no_of_cubicles").html('');
			calculate_property_rate();
		});

		/*validation*/
		$('.booking_timepicker').timepicker();
	});
    
    var price_class = $('.price-lar-inner').length;
    if(price_class == 3) {
		$("#booking_prices_div").addClass('detai-top-adje-two');
    }

	function convert_date_format(arr_dates)
	{
		var i;
		var arr_formatted_dates = [];
		for (i = 0; i < arr_dates.length; i++) {
			var date = new Date(arr_dates[i]);
			var formatted_date = date.getDate()+'-'+(date.getMonth()+1)+'-'+date.getFullYear();  
			arr_formatted_dates.push(formatted_date);
		}
		return arr_formatted_dates;
	}

	function get_available_slots()
	{
		var address     = '';
		var property_id = $('#enc_property_id').val();
		var type_slug   = $('#property_type_slug').val();
		var start_date  = $('#start-date').val();
		var end_date    = $('#end-date').val();

		var session_no_of_guest = $('#session_no_of_guest').val();

		if( start_date != '' && end_date != '' && property_id != '' && type_slug != '' ) {
			if ($.trim(type_slug) == 'office-space') {
				address = 'get_available_office';
			} else {
				address = 'get_available_slots';
			}

			$.ajax({
				'url':SITE_URL+'/property/booking/'+address,
				'type':'post',
				'data':{ _token:csrf_token, start_date:start_date, end_date:end_date, type_slug:type_slug, property_id:property_id, session_no_of_guest:session_no_of_guest },
				success:function(response) {

					if ($.trim(type_slug) == 'warehouse') {
						$("#available_no_of_slots").html(response);
					} else if ($.trim(type_slug) == 'office-space') {
						$("#available_no_of_employee").html(response.employee_html);
						$("#available_no_of_room").html(response.room_html);
						$("#available_no_of_desk").html(response.desk_html);
						$("#available_no_of_cubicles").html(response.cubicles_html);
					} else {
						$("#no_of_guest").html(response);
					}

					calculate_property_rate();
				}
			});
		}
	}

	function calculate_property_rate() {
		var employee              = "{{ $employee }}";
		var room                  = "{{ $room }}";
		var desk                  = "{{ $desk }}";
		var cubicles              = "{{ $cubicles }}";

		var property_id           = $('#enc_property_id').val();
		var property_type_slug    = $('#property_type_slug').val();
		var start_date            = $('#start-date').val();
		var end_date              = $('#end-date').val();
		var coupon_code           = $("#coupon_code").val();
		var no_of_guest           = $("#no_of_guest").val();
		var available_no_of_slots = $('#available_no_of_slots').val();
		var price_per_sqft        = $('#price_per_sqft').val();
		var no_of_slots           = $('#no_of_slots').val();
		var property_area         = $('#property_area').val();
		var price_per_office      = $('#price_per_office').val();
		var price_per_night       = $('#price_per_night').val();
		var mandatory             = 1;

		if( employee == 'on' ) {
			var available_no_of_employee = $('#available_no_of_employee').val();
		} else {
			var available_no_of_employee = '';
		}

		if( room == 'on' ) {
			var available_no_of_room = $('#available_no_of_room').val();
		} else {
			var available_no_of_room = '';
		}

		if( desk == 'on' ) {
			var available_no_of_desk = $('#available_no_of_desk').val();
		} else {
			var available_no_of_desk = '';
		}

		if( cubicles == 'on' ) {
			var available_no_of_cubicles = $('#available_no_of_cubicles').val();
		} else {
			var available_no_of_cubicles = '';
		}

		var data = new FormData();
		data.append( 'enc_property_id', property_id );
		data.append( 'start_date', start_date );
		data.append( 'end_date', end_date );
		data.append( 'no_of_guest', no_of_guest );
		data.append( 'property_type_slug', property_type_slug );
		data.append( 'coupon_code', coupon_code );
		data.append( 'available_no_of_slots', available_no_of_slots );
		data.append( 'available_no_of_employee', available_no_of_employee );
		data.append( 'available_no_of_room', available_no_of_room );
		data.append( 'available_no_of_desk', available_no_of_desk );
		data.append( 'available_no_of_cubicles', available_no_of_cubicles );

		if(property_type_slug == 'warehouse') {
			if(available_no_of_slots == '') {
				mandatory = 0;
			}
		} else if(property_type_slug == 'office-space') {
			if(available_no_of_employee == '' && available_no_of_room == '' && available_no_of_desk == '' && available_no_of_cubicles == '') {
				mandatory = 0;
			}
		} else {
			if(no_of_guest == '') {
				mandatory = 0;
			}
		}

		if( start_date != '' && end_date != '' && property_id != '' && property_type_slug != '' && mandatory == 1 ) {

			showProcessingOverlay();

			$.ajax({
				headers:{'X-CSRF-Token': csrf_token},
				url:"{{ $module_url_path }}/booking/calculate_rate_for_selected_dates",
				method:"POST",
				data:data,
				contentType: false,
				cache: false,
				processData:false,
				success:function(response)
				{
					hideProcessingOverlay();

					if (response.status == "ERROR") {
						swal("Error", 'Something went wrong.', "warning");
						return;
					}

					if (response.status == "NO_RATES") {
						swal("Error", 'No rates are available.', "warning");
						return;
					}

					if (response.status == "UNAVAILABLE") {
						swal("Error", 'Selected date not available for booking, Please select another dates.', "warning"); 
						return;
					}

					if (response.status == "COUPON_INAPPLICABLE") {
						$('#is_coupon_code').val('invalid');
						swal("Error", 'Coupon code unavailable.', "warning");
						$("#coupon_code").val('');
						$("#txt_coupon_code").val('');
						$("#coupon_code").attr('disabled', false);
						$("#coupon_code").val('');
						$('#discount_amount').html('0');
						$("#apply_coupon_code").css("display", "none");
						$("#remove_coupon_code").css("display", "none");
						return;
					}

					if (response.status == "COUPON_UNAVAILABLE") {
						$('#is_coupon_code').val('invalid');
						$("#coupon_code").val('');
						$("#coupon_code").val('');
						$("#txt_coupon_code").val('');
						$("#coupon_code").attr('disabled', false);
						$("#coupon_code").val('');
						$('#discount_amount').html('0');
						$("#apply_coupon_code").css("display", "none");
						$("#remove_coupon_code").css("display", "none");
						swal("Error", 'Coupon code unavailable.', "warning");
						return;
					}

					if (response.status == "COUPON_INCORRECT") {
						$('#is_coupon_code').val('invalid');
						$("#coupon_code").val('');
						$("#coupon_code").val('');
						$("#txt_coupon_code").val('');
						$("#coupon_code").attr('disabled', false);
						$("#coupon_code").val('');
						$('#discount_amount').html('0');
						$("#apply_coupon_code").css("display", "none");
						$("#remove_coupon_code").css("display", "none");
						swal("Error", 'Incorrect coupon code.', "warning");
						return;
					}

					if( response.arr_data.total_payble_amount != undefined && response.arr_data.total_payble_amount != '' ) {

						$('#amount_div_id').show();

						if(response.status == "SUCCESS") {

							if(property_type_slug == 'warehouse') {

								var str = '<p class="text1"> (TA / TS) * SS * P * N = ' +response.arr_data.arr_cal_result.total_night_price+' '+response.arr_data.currency_code+'</p>';

								$("#total_area_desc").html(property_area+' Sq.Ft');
								$("#total_slot_desc").html(no_of_slots+' Slot(s)');
								$("#selected_no_of_slots_desc").html(response.arr_data.arr_cal_result.available_no_of_slots+' Slot(s)');
								$("#property_price_desc").html(response.arr_data.arr_cal_result.price_per_sqft+' '+response.arr_data.currency_code);
							} else if(property_type_slug == 'office-space') {
								var str1 = '';
								var str2 = '';
								var str3 = '';
								var str4 = '';
								var str5 = '';
								var str6 = '';
								var str7 = '';

								var str1 = '<p class="text1">N * ';

								if( $.trim( available_no_of_room ) != '' ) {
									str2 = ' ( SR * PR ) ';

									$("#div_room").css('display','block');
									$("#selected_no_of_rooms_desc").html(available_no_of_room+' Room(s)');
									$("#price_per_room_desc").html(response.arr_data.arr_cal_result.room_price+' '+response.arr_data.currency_code);
								} else if( $.trim( available_no_of_room ) == '' ) {
									$("#div_room").css('display','none');
								}

								if( $.trim( available_no_of_room ) != '' && $.trim( available_no_of_desk ) != '' ) {
									str3 = ' + ';
								} else if( $.trim( available_no_of_room ) != '' && $.trim( available_no_of_cubicles ) != '' ) {
									str3 = ' + ';
								}

								if( $.trim( available_no_of_desk ) != '' ) {
									str4 = '( SD * PD )';

									$("#div_desk").css('display','block');
									$("#selected_no_of_desk_desc").html(available_no_of_desk+' Desk(s)');
									$("#price_per_desk_desc").html(response.arr_data.arr_cal_result.desk_price+' '+response.arr_data.currency_code);
								} else if( $.trim( available_no_of_desk ) == '' ) {
									$("#div_desk").css('display','none');
								}								

								if( $.trim( available_no_of_desk ) != '' && $.trim( available_no_of_cubicles ) != '' ) {
									str5 = ' + ';
								} else if( $.trim( available_no_of_desk ) != '' && $.trim( available_no_of_cubicles ) == '' ) {
									str5 = '';
								}

								if( $.trim( available_no_of_cubicles ) != '' ) {
									str6 = '( SC * PC ) ';

									$("#div_cubicles").css('display','block');
									$("#selected_no_of_cubicles_desc").html(available_no_of_cubicles+' Cubicle(s)');
									$("#price_per_cubicles_desc").html(response.arr_data.arr_cal_result.cubicles_price+' '+response.arr_data.currency_code);
								} else if( $.trim( available_no_of_cubicles ) == '' ) {
									$("#div_cubicles").css('display','none');
								}
								
								str7 = ' = '+response.arr_data.arr_cal_result.total_night_price+' '+response.arr_data.currency_code+'</p>';

								var str = str1 + str2 + str3 + str4 + str5 + str6 + str7;
							} else {

								var str = '<p class="text1"> N * G * P = '+response.arr_data.arr_cal_result.total_night_price+' '+response.arr_data.currency_code+'</p>';

								$("#no_of_guest_desc").html(no_of_guest);
								$("#price_desc").html(response.arr_data.arr_cal_result.price_per_night+' '+response.arr_data.currency_code);
							}

							$('#total_charges_of_night').html(str);
							$('#total_payble_amt').html('<p class="text1">'+response.arr_data.amount_discount_inr+' INR</p>');
							$('#total_payble_amt_txt').val(response.arr_data.amount_discount_inr);

							$("#no_of_nights_desc").html(response.arr_data.arr_cal_result.number_of_nights+' Night(s)');
							
							$("#gst_tax_price").html('<div class="fee-bx"><div class="service-fee-title gsttip-rela"> GST Tax Price <div class="quetion-tool-gsttip-block" id="gst_tax_percentage" style="display:none;">GST Tax : '+ response.arr_data.arr_cal_result.gst +' % <span class="gsttip-rela-close" id="gst_tax_percentage_close"></span></div> <i class="fa fa-question-circle-o" id="show_gst_tax_percentage"></i> : </div> <div class="service-fee-price"><p class="text1">'+response.arr_data.arr_cal_result.gst_amount+' '+response.arr_data.currency_code+'</p></div></div>');

							$("#service_fee_price").html('<div class="fee-bx "><div class="service-fee-title gsttip-rela"> Service Fee Price <div class="quetion-tool-gsttip-block" id="service_fee_percentage" style="display:none;"> Service Fee : '+ response.arr_data.arr_cal_result.service_fee_percentage +' % <span class="gsttip-rela-close" id="service_fee_percentage_close"></span></div> <i class="fa fa-question-circle-o" id="show_service_fee_percentage"></i> : </div> <div class="service-fee-price"><p class="text1">'+response.arr_data.arr_cal_result.service_fee+' '+response.arr_data.currency_code+'</p></div></div>');

							if (response.arr_data.arr_cal_result.discount_price != 0) {
								$('#txt_discount_amount').val(response.arr_data.arr_cal_result.discount_price);
								$('#discount_amount').html(response.arr_data.arr_cal_result.discount_price+' INR');
								$("#apply_coupon_code").css("display", "none");
								$("#remove_coupon_code").css("display", "block");
								$("#coupon_code").attr('disabled', true);
							}

							var single_currency = '<div class="fee-bx"><div class="service-fee-title"> 1&nbsp;'+response.arr_data.currency_code +' : </div> <div class="service-fee-price"><p class="text1">'+response.arr_data.convert_single+' INR</p></div></div>';

							var total_amount = '<div class="fee-bx"><div class="service-fee-title">'+response.arr_data.total_payble_amount+'&nbsp;'+response.arr_data.currency_code +' : </div> <div class="service-fee-price"><p class="text1">'+response.arr_data.total_amount_inr+' INR</p></div></div>';

							if(response.arr_data.currency_code != "INR") {
								$('#single_currency_code').html(single_currency);
								$('#total_convert_amount').html(total_amount);
							} else {
								$('#single_currency_code').html('');
								$('#total_convert_amount').html('');
							}

							var wallet_payment = $("#wallet_payment").val();
							if(wallet_payment != '') {
								$("#btn_book_property").click();
							}
						}
					}
				},
				complete: function() {
				}
			});
		}
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

	$(document).ready(function() {
		
	});

	$("#coupon_code").on('keyup',function() {
		$('#is_coupon_code').val('');
	});

	// enter coupon code
	$("#coupon_code").on('input', function() {
		var coupon_code = $("#coupon_code").val();        
		if ($.trim(coupon_code) != '') {
			$("#apply_coupon_code").css("display", "block");
		} else {
			$("#apply_coupon_code").css("display", "none");
		}
	});

	// apply coupon code
  	$("#apply_coupon_code").click(function() {
		var start_date               = $("#start-date").val();
		var end_date                 = $("#end-date").val();
		var no_of_guest              = $('#no_of_guest').val();
		var property_type_slug       = $('#property_type_slug').val();
		var available_no_of_slots    = $('#available_no_of_slots').val();
		var available_no_of_employee = $('#available_no_of_employee').val();
		var coupon_code              = $("#coupon_code").val();
	  	
	  	if($.trim(coupon_code) != '') {

	  		if(coupon_code.length > 10) {
	  			$("#err_coupon_code").show();
	  			$("#err_coupon_code").html('Please enter 10 digit coupon code.');          
	  			return false;
	  		} else {
	  			$("#err_coupon_code").hide();
	  			$("#err_coupon_code").html('');

	  			calculate_property_rate();
	  		}

	  		if(property_type_slug == 'warehouse') {
				mandatory = available_no_of_slots;
			}
			else if(property_type_slug == 'office-space') {
				mandatory = available_no_of_employee;
			}
			else {
				mandatory = no_of_guest;
			}

	  		if(start_date != '' && end_date != '' && mandatory != '') {
	  			$("#txt_coupon_code").val(coupon_code);
	  			calculate_property_rate();
			} else {
				$('#err_start_date').show();
				$('#err_end_date').show();
				$("#err_start_date").html("Please select check in date");
				$("#err_end_date").html("Please select check out date");
				$("#err_"+mandatory).html("Please select any value");
				$("#err_start_date").fadeOut(8000);
				$("#err_end_date").fadeOut(8000);
				$("#err_"+mandatory).fadeOut(8000);
				$('html, body').animate({ scrollTop: $("#frm_book_property").offset().top }, 2000);
			}
		} else {
			$('#err_coupon_code').show();
			$("#err_coupon_code").html("Please select check in date");
			$("#err_coupon_code").fadeOut(8000);
		}
	});

	// remove coupon code
	$("#remove_coupon_code").click(function() {
		var start_date               = $("#start-date").val();
		var end_date                 = $("#end-date").val();
		var coupon_code              = $("#coupon_code").val();
		var property_type_slug       = $('#property_type_slug').val();
		var no_of_guest              = $('#no_of_guest').val();
		var available_no_of_slots    = $('#available_no_of_slots').val();
		var available_no_of_employee = $('#available_no_of_employee').val();

		if ($.trim(coupon_code) != '') {
			if(property_type_slug == 'warehouse') {
				mandatory = available_no_of_slots;
			}
			else if(property_type_slug == 'office-space') {
				mandatory = available_no_of_employee;
			}
			else {
				mandatory = no_of_guest;
			}

			if (start_date != '' && end_date != '' ) {
				coupon_code = '';

				$("#txt_coupon_code").val('');
				$("#coupon_code").attr('disabled', false);
				$("#coupon_code").val('');
				$('#discount_amount').html('0');
				$("#apply_coupon_code").css("display", "none");
				$("#remove_coupon_code").css("display", "none");

				calculate_property_rate();

			} else {
				$('#err_start_date').show();
				$('#err_end_date').show();
				$("#err_start_date").html("Please select check in date");
				$("#err_end_date").html("Please select check out date");
				$("#err_"+mandatory).html("Please select any value");
				$("#err_start_date").fadeOut(8000);
				$("#err_end_date").fadeOut(8000);
				$("#err_"+mandatory).fadeOut(8000);
				$('html, body').animate({ scrollTop: $("#frm_book_property").offset().top }, 2000);
			}
		}
	});

	$('#btn_before_login, #btn_book_property').click(function() {
		var start_date               = $("#start-date").val();
		var end_date                 = $("#end-date").val();
		var no_of_guest              = $('#no_of_guest').val();
		var property_name_slug       = $('#property_name_slug').val();
		var property_type_slug       = $('#property_type_slug').val();
		var available_no_of_slots    = $('#available_no_of_slots').val();
		var available_no_of_employee = $('#available_no_of_employee').val();
		var available_no_of_room     = $('#available_no_of_room').val();
		var available_no_of_desk     = $('#available_no_of_desk').val();
		var available_no_of_cubicles = $('#available_no_of_cubicles').val();
		var booking_id               = $("#ad_id").val();
		var is_login                 = $("#is_login").val();
		var is_valid_code            = $('#is_coupon_code').val();
		var coupon_code              = $('#coupon_code').val();
		var flag                     = 1;
		
		$("#err_coupon_code").html('');
		$("#err_coupon_code").hide();

		$("#err_start_date").html('');
		$("#err_end_date").html('');
		$("#err_available_no_of_slots").html('');
		$("#err_available_no_of_employee").html('');
		$("#err_available_no_of_room").html('');
		$("#err_available_no_of_desk").html('');
		$("#err_available_no_of_cubicles").html('');
		$("#err_no_of_guest").html('');

		if ($.trim(start_date) == '') {
			$("#err_start_date").html('This field is required.');
			flag = 0;
		}

		if ($.trim(end_date) == '') {
			$("#err_end_date").html('This field is required.');
			flag = 0;
		}

		if ($.trim(property_type_slug) == 'warehouse' && $.trim(available_no_of_slots) == '') {
			$("#err_available_no_of_slots").html('This field is required.');
			flag = 0;
		} else if ($.trim(property_type_slug) == 'office-space') {

			if ($.trim(available_no_of_employee) == '' && $.trim(available_no_of_room) == '' && $.trim(available_no_of_desk) == '' && $.trim(available_no_of_cubicles) == '') {
				$("#err_available_no_of_employee").html('This field is required.');
				$("#err_available_no_of_room").html('This field is required.');
				$("#err_available_no_of_desk").html('This field is required.');
				$("#err_available_no_of_cubicles").html('This field is required.');
				flag = 0;
			}
		} else if ($.trim(property_type_slug) != 'warehouse' && $.trim(property_type_slug) != 'office-space' && $.trim(no_of_guest) == '') {
			$("#err_no_of_guest").html('This field is required.');
			flag = 0;
		}

		if (coupon_code!='') {
			if (coupon_code.length > 10) {
				$("#err_coupon_code").show();
				$("#err_coupon_code").html('Please enter 10 digit coupon code.');          
				flag = 0; 
			}  
		}

		if (flag == 0) {
			return false;
		} else {

			$.ajax({
				'url':SITE_URL+'/property/booking/session_store',                    
				'type':'post',
				'data':{ _token:csrf_token, property_name_slug:property_name_slug, start_date:start_date, end_date:end_date, no_of_guest:no_of_guest, booking_id:booking_id, property_type_slug:property_type_slug, available_no_of_slots:available_no_of_slots, available_no_of_employee:available_no_of_employee, available_no_of_room:available_no_of_room, available_no_of_desk:available_no_of_desk, available_no_of_cubicles:available_no_of_cubicles },
				success:function(response) {
					if(response.status == 'success') {
						if(is_login == '1') {
							var form_data = $('#frm_book_property').serialize();
							var sendingRequest = null;
							sendingRequest = $.ajax({
								'url':SITE_URL+'/property/book_property',
								'type':'post',
								'data':{_token:csrf_token, form_data:form_data},
								'dataType':'json',
								beforeSend: function(data, statusText, xhr, wrapper) {
									if (sendingRequest != null) {
										swal('Server busy, Please try again later.');
										return false;
									}
								},
								success:function(res) {
									hideProcessingOverlay();

									if (res.status == 'success') {

										$("#pay-with-option").modal('show');
										$('.btn_confirm_wallet_payment, .btn_confirm_payment').attr("data-currency_code",res.currency_code);
										$('.btn_confirm_wallet_payment, .btn_confirm_payment').attr("data-amount",res.amount);
										$('.btn_confirm_wallet_payment, .btn_confirm_payment').attr("data-amount_inr",res.amount_inr);
										$('.btn_confirm_wallet_payment, .btn_confirm_payment').attr("data-needed",res.needed_amount);
										$('.btn_confirm_wallet_payment, .btn_confirm_payment').attr("data-needed_inr",res.needed_amount_inr);
										$('.btn_confirm_wallet_payment, .btn_confirm_payment').attr("data-payable",res.payable_amount);
										$('.btn_confirm_wallet_payment, .btn_confirm_payment').attr("data-payable_inr",res.payable_amount_INR);
										$('.btn_confirm_wallet_payment, .btn_confirm_payment').attr("data-booking_id",res.booking_id);
										$('.btn_confirm_wallet_payment, .btn_confirm_payment').attr("data-used_coupon_id",res.used_coupon_id);
										$('#current_wallet_amount').text(res.current_wallet_amount);

									} else if(res.status == 'warning') {
										swal({
											title: 'Sorry !',
											text: res.message,
											type: "warning",
											customClass:'NewErrorMsgFont',
											confirmButtonColor: "#ff4747",
											confirmButtonText: 'Okay'
										});
									} else {
										swal("Error", res.message, "error");
									}
								},
								complete: function()
								{
								}    
							});
						} else {
							window.location.href = "{{ url('/') }}/login";
						}
					}
				}
			});
		}      
	});

	$(".btn_confirm_wallet_payment,.btn_confirm_payment").click(function(){
		$("#pay-with-option").modal('hide');
	});

</script>