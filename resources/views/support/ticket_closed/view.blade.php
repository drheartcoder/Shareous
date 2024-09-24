@extends('support.layout.master')
@section('main_content')

<!-- BEGIN Page Title -->
    <div class="page-title"><div></div></div>
<!-- END Page Title -->

<!-- BEGIN Breadcrumb -->
<div id="breadcrumbs">
    <ul class="breadcrumb">
        <li><i class="fa fa-home"></i><a href="{{ url($support_panel_slug.'/dashboard') }}">Dashboard</a></li>
        <span class="divider">
            <i class="fa fa-angle-right"></i>
            <i class="{{ $module_icon or '' }}"></i>
        </span>
        <li><a href="{{ $previous_page_url }}">{{ isset($previous_page_title) ? $previous_page_title : "" }}</a></li>
        <span class="divider">
            <i class="fa fa-angle-right"></i>
            <i class="{{ $page_icon or '' }}"></i>
        </span>
        <li class="active">{{ isset($page_title) ? $page_title : "" }}</li>
    </ul>
</div>
<!-- END Breadcrumb -->

<!-- BEGIN Main Content -->
<div class="row">
<div class="col-md-12">
    <div class="box {{ support_navbar_color() }}">
        <div class="box-title">
            <h3><i class="{{ $page_icon or '' }}"></i>{{ isset($page_title) ? $page_title : "" }}</h3>
            <div class="box-tool"></div>
        </div>

        <div class="box-content">
        @include('support.layout._operation_status')
        
        @if(isset($arr_user) && sizeof($arr_user) > 0)
            
        <?php
            //dd($arr_user);
            // Query Details
            $query_type        = isset($arr_user['query_type_details']['query_type']) && $arr_user['query_type_details']['query_type'] != "" ? $arr_user['query_type_details']['query_type'] : 'NA';
            $query_subject     = isset($arr_user['query_subject']) && $arr_user['query_subject'] != "" ? $arr_user['query_subject'] : 'NA';
            $query_description = isset($arr_user['query_description']) && $arr_user['query_description'] != "" ? $arr_user['query_description'] : 'NA';
            $query_type_id     = isset($arr_user['query_type_id']) && $arr_user['query_type_id'] != "" ? $arr_user['query_type_id'] : '';
            $query_subject     = isset($arr_user['query_subject']) && $arr_user['query_subject'] != "" ? $arr_user['query_subject'] : '';
        ?>

         <div class="row">
            <div class="col-sm-6">
               
                @if( $arr_user['booking_details'] == null && empty( $arr_user['booking_details'] ) )
                    <div class="panel panel-default">
                        <div class="panel-heading font-bold">Profile Image</div>
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="col-sm-3">
                                    <div class="thumbnail">
                                        @if(isset($arr_user['user_details']['profile_image']) && $arr_user['user_details']['profile_image'] != "" && file_exists($profile_image_base_path.$arr_user['user_details']['profile_image']))
                                            <img src="{{ $profile_image_public_path.$arr_user['user_details']['profile_image'] }}" class="img-responsive">
                                        @else
                                            <img src="{{ url('/uploads').'/default-profile.png' }}" class="img-responsive">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

               <div class="panel panel-default">
                  <div class="panel-heading font-bold">Query Details</div>
                  <div class="panel-body">
                     <div class="form-group">
                        
                        @if( $arr_user['booking_details'] != null && !empty( $arr_user['booking_details'] ) )
                        <?php
                            $ticket_by_fname = isset($arr_user['user_details']['first_name']) && $arr_user['user_details']['first_name'] != "" ? ucfirst($arr_user['user_details']['first_name']) : '';
                            $ticket_by_lname = isset($arr_user['user_details']['last_name']) && $arr_user['user_details']['last_name'] != "" ? ucfirst($arr_user['user_details']['last_name']) : '';
                        ?>
                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">Ticket raise by:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-12">
                                 {{ $ticket_by_fname.' '.$ticket_by_lname }}
                              </div>
                           </div>
                        </div>
                        @endif

                        @if( $query_type != 'NA' )
                        <div class="form-group col-sm-12">
                            <label class="col-lg-4 control-label">Query Type:</label>
                            <div class="col-lg-8">
                                <div class="col-lg-5">
                                    {{ $query_type }}
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">Query Subject:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-5">
                                 {{ $query_subject }}
                              </div>
                           </div>
                        </div>
                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">Query Description:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-12">
                                 {{ $query_description }}
                              </div>
                           </div>
                        </div>
                        @if( $query_type != 'NA' )
                        
                        <?php
                        $attachment_file_name = isset($arr_user['attachment_file_name']) && $arr_user['attachment_file_name'] != "" ? $arr_user['attachment_file_name'] : 'NA';
                        ?>

                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">Query Image:</label>
                           <div class="col-lg-8">
                              @if(isset($arr_user['attachment_file']) && !empty($arr_user['attachment_file']) && file_exists($query_image_base_path.$arr_user['attachment_file']))
                              <div class="col-lg-12">
                                 {{ $attachment_file_name }}
                                 <a href="{{$query_image_public_path}}{{$arr_user['attachment_file']}}" download class="btn btn-circle show-tooltip" title="Download"> <i class="fa fa-download"></i> </a>
                              </div>
                              @else
                              <div class="col-lg-12">
                                 {{ 'NA' }}
                              </div>
                              @endif
                           </div>
                        </div>
                        @endif
                     </div>
                  </div>
               </div>

               @if( $arr_user['booking_details'] != null && !empty( $arr_user['booking_details'] ) )
               
                <?php
                    // Booking Details
                    $booking_id = isset($arr_user['booking_details']['booking_id']) && $arr_user['booking_details']['booking_id'] != "" ? $arr_user['booking_details']['booking_id'] : 'NA';
                    $checkin = isset($arr_user['booking_details']['check_in_date']) && $arr_user['booking_details']['check_in_date'] != "" ? get_added_on_date($arr_user['booking_details']['check_in_date']) : 'NA';
                    $checkout = isset($arr_user['booking_details']['check_out_date']) && $arr_user['booking_details']['check_out_date'] != "" ? get_added_on_date($arr_user['booking_details']['check_out_date']) : 'NA';
                    $payment_type = isset($arr_user['booking_details']['payment_type']) && $arr_user['booking_details']['payment_type'] != "" ? $arr_user['booking_details']['payment_type'] : 'NA';
                    $booking_date = isset($arr_user['booking_details']['created_at']) && $arr_user['booking_details']['created_at'] != "" ? get_added_on_date_time($arr_user['booking_details']['created_at']) : 'NA';

                    $booking_days = isset($arr_user['booking_details']['no_of_days']) && $arr_user['booking_details']['no_of_days'] != "" ? $arr_user['booking_details']['no_of_days'] : 'NA';

                    $no_of_slots = isset($arr_user['booking_details']['selected_no_of_slots']) && $arr_user['booking_details']['selected_no_of_slots'] != "" ? $arr_user['booking_details']['selected_no_of_slots'] : 'NA';

                    $no_of_employee = isset($arr_user['booking_details']['selected_of_employee']) && $arr_user['booking_details']['selected_of_employee'] != "" ? $arr_user['booking_details']['selected_of_employee'] : '0';

                    $no_of_room = isset($arr_user['booking_details']['selected_of_room']) && $arr_user['booking_details']['selected_of_room'] != "" ? $arr_user['booking_details']['selected_of_room'] : '0';

                    $no_of_desk = isset($arr_user['booking_details']['selected_of_desk']) && $arr_user['booking_details']['selected_of_desk'] != "" ? $arr_user['booking_details']['selected_of_desk'] : '0';

                    $no_of_cubicles = isset($arr_user['booking_details']['selected_of_cubicles']) && $arr_user['booking_details']['selected_of_cubicles'] != "" ? $arr_user['booking_details']['selected_of_cubicles'] : '0';

                    $no_of_guest = isset($arr_user['booking_details']['no_of_guest']) && $arr_user['booking_details']['no_of_guest'] != "" ? $arr_user['booking_details']['no_of_guest'] : 'NA';

                    $total_amount = isset($arr_user['booking_details']['total_amount']) && $arr_user['booking_details']['total_amount'] != "" ? '<i class="fa fa-inr" aria-hidden="true"></i> '.number_format($arr_user['booking_details']['total_amount'], 2, '.', '') : '0';

                    $price_per = isset($arr_user['booking_details']['property_details']['price_per']) && $arr_user['booking_details']['property_details']['price_per'] != "" ? $arr_user['booking_details']['property_details']['price_per'] : 'NA';

                    if($payment_type == 'booking') {
                        $payment_method = 'Online';
                    }
                    if($payment_type == 'wallet') {
                        $payment_method = 'Wallet';
                    }
                ?>

               <div class="panel panel-default">
                  <div class="panel-heading font-bold">Booking Details</div>
                  <div class="panel-body">
                     <div class="form-group">
                        
                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">Booking ID:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-5">
                                 {{ $booking_id }}
                              </div>
                           </div>
                        </div>

                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">Booking Date:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-12">
                                 {{ $booking_date }}
                                 <input type="hidden" id="paid_amount" value="{{ $total_amount }}">
                              </div>
                           </div>
                        </div>

                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">Check-in Date:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-5">
                                 {{ $checkin }}
                              </div>
                           </div>
                        </div>

                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">Check-out Date:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-5">
                                 {{ $checkout }}
                              </div>
                           </div>
                        </div>
                        
                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">No Of Nights:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-5">
                                 {{ $booking_days }}
                              </div>
                           </div>
                        </div>
                        @if($arr_user['booking_details']['property_type_slug'] == 'warehouse')
                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">No Of Slots:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-12">
                                 {{ $no_of_slots }}
                              </div>
                           </div>
                        </div>
                        @elseif($arr_user['booking_details']['property_type_slug'] == 'office-space')
                        
                        @if( $no_of_employee != 0 )
                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">No Of People:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-12">
                                 {{ $no_of_employee }}
                              </div>
                           </div>
                        </div>
                        @endif
                        @if( $no_of_room != 0 )
                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">No Of Room:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-12">
                                 {{ $no_of_room }}
                              </div>
                           </div>
                        </div>
                        @endif
                        @if( $no_of_desk != 0 )
                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">No Of Desk:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-12">
                                 {{ $no_of_desk }}
                              </div>
                           </div>
                        </div>
                        @endif
                        @if( $no_of_cubicles != 0 )
                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">No Of Cubicles:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-12">
                                 {{ $no_of_cubicles }}
                              </div>
                           </div>
                        </div>
                        @endif
                        
                        @else
                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">No Of Guest:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-12">
                                 {{ $no_of_guest }}
                              </div>
                           </div>
                        </div>
                        @endif
                        
                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">Payment Method:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-5">
                                 {{ $payment_method }}
                              </div>
                           </div>
                        </div>

                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">Total Paid Amount:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-12">
                                 {!! $total_amount !!}
                                 <input type="hidden" id="paid_amount" value="{{ $total_amount }}">
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

                <?php
                    // Property Details
                    $property_type_slug = isset($arr_user['booking_details']['property_type_slug']) && $arr_user['booking_details']['property_type_slug'] != "" ? $arr_user['booking_details']['property_type_slug'] : '-';
                    $property_name      = isset($arr_user['booking_details']['property_details']['property_name']) && $arr_user['booking_details']['property_details']['property_name'] != "" ? $arr_user['booking_details']['property_details']['property_name'] : 'NA';
                    $property_address   = isset($arr_user['booking_details']['property_details']['address']) && $arr_user['booking_details']['property_details']['address'] != "" ? $arr_user['booking_details']['property_details']['address'] : 'NA';
                    $price_per_night    = isset($arr_user['booking_details']['property_details']['price_per_night']) && $arr_user['booking_details']['property_details']['price_per_night'] != "" ? '<i class="fa fa-inr" aria-hidden="true"></i> '.number_format($arr_user['booking_details']['property_details']['price_per_night'], 2, '.', '') : '0';
                    $price_per_sqft     = isset($arr_user['booking_details']['property_details']['price_per_sqft']) && $arr_user['booking_details']['property_details']['price_per_sqft'] != "" ? '<i class="fa fa-inr" aria-hidden="true"></i> '.number_format($arr_user['booking_details']['property_details']['price_per_sqft'], 2, '.', '') : '0';
                    $price_per_office   = isset($arr_user['booking_details']['property_details']['price_per_office']) && $arr_user['booking_details']['property_details']['price_per_office'] != "" ? '<i class="fa fa-inr" aria-hidden="true"></i> '.number_format($arr_user['booking_details']['property_details']['price_per_office'], 2, '.', '') : '0';

                    $room     = isset( $arr_user['booking_details']['property_details']['room'] ) ? $arr_user['booking_details']['property_details']['room'] : 'off';
                    $desk     = isset( $arr_user['booking_details']['property_details']['desk'] ) ? $arr_user['booking_details']['property_details']['desk'] : 'off';
                    $cubicles = isset( $arr_user['booking_details']['property_details']['cubicles'] ) ? $arr_user['booking_details']['property_details']['cubicles'] : 'off';

                    $no_of_room     = isset( $arr_user['booking_details']['no_of_room'] ) ? $arr_user['booking_details']['no_of_room'] : '';
                    $no_of_desk     = isset( $arr_user['booking_details']['no_of_desk'] ) ? $arr_user['booking_details']['no_of_desk'] : '';
                    $no_of_cubicles = isset( $arr_user['booking_details']['no_of_cubicles'] ) ? $arr_user['booking_details']['no_of_cubicles'] : '';

                    $room_price     = isset($arr_user['booking_details']['property_details']['room_price']) && $arr_user['booking_details']['property_details']['room_price'] != "" ? '<i class="fa fa-inr" aria-hidden="true"></i> '.number_format($arr_user['booking_details']['property_details']['room_price'], 2, '.', '') : '0';
                    $desk_price     = isset($arr_user['booking_details']['property_details']['desk_price']) && $arr_user['booking_details']['property_details']['desk_price'] != "" ? '<i class="fa fa-inr" aria-hidden="true"></i> '.number_format($arr_user['booking_details']['property_details']['desk_price'], 2, '.', '') : '0';
                    $cubicles_price = isset($arr_user['booking_details']['property_details']['cubicles_price']) && $arr_user['booking_details']['property_details']['cubicles_price'] != "" ? '<i class="fa fa-inr" aria-hidden="true"></i> '.number_format($arr_user['booking_details']['property_details']['cubicles_price'], 2, '.', '') : '0';
                ?>

               <div class="panel panel-default">
                  <div class="panel-heading font-bold">Property Details</div>
                  <div class="panel-body">
                     <div class="form-group">
                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">Property Name:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-5">
                                 {{ $property_name.' ('.ucwords($property_type_slug).')' }}
                              </div>
                           </div>
                        </div>
                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">Address:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-5">
                                 {{ $property_address }}
                              </div>
                           </div>
                        </div>
                        @if($property_type_slug == 'warehouse')
                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">Price per sq.ft:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-12">
                                 {!! $price_per_sqft !!}
                              </div>
                           </div>
                        </div>
                        @elseif($property_type_slug == 'office-space')
                        @if( $room == 'on' )
                        <div class="form-group col-sm-12">
                          <label class="col-lg-4 control-label">Price per Room:</label>
                          <div class="col-lg-8">
                            <div class="col-lg-5">{!! $room_price !!}</div>
                          </div>
                        </div>
                        @endif

                        @if( $desk == 'on' )
                        <div class="form-group col-sm-12">
                          <label class="col-lg-4 control-label">Price per Dedicated Desk:</label>
                          <div class="col-lg-8">
                            <div class="col-lg-5">{!! $desk_price !!}</div>
                          </div>
                        </div>
                        @endif

                        @if( $cubicles == 'on' )
                        <div class="form-group col-sm-12">
                          <label class="col-lg-4 control-label">Price per Cubicles:</label>
                          <div class="col-lg-8">
                            <div class="col-lg-5">{!! $cubicles_price !!}</div>
                          </div>
                        </div>
                        @endif
                        @else
                        <div class="form-group col-sm-12">
                           <label class="col-lg-4 control-label">Price per Night:</label>
                           <div class="col-lg-8">
                              <div class="col-lg-12">
                                 {!! $price_per_night !!}
                              </div>
                           </div>
                        </div>
                        @endif
                     </div>
                  </div>
               </div>
               @endif
            </div>
            <div class="col-sm-6">
               
               @if( $arr_user['booking_details'] == null && empty( $arr_user['booking_details'] ) )
               
                <?php
                    // User Details
                    $username     = isset($arr_user['user_details']['user_name']) && $arr_user['user_details']['user_name'] != "" ? ucfirst($arr_user['user_details']['user_name']) : 'NA';
                    $user_fname   = isset($arr_user['user_details']['first_name']) && $arr_user['user_details']['first_name'] != "" ? ucfirst($arr_user['user_details']['first_name']) : 'NA';
                    $user_lname   = isset($arr_user['user_details']['last_name']) && $arr_user['user_details']['last_name'] != "" ? ucfirst($arr_user['user_details']['last_name']) : 'NA';
                    $user_email   = isset($arr_user['user_details']['email']) && $arr_user['user_details']['email'] != "" ? $arr_user['user_details']['email'] : 'NA';
                    $user_mobile  = isset($arr_user['user_details']['mobile_number']) && $arr_user['user_details']['mobile_number'] != "" ? $arr_user['user_details']['mobile_number'] : 'NA';
                    $user_dob     = isset($arr_user['user_details']['birth_date']) && $arr_user['user_details']['birth_date'] != "" ? date('d-M-Y',strtotime($arr_user['user_details']['birth_date'])) : 'NA';
                    $user_address = isset($arr_user['user_details']['address']) && $arr_user['user_details']['address'] != "" ? $arr_user['user_details']['address'] : 'NA';
                    $user_gender  = isset($arr_user['user_details']['gender']) && !empty($arr_user['user_details']['gender']) ? $arr_user['user_details']['gender'] : '-';
                    if($user_gender == '0') {
                        $gender_val = "Female";
                    }
                    elseif($user_gender == '1') {
                        $gender_val = "Male";
                    }
                    else {
                        $gender_val = "-";
                    }
                ?>

               <div class="panel panel-default">
                  <div class="panel-heading font-bold">Personal Details</div>
                  <div class="panel-body">
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">User Name:</label>
                        <div class="col-lg-8">
                           {{ $username }}
                        </div>
                     </div>
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">First Name:</label>
                        <div class="col-lg-8">
                           {{ $user_fname }}
                        </div>
                     </div>
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">Last Name:</label>
                        <div class="col-lg-8">
                           {{ $user_lname }}
                        </div>
                     </div>
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">Email:</label>
                        <div class="col-lg-8">
                           {{ $user_email }}
                        </div>
                     </div>
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">Mobile:</label>
                        <div class="col-lg-8">
                           {{ $user_mobile }}
                        </div>
                     </div>
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">Birthdate:</label>
                        <div class="col-lg-8">
                           {{ $user_dob }}
                        </div>
                     </div>
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">Gender:</label>
                        <div class="col-lg-8">
                           {{ $gender_val }}
                        </div>
                     </div>
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">Address:</label>
                        <div class="col-lg-8">
                           {{ $user_address }}
                        </div>
                     </div>

                  </div>
               </div>
               
               @else

               <?php
                   // Guest Details
                   $guest_username = isset($arr_user['booking_details']['booking_by_user_details']['user_name']) && $arr_user['booking_details']['booking_by_user_details']['user_name'] != "" ? ucfirst($arr_user['booking_details']['booking_by_user_details']['user_name']) : 'NA';
                   $guest_fname    = isset($arr_user['booking_details']['booking_by_user_details']['first_name']) && $arr_user['booking_details']['booking_by_user_details']['first_name'] != "" ? ucfirst($arr_user['booking_details']['booking_by_user_details']['first_name']) : 'NA';
                   $guest_lname    = isset($arr_user['booking_details']['booking_by_user_details']['last_name']) && $arr_user['booking_details']['booking_by_user_details']['last_name'] != "" ? ucfirst($arr_user['booking_details']['booking_by_user_details']['last_name']) : 'NA';
                   $guest_email    = isset($arr_user['booking_details']['booking_by_user_details']['email']) && $arr_user['booking_details']['booking_by_user_details']['email'] != "" ? $arr_user['booking_details']['booking_by_user_details']['email'] : 'NA';
                   $guest_mobile   = isset($arr_user['booking_details']['booking_by_user_details']['mobile_number']) && $arr_user['booking_details']['booking_by_user_details']['mobile_number'] != "" ? $arr_user['booking_details']['booking_by_user_details']['mobile_number'] : 'NA';
                   $guest_address  = isset($arr_user['booking_details']['booking_by_user_details']['address']) && $arr_user['booking_details']['booking_by_user_details']['address'] != "" ? $arr_user['booking_details']['booking_by_user_details']['address'] : 'NA';
               ?>

               <div class="panel panel-default">
                  <div class="panel-heading font-bold">Guest Details</div>
                  <div class="panel-body">
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">Username:</label>
                        <div class="col-lg-8">
                           {{ $guest_username }}
                        </div>
                     </div>
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">User Name:</label>
                        <div class="col-lg-8">
                           {{ $guest_fname.' '.$guest_lname }}
                        </div>
                     </div>
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">Email:</label>
                        <div class="col-lg-8">
                           {{ $guest_email }}
                        </div>
                     </div>
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">Mobile:</label>
                        <div class="col-lg-8">
                           {{ $guest_mobile }}
                        </div>
                     </div>
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">Address:</label>
                        <div class="col-lg-8">
                           {{ $guest_address }}
                        </div>
                     </div>

                  </div>
               </div>

               <?php
                   // Host Details
                   $host_username = isset($arr_user['booking_details']['property_owner']['user_name']) && $arr_user['booking_details']['property_owner']['user_name'] != "" ? ucfirst($arr_user['booking_details']['property_owner']['user_name']) : 'NA';
                   $host_fname    = isset($arr_user['booking_details']['property_owner']['first_name']) && $arr_user['booking_details']['property_owner']['first_name'] != "" ? ucfirst($arr_user['booking_details']['property_owner']['first_name']) : 'NA';
                   $host_lname    = isset($arr_user['booking_details']['property_owner']['last_name']) && $arr_user['booking_details']['property_owner']['last_name'] != "" ? ucfirst($arr_user['booking_details']['property_owner']['last_name']) : 'NA';
                   $host_email    = isset($arr_user['booking_details']['property_owner']['email']) && $arr_user['booking_details']['property_owner']['email'] != "" ? $arr_user['booking_details']['property_owner']['email'] : 'NA';
                   $host_mobile   = isset($arr_user['booking_details']['property_owner']['mobile_number']) && $arr_user['booking_details']['property_owner']['mobile_number'] != "" ? $arr_user['booking_details']['property_owner']['mobile_number'] : 'NA';
                   $host_address  = isset($arr_user['booking_details']['property_owner']['address']) && $arr_user['booking_details']['property_owner']['address'] != "" ? $arr_user['booking_details']['property_owner']['address'] : 'NA';
               ?>

               <div class="panel panel-default">
                  <div class="panel-heading font-bold">Host Details</div>
                  <div class="panel-body">
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">Username:</label>
                        <div class="col-lg-8">
                           {{ $host_username }}
                        </div>
                     </div>
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">User Name:</label>
                        <div class="col-lg-8">
                           {{ $host_fname.' '.$host_lname }}
                        </div>
                     </div>
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">Email:</label>
                        <div class="col-lg-8">
                           {{ $host_email }}
                        </div>
                     </div>
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">Mobile:</label>
                        <div class="col-lg-8">
                           {{ $host_mobile }}
                        </div>
                     </div>
                     <div class="form-group col-sm-12">
                        <label class="col-lg-4 control-label">Address:</label>
                        <div class="col-lg-8">
                           {{ $host_address }}
                        </div>
                     </div>

                  </div>
               </div>
               @endif

                <form id="verification_form" method="post" name="verification_form">
                   {{ csrf_field() }}
                   <div class="form-group">
                      <div class="col-lg-12">
                         <button type="button" onclick="location.href='{{ $module_url_path.'/closed_ticket' }}'" class="btn btn-cancel"><i class="fa fa-arrow-left"></i> Back</button>
                      </div>
                   </div>
                </form>

            </div>


         </div>
         @endif
      </div>
   </div>
