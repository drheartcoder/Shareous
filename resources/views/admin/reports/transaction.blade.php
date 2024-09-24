@extends('admin.layout.master')                

    @section('main_content')

     <!-- BEGIN Page Title -->
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/data-tables/latest/dataTables.bootstrap.min.css">

    <div class="page-title">
        <div>
            <h1>
                <i class="fa fa-file-text"></i>
                {{ isset($page_title)?$page_title:"" }}
            </h1>
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
          <i class="fa {{$module_icon}}"></i>
          </span>
          <li class="active">{{ $page_title or ''}}</li>
       </ul>
    </div>
    <!-- END Breadcrumb -->

    <form class="form-horizontal"  id="form-validate" method="GET" action="{{$module_url_path}}/get_report" >

      {{csrf_field()}}

      <input type="hidden" id="report_type" name="report_type" value="{{isset($report_type) ? $report_type : 0}}">

      <div class="form-group">
            <label class="col-sm-3 col-lg-1 control-label">Start date<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-2 controls">
                <input type="text" id="start_date"  data-rule-required="true" placeholder="Start Date" class="date-picker form-control" name="start_date" value="{{isset($start_date) && $start_date!=''?$start_date:''}}">
                <span class="help-block" id="err_start_date"></span>
            </div>
      </div>

      <div class="clearfix"></div>
      <div class="form-group">
            <label class="col-sm-3 col-lg-1 control-label">End date<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-2 controls">
                <input type="text"  id="end_date"  data-rule-required="true" placeholder="End Date" class="date-picker form-control" name="end_date" value="{{isset($end_date) && $end_date!=''?$end_date:''}}">
                <span class="help-block" id="err_end_date"></span>
            </div>
      </div>

      <div class="clearfix"></div>
      @if(isset($report_type) && $report_type == 'user')
        <div class="form-group">
          <label class="col-sm-3 col-lg-1 control-label">User Type<i class="red">*</i></label>
          <div class="col-sm-9 col-lg-2 controls">
            <select class="form-control" name="type" id="user_type" data-rule-required="true"> 
            <option value="">Select Type</option>
             <option value="customer" @if(isset($flag) && $flag=='customer') selected="" @endif>Customer</option>
             <option value="translator" @if(isset($flag) && $flag=='translator') selected="" @endif >Translator</option>
             <option value="interpreter" @if(isset($flag) && $flag=='interpreter') selected="" @endif >Interpreter</option>
             </select>
              <span class="help-block" id="err_user_type">{{ $errors->first('type') }}</span>
          </div>
        </div>
      @elseif(isset($report_type) && $report_type == 'transaction')
        <div class="form-group">
          <label class="col-sm-3 col-lg-1 control-label">Transaction Type<i class="red">*</i></label>
          <div class="col-sm-9 col-lg-2 controls">
            <select class="form-control" name="type" id="user_type" data-rule-required="true"> 
            <option value="">Select Type</option>
             <option value="1" @if(isset($flag) && $flag=='1') selected="" @endif>Approved</option>
             <option value="2" @if(isset($flag) && $flag=='2') selected="" @endif >Paid</option>
             <option value="0" @if(isset($flag) && $flag=='0') selected="" @endif >Pending</option>
             <option value="3" @if(isset($flag) && $flag=='3') selected="" @endif >Failed</option>
             </select>
              <span class="help-block" id="err_user_type">{{ $errors->first('type') }}</span>
          </div>
        </div>
      @endif

      <div class="clearfix"></div>
        
      <input type="hidden" name="role_type" id="role_type" value="{{ isset($role_type)?$role_type:'' }}">
      <input type="hidden" name="is_active" id="is_active" value="{{ isset($is_active)?$is_active:'' }}">
      <div class="clearfix"></div>

      <div class="col-sm-9 col-lg-3 controls">
        <input type="submit" class="btn btn btn-primary" value="Apply">
      </div>

      <div class="clearfix"></div>

      </br></br>
    </form>

    <!-- BEGIN Main Content -->
    <div class="row">
      <div class="col-md-12">

          <div class="box">
            <div class="box-title">
              <h3>
                <i class="fa fa-file-text"></i>
                {{ isset($page_title)?$page_title:"" }}
            </h3>
            <div class="box-tool">
                <a data-action="collapse" href="#"></a>
                <a data-action="close" href="#"></a>
            </div>
        </div>
       
     <div class="box-content">
      @if(isset($arr_transaction) && sizeof($arr_transaction)>0)
      <div class="btn-toolbar pull-right">
            <button class="btn btn-primary" id="export_btn"> Export To CSV</button>
      </div>
      @endif
      <br/><br/>
      <div class="clearfix"></div>
      <form id="frm_download_report" name="frm_download_report" method="POST" action="{{$module_url_path}}/download">
        {{csrf_field()}}
          <input type="hidden" value="{{$report_type or ''}}" name="report_type" id="report_type">
          <input type="hidden" value="{{$start_date or ''}}" name="start_date" id="start_date">
          <input type="hidden" value="{{$end_date or ''}}" name="end_date" id="end_date">
          <input type="hidden" value="{{$type or ''}}" name="type" id="type">
          <input type="hidden" name="records" id="records">
        </form>
         <form class="form-horizontal">
       
              <div class="table-responsive" style="border:0">
                <table class="table table-advance"  id="reports_table" >
                   <thead>
                          <tr>
                            <th>Job Title</th> 
                            <th>Transaction by</th> 
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Transaction Date</th>
                          </tr>
                   </thead>
                   <tbody>
                       @if(isset($arr_transaction) && sizeof($arr_transaction)>0)
                        @foreach($arr_transaction as $transaction) 
                      
                        <tr>
                           <td>  {{ isset($transaction['title'])?$transaction['title']:'NA' }}</td>
                           <td> 
                           	  @if(isset($transaction['transaction_type']) && $transaction['transaction_type']  == 3)
                                  Admin
                              @else
                           		{{ isset($transaction['first_name'])?$transaction['first_name']:'' }} {{ isset($transaction['last_name'])?$transaction['last_name']:'' }} </td>
                           	  @endif
                           <td>
                              @php  $currency_symbol = isset($transaction['currency']) ? $transaction['currency'] : ' '; @endphp 
                              
                              @if(isset($transaction['payment_type']) && $transaction['payment_type'] == 1)
                                  {{isset($transaction['due_amount']) ? $currency_symbol.$transaction['due_amount']:''}}
                              @elseif(isset($transaction['payment_type']) && $transaction['payment_type'] == 2)
                                  {{isset($transaction['refund_amt']) ? $currency_symbol.$transaction['refund_amt']:''}}
                              @elseif(isset($transaction['payment_type']) && $transaction['payment_type'] == 3)
                                  {{isset($transaction['wages_amt']) ? $currency_symbol.$transaction['wages_amt']:''}}
                              @else
                                  {{ isset($transaction['payment_amount'])? $currency_symbol.$transaction['payment_amount']:'' }}   
                              @endif
                            </td>
                           <td> 
                              @if(isset($transaction['payment_status']) && $transaction['payment_status'] != '')
                                  {{$transaction['payment_status'] == 1 ? 'Approved' : ''}}
                                  {{$transaction['payment_status'] == 0 ? 'Pending' : ''}}
                                  {{$transaction['payment_status'] == 2 ? 'Paid' : ''}}
                                  {{$transaction['payment_status'] == 3 ? 'Failed' : ''}}
                              @endif
                            </td>
                           <td> @if(isset($transaction['payment_date'])) {{ $transaction['payment_date'] }} @endif</td>
                        </tr>

                        @endforeach
                        @endif
                   </tbody>
                </table>
              </div>
            <div>  
         </div>
        </form>
      
 </div>
 </div>
