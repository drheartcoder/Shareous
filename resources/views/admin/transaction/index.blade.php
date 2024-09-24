

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
  $user_id          = Request::input('user_id');
  $user_name        = Request::input('user_name');
  $transaction_id   = Request::input('transaction_id');
  $transaction_date = Request::input('transaction_date');
?> 

  <!-- BEGIN Tiles -->
  <div class="row">
    <div class="col-md-12">
      <div class="box {{theme_color()}}">
        <div class="box-title">
          <h3><i class="fa {{ $module_icon }}"></i> {{ $page_title or '' }}</h3>
        </div>
        <div class="box-content">         
          <form name="frm-manage" id="frm-manage" method="get" action="{{$module_url_path}}"  class="form-inline" >

                <div class="form-group col-md-3" style="margin-top:6px;">
                  <label>Username :</label>
                  <select class="form-control chosen-with-diselect" tabindex="-1" id="user_name" name="user_name">
                      <option value="">--Select Username--</option>
                        @if(isset($arr_user) && count($arr_user)>0)
                          @foreach($arr_user as $user)
                           <option value="{{ base64_encode($user->id) }}" {{ ( Request::get('user_name') != '' && Request::get('user_name') == base64_encode($user->id)) ? "selected" : ''}} > {{ $user->user_name }} 
                            </option>
                           @endforeach
                        @endif
                  </select>
                </div>
                
                <div class="form-group col-md-3" style="margin-top:6px;">
                    <label >Transaction Id: </label>
                    <input type="text"  placeholder="Transaction Id"  id="transaction_id" name="transaction_id" class="form-control"  value="{{ $transaction_id}}"  >
                </div>

                 <div class="form-group col-md-3" style="margin-top:6px;">
                    <label >Transaction Date: </label>
                    <input type="text"  placeholder="Transaction Date" class="date-picker form-control" id="transaction_date" name="transaction_date" class="form-control"  value="{{ $transaction_date}}" >
                </div>
                <input type="hidden"  id="user_id" name="user_id"  value="{{ $user_id}}"  >

                <div class="form-group" >
                    <input id="submit_filter" class="btn btn-primary same_search" type="submit" value="Search" name="btn_search"> &nbsp;
                    <a href="{{$module_url_path}}" class="btn btn-default">Reset </a>
                </div>
             
              <input type="hidden" name="htoken" value="{{ csrf_token() }}">
              <div class="clearfix"></div>
        </form>

        <form name="export" id="frm-export" action="{{$module_url_path}}/export" method="get">
              <input type="hidden" name="e_user_id" id="e_user_id" value="">  
              <input type="hidden" name="e_user_name" id="e_user_name" value="">  
              <input type="hidden" name="e_transaction_id" id="e_transaction_id" value=""> 
              <input type="hidden" name="e_transaction_date" id="e_transaction_date" value="">
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
                     <!--   <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Multiple Unblock" href="javascript:void(0);" onclick="javascript : return check_multi_action('frm_manage','activate');" style="text-decoration:none;">
                        <i class="fa fa-unlock"></i>
                      </a> 
                      <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip btn-dangers" title="Multiple Block" href="javascript:void(0);" onclick="javascript : return check_multi_action('frm_manage','deactivate');"  style="text-decoration:none;">
                        <i class="fa fa-lock"></i>
                      </a>   -->
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
                        <input type="checkbox" class="checked-all" name="mult_change" id="mult_change" value="delete" />
                      </th>
                      <th>User Name</th>
                      <th>Transaction ID</th>
                      <th>Transaction For</th>
                      <th>Total Amount</th>
                      <th>Commission</th>
                      <th>Transaction Date</th>
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

$("#transaction_date").datepicker({ dateFormat: 'yy-mm-dd' });

    var user_id            = $('#user_id').val();
    var user_name          = $('#user_name').val();
    var transaction_id     = $('#transaction_id').val();
    var transaction_date   = $('#transaction_date').val();

    var module_url_path    = "{{url($module_url_path)}}";
    var temp_url           = module_url_path+'/load_data';

    var url                = temp_url.replace(/&amp;/g, '&'); 
    
    table_module           = $('#myTable').DataTable({
          "processing": true,
          "serverSide": true,
          "paging": true,
          "searching":false,
          "ordering": true,
          "destroy": true,
      ajax: 
      {
        'url'     : temp_url,
        'data'    : {'user_id':user_id,'user_name':user_name,'transaction_id':transaction_id,'transaction_date':transaction_date}
      },
      "order": [[ 6, "desc" ]],
       "columnDefs": [
            { orderable: false, targets: [ 0, 2, 3 ] }
        ]
    });  
</script>

<script type="text/javascript">

  $('#check-all').click(function (e) {
    $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
  });
  
</script>

<script type="text/javascript">
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


  function getData()
  { 
    var user_id           = $('#user_id').val();   
    var user_name         = $('#user_name').val();    
    var transaction_id    = $('#transaction_id').val();
    var transaction_date  = $('#transaction_date').val();
  
    $('#e_user_id').val(user_id);
    $('#e_user_name').val(user_name);
    $('#e_transaction_id').val(transaction_id);
    $('#e_transaction_date').val(transaction_date);
    $('#frm-export').submit();
  }
</script>


    @stop