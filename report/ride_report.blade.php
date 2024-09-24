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
                <i class="fa fa-users"></i>                
            </span> 
            <li class="active">{{ isset($ride_module_title)?$ride_module_title:"" }}</li>
        </ul>
      </div>
    <!-- END Breadcrumb -->
                            
    <!-- BEGIN Main Content -->
    <div class="row">
      <div class="col-md-12">

          <div class="box {{ $theme_color }}">
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
                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" 
                   title="Refresh" 
                   href="{{ $module_url_path }}?report_type=ride"
                   style="text-decoration:none;">
                   <i class="fa fa-repeat"></i>
                </a> 
              </div>
          </div>
          <div class="col-sm-5 col-lg-3">
              {{-- <a class="btn btn-circle btn-to-success column_filter"   onclick="setFilter('daily')">Daily</a>
              <a class="btn btn-circle btn-to-success column_filter"  onclick="setFilter('weekly')">Weekly</a>
              <a class="btn btn-circle btn-to-success column_filter"  onclick="setFilter('monthly')">Monthly</a>
              <input type="hidden" name="q_date" id="q_date"> --}}
            </div>

            {{-- <label class="col-sm-3 col-lg-2 control-label">User Type</label>
            <div class="col-sm-7 col-lg-2 controls" >
                <select class="form-control"  name="user_type" id="user_type" onchange="change_default_type()">
                  <option value="rider" >Rider</option>
                  <option value="driver">Driver</option>
                  <option value="ride" selected>Ride</option>
                  <option value="rating">Rating</option>
                </select><br>
            </div> --}}
             
          <div class="clearfix">
            
          </div><br>
          <div class="form-group" style="margin-top: 25px;">
                <div class="col-sm-5 col-lg-2 controls" >
                  <a class="btn btn-circle btn-to-success column_filter"   onclick="setFilter('daily')">Daily</a>
                  <a class="btn btn-circle btn-to-success column_filter"  onclick="setFilter('weekly')">Weekly</a>
                  <a class="btn btn-circle btn-to-success column_filter"  onclick="setFilter('monthly')">Monthly</a>
                  <input type="hidden" name="q_date" id="q_date">
               </div>
                  <div class="col-sm-7 col-lg-3 controls" >
                  <div class="inner-addon input-group">
                  
                      <input type="text" class="form-control" id ="from_date" name="from_date" placeholder="From" data-rule-required="true"  />
                      <span class="input-group-addon" id="perc-addon"><i class="fa fa-calendar"></i></span>
                      
                      </div>
                  </div>
                  <div class="col-sm-7 col-lg-3 controls" >
                  <div class="inner-addon input-group">
                      <input type="text" class="form-control" id="to_date" name="to_date" placeholder="To" data-rule-required="true"/>
                      <span class="input-group-addon" id="perc-addon"><i class="fa fa-calendar"></i></span>
                      
                      </div>
                  </div>
                   <div >
          <a class="btn btn-circle btn-to-success search" onclick="filterData()">Search</a>
          <a class="btn btn-circle btn-to-success reset" href="{{$module_url_path}}?report_type=ride">Reset</a>
          <a class="btn btn-circle btn-to-success print" onclick="generate_ride_excel()" >Print</a>
          </div>
            </div><br>

          <div class="table-responsive" style="border:0">      
              <input type="hidden" name="multi_action" value="" />
                <table class="table table-advance"  id="table_module">
                  <thead>
                    <tr>  
                      <th><a class="sort-desc" href="#">Ride ID</a>
                         
                      </th> 
                      
                      <th><a class="sort-desc" href="#">Date </a>
                            
                      </th> 

                      <th><a class="sort-desc" href="#">Driver Name</a>
                        
                      </th> 

                      <th><a class="sort-desc" href="#">Rider Name</a>
                          
                      </th> 
                      
                      <th><a class="sort-desc" href="#">Vehicle Type</a>
                        
                      </th>

                      <th><a class="sort-desc" href="#">Pick Location</a>
                      </th> 

                      <th><a class="sort-desc" href="#">Drop Location</a>
                      </th> 

                      <th><a class="sort-desc" href="#">Fair Charge</a>
                      </th>

                      <th><a class="sort-desc" href="#">Distance</a>
                      </th>

                      <th><a class="sort-desc" href="#">Total Amount</a>
                      </th>

                      <th><a class="sort-desc" href="#">Discount Amt</a>
                          
                      </th>

                      <th><a class="sort-desc" href="#">Rider Paid Amt</a>
                          
                      </th> 

                      <th><a class="sort-desc" href="#">Driver Amt</a>
                          
                      </th>

                      <th><a class="sort-desc" href="#">Admin Amt</a>
                      </th>


                      {{-- <th><a class="sort-desc" href="#">Final Amount</a>
                      </th> --}}

                      {{-- <th><a class="sort-desc" href="#">Payment Status</a>
                      </th> --}}

                      <th><a class="sort-desc" href="#">Ride Status</a>
                      </th>

                    {{--   <th>Action</th> --}}

                    </tr>
                  </thead>
               </table>
            </div>

          <div> </div>
         
          {!! Form::close() !!}
      </div>
  </div>
</div>

