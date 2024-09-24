@extends('admin.layout.master')
@section('main_content')
<style>
    .form-group .coupon-input-blo label{display: block; width: 100%;}
    .form-group .coupon-input-blo input{display: block; width: 100%;}
</style>
<div id="main-content">
  <div class="page-title">
    <div>
      <h1><i class="fa {{$module_icon}}"></i> {{$page_title or ''}}</h1>
  </div>
</div>

<div id="breadcrumbs">
    <ul class="breadcrumb">
      <li>
        <i class="fa fa-home"></i>
        <a href="{{url($admin_panel_slug.'/dashboard')}}">Dashboard</a>
        <span class="divider"><i class="fa fa-angle-right"></i></span>
    </li>
    <li class="active"> <i class="fa {{$module_icon}}"></i>  {{$page_title or ''}}</li>
</ul>
</div>

@if(Session::has('success'))
<div class="alert alert-success alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{Session::get('success')}}
</div>
@endif
@if(Session::has('error'))
<div class="alert alert-danger alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{Session::get('error')}}
</div>
@endif

<?php
  $code             = Request::input('code');
  $discount_type    = Request::input('discount_type');
  $discount_account = Request::input('discount_account');
  $global_expiry    = Request::input('global_expiry');
  $auto_expiry      = Request::input('auto_expiry');
  $coupon_use       = Request::input('coupon_use');
?>

<div class="row">
    <div class="col-md-12">
      <div class="box {{theme_color()}}">
        <div class="box-title">
          <h3><i class="fa {{ $module_icon }}"></i> {{ $page_title or '' }}</h3>
      </div>
      <div class="box-content">         
          <form name="frm-manage" id="frm-manage" method="get" action="{{$module_url_path}}"  class="form-inline dfcdfvdfv" >

           <div class="row">
            <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
             <div class="coupon-input-blo">
              <label >Code: </label>
              <input type="text" placeholder="Code"  id="code" name="code" class="form-control "  value="{{ trim($code)}}"  >
          </div>
      </div>

      <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4" >
       <div class="coupon-input-blo">
        <label>Discount Type: </label>
          <select class="form-control chosen-with-diselect" tabindex="-1" id="discount_type" name="discount_type">
              <option value="">Select Discount Type</option>
              <option @if($discount_type=="1") selected="" @endif value="1" >Fix Amount</option>
              <option @if($discount_type=="2") selected="" @endif value="2" >Percentage</option>
          </select>
      
    </div>
</div>

<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4" >
 <div class="coupon-input-blo">
  <label >Discount Amount: </label>
  <input type="text"  placeholder="Discount Amount"  id="discount_account" name="discount_account" class="form-control"  value="{{ trim($discount_account)}}"  >
</div>
</div>

<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4" >
   <div class="coupon-input-blo">
    <label >Global Expiry: </label>
    <input type="text" placeholder="Global Expiry"  id="global_expiry" name="global_expiry" class="form-control datepicker" value="{{ trim($global_expiry)}}" >
    <div class="error text-danger" id="err_global_expiry"></div>
</div>
</div>

<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4" >
 <div class="coupon-input-blo">
  <label >Auto Expiry: </label>
  <input type="text" placeholder="Auto Expiry"  id="auto_expiry" name="auto_expiry" class="form-control datetimepicker3"  value="{{ trim($auto_expiry)}}"  >
</div>
</div>

<!-- <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4" >
  <div class="coupon-input-blo">
    <label >Coupon Use: </label>
    <select class="form-control chosen-with-diselect" tabindex="-1" id="coupon_use" name="coupon_use">
        <option value="">Select Coupon Use</option>
        <option @if($coupon_use=="1") selected="" @endif value="1" >Min Amount</option>
        <option @if($coupon_use=="2") selected="" @endif value="2" >User First Time</option>
        <option @if($coupon_use=="3") selected="" @endif value="3" >Both</option>
    </select>
  </div>
</div> -->

</div>

<div class="form-group" >
  <input id="submit_filter" class="btn btn-primary same_search" type="submit" value="Search" name="btn_search"> &nbsp;
  <a href="{{$module_url_path}}" class="btn btn-default">Reset </a>
</div>

<input type="hidden" name="htoken" value="{{ csrf_token() }}">
<div class="clearfix"></div>
</form>

<form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{$module_url_path}}/multi_action">
  {{ csrf_field() }}

  <div class="btn-toolbar pull-right">
    <div class="btn-group"> 
      <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Refresh" onclick="window.location.reload()" style="text-decoration:none;">
        <i class="fa fa-repeat"></i>
    </a> 
    <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Multiple Unblock" href="javascript:void(0);" onclick="javascript : return check_multi_action('frm_manage','activate');" style="text-decoration:none;">
        <i class="fa fa-unlock"></i>
    </a> 
    <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip btn-dangers" title="Multiple Block" href="javascript:void(0);" onclick="javascript : return check_multi_action('frm_manage','deactivate');"  style="text-decoration:none;">
        <i class="fa fa-lock"></i>
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
            <input type="checkbox" name="mult_change" id="mult_change" value="delete" />
        </th>
        <th>Code</th>
        <th>Discount Type</th>
        <th>Discount/Amount</th>
        <th>Global Expiry</th>
        <th>Auto Expiry</th> 
        <!-- <th>Coupon Use</th> -->
        <th>Status</th>
        <th>Action</th>
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
  $(function () {
    $(".datepicker").datepicker({
      todayHighlight: true,
      autoclose: true
  });

    $('.datetimepicker3').datetimepicker({
      format: 'HH:mm'
  });
});

    $('#submit_filter').click(function(){
        var auto_expiry   = $('#auto_expiry').val().trim();
        var global_expiry = $('#global_expiry').val().trim();

        if(auto_expiry != '' && auto_expiry != '00:00'){
            if(global_expiry == ''){
                $('#err_global_expiry').text("Please select Global Expiry");
                return false;
            }
        }
        return true;
    });

  var code             = $('#code').val();
  var discount_type    = $('#discount_type').val();
  var discount_account = $('#discount_account').val();
  var global_expiry    = $('#global_expiry').val();
  var auto_expiry      = $('#auto_expiry').val();
  //var coupon_use       = $('#coupon_use').val();

  var module_url_path  = "{{url($module_url_path)}}";
  var temp_url         = module_url_path+'/load_data';

  var url              = temp_url.replace(/&amp;/g, '&');

  table_module         = $('#myTable').DataTable({
    "processing": true,
    "serverSide": true,
    "paging": true,
    "searching":false,
    "ordering": true,
    "destroy": true,
    ajax: 
    {
      'url'     : temp_url,
      'data'    : {'code':code,'discount_type':discount_type,'discount_account':discount_account,'global_expiry':global_expiry,'auto_expiry':auto_expiry}
  },
  "columnDefs": [
  { orderable: false, targets: [ 0,6] }
  ]
});  
</script>

<script type="text/javascript">

  $('#check-all').click(function (e) {
    $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
});

</script>


@stop