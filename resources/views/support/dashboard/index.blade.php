@extends('support.layout.master')                
@section('main_content')
<style type="text/css">
		.tmp-cms{
				margin-top: 100px;
				text-align: center;
				margin-bottom: 100px;
		}
		.btn-assign{
				border-radius: 5px!important;background-color: #8b4787; color: #ffffff!important;
		}
				.btn-assign:hover {
				background-color: #0a59c0;
		}
</style>

<!-- BEGIN Page Title -->
<div class="page-title">
		<div>
				<h1><i class="fa fa-dashboard"></i> Dashboard</h1>

		</div>
</div>
<!-- END Page Title -->

<!-- BEGIN Breadcrumb -->
<div id="breadcrumbs">
		<ul class="breadcrumb">
				<li class="active"><i class="fa fa-home"></i> Home</li>

		</ul>
</div>
<!-- END Breadcrumb -->

<!-- BEGIN Tiles -->
<div class="row">
		<div class="col-md-12">
		@include('support.layout._operation_status')
				{{-- <div class="tmp-cms">
					<h1>Coming Soon...!</h1>
			</div> --}} 
			<div class="col-md-3">
				<div class="tile tile-dark-blue">
						<div class="img">
								<i class="fa fa-ticket"></i>
						</div>
						<div class="content">
								<p class="big">{{isset($total_ticket) ? $total_ticket :'0'}}</p>
								<p class="title">Total Tickets</p>
						</div>
				</div>
		</div>

	 @if(isset($support_level) && $support_level=='L1')
		<div class="col-md-3">
			<a href="{{$support_url_path.'/verification'}}">
				<div class="tile tile-magenta">
						<div class="img">
								<i class="fa fa-handshake-o"></i>
						</div>
						<div class="content">
								<p class="big">{{isset($total_verification_request) ? $total_verification_request :'0'}}</p>
								<p class="title">Verification Requests</p>
						</div>
				</div>
			</a>
		</div>
	@endif
		<div class="col-md-3">
			<a href="{{$support_url_path.'/ticket/closed_ticket'}}">
				<div class="tile tile-gray">
						<div class="img">
								<i class="fa fa-thumbs-o-up"></i>
						</div>
						<div class="content">
								<p class="big">{{isset($total_closed_ticket)? $total_closed_ticket:'0'}}</p>
								<p class="title">Answered Tickets</p>
						</div>
				</div>
			</a>
		</div>
	@if(isset($support_level) && $support_level=='L1')
		<div class="col-md-3">
				<a href="{{$support_url_path.'/verification/approve'}}">
					<div class="tile tile-blue">
							<div class="img">
									<i class="fa fa-user-plus"></i>
							</div>
							<div class="content">
									<p class="big">{{isset($accepted_verification_request) ? $accepted_verification_request :'0'}}</p>
									<p class="title">Approved Requests</p>
							</div>
					</div>
				</a>
		</div>
	@endif