<script type="text/javascript">
      function change_default_type()
      {
        var user_type = $('#user_type').val();
        var module_url_path = '{{$module_url_path}}';
        var url = module_url_path+'?report_type='+user_type;
        window.location.href = url;  
      }
      $('#q_date').datepicker({ 
             // dateFormat: "yy-mm-dd"
             dateFormat: "dd M yy"
      });

      /*Script to show table data*/
      var table_module = false;
      $(document).ready(function()
      {
        table_module = $('#table_module').DataTable({
          processing: true,
          serverSide: true,
          autoWidth: false,
          bFilter: false,
          ajax: {
          'url':'{{ $module_url_path.'/get_ride_records'}}',
          'data': function(d)
            {
              d['column_filter[date_filter]']       = $("input[name='q_date']").val(),
              d['column_filter[from_date]']         = $("input[name='from_date']").val(),
              d['column_filter[to_date]']           = $("input[name='to_date']").val()
            }
          },
          columns: [
          {data: 'ride_unique_id', "orderable": true, "searchable":true},
          {data: 'date', "orderable": false, "searchable":false},
          {data: 'driver_name', "orderable": true, "searchable":true},
          {data: 'rider_name', "orderable": true, "searchable":true},
          {data: 'vehicle_type', "orderable":false, " searchable":false},
          {data: 'pick_location', "orderable": true, "searchable":true},
          {data: 'drop_location', "orderable": true, "searchable":true},
          {data: 'driver_fair_charge', "orderable": true, "searchable":true},
          {data: 'distance', "orderable": true, "searchable":true},
          /*{data: 'final_amount', "orderable": true, "searchable":true},*/
          {
            render : function(data, type, row, meta) 
            {
              return row.total_amount;
            },
            "orderable": false, "searchable":false
          },
          {data: 'applied_promo_code_charge', "orderable": true, "searchable":true},
          {data: 'final_amount', "orderable": true, "searchable":true},
          {data: 'driver_amount', "orderable": false, "searchable":false},
          {
            render : function(data, type, row, meta) 
            {
              return row.admin_commission_amount;
            },
            "orderable": false, "searchable":false
          },
                
          
          // {
          //   render : function(data, type, row, meta) 
          //   {
          //     return row.final_amount;
          //   },
          //   "orderable": false, "searchable":false
          // },
          
          // {data: 'payment_status', "orderable": true, "searchable":true},
          {
            render : function(data, type, row, meta) 
            {
              return row.ride_status;
            },
            "orderable": false, "searchable":false
          },
          /*{
            render : function(data, type, row, meta) 
            {
              return row.build_action_btn;
            },
            "orderable": false, "searchable":false
          }*/

          ]
        });

         $('input.column_filter').on( 'keyup click', function () 
        {
            filterData();
        });

        $('#table_module').on('draw.dt',function(event)
        {
          var oTable = $('#table_module').dataTable();
          var recordLength = oTable.fnGetData().length;
          $('#record_count').html(recordLength);
        });
      });
  function filterData()
  {
    table_module.draw();
  }

  
  function generate_ride_excel()
  {
    var from_date = $("#from_date").val(); 
    var to_date   = $("#to_date").val();
    var report_type = $("#user_type").val();
    var url = "{{$module_url_path.'/generate_ride_excel?from_date='}}"+from_date+"&to_date="+to_date+"&report_type="+report_type;

    window.location.href=url;
  }

  $('#from_date').datepicker({ 
         dateFormat: "yy-mm-dd"
  });

 $('#to_date').datepicker({ 
         dateFormat: "yy-mm-dd"
  });

  function getData()
 {
    var val1 =$(".select_date").val();
    if(val1=="daily")
    {
        $("#from_date").val("<?php echo date("Y-m-d"); ?>");
        $("#to_date").val("<?php echo date("Y-m-d"); ?>");
    }
    else if(val1=="weekly")
    {
        var from_date = '<?php echo date('Y-m-d', strtotime('-7 days')); ?>';
        var to_date =   '<?php echo date('Y-m-d'); ?>';
         
        $("#from_date").val(from_date);
        $("#to_date").val(to_date); 
    }
    else
    {
        var from_date = '<?php echo date('Y-m-d', strtotime('first day of this month')); ?>';
        var to_date =   '<?php echo date('Y-m-d', strtotime('last day of this month')); ?>';

        $("#from_date").val(from_date);
        $("#to_date").val(to_date);
    }
    filterData();
 }

  function setFilter(param)
  {
    $("#q_date").val(param);
    var val1 =param;
    if(val1=="daily")
    {
        $("#from_date").val("<?php echo date("Y-m-d"); ?>");
        $("#to_date").val("<?php echo date("Y-m-d"); ?>");
    }
    else if(val1=="weekly")
    {
        var from_date = '<?php echo date('Y-m-d', strtotime('-7 days')); ?>';
        var to_date =   '<?php echo date('Y-m-d'); ?>';
         
        $("#from_date").val(from_date);
        $("#to_date").val(to_date); 
    }
    else
    {
        var from_date = '<?php echo date('Y-m-d', strtotime('first day of this month')); ?>';
        var to_date =   '<?php echo date('Y-m-d', strtotime('last day of this month')); ?>';

        $("#from_date").val(from_date);
        $("#to_date").val(to_date);
    }
    filterData();
  
  }

 </script>  
@stop