@extends('support.layout.master')
@section('main_content')
<!-- BEGIN Content -->

</style>
<div id="main-content">
  <!-- BEGIN Page Title -->
  <div class="page-title">
    <div>
      <h1><i class="fa {{$module_icon}}"></i> {{$page_title or ''}}</h1>
      <!-- <h4>Overview, stats, chat and more</h4> -->
    </div>
  </div>

  <!-- END Page Title -->
  <!-- BEGIN Breadcrumb -->
  <div id="breadcrumbs">
    <ul class="breadcrumb">
      <li>
        <i class="fa fa-home"></i>
        <a href="{{url($support_panel_slug.'/dashboard')}}">Dashboard</a>
        <span class="divider"><i class="fa fa-angle-right"></i></span>
      </li>
      <li class="active"> <i class="fa {{$module_icon}}"></i>  {{$page_title or ''}}</li>
    </ul>
  </div>
  <!-- END Breadcrumb -->

  <!-- BEGIN Tiles -->
  <?php 
      $request_id    = Request::input('request_id');
      $user_name     = Request::input('user_name');
      $requested_on  = Request::input('requested_on');
  ?>
 @include('support.layout._operation_status')
  <!-- BEGIN Tiles -->
  <div class="row">
    <div class="col-md-12">
      <div class="box {{theme_color()}}">
        <div class="box-title">
          <h3><i class="fa {{$module_icon}}"></i> {{$page_title or ''}}</h3>
        </div>
        <div class="box-content">         
          <form name="frm-manage" id="frm-manage" method="get" action="{{$module_url_path}}/approve"  class="form-inline" >

               
                <div class="form-group " style="margin-top:6px;">
                  <label >Request Id: </label>
                  <input type="text" placeholder="Request Id"  id="request_id" name="request_id" class="form-control email"  value="{{ trim($request_id)}}"  >
                </div>
                &nbsp;&nbsp;
                <div class="form-group " style="margin-top:6px;">
                  <label >User Name: </label>
                  <input type="text"  placeholder="User Name"  id="user_name" name="user_name" class="form-control"  value="{{ trim($user_name)}}"  >
                </div>
                &nbsp;&nbsp;
                <div class="form-group" style="margin-top:6px;">
                  <label >Requested On: </label>
                  <input type="text"  placeholder="Requested On"  id="requested_on" name="requested_on" class="form-control" class="date-picker form-control" value="{{ trim($requested_on)}}"  >
                </div>
                &nbsp;&nbsp;
                <div class="form-group">
                  <input id="submit_filter" class="btn btn-primary same_search" type="submit" value="Search" name="btn_search"> &nbsp;
                  <a href="{{$module_url_path}}/approve" class="btn btn-default">Reset </a>
                </div>
             
              <input type="hidden" name="htoken" value="{{ csrf_token() }}">
              <div class="clearfix"></div>
          </form>

     <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{$module_url_path}}/multi_action">
              {{ csrf_field() }}
              {{-- <div class="col-md-10">
                <div id="ajax_op_status"></div>
                <div class="alert alert-danger" id="no_select" style="display:none;"></div>
                <div class="alert alert-warning" id="warning_msg" style="display:none;"></div>
              </div> --}}
            
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
                      <th>Request Id</th>
                      <th>User Name</th>
                      <th>Requested On</th>
                      <th>Status</th>
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

<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>

<script type="text/javascript"> 
  $("#requested_on").datepicker({ dateFormat: 'yy-mm-dd' });
  
    var request_id      = $('#request_id').val();
    var user_name       = $('#user_name').val();
    var requested_on    = $('#requested_on').val();

    var module_url_path = "{{url($module_url_path)}}";
    var temp_url        = module_url_path+'/load_approve_data';
    
    var url             = temp_url.replace(/&amp;/g, '&'); 
    table_module        = $('#myTable').DataTable({

          "processing"  : true,
          "serverSide"  : true,
          "paging"      : true,
          "searching"   : false,
          "ordering"    : true,
          "destroy"     : true,
      ajax: 
      {
        'url'     : temp_url,
        'data'    : {'request_id':request_id,'user_name':user_name,'requested_on':requested_on}
      },
       /*"columnDefs": [
            { orderable: false, targets: [ 0,6] }
        ]*/
    });  
</script>

<script type="text/javascript">

  $('#check-all').click(function (e) {
    $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
  });

  
</script>


    @stop