@extends('admin.layout.master') 
@section('main_content')
<style>
.btn-custom {
    background-color: #3C5877!important;
    margin-right: 5px;
}
</style>
<div class="page-title"><div></div></div>

<div id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-home"></i>
			<a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard</a>
		</li>
		<span class="divider">
			<i class="fa fa-angle-right"></i>
			<i class="fa {{$page_icon}}"></i>
		</span>
		<li class="active">{{ $page_title or ''}}</li>
	</ul>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-title">
				<h3>
					<i class="fa {{$page_icon}}"></i>
					{{ isset($page_title) ? $page_title : "" }}
				</h3>
				<div class="box-tool">
					<a data-action="collapse" href="#"></a>
					<a data-action="close" href="#"></a>
				</div>
			</div>
			<div class="box-content">
				@include('admin.layout._operation_status') 
				<div class="row">
					<div class="col-sm-12">
						<form name="validation-form" id="validation_form" method="POST" class="form-horizontal" action="{{$module_url_path}}/update/{{base64_encode($record['id'])}}" enctype="multipart/form-data"  files ="true">
							{{csrf_field()}}

							<div class="col-md-12">
								<div class="tabbable">
									<ul id="myTab1" class="nav nav-tabs">

	<li class="active"><a href="#payment" data-toggle="tab"><i class="fa fa-money"></i> Payment Gateway</a></li>
	<li><a href="#onesignal" data-toggle="tab"><i class="fa fa-lg fa-mobile"></i> Push Notifications</a></li>
	<li><a href="#mailchimp" data-toggle="tab"><i class="fa fa-lg fa-envelope"></i> Newsletter</a></li>
	<li><a href="#sms_gateway" data-toggle="tab"><i class="fa fa-lg fa-send"></i> SMS Gateway</a></li>
	<li><a href="#chat_api" data-toggle="tab"><i class="fa fa-lg fa-commenting"></i> Chat </a></li>
	<li><a href="#facebook_api" data-toggle="tab"><i class="fa fa-lg fa-facebook"></i> Facebook </a></li>
	<li><a href="#twitter_api" data-toggle="tab"><i class="fa fa-lg fa-twitter"></i> Twitter </a></li>
	<li><a href="#google_api" data-toggle="tab"><i class="fa fa-lg fa-google"></i> Google </a></li>

									</ul>

									<div id="myTabContent1" class="tab-content">
										<div class="tab-pane fade in active" id="payment">
											<div class="col-sm-9 col-lg-6 col-md-offset-3"><label><h4>Sandbox</h4></label></div>
											<div class="clearfix"></div>
											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">RazorPay Sandbox ID<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="text" name="razorpay_sandbox_id" id="razorpay_sandbox_id" class="form-control" placeholder="RazorPay Api Key" value="{{$record['razorpay_sandbox_id'] or ''}}" />
													<span class='help-block'>{{ $errors->first('razorpay_sandbox_id') }}
													</span>
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">RazorPay Sandbox Secret Key<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="password" name="razorpay_sandbox_secret" id="razorpay_sandbox_secret" class="form-control" placeholder="RazorPay Client Secret" value="{{$record['razorpay_sandbox_secret'] or ''}}">
													<span class='help-block'>{{ $errors->first('razorpay_sandbox_secret') }}
													</span>
												</div>
											</div>
											
											<hr>

                                            <div class="col-sm-9 col-lg-6 col-md-offset-3"><label><h4>Live</h4></label></div>
                                            <div class="clearfix"></div>
											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">RazorPay ID<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="text" name="razorpay_id" id="razorpay_id" class="form-control" placeholder="RazorPay Api Key" value="{{$record['razorpay_id'] or ''}}">
													<span class='help-block'>{{ $errors->first('razorpay_id') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">RazorPay Secret Key<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="password" name="razorpay_secret" id="razorpay_secret" class="form-control" placeholder="RazorPay Client Secret" value="{{$record['razorpay_secret'] or ''}}">
													<span class='help-block'>{{ $errors->first('razorpay_secret') }}
													</span>
												</div>
											</div>

											<hr>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Mode<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<label class="radio-inline"><input type="radio" value="1" name="payment_mode" @if(isset($record['payment_mode']) && $record['payment_mode']=='1') checked="checked" @endif>Live </label>
													<label class="radio-inline"><input type="radio" value="2" name="payment_mode" @if(isset($record['payment_mode']) && $record['payment_mode']=='2') checked="checked" @endif>Sanbox </label>
													<span class='help-block'>{{ $errors->first('payment_mode') }}
													</span>
												</div>
											</div>
										</div>

										<div class="tab-pane fade" id="onesignal">
										
										    <div class="col-sm-9 col-lg-6 col-md-offset-3"><label><h4>Sandbox</h4></label></div>
                                            <div class="clearfix"></div>
										
											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Api Sandbox Key<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="password" name="onesignal_sandbox_api_key" id="onesignal_sandbox_api_key" class="form-control" placeholder="OneSignal Api Key" value="{{$record['onesignal_sandbox_api_key'] or ''}}">
													<span class='help-block'>{{ $errors->first('onesignal_sandbox_api_key') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">App Sandbox ID<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="text" name="onesignal_sandbox_app_id" id="onesignal_sandbox_app_id" class="form-control" placeholder="OneSignal App Id" value="{{$record['onesignal_sandbox_app_id'] or ''}}">
													<span class='help-block'>{{ $errors->first('onesignal_sandbox_app_id') }}
													</span>
												</div>
											</div>

											<hr>

											<div class="col-sm-9 col-lg-6 col-md-offset-3"><label><h4>Live</h4></label></div>
                                            <div class="clearfix"></div>
											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Api Key<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="password" name="onesignal_api_key" id="onesignal_api_key" class="form-control" placeholder="OneSignal Api Key" value="{{$record['onesignal_api_key'] or ''}}">
													<span class='help-block'>{{ $errors->first('onesignal_api_key') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">App Id<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="text" name="onesignal_app_id" id="onesignal_app_id" class="form-control" placeholder="OneSignal App Id" value="{{$record['onesignal_app_id'] or ''}}">
													<span class='help-block'>{{ $errors->first('onesignal_app_id') }}
													</span>
												</div>
											</div>

											<hr>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Api Mode<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<label class="radio-inline"><input type="radio" value="1" name="onesignal_api_mode" @if(isset($record['onesignal_api_mode']) && $record['onesignal_api_mode']=='1') checked="checked" @endif>Live Mode</label>
													<label class="radio-inline"><input type="radio" value="2" name="onesignal_api_mode" @if(isset($record['onesignal_api_mode']) && $record['onesignal_api_mode']=='2') checked="checked" @endif > Sanbox Mode </label>
													<span class='help-block'>{{ $errors->first('onesignal_api_mode') }}</span>
												</div>
											</div>
										</div>     

										<div class="tab-pane fade" id="mailchimp">

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Mailchimp Api Key<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-9 controls">
													<input type="password" name="mailchimp_api_key" id="mailchimp_api_key" class="form-control" placeholder="Mailchimp Api Key" value="{{$record['mailchimp_api_key'] or ''}}">
													<span class='help-block'>{{ $errors->first('mailchimp_api_key') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Mailchimp List ID<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-9 controls">
													<input type="text" name="mailchimp_list_id" id="mailchimp_list_id" class="form-control" placeholder="Mailchimp List ID" value="{{$record['mailchimp_list_id'] or ''}}">
													<span class='help-block'>{{ $errors->first('mailchimp_list_id') }}
													</span>
												</div>
											</div>
										</div>

										<div class="tab-pane fade" id="sms_gateway">
											<div class="col-sm-9 col-lg-6 col-md-offset-3"><label><h4>Sandbox</h4></label></div>
                                            <div class="clearfix"></div>
											
											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Twilio Sandbox Service SID<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-9 controls">
													<input type="text" name="twilio_test_service_sid" id="twilio_test_service_sid" class="form-control" placeholder="Twilio Sandbox Service SID" value="{{ $record['twilio_test_service_sid'] or '' }}">
													<span class='help-block'>{{ $errors->first('twilio_test_service_sid') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Twilio Sandbox SID<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-9 controls">
													<input type="text" name="twilio_test_sid" id="twilio_test_sid" class="form-control" placeholder="Twilio Sandbox SID" value="{{$record['twilio_test_sid'] or ''}}">
													<span class='help-block'>{{ $errors->first('twilio_test_sid') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Twilio Sandbox Token<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-9 controls">
													<input type="text" name="twilio_test_token" id="twilio_test_token" class="form-control" placeholder="Twilio Sandbox Token" value="{{$record['twilio_test_token'] or ''}}">
													<span class='help-block'>{{ $errors->first('twilio_test_token') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Twilio Sandbox From User Mobile<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-9 controls">
													<input type="text" name="test_from_user_mobile" id="test_from_user_mobile" class="form-control" placeholder="Twilio Sandbox From User Mobile" value="{{$record['test_from_user_mobile'] or ''}}">
													<span class='help-block'>{{ $errors->first('test_from_user_mobile') }}
													</span>
												</div>
											</div>
											
											<hr>

											<div class="col-sm-9 col-lg-6 col-md-offset-3"><label><h4>Live</h4></label></div>
                                            <div class="clearfix"></div>
											
											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Twilio Service SID<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-9 controls">
													<input type="text" name="twilio_service_sid" id="twilio_service_sid" class="form-control" placeholder="Twilio Service SID" value="{{ $record['twilio_service_sid'] or '' }}">
													<span class='help-block'>{{ $errors->first('twilio_service_sid') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Twilio SID<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-9 controls">
													<input type="text" name="twilio_sid" id="twilio_sid" class="form-control" placeholder="Twilio SID" value="{{$record['twilio_sid'] or ''}}">
													<span class='help-block'>{{ $errors->first('twilio_sid') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Twilio Token<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-9 controls">
													<input type="text" name="twilio_token" id="twilio_token" class="form-control" placeholder="Twilio Token" value="{{$record['twilio_token'] or ''}}">
													<span class='help-block'>{{ $errors->first('twilio_token') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Twilio From User Mobile<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-9 controls">
													<input type="text" name="from_user_mobile" id="from_user_mobile" class="form-control" placeholder="Twilio From User Mobile" value="{{$record['from_user_mobile'] or ''}}">
													<span class='help-block'>{{ $errors->first('from_user_mobile') }}
													</span>
												</div>
											</div>

											<hr>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Mode<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<label class="radio-inline"><input type="radio" value="1" name="twilio_mode" @if(isset($record['twilio_mode']) && $record['twilio_mode']=='1') checked="checked" @endif> Live</label>
													<label class="radio-inline"><input type="radio" value="2" name="twilio_mode" @if(isset($record['twilio_mode']) && $record['twilio_mode']=='2') checked="checked" @endif>Sanbox</label>
													<span class='help-block'>{{ $errors->first('twilio_mode') }}
													</span>
												</div>
											</div>
										</div>

										<div class="tab-pane fade" id="chat_api">

											<div class="col-sm-9 col-lg-6 col-md-offset-3"><label><h4>Sandbox</h4></label></div>
                                            <div class="clearfix"></div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Freshchat Sandbox Token<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-9 controls">
													<input type="text" name="freshchat_api_test_token" id="freshchat_api_test_token" class="form-control" placeholder="Freshchat Sandbox token" value="{{$record['freshchat_api_test_token'] or ''}}">
													<span class='help-block'>{{ $errors->first('freshchat_api_test_token') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Freshchat Sandbox App ID<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-9 controls">
													<input type="text" name="freshchat_api_test_app_id" id="freshchat_api_test_app_id" class="form-control" placeholder="Freshchat Sandbox App ID" value="{{$record['freshchat_api_test_app_id'] or ''}}">
													<span class='help-block'>{{ $errors->first('freshchat_api_test_app_id') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Freshchat Sandbox App Key<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-9 controls">
													<input type="password" name="freshchat_api_test_app_key" id="freshchat_api_test_app_key" class="form-control" placeholder="Freshchat Sandbox App Key" value="{{$record['freshchat_api_test_app_key'] or ''}}">
													<span class='help-block'>{{ $errors->first('freshchat_api_test_app_key') }}
													</span>
												</div>
											</div>

											<hr>
											
											<div class="col-sm-9 col-lg-6 col-md-offset-3"><label><h4>Live</h4></label></div>
                                            <div class="clearfix"></div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Freshchat Token<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-9 controls">
													<input type="text" name="freshchat_api_token" id="freshchat_api_token" class="form-control" placeholder="Freshchat API token" value="{{$record['freshchat_api_token'] or ''}}">
													<span class='help-block'>{{ $errors->first('freshchat_api_token') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Freshchat App ID<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-9 controls">
													<input type="text" name="freshchat_api_app_id" id="freshchat_api_app_id" class="form-control" placeholder="Freshchat App ID" value="{{$record['freshchat_api_app_id'] or ''}}">
													<span class='help-block'>{{ $errors->first('freshchat_api_app_id') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Freshchat App Key<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-9 controls">
													<input type="password" name="freshchat_api_app_key" id="freshchat_api_app_key" class="form-control" placeholder="Freshchat App Key" value="{{$record['freshchat_api_app_key'] or ''}}">
													<span class='help-block'>{{ $errors->first('freshchat_api_app_key') }}
													</span>
												</div>
											</div>

											<hr>
											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Api Mode<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<label class="radio-inline"><input type="radio" value="1" name="freshchat_api_mode" @if(isset($record['freshchat_api_mode']) && $record['freshchat_api_mode']=='1') checked="checked" @endif>Live Mode</label>
													<label class="radio-inline"><input type="radio" value="2" name="freshchat_api_mode" @if(isset($record['freshchat_api_mode']) && $record['freshchat_api_mode']=='2') checked="checked" @endif > Sanbox Mode </label>
													<span class='help-block'>{{ $errors->first('freshchat_api_mode') }}</span>
												</div>
											</div>
										</div>

										<div class="tab-pane fade" id="facebook_api">
										
										    <div class="col-sm-9 col-lg-6 col-md-offset-3"><label><h4>Sandbox</h4></label></div>
                                            <div class="clearfix"></div>
										
											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Sandbox Client ID<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="text" name="facebook_sandbox_client_id" id="facebook_sandbox_client_id" class="form-control" placeholder="Sandbox Client ID" value="{{ $record['facebook_sandbox_client_id'] or '' }}">
													<span class='help-block'>{{ $errors->first('facebook_sandbox_client_id') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Sandbox Client Secret<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="password" name="facebook_sandbox_client_secret" id="facebook_sandbox_client_secret" class="form-control" placeholder="Sandbox Client Secret" value="{{ $record['facebook_sandbox_client_secret'] or '' }}">
													<span class='help-block'>{{ $errors->first('facebook_sandbox_client_secret') }}
													</span>
												</div>
											</div>

											<hr>

											<div class="col-sm-9 col-lg-6 col-md-offset-3"><label><h4>Live</h4></label></div>
                                            <div class="clearfix"></div>
											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Client ID<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="text" name="facebook_client_id" id="facebook_client_id" class="form-control" placeholder="Client ID" value="{{ $record['facebook_client_id'] or '' }}">
													<span class='help-block'>{{ $errors->first('facebook_client_id') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Client Secret<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="password" name="facebook_client_secret" id="facebook_client_secret" class="form-control" placeholder="Client Secret" value="{{ $record['facebook_client_secret'] or '' }}">
													<span class='help-block'>{{ $errors->first('facebook_client_secret') }}
													</span>
												</div>
											</div>

											<hr>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Api Mode<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<label class="radio-inline"><input type="radio" value="1" name="facebook_api_mode" @if(isset($record['facebook_api_mode']) && $record['facebook_api_mode']=='1') checked="checked" @endif>Live Mode</label>
													<label class="radio-inline"><input type="radio" value="2" name="facebook_api_mode" @if(isset($record['facebook_api_mode']) && $record['facebook_api_mode']=='2') checked="checked" @endif > Sanbox Mode </label>
													<span class='help-block'>{{ $errors->first('facebook_api_mode') }}</span>
												</div>
											</div>
										</div>

										<div class="tab-pane fade" id="twitter_api">
										
										    <div class="col-sm-9 col-lg-6 col-md-offset-3"><label><h4>Sandbox</h4></label></div>
                                            <div class="clearfix"></div>
										
											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Sandbox Client ID<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="text" name="twitter_sandbox_client_id" id="twitter_sandbox_client_id" class="form-control" placeholder="Sandbox Client ID" value="{{ $record['twitter_sandbox_client_id'] or '' }}">
													<span class='help-block'>{{ $errors->first('twitter_sandbox_client_id') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Sandbox Client Secret<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="password" name="twitter_sandbox_client_secret" id="twitter_sandbox_client_secret" class="form-control" placeholder="Sandbox Client Secret" value="{{ $record['twitter_sandbox_client_secret'] or '' }}">
													<span class='help-block'>{{ $errors->first('twitter_sandbox_client_secret') }}
													</span>
												</div>
											</div>

											<hr>

											<div class="col-sm-9 col-lg-6 col-md-offset-3"><label><h4>Live</h4></label></div>
                                            <div class="clearfix"></div>
											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Client ID<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="text" name="twitter_client_id" id="twitter_client_id" class="form-control" placeholder="Client ID" value="{{ $record['twitter_client_id'] or '' }}">
													<span class='help-block'>{{ $errors->first('twitter_client_id') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Client Secret<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="password" name="twitter_client_secret" id="twitter_client_secret" class="form-control" placeholder="Client Secret" value="{{ $record['twitter_client_secret'] or '' }}">
													<span class='help-block'>{{ $errors->first('twitter_client_secret') }}
													</span>
												</div>
											</div>
											<hr>
											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Api Mode<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<label class="radio-inline"><input type="radio" value="1" name="twitter_api_mode" @if(isset($record['twitter_api_mode']) && $record['twitter_api_mode']=='1') checked="checked" @endif>Live Mode</label>
													<label class="radio-inline"><input type="radio" value="2" name="twitter_api_mode" @if(isset($record['twitter_api_mode']) && $record['twitter_api_mode']=='2') checked="checked" @endif > Sanbox Mode </label>
													<span class='help-block'>{{ $errors->first('twitter_api_mode') }}</span>
												</div>
											</div>
										</div>

										<div class="tab-pane fade" id="google_api">
										
										    <div class="col-sm-9 col-lg-6 col-md-offset-3"><label><h4>Sandbox</h4></label></div>
                                            <div class="clearfix"></div>
										
											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Sandbox Client ID<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="text" name="google_sandbox_client_id" id="google_sandbox_client_id" class="form-control" placeholder="Sandbox Client ID" value="{{ $record['google_sandbox_client_id'] or '' }}">
													<span class='help-block'>{{ $errors->first('google_sandbox_client_id') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Sandbox Client Secret<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="password" name="google_sandbox_client_secret" id="google_sandbox_client_secret" class="form-control" placeholder="Sandbox Client Secret" value="{{ $record['google_sandbox_client_secret'] or '' }}">
													<span class='help-block'>{{ $errors->first('google_sandbox_client_secret') }}
													</span>
												</div>
											</div>

											<hr>

											<div class="col-sm-9 col-lg-6 col-md-offset-3"><label><h4>Live</h4></label></div>
                                            <div class="clearfix"></div>
											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Client ID<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="text" name="google_client_id" id="google_client_id" class="form-control" placeholder="Client ID" value="{{ $record['google_client_id'] or '' }}">
													<span class='help-block'>{{ $errors->first('google_client_id') }}
													</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Client Secret<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<input type="password" name="google_client_secret" id="google_client_secret" class="form-control" placeholder="Client Secret" value="{{ $record['google_client_secret'] or '' }}">
													<span class='help-block'>{{ $errors->first('google_client_secret') }}
													</span>
												</div>
											</div>
											<hr>
											<div class="form-group">
												<label class="col-sm-3 col-lg-3 control-label">Api Mode<i class="red">*</i></label>
												<div class="col-sm-9 col-lg-6 controls">
													<label class="radio-inline"><input type="radio" value="1" name="google_api_mode" @if(isset($record['google_api_mode']) && $record['google_api_mode']=='1') checked="checked" @endif>Live Mode</label>
													<label class="radio-inline"><input type="radio" value="2" name="google_api_mode" @if(isset($record['google_api_mode']) && $record['google_api_mode']=='2') checked="checked" @endif > Sanbox Mode </label>
													<span class='help-block'>{{ $errors->first('google_api_mode') }}</span>
												</div>
											</div>
										</div>

									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-9 col-sm-offset-3 col-lg-7 col-lg-offset-3">
										<i class="red"> 
											<span class="label label-important">NOTE!</span>
											Please fill all credentials in each tab 
										</i>
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-9 col-sm-offset-3 col-lg-7 col-lg-offset-3">
										<button class="btn btn-primary btn-custom">Update</button>
										<a href="{{url($admin_panel_slug)}}/api_credentials" class="btn btn-cancel">Cancel</a>
									</div>
								</div>

							</form>      
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() 
		{ 
			jQuery('#validation_form').validate({
				ignore: [],
				errorPlacement: function(error, element) 
				{
					if(element.attr("name") == "category_image")
					{ 
						error.appendTo("#category_image");
					}
					else
					{
						error.insertAfter(element);
					}
				}
			});
		});
	</script>
	@stop
