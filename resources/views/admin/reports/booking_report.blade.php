@extends('admin.layout.master')
<style>
    .form-group input{width: 100% !important;}
    .form-group label{display: block;}
    .form-group .form-control{width: 100%;}
    .form-group  .btn-primary.same_search{ width: auto !important;}
    .marn-tp{margin-top: 24px;}
    .form-group .chosen-container{width: 100% !important;}
    .form-group .control-label{margin-bottom: 5px;}
</style>
@section('main_content')
<!-- BEGIN Content -->

<div id="main-content">
  <!-- BEGIN Page Title -->
  <div class="page-title">
    <div>
      <h1><i class="fa {{$module_icon}}"></i> {{$page_title or ''}}</h1>
      <!-- <h4>Overview, stats, chat and more</h4> -->
    </div>
  </div>
  <!-- END Page Title -->

	<?php 
		$property_name      = Request::input('property_name');
		$property_owner_id  = Request::input('property_owner_id');
		$property_booked_id = Request::input('property_booked_id');
		$booking_date       = Request::input('booking_date');
	?>
  
  <!-- BEGIN Breadcrumb -->
  <div id="breadcrumbs">
    <ul class="breadcrumb">
      <li>
        <i class="fa fa-home"></i>
        <a href="{{url($admin_panel_slug.'/dashboard')}}">Dashboard</a>
        <span class="divider"><i class="fa fa-angle-right"></i></span>
      </li>

      <?php if(isset($user_id) && !empty($user_id)){?>
      <li>
        <i class="fa {{ $guest_user_icon }}"></i>
        <a href="{{$guest_user_path}}">Guest</a>
        <span class="divider"><i class="fa fa-angle-right"></i></span>
      </li>
      <?php } ?>

       <?php if(isset($host_user_id) && !empty($host_user_id)){?>
      <li>
        <i class="fa {{ $host_user_icon }}"></i>
        <a href="{{$host_user_path}}">Host</a>
        <span class="divider"><i class="fa fa-angle-right"></i></span>
      </li>
      <?php } ?>

      <li class="active"> <i class="fa {{$module_icon}}"></i>  {{$page_title or ''}}</li>
    </ul>
  </div>

  <!-- END Breadcrumb -->
  


  <!-- BEGIN Tiles -->
  <div class="row">
    <div class="col-md-12">
      <div class="box {{theme_color()}}">
        <div class="box-title">
          <h3><i class="fa {{$module_icon}}"></i> {{$page_title or ''}}</h3>
        </div>
        <div class="box-content">   

         <form name="frm-manage" id="frm-manage" method="get" action="{{$module_url_path}}"  class="form-inline" >

               <div class="form-group col-md-3">
                  <label>Property Name :</label>
                     <select class="form-control chosen-with-diselect" tabindex="-1" id="property_name" name="property_name">
                        <option value="">Select Property Name</option>
                          @if(isset($arr_property) && count($arr_property)>0)
                            @foreach($arr_property as $obj_property)
                             <option value="{{ base64_encode($obj_property->id) }}" {{ ( Request::get('property_name') != '' && Request::get('property_name') == base64_encode($obj_property->id)) ? "selected" : '' }} > {{ $obj_property->property_name}} 
                              </option>
                             @endforeach
                          @endif
                     </select>
               </div>

               <div class="form-group col-md-3">
                  <label>Property Owner Name :</label>
                     <select class="form-control chosen-with-diselect" tabindex="-1" id="property_owner_id" name="property_owner_id">
                        <option value="">Select Owner Name</option>
                          @if(isset($arr_owner) && count($arr_owner)>0)
                            @foreach($arr_owner as $obj_owner)
                             <option value="{{ base64_encode($obj_owner->id) }}" {{ ( Request::get('property_owner_id') != '' && Request::get('property_owner_id') == base64_encode($obj_owner->id)) ? "selected" : '' }} > {{ $obj_owner->owner_firstname."&nbsp;".$obj_owner->owner_lastname}} 
                              </option>
                             @endforeach
                          @endif
                     </select>
               </div>

               <div class="form-group col-md-3">
                  <label> Booked By:</label>
                     <select class="form-control chosen-with-diselect" tabindex="-1" id="property_booked_id" name="property_booked_id">
                        <option value="">Select Booked By</option>
                          @if(isset($arr_booked_by) && count($arr_booked_by)>0)
                            @foreach($arr_booked_by as $obj_booked_by)
                             <option value="{{ base64_encode($obj_booked_by->id) }}" {{ ( Request::get('property_booked_id') != '' && Request::get('property_booked_id') == base64_encode($obj_booked_by->id)) ? "selected" : '' }} > {{ $obj_booked_by->booked_by_firstname."&nbsp;".$obj_booked_by->booked_by_lastname}} 
                              </option>
                             @endforeach
                          @endif
                     </select>
               </div>

                <div class="form-group col-md-3" style="margin-top:6px;">
                    <label>Booking Date<i class="red">*</i></label>
                    <input type="text" id="booking_date"  data-rule-required="true" placeholder="Booking Date" class="date-picker form-control" name="booking_date" value="{{isset($booking_date) && $booking_date!=''?$booking_date:''}}">
                    <span class="help-block" id="err_booking_date">{{ $errors->first('booking_date')}}</span>  
                </div>
                 

              <div class="form-group" >
                <input id="submit_filter" class="btn btn-primary same_search" type="submit" value="Search" name="btn_search"> &nbsp;
                <a href="{{$module_url_path}}" class="btn btn-default">Reset </a>
              </div>

              <input type="hidden" name="htoken" value="{{ csrf_token() }}">
              <div class="clearfix"></div>
          </form>


        <form name="export" id="frm-export"  action="{{$module_url_path}}/export" method="get">

              <input type="hidden" name="e_property_name" id="e_property_name" value="">  
              <input type="hidden" name="e_property_owner_id" id="e_property_owner_id" value=""> 
              <input type="hidden" name="e_property_booked_id" id="e_property_booked_id" value=""> 
              <input type="hidden" name="e_booking_date" id="e_booking_date" value=""> 
              <input type="hidden" name="records" id="records">
             <br>
              &nbsp;&nbsp;&nbsp;<div class="btn-group">
                 <button type="button" class="btn btn-default show-tooltip" id="getVal" title="Export"  onclick="getData()"
               style="text-decoration:none;background-color: #0090ff; color: rgba(255,255,255,0.9);">Export Report
                </button>
              </div>
        </form>
      
          <br />
          @include('admin.layout._operation_status')
         
          <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{$module_url_path}}/multi_action">
            {{ csrf_field() }}
          
            <div class="btn-toolbar pull-right">
              <div class="btn-group"> 

                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Refresh" onclick="window.location.reload()" style="text-decoration:none;">
                  <i class="fa fa-repeat"></i>
                </a> 
                
              </div>
            </div>
             <br/><br/>
            <div class="clearfix"></div>
            <input type="hidden" name="multi_action" value="" />
            <div class="table-responsive" style="border:0">              
              <table id="myTable" class="table table-advance">
                <thead>
                  <tr>
                    <th style="width:18px"> 
                      <input type="checkbox"  class="checked-all" name="mult_change" id="mult_change" value="delete" />
                    </th>
                    <th>Property Name</th>
                    <th>Owner Name</th>
                    <th>Booked By</th>
                    <th>Booking Info</th>
                    <th>Amount</th>
                    <th>Status</th>
                  </tr>                   
                  </tr>
                </thead>
                <tbody>             
                </tbody>
              </table>
            </div>

          </form>
        </div>
      </div>
    </div>


    <!-- END Tiles -->
