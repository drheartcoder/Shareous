@extends('front.layout.master')
@section('main_content')
<style type="text/css">
    #beds-table table, td, th{  border: 1px solid #ff4747;text-align: center;}
    #beds-table.{border-collapse: collapse;border-color: red;}
    .contact-left-img.image-none{background-image: none;}
</style>

<!--star rating-->
<link href="{{url('/')}}/front/css/star-rating.css" rel="stylesheet" type="text/css"/>

<div class="swiper-container" id="ka-swiper1">
   <div class="swiper-wrapper">
      @if(isset($arr_images) && is_array($arr_images) && sizeof($arr_images)>0)
        @foreach($arr_images as $key => $image)
            <div class="swiper-slide">
                <img src="{{ get_resized_image($image_file = $image['image_name'], $dir = $property_image_base_path, $height=500, $width=640, $fallback_text = "No Image Available") }}" alt="slider-{{ $key+1 }}" />
            </div>
        @endforeach
      @else
      <div class="swiper-slide"> <img src="{{url('/')}}/front/images/no-img.png" alt="slider-1" /> </div>
      <div class="swiper-slide"> <img src="{{url('/')}}/front/images/no-img.png" alt="slider-1" /> </div>
      <div class="swiper-slide"> <img src="{{url('/')}}/front/images/no-img.png" alt="slider-1" /> </div>
      @endif
   </div>
   <!-- Add Arrows -->
   <div class="swiper-button-next">
      <div class="slider-arrow-right"> <img src="{{url('/')}}/front/images/details-silder-arrow-2.png" alt="arrow" /> </div>
   </div>
   <div class="swiper-button-prev">
      <div class="slider-arrow-right"> <img src="{{url('/')}}/front/images/details-silder-arrow.png" alt="arrow-2" /> </div>
   </div>
</div>

