@extends('admin.layout.master')
<style>
    .form-group input{width: 100% !important;}
    .form-group label{display: block;}
    .form-group .form-control{width: 100%;}
    .form-group  .btn-primary.same_search{ width: auto !important;}
    .marn-tp{margin-top: 24px;}
    .form-group .chosen-container{width: 100% !important;}
    .form-group .control-label{margin-bottom: 5px;}
</style>
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

   <?php 

      $generated_by          = Request::input('generated_by');
      $query_type            = Request::input('query_type');
      $support_user          = Request::input('support_user');    
  ?>
  
  <!-- BEGIN Breadcrumb -->
  <div id="breadcrumbs">
    <ul class="breadcrumb">
      <li>
        <i class="fa fa-home"></i>
        <a href="{{url($admin_panel_slug.'/dashboard')}}">Dashboard</a>
        <span class="divider"><i class="fa fa-angle-right"></i></span>
      </li>

      <?php if(isset($user_id) && !empty($user_id)){?>
      <li>
        <i class="fa {{ $guest_user_icon }}"></i>
        <a href="{{$guest_user_path}}">Guest</a>
        <span class="divider"><i class="fa fa-angle-right"></i></span>
      </li>
      <?php } ?>

       <?php if(isset($host_user_id) && !empty($host_user_id)){?>
      <li>
        <i class="fa {{ $host_user_icon }}"></i>
        <a href="{{$host_user_path}}">Host</a>
        <span class="divider"><i class="fa fa-angle-right"></i></span>
      </li>
      <?php } ?>

      <li class="active"> <i class="fa {{$module_icon}}"></i>  {{$page_title or ''}}</li>
    </ul>
  </div>

  <!-- END Breadcrumb -->
  


  <!-- BEGIN Tiles -->
  <div class="row">
    <div class="col-md-12">
      <div class="box {{theme_color()}}">
        <div class="box-title">
          <h3><i class="fa {{$module_icon}}"></i> {{$page_title or ''}}</h3>
        </div>
        <div class="box-content">   

         <form name="frm-manage" id="frm-manage" method="get" action="{{$module_url_path}}/ticket"  class="form-inline" >

               <div class="form-group col-md-3">
                  <label>Generated By :</label>
                     <select class="form-control chosen-with-diselect" tabindex="-1" name="generated_by" id="generated_by">
                        <option value="">Select Generated By</option>
                          @if(isset($arr_generated_by) && count($arr_generated_by)>0)
                            @foreach($arr_generated_by as $generated_by)
                             <option value="{{ base64_encode($generated_by->id) }}" {{ ( Request::get('generated_by') != '' && Request::get('generated_by') == base64_encode($generated_by->id)) ? "selected" : '' }} > {{ $generated_by->generated_by_firstname."&nbsp;".$generated_by->generated_by_lastname}} 
                              </option>
                             @endforeach
                          @endif
                     </select>
               </div>

               <div class="form-group col-md-3">
                  <label>Query Type :</label>
                     <select class="form-control chosen-with-diselect" tabindex="-1" id="query_type" name="query_type">
                        <option value="">Select Query Type</option>
                          @if(isset($arr_query_type) && count($arr_query_type)>0)
                            @foreach($arr_query_type as $query_type)
                             <option value="{{ base64_encode($query_type->id) }}" {{ ( Request::get('query_type') != '' && Request::get('query_type') == base64_encode($query_type->id)) ? "selected" : '' }} > {{ $query_type->query_type}} 
                              </option>
                             @endforeach
                          @endif
                     </select>
               </div>

               <div class="form-group col-md-3">
                  <label>Support User :</label>
                     <select class="form-control chosen-with-diselect" tabindex="-1" name="support_user" id="support_user">
                        <option value="">Select User</option>
                          @if(isset($arr_support_user) && count($arr_support_user)>0)
                            @foreach($arr_support_user as $support_user)
                             <option value="{{ base64_encode($support_user->id) }}" {{ ( Request::get('support_user') != '' && Request::get('support_user') == base64_encode($support_user->id)) ? "selected" : '' }} > {{ $support_user->support_firstname."&nbsp;".$support_user->support_lastname }} 
                              </option>
                             @endforeach
                          @endif
                     </select>
               </div>

                
                 

              <div class="form-group col-md-3 marn-tp" >
                <input id="submit_filter" class="btn btn-primary same_search " type="submit" value="Search" name="btn_search"> &nbsp;
                <a href="{{$module_url_path}}/ticket" class="btn btn-default">Reset </a>
              </div>

              <input type="hidden" name="htoken" value="{{ csrf_token() }}">
              <input type="hidden" name="ticket_type" id="ticket_type" value="{{$ticket_type}}">
              <div class="clearfix"></div>
          </form>

        <form name="export" id="frm-export"  action="{{$module_url_path}}/ticket_export" method="get">

              <input type="hidden" name="e_generated_by" id="e_generated_by" value="">  
              <input type="hidden" name="e_query_type" id="e_query_type" value=""> 
              <input type="hidden" name="e_support_user" id="e_support_user" value=""> 
              <input type="hidden" name="records" id="records">
              
             <br>
              &nbsp;&nbsp;&nbsp;<div class="btn-group">
                 <button type="button" class="btn btn-default show-tooltip" id="getVal" title="Export"  onclick="getData()"
               style="text-decoration:none;background-color: #0090ff; color: rgba(255,255,255,0.9);">Export Report
                </button>
              </div>
        </form>


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
                    <th style="width:18px"> 
                      <input type="checkbox"  class="checked-all" name="mult_change" id="mult_change" value="delete" />
                    </th>
                    <th width="150px">Generated By</th>
                    <th>Query Type</th>
                    <th width="150px">Support User</th>
                    <th>Subject</th>
                    <th>Description</th>
                    <th width="100px">Date</th>
                    <th>Status</th>
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

    var generated_by        = $('#generated_by').val();
    var query_type          = $('#query_type').val();
    var support_user        = $('#support_user').val();
    var ticket_type         = $('#ticket_type').val();
    
    console.log(generated_by,query_type,support_user,ticket_type);

    var module_url_path     = "{{url($module_url_path)}}";
    var temp_url            = module_url_path+'/load_ticket_data';
    var url                 = temp_url.replace(/&amp;/g, '&'); 
    
    table_module            = $('#myTable').DataTable({

          "processing": true,
          "serverSide": true,
          "paging"    : true,
          "searching" : false,
          "ordering"  : true,
          "destroy"   : true,

      ajax: 
      {
        'url'       : temp_url,
        'data'      :  {'generated_by':generated_by,'query_type':query_type,'support_user':support_user,'ticket_type':ticket_type}

      
      },
      "order": [[ 1, "desc" ]],
      "columnDefs" : [
            { orderable: false, targets: [ 0,6] }
        ]
    });  