<script type="text/javascript">

    var property_name       = $('#property_name').val();
    var property_owner_id   = $('#property_owner_id').val();
    var property_booked_id  = $('#property_booked_id').val();
    var booking_date        = $('#booking_date').val();

    var module_url_path     = "{{url($module_url_path)}}";
    var temp_url            = module_url_path+'/load_booking_data';
    var url                 = temp_url.replace(/&amp;/g, '&'); 
    
    table_module            = $('#myTable').DataTable({

          "processing": true,
          "serverSide": true,
          "paging"    : true,
          "searching" : false,
          "ordering"  : true,
          "destroy"   : true,

      ajax: 
      {
        'url'       : temp_url,
       'data'       :  {'property_owner_id':property_owner_id,'property_booked_id':property_booked_id,'property_name':property_name,'booking_date':booking_date}
      },
      "order": [[ 1, "desc" ]],
      "columnDefs" : [
            { orderable: false, targets: [ 0,4,5,6] }
        ]
    });  
</script>

<script type="text/javascript">
$('#check-all').click(function (e) {
  $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
});

  function getData()
  { 
    var property_name       = $('#property_name').val();
    var property_owner_id   = $('#property_owner_id').val();
    var property_booked_id  = $('#property_booked_id').val();
    var booking_date        = $('#booking_date').val();
  
    $('#e_property_name').val(property_name);
    $('#e_property_owner_id').val(property_owner_id);
    $('#e_property_booked_id').val(property_booked_id);
    $('#e_booking_date').val(booking_date);
   
    $('#frm-export').submit();
  }

  $(document).ready(function(){
    $('#records').val('');
  });
  $(document).on('click','.checked-all', function(){    
    var yourArray = [];
    var records = $('#records').val();
      $('.checked_record').each(function(){
          yourArray.push($(this).val());
      });

      if($(this).prop('checked') == true){
      var checked_id = records+','+yourArray;
      $('#records').val(checked_id);
     }
     else{      
      var newValue = records.replace(','+yourArray, '');
      $('#records').val(newValue);
     }     

  });
  $(document).on('click','.checked_record' , function(){
     var records = $('#records').val();
     if($(this).prop('checked') == true){
      var checked_id = records+','+$(this).val();
      $('#records').val(checked_id);
     }
     else{
      var checked_id = $(this).val();
      var newValue = records.replace(','+checked_id, '');
      $('#records').val(newValue);
     }
  });

</script>
@stop