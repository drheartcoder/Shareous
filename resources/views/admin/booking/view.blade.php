@extends('admin.layout.master')
<style>
    .form-group .control-label{font-weight: 600;}
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{ font-size: 14px; color: #333; }
    .table{ border: 1px solid #ccc; }
</style>
@section('main_content')

<!-- BEGIN Page Title -->
<div class="page-title">
  <div>
    <h1>
      <i class="fa fa-eye"></i>
      {{ isset($page_title)?$page_title:"" }}
    </h1>
  </div>
</div>
<!-- END Page Title -->

<!-- BEGIN Breadcrumb -->
<div id="breadcrumbs">
  <ul class="breadcrumb">
    <li>
      <i class="fa fa-home"></i>
      <a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard</a>
    </li>
    <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa {{ $module_icon }}"></i>
      <a href="{{ url()->previous() }}">Booking</a>
    </span> 
    <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa fa-eye"></i>
    </span>
    <li class="active">{{ $page_title or '' }}</li>
  </ul>
</div>
<!-- END Breadcrumb -->



<!-- BEGIN Main Content -->
<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-title">
        <h3>
          <i class="fa {{ $module_icon }}"></i>
          {{ isset($page_title)?$page_title:"" }}
        </h3>
      </div>
      <div class="box-content">

        @include('admin.layout._operation_status')
        
        @if(isset($status) && $status !="" )

        <div class="row">
          <div class="col-sm-12">
            <form name="validation-form" id="validate_form" method="POST" class="form-horizontal" action="{{ $module_url_path }}/change_status/{{ $id }}/{{ $status }}/comment" enctype="multipart/form-data" files ="true">
              {{ csrf_field() }}

              <div class="form-group col-lg-11">
                <label class="col-sm-4 col-lg-3 control-label" for="page_title" style="text-align: right;">Reason For Rejection <i class="red">*</i> </label>
                <div class="col-sm-8 col-lg-5 controls">
                  <textarea name="reject_comment" id="reject_comment" rows="4" data-rule-maxlength="255" class="form-control" data-rule-required="true" placeholder="Enter Reason for rejection">{{ old('reject_comment') }}</textarea>
                  <span class='error help-block'>{{ $errors->first('reject_comment') }}</span>
                </div>          
              </div>              
              <div class="form-group col-lg-11">
                <div class="col-sm-6 col-sm-offset-3 col-lg-6 col-lg-offset-3">
                  <input type="submit" value="Save" class="btn btn btn-primary btn-custom">
                  <a href="{{ $module_url_path.'/all' }}" type="button" class="btn btn-cancel">Cancel</a>
                </div>
              </div>
            </form> 
            </div>
          </div>
        </div>                
        
        @endif

        @if(isset($arr_property_data) && sizeof($arr_property_data)>0)
           
        <?php $proprty_type_slug = get_property_type_slug($arr_property_data['property_details']['property_type_id']); ?>

        <div class="row">
           <div class="col-sm-12">
            <div class="panel panel-default">
              <div class="panel-heading font-bold">Booking Details</div>
              <div class="panel-body">

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">Booking ID:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['booking_id']) && $arr_property_data['booking_id'] != "" ? $arr_property_data['booking_id'] : 'NA' }}
                  </div>
                </div> 

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">Check In Date:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['check_in_date']) && $arr_property_data['check_in_date'] != "" ? get_added_on_date($arr_property_data['check_in_date']) : 'NA' }}
                  </div>
                </div> 

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">Check Out Date:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['check_out_date']) && $arr_property_data['check_out_date'] != "" ? get_added_on_date($arr_property_data['check_out_date']) : 'NA' }}
                  </div>
                </div> 

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">Booking For:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['no_of_days']) && $arr_property_data['no_of_days'] != "" ? $arr_property_data['no_of_days']." Day's" : 'NA' }}
                  </div>
                </div> 
                <?php 
                  if ($proprty_type_slug == 'warehouse') {
                    $price_title = 'Property Per Sq.Ft Amount';
                    $gst = get_gst_data(0, 'warehouse');
                  }
                  else if ($proprty_type_slug == 'office-space') {
                    $price_title = 'Property Per office Amount';
                    $gst = get_gst_data(0, 'office-space');
                  }
                  else {
                    $price_title = 'Property Per Night Amount';

                    $property_amount = isset($arr_property_data['property_amount']) && $arr_property_data['property_amount'] != "" ? $arr_property_data['property_amount'] : '0';
                    $gst = get_gst_data($property_amount, 'other');
                  }
                ?>
                
                @if ($proprty_type_slug != 'office-space')
                  <div class="form-group col-sm-6">
                    <label class="col-lg-4 control-label">{{ $price_title }}:</label>
                    <div class="col-lg-8">
                      {!! isset($arr_property_data['property_amount']) && $arr_property_data['property_amount'] != "" ? '<i class="fa fa-inr" aria-hidden="true"></i> '.$arr_property_data['property_amount'] : 'NA' !!}
                    </div>
                  </div>
                @endif

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">Admin Commission:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['admin_commission']) && $arr_property_data['admin_commission'] != "" ? $arr_property_data['admin_commission'].' %' : 'NA' }}
                  </div>
                </div> 

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">Service Fee:</label>
                  <div class="col-lg-8">
                    {!! isset($arr_property_data['service_fee']) && $arr_property_data['service_fee'] != "" ? '<i class="fa fa-inr" aria-hidden="true"></i> '.$arr_property_data['service_fee'] : 'NA' !!}
                  </div>
                </div>

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">GST Tax:</label>
                  <div class="col-lg-8">
                    {{ $gst.' %' }}
                  </div>
                </div>

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">GST Tax Price:</label>
                  <div class="col-lg-8">
                    {!! isset($arr_property_data['gst_amount']) && $arr_property_data['gst_amount'] != "" ? '<i class="fa fa-inr" aria-hidden="true"></i> '.$arr_property_data['gst_amount'] : 'NA' !!}
                  </div>
                </div>  
                
                @if($proprty_type_slug == 'warehouse')
                <div class="form-group col-sm-6">
                    <label class="col-lg-4 control-label">No of Slots:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['selected_no_of_slots']) && $arr_property_data['selected_no_of_slots'] != "" ? $arr_property_data['selected_no_of_slots'] : 'NA' }}
                    </div>
                </div>

                @elseif($proprty_type_slug == 'office-space')
                
                <?php
                  $selected_of_room     = isset($arr_property_data['selected_of_room']) && $arr_property_data['selected_of_room'] != "" ? $arr_property_data['selected_of_room'] : '0';
                  $selected_of_desk     = isset($arr_property_data['selected_of_desk']) && $arr_property_data['selected_of_desk'] != "" ? $arr_property_data['selected_of_desk'] : '0';
                  $selected_of_cubicles = isset($arr_property_data['selected_of_cubicles']) && $arr_property_data['selected_of_cubicles'] != "" ? $arr_property_data['selected_of_cubicles'] : '0';

                  $room_amount     = isset($arr_property_data['room_amount']) && $arr_property_data['room_amount'] != "" ? $arr_property_data['room_amount'] : '0';
                  $desk_amount     = isset($arr_property_data['desk_amount']) && $arr_property_data['desk_amount'] != "" ? $arr_property_data['desk_amount'] : '0';
                  $cubicles_amount = isset($arr_property_data['cubicles_amount']) && $arr_property_data['cubicles_amount'] != "" ? $arr_property_data['cubicles_amount'] : '0';
                ?>

                @if( $selected_of_room != 0 )
                <div class="form-group col-sm-6">
                    <label class="col-lg-4 control-label">No of Room:</label>
                    <div class="col-lg-8">
                      {{ $selected_of_room }}
                    </div>
                </div>

                <div class="form-group col-sm-6">
                    <label class="col-lg-4 control-label">Price per Room:</label>
                    <div class="col-lg-8">
                      {!! '<i class="fa fa-inr" aria-hidden="true"></i> '.$room_amount !!}
                    </div>
                </div>
                @endif

                @if( $selected_of_desk != 0 )
                <div class="form-group col-sm-6">
                    <label class="col-lg-4 control-label">No of Desk:</label>
                    <div class="col-lg-8">
                      {{ $selected_of_desk }}
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="col-lg-4 control-label">Price per Dedicated Desk:</label>
                    <div class="col-lg-8">
                      {!! '<i class="fa fa-inr" aria-hidden="true"></i> '.$desk_amount !!}
                    </div>
                </div>
                @endif

                @if( $selected_of_cubicles != 0 )
                <div class="form-group col-sm-6">
                    <label class="col-lg-4 control-label">No of Cubicles:</label>
                    <div class="col-lg-8">
                      {{ $selected_of_cubicles }}
                    </div>
                </div>

                <div class="form-group col-sm-6">
                    <label class="col-lg-4 control-label">Price per Cubicles:</label>
                    <div class="col-lg-8">
                      {!! '<i class="fa fa-inr" aria-hidden="true"></i> '.$cubicles_amount !!}
                    </div>
                </div>
                @endif

                @else
                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">No of Guest:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['no_of_guest']) && $arr_property_data['no_of_guest'] != "" ? $arr_property_data['no_of_guest'] : 'NA' }}
                  </div>
                </div>
                @endif

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">Coupen Code Amount:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['coupen_code_amount']) && $arr_property_data['coupen_code_amount'] != "" ? $arr_property_data['property_details']['currency']."&nbsp;".$arr_property_data['coupen_code_amount'] : 'NA' }}
                  </div>
                </div>

                @if(isset($arr_property_data['refund_amount']) && $arr_property_data['refund_amount'] != '0.00')
                  <div class="form-group col-sm-6">
                    <label class="col-lg-4 control-label">Refund Amount:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['refund_amount']) && $arr_property_data['refund_amount'] != "" ? $arr_property_data['property_details']['currency']."&nbsp;".$arr_property_data['refund_amount'] : 'NA' }}
                    </div>
                  </div> 
                @endif

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">Booking Date:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['created_at']) && $arr_property_data['created_at'] != "" ? get_added_on_date($arr_property_data['created_at']) : 'NA' }}
                  </div>
                </div> 

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">Host Booking Accepted Date:</label>
                  <div class="col-lg-8">
                    {{isset($arr_property_data['host_accepted_date']) && $arr_property_data['host_accepted_date'] != "0000-00-00" ? get_added_on_date($arr_property_data['host_accepted_date']) : 'NA' }}
                  </div>
                </div> 

               
                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">Host Booking Reject Amount:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['host_rejected_date']) && $arr_property_data['host_rejected_date'] != "0000-00-00" ? get_added_on_date($arr_property_data['host_rejected_date']) : 'NA' }}
                  </div>
                </div> 

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">Cancelled Date:</label>
                  <div class="col-lg-8">
                    {{isset($arr_property_data['cancelled_date']) && $arr_property_data['cancelled_date'] != "0000-00-00" ? get_added_on_date($arr_property_data['cancelled_date']) : 'NA' }}
                  </div>
                </div> 

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">Reject Reason:</label>
                  <div class="col-lg-8">
                    {{isset($arr_property_data['reject_reason']) && $arr_property_data['reject_reason'] != "" ? $arr_property_data['reject_reason'] : 'NA' }}
                  </div>
                </div>

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">Total Amount:</label>
                  <div class="col-lg-8">
                    {!! isset($arr_property_data['total_night_price']) && $arr_property_data['total_night_price'] != "" ? '<i class="fa fa-inr" aria-hidden="true"></i> '.$arr_property_data['total_night_price'] : 'NA' !!}
                  </div>
                </div>

                @php
                  $cancelled_reason = isset($arr_property_data['cancelled_reason']) && $arr_property_data['cancelled_reason'] != "" ? $arr_property_data['cancelled_reason'] : '';
                @endphp
                
                @if( !empty($cancelled_reason) && $cancelled_reason != null )
                  <div class="form-group col-sm-6">
                    <label class="col-lg-4 control-label">Cancelled Reason:</label>
                    <div class="col-lg-8">
                      {{ $cancelled_reason }}
                    </div>
                  </div>
                @endif

              </div>
            </div>
          </div>
        </div>

        <div class="row">
           <div class="col-sm-12">
            <div class="panel panel-default">
              <div class="panel-heading font-bold">Booked By User Details</div>
              <div class="panel-body">

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">Full Name:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['booking_by_user_details']['first_name']) && $arr_property_data['booking_by_user_details']['first_name'] != "" && isset($arr_property_data['booking_by_user_details']['last_name']) && $arr_property_data['booking_by_user_details']['last_name'] != "" ? $arr_property_data['booking_by_user_details']['first_name'].' '.$arr_property_data['booking_by_user_details']['last_name'] : 'NA' }}
                  </div>
                </div> 

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">Email:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['booking_by_user_details']['email']) && $arr_property_data['booking_by_user_details']['email'] != "" ? $arr_property_data['booking_by_user_details']['email'] : 'NA' }}
                  </div>
                </div> 

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">Conatct Number:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['booking_by_user_details']['mobile_number']) && $arr_property_data['booking_by_user_details']['mobile_number'] != "" ? $arr_property_data['booking_by_user_details']['mobile_number'] : 'NA' }}
                  </div>
                </div> 

                <div class="form-group col-sm-6">
                  <label class="col-lg-4 control-label">Address:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['booking_by_user_details']['address']) && $arr_property_data['booking_by_user_details']['address'] != "" ? $arr_property_data['booking_by_user_details']['address'] : 'NA' }}
                  </div>
                </div> 

              
              </div>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading font-bold">Property Details</div>
              <div class="panel-body">
                <div class="form-group col-sm-12">
                  <label class="col-lg-4 control-label">Name:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['property_details']['property_name']) && $arr_property_data['property_details']['property_name'] != "" ? $arr_property_data['property_details']['property_name'] : 'NA' }}
                  </div>
                </div>                                                 
                <div class="form-group col-sm-12">
                  <label class="col-lg-4 control-label">Propery Type:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['property_details']['property_type']['name']) && $arr_property_data['property_details']['property_type']['name'] != "" ? $arr_property_data['property_details']['property_type']['name'] : 'NA' }}
                  </div>
                </div> 
                <div class="form-group col-sm-12">
                  <label class="col-lg-4 control-label">Description:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['property_details']['description']) && $arr_property_data['property_details']['description'] != "" ? $arr_property_data['property_details']['description'] : 'NA' }}
                  </div>
                </div>  
                 
                  @if($proprty_type_slug == 'warehouse')
                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Total Area:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['property_area']) && $arr_property_data['property_details']['property_area'] != "" ? $arr_property_data['property_details']['property_area'] : '0' }} Sq.Ft
                    </div>
                  </div>
                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Total Build Area:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['total_build_area']) && $arr_property_data['property_details']['total_build_area'] != "" ? $arr_property_data['property_details']['total_build_area'] : '0' }} Sq.Ft
                    </div>
                  </div>
                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Admin Area:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['admin_area']) && $arr_property_data['property_details']['admin_area'] != "" ? $arr_property_data['property_details']['admin_area'] : '0' }} Sq.Ft
                    </div>
                  </div>
                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Price:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['price_per_sqft']) && $arr_property_data['property_details']['price_per_sqft'] != "" ? $arr_property_data['property_details']['currency'].$arr_property_data['property_details']['price_per_sqft'] : '0' }}
                    </div>
                  </div>
                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">No. Of Slots:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['no_of_slots']) && $arr_property_data['property_details']['no_of_slots'] != "" ? $arr_property_data['property_details']['no_of_slots'] : '0' }}
                    </div>
                  </div>
                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Available No. Of Slots:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['available_no_of_slots']) && $arr_property_data['property_details']['available_no_of_slots'] != "" ? $arr_property_data['property_details']['available_no_of_slots'] : '0' }}
                    </div>
                  </div>
                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Working Status:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['property_working_status']) && $arr_property_data['property_details']['property_working_status'] != "" ? $arr_property_data['property_details']['property_working_status'] : '0' }}
                    </div>
                  </div>
                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Build Type:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['build_type']) && $arr_property_data['property_details']['build_type'] != "" ? $arr_property_data['property_details']['build_type'] : '0' }}
                    </div>
                  </div>
                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Good Storage:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['good_storage']) && $arr_property_data['property_details']['good_storage'] != "" ? $arr_property_data['property_details']['good_storage'] : '0' }}
                    </div>
                  </div>
                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Custom Type:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['custom_type']) && $arr_property_data['property_details']['custom_type'] != "" ? $arr_property_data['property_details']['custom_type'] : '0' }}
                    </div>
                  </div>
                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Prperty Management:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['management']) && $arr_property_data['property_details']['management'] != "" ? $arr_property_data['property_details']['management'] : '0' }}
                    </div>
                  </div>
                @elseif($proprty_type_slug == 'office-space')
                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Total Area:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['property_area']) && $arr_property_data['property_details']['property_area'] != "" ? $arr_property_data['property_details']['property_area'] : '0' }} Sq.Ft
                    </div>
                  </div>

                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Total Build Area:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['total_build_area']) && $arr_property_data['property_details']['total_build_area'] != "" ? $arr_property_data['property_details']['total_build_area'] : '0' }} Sq.Ft
                    </div>
                  </div>

                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Admin Area:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['admin_area']) && $arr_property_data['property_details']['admin_area'] != "" ? $arr_property_data['property_details']['admin_area'] : '0' }} Sq.Ft
                    </div>
                  </div>

                  <?php
                    $employee = isset( $arr_property_data['property_details']['employee'] ) ? $arr_property_data['property_details']['employee'] : '';
                    $room     = isset( $arr_property_data['property_details']['room'] ) ? $arr_property_data['property_details']['room'] : '';
                    $desk     = isset( $arr_property_data['property_details']['desk'] ) ? $arr_property_data['property_details']['desk'] : '';
                    $cubicles = isset( $arr_property_data['property_details']['cubicles'] ) ? $arr_property_data['property_details']['cubicles'] : '';
                  ?>

                  @if( $room == 'on' )
                    <div class="form-group col-sm-12">
                      <label class="col-lg-4 control-label">No. Of Room:</label>
                      <div class="col-lg-8">
                        {{ isset($arr_property_data['property_details']['no_of_room']) && $arr_property_data['property_details']['no_of_room'] != "" ? $arr_property_data['property_details']['no_of_room'] : '0' }}
                      </div>
                    </div>

                    <div class="form-group col-sm-12">
                      <label class="col-lg-4 control-label">Price per Room:</label>
                      <div class="col-lg-8">
                        {{ isset($arr_property_data['property_details']['room_price']) && $arr_property_data['property_details']['room_price'] != "" ? $arr_property_data['property_details']['currency'].$arr_property_data['property_details']['room_price'] : '0' }}
                      </div>
                    </div>
                  @endif

                  @if( $desk == 'on' )
                    <div class="form-group col-sm-12">
                      <label class="col-lg-4 control-label">No. Of Desk:</label>
                      <div class="col-lg-8">
                        {{ isset($arr_property_data['property_details']['no_of_desk']) && $arr_property_data['property_details']['no_of_desk'] != "" ? $arr_property_data['property_details']['no_of_desk'] : '0' }}
                      </div>
                    </div>

                    <div class="form-group col-sm-12">
                      <label class="col-lg-4 control-label">Price per Dedicated Desk:</label>
                      <div class="col-lg-8">
                        {{ isset($arr_property_data['property_details']['desk_price']) && $arr_property_data['property_details']['desk_price'] != "" ? $arr_property_data['property_details']['currency'].$arr_property_data['property_details']['desk_price'] : '0' }}
                      </div>
                    </div>
                  @endif

                  @if( $cubicles == 'on' )
                    <div class="form-group col-sm-12">
                      <label class="col-lg-4 control-label">No. Of Cubicles:</label>
                      <div class="col-lg-8">
                        {{ isset($arr_property_data['property_details']['no_of_cubicles']) && $arr_property_data['property_details']['no_of_cubicles'] != "" ? $arr_property_data['property_details']['no_of_cubicles'] : '0' }}
                      </div>
                    </div>

                    <div class="form-group col-sm-12">
                      <label class="col-lg-4 control-label">Price per Cubicles:</label>
                      <div class="col-lg-8">
                        {{ isset($arr_property_data['property_details']['cubicles_price']) && $arr_property_data['property_details']['cubicles_price'] != "" ? $arr_property_data['property_details']['currency'].$arr_property_data['property_details']['cubicles_price'] : '0' }}
                      </div>
                    </div>
                  @endif

                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Working Status:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['property_working_status']) && $arr_property_data['property_details']['property_working_status'] != "" ? ucfirst($arr_property_data['property_details']['property_working_status']) : '0' }}
                    </div>
                  </div>
                @else
                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Number of Guest:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['number_of_guest']) && $arr_property_data['property_details']['number_of_guest'] != "" ? $arr_property_data['property_details']['number_of_guest'] : 'NA' }}
                    </div>
                  </div>

                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Number of Bedrooms:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['number_of_bedrooms']) && $arr_property_data['property_details']['number_of_bedrooms'] != "" ? $arr_property_data['property_details']['number_of_bedrooms'] : 'NA' }}
                    </div>
                  </div>

                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Number of Bathrooms:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['number_of_bathrooms']) && $arr_property_data['property_details']['number_of_bathrooms'] != "" ? $arr_property_data['property_details']['number_of_bathrooms'] : 'NA' }}
                    </div>
                  </div>

                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Number of Beds:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['number_of_beds']) && $arr_property_data['property_details']['number_of_beds'] != "" ? $arr_property_data['property_details']['number_of_beds'] : 'NA' }}
                    </div>
                  </div>

                  <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Charges Per Night:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_details']['price_per_night']) && $arr_property_data['property_details']['price_per_night'] != "" ? $arr_property_data['property_details']['currency']."&nbsp;".$arr_property_data['property_details']['price_per_night'] : 'NA' }}
                    </div>
                  </div>
                @endif

                @if(isset($arr_property_data['property_details']['property_aminities']) && count($arr_property_data['property_details']['property_aminities']) > 0)
                    @foreach($arr_property_data['property_details']['property_aminities'] as $aminity)
                      <?php
                          $aminity_slug = trim(str_slug($aminity['aminities']['aminity_name'],'-'));
                          $lable_name   = '';
                          $lable_value  = '';
                          $lable_style  = "display:none;";

                          if ($aminity_slug == 'nearest-railway-station') {
                            $lable_name  = $aminity['aminities']['aminity_name'];
                            $lable_value = $arr_property_data['property_details']['nearest_railway_station'];
                            $lable_style = "display:block;";
                          }
                          if ($aminity_slug == 'nearest-national-highway') {
                            $lable_name  = $aminity['aminities']['aminity_name'];
                            $lable_value = $arr_property_data['property_details']['nearest_national_highway'];
                            $lable_style = "display:block;";
                          }
                          if ($aminity_slug == 'nearest-bus-stop') {
                            $lable_name  = $aminity['aminities']['aminity_name'];
                            $lable_value = $arr_property_data['property_details']['nearest_bus_stop'];
                            $lable_style = "display:block;";
                          }
                          if ($aminity_slug == 'working-hours') {
                            $lable_name  = $aminity['aminities']['aminity_name'];
                            $lable_value = $arr_property_data['property_details']['working_hours'];
                            $lable_style = "display:block;";
                          }
                          if ($aminity_slug == 'working-days') {
                            $lable_name  = $aminity['aminities']['aminity_name'];
                            $lable_value = $arr_property_data['property_details']['working_days'];
                            $lable_style = "display:block;";
                          }
                      ?>

                      @if(isset($lable_value) && $lable_value != '')
                        <div class="form-group col-sm-12">
                          <label class="col-lg-4 control-label">{{ $lable_name }}:</label>
                          <div class="col-lg-8">{{ $lable_value }}</div>
                        </div>
                      @endif
                    @endforeach

                    @if(isset($arr_property_data['property_remark']) &&  $arr_property_data['property_remark'] != '' &&  $arr_property_data['property_remark'] != null)
                    <div class="form-group col-sm-12">
                      <label class="col-lg-4 control-label">Property Remark:</label>
                      <div class="col-lg-8">
                        {{ $arr_property_data['property_remark'] }}
                      </div>
                    </div>
                    @endif

                @endif
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading font-bold"> Property Owner Details</div>
              <div class="panel-body">
                <div class="form-group col-sm-12">
                  <label class="col-lg-4 control-label">Username:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['user_details']['user_name']) && $arr_property_data['user_details']['user_name'] != "" ? $arr_property_data['user_details']['user_name'] : 'NA' }}
                  </div>
                </div>
                <div class="form-group col-sm-12">
                  <label class="col-lg-4 control-label">Full Name:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['user_details']['first_name']) && $arr_property_data['user_details']['first_name'] != "" && isset($arr_property_data['user_details']['last_name']) && $arr_property_data['user_details']['last_name'] != "" ? $arr_property_data['user_details']['first_name'].' '.$arr_property_data['user_details']['last_name'] : 'NA' }}
                  </div>
                </div>
                <div class="form-group col-sm-12">
                  <label class="col-lg-4 control-label">Email ID:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['user_details']['email']) && $arr_property_data['user_details']['email'] != "" ? $arr_property_data['user_details']['email'] : 'NA' }}
                  </div>
                </div>
                <div class="form-group col-sm-12">
                  <label class="col-lg-4 control-label">Mobile Number:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['user_details']['mobile_number']) && $arr_property_data['user_details']['mobile_number'] != "" ? $arr_property_data['user_details']['mobile_number'] : 'NA' }}
                  </div>
                </div>
                <div class="form-group col-sm-12">
                  <label class="col-lg-4 control-label">Address:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['user_details']['address']) && $arr_property_data['user_details']['address'] != "" ? $arr_property_data['user_details']['address'] : 'NA' }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading font-bold"> Property Address</div>
              <div class="panel-body">
                <div class="form-group col-sm-12">
                  <label class="col-lg-4 control-label">City:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['property_details']['city']) && $arr_property_data['property_details']['city'] != "" ? $arr_property_data['property_details']['city'] : 'NA' }}
                  </div>
                </div>    
                <div class="form-group col-sm-12">
                  <label class="col-lg-4 control-label">State:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['property_details']['state']) && $arr_property_data['property_details']['state'] != "" ? $arr_property_data['property_details']['state'] : 'NA' }}
                  </div>
                </div>    
                <div class="form-group col-sm-12">
                  <label class="col-lg-4 control-label">Country:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['property_details']['country']) && $arr_property_data['property_details']['country'] != "" ? $arr_property_data['property_details']['country'] : 'NA' }}
                  </div>
                </div>    
                <div class="form-group col-sm-12">
                  <label class="col-lg-4 control-label">Postal Code:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['property_details']['postal_code']) && $arr_property_data['property_details']['postal_code'] != "" ? $arr_property_data['property_details']['postal_code'] : 'NA' }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          @if(isset($arr_property_data['property_details']['property_rules']) && sizeof($arr_property_data['property_details']['property_rules'])>0 )
            <div class="col-sm-6">
              <div class="panel panel-default">
                <div class="panel-heading font-bold">Property Rules</div>
                <div class="panel-body">   
                  <ul>
                    @if(isset($arr_property_data['property_details']['property_rules']) && sizeof($arr_property_data['property_details']['property_rules'])>0 )
                      @foreach($arr_property_data['property_details']['property_rules'] as $property_rules)
                        <div class="form-group col-sm-12">
                          <li>{{ isset($property_rules['rules']) && $property_rules['rules'] != "" ? ucfirst($property_rules['rules']) : 'NA' }}</li>
                        </div>
                      @endforeach
                    @else
                      <div class="form-group col-sm-12">No Rules for this property</div>
                    @endif
                  </ul>  
                </div>
              </div>
            </div>  
          @endif
        </div>

        <div class="row">
            @if(isset($arr_property_data['property_details']['property_unavailability']) && sizeof($arr_property_data['property_details']['property_unavailability'])>0 )
              <div class="col-sm-6">
                <div class="panel panel-default">
                  <div class="panel-heading font-bold">Property Unavailabilities</div>
                  <div class="panel-body">   
                    <ul class="panel-remove-empty">
                      <div class="form-group"> 
                        <div class="table-responsive" style="border:0">
                          <table class="table table-advance" id="faq_table">
                            <thead>                       
                              <tr>
                                <th>No.</th>                           
                                <th>From Date</th>
                                <th>To Date</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php $key = 0; ?>
                              @if(isset($arr_property_data['property_details']['property_unavailability']) && sizeof($arr_property_data['property_details']['property_unavailability'])>0 )
                                @foreach($arr_property_data['property_details']['property_unavailability'] as $key => $property_unavailability)
                                  <tr>
                                    <td> {{ $key = $key+1 }} </td>
                                    <td>{{ isset($property_unavailability['from_date']) && $property_unavailability['from_date'] != "" ?  get_added_on_date($property_unavailability['from_date']) : '00:00:0000' }}</td>
                                    <td>{{ isset($property_unavailability['to_date']) && $property_unavailability['to_date'] != "" ? get_added_on_date($property_unavailability['to_date']) : '00:00:0000' }}</td>
                                  </tr>
                                @endforeach
                              @else
                                <tr><td colspan="6">No records found.... </td></tr>
                              @endif
                            </tbody>
                          </table> 
                        </div>  
                      </div>
                    </ul>  
                  </div>
                </div>
              </div>         
            @endif
            
            @if(isset($arr_property_data['property_details']['property_aminities']) && sizeof($arr_property_data['property_details']['property_aminities'])>0 )
              <div class="col-sm-6">
                <div class="panel panel-default">
                  <div class="panel-heading font-bold">Property Amenities</div>
                  <div class="panel-body">
                    <ul>
                      @if(isset($arr_property_data['property_details']['property_aminities']) && sizeof($arr_property_data['property_details']['property_aminities'])>0 )
                        @foreach($arr_property_data['property_details']['property_aminities'] as $property_aminities)
                          <div class="form-group col-sm-12">
                            <li>{{ isset($property_aminities['aminities']['aminity_name']) && $property_aminities['aminities']['aminity_name'] != "" ? ucwords($property_aminities['aminities']['aminity_name']) : 'NA' }}</li>
                          </div>
                        @endforeach
                      @else
                        <div class="form-group col-sm-12">No Amenitites for this property</div>
                      @endif
                    </ul>
                  </div>
                </div>
              </div>
            @endif

        </div>

        <div class="row">
          @if(isset($arr_property_data['property_details']['property_bed_arrangment']) && sizeof($arr_property_data['property_details']['property_bed_arrangment'])>0 )
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading font-bold">Property Bed Arrangements</div>
              <div class="panel-body">   
                <ul class="panel-remove-empty">
                  <div class="form-group"> 
                    <div class="table-responsive" style="border:0">
                      <table class="table table-advance" id="faq_table">
                        <thead>                       
                          <tr>
                            <th>No.</th>
                            <th>Double Bed</th>
                            <th>Single Bed</th>
                            <th>Queen Bed</th>
                            <th>Sofa Bed</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $key = 0; ?>
                          @if(isset($arr_property_data['property_details']['property_bed_arrangment']) && sizeof($arr_property_data['property_details']['property_bed_arrangment']) > 0 )

                            @foreach($arr_property_data['property_details']['property_bed_arrangment'] as $key => $property_unavailability)
                              <?php
                                $double_bed = isset($property_unavailability['double_bed']) && $property_unavailability['double_bed'] != "" ? ucfirst(get_sleeping_arrangment_name($property_unavailability['double_bed'])) : '0';
                                $single_bed = isset($property_unavailability['single_bed']) && $property_unavailability['single_bed'] != "" ? ucfirst(get_sleeping_arrangment_name($property_unavailability['single_bed'])) : '0';
                                $queen_bed = isset($property_unavailability['queen_bed']) && $property_unavailability['queen_bed'] != "" ? ucfirst(get_sleeping_arrangment_name($property_unavailability['queen_bed'])) : '0';
                                $sofa_bed = isset($property_unavailability['sofa_bed']) && $property_unavailability['sofa_bed'] != "" ? ucfirst(get_sleeping_arrangment_name($property_unavailability['sofa_bed'])) : '0';
                              ?>
                              <tr>
                                  <td>{{ $key = $key+1 }}</td>
                                  <td><span class="badge badge-info">{{ $double_bed }}</span></td>
                                  <td><span class="badge badge-info">{{ $single_bed }}</span></td>
                                  <td><span class="badge badge-info">{{ $queen_bed }}</span></td>
                                  <td><span class="badge badge-info">{{ $sofa_bed }}</span></td>
                              </tr>
                            @endforeach

                          @else
                            <tr> <td colspan="6">No records found.... </td></tr>
                          @endif
                        </tbody>
                      </table> 
                    </div>  
                  </div>
                </ul>  
              </div>
            </div>
          </div>
          @endif
        </div>

        <div class="row">
          <div class="col-sm-12">
            <div class="panel panel-default">
              <div class="panel-heading font-bold">Property Images</div>
              <div class="panel-body">
                <div class="form-group img-height">
                  
                  @if(isset($arr_property_data['property_details']['property_images']) && sizeof($arr_property_data['property_details']['property_images'])>0 )
                    @foreach($arr_property_data['property_details']['property_images'] as $property_images)
                      <div class="thumbnail">
                        @if(isset($property_images['image']) && $property_images['image']!="" && file_exists($property_image_base_path.$property_images['image']))
                          <img src="{{ $property_image_public_path.$property_images['image'] }}" class="img-responsive">
                        @else
                          <img src="{{ url('/uploads').'/default-profile.png' }}" class="img-responsive">
                        @endif
                      </div>
                    @endforeach
                  @else
                    <div class="thumbnail">
                      <img src="{{ url('/uploads').'/default-profile.png' }}" class="img-responsive">
                    </div>
                  @endif

                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="form-group ">
            <div class="col-sm-9 col-sm-offset-0 col-lg-10 col-lg-offset-0">
              <a href="{{ URL::previous() }}" class="btn">Back</a>
            </div>
          </div>
        </div>

      </div>  
      @endif
    </div>
  </div>

<script type="text/javascript">
  $(document).ready(function() { 
    $('#validate_form').validate();
  });
</script>
@stop