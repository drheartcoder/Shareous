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
           <li class="active">{{ isset($rating_module_title)?$rating_module_title:"" }}</li>
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
                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip refrash-btns" 
                   title="Refresh" 
                   href="{{ $module_url_path }}?report_type=rating"
                   style="text-decoration:none;">
                   <i class="fa fa-repeat"></i>
                </a> 
              </div>

              <br>
          </div>
          <div class="col-sm-5 col-lg-3">
              {{-- <a class="btn btn-circle btn-to-success column_filter"   onclick="setFilter('daily')">Daily</a>
              <a class="btn btn-circle btn-to-success column_filter"  onclick="setFilter('weekly')">Weekly</a>
              <a class="btn btn-circle btn-to-success column_filter"  onclick="setFilter('monthly')">Monthly</a>
              <input type="hidden" name="q_date" id="q_date"> --}}
            </div>
       
         {{--  <label class="col-sm-3 col-lg-2 control-label">User Type</label>
            <div class="col-sm-7 col-lg-2 controls" >
                <select class="form-control"  name="user_type" id="user_type" onchange="change_default_type()">
                  <option value="">Select User-Type</option>  
                  <option value="rider" >Rider</option>
                  <option value="driver">Driver</option>
                  <option value="ride">Ride</option>
                  <option value="rating" selected>Rating</option>
                </select>
            </div> --}}
          <div class="clearfix" ></div><br>
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
          <a class="btn btn-circle btn-to-success reset" href="{{$module_url_path}}?report_type=rating" >Reset</a>
          <a class="btn btn-circle btn-to-success print" onclick="generate_rating_excel()" >Print</a>
          </div>
            </div><br>     
           <div class="table-responsive" style="border:0">      
              <input type="hidden" name="multi_action" value="" />
                <table class="table table-advance"  id="table_module">
                  <thead>
                    <tr>  
                                                
                        <th style="width: 18px; vertical-align: initial;">ID</th>
            
                        <th><a class="sort-desc" href="#">Rating Date </a>

                        <th><a class="sort-desc" href="#">From user Name </a>
                            {{-- <input type="text" name="q_name" placeholder="Search" class="search-block-new-table column_filter" /> --}}
                        </th> 

                        <th><a class="sort-desc" href="#">To user Name </a>
                            
                      </th>

                        {{-- <th><a class="sort-desc" href="#">Ride id </a>
                           
                        </th>  --}}

                        <th><a class="sort-desc" href="#">User Type </a>
                            {{-- <input type="text" name="q_contact_number" placeholder="Search" class="search-block-new-table column_filter" /> --}}
                        </th>
                        
                        <th><a class="sort-desc" href="#">Rating </a>
                            
                        </th>
                        
                        {{-- <th  width="150px">Action</th> --}}
                        
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
      var table_module = false;
      $(document).ready(function()
      {
         
        var user_type = $('#user_type').val();
       
        table_module = $('#table_module').DataTable({
          processing: true,
          serverSide: true,
          autoWidth: false,
          bFilter: false,
          ajax: {
          'url':'{{ $module_url_path.'/get_rating_records?role='}}{{$role or ''}}',
          'data': function(d)
            {
               d['column_filter[user_type]']        = $("select[name='user_type']").val(),
               d['column_filter[date_filter]']      = $("input[name='q_date']").val(),
              d['column_filter[from_date]']         = $("input[name='from_date']").val(),
              d['column_filter[to_date]']           = $("input[name='to_date']").val()
            }
          },
          columns: [
          {
           
            render : function(data, type, row, meta) 
            {
                  // return '<div class="check-box"><input type="checkbox" class="filled-in case" name="checked_record[]"  id="mult_change_'+row.enc_id+'" value="'+row.enc_id+'" /><label for="mult_change_'+row.enc_id+'"></label></div>';
                  return row.id+' )';

            },
            "orderable": false,
            "searchable":false
          },
          {data: 'rating_register_date', "orderable": false, "searchable":false},
          {data: 'from_user_name', "orderable": false, "searchable":false},
          {data: 'to_user_name', "orderable": false, "searchable":false},
          /*{data: 'ride_id', "orderable": false, "searchable":false},*/
          {data: 'user_type', "orderable": false, "searchable":false},
          {
            render : function(data, type, row, meta) 
            {
              return row.rating;
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
 </script> 

<!-- END Main Content -->
<script type="text/javascript">

 function filterData()
  {
    table_module.draw();
  }
  $('#from_date').datepicker({ 
         dateFormat: "yy-mm-dd"
  });

  function generate_rating_excel()
  {
    var from_date = $("#from_date").val(); 
    var to_date   = $("#to_date").val();
    var report_type = $("#user_type").val();
    var url = "{{$module_url_path.'/generate_rating_excel?from_date='}}"+from_date+"&to_date="+to_date+"&report_type="+report_type;

    window.location.href=url;
  }

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