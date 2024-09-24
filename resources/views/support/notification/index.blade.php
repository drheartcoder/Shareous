    @extends('support.layout.master')                

    @section('main_content')
    <!-- BEGIN Page Title -->
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/data-tables/latest/dataTables.bootstrap.min.css">
    <div class="page-title">
        <div>
        </div>
    </div>
    <!-- END Page Title -->

    <!-- BEGIN Breadcrumb -->
    <div id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{ url($support_panel_slug.'/dashboard') }}">Dashboard</a>
            </li>
            <span class="divider">
                <i class="fa fa-angle-right"></i>
                <i class="fa {{$mdule_icon}}"></i>                
            </span> 
            <li class="active">{{ $module_title or ''}}</li>
           
        </ul>
      </div>
    <!-- END Breadcrumb -->

    <!-- BEGIN Main Content -->
    <div class="row">
      <div class="col-md-12">

          <div class="box ">
            <div class="box-title">
              <h3>
                <i class="fa {{$mdule_icon}}"></i>
                {{ isset($page_name)?$page_name:"" }}
            </h3>
            <div class="box-tool">
                <a data-action="collapse" href="#"></a>
                <a data-action="close" href="#"></a>
            </div>
        </div>
        <div class="box-content">
        
          @include('support.layout._operation_status')  
          
          <form class="form-horizontal" id="frm_manage" method="POST" action="{{ url($module_url_path.'/multi_action') }}">

            {{ csrf_field() }}

            <div class="col-md-10">
            

            <div id="ajax_op_status">
                
            </div>
            <div class="alert alert-danger" id="no_select" style="display:none;"></div>
            <div class="alert alert-warning" id="warning_msg" style="display:none;"></div>
          </div>
          <div class="btn-toolbar pull-right clearfix">

            <div class="btn-group"> 
                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                   title="Refresh" 
                   href="javascript:void(0)"
                   onclick="javascript:location.reload();" 
                   style="text-decoration:none;">
                   <i class="fa fa-repeat"></i>
                </a> 
            </div>
          </div>
          <br/><br/>
          <div class="clearfix"></div>

          <div class="table-responsive" style="border:0">

            <input type="hidden" name="multi_action" value="" />

            <table class="table table-advance"  id="table_module">
              <thead>
                <tr>
                  <th>Notification text</th> 
                  <th>Received On</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
          
                @if(isset($arr_notification) && is_array($arr_notification) && sizeof($arr_notification)>0)
                  @foreach($arr_notification as $key=>$notification)
                  <tr>
                    <td> {{ $notification['notification_text'] or '' }} </t d> 
                    <td> {{ isset($notification['created_at'])? get_added_on_date($notification['created_at']):'' }} </td> 
                    <td> <a class="btn btn-circle btn-to-danger btn-bordered btn-fill show-tooltip" title="Delete" href="javascript:void(0)" onclick='delete_record("{{base64_encode($notification['id'])}}")'><i class="fa fa-trash"></i> </td> 
                  </tr>
                  @endforeach
                @endif
                 
              </tbody>
            </table>
          </div>


        <div> </div>
         
          </form>
      </div>
  </div>
</div>

<!-- END Main Content -->
<script type="text/javascript">
  $(document).ready(function()
  {
      var oTable = $('#table_module').dataTable({
            "aoColumnDefs": [
                              { 
                                "bSortable": false, 
                                "aTargets": [2] ,
                              }, 
                              { "searchable": false, "targets": [2] },
                            ],
                "ordering": false,       
          });
          //oTable.fnSort( [ [1,'desc'] ] );
});

  function delete_record(id)
      {
        swal({
          title: "Are you sure",
          text: "Do you want to delete record?",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Yes, delete it!",
          closeOnConfirm: false
        },
        function(){
          location.href="{{url($module_url_path)}}/delete/"+id;
        });
      }

</script>
 
@stop                    


