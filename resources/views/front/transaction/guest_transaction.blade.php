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
            $search_date = Request::input('search_date');
            $keyword     = Request::input('keyword');
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
                        if(Request::get('sort_by')==null || Request::get('sort_by')=="DESC") {
                            $sort_by = "ASC";
                        } else {
                            $sort_by = "DESC";
                        }
                    ?>
                <form action="{{ $module_url_path }}/search" method="get" name="frm_search_transaction" id="frm_search_transaction">

                    <div  class=" col-sm-8 col-md-3 col-lg-3">    
                        <div class="form-group">    
                               <input id="search_date" class="datepicker-input"  name="search_date" type="text" placeholder="Search Date" value="{{$search_date}}"/>
                        </div>
                    </div>
                    <div  class=" col-sm-8 col-md-3 col-lg-3">    
                        <div class="form-group">    
                               <input id="keyword" name="keyword" type="text" placeholder="Search keyword" value="{{$keyword}}"/>
                        </div>
                    </div>

                    <input type="hidden" name="field_name" id="field_name" value="{{Request::get('field_name')}}"> 
                    <input type="hidden" name="sort_by" id="sort_by" value="{{Request::get('sort_by')}}">
                    
                    <div class=" col-sm-6 col-md-3 col-lg-3">    
                        <div class="transac-search">
                            <button type="submit" class="btn-cancel tran-sear"><i class="fa fa-search"></i></button>
                            <a class="btn-pays tran-pays" href="{{ $module_url_path }}"><i class="fa fa-retweet"></i></a>
                        </div>
                   </form>      
                         <form action="{{ $module_url_path }}/export" method="get" name="frm_export_transaction" id="frm_export_transaction">
                    
                      <input type="hidden" name="e_search_date" id="e_search_date" value=""> 
                      <input type="hidden" name="e_keyword" id="e_keyword" value="">
                     
                        <div class="transa-btn-main expert">                              
                            <button class="transa-btn" onclick="getData()"><i class="fa fa-file-excel-o"></i>&nbsp; Export</button>
                        </div>
                        </form>
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
                                                <div class="table-cell sort-option" data-type="transaction_id" data-sort="{{$sort_by}}">Transaction Id <i class="fa fa-sort"></i></div>
                                                <div class="table-cell sort-option" data-type="transaction_for" data-sort="{{$sort_by}}">Transaction For <i class="fa fa-sort"></i></div>
                                                <div class="table-cell sort-option" data-type="transaction_date" data-sort="{{$sort_by}}">Date <i class="fa fa-sort"></i></div>
                                                <div class="table-cell sort-option" data-type="amount" data-sort="{{$sort_by}}">Amount <i class="fa fa-sort"></i></div>
                                                <div class="table-cell review-head sort-option" data-type="payment_type" data-sort="{{$sort_by}}">Payment For <i class="fa fa-sort"></i></div>
                                                <div class="table-cell">Action</div>
                                            </div>

                                            @foreach($arr_transaction['data'] as $data)
                                                <?php 
                                                    $transaction_id   = isset($data['transaction_id']) ? $data['transaction_id'] : '-';
                                                    $transaction_date = isset($data['transaction_date']) ? get_added_on_date($data['transaction_date']) : '-';
                                                    $description      = isset($data['transaction_for']) ? $data['transaction_for'] : '-';
                                                 
                                                    $location         = isset($data['location']) ? $data['location'] : '-';
                                                    $payment_type     = isset($data['payment_type']) ? $data['payment_type'] : '-';

                                                    $currency_code    = isset($data['currency_code']) ? $data['currency_code'] : '';
                                                    $currency         = isset($data['currency']) ? " ".$data['currency'] : '';

                                                    $amount           = isset($data['amount']) ? $data['amount'] : '-';

                                                    if(isset($data['payment_type']) && $data['payment_type'] == 'booking') {
                                                        if($currency_code == 'INR') {
                                                            $amount = isset($data['amount']) ? number_format($data['amount'],'2','.','' ) : '';
                                                        } else {
                                                            $amount = isset($data['amount']) ? number_format(currencyConverterAPI($currency_code, 'INR', $data['amount']),'2','.','' ) : '';
                                                        }
                                                        $total_amount = $currency.$amount;
                                                    } else {
                                                        $amount       = isset($data['amount']) ? number_format($data['amount'],2) : '';
                                                        $total_amount = "&#8377;".$amount;
                                                    }

                                                    $session_currency = \Session::get('get_currency');
                                                    $currency_icon = \Session::get('get_currency_icon');
                                                    if( $session_currency != 'INR' ){
                                                        $currency_amount = currencyConverterAPI('INR', $session_currency, $data['amount']);
                                                    }
                                                    else {
                                                        $currency_amount = $data['amount'];
                                                    }

                                                    ?>
                                                <div class="table-row">
                                                    <div class="table-cell cargo-type">{{ $transaction_id }}</div>
                                                    <div class="table-cell tabe-discrip short date">{{ $description }}</div>
                                                    <div class="table-cell vehical-category">{{ $transaction_date }}</div>

                                                    
                                                    <div class="table-cell tabe-discrip short date"> {!! $currency_icon !!} {{ number_format($currency_amount, 2, '.', '') }} </div>
                                                 
                                                    <div class="table-cell compli-green reviews-rating"> {{ ucfirst($payment_type) }} </div>
                                                    <div class="table-cell actio-style action">
                                                        <a href="{{$module_url_path.'/transaction-details/'.base64_encode($data['id'])}}"><i class="fa fa-eye"></i></a>                                                        
                                                        <?php 
                                                        if(isset($data['invoice']) && !empty($data['invoice']) && $data['invoice'] != null && file_exists($invoice_path_base_path.$data['invoice'] )) {
                                                            $invoice_path_src = $invoice_path_public_path.$data['invoice'];
                                                            ?><a href="{{ $invoice_path_src }}" target="_blank"> <i class="fa fa-file-pdf-o"></i></a><?php
                                                        } ?>
                                                        
                                                    </div>
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
                                    <p>Sorry!, we couldn't find any Transaction!.</p>
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
            $("#search_date").datepicker(
            {
                todayHighlight: true,
                autoclose: true,
            });
        });
            
        $("#search_date").datepicker({ 
            dateFormat: 'yy-mm-dd',
            todayHighlight: true,
            format: 'dd-M-yyyy',
            autoclose: true 
        });

        function getData()
        {
            var search_date      = $('#search_date').val();
            var keyword          = $('#keyword').val();

            $('#e_search_date').val(search_date);
            $('#e_keyword').val(keyword);
            $('#frm_export_transaction').submit();
        }


        $('.sort-option').click(function() {
            var field_name = $(this).attr('data-type');
            var sort_by = $(this).attr('data-sort');

            $("#field_name").val(field_name);
            $("#sort_by").val(sort_by);

            $('#frm_search_transaction').submit();
        })
    </script>

@endsection
