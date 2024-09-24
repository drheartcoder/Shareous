    @extends('admin.layout.master')                


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
                <a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard</a>
            </li>
            <span class="divider">
                <i class="fa fa-angle-right"></i>
                <i class="fa fa-car"></i>           
            </span> 
             <li class="active">{{ $module_title or ''}}</li>
           
        </ul>
      </div>
    <!-- END Breadcrumb -->

    <!-- BEGIN Main Content -->
    <div class="row">
      <div class="col-md-12">

          <div class="box">
            <div class="box-title">
              <h3>
                <i class="fa fa-list"></i>
                {{ isset($page_title)?$page_title:"" }}
            </h3>
            <div class="box-tool">
                <a data-action="collapse" href="#"></a>
                <a data-action="close" href="#"></a>
            </div>
        </div>
        <div class="box-content">
        
          @include('admin.layout._operation_status')  
          

          {!! Form::open([ 'url' => $module_url_path.'/multi_action',
                                 'method'=>'POST',
                                 'enctype' =>'multipart/form-data',   
                                 'class'=>'form-horizontal', 
                                 'id'=>'frm_manage' 
                                ]) !!} 

            {{ csrf_field() }}

            <div class="col-md-10">
            
            <div id="ajax_op_status">
                
            </div>
            <div class="alert alert-danger" id="no_select" style="display:none;"></div>
            <div class="alert alert-warning" id="warning_msg" style="display:none;"></div>
          </div>
        
          
          <div class="btn-toolbar pull-right clearfix">

            <div class="btn-group"> 
             

           {{--  @if(array_key_exists('vehicle.update', $arr_current_user_access))                 
                  
                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                   title="Lock" 
                   href="javascript:void(0)"
                   onclick="javascript : return check_multi_action('frm_manage','deactivate');" 
                   style="text-decoration:none;">
                   <i class="fa fa-lock"></i>
                </a> 

                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                   title="Unlock" 
                   href="javascript:void(0)"
                   onclick="javascript : return check_multi_action('frm_manage','activate');" 
                   style="text-decoration:none;">
                   <i class="fa fa-unlock"></i>
                </a> 
            @endif
            @if(array_key_exists('vehicle.delete', $arr_current_user_access))                     
                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                   title="Multiple Delete" 
                   href="javascript:void(0);" 
                   onclick="javascript : return check_multi_action('frm_manage','delete');"  
                   style="text-decoration:none;">
                   <i class="fa fa-trash-o"></i>
                </a>
            @endif --}}
                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip refrash-btns" 
                   title="Refresh" 
                   href="javascript:void(0)"
                   onclick="javascript:location.reload();" 
                   style="text-decoration:none;">
                   <i class="fa fa-repeat"></i>
                </a> 
            </div>
          </div>
          <label class="col-sm-3 col-lg-2 control-label">User Type</label>
            <div class="col-sm-3 col-lg-2 controls-label" >
                <select class="form-control"  name="service_type" id="service_type" >
                  <option value="normal">Rider</option>
                  <option value="emergency">Driver</option>
                </select>
            </div>

          <br/> <br>
          <div class="clearfix"></div>
          <div class="table-responsive" style="border:0">

            <input type="hidden" name="multi_action" value="" />

            <table class="table table-advance"  id="table_module" >
              <thead>
                <tr>

                  @if(array_key_exists('vehicle.update', $arr_current_user_access) || array_key_exists('vehicle.delete', $arr_current_user_access)) 
                    <th style="width:18px"> <input type="checkbox" name="mult_change" id="mult_change" /></th>
                  @endif
                  <th>Name</th> 
                  <th>Email Address</th>
                  <th>Mobile Number</th>
                  <th width="150px">Action</th> 
                </tr>
              </thead>
              <tbody>
                
                @if(isset($arr_ride) && sizeof($arr_ride)>0)
                  @foreach($arr_ride as $key => $data)

                  <tr class="paid-mark">
                    @if(array_key_exists('vehicle.update', $arr_current_user_access) ||array_key_exists('vehicle.delete', $arr_current_user_access))                 
                    <td> 
                      <input type="checkbox" 
                             name="checked_record[]"  
                             value="{{ base64_encode($data['id']) }}" /> 
                    </td>
                    @endif

                    <td> {{ isset($data['rider_to_driver_request_details']['rider_details']['first_name']) ? $data['rider_to_driver_request_details']['rider_details']['first_name'] : '' }} 
                    {{ isset($data['rider_to_driver_request_details']['rider_details']['last_name']) ? $data['rider_to_driver_request_details']['rider_details']['last_name'] : '' }} 
                    </td>
                       
                    <td> 

                    </td>
                                                           
                    <td>  </td>
                                                            
                    <td>  </td>                                 
                  </tr>
                                   
                  @endforeach
                @endif
                 
              </tbody>
            </table>
          </div>
        <div> </div>
         
          {!! Form::close() !!} 
      </div>
  </div>
</div>
<script type="text/javascript">
    
    $(document).ready(function() {
        $('#table_module').DataTable();
    });
</script>
<!-- END Main Content -->

<script type="text/javascript">
              $(document).ready(function()
              {
                $('.update-paid-status').click(function() {

                  var status = $(this).parent('td').parent('tr').find('td.paid-status').html('<b>PAID<b>');
                  var ride_id = $(this).val();
                    $.ajax({
                    type:'GET',
                    url:"{{url('/')}}/admin/ride/update_payment?ride_id="+ride_id,
                    success:function(response) {                      
                      $.each(response.arr_data, function (key, val) {
                      var stl = '<td>'+val.payment_status+'</td>'
                    });
                        $('#paid').html(stl);
                    }
                  });
                  
                });
              });
          </script>  
@stop                    


