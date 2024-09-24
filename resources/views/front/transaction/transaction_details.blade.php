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
<div class="change-pass-bg">
   <div class="container">
      <div class="row">
         <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 left-bar-dashboard">
            <div id="left-bar-host">                   
               @include('front.layout.left_bar_host')
            </div>
         </div>
         <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
            <div class="transact-deta-back button">
               <a href="{{ URL::previous()}}" class="bookib-detai-back"> <i class="fa fa-long-arrow-left"></i> Back</a>
            </div>
            <div class="clearfix"></div>
            {{-- dump($arr_transaction) --}}
            @if(isset($transaction) && !empty($transaction))                        
                @php
                    $transaction_id     = isset($transaction->transaction_id) ? $transaction->transaction_id : '';
                    $booking_date       = isset($transaction->created_at) ? $transaction->created_at : '';
                    
                    if(!empty($booking_date) && $booking_date != null) {
                      $booking_date     = get_added_on_date($booking_date);
                    }
                    
                    $check_in_date     = isset($transaction->check_in_date) ? $transaction->check_in_date : '';
                    
                    if(!empty($check_in_date) && $check_in_date != null) {
                      $check_in_date    = get_added_on_date($check_in_date);
                    }
                    
                    $check_out_date     = isset($transaction->check_out_date) ? $transaction->check_out_date : '';
                    
                    if(!empty($check_out_date) && $check_out_date != null) {
                      $check_out_date   = get_added_on_date($check_out_date);
                    }
                    
                    $transaction_date   = isset($transaction->transaction_date) ? $transaction->transaction_date : '';
                    
                    if(!empty($transaction_date) && $transaction_date != null) {
                      $transaction_date = get_added_on_date($transaction_date);
                    }
                    
                    $currency_code      = isset($transaction->currency_code) ? $transaction->currency_code : '';
                    $currency           = isset($transaction->currency) ? " ".$transaction->currency : '';
                    
                    if(isset($transaction->payment_type) && $transaction->payment_type == 'booking') {
                        if($currency_code == 'INR') {
                            $amount    = isset($transaction->amount) ? number_format($transaction->amount,'2','.','' ) : '';
                        } else {
                            $amount    = isset($transaction->amount) ? number_format(currencyConverter($currency_code, 'INR', $transaction->amount),'2','.','' ) : '';
                        }
                        $total_amount = $currency.$amount;
                    } else {
                        $amount       = isset($transaction->amount) ? number_format($transaction->amount) : '';
                        $total_amount = "&#8377;".$amount;
                    }

                    $session_currency = \Session::get('get_currency');
                    $currency_icon = \Session::get('get_currency_icon');
                    if( $session_currency != 'INR' ){
                        $currency_amount = currencyConverterAPI('INR', $session_currency, $transaction->amount);
                    }
                    else {
                        $currency_amount = $transaction->amount;
                    }
                @endphp
            <div class="clearfix"></div>
            <div class="box-white-user">
               <div class="row">
                  <div class="col-sm-12 col-md-8 col-lg-9">
                     <div class="user-id-new"><span>Transaction Id : </span>&nbsp;{{$transaction_id}}</div>
                     <div class="clearfix"></div>
                     <div class="box-main-bx transac-details">
                        <?php if(isset($transaction->payment_type) && $transaction->payment_type == 'booking'){ ?>
                        <div class="li-boxss">
                           <span class="li-boxss-left-text">Booking Date : </span> 
                           <span class="li-boxss-right-text">{{ $booking_date }}</span>
                        </div>
                        <div class="li-boxss">
                           <span class="li-boxss-left-text"> Check In : </span>
                           <span class="li-boxss-right-text">{{ $check_in_date }}</span>
                        </div>
                        <div class="li-boxss">
                           <span class="li-boxss-left-text"> Check Out : </span>
                           <span class="li-boxss-right-text"> {{ $check_out_date }}</span>
                        </div>
                        <?php } ?>
                        <div class="li-boxss">
                           <span class="li-boxss-left-text"> Transaction Date : </span>
                           <span class="li-boxss-right-text">{{ $transaction_date }}</span>
                        </div>
                        <div class="li-boxss">
                           <span class="li-boxss-left-text"> Payment For : </span>
                           <span class="li-boxss-right-text"> {{ isset($transaction->payment_type) ? ucfirst($transaction->payment_type) : ''}}</span>
                        </div>
                        <div class="li-boxss">
                           <span class="li-boxss-left-text"> Transaction For : </span>
                           <span class="li-boxss-right-text"> {{ isset($transaction->transaction_for) ? $transaction->transaction_for : ''}}</span>
                        </div>
                     </div>
                  </div>
                  
                  <div class="col-sm-12 col-md-4 col-lg-3">
                     <div class="txt-rightside">
                        <div class="users-nw-books"><span>Total :</span> {!! $currency_icon !!}{{ number_format($currency_amount, 2, '.', '') }} </div>
                     </div>
                     <div class="table-cell actio-style action">
                        <?php if(isset($transaction->invoice) && !empty($transaction->invoice) && $transaction->invoice != null && file_exists($invoice_path_base_path.$transaction->invoice ))      
                         {
                             $invoice_path_src = $invoice_path_public_path.$transaction->invoice;
                             ?><a href="{{ $invoice_path_src }}" target="_blank" title="View PDF" style="font-size: 25px;margin: 0px 154px !important;"> <i class="fa fa-file-pdf-o"></i></a><?php 
                         }?>

                     </div>
                  </div>
                  <div class="clearfix"></div>
               </div>
               @endif
            </div>
         </div>
      </div>
   </div>
</div>
@endsection