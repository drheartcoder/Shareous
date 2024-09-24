@extends('front.layout.master')                
@section('main_content')
<div class="clearfix"></div>
<div class="overflow-hidden-section">
    <div class="titile-user-breadcrum">
        <div class="container">
            <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions">
                <h1>My Earnings</h1>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <?php 
        $from_date = Request::input('from_date');
        $to_date   = Request::input('to_date');
    ?> 
    <div class="change-pass-bg">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 left-bar-dashboard">    
                    <div id="left-bar-host">                   
                        @include('front.layout.left_bar_host')
                    </div>
                </div>
                <?php
                    $sort_by = "DESC";                                                 
                    if(Request::get('sort_by') == null || Request::get('sort_by') == "DESC") {
                        $sort_by = "ASC";
                    } else {
                        $sort_by = "DESC";
                    }
                ?>
                <form action="{{ $module_url_path }}/search" method="get" name="frm_search_transaction" id="frm_search_transaction">

                    <div  class="col-sm-8 col-md-3 col-lg-3">    
                        <div class="form-group earning-errer">    
                            <input id="from_date" class="datepicker-input"  name="from_date" type="text" placeholder="From Date" value="{{ $from_date }}"/>
                            <span class="error" id="err_from_date">{{ $errors->first('from_date')}}</span>
                        </div>
                    </div>
                    <input type="hidden" name="field_name" id="field_name" value="{{Request::get('field_name')}}"> 
                    <input type="hidden" name="sort_by" id="sort_by" value="{{Request::get('sort_by')}}">

                    <div  class="col-sm-8 col-md-3 col-lg-3">    
                        <div class="form-group earning-errer">    
                            <input id="to_date" class="datepicker-input"  name="to_date" type="text" placeholder="To Date" value="{{$to_date}}"/>
                            <span class="error" id="err_to_date">{{ $errors->first('to_date')}}</span>
                        </div>
                    </div>

                    <div class=" col-sm-6 col-md-3 col-lg-3">   
                        <div class="transac-search"> 
                            <button class="btn-cancel tran-sear" type="submit" id="btn_search" style="min-width: 40px;"><i class="fa fa-search"></i></button>
                            <a class="btn-pays tran-pays" href="{{ $module_url_path }}" style="min-width: 40px; padding:0px 6px 4px;"><i class="fa fa-retweet"></i></a>
                        </div>
                    </form>

                    <form action="{{ $module_url_path }}/export" method="get" name="frm_export_transaction" id="frm_export_transaction">
                        <input type="hidden" name="e_from_date" id="e_from_date" value=""> 
                        <input type="hidden" name="e_to_date" id="e_to_date" value="">

                        <div class="transa-btn-main">                               
                            <button class="transa-btn" onclick="getData()"><i class="fa fa-file-excel-o"> Export</i></button>
                        </div>
                    </form>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="earning-request-btn-bock">
                        <form name="request_to_admin" id="request_to_admin" action="{{$module_url_path}}/request_to_admin" method="get">

                            <input type="hidden" name="e_report_from_date" id="e_report_from_date" value="">
                            <input type="hidden" name="e_report_to_date" id="e_report_to_date" value="">
                            <button type="submit" class="btn btn-default show-tooltip" id="getVal" title="Payout Request" style="text-decoration:none;">Payout Request</button>
                        </form>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                    @include('front.layout._flash_errors')   
                    @if(isset($arr_transaction['data']) && !empty($arr_transaction['data']))
                    <div class="user-tarnsaction-main">
                        <div class="change-pass-bady">
                            <div class="transactions-table table-responsive">
                                <!--div format starts here-->
                                <div class="table">
                                    <div class="table-row heading">
                                        <div class="table-cell sort-option" data-type="transaction_id" data-sort="{{ $sort_by }}">Transaction ID <i class="fa fa-sort"></i></div>
                                        <div class="table-cell sort-option" data-type="booking_id" data-sort="{{ $sort_by }}">Booking Id <i class="fa fa-sort"></i></div>
                                        <div class="table-cell sort-option" data-type="transaction_date" data-sort="{{ $sort_by }}">Date <i class="fa fa-sort"></i></div>
                                        <div class="table-cell sort-option" data-type="amount" data-sort="{{ $sort_by }}">Booking <br>Amount <i class="fa fa-sort"></i></div>
                                        <div class="table-cell sort-option" data-type="admin_commission" data-sort="{{ $sort_by }}">Commission <i class="fa fa-sort"></i></div>
                                        <div class="table-cell sort-option">Total Earning</div>
                                    </div>
                                    @foreach($arr_transaction['data'] as $data)
                                    <?php
                                        $id = isset($data->id) ? $data->id : '-';
                                        $transaction_id   = isset($data->transaction_id) && !empty($data->transaction_id) ? $data->transaction_id : '-'; 
                                        $booking_id       = isset($data->booking_id) ? $data->booking_id : '-'; 
                                        $property_name    = isset($data->property_name) ? $data->property_name : '-'; 
                                        $transaction_date = isset($data->transaction_date) ? get_added_on_date($data->transaction_date) : '-'; 
                                        $amount  = isset($data->amount) ? $data->amount : '-'; 
                                        $admin_commission = isset($data->admin_commission) ? $data->admin_commission : '-';
                                        $currency         = isset($data->currency) ? $data->currency : '-';
                                        $currency_code    = isset($data->currency_code) ? $data->currency_code : '';

                                        if (isset($data->amount) && isset($data->admin_commission)) {
                                            $pro_amount = $data->amount;
                                            $commission = $data->admin_commission;
                                            $host_earn  = $admin_earn = 0;
                                            $admin_earn = ( $commission / 100 ) * $pro_amount;
                                            $host_earn  = $pro_amount - $admin_earn;
                                        } 

                                        if ($currency_code != 'INR') {
                                            $converttoINR = currencyConverterAPI($currency_code,'INR',$host_earn);
                                        } else {
                                            $converttoINR = $host_earn;
                                        }

                                        $session_currency = \Session::get('get_currency');
                                        $currency_icon = \Session::get('get_currency_icon');
                                        if( $session_currency != 'INR' ){
                                            $currency_property_amount = currencyConverterAPI('INR', $session_currency, $amount);
                                            $currency_host_earn = currencyConverterAPI('INR', $session_currency, $host_earn);
                                        }
                                        else {
                                            $currency_property_amount = $amount;
                                            $currency_host_earn = $host_earn;
                                        }
                                    ?>
                                    <div class="table-row">
                                        <div class="table-cell cargo-type">{{ $transaction_id }}</div>

                                        <div class="table-cell tabe-discrip date"><a href="{{ url('/').'/my-booking/new/booking-details/'.base64_encode($id) }}"><u>{{ $booking_id }}</u></a></div>

                                        <div class="table-cell vehical-category">{{ $transaction_date }}</div>
                                        <div class="table-cell vehical-category">{!!$currency_icon!!} {{ number_format($currency_property_amount,2) }}</div>

                                        <div class="table-cell vehical-category">{{ $admin_commission }}%</div>
                                        <div class="table-cell vehical-category">{!!$currency_icon!!} {{ number_format($currency_host_earn, 2) }}</div>
                                        <!-- <div class="table-cell vehical-category">&#8377;{{ number_format($converttoINR,2) }}</div> -->

                                    </div>
                                    <div class="clearfix"></div>
                                    @endforeach
                                </div>
                                
                            </div>
                        </div>

                        <div class="paginations">
                            {{ $page_link }}
                        </div>
                    </div>
                    @else
                        <div class="list-vactions-details">
                            <div class="no-record-found"></div>
                            <!-- <div class="content-list-vact" style="color: red;font-size: 13px;">
                                <p>Sorry!, we couldn't find any Earning!.</p>
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
    $(function (){
        $("#from_date").datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: true,
            format: 'dd-M-yyyy',
            autoclose: true
        });

        $("#to_date").datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: true,
            format: 'dd-M-yyyy',
            autoclose: true
        });
    });

    function getData()
    {
        var from_date = $('#from_date').val();
        var to_date   = $('#to_date').val();

        $('#e_from_date').val(from_date);
        $('#e_to_date').val(to_date);

        $('#frm_export_transaction').submit();
    }

    $("#btn_search").add('#getVal').on("click", function(){  
        var from_date = $('#from_date').val();
        var to_date   = $('#to_date').val();
        var flag      = 1;

        if (from_date == '') {
            $("#err_from_date").html("Please select from date");      
            flag = 0;
        } else {
            $("#err_from_date").html("");      
        }

        if (to_date == '') {
            $("#err_to_date").html("Please select to date");      
            flag = 0;
        } else {
            $("#err_from_date").html("");      
        }

        if (from_date !='' && to_date != '') {
            if (from_date > to_date) {
                $("#err_from_date").html("Please select start date which is less then end date");      
                flag = 0;
            } else {
                $("#err_from_date").html("");   
            }
        }

        if (flag == 0) {
            return false;
        } else {
            $('#e_report_from_date').val(from_date);
            $('#e_report_to_date').val(to_date);
            return true; 
        }
    });

    $('.sort-option').click(function(){
        var field_name = $(this).attr('data-type');
        var sort_by = $(this).attr('data-sort');

        $("#field_name").val(field_name);
        $("#sort_by").val(sort_by);

        $('#frm_search_transaction').submit();
    })
</script>

@endsection
