@extends('front.layout.master')                
@section('main_content')
    <div class="clearfix"></div>
    <div class="overflow-hidden-section">
        <div class="titile-user-breadcrum">
            <div class="container">
                <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions">
                    <h1>My Transaction</h1>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
 <?php 
      $from_date     = Request::input('from_date');
      $to_date       = Request::input('to_date');
  ?> 
        <div class="change-pass-bg">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 left-bar-dashboard">    
                        <div id="left-bar-host">                   
                            @include('front.layout.left_bar_host')
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                       @include('front.layout._flash_errors')   
                        @if(isset($my_report_data['data']) && !empty($my_report_data['data']))
                            <div class="user-tarnsaction-main">
                                <div class="change-pass-bady">
                                    <div class="transactions-table table-responsive">
                                        <!--div format starts here-->
                                        <div class="table">
                                            <div class="table-row heading">
                                                <div class="table-cell">Report ID</div>
                                                <div class="table-cell">Date</div>
                                                <div class="table-cell">Username</div>
                                                <div class="table-cell">From Date</div>
                                                <div class="table-cell">To Date</div>
                                                <div class="table-cell">Total Amount</div>
                                                <div class="table-cell">Status</div>
                                                <div class="table-cell">Action</div>
                                            </div>
                                            @foreach($my_report_data['data'] as $data)
                                              <?php
                                                $report_id      = isset($data->report_id)?$data->report_id : '-';
                                                $report_date    = isset($data->report_date) ? date('d M Y',strtotime($data->report_date)) : '-';
                                                $username       = isset($data->username) ? $data->username : '-';
                                                $fromdate       = isset($data->fromdate) ?date('d M Y',strtotime($data->fromdate)) : '-';
                                                $todate         = isset($data->todate) ?date('d M Y',strtotime($data->todate)) : '-';
                                                $total_amount   = isset($data->total_amount) ? $data->total_amount : '-';
                                                $status         = isset($data->status) ? $data->status : '-';
                                                $report_invoice = isset($data->report_invoice) ? $data->report_invoice : '';
                                              ?>
                                              <div class="table-row">
                                                  <div class="table-cell cargo-type">{{ $report_id }}</div>
                                                  <div class="table-cell tabe-discrip date">{{ $report_date }}</div>
                                                  <div class="table-cell vehical-category">{{ $username }}</div>
                                                  <div class="table-cell vehical-category">{{ $fromdate }}</div>
                                                  <div class="table-cell vehical-category">{{ $todate }}</div>
                                                  <div class="table-cell vehical-category">{{ $total_amount }} INR</div>
                                                  <div class="table-cell vehical-category">{{ $status }}</div>
                                                  <div class="table-cell vehical-category"><a href="{{ url('/') }}/uploads/invoice/{{ $report_invoice }}"><i class="fa fa-download"></i></a></div>
                                                 
                                              </div>
                                              <div class="clearfix"></div>
                                            @endforeach
                                        </div>
                                        <!--div format ends here-->
                                    </div>
                                </div>
                                
                                <div class="paginations">
                                    {{$page_link}}
                                </div>
                            </div>
                        @else
                            <div class="list-vactions-details">
                                <div class="no-record-found"></div>
                                <!-- <div class="content-list-vact" style="color: red;font-size: 13px;">
                                    <p>Sorry!, we couldn't find any Request!.</p>
                                </div> -->
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script type="text/javascript">
         $(function ()
         {
            $("#from_date").datepicker(
            {
                todayHighlight: true,
                autoclose: true,
            });
             $("#to_date").datepicker(
            {
                todayHighlight: true,
                autoclose: true,
            });
        });
          $("#from_date").datepicker({ dateFormat: 'yy-mm-dd' });
          $("#to_date").datepicker({ dateFormat: 'yy-mm-dd' });

          function getData()
          { 
        
            var from_date      = $('#from_date').val();
            var to_date        = $('#to_date').val();
          
            $('#e_from_date').val(from_date);
            $('#e_to_date').val(to_date);
           
            $('#frm_export_transaction').submit();
          }

         

    $("#btn_search").add('#getVal').on("click", function(){  
       
        var from_date       = $('#from_date').val();
        var to_date         = $('#to_date').val();

        var flag    = 1;
      
        if(from_date == '')
        {
           $("#err_from_date").html("Please select from date");      
           flag = 0;
        }
        else
        {
           $("#err_from_date").html("");      
        }

        if(to_date == '')
        {
           $("#err_to_date").html("Please select to date");      
            flag = 0;
        }
        else
        {
            $("#err_from_date").html("");      
        }

        if(from_date !='' && to_date != '')
        {
          if(from_date > to_date)
          {
             $("#err_from_date").html("Please select start date which is less then end date");      
             flag = 0;
          }
          else
          {
             $("#err_from_date").html("");   
          }
        }

        if(flag==0)
        {
          return false;
        }
        else
        {
          $('#e_report_from_date').val(from_date);
          $('#e_report_to_date').val(to_date);

          return true; 
        }
  });

    </script>


@endsection