</div>
<script type="text/javascript">
   function change_level(id)
   {
     swal({
       title: "Are you sure",
       text: "Do you want to assign it to higher level of support?",
       type: "warning",
       showCancelButton: true,
       confirmButtonClass: "btn-danger",
       confirmButtonText: "Confirm",
       closeOnConfirm: false
     },
     function(){
       location.href = "{{url($module_url_path)}}/change_level/"+id;
     });
   }
   
   function accept(id)
   {
     swal({
       title: "Are you sure",
       text: " Do you want to reply / reject?",
       type: "warning",
       showCancelButton: true,
       confirmButtonClass: "btn-danger",
       confirmButtonText: "Confirm",
       closeOnConfirm: false
     },
     function(){
       location.href="{{url($module_url_path)}}/reply/"+id;
     });
   }
   
   function close_ticket(id)
   {
     swal({
       title: "Are you sure",
       text: " Do you want to close ticket?",
       type: "warning",
       showCancelButton: true,
       confirmButtonClass: "btn-danger",
       confirmButtonText: "Confirm",
       closeOnConfirm: false
     },
     function(){
       location.href="{{url($module_url_path)}}/close/"+id;
     });
   }
   
   function reject(id)
   {
     swal({
       title: "Are you sure",
       text: " Do you want to reject verification of user?",
       type: "warning",
       showCancelButton: true,
       confirmButtonClass: "btn-danger",
       confirmButtonText: "Confirm",
       closeOnConfirm: false
     },
     function(){
       location.href="{{url($module_url_path)}}/reject/"+id;
     });
   }
   
   $(document).ready(function(){
     // Allow only Numeric Characters
     $('#txt_refund_amount').keyup(function() {
         if (this.value.match(/[^0-9.]/g)) {
           this.value = this.value.replace(/[^0-9.]/g, '');
         }
     });
   });
   
   function refund(id)
   {
     var ticket_id         = '{{ Request::segment(4) }}';
     var paid_amount       = $("#paid_amount").val();
     var txt_refund_amount = $("#txt_refund_amount").val();
   
     if(txt_refund_amount == '')
     {
       $("#err_txt_refund_amount").show();
       $("#err_txt_refund_amount").html("Please enter amount");
       $("#txt_refund_amount").focus();
     }
     else if( (parseFloat(txt_refund_amount)) > (parseFloat(paid_amount)))
     {
       $("#err_txt_refund_amount").show();
       $("#err_txt_refund_amount").html("Refund amount can't be greater than Total Paid amount");
       $("#txt_refund_amount").focus();
     }
     else if( (parseFloat(txt_refund_amount)) <= (parseFloat(paid_amount)))
     {
         $('#btn_refund_amount').hide();
         $('#btn_process_refund').show();
   
         swal({
           title: "Are you sure",
           text: " Do you want to refund amount?",
           type: "warning",
           showCancelButton: true,
           confirmButtonClass: "btn-danger",
           confirmButtonText: "Confirm",
           closeOnConfirm: false
         },
         function(isConfirm)
         {
           if (isConfirm)
           {
             swal({ title: "Processing...!", text: "Please wait", showConfirmButton: false });
   
             var refund_amount = $("#txt_refund_amount").val();
             var token = $('input[name="_token"]').val();   
   
             $.ajax({
                 'url':'{{ url($module_url_path) }}/refund',
                 'type':'post',
                 'data':{_token: token, booking_id: id, refund_amount: refund_amount, ticket_id: ticket_id },
                 success:function(res)
                 {
                     if(res.status == 'success'){
                       swal({ title: "Success", text: res.msg });
                       location.href = "{{ url($module_url_path) }}/closed_ticket";
                     }else{
                       swal({ title: "Error", text: res.msg });
                       $('#btn_refund_amount').show();
                       $('#btn_process_refund').hide();
                     }
                 }
             });
           }
           else
           {
             $('#btn_refund_amount').show();
             $('#btn_process_refund').hide();
           }
   
         });
     }
     else {
       $("#err_txt_refund_amount").show();
       $("#err_txt_refund_amount").html("Something went wrong.");
       $("#txt_refund_amount").focus();
     }
   }
</script>
@endsection