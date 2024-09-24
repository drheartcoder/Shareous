
@extends('admin.layout.master')
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
        <a href="{{url($admin_panel_slug.'/dashboard')}}">Dashboard</a>
        <span class="divider"><i class="fa fa-angle-right"></i></span>
      </li>
      @if(Request::segment(2) == 'host')
         <li>
          <i class="fa fa-server"></i>
          <a href="{{url($manage_host)}}">Manage Host</a>
          <span class="divider"><i class="fa fa-angle-right"></i></span>
        </li>
      @endif
         <li>
          <i class="fa fa-server"></i>
          <a href="{{url($module_url_path)}}">Manage Review & Ratings</a>
          <span class="divider"><i class="fa fa-angle-right"></i></span>
        </li>
      
      <li class="active"> <i class="fa {{$module_icon}}"></i>{{$page_title or ''}}</li>
    </ul>
  </div>
  <!-- END Breadcrumb -->

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
  <!-- BEGIN Tiles -->

  <!-- BEGIN Tiles -->
  <div class="row">
    <div class="col-md-12">
      <div class="box {{theme_color()}}">
        <div class="box-title">
          <h3><i class="fa {{$module_icon}}"></i> {{$page_title or ''}}</h3>
        </div>
        <div class="box-content">         
       
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
                       <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Multiple Unblock" href="javascript:void(0);" onclick="javascript : return check_multi_action('frm_manage','activate');" style="text-decoration:none;">
                        <i class="fa fa-unlock"></i>
                      </a> 
                      <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip btn-dangers" title="Multiple Block" href="javascript:void(0);" onclick="javascript : return check_multi_action('frm_manage','deactivate');"  style="text-decoration:none;">
                        <i class="fa fa-lock"></i>
                      </a>  

                       <a class="btn btn-circle btn-to-danger btn-bordered btn-fill show-tooltip" title="Delete multiple records" href="javascript:void(0)" onclick="javascript : return check_multi_action('frm_manage','delete');"><i class="fa fa-trash"></i></a>
    
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
                        <th>User Name</th>
                        <th>Property Name</th>
                        <th>Message</th>
                        <th>Rating</th>         
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

    var check_host = "{{Request::segment(2)}}";


    if(check_host == 'host')
    {
       var property_id = "{{Request::segment(5)}}";
    }
    else
    {
       var property_id = "{{Request::segment(4)}}";
    }

    var module_url_path = "{{url($module_url_path)}}";
    var temp_url        = module_url_path+'/load_individual_rating';
    var url             = temp_url.replace(/&amp;/g, '&'); 
    
    table_module        = $('#myTable').DataTable({

          "processing": true,
          "serverSide": true,
          "paging": true,
          "searching":false,
          "ordering": true,
          "destroy": true,
      ajax: 
      {
        // 'dataType': 'json',
        'url'     : temp_url,
        'data'    : {'property_id':property_id}
      },
       "columnDefs": [
            { orderable: false, targets: [ 0,4] }
        ]
    });  
</script>

<script type="text/javascript">

  $('#check-all').click(function (e) {
    $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
  });
  
</script>


 @stop