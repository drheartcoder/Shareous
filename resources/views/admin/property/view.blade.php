@extends('admin.layout.master')

<style>
    .control-label{font-weight: 600;}
</style>

@section('main_content')

{{-- {{ dd($page_title) }} --}}


<!-- BEGIN Page Title -->
<div class="page-title">
  <div>
    <h1>
      <i class="fa fa-eye"></i>
      {{ isset($page_title)?$page_title:"" }}
    </h1>
  </div>
</div>
<!-- <link rel="stylesheet" type="text/css" href="{{ url('/') }}/admin/css/app.css" /> -->
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
      <i class="fa {{$module_icon}}"></i>
      <!-- <a href="{{ $module_url_path}}/all">Property</a> -->
      <a href="{{ url()->previous() }}">Property</a>
    </span> 
    <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa fa-eye"></i>
    </span>
    <li class="active">{{ $page_title or ''}}</li>
  </ul>
</div>
<!-- END Breadcrumb -->
<!-- BEGIN Main Content -->
<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-title">
        <h3>
          <i class="fa {{$module_icon}}"></i>
          {{ isset($page_title)?$page_title:"" }}
        </h3>
      </div>
      <div class="box-content">
        @include('admin.layout._operation_status')
        
        @if(isset($status) && $status !="" )

        <div class="row">
          <div class="col-sm-12">
            <form name="validation-form" id="validate_form" method="POST" class="form-horizontal" action="{{$module_url_path}}/change_status/{{$id}}/{{$status}}/comment" enctype="multipart/form-data" files ="true">
              {{csrf_field()}}

              <div class="form-group col-lg-11">
                <label class="col-sm-4 col-lg-3 control-label" for="page_title" style="text-align: right;">Reason For Rejection <i class="red">*</i> </label>
                <div class="col-sm-8 col-lg-5 controls">
                  <textarea name="reject_comment" id="reject_comment" rows="4" data-rule-maxlength="255" class="form-control" data-rule-required="true" placeholder="Enter Reason for rejection">{{old('reject_comment')}}</textarea>
                  <span class='error help-block'>{{ $errors->first('reject_comment') }}</span>
                </div>          
              </div>              
              <div class="form-group col-lg-11">
                <div class="col-sm-6 col-sm-offset-3 col-lg-6 col-lg-offset-3">
                  <input type="submit" value="Save" class="btn btn btn-primary btn-custom">
                  <a href="{{ $module_url_path.'/all'}}" type="button" class="btn btn-cancel">Cancel</a>
                </div>
              </div>
            </form> 
            </div>
          </div>
        </div>                
        
        @endif
        <?php 
          $proprty_type_slug = get_property_type_slug($arr_property_data['property_type_id']);
        ?>
        @if(isset($arr_property_data) && sizeof($arr_property_data)>0)
        <div class="row">
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading font-bold">Property Details  @if($arr_property_data['admin_status']==1) <span class="badge badge-large badge-info pending">Pending</span> @elseif($arr_property_data['admin_status']==2)<span class="badge badge-large badge-success confirm">Confirmed</span>  @elseif($arr_property_data['admin_status']==3)<span class="badge badge-large badge-warning reject">Rejected</span>  @elseif($arr_property_data['admin_status']==4)<span class="badge badge-large badge-important perm-reject">Perm. Rejected</span>  @endif</div>
              <div class="panel-body">
                <div class="form-group ">
                  <label class="col-lg-4 control-label">Name:</label>
                  <div class="col-lg-8">
                    {{isset($arr_property_data['property_name']) && $arr_property_data['property_name']!=""?$arr_property_data['property_name']:'NA'}}
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="form-group ">
                  <label class="col-lg-4 control-label">Propery Type:</label>
                  <div class="col-lg-8">
                    {{isset($arr_property_data['property_type']['name']) && $arr_property_data['property_type']['name']!=""?$arr_property_data['property_type']['name']:'NA'}}
                  </div>
                   <div class="clearfix"></div>
                </div> 
                <div class="form-group ">
                  <label class="col-lg-4 control-label">Description:</label>
                  <div class="col-lg-8">
                    {{isset($arr_property_data['description']) && $arr_property_data['description']!=""?$arr_property_data['description']:'NA'}}
                  </div>
                   <div class="clearfix"></div>
                </div>    
                @if($proprty_type_slug == 'warehouse')
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Total Area:</label>
                    <div class="col-lg-8">
                      {{isset($arr_property_data['property_area']) && $arr_property_data['property_area']!=""?$arr_property_data['property_area']:'0'}}Sq.Ft
                    </div> <div class="clearfix"></div>
                  </div>
                  
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Total Build Area:</label>
                    <div class="col-lg-8">
                      {{isset($arr_property_data['total_build_area']) && $arr_property_data['total_build_area']!=""?$arr_property_data['total_build_area']:'0'}}Sq.Ft
                    </div> <div class="clearfix"></div>
                  </div>
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Admin Area:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['admin_area']) && $arr_property_data['admin_area'] !="" ? $arr_property_data['admin_area'] : '0' }}Sq.Ft
                    </div> <div class="clearfix"></div>
                  </div>
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Price:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['price_per_sqft']) && $arr_property_data['price_per_sqft'] != "" ? $arr_property_data['currency'].$arr_property_data['price_per_sqft'] : '0' }}
                    </div> <div class="clearfix"></div>
                  </div>
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">No. Of Slots:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['no_of_slots']) && $arr_property_data['no_of_slots'] != "" ? $arr_property_data['no_of_slots'] : '0' }}
                    </div> <div class="clearfix"></div>
                  </div>
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Available No. Of Slots:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['available_no_of_slots']) && $arr_property_data['available_no_of_slots'] != "" ? $arr_property_data['available_no_of_slots'] : '0' }}
                    </div> <div class="clearfix"></div>
                  </div>
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Working Status:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['property_working_status']) && $arr_property_data['property_working_status'] != "" ? $arr_property_data['property_working_status'] : '-' }}
                    </div> <div class="clearfix"></div>
                  </div>
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Build Type:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['build_type']) && $arr_property_data['build_type'] != "" ? $arr_property_data['build_type'] : '-' }}
                    </div> <div class="clearfix"></div>
                  </div>
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Good Storage:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['good_storage']) && $arr_property_data['good_storage'] != "" ? $arr_property_data['good_storage'] : '-' }}
                    </div> <div class="clearfix"></div>
                  </div>
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Custom Type:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['custom_type']) && $arr_property_data['custom_type'] != "" ? $arr_property_data['custom_type'] : '-' }}
                    </div> <div class="clearfix"></div>
                  </div>
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Prperty Management:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['management']) && $arr_property_data['management'] != "" ? $arr_property_data['management'] : '-' }}
                    </div> <div class="clearfix"></div>
                  </div>
                @elseif($proprty_type_slug == 'office-space')
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Total Area:</label>
                    <div class="col-lg-8">
                      {{isset($arr_property_data['property_area']) && $arr_property_data['property_area']!=""?$arr_property_data['property_area']:'0'}}Sq.Ft
                    </div> <div class="clearfix"></div>
                  </div>
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Total Build Area:</label>
                    <div class="col-lg-8">
                      {{isset($arr_property_data['total_build_area']) && $arr_property_data['total_build_area']!=""?$arr_property_data['total_build_area']:'0'}}Sq.Ft
                    </div> <div class="clearfix"></div>
                  </div>
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Admin Area:</label>
                    <div class="col-lg-8">
                      {{isset($arr_property_data['admin_area']) && $arr_property_data['admin_area']!=""?$arr_property_data['admin_area']:'0'}}Sq.Ft
                    </div> <div class="clearfix"></div>
                  </div>
                  
                  <?php
                    $room     = isset( $arr_property_data['room'] ) ? $arr_property_data['room'] : '';
                    $desk     = isset( $arr_property_data['desk'] ) ? $arr_property_data['desk'] : '';
                    $cubicles = isset( $arr_property_data['cubicles'] ) ? $arr_property_data['cubicles'] : '';

                    $no_of_room     = isset( $arr_property_data['no_of_room'] ) ? $arr_property_data['no_of_room'] : '';
                    $no_of_desk     = isset( $arr_property_data['no_of_desk'] ) ? $arr_property_data['no_of_desk'] : '';
                    $no_of_cubicles = isset( $arr_property_data['no_of_cubicles'] ) ? $arr_property_data['no_of_cubicles'] : '';

                    $room_price     = isset( $arr_property_data['room_price'] ) ? $arr_property_data['room_price'] : '0';
                    $desk_price     = isset( $arr_property_data['desk_price'] ) ? $arr_property_data['desk_price'] : '0';
                    $cubicles_price = isset( $arr_property_data['cubicles_price'] ) ? $arr_property_data['cubicles_price'] : '0';
                  ?>

                  @if( $room == 'on' )
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">No. Of Private Room:</label>
                    <div class="col-lg-8">
                      {{ $no_of_room }}
                    </div> <div class="clearfix"></div>
                  </div>

                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Price per Room:</label>
                    <div class="col-lg-8">
                      {{ $arr_property_data['currency'].$room_price }}
                    </div> <div class="clearfix"></div>
                  </div>
                  @endif

                  @if( $desk == 'on' )
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">No. Of Dedicated Desk:</label>
                    <div class="col-lg-8">
                      {{ $no_of_desk }}
                    </div> <div class="clearfix"></div>
                  </div>

                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Price per Dedicated Desk:</label>
                    <div class="col-lg-8">
                      {{ $arr_property_data['currency'].$desk_price }}
                    </div> <div class="clearfix"></div>
                  </div>
                  @endif

                  @if( $cubicles == 'on' )
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">No. Of Cubicles:</label>
                    <div class="col-lg-8">
                      {{ $no_of_cubicles }}
                    </div> <div class="clearfix"></div>
                  </div>

                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Price per Cubicles:</label>
                    <div class="col-lg-8">
                      {{ $arr_property_data['currency'].$cubicles_price }}
                    </div> <div class="clearfix"></div>
                  </div>
                  @endif
                  
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Working Status:</label>
                    <div class="col-lg-8">
                      {{isset($arr_property_data['property_working_status']) && $arr_property_data['property_working_status']!=""?ucfirst($arr_property_data['property_working_status']):'0'}}
                    </div> <div class="clearfix"></div>
                  </div>
                @else
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Number of Guest:</label>
                    <div class="col-lg-8">
                      {{isset($arr_property_data['number_of_guest']) && $arr_property_data['number_of_guest']!=""?$arr_property_data['number_of_guest']:'NA'}}
                    </div> <div class="clearfix"></div>
                  </div>    
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Number of Bedrooms:</label>
                    <div class="col-lg-8">
                      {{isset($arr_property_data['number_of_bedrooms']) && $arr_property_data['number_of_bedrooms']!=""?$arr_property_data['number_of_bedrooms']:'NA'}}
                    </div> <div class="clearfix"></div>
                  </div>    
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Number of Bathrooms:</label>
                    <div class="col-lg-8">
                      {{isset($arr_property_data['number_of_bathrooms']) && $arr_property_data['number_of_bathrooms']!=""?$arr_property_data['number_of_bathrooms']:'NA'}}
                    </div> <div class="clearfix"></div>
                  </div>    
                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Number of Beds:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['number_of_beds']) && $arr_property_data['number_of_beds'] != "" ? $arr_property_data['number_of_beds'] : 'NA' }}
                    </div> <div class="clearfix"></div>
                  </div>    

                  <div class="form-group ">
                    <label class="col-lg-4 control-label">Charges Per Night:</label>
                    <div class="col-lg-8">
                      {{ isset($arr_property_data['price_per_night']) && $arr_property_data['price_per_night'] != "" ? $arr_property_data['currency']."&nbsp;".$arr_property_data['price_per_night'] : 'NA' }}
                    </div> <div class="clearfix"></div>
                  </div>   
                @endif
                 @if(isset($arr_property_data['property_aminities']) && count($arr_property_data['property_aminities'])>0)
                        @foreach($arr_property_data['property_aminities'] as $aminity)
                            <?php

                                $aminity_slug = trim(str_slug($aminity['aminities']['aminity_name'],'-'));
                                $lable_name   = '';
                                $lable_value  = '';
                                $lable_style  = "display:none;";
                                if ($aminity_slug == 'nearest-railway-station') 
                                {
                                    $lable_name   = $aminity['aminities']['aminity_name'];
                                    $lable_value  = $arr_property_data['nearest_railway_station'];
                                    $lable_style  = "display:block;";
                                }
                                if ($aminity_slug == 'nearest-national-highway') 
                                {
                                    $lable_name   = $aminity['aminities']['aminity_name'];
                                    $lable_value  = $arr_property_data['nearest_national_highway'];
                                    $lable_style  = "display:block;";
                                }
                                if ($aminity_slug == 'nearest-bus-stop') 
                                {
                                    $lable_name   = $aminity['aminities']['aminity_name'];
                                    $lable_value  = $arr_property_data['nearest_bus_stop'];
                                    $lable_style  = "display:block;";
                                }
                                if ($aminity_slug == 'working-hours') 
                                {
                                    $lable_name   =  $aminity['aminities']['aminity_name'];
                                    $lable_value  =  $arr_property_data['working_hours'];
                                    $lable_style  =  "display:block;";
                                }
                                if ($aminity_slug == 'working-days') 
                                {
                                    $lable_name   =  $aminity['aminities']['aminity_name'];
                                    $lable_value  =  $arr_property_data['working_days'];
                                    $lable_style  =  "display:block;";
                                }
                            ?>
                            @if(isset($lable_value) && $lable_value != '')
                            <div class="form-group ">
                              <label class="col-lg-4 control-label">{{$lable_name}}:</label>
                              <div class="col-lg-8">
                                {{$lable_value}}
                              </div> <div class="clearfix"></div>
                            </div>   
                            @endif
                        @endforeach
                         @if(isset($arr_property_data['property_remark']) &&  $arr_property_data['property_remark'] != '' &&  $arr_property_data['property_remark'] != null)
                        <div class="form-group ">
                          <label class="col-lg-4 control-label">Property Remark:</label>
                          <div class="col-lg-8">
                            {{ $arr_property_data['property_remark'] }}
                          </div> <div class="clearfix"></div>
                        </div>   
                        @endif
                  @endif

                @if($arr_property_data['admin_status']==3 || $arr_property_data['admin_status']==4)
                <div class="form-group ">
                  <label class="col-lg-4 control-label">Admin Rejected Reason:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['admin_comment']) && $arr_property_data['admin_comment'] != "" ? $arr_property_data['admin_comment'] : 'NA' }}
                  </div> <div class="clearfix"></div>
                </div> 
                @endif 
                   
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading font-bold"> Property Owner Details</div>
              <div class="panel-body">
                <div class="form-group ">
                  <label class="col-lg-4 control-label">Username:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['user_details']['user_name']) && $arr_property_data['user_details']['user_name'] != "" ? $arr_property_data['user_details']['user_name'] : 'NA' }}
                  </div> <div class="clearfix"></div>
                </div>    
                <div class="form-group ">
                  <label class="col-lg-4 control-label">Full Name:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['user_details']['first_name']) && $arr_property_data['user_details']['first_name'] != "" && isset($arr_property_data['user_details']['last_name']) && $arr_property_data['user_details']['last_name'] != "" ? $arr_property_data['user_details']['first_name'].' '.$arr_property_data['user_details']['last_name'] : 'NA' }}
                  </div> <div class="clearfix"></div>
                </div>    
                <div class="form-group ">
                  <label class="col-lg-4 control-label">Email ID:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['user_details']['email']) && $arr_property_data['user_details']['email'] != "" ? $arr_property_data['user_details']['email'] : 'NA' }}
                  </div> <div class="clearfix"></div>
                </div>    
                <div class="form-group ">
                  <label class="col-lg-4 control-label">Mobile Number:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['user_details']['mobile_number']) && $arr_property_data['user_details']['mobile_number'] != "" ? $arr_property_data['user_details']['mobile_number'] : 'NA' }}
                  </div> <div class="clearfix"></div>
                </div>  
                <div class="form-group ">
                  <label class="col-lg-4 control-label">Address:</label>
                  <div class="col-lg-8">
                    {{ isset($arr_property_data['user_details']['address']) && $arr_property_data['user_details']['address'] != "" ? $arr_property_data['user_details']['address'] : 'NA' }}
                  </div> <div class="clearfix"></div>
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
                 <div class="form-group ">
                  <label class="col-lg-4 control-label">Address:</label>
                  <div class="col-lg-8">
                    {{isset($arr_property_data['address']) && $arr_property_data['address']!=""?$arr_property_data['address']:'NA'}}
                  </div> <div class="clearfix"></div>
                </div>   
                <div class="form-group ">
                  <label class="col-lg-4 control-label">City:</label>
                  <div class="col-lg-8">
                    {{isset($arr_property_data['city']) && $arr_property_data['city']!=""?$arr_property_data['city']:'NA'}}
                  </div> <div class="clearfix"></div>
                </div>    
                <div class="form-group ">
                  <label class="col-lg-4 control-label">State:</label>
                  <div class="col-lg-8">
                    {{isset($arr_property_data['state']) && $arr_property_data['state']!=""?$arr_property_data['state']:'NA'}}
                  </div> <div class="clearfix"></div>
                </div>    
                <div class="form-group ">
                  <label class="col-lg-4 control-label">Country:</label>
                  <div class="col-lg-8">
                    {{isset($arr_property_data['country']) && $arr_property_data['country']!=""?$arr_property_data['country']:'NA'}}
                  </div> <div class="clearfix"></div>
                </div>    
                <div class="form-group ">
                  <label class="col-lg-4 control-label">Postal Code:</label>
                  <div class="col-lg-8">
                    {{isset($arr_property_data['postal_code']) && $arr_property_data['postal_code']!=""?$arr_property_data['postal_code']:'NA'}}
                  </div> <div class="clearfix"></div>
                </div>                             
              </div>
            </div>
          </div> 
           @if(isset($arr_property_data['property_rules']) && sizeof($arr_property_data['property_rules'])>0 )
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading font-bold">Property Rules</div>
              <div class="panel-body">   
                <ul>
                  @if(isset($arr_property_data['property_rules']) && sizeof($arr_property_data['property_rules'])>0 )
                  @foreach($arr_property_data['property_rules'] as $property_rules)                              
                  <div class="form-group col-sm-12">                                   
                    <li>{{ isset($property_rules['rules']) && $property_rules['rules'] != "" ? $property_rules['rules'] : 'NA' }}</li>
                  </div>
                  @endforeach                               
                  @else
                  <div class="form-group col-sm-12">
                    No Rules for this property
                  </div>
                  @endif
                </ul>  
              </div>
            </div>
          </div> 
          @endif 
        </div>
        <div class="row">
          @if(isset($arr_property_data['property_unavailability']) && sizeof($arr_property_data['property_unavailability'])>0 )
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
                        <?php $key = 0;  ?>
                          @if(isset($arr_property_data['property_unavailability']) && sizeof($arr_property_data['property_unavailability'])>0 )
                          @foreach($arr_property_data['property_unavailability'] as $key => $property_unavailability)                              
                          <tr>
                            <td> {{ $key = $key+1 }} </td>                            
                            <td>{{ isset($property_unavailability['from_date']) && $property_unavailability['from_date'] != "" ?  get_added_on_date($property_unavailability['from_date']) : '00:00:0000' }}</td>
                            <td>{{ isset($property_unavailability['to_date']) && $property_unavailability['to_date'] != "" ?  get_added_on_date($property_unavailability['to_date']) : '00:00:0000' }}</td>
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
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading font-bold">Property Amenities</div>
              <div class="panel-body">   
                <ul>
                  @if(isset($arr_property_data['property_aminities']) && sizeof($arr_property_data['property_aminities'])>0 )
                  @foreach($arr_property_data['property_aminities'] as $property_aminities)                                           
                  <div class="form-group col-sm-12">                                    
                    <li>{{ isset($property_aminities['aminities']['aminity_name']) && $property_aminities['aminities']['aminity_name'] != "" ? $property_aminities['aminities']['aminity_name'] : 'NA' }}</li>
                  </div>
                  @endforeach                               
                  @else
                  <div class="form-group col-sm-12">
                    No Amenitites for this property
                  </div>
                  @endif
                </ul>  
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          @if(isset($arr_property_data['property_bed_arrangment']) && sizeof($arr_property_data['property_bed_arrangment'])>0 )
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
                          @if(isset($arr_property_data['property_bed_arrangment']) && sizeof($arr_property_data['property_bed_arrangment'])>0 )
                          @foreach($arr_property_data['property_bed_arrangment'] as $key => $property_unavailability)   

                          <?php
                            $double_bed = isset($property_unavailability['double_bed']) && $property_unavailability['double_bed'] != ""? ucfirst(get_sleeping_arrangment_name($property_unavailability['double_bed'])) : '0';
                            $single_bed = isset($property_unavailability['single_bed']) && $property_unavailability['single_bed'] != ""? ucfirst(get_sleeping_arrangment_name($property_unavailability['single_bed'])) : '0';
                            $queen_bed = isset($property_unavailability['queen_bed']) && $property_unavailability['queen_bed'] != ""? ucfirst(get_sleeping_arrangment_name($property_unavailability['queen_bed'])) : '0';
                            $sofa_bed = isset($property_unavailability['sofa_bed']) && $property_unavailability['sofa_bed'] != ""? ucfirst(get_sleeping_arrangment_name($property_unavailability['sofa_bed'])) : '0';
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
          <!-- <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading font-bold"> SEO Meta Details</div>
              <div class="panel-body">
                <div class="form-group col-sm-12">
                  <label class="col-lg-4 control-label">Title:</label>
                  <div class="col-lg-8">
                    {{isset($arr_property_data['meta_title']) && $arr_property_data['meta_title'] != "" ? $arr_property_data['meta_title'] : 'NA'}}
                  </div>
                </div>
                <div class="form-group col-sm-12">
                  <label class="col-lg-4 control-label">Keyword:</label>
                  <div class="col-lg-8">
                    {{isset($arr_property_data['meta_keyword']) && $arr_property_data['meta_keyword'] != "" ? $arr_property_data['meta_keyword'] : 'NA'}}
                  </div>
                </div>
                <div class="form-group col-sm-12">
                  <label class="col-lg-4 control-label">Description:</label>
                  <div class="col-lg-8">
                    {{isset($arr_property_data['meta_description']) && $arr_property_data['meta_description'] != "" ? $arr_property_data['meta_description'] : 'NA'}}
                  </div>
                </div>                             
              </div>
            </div>
          </div> --> 
        </div>
        <div class="row">
           <div class="col-sm-12">
            <div class="panel panel-default">
              <div class="panel-heading font-bold">Property Images</div>
              <div class="panel-body">
                <div class="form-group img-height">
                  @if(isset($arr_property_data['property_images']) && sizeof($arr_property_data['property_images'])>0 )
                  @foreach($arr_property_data['property_images'] as $property_images)
                   <div class="thumbnail">
                      @if(isset($property_images['image']) && $property_images['image']!="" && file_exists($property_image_base_path.$property_images['image']))
                      <img src="{{$property_image_public_path.$property_images['image'] }}" class="img-responsive">
                      @else
                      <img src="{{url('/uploads').'/default-profile.png' }}" class="img-responsive">
                      @endif
                    </div>
                 @endforeach
                  @else
                 <div class="thumbnail">
                      <img src="{{url('/uploads').'/default-profile.png' }}" class="img-responsive">                                        
                    </div>
                  @endif       
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
        <div class="form-group ">
          <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2" style="margin-left: 0;">
            <?php 
              if($arr_property_data['admin_status'] == 1)
              {
                $module_url_path = isset($module_url_path) && $module_url_path != '' ? $module_url_path : url('/admin/property');
                $enc_property_id = isset($enc_property_id) && $enc_property_id != '' ? $enc_property_id : '0';

                $confirm_href     = $module_url_path.'/change_status/'.$enc_property_id.'/2';
                $reject_href      = $module_url_path.'/change_status/'.$enc_property_id.'/3';
                $reject_perm_href = $module_url_path.'/change_status/'.$enc_property_id.'/4';

                $confirm_button     = '<a class="btn btn-sm btn-success show-tooltip" title="Confirm" href="'.$confirm_href.'" onclick="return confirm_action(this,event,\'Do you really want to confirm this property ?\')" >Confirm</a>';
                $reject_button      = '<a class="btn btn-sm btn-warning show-tooltip" title="Reject" href="'.$reject_href.'" onclick="return confirm_action(this,event,\'Do you really want to reject this property ?\')" >Reject</a>';
                $reject_perm_button = '<a class="btn btn-sm btn-danger show-tooltip" title="Permanant Reject" href="'.$reject_perm_href.'" onclick="return confirm_action(this,event,\'Do you really want to permanant reject this property ?\')" >Permanant Reject</a>';

                echo $confirm_button.' '.$reject_button.' '.$reject_perm_button;
              }
            ?>
            <a href="{{ URL::previous() }}" class="btn" ">Back</a>
          </div>
        </div>
        </div>
      </div>  
      @endif
    </div>
  </div>

<script type="text/javascript">
  $(document).ready(function() { $('#validate_form').validate(); });
</script>
@stop
