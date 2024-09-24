@extends('admin.layout.master')

<style>
.margintop{margin-top: 23px;}
.form-field-txt .chosen-container{width: 100% !important;}
.form-field-txt .form-inline .form-control{width: 100%;}
.icrcle-btm{position: absolute;right: 20px; top: 59px;}
.form-inline.bottm-noe{margin-bottom: 0; padding-right: 30px;}
    
    
    
    
    @media all and (max-width:991px){
        .icrcle-btm{position: static;margin-bottom: 20px;float: right;}
    }    
    
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
      <li class="active"> <i class="fa {{ $module_icon }}"></i>{{ $page_title or '' }}</li>
    </ul>
  </div>
  <!-- END Breadcrumb -->

  <?php
      $property = Request::input('property');
      $keyword  = Request::input('keyword');
      $request_segment_three = Request::segment(3);
  ?>

  <!-- BEGIN Tiles -->
  <div class="row">
    <div class="col-md-12">
      <div class="box {{ theme_color() }}">
        <div class="box-title">
          <h3><i class="fa {{ $module_icon }}"></i> {{ $page_title or '' }}</h3>
        </div>
        <div class="box-content form-field-txt">         
          <form name="frm-manage" id="frm-manage" method="get" action="{{ $module_url_path }}/{{ $request_segment_three }}" class="form-inline bottm-noe" >
            <div class="row">

              <div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
                <label >Keyword: </label>
                <input type="text" placeholder="Keyword" id="keyword" name="keyword" class="form-control" value="{{ trim($keyword) }}">
              </div>

              <div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3 margintop">
                <input id="submit_filter" class="btn btn-primary same_search" type="submit" value="Search" name="btn_search"> &nbsp;
                <a href="{{ $module_url_path }}/{{ $request_segment_three }}" class="btn btn-default">Reset </a>
              </div>

              <input type="hidden" name="htoken" value="{{ csrf_token() }}">
              <div class="clearfix"></div>
            </div>
          </form>

          <br/>

          @include('admin.layout._operation_status')

          <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{ $module_url_path }}/multi_action">
            {{ csrf_field() }}
          
            <div class="btn-toolbar icrcle-btm">
              <div class="btn-group"> 
                @if(Request::segment(3) == 'pending')
                <a class="badge badge-important" title="Confirm Multiple Property" @if(Request::segment(3)=='pending')onclick="javascript : return confirm_multi_action('frm_manage','2');" @endif style="text-decoration:none;padding: 7px 7px;background-color:#15b74e;">
                  Confirm <i class="fa fa-check-circle"></i>
                </a>
                @endif

                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Refresh" onclick="window.location.reload()" style="text-decoration:none;">
                  <i class="fa fa-repeat"></i>
                </a> 

              </div>
            </div>

            <input type="hidden" name="multi_action" value="" />
            <div class="table-responsive" style="border:0">
              <table id="myTable" class="table table-advance">
                <thead>
                  <tr>
                    <th style="width:18px">
                      <input type="checkbox" name="mult_change" id="mult_change" value="delete" />
                    </th>
                    <th width="20%">Property Name</th>
                    <th width="15%">Owner Email</th>
                    <th width="20%">Address</th>
                    <th>Added Date</th>
                    <th>Status</th>
                    @if($admin_status == 2)
                      <th>Is Featured</th>
                    @endif
                    <th>Action</th>
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

    var admin_status    = '{{ $admin_status }}';
    var keyword         = $('#keyword').val();
    var property        = $('#property').val();
    var module_url_path = "{{url($module_url_path)}}";
    var temp_url        = module_url_path+'/load_data?admin_status='+admin_status;
    var url             = temp_url.replace(/&amp;/g, '&');
    
    var table_module    = $('#myTable').DataTable({
          "processing": true,
          "serverSide": true,
          "paging": true,
          "searching": false,
          "ordering": true,
          "destroy": true,
      ajax: 
      {
        'url' : temp_url,
        'data' : { 'keyword':keyword, 'property':property }
      },
      "columnDefs": [
        { orderable: false, targets: [0,5,6] }
      ]
    });  

  $('#check-all').click(function (e) {
    $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
  });

  function confirm_multi_action(frm_id,action) {
      var len = $('input[name="checked_record[]"]:checked').length;
      var flag = 1;
      var frm_ref = $("#"+frm_id);
      
      if(len <= 0) {
        swal("Oops..","Please select the record to perform this Action.");
        return false;
      }

      if(action=='2') {
        var confirmation_msg = "Do you really want to confirm selected record(s) ?";
      }
      else if(action == '3') {
        var confirmation_msg = "Do you really want to reject selected record(s) ?";
      }
      else if(action == '4') {
        var confirmation_msg = "Do you really want to reject permanentlyy selected record(s) ?";
      }
      
      swal({
            title: "Are you sure ?",
            text: confirmation_msg,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: true,
            closeOnCancel: true
          },
          function(isConfirm) {
            if(isConfirm) {
              $('input[name="multi_action"]').val(action);
              $(frm_ref)[0].submit();
            }
            else {
              return false;
            }
          });
  }
</script>

@stop