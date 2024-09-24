@extends('front.layout.master') @section('main_content')
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
						<ul class="resp-tabs-list small">
							<a href="{{ url('/') }}/my-booking/new"><li class="resp-tab-item short resp-tab-active"> Pending </li></a>
							<a href="{{ url('/') }}/my-booking/confirmed"><li class="resp-tab-item"> Upcoming </li></a>
							<a href="{{ url('/') }}/my-booking/completed"><li class="resp-tab-item"> Completed </li></a>
							<a href="{{ url('/') }}/my-booking/cancelled"><li class="resp-tab-item"> Cancelled </li></a>
						</ul>
						<div class="clearfix"></div>
						<div class="resp-tabs-container margin new-for">

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

							@php $selected_status = isset($booking_status) ? $booking_status : ''; @endphp
							@if(isset($arr_booking['data']) && !empty($arr_booking['data']))
							<div>
								<div id="add_class">
									@foreach($arr_booking['data'] as $property) 

									@php 
									$id = isset($property['id']) ? $property['id'] : '';
									$booking_id = isset($property['booking_id']) && $property['booking_id'] !='' ? $property['booking_id'] : 'B0';
									$title = isset($property['property_details']['property_name']) ? $property['property_details']['property_name'] : ''; 


									$status = isset($property['booking_status']) ? $property['booking_status'] : ''; 

									if ($status == 3) {
										$html_status = 'awaiting'; 
										$status = 'Pending'; 
									}

									if ($status == 4) {
										$html_status = 'cancelled-by'; 
										$status = 'rejected'; 
									} 

									$booking_date = isset($property['created_at']) ? $property['created_at'] : ''; 

									if (!empty($booking_date) && $booking_date != null) {
										$booking_date = get_added_on_date($booking_date); 
									} 

									$check_in_date = isset($property['check_in_date']) ? $property['check_in_date'] : ''; 

									if (!empty($check_in_date) && $check_in_date != null) {
										$check_in_date = get_added_on_date($check_in_date); 
									} 

									$check_out_date = isset($property['check_out_date']) ? $property['check_out_date'] : ''; 

									if (!empty($check_out_date) && $check_out_date != null) {
										$check_out_date = get_added_on_date($check_out_date); 
									} 

									$currency = isset($property['property_details']['currency']) ? $property['property_details']['currency'] : ''; 
									$total_amount = isset($property['total_amount']) ? number_format($property['total_amount'],'2','.','' ) : ''; 
									$currency_code = isset($property['property_details']['currency_code']) ? $property['property_details']['currency_code'] : '';


									if ($currency_code != 'INR' && $currency != '') {
										$inr_currency = currencyConverter($currency_code, 'INR', $total_amount);

										if ($inr_currency=='0') {
											$inr_currency = $total_amount;
										}
									} else {
										$inr_currency = $total_amount;
									}


									$needed_amount = 0;   $property_currency = 0;
									if ($user_wallet >= $inr_currency) {
										$needed_amount = $inr_currency - $user_wallet;
										$property_currency = currencyConverter('INR', $currency_code, $needed_amount);
									}
									@endphp

									<div class="box-white-user booking-new">

										@if(auth()->guard('users')->user()!=null) @if(auth()->guard('users')->user()['id'] != $property['property_details']['user_id'] && Session::get('user_type') != null && Session::get('user_type') != '4') @if(count(check_favorite_property(auth()->guard('users')->user()['id'],$property['property_details']['id'])) > 0)
										<a onclick="makePropertyFavourite('{{ base64_encode($property['property_details']['id']) }}');" class="favorat-icn">          
											<i class="fa fa-heart"></i>
										</a> @else
										<a onclick="makePropertyFavourite('{{ base64_encode($property['property_details']['id']) }}');" class="favorat-icn">          
											<i class="fa fa-heart-o"></i>
										</a> @endif @else
										<a onclick="showAlert('Invalid User');" class="favorat-icn">          
											<i class="fa fa-heart-o"></i>
										</a> @endif @else
										<a href="{{url('/login')}}" class="favorat-icn">          
											<i class="fa fa-heart-o"></i>
										</a> @endif


										<div class="row">
											<div class="col-sm-12 col-md-8 col-lg-9">
												<div class="user-id-new"><span>Id:</span> {{ $booking_id }} </div>
												<a href="{{ $module_url_path }}/booking-details/{{ base64_encode(isset($property['id']) ? $property['id'] : '') }}">
													<div class="heading-user-title">{{ $title }}</div>
												</a>
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
													<div class="users-nw-books"> <span>Total</span> {{ $currency.$total_amount }}</div>
													@if(Session::get('user_type') != null && Session::get('user_type') == '1' && $property['booking_status']!='4')
													<!-- <button type="button" class="btn-cancel" onclick='cancel_booking("{{ base64_encode($id) }}")'>Cancel</button>                                         -->


													<div class="booking-detail-link">
														<a href="javascript:void(0)" data-id="{{$id}}" data-amount="{{$inr_currency}}" onclick="payment_process(this)" class="btn_confirm_payment btn-pays margin-b">Pay Online</a>

														<a href="javascript:void(0)" class="btn_process_payment btn-pays margin-b loader" style="display: none; height: 16;"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></a>

														<a href="javascript:void(0)" data-id="{{$id}}" data-amount="{{$inr_currency}}" data-wallet="{{number_format($user_wallet, 2, '.', '')}}" data-needed_inr="{{number_format($needed_amount, 2, '.', '')}}" data-needed="{{number_format($property_currency, 2, '.', '')}}" data-currency="{{$currency_code}}" class="btn_confirm_wallet_payment btn-pays margin-b" onclick="wallet_payment(this)" >Wallet Pay</a>

													</div>

													@endif
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									@endforeach
								</div>     
							</div>
							@else
							<div class="list-vactions-details">
								<div class="no-record-found"></div>
								<!-- <div class="content-list-vact" style="color: red;font-size: 13px;">
									<p>Sorry, we couldn't find any matches.</p>
								</div> -->
							</div>
							@endif
							<div class="clearfix"></div>
						</div>
					</div>
					@if(isset($obj_pagination) && $obj_pagination!=null)            
					@include('front.common.pagination ',['obj_pagination ' => $obj_pagination])
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
<!--reject booking popup start here-->
<!-- <div class="host-contact-popup upgrade payment">
   <div class="popup-inquiry-form">
	  <div id=RejectPopup class="modal fade" data-backdrop="static" role=dialog>
		 <div class=modal-dialog>
			<div class=modal-content>
			   <div class="modal-header black-close">
				  <button type=button class=close data-dismiss=modal>
				  <span class="contact-left-img popup-close nonebg"><img src="{{url('/ ')}}/front/images/popup-close-btn.png" alt=""></span>
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
								 <span class='error help-block ' id="err_reason">{{ $errors->first('reason ') }}</span>    
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
</div> -->
<!--reject booking popup end here-->
<!--cancel booking popup start here-->
<!-- <div class="host-contact-popup upgrade payment">
   <div class="popup-inquiry-form">
	  <div id=CancelPopup class="modal fade" data-backdrop="static" role=dialog>
		 <div class=modal-dialog>
			<div class=modal-content>
			   <div class="modal-header black-close">
				  <button type=button class=close data-dismiss=modal>
				  <span class="contact-left-img popup-close nonebg"><img src="{{url('/ ')}}/front/images/popup-close-btn.png" alt=""></span>
				  </button>
				  <h4 class=modal-title>Booking Cancel</h4>
			   </div>
			   <div class=modal-body>
				  <div class="payment-detail-tab-one">
					 <form name="frm_add_account" action="{{url('/')}}/my-booking/cancel/process" id="frm_add_account" method="POST">
						{{ csrf_field() }}
						<div class="row">
						   <div class="col-sm-12 col-md-12 col-lg-12">
							  <div class="form-group">
								 <textarea id="cancel_reason" name="cancel_reason" data-rule-required="true" data-rule-minlength="3"  data-rule-maxlength="50"  /></textarea>
								 <label for="add-bank-name-id">Reason</label> 
								 <span class='error help-block ' id="err_cancel_reason">{{ $errors->first('reason ') }}</span>    
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
</div> -->

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

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<!--cancel booking popup end here-->
<script type="text/javascript">
	$(document).ready(function(){
		$('#btn_search_status ').click(function(){
			var cmb_status = $("#cmb_status").val();

			if(cmb_status == ' ')
			{
				$('#err_cmb_status ').show();
				$('#err_cmb_status ').html('Please status to search ');
				$('#err_cmb_status ').fadeOut(8000);
				$('#cmb_status ').focus();
				return false;
			}
			else{
				$('#search_status_form ').submit();
			}

		});
		
		$("#btn_submit_reject").click(function(){
			var reason = $('#reason ').val();
			if(reason == ' ')
			{
				$('#err_reason ').show();
				$('#err_reason ').html('Please enter cancel reason ');
				$('#err_reason ').fadeOut(8000);
				$('#reason ').focus();
				return false;
			}
		});
		
		$("#btn_submit_cancel_process").click(function(){
			var cancel_reason = $('#cancel_reason ').val();
			if(cancel_reason == ' ')
			{
				$('#err_cancel_reason ').show();
				$('#err_cancel_reason ').html('Please enter cancel reason ');
				$('#err_cancel_reason ').fadeOut(8000);
				$('#cancel_reason ').focus();
				return false;
			}
		});
	});

	function reject_booking(id)
	{
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
		function()
		{
			$('.cancel ').click();
			$('#RejectPopup ').modal();
			$("#booking_id").val(id);
		});
	}

