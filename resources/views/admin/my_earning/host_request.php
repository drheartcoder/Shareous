@extends('admin.layout.master')
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

  
  <!-- BEGIN Breadcrumb -->
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

  <!-- END Breadcrumb -->
  
  <?php 
      $property_name   = Request::input('property_name');
      $property_owner  = Request::input('property_owner');
      $search_date     = Request::input('search_date');
      $keyword         = Request::input('keyword');
  ?>
  <!-- BEGIN Tiles -->
  <div class="row">
    <div class="col-md-12">
      <div class="box {{theme_color()}}">
        <div class="box-title">
          <h3><i class="fa {{$module_icon}}"></i> {{$page_title or ''}}</h3>
        </div>
        <div class="box-content">    

         

          <br />
          @include('admin.layout._operation_status')
         
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
                    <th>Transaction Id</th>
                    <th>Booking Id</th>
                    <th>Property Owner</th>
                    <th>Property Name</th>
                    <th>Date</th>
                    <th>Property Amount</th>
                    <th>Commission</th>
                    <th>Total Earn</th>
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

    $("#search_date").datepicker({ dateFormat: 'yy-mm-dd' });

 
    var module_url_path     = "{{url($module_url_path)}}";
    var temp_url            = module_url_path+'/load_host_request_data';
    var url                 = temp_url.replace(/&amp;/g, '&'); 
    
    table_module            = $('#myTable').DataTable({

          "processing": true,
          "serverSide": true,
          "paging": true,
          "searching":false,
          "ordering": true,
          "destroy": true,
      ajax: 
      {
        'url'     : temp_url
       /* 'data'    : {'property_name':property_name,'property_owner':property_owner,'search_date':search_date,'keyword':keyword}*/
      },
      /* "columnDefs": [
            { orderable: false, targets: [ 0,5] }
        ]*/
    });  
</script>

<script type="text/javascript">
$('#check-all').click(function (e) {
  $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
});

</script>
@stop