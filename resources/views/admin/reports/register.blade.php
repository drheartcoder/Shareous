@extends('admin.layout.master')
@section('main_content')
<!-- BEGIN Content -->
<style>
    .form-group input{width: 100% !important;}
    .form-group label{display: block;}
    .form-group .form-control{width: 100%;}
    .form-group  .btn-primary.same_search{ width: auto !important;}
    .marn-tp{margin-top: 24px;}
    .form-group .control-label{margin-bottom: 5px;}
</style>
<div id="main-content">
  <!-- BEGIN Page Title -->
  <div class="page-title">
    <div>
      <h1><i class="fa {{$module_icon}}"></i> {{$page_title or ''}}</h1>
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
  <?php 
      $start_date = Request::input('start_date');
      $end_date   = Request::input('end_date');
      //$user_type  = Request::input('user_type');
  ?>

  <!-- BEGIN Tiles -->
  <div class="row">
    <div class="col-md-12">
      <div class="box {{theme_color()}}">
        <div class="box-title">
          <h3><i class="fa {{$module_icon}}"></i> {{$page_title or ''}}</h3>
        </div>
        <div class="box-content">         
          <form name="frm-manage" id="frm-manage" method="get" action="{{$module_url_path}}/register"  class="form-inline" >

                <div class="form-group col-md-3">
                    <label>Start date<i class="red">*</i></label>
                    <input type="text" id="start_date"  data-rule-required="true" placeholder="Start Date" class="date-picker form-control" name="start_date" value="{{isset($start_date) && $start_date!=''?$start_date:''}}">
                    <span class="help-block" id="err_start_date">{{ $errors->first('start_date')}}</span>  
                </div>

                <div class="form-group col-md-3">
                    <label>End date<i class="red">*</i></label>
                    <input type="text"  id="end_date"  data-rule-required="true" placeholder="End Date" class="date-picker form-control" name="end_date" value="{{isset($end_date) && $end_date!=''?$end_date:''}}">
                    <span class="help-block" id="err_end_date">{{ $errors->first('end_date')}}</span>
               </div>

                <div class="form-group col-md-3 marn-tp">
                  <input class="btn btn-primary same_search" type="submit" value="Search" id="btn_search">&nbsp;
                  <a href="{{$module_url_path}}/register" class="btn btn-default">Reset </a>
                </div>
              <div class="clearfix"></div>
          </form>


        <form name="export" id="frm-export"  action="{{$module_url_path}}/export" method="get">

              <input type="hidden" name="e_start_date" id="e_start_date" value="">  
              <input type="hidden" name="e_end_date" id="e_end_date" value=""> 
              <!-- <input type="hidden" name="e_user_type" id="e_user_type" value="">  -->
              <input type="hidden" name="records" id="records">
             <br>
              &nbsp;&nbsp;&nbsp;<div class="btn-group">
                 <button type="button" class="btn btn-default show-tooltip" id="getVal" title="Export"  onclick="getData()"
               style="text-decoration:none;background-color: #0090ff; color: rgba(255,255,255,0.9);">Export Report
                </button>
              </div>
        </form>

        

     <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{$module_url_path}}/multi_action">
              {{ csrf_field() }}
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
              <input type="hidden" value="{{$report_type or ''}}" name="report_type" id="report_type">
              <input type="hidden" value="{{$start_date or ''}}" name="start_date" id="start_date">
              <input type="hidden" value="{{$end_date or ''}}" name="end_date" id="end_date">
              <!-- <input type="hidden" value="{{$user_type or ''}}" name="user_type" id="user_type">    -->

              <div class="table-responsive" style="border:0">              
                <table id="myTable" class="table table-advance">
                  <thead>
                    <tr>
                     <th style="width:18px"> 
                        <input type="checkbox"  class="checked-all" name="mult_change" id="mult_change" value="delete" />
                      </th>
                        <th>Name</th> 
                        <th>Email</th> 
                        <th>Mobile no.</th>
                        <th>Address</th>
                        <th>Registration Date</th>
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

  $("#start_date").datepicker({ dateFormat: 'yy-mm-dd' });
  $("#end_date").datepicker({ dateFormat: 'yy-mm-dd' });
  
    var start_date      = $('#start_date').val();
    var end_date        = $('#end_date').val();
  
    var module_url_path = "{{url($module_url_path)}}";
    var temp_url        = module_url_path+'/load_register_data';
    var url             = temp_url.replace(/&amp;/g, '&');

    table_module        = $('#myTable').DataTable({
          "processing": true,
          "serverSide": true,
          "paging":     true,
          "searching":  false,
          "ordering":   true,
          "destroy":    true,
      ajax: 
      {
        'url' : temp_url,
        'data' : {'end_date':end_date, 'start_date':start_date }
      },
      "order": [[ 1, "desc" ]],
      "columnDefs" : [
            { orderable: false, targets: [ 0 ] }
        ]   
    }); 

  $('#check-all').click(function (e) {
    $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
  });
  
  $("#btn_search").on("click", function(){
      var start_date = $('#start_date').val();
      var end_date   = $('#end_date').val();
      var flag       = 1;
    
      if(start_date == '')
      {
        $("#err_start_date").html("Please select start date");      
        $('input[name="start_date"]').on('change',function(){ $("#err_start_date").html("");});
        $('input[name="end_date"]').focus();
        flag = 0;
      }

      if(end_date == '')
      {
        $("#err_end_date").html("Please select end date");      
        $('input[name="end_date"]').on('change',function(){ $("#err_end_date").html("");});
        $('input[name="end_date"]').focus();
        flag = 0;
      }

      if(start_date != '' && end_date != '')
      {
        if(start_date > end_date)
        {
          $("#err_start_date").html("Please select start date which is less then end date");      
          $('input[name="end_date"]').on('change',function(){ $("#err_end_date").html(""); });
          $('input[name="end_date"]').focus();
          flag = 0;
        }
      }

      if(flag==1)
      {
        return true;
      }
      else
      {
        return false; 
      }
  });

  $(document).ready(function(){
    $('#records').val('');
  });

  $(document).on('click','.checked-all', function(){    
    var yourArray = [];
    var records = $('#records').val();
    $('.checked_record').each(function() {
      yourArray.push($(this).val());
    });

    if($(this).prop('checked') == true) {
      var checked_id = records+','+yourArray;
      $('#records').val(checked_id);
    } else {
      var newValue = records.replace(','+yourArray, '');
      $('#records').val(newValue);
    }
  });

  $(document).on('click','.checked_record' , function(){
    var records = $('#records').val();
    if($(this).prop('checked') == true) {
      var checked_id = records+','+$(this).val();
      $('#records').val(checked_id);
    }
    else {
      var checked_id = $(this).val();
      var newValue = records.replace(','+checked_id, '');
      $('#records').val(newValue);
    }
  });

  function getData()
  { 
    var start_date = $('#start_date').val();
    var end_date   = $('#end_date').val();
    $('#e_start_date').val(start_date);
    $('#e_end_date').val(end_date);
    $('#frm-export').submit();
  }

</script>

@stop