/*   function cancel_booking(id)
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
		   $('.cancel ').click();
		   $('#CancelPopup ').modal();
		   $("#cancel_booking_id").val(id);
	   });
	}*/

   //<!--tab js script-->  
   /*$('#horizontalTab ').easyResponsiveTabs({
	   type: 'default ',
	   width: 'auto ',
	   fit: true,
	   closed: 'accordion ',
	   activate: function (event) {
		   var $tab = $(this);
		   var $info = $('#tabInfo ');
		   var $name = $('span ', $info);
		   $name.text($tab.text());
		   $info.show();
	   }
	});*/
	function makePropertyFavourite(enc_property_id)
	{
		var is_user_login = '{{ validate_login( 'users') }} ';
		var property_id   = enc_property_id ;

		if(is_user_login == "")
		{
			showAlert("Please Login First!", "error");
			return false;
		}
		else
		{
			$.ajax({ 
				headers:{'X-CSRF-Token': csrf_token},
				url:"{{url('/')}}/property/favourite/"+property_id,
				type:'get',                    
				success:function(response)   
				{
					hideProcessingOverlay();
					if(response.status == 'success')
					{      
						$("#add_class").load(location.href + " #add_class");
						showAlert(response.message, "success");
					}
					else
					{
						showAlert(response.message, "error");
					}                      
				}

			});
		}

	}





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
							'data':{_token: token, transaction_id: response.razorpay_payment_id, payment_amount: payment_amount, booking_id: id ,page:'list'},
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