</div>
<!-- END Main Content -->

<script type="text/javascript">
  // $(function () 
  // {
  //     $("#end_date").datepicker({       
  //       dateFormat: 'dd-mm-yy',
  //       changeMonth: true,
  //       changeYear: true,
  //       maxDate: 0,
  //       autoclose: true
  //     });
  //   });

  $(document).ready(function(){
    $('#records').val('');
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

$(document).ready(function(){

$('#end_date').datepicker(
  {
            maxDate: 0,
  });
$('#export_btn').click(function()
{

  $('#start_date').datepicker( {

      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true,
      maxDate: new Date(),
  });

  $('#end_date').datepicker( {
       'startDate': new Date(),
      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true,
      maxDate: new Date(),
  });

  $('#start_date').on('change',function(event)
  {
    $('#end_date').datepicker('option', 'minDate', $('#start_date').datepicker('getDate'));
  });
  $('#frm_download_report').submit();
 });
});


$('#form-validate').submit(function(){

    var flag               = true;
    var start_date         = $('#start_date').val();
    var end_date           = $('#end_date').val();
    var type1               = $('#user_type').val();
    $('#err_start_date').html('');
    $('#err_end_date').html('');
       
    var myDate=start_date;
    myDate=myDate.split("/");
    var startDate=myDate[1]+","+myDate[0]+","+myDate[2];
    
    var myDate=end_date;
    myDate=myDate.split("/");
    var endDate=myDate[1]+","+myDate[0]+","+myDate[2];
    
    if(startDate > endDate)
    {
       $('#err_diff_date').html('End date must be greater than Start date');
       var flag = false;
    }
   if(start_date=='')
   {
      $('#err_start_date').html('This field is required.');
      flag = false;
   }
   if(end_date=='')
   {
      $('#err_end_date').html('This field is required.');
      flag = false;
   }
   if(type1=='')
   {
      $('#err_user_type').html('This field is required.');
      flag = false;
   }

   return flag;

  });

</script>

<script>
  $(document).ready(function() 
     {
       var oTable = $('#reports_table').dataTable({
      "ordering": true,
       "iDisplayLength": -1,
       "aaSorting": [[ 4, "desc" ]]  
    
     });
   });
</script>

@stop                    