<div class="col-md-12">
	<div class="box box-black">
			<div class="box-content table-responsive" >

			<div class="col-md-12">

					<div class="tabbable">
							<ul id="myTab1" class="nav nav-tabs">
								<li class="active"><a href="{{url($module_url_path)}}"><i class="fa fa-ticket"></i> Recent Ticket</a></li>
								 @if(isset($support_level) && $support_level=='L1')
									<li><a href="{{url($module_url_path.'/verification')}}"><i class="fa fa-id-card-o"></i> Recent Verification Request</a></li>
								 @endif
							</ul>

							<div id="myTabContent1" class="tab-content">
									<div class="tab-pane fade in active" id="home1">
										<?php 
												$ticket_id       = Request::input('ticket_id');
												$user_name       = Request::input('user_name');
												$generated_on    = Request::input('generated_on');
										?>                   
											<div class="box-content" >     
														<form name="frm-manage" id="frm-manage" method="get" action="{{$module_url_path}}"  class="form-inline" >
																<div class="form-group" style="margin-top:6px;">
																	<label >Tocket Id: </label>
																	<input type="text"  placeholder="Tocket Id"  id="ticket_id" name="ticket_id" class="form-control"  value="{{ trim($ticket_id)}}"  >
																</div>
																&nbsp;&nbsp;
																<div class="form-group col-md-3" style="margin-top:6px;">
																 <label>Username :</label>
																	<select class="form-control chosen-with-diselect" tabindex="-1" id="user_name" name="user_name" style="width: 17% !important;">
																			<option value="">--Select Username--</option>
																				@if(isset($get_user) && count($get_user)>0)
																					@foreach($get_user as $user)
																					 <option value="{{ base64_encode($user->id) }}" {{ ( Request::get('user_name') != '' && Request::get('user_name') == base64_encode($user->id)) ? "selected" : '' }} > {{ $user->first_name.' '.$user->last_name }} 
																						</option>
																					 @endforeach
																				@endif
																	</select>
																</div>
																&nbsp;&nbsp;
																<div class="form-group" style="margin-top:6px;">
																	<label >Generated On: </label>
																	<input type="text"  placeholder="Generated On" class="date-picker form-control" id="generated_on" name="generated_on" class="form-control"  value="{{ trim($generated_on)}}" >
																</div>
																&nbsp;&nbsp;
																<div class="form-group" style="margin-top:6px;">
																	<input id="submit_filter" class="btn btn-primary same_search" type="submit" value="Search" name="btn_search"> &nbsp;
																	<a href="{{$module_url_path}}" class="btn btn-default">Reset </a>
																</div>
														 
															<input type="hidden" name="htoken" value="{{ csrf_token() }}">
															<div class="clearfix"></div>
													</form> 
											</div>    
											<div class="box-content table-responsive" >
												 <table  class="table table-advance" id="table_tickets" >
													<thead>
														<tr>
															<th>Ticket Id </th>
															<th>User Name</th>
															<th>Generated On</th>
															<th>Subject</th>
															<th>Status</th>
															<th>Action</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
													</table>
											</div>
									 </div>
						</div>
				</div>

		</div>
				</div>
		</div>
</div>
</div>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>

<script type="text/javascript"> 
	$("#generated_on").datepicker({ dateFormat: 'yy-mm-dd' });
	
		var ticket_id       = $('#ticket_id').val();
		var user_name       = $('#user_name').val();
		var generated_on    = $('#generated_on').val();

		var module_url_path = "{{url($module_url_path)}}";
		var temp_url        = module_url_path+'/load_ticket_data';
		var url             = temp_url.replace(/&amp;/g, '&'); 
	
		table_module    = $('#table_tickets').DataTable({

					"processing": true,
					"serverSide": true,
					"paging": true,
					"searching":false,
					"ordering": true,
					"destroy": true,
			ajax: 
			{
				'url'     : temp_url,
				'data'    : {'ticket_id':ticket_id,'user_name':user_name,'generated_on':generated_on}
			},
		 
		});  
</script>

<script type="text/javascript">

	$('#check-all').click(function (e) {
		$(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
	});

function accept_request(id)
{
	swal({
		title: "Are you sure",
		text: " Do you want to assign verification request to yourself ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: "btn-danger",
		confirmButtonText: "Confirm",
		closeOnConfirm: false
	},
	function(confirm){
		if(confirm){
		location.href="{{url($module_url_path)}}/assign_request/"+id;
		}
	 
	});
}

function accept_ticket(id)
{
	swal({
		title: "Are you sure",
		text: " Do you want to assign ticket to yourself ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: "btn-danger",
		confirmButtonText: "Confirm",
		closeOnConfirm: false
	},

	function(confirm){
		if(confirm){
			location.href="{{url($module_url_path)}}/assign_ticket/"+id;
		 //location.href="{{url($support_url_path)}}/ticket/";
		}

	});
}
	
</script>

@stop                    