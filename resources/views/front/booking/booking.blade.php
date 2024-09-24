@extends('front.layout.master')                
@section('main_content')

  <div class="clearfix"></div>

<div class="overflow-hidden-section">
        <div class="titile-user-breadcrum">
            <div class="container">
                <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions">
                    <h1>New Request</h1>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        
        <div class="change-pass-bg main-hidden">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 left-bar-dashboard">
                       @include('front.layout.left_bar_host')
                    </div>
                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9"> 
                        @include('front.layout._flash_errors')                       
                        
                        @if(isset($arr_booking['data']) && sizeof($arr_booking['data'])>0)
                            @foreach($arr_booking['data'] as $booking)

    						<?php 
                                $booking_date   = get_added_on_date($booking['created_at']); 
                                $check_in_date  = get_added_on_date($booking['check_in_date']); 
                                $check_out_date = get_added_on_date($booking['check_out_date']);
    						?>
     
                                <div class="box-white-user">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-8 col-lg-9">
                                            <div class="host-request-left">
                                                <div class="host-request-img-blo">
                                                    @if(isset($booking['user_details']['profile_image']) && !empty($booking['user_details']['profile_image']))  
                                                    	<img src={{ $profile_image_public_img_path.$booking['user_details']['profile_image']}} alt="" />
                                                    @else
                                                    	<img src="{{url('/front')}}/images/profile-avtr.jpg" alt="" />
                                                    @endif 
                                                </div>
                                                <div class="review-cont">{{ $booking['user_details']['user_name'] or '' }}</div>
                                            </div>
                                            <div class="host-request-right">
                                                <div class="user-id-new"><span>Id:</span> B1234567</div>
                                                <div class="heading-user-title"> {{ $booking['property_details']['property_name'] or '' }} </div>
                                                <div class="box-main-bx">
                                                    <div class="li-boxss">
                                                        Booking Date
                                                        <span>{{ $booking_date or '' }}</span>
                                                    </div>
                                                    <div class="li-boxss">
                                                        Check In
                                                        <span>{{ $check_in_date or '' }}</span>
                                                    </div>
                                                    <div class="li-boxss">
                                                        Check Out
                                                        <span>{{ $check_out_date or '' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-4 col-lg-3">
                                            <div class="txt-rightside">
                                                <div class="users-nw-books"> <span>Total</span> <span class="pri-rupee one"><i class="fa fa-rupee"></i></span> {{ number_format($booking['total_amount'],'2','.','' ) }} </div>
                                                
                                                <a href="{{ $module_url_path }}/booking/change_status/accept/{{ base64_encode(isset($booking['id']) ? $booking['id'] : '') }}" class="btn-pays margin-b green">Accept</a>

                                                {{-- <a href="{{ $module_url_path }}/booking/change_status/reject/{{ base64_encode(isset($booking['id']) ? $booking['id'] : '') }}" class="btn-cancel" data-toggle=modal data-target="#RejectPopup">Reject</a> --}}
                                                <a href="javascript:void(0)" class="btn-cancel" onclick='reject_booking("{{ base64_encode($booking['id']) }}")'>Reject</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                
                            @endforeach
                        @else
                        <div class="list-vactions-details">
                          <div class="content-list-vact">
                            <p>Sorry, we couldn't find any matches.</p>
                          </div>
                        </div>
                        @endif
                      
                       @if(isset($obj_pagination) && $obj_pagination!=null)            
                            @include('front.common.pagination',['obj_pagination' => $obj_pagination])
                       @endif    

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function reject_booking(id)
        {
            swal({
                title: "Are you sure",
                text: "Do you want to reject booking?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-reject",
                confirmButtonText: "Yes, reject it!",
                closeOnConfirm: false
            },
            function()
            {
                $('.cancel').click();
                $('#RejectPopup').modal();
                $("#booking_id").val(id);
            });
        }
    </script>

    <!--Add Bank Account Detail popup start here-->
    <div class="host-contact-popup upgrade payment">
        <div class="popup-inquiry-form">
            <div id=RejectPopup class="modal fade" data-backdrop="static" role=dialog>
                <div class=modal-dialog>
                    <div class=modal-content>
                        <div class="modal-header black-close">
                            <button type=button class=close data-dismiss=modal>
                                <span class="contact-left-img popup-close nonebg"><img src="{{url('/')}}/front/images/popup-close-btn.png" alt=""></span>
                            </button>
                            <h4 class=modal-title>Booking Reject</h4>
                        </div>
                        <div class=modal-body>                           
                            <div class="payment-detail-tab-one">

                                <form name="frm_add_account" action="{{ $module_url_path }}/booking/reject" id="frm_add_account" method="POST">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">

                                        <div class="form-group">
                                            <textarea id="reason" name="reason" data-rule-required="true" data-rule-minlength="3"  data-rule-maxlength="50"  /></textarea>
                                            <label for="add-bank-name-id">Reason</label> 
                                            <span class='error help-block'>{{ $errors->first('reason') }}</span>    
                                        </div>

                                        <input type="hidden" name="booking_id" id="booking_id" />

                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="change-pass-btn">
                                            <a class="login-btn cancel" data-dismiss=modal href="javascript:void(0)">Cancel</a>
                                            <input type="submit" class="login-btn" name="btn_store_bank_details" value="save" id="btn_store_bank_details">
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                        <div class=clearfix></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Add Bank Account Detail popup end here-->  


    @endsection
