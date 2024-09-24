@extends('admin.layout.master')

<style>
  .form-group label{ display: block; }
  .form-group .chosen-container{ width: 100% !important; }
  .form-group input{ width: 100% !important; }
  .form-group .btn-primary.same_search{ width: auto !important; }
</style>

@section('main_content')
<!-- BEGIN Content -->

<div id="main-content">
  <!-- BEGIN Page Title -->
  <div class="page-title">
    <div>
      <h1><i class="fa {{ $module_icon }}"></i> {{ $page_title or '' }}</h1>
    </div>
  </div>
  <!-- END Page Title -->

  
  <!-- BEGIN Breadcrumb -->
  <div id="breadcrumbs">
    <ul class="breadcrumb">
      <li>
        <i class="fa fa-home"></i>
        <a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard</a>
        <span class="divider"><i class="fa fa-angle-right"></i></span>
      </li>


      <li class="active"> <i class="fa fa-list fa-lg"></i>  {{$page_title or ''}}</li>
    </ul>
  </div>

  <!-- END Breadcrumb -->
  
  <?php 
    $property_name  = Request::input('property_name');
    $property_owner = Request::input('property_owner');
    $search_date    = Request::input('search_date');
    $keyword        = Request::input('keyword');
  ?>
  <!-- BEGIN Tiles -->
  <div class="row">
    <div class="col-md-12">
      <div class="box {{theme_color()}}">
        <div class="box-title">
          <h3><i class="fa {{ $module_icon }}"></i> {{ $page_title or '' }}</h3>
        </div>
        <div class="box-content">    

          <form name="frm-manage" id="frm-manage" method="get" action="{{ $module_url_path }}"  class="form-inline" >

                <div class="form-group col-md-3">
                    <label>Property Name :</label>
                    <select class="form-control chosen-with-diselect" tabindex="-1" id="property_name" name="property_name">
                        <option value="">--Select Property Name--</option>
                          @if(isset($arr_property) && count($arr_property)>0)
                            @foreach($arr_property as $property)
                             <option value="{{ base64_encode($property->id) }}" {{ ( Request::get('property_name') != '' && Request::get('property_name') == base64_encode($property->id)) ? "selected" : '' }} > {{ $property->property_name }} 
                              </option>
                             @endforeach
                          @endif
                    </select>
                </div>

                <div class="form-group col-md-3">
                  <label>Property Owner :</label>
                  <select class="form-control chosen-with-diselect" tabindex="-1" id="property_owner" name="property_owner">
                      <option value="">--Select Property Owner--</option>
                        @if(isset($arr_property_owner) && count($arr_property_owner)>0)
                          @foreach($arr_property_owner as $owner)
                           <option value="{{ base64_encode($owner->id) }}" {{ ( Request::get('property_owner') != '' && Request::get('property_owner') == base64_encode($owner->id)) ? "selected" : '' }} > {{ $owner->owner_firstname.' '.$owner->owner_lastname }} 
                            </option>
                           @endforeach
                        @endif
                  </select>
                </div>

                <div class="form-group col-md-3">
                  <label>Date</label>
                  <input type="text" id="search_date"   placeholder="Search Date" class="date-picker form-control" name="search_date" value="{{ isset($search_date) && $search_date != '' ? $search_date : '' }}">
                  <span class="help-block" id="err_booking_date">{{ $errors->first('search_date')}}</span>  
                </div>

                <div class="form-group col-md-3">
                    <label>Keyword</label>
                    <input type="text" id="keyword"  placeholder="keyword" class="form-control" name="keyword" value="{{ isset($keyword) && $keyword != '' ? $keyword : '' }}">
                    <span class="help-block" id="err_keyword">{{ $errors->first('keyword')}}</span>  
                </div>

                <div class="form-group top-less col-md-3">
                  <input id="submit_filter" class="btn btn-primary same_search" type="submit" value="Search" name="btn_search"> &nbsp;
                  <a href="{{ $module_url_path }}" class="btn btn-default">Reset </a>
                </div>

              <input type="hidden" name="htoken" value="{{ csrf_token() }}">
              <div class="clearfix"></div>
          </form>

          <form name="export" id="frm-export" action="{{ $module_url_path }}/export" method="get">
              <input type="hidden" name="e_property_owner" id="e_property_owner" value="">
              <input type="hidden" name="e_property_name"  id="e_property_name"  value="">
              <input type="hidden" name="e_search_date"    id="e_search_date"    value="">
              <input type="hidden" name="e_keyword"        id="e_keyword"        value="">
              <br>
              &nbsp;&nbsp;&nbsp;
              <div class="btn-group">
                <button type="button" class="btn btn-default show-tooltip" id="getVal" title="Export" onclick="getData()" style="text-decoration:none;background-color: #0090ff; color: rgba(255,255,255,0.9);">Export</button>
              </div>
          </form>

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
            <div class="clearfix"></div>

            <input type="hidden" name="multi_action" value="" />
            <div class="table-responsive" style="border:0">
              <table id="myTable" class="table table-advance">
                <thead>
                  <tr>
                    <th>Transaction Id</th>
                    <th>Booking Id</th>
                    <th>Property Owner</th>
                    <th>Property Name</th>
                    <th>Date</th>
                    <th>Property Amount</th>
                    <th>Total Paid Amount</th>
                    <th>Commission</th>
                    <th>Total Earn</th>
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

  $("#search_date").datepicker({ dateFormat: 'yy-mm-dd' });

  var property_name   = $('#property_name').val();
  var property_owner  = $('#property_owner').val();
  var search_date     = $('#search_date').val();
  var keyword         = $('#keyword').val();
  var module_url_path = "{{ url($module_url_path) }}";
  var temp_url        = module_url_path+'/load_data';
  var url             = temp_url.replace(/&amp;/g, '&');
  
  table_module = $('#myTable').DataTable({
    "processing" : true,
    "serverSide" : true,
    "paging"     : true,
    "searching"  : false,
    "ordering"   : true,
    "destroy"    : true,
    
    ajax : {
      'url'  : temp_url,
      'data' : {'property_name':property_name,'property_owner':property_owner,'search_date':search_date,'keyword':keyword}
    }

  });  

  $('#check-all').click(function (e) {
    $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
  });

  function getData()
  { 
    var property_owner = $('#property_owner').val();
    var property_name  = $('#property_name').val();
    var search_date    = $('#search_date').val();
    var keyword        = $('#keyword').val();
    
    $('#e_property_owner').val(property_owner);
    $('#e_property_name').val(property_name);
    $('#e_search_date').val(search_date);
    $('#e_keyword').val(keyword);

    $('#frm-export').submit();
  }

</script>
@stop