<div class="faq-main view-deta">
    <div class="container">
        <div class="row">

            <div class="col-sm-8 col-md-8 col-lg-8">
                @include('front.layout._operation_status')
                <div class="details-title test">{{ isset($arr_property['property_name']) ? title_case($arr_property['property_name']) : '' }} {{ isset($arr_property_type['name']) ? '('.title_case($arr_property_type['name']).')' : '' }}</div>
                <div class="details-star-box">
                    <?php $booking_id = Request::input('booking_id');

                    if (auth()->guard('users')->user() == null) {
                        $url               = Request::fullUrl();
                        $notification_url  = str_replace(url('/'), "", $url);

                        if (Session::get('notification_url') =='' && !empty(Session::get('notification_url'))) {
                            Session::set('notification_url', $notification_url);
                        } else {
                            Session::put('notification_url', '');
                            Session::set('notification_url', $notification_url);
                        }
                    }

                    $total = 0; $count = 0; $tmp_str_rating = ''; $no_reviews = 0;

                    if (isset($arr_property_review)) {
                        foreach ($arr_property_review as $rating) {
                            if ($rating['property_id'] == $arr_property['id']) {
                                $total  += floatval($rating['rating']);
                                $count++;
                            }
                        }
                    }
                    if ($count !=0) {
                        $no_reviews  = $total/$count;
                    }
                    ?>

                    @if($no_reviews != 0)
                    <div class="start-details">
                        <?php if (isset($no_reviews) && $no_reviews!='') {
                            $starNumber = $no_reviews;
                            for ($x=1;$x<=$starNumber;$x++) {
                                echo '<img src="'.url('/').'/front/images/star1.png" />';
                            }
                            if (strpos($starNumber, '.')) {
                                echo '<img src="'.url('/').'/front/images/half-star.png" />';
                                $x++;
                            }
                            while ($x<=5) {
                                echo '<img src="'.url('/').'/front/images/star2.png" />';
                                $x++;
                            }
                        }?>
                    </div>
                    <div class="star-review-txt">(<?php echo number_format($no_reviews,1, '.', ''); ?>)</div>
                    @endif
                    <div class="details-location"> <i class="fa fa-map-marker"></i> {{isset($arr_property['address'])? title_case($arr_property['address']):''}}</div>
                </div>
                <div class="details-description-bx">
                    <div class="main-dts-title">Description</div>
                    <div class="description-sml-txt" style="text-align: justify!important;">
                        @php
                            $property_id     = isset($arr_property['id']) ? $arr_property['id'] : '';
                            $description     = isset($arr_property['description']) ? $arr_property['description'] : '';
                            $price_per_night = isset($arr_property['price_per_night']) ? $arr_property['price_per_night'] : '0';
                        @endphp
                        <p hidden="hidden" >{{ $description }} @if(!empty(substr($description,200)))<a href="javascript:void(0)" onclick="load_less(this)">Read Less</a>@endif </p>
                        <p>{{ str_limit($description,200) }} @if(!empty(substr($description,200)))<a href="javascript:void(0)" onclick="load_more(this)">Read More</a>@endif </p>
                    </div>
                </div>
                
                <div class="details-description-bx">
                    <div class="main-dts-title">Contact Info</div>
                    <div class="cont-info-email"> <span>Name :</span> {{ $arr_property['owner_first_name'].' '.$arr_property['owner_last_name'] }}</div>
                    <div class="cont-info-email"> <span>Address :</span> {{ $arr_property['owner_address'] }}</div>
                </div>
                <?php $property_type_slug = get_property_type_slug($arr_property['property_type_id'] ); ?>
                <div class="details-description-bx">
                    <div class="main-dts-title">Basic Features</div>

                    @if($property_type_slug == 'warehouse')
                        <div class="row">
                            
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                         <img src="{{url('/')}}/front/images/total-area-icn.png" alt="arrow" /> 
                                    </div>
                                    <div class="basic-features-price">Total Area</div>
                                    <div class="basic-features-amount">{{ $arr_property['property_area'] }} Sq.Ft</div>
                                </div>
                            </div>
                            
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                         <img src="{{url('/')}}/front/images/total-build-area-icn.png" alt="arrow" />
                                    </div>
                                    <div class="basic-features-price">Total Build Area</div>
                                    <div class="basic-features-amount">{{ $arr_property['total_build_area'] }} Sq.Ft</div>
                                </div>
                            </div>
                            
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/admin-area-icn.png" alt="arrow" />
                                    </div>
                                    <div class="basic-features-price">Admin Area</div>
                                    <div class="basic-features-amount">{{ $arr_property['admin_area'] }} Sq.Ft</div>
                                </div>
                            </div>

                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/price-icns.png" alt="arrow" /></div>
                                    <div class="basic-features-price">Price Per Sq.Ft</div>
                                    <div class="basic-features-amount">{{ session_currency($arr_property['price_per_sqft'], $property_id) }}</div>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/no-of-slots-icn.png" alt="arrow" /></div>
                                    <div class="basic-features-price">No. Of Slots</div>
                                    <div class="basic-features-amount">{{ $arr_property['no_of_slots'] }}</div>
                                </div>
                            </div>

                            <?php
                            $property_area = isset( $arr_property['property_area'] ) ? $arr_property['property_area'] : 0;
                            $no_of_slots = isset( $arr_property['no_of_slots'] ) ? $arr_property['no_of_slots'] : 0;
                            ?>
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                         <img src="{{url('/')}}/front/images/total-area-icn.png" alt="arrow" /> 
                                    </div>
                                    <div class="basic-features-price">Slot Area</div>
                                    <div class="basic-features-amount">{{ $property_area / $no_of_slots }} Sq.Ft/Slot</div>
                                </div>
                            </div>
                            
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/working-status.png" alt="arrow" /></div>
                                    <div class="basic-features-price">Working Status</div>
                                    <div class="basic-features-amount">{{ ucwords($arr_property['property_working_status']) }}</div>
                                </div>
                            </div>
                            
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/build-type-icn.png" alt="arrow" /></div>
                                    <div class="basic-features-price">Build Type</div>
                                    <div class="basic-features-amount">{{ $arr_property['build_type'] }}</div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/good-storage-icn.png" alt="arrow" /></div>
                                    <div class="basic-features-price">Good Storage</div>
                                    <div class="basic-features-amount">{{ ucwords($arr_property['good_storage']) }}</div>
                                </div>
                            </div>

                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/custom-type-icn.png" alt="arrow" />
                                     </div>
                                    <div class="basic-features-price">Custom Type</div>
                                    <div class="basic-features-amount">{{ ucwords($arr_property['custom_type']) }}</div>
                                </div>
                            </div>
                            
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/prperty-management-icn.png" alt="arrow" />
                                    </div>
                                    <div class="basic-features-price">Property Management</div>
                                    <div class="basic-features-amount">{{ ucwords($arr_property['management']) }}</div>
                                </div>
                            </div>
                            
                            @if(isset($arr_aminities) && count($arr_aminities) > 0)
                                <div class="col-sm-3 col-md-3 col-lg-3">
                                    <div class="basic-features aminities_div" style="cursor: pointer;">
                                        <div class="contact-left-img basic-features-img Amenities">
                                        </div>
                                        <div class="basic-features-price">Amenities</div>
                                        <div class="basic-features-amount">{{ isset($arr_aminities) ? count($arr_aminities) : '0' }} Amenities</div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    @elseif($property_type_slug == 'office-space')
                        <div class="row">
                            
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/total-area-icn.png" alt="arrow" />
                                    </div>
                                    <div class="basic-features-price">Total Area</div>
                                    <div class="basic-features-amount">{{ $arr_property['property_area'] }} Sq.Ft</div>
                                </div>
                            </div>
                            
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/total-build-area-icn.png" alt="arrow" />
                                    </div>
                                    <div class="basic-features-price">Total Build Area</div>
                                    <div class="basic-features-amount">{{$arr_property['total_build_area']}} Sq.Ft</div>
                                </div>
                            </div>
                            
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/admin-area-icn.png" alt="arrow" />
                                    </div>
                                    <div class="basic-features-price">Admin Area</div>
                                    <div class="basic-features-amount">{{$arr_property['admin_area']}} Sq.Ft</div>
                                </div>
                            </div>

                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/working-status.png" alt="arrow" />
                                    </div>
                                    <div class="basic-features-price">Working Status</div>
                                    <div class="basic-features-amount">{{ ucwords($arr_property['property_working_status']) }}</div>
                                </div>
                            </div>

                        </div>
                        
                        <?php
                        $room           = isset( $arr_property['room'] ) ? $arr_property['room'] : '';
                        $desk           = isset( $arr_property['desk'] ) ? $arr_property['desk'] : '';
                        $cubicles       = isset( $arr_property['cubicles'] ) ? $arr_property['cubicles'] : '';

                        $no_of_room     = isset( $arr_property['no_of_room'] ) ? $arr_property['no_of_room'] : '';
                        $no_of_desk     = isset( $arr_property['no_of_desk'] ) ? $arr_property['no_of_desk'] : '';
                        $no_of_cubicles = isset( $arr_property['no_of_cubicles'] ) ? $arr_property['no_of_cubicles'] : '';

                        $room_price     = isset( $arr_property['room_price'] ) ? $arr_property['room_price'] : '0';
                        $desk_price     = isset( $arr_property['desk_price'] ) ? $arr_property['desk_price'] : '0';
                        $cubicles_price = isset( $arr_property['cubicles_price'] ) ? $arr_property['cubicles_price'] : '0';
                        ?>

                        <div class="row">

                            @if( $room == 'on' )
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/total-plot-area-icn.png" alt="arrow" />
                                    </div>
                                    <div class="basic-features-price">No. Of Private Room</div>
                                    <div class="basic-features-amount">{{ $no_of_room }} </div>
                                </div>
                            </div>

                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/price-icns.png" alt="arrow" />
                                    </div>
                                    <div class="basic-features-price">Price per Private Room</div>
                                    <div class="basic-features-amount">{{ session_currency($room_price, $property_id) }}</div>
                                </div>
                            </div>
                            @endif

                            @if( $desk == 'on' )
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/total-plot-area-icn.png" alt="arrow" />
                                    </div>
                                    <div class="basic-features-price">No. Of Dedicated Desk</div>
                                    <div class="basic-features-amount">{{ $no_of_desk }} </div>
                                </div>
                            </div>

                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/price-icns.png" alt="arrow" />
                                    </div>
                                    <div class="basic-features-price">Price per Dedicated Desk</div>
                                    <div class="basic-features-amount">{{ session_currency($desk_price, $property_id) }}</div>
                                </div>
                            </div>
                            @endif

                            @if( $cubicles == 'on' )
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/total-plot-area-icn.png" alt="arrow" />
                                    </div>
                                    <div class="basic-features-price">No. Of Cubicles</div>
                                    <div class="basic-features-amount">{{ $no_of_cubicles }} </div>
                                </div>
                            </div>

                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/price-icns.png" alt="arrow" />
                                    </div>
                                    <div class="basic-features-price">Price per Cubicles</div>
                                    <div class="basic-features-amount">{{ session_currency($cubicles_price, $property_id) }}</div>
                                </div>
                            </div>
                            @endif
                            
                            @if(isset($arr_aminities) && count($arr_aminities) > 0)
                                <div class="col-sm-3 col-md-3 col-lg-3">
                                    <div class="basic-features">
                                        <div class="contact-left-img basic-features-img Amenities aminities_div" style="cursor: pointer;">
                                        </div>
                                        <div class="basic-features-price">Amenities</div>
                                        <div class="basic-features-amount">{{ isset($arr_aminities) ? count($arr_aminities) : '0' }} Amenities</div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    @else
                        <div class="row">
                            
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img image-none">
                                     <img src="{{url('/')}}/front/images/price-icns.png" alt="arrow" />
                                   </div>
                                    <div class="basic-features-price">Price</div>
                                    <div class="basic-features-amount">{{ session_currency($price_per_night, $property_id) }}</div>
                                </div>
                            </div>
                            
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img Amenities">
                                    </div>
                                    <div class="basic-features-price">Guests</div>
                                    <div class="basic-features-amount">{{ isset($arr_property['number_of_guest']) ?  $arr_property['number_of_guest'] : '0' }} Guests</div>
                                </div>
                            </div>
                            
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img Bedrooms">
                                    </div>
                                    <div class="basic-features-price">Bedrooms</div>
                                    <div class="basic-features-amount">{{ isset($arr_property['number_of_beds']) ?  $arr_property['number_of_beds'] : '0' }} Beds</div>
                                </div>
                            </div>
                            
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <div class="basic-features">
                                    <div class="contact-left-img basic-features-img Amenities">
                                    </div>
                                    <div class="basic-features-price">Bathrooms</div>
                                    <div class="basic-features-amount">{{ isset($arr_property['number_of_bathrooms']) ?  $arr_property['number_of_bathrooms'] : '0' }} Bathrooms</div>
                                </div>
                            </div>
                            
                            @if(isset($arr_aminities) && count($arr_aminities) > 0)
                                <div class="col-sm-3 col-md-3 col-lg-3">
                                    <div class="basic-features">
                                        <div class="contact-left-img basic-features-img Amenities aminities_div" style="cursor: pointer;">
                                        </div>
                                        <div class="basic-features-price">Amenities</div>
                                        <div class="basic-features-amount">{{ isset($arr_aminities) ? count($arr_aminities) : '0' }} Amenities</div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    @endif

                    <div class="row" id="aminities_details_div">

                    @if(isset($arr_aminities) && count($arr_aminities) > 0)
                        @foreach($arr_aminities as $aminity)
                            <?php
                                $aminity_slug = trim( str_slug( $aminity['aminity_name'], '-' ) );
                                $lable_name   = '';
                                $lable_value  = '';
                                $lable_style  = "display:none;";

                                if ($aminity_slug == 'nearest-railway-station') 
                                {
                                    $lable_name   = $aminity['aminity_name'];
                                    $lable_value  = $arr_property['nearest_railway_station'];
                                    $lable_style  = "display:block;";
                                }
                                if ($aminity_slug == 'nearest-national-highway') 
                                {
                                    $lable_name   = $aminity['aminity_name'];
                                    $lable_value  = $arr_property['nearest_national_highway'];
                                    $lable_style  = "display:block;";
                                }
                                if ($aminity_slug == 'nearest-bus-stop') 
                                {
                                    $lable_name   = $aminity['aminity_name'];
                                    $lable_value  = $arr_property['nearest_bus_stop'];
                                    $lable_style  = "display:block;";
                                }
                                if ($aminity_slug == 'working-hours') 
                                {
                                    $lable_name   = $aminity['aminity_name'];
                                    $lable_value  = $arr_property['working_hours'];
                                    $lable_style  = "display:block;";
                                }
                                if ($aminity_slug == 'working-days') 
                                {
                                    $lable_name   = $aminity['aminity_name'];
                                    $lable_value  = $arr_property['working_days'];
                                    $lable_style  = "display:block;";
                                }
                            ?>
                            @if(isset($lable_value) && $lable_value != '')
                                <div class="col-sm-3 col-md-3 col-lg-3" style="{{ $lable_style }}">
                                    <div class="basic-features">
                                        <div class="contact-left-img basic-features-img image-none Amenities">
                                            <img src="{{url('/')}}/front/images/working-hours-icn.png" alt="arrow" />
                                        </div>
                                        <div class="basic-features-price">{{ ucwords($lable_name) }}</div>
                                        <div class="basic-features-amount">{{ ucwords($lable_value) }}</div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                    </div>
                    <div class="clr"></div>
                </div>
        
    @if(isset($sleeping_arrangment_arr) && is_array($sleeping_arrangment_arr) && sizeof($sleeping_arrangment_arr)>0)
        <div class="details-description-bx">
            <div class="main-dts-title">Bedrooms Beds</div>
            <div class="ameneties-box small-space">
                <div class="user-tarnsaction-main view">
                    <div class="transactions-table table-responsive">
                        <!--div format starts here-->
                        <div class="table">
                            <div class="table-row heading">
                                <div class="table-cell">Double Bed</div>
                                <div class="table-cell">Single Bed</div>
                                <div class="table-cell">Queen Bed</div>
                                <div class="table-cell">Sofa Bed</div>
                            </div>
                            <?php $i=1; ?>
                            @foreach($sleeping_arrangment_arr as $row)
                            <?php
                            if ($i%2==0) 
                            {
                                $style = "background: #f6f6f6";
                            } else 
                            {
                                $style = "";
                            } 

                            $double_title = title_case(get_sleeping_arrangment_name($row->double_bed));
                            $single_title = title_case(get_sleeping_arrangment_name($row->single_bed));
                            $sofa_title   = title_case(get_sleeping_arrangment_name($row->sofa_bed));
                            $queen_title  = title_case(get_sleeping_arrangment_name($row->sofa_bed));
                            ?>
                            <div class="table-row" style="{{ $style }}">
                                <div class="table-cell cargo-type">
                                    {{ isset($double_title) && $double_title != '' ? $double_title : '--' }}
                                </div>
                                <div class="table-cell vehical-category">
                                    {{ isset($single_title) && $single_title != '' ? $single_title : '--' }}
                                </div>
                                <div class="table-cell delevery-location">
                                    {{ isset($queen_title) && $queen_title != '' ? $queen_title : '--' }}
                                </div>
                                <div class="table-cell tabe-discrip date">
                                    {{ isset($sofa_title) && $sofa_title != '' ? $sofa_title : '--' }}
                                </div>
                            </div>
                            <?php $i++; ?>
                            @endforeach
                            <div class="clearfix"></div>
                        </div>
                        <!--div format ends here-->
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if(isset($arr_aminities) && is_array($arr_aminities) && sizeof($arr_aminities)>0)
        <div class="details-description-bx">
            <div class="main-dts-title">Amenities</div>
            <div class="ameneties-box small-space inline">
                <ul>
                    @foreach($arr_aminities as $aminity)
                    <li><i class="fa fa-circle-o"></i>{{title_case($aminity['aminity_name'])}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    @if(isset($arr_rules) && is_array($arr_rules) && sizeof($arr_rules)>0)
        <div class="details-description-bx">
            <div class="main-dts-title">Housing Rules</div>
            <div class="ameneties-box housing-rules">
                <ul>
                    @foreach($arr_rules as $rule)
                    <li><i class="fa fa-circle-o"></i>{{title_case($rule['rules'])}}</li>
                    @endforeach
                </ul>
                {{-- <div class="ameneties-more"><a href="#">More...</a></div> --}}
            </div>
        </div>
    @endif
    

    @if(isset($unavailble_dates) && sizeof($unavailble_dates)>0)
        <div class="details-description-bx">
            <div class="main-dts-title">Unavailable Dates</div>
            <div class="ameneties-box small-space">
                <div class="user-tarnsaction-main view">
                    <div class="transactions-table table-responsive">
                        <div class="table">
                            <div class="table-row heading">
                                <div class="table-cell">Start Date</div>
                                <div class="table-cell">End Date</div>
                            </div>
                            <?php $i = 1; ?>
                            @foreach($unavailble_dates as $row)
                                <?php
                                    if ($i%2 == 0) {
                                        $style = "background: #f6f6f6";
                                    }
                                    else {
                                        $style = "";
                                    }
                                ?>
                                <div class="table-row" style="{{ $style }}">
                                    <div class="table-cell cargo-type">{{ get_added_on_date($row['from_date']) }}</div>
                                    <div class="table-cell vehical-category">{{ get_added_on_date($row['to_date']) }}</div>
                                </div>
                                <?php $i++; ?>
                            @endforeach
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(isset($arr_property['property_remark']) &&  $arr_property['property_remark'] != '' &&  $arr_property['property_remark'] != null)
        <div class="details-description-bx">
            <div class="main-dts-title">Property Remark</div>
            <div class="description-sml-txt" style="text-align: justify!important;">
                <p >{{ $arr_property['property_remark'] }} </p>
            </div>
        </div>
    @endif

    <?php
        $address            = isset($arr_property['address']) ? $arr_property['address'] : '';
        $property_latitude  = isset($arr_property['property_latitude']) ? $arr_property['property_latitude'] : '';
        $property_longitude = isset($arr_property['property_longitude']) ? $arr_property['property_longitude'] : '';
    ?>

    <div class="details-description-bx map">
        <div class="main-dts-title">Location</div>
        <div class="loc-map">
            <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCccvQtzVx4aAt05YnfzJDSWEzPiVnNVsY&q={{$address}}&q={{$property_latitude}},{{$property_longitude}}" width="100%" height="642" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
    </div>
    <?php if ($no_reviews > 0) {
        ?>
        <div class="details-description-bx">
            <div class="review-count">
                <div class="review-no"><?php echo number_format($no_reviews, 1, '.', ''); ?></div>
                <div class="reviews-32">
                    <div class="start-details count-bx">
                        <?php if (isset($no_reviews) && $no_reviews!='') {
                            $starNumber = $no_reviews;
                            for ($x=1;$x<=$starNumber;$x++) {
                                echo '<img src="'.url('/').'/front/images/star1.png" />';
                            }
                            if (strpos($starNumber, '.')) {
                                echo '<img src="'.url('/').'/front/images/half-star.png" />';
                                $x++;
                            }
                            while ($x<=5) {
                                echo '<img src="'.url('/').'/front/images/star2.png" />';
                                $x++;
                            }
                        } ?>
                    </div>                    
                    <div class="review-number"><?php echo count($arr_review_rating); ?>&nbsp;Reviews</div>
                </div>
            </div>
            <div class="clr"></div>
        </div>
        <?php
    }
    $is_review_given = 'NO';
    $login_user_id = isset(auth()->guard('users')->user()->id) ? auth()->guard('users')->user()->id : 0;
    ?>

 
    @if(isset($arr_review_rating) && is_array($arr_review_rating) && sizeof($arr_review_rating)>0)
    <div id="write-review-div" class="review-bx-height for-height">
        @foreach($arr_review_rating as $key => $rating)
        <?php
            if(isset($rating->rating_user_id) && $rating->rating_user_id == $login_user_id)
            {
                $is_review_given = 'YES';
            }
        ?>

        <div class="rating-white-block marg-top">
            <div class="review-profile-image">

                @if(isset($rating->profile_image) && $rating->profile_image != '' && file_exists($user_profile_image_base_path.$rating->profile_image))
                    <img src="{{$user_profile_image_public_path.$rating->profile_image}}" alt="property image" />
                @else
                    <img src="{{ url('/') }}/front/images/default-user.png" alt="" />
                @endif

            </div>
            <div class="review-content-block">
                <div class="review-send-head">
                    <?php echo $rating->first_name."&nbsp;".$rating->last_name; ?>
                </div>
                <div class="rating-review-stars">
                    <span class="start-rate-count-blue star-rate-count-yellow"><?php echo $rating->rating; ?></span>
                    <span class="stars-block star-listing">
                        <?php if (isset($rating->rating) && $rating->rating!='') {
                            $starNumber = $rating->rating;
                            for ( $x = 1; $x <= $starNumber; $x++ ) {
                                echo '<img src="'.url('/').'/front/images/star1.png" />';
                            }
                            if (strpos($starNumber, '.')) {
                                echo '<img src="'.url('/').'/front/images/half-star.png" />';
                                $x++;
                            }
                            while ($x<=5) {
                                echo '<img src="'.url('/').'/front/images/star2.png" />';
                                $x++;
                            }
                        } ?>
                    </span>
                    <div class="time-text">
                        <?php
                            echo $str1 = date("h:s a - ", strtotime($rating->updated_at));
                            $str = date("m-d-Y", strtotime($rating->updated_at));
                            $dateObj = DateTime::createFromFormat('m-d-Y', $str);
                            echo $dateObj->format('M d, Y');
                        ?>
                    </div>
                </div>

                <div class="review-rating-message" hidden="hidden">{{ $rating->message }} @if(!empty(substr($rating->message,200)))<a href="javascript:void(0)" onclick="review_load_less(this)">Read Less</a>@endif </div>

                <div class="review-rating-message">{{ str_limit($rating->message,200) }} @if(!empty(substr($rating->message,200)))<a href="javascript:void(0)" onclick="review_load_more(this)">Read More</a>@endif </div>

            </div>

        </div>

        @endforeach

    </div>
    @endif

    @if(isset($booking_id) && $booking_id !='' && $is_review_given == 'NO')
    <div class="add-review-toggle" id="add_review_toggle" >
        <form action="{{$module_url_path.'/submit-rating-review/'.base64_encode($arr_property['id'])}}" method="post" class="profile-page-form" id="frm-write-review">
            {{csrf_field()}}

            <?php $review_data =  get_review_details(base64_decode($booking_id)); ?>
            <div class="rating-white-block marg-top text-box ">
                <div class="comments-title">Add A Review &amp; Ratings</div>
                <div class="starrr text-left" style="padding-bottom: 50px;" >
                    <fieldset class="rating">
                        <input type="radio" id="star5" name="rating" value="5" {{isset($review_data) && $review_data['rating']!='' && $review_data['rating']=='5' ?"checked":""}}/><label class = "full" for="star5" title="Awesome - 5 stars" ></label>
                        <input type="radio" id="star4half" name="rating" value="4.5" {{isset($review_data) && $review_data['rating']!='' && $review_data['rating']=='4.5' ?"checked":""}}/><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
                        <input type="radio" id="star4" name="rating" value="4" {{isset($review_data) && $review_data['rating']!='' && $review_data['rating']=='4' ?"checked":""}}/><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
                        <input type="radio" id="star3half" name="rating" value="3.5" {{isset($review_data) && $review_data['rating']!='' && $review_data['rating']=='3.5' ?"checked":""}}/><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
                        <input type="radio" id="star3" name="rating" value="3" {{isset($review_data) && $review_data['rating']!='' && $review_data['rating']=='3' ?"checked":""}}/><label class = "full" for="star3" title="Meh - 3 stars"></label>
                        <input type="radio" id="star2half" name="rating" value="2.5" {{isset($review_data) && $review_data['rating']!='' && $review_data['rating']=='2.5' ?"checked":""}}/><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
                        <input type="radio" id="star2" name="rating" value="2" {{isset($review_data) && $review_data['rating']!='' && $review_data['rating']=='2' ?"checked":""}}/><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
                        <input type="radio" id="star1half" name="rating" value="1.5" {{isset($review_data) && $review_data['rating']!='' && $review_data['rating']=='1.5' ?"checked":""}}/><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
                        <input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
                        <input type="radio" id="starhalf" name="rating" value="0.5" {{isset($review_data) && $review_data['rating']!='' && $review_data['rating']=='0.5' ?"checked":""}}/><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>

                    </fieldset>
                    <input type="hidden" id="booking_id" name="booking_id"  value="{{isset($review_data) && $review_data['booking_id']!=''?$review_data['booking_id']:$booking_id}}" >

                    <input type="hidden" id="review_id" name="review_id"  value="{{isset($review_data) && $review_data['id']!=''?$review_data['id']:''}}" >

                </div>

                <div class="error" id="err_rating" >{{ $errors->first('rating')}}</div><br>

                <div class="clr"></div>

                <div class="comments-title">Comments</div>

                <div class="profile-info-mian rting">
                    <input type="hidden" id="ad_id" name="ad_id" value="{{isset($review_data) && $review_data['property_id']!=''?$review_data['property_id']:$arr_property['id']}}">
                    <div class="form-group">
                        <textarea rows="2" class="text-area" id="comment" name="comment" required>{{ old('comment') }}</textarea>
                        <label>Write a Comment</label>
                        <div class="error" id="err_comment">{{ $errors->first('comment')}}</div>

                    </div>
                </div>

                <div class="blog-button">
                    <div class="send-review-brn">
                        @if(auth()->guard('users')->user()!=null)
                            @if(isset($review_data) && count($review_data)>0)
                                <button class="review-send"  id="btnUpdateReview"  type="submit">Update Review</button>
                            @else
                                <button class="review-send"  id="btnSubmitReview"  type="submit">Send Review</button>
                            @endif
                        @else
                            <a href="{{ url('/login') }}" class="review-send"> Send Review</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
    @endif

</div>
@if( auth()->guard('users')->user() == null || Session::get('user_type') == null || Session::get('user_type') == '1' )
    @if( auth()->guard('users')->user() != null )
        @if( $arr_property['user_id'] != auth()->guard('users')->user()->id )
            @include('front.property.booking')
        @endif
    @else
        @include('front.property.booking')
    @endif
@endif
</div>
</div>
</div>


@if(Session::has('booking_success'))
    <input type="hidden" id="session_success" value="{{ Session::get('booking_success') }}" />
@endif
@if(Session::has('booking_error'))
    <input type="hidden" id="session_error" value="{{ Session::get('booking_error') }}" />
@endif


<!--Rating half star css-->
<link rel="stylesheet" href="{{ url('/front/css/star-rating.css') }}" />
<!--rating demo-->
<script type="text/javascript" language="javascript" src="{{url('/front/js/jquery.rating.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{url('/front/js/star-rating.js')}}"></script>

<script>
    $(document).ready(function() {
        if ($("#session_success").val() != null) {
            swal("Property booked", $("#session_success").val(), "success");
        }

        if ($("#session_error").val() != null) {
            swal("Error", $("#session_error").val(), "warning");
        }

        @if(isset($booking_id) && $booking_id !='')
            var booking_id  = $('#booking_id').val();
            if (booking_id != '') {
                $('html, body').animate({
                    scrollTop: $("#add_review_toggle").offset().top - 200
                }, 2000);
            }
        @endif

        $(".aminities_div").click(function(){
            $('html, body').animate({
                    scrollTop: $("#aminities_details_div").offset().top
                }, 2000);
        });
    });

    $("#review_btn").on("click", function() {
        $(".add-review-toggle").slideToggle("slow");
    });

    $("#btnSubmitReview","#btnUpdateReview" ).on("click", function() {
        var rating  = $("input[name=rating]:checked").val();
        var comment = $('#comment').val();
        var flag    = 1;

        if ($('input[name="rating"]').is(':checked') == false) {
            $("#err_rating").html("Please select the rating");
            $('input[name="rating"]').on('change',function(){ $("#err_rating").html("");});
            $('input[name="rating"]').focus();
            flag = 0;
        }

        if ($.trim(comment.length) < 50) {
            $("#err_comment").html("Please enter comment above 50 character");
            $("#comment").on('click',function(){ $("#err_message").html("");});
            $("#comment").focus();
            flag = 0;
        }

        if ($.trim(comment) == '') {
            $("#err_comment").html("Please enter comment");
            $("#comment").on('click',function(){ $("#err_comment").html("");});
            $("#comment").focus();
            flag = 0;
        }

        if (flag == 1) {
            return true;
        } else {
            return false;
        }
    });
</script>

<script type="text/javascript">
    var array_files = [];
    $(document).ready(function () {
        /*var seg = '{{ Request::get("u") }}';
        if( seg != '' && seg == 'review' ) {
            $(".add-review-toggle").slideToggle("slow");
        }*/

        var placeSearch, autocomplete;
        $('#frm-write-review').validate({
            ignore: [],
            errorClass: "error",
            errorElement : 'div',
        });
    });

    $('#frm-write-review').on('submit',function() {
        var form = $(this);
        if(form.valid()) {
            showProcessingOverlay();
            return true;
        }
    });
</script>
<script src="{{ url('/front/js/swiper.min.js') }}" type="text/javascript"></script>
<script>
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 3,
        //spaceBetween: 30,
        slidesPerGroup: 1,
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    /*var kaSwiper1 = new Swiper('#ka-swiper1', {
        loop: true,
        pagination: '.swiper-pagination',
        paginationClickable: true,
        centeredSlides: true,
        slidesPerView: 'auto',
        spaceBetween: 0,
        autoHeight: true,
        breakpoints: {
            768: {
                spaceBetweenSlides: 10
            }
        }
    });*/

</script>
<script type="text/javascript">
    function load_more(id) {
        $(id).parent().prev().slideDown();
        $(id).parent().slideUp();
    }
    function load_less(id) {
        $(id).parent().next().slideDown();
        $(id).parent().slideUp();
    }

    function review_load_more(id) {
        $(id).parent().prev().slideDown();
        $(id).parent().slideUp();
    }
    function review_load_less(id) {
        $(id).parent().next().slideDown();
        $(id).parent().slideUp();
    }
</script>
@endsection