</script>

<script type="text/javascript">
$('#check-all').click(function (e) {
  $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
});

 function getData()
  { 
    var generated_by    = $('#generated_by').val();
    var query_type      = $('#query_type').val();
    var support_user    = $('#support_user').val();
   
  
    $('#e_generated_by').val(generated_by);
    $('#e_query_type').val(query_type);
    $('#e_support_user').val(support_user);
  
   
    $('#frm-export').submit();
  }


  $(document).ready(function(){
    $('#records').val('');
  });

  $(document).on('click','.checked-all', function(){    
    var yourArray = [];
    var records = $('#records').val();
      $('.checked_record').each(function(){
          yourArray.push($(this).val());
      });

      if($(this).prop('checked') == true){
      var checked_id = records+','+yourArray;
      $('#records').val(checked_id);
     }
     else{      
      var newValue = records.replace(','+yourArray, '');
      $('#records').val(newValue);
     }     

  });

  $(document).on('click','.checked_record' , function(){
     var records = $('#records').val();
     if($(this).prop('checked') == true){
      var checked_id = records+','+$(this).val();
      $('#records').val(checked_id);
     }
     else{
      var checked_id = $(this).val();
      var newValue = records.replace(','+checked_id, '');
      $('#records').val(newValue);
     }
  });
</script>
@stop