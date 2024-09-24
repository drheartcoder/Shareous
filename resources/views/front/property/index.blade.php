@extends('front.layout.master')                
@section('main_content')
<style type="text/css">
.star-reviews-list .fa {
    color: #ffb618;
    font-size: 14px;
}
.grays-star {
    color: #ffb618;
    font-size: 14px;
}
</style>

<script type="text/javascript" language="javascript" src="{{ url('/front/js/jquery.geocomplete.min.js') }}"></script> 
<!-- <link href="{{ url('/front/css/fSelect.css') }}" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{{ url('/front/js/fSelect.js') }}"></script> -->

<div id="main"></div>
<div class="clearfix"></div>

<div class="title-common">
    <div class="container">
        @include('front.common.search_bar') 
    </div>
</div>

<div class="list-main-list">
    <form id="frmSearch">
        <?php
            $property_type_name = Request::input('property_type');
            $property_slug = str_slug($property_type_name,'-');
        ?>
        <div class="hide-show-bar">
            <div class="icn-hide"> <a class="back-filter-hide" href="javascript:void(0)"></a></div>
            <div class="red-txt">Refine Results</div>
            <?php
                if($property_slug == 'warehouse') {
                    $price_lable = 'Price Per Sq. Ft';
                }
                elseif($property_slug == 'office-space') {
                   $price_lable = 'Price Per Office';
                }
                else {
                   $price_lable = 'Price Per Night';
                }
            ?>
            <input type="hidden" name="property_max_value" id="property_max_value" value="{{ $property_max_value }}">
            <div class="pricepernight">{{ $price_lable }}<span><i class="fa fa-rupee"></i>0 - <i class="fa fa-rupee"></i>{{ $property_max_value }}</span></div>
            <div class="range-t input-bx">
                <div id="slider-price-range" class="slider-rang"></div>
                <div class="amount-no" id="slider_price_range_txt"></div>
                <input type="hidden" name="price_max" id="price_max" value="{{ Request::get('price_max') != '' ? Request::get('price_max') : '' }}">
                <input type="hidden" name="price_min" id="price_min" value="{{ Request::get('price_min') != '' ? Request::get('price_min') : '' }}">
            </div>
            @if($property_slug == 'warehouse')
                <div class="title-guestt">Select Propert Status</div>
                <div class="form-group mb20">
                    <div class="select-style">
                       <select class="frm-select" name="property_working_status" id="property_working_status">
                              <option value="">Select Property status</option>
                              <option value="open"  @if(Request::get('property_working_status')!='' && Request::get('property_working_status')=='open') selected="" @endif>Open</option>
                              <option value="closed"  @if(Request::get('property_working_status')!='' && Request::get('property_working_status')=='closed') selected="" @endif>Closed</option>
                        </select>
                    </div>
                </div>
            @elseif($property_slug == 'office-space')
                <div class="title-guestt">Price Per</div>
                <div class="form-group mb20">
                    <div class="select-style">
                       <select class="frm-select" name="price_per" id="price_per">
                            <option value="">Price Per</option>
                            <option value="person" @if(Request::get('price_per')!='' && Request::get('price_per')=='person') selected="" @endif>Person</option>
                            <option value="private-room"  @if(Request::get('price_per')!='' && Request::get('price_per')=='private-room') selected="" @endif>Private Room</option>
                            <option value="dedicated-desk"  @if(Request::get('price_per')!='' && Request::get('price_per')=='dedicated-desk') selected="" @endif>Dedicated Desk</option>
                            <option value="cubicles"  @if(Request::get('price_per')!='' && Request::get('price_per')=='cubicles') selected="" @endif>Cubicles</option>option>
                        </select>
                    </div>
                </div>

                <div id="div_price_per" style="display: none;">
                    <div class="title-guestt" id="div_price_per_title">No. Of Employee</div>
                    <div class="form-group mb20">
                        <input type="text" name="no_of_employee" id="no_of_employee" value="{{ Request::get('no_of_employee') != '' ? Request::get('no_of_employee') : "" }}" />
                    </div>
                </div>

            @else
                <div class="title-guestt">Bedrooms</div>      
                <div class="form-group mb20">
                    <input type="text" name="min_bedrooms" id="min_bedrooms" value="{{Request::get('min_bedrooms')!=''?Request::get('min_bedrooms'):""}}" />
                </div>
                <div class="title-guestt">Bathrooms</div>
                <div class="form-group mb20">
                    <input type="text" name="min_bathrooms" id="min_bathrooms" value="{{Request::get('min_bathrooms')!=''?Request::get('min_bathrooms'):""}}"/>
                </div>
            @endif

            @if($property_slug == 'warehouse' || $property_slug == 'office-space')
            <div class="title-guestt">Build Type</div>
                <div class="form-group mb20">
                    <div class="select-style">
                        <select class="frm-select" name="build_type" id="build_type">
                            <option value="">Select Build Type</option>
                            <option value="RCC" @if(Request::get('build_type')!='' && Request::get('build_type')=='RCC') selected="" @endif>RCC</option>
                            <option value="PEB"  @if(Request::get('build_type')!='' && Request::get('build_type')=='PEB') selected="" @endif>PEB</option>
                            <option value="shed"  @if(Request::get('build_type')!='' && Request::get('build_type')=='shed') selected="" @endif>Shed</option>
                            <option value="open"  @if(Request::get('build_type')!='' && Request::get('build_type')=='open') selected="" @endif>Open</option>
                            <option value="closed"  @if(Request::get('build_type')!='' && Request::get('build_type')=='closed') selected="" @endif>closed</option>
                        </select>
                    </div>
                </div>
            @endif
            <div class="title-guestt">Rating</div>
            <div class="form-group mb20">
                <div class="select-style">
                    <select class="frm-select" name="cmb_rating" id="cmb_rating">
                        <option value="">Select Rating</option>
                        <option value="ASC" @if(Request::get('cmb_rating')!='' && Request::get('cmb_rating')=='ASC') selected="" @endif>Low to High</option>
                        <option value="DESC" @if(Request::get('cmb_rating')!='' && Request::get('cmb_rating')=='DESC') selected="" @endif>High to Low</option>
                    </select>
                </div>
            </div>

            <div class="title-guestt">Featured</div>
            <div class="form-group mb20">
                <div class="select-style">
                    <select class="frm-select" id="is_featured" name="is_featured" >
                    <option value="">--Select--</option>
                    <option value="yes"  @if(Request::get('is_featured')!='' && Request::get('is_featured')=='yes') selected="selected" @endif>Yes</option>
                    <option value="no"   @if(Request::get('is_featured')!='' && Request::get('is_featured')=='no')  selected="selected" @endif>No</option>
                    </select>
                </div>
            </div>

            <div class="title-guestt">Amenities</div>
            <div class="form-group mb20">
                <div class="select-style">
                    <select class="form-control test" name="amenities[]" multiple="multiple" id="property_amenities"></select>
                </div>
            </div>

            @if(\Request::input('city'))
            <input type="hidden" name="city" id="locality" value="{{\Request::input('city')}}">
            @endif

            @if(\Request::input('state'))
            <input type="hidden" name="state" id="administrative_area_level_1" value="{{\Request::input('state')}}">
            @endif

            @if(\Request::input('country'))
            <input type="hidden" name="country" id="country" value="{{\Request::input('country')}}">
            @endif

            @if(\Request::input('postal_code'))
            <input type="hidden" name="postal_code" id="postal_code" value="{{\Request::input('postal_code')}}">
            @endif

            @if(\Request::input('latitude'))
            <input type="hidden" name="latitude" id="latitude" value="{{\Request::input('latitude')}}">
            @endif

            @if(\Request::input('longitude'))
            <input type="hidden" name="longitude" id="longitude" value="{{\Request::input('longitude')}}">
            @endif

            @if(\Request::input('location'))
            <input type="hidden" name="location" id="location" value="{{\Request::input('location')}}">
            @endif

            @if(\Request::input('checkin'))
            <input type="hidden" name="checkin" id="checkin" value="{{\Request::input('checkin')}}">
            @endif

            @if(\Request::input('checkout'))
            <input type="hidden" name="checkout" id="checkout" value="{{\Request::input('checkout')}}">
            @endif

            @if(\Request::input('guests'))
            <input type="hidden" name="guests" id="guests" value="{{\Request::input('guests')}}">
            @endif

            @if(\Request::input('property_type'))
            <input type="hidden" name="property_type" id="property_type" value="{{\Request::input('property_type')}}">
            @endif

            <div class="contact-btn vactinos">
                <button class="login-btn" type="submit" id="btn_submit">Apply</button>
                <a href="{{url('/property')}}" class="login-btn" name="btn_submit" id="btn_submit">Clear</a>
            </div>

        </div>
    </form>

    <div class="container-fluid" id="listing_div">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-7">
                <div class="fiter-top">
                    <div class="shwoing1">Showing  <span>{{$from or '0'}} - {{$to or '0'}}</span> of <span>{{$total or '0'}}</span> Properties
                    </div>
                    <button  type="button" class="login-btn filter-btn mor-filter-btnn" id="showmenu"><span> </span>More Filter</button>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
            @include('front.layout._operation_status')
            <div class="col-sm-12 col-md-7 col-lg-7">
                <div id="show_message"></div>
                
                <div id="add_class">
                    <?php 
                        if(auth()->guard('users')->user() == null) {
                            $url              = Request::fullUrl();
                            $notification_url = str_replace(url('/'), "", $url);

                            if(Session::get('notification_url') == '' && !empty(Session::get('notification_url'))) {
                                Session::set('notification_url', $notification_url);
                            }
                            else {
                                Session::put('notification_url','');
                                Session::set('notification_url', $notification_url);
                            }
                        }
                    ?>
                    @if(isset($arr_property) && is_array($arr_property) && sizeof($arr_property)>0)
                    @foreach($arr_property as $property)                 
                    <div class="list-vactions-details total-plot">
                        <div class="image-list-vact">
                            
                            @if(auth()->guard('users')->user()!=null) 
                            @if(auth()->guard('users')->user()['id']!=$property['user_id'] && Session::get('user_type') != null && Session::get('user_type') != '4') 

                            @if(count(check_favorite_property(auth()->guard('users')->user()['id'],$property['id'])) > 0)
                                <a onclick="makePropertyFavourite('{{ base64_encode($property['id']) }}');" class="favorat-icn" ><i class="fa fa-heart"></i></a>
                            @else
                                <a onclick="makePropertyFavourite('{{ base64_encode($property['id']) }}');" class="favorat-icn"  ><i class="fa fa-heart-o"></i></a>
                            @endif
                            @else
                            <!-- <a onclick="showAlert('Invalid User');" class="favorat-icn" ><i class="fa fa-heart-o"></i></a> -->
                            @endif

                            @else
                                <a href="{{url('/login')}}" class="favorat-icn" ><i class="fa fa-heart-o"></i></a>
                            @endif  

                            @if( isset($property['property_image']) && $property['property_image'] != '' && file_exists($property_image_base_path.$property['property_image']))
                                <img src="{{ $property_image_public_path.$property['property_image'] }}" alt="property image" />
                            @else
                                <img src="{{ url('/') }}/front/images/Listing-page-no-image.jpg" alt="" />
                            @endif
                        </div>

                        <?php
                            $booking_url = $module_url_path.'/view/'.$property['property_name_slug'];
                            if( !empty(Session::get('BookingRequestData.checkin') ) && Session::get('BookingRequestData.checkin') != "" ) {
                                $checkin_date = base64_encode(Session::get('BookingRequestData.checkin'));
                                $booking_url = $booking_url.'/'.$checkin_date;
                            }
                            
                            if( !empty(Session::get('BookingRequestData.checkout') ) && Session::get('BookingRequestData.checkout') != "" ) {
                                $checkout_date = base64_encode(Session::get('BookingRequestData.checkout'));
                                $booking_url = $booking_url.'/'.$checkout_date;
                            }
                            
                            if( !empty(Session::get('BookingRequestData.guests') ) && Session::get('BookingRequestData.guests') != "" ) {
                                $no_of_guests = base64_encode(Session::get('BookingRequestData.guests'));
                                $booking_url = $booking_url.'/'.$no_of_guests;
                            }
                        ?>

                        <div class="content-list-vact for-price">
                            <h3><a href="{{ $booking_url }}" target="new">
                            {{ isset($property['property_name']) ? title_case($property['property_name']) : '' }} {{ isset($property['property_type_name']) ? '('.title_case($property['property_type_name']).')' : '' }}</a></h3>

                            <div class="review-cont"><img src="{{ url('/front/images/map-icns.png') }}"> {{ isset($property['address']) ? title_case($property['address']) : '' }}</div>

                            @if($property['total_property_reviews'] != 0)
                            <div class="star-reviews-list pull-left">
                                <?php
                                    $starNumber = '';
                                    if( isset($property['average_rating']) && $property['average_rating'] != '' )
                                    {
                                        $starNumber = $property['average_rating'];
                                        for( $x = 1; $x <= $starNumber; $x++ ) {
                                            echo '<img src="'.url('/').'/front/images/star1.png" />';
                                        }
                                        if ( strpos($starNumber, '.' )) {
                                            echo '<img src="'.url('/').'/front/images/half-star.png" />';
                                            $x++;
                                        }
                                        while ( $x <= 5 ) {
                                            echo '<img src="'.url('/').'/front/images/star2.png" />';
                                            $x++;
                                        }
                                    }
                                ?>
                            </div>

                            <div class="review-cont">{{ $property['total_property_reviews'] }} reviews</div>
                            @endif
                            <?php $property_slug = get_property_type_slug($property['property_type_id'] ); ?>
                            <div class="guest-threebox">
                                <ul>
                                    @if($property_slug == 'warehouse' || $property_slug == 'office-space')
                                        <li>{{ isset($property['property_area']) ? $property['property_area'] : '0' }} Sq.Ft<div class="txt-p">Total area</div></li>
                                        <li>{{ isset($property['total_build_area']) ? $property['total_build_area'] : '0' }} Sq.Ft<div class="txt-p">Total Build Area</div></li>
                                        <li>{{ isset($property['admin_area']) ? $property['admin_area'] : '0' }} Sq.Ft<div class="txt-p">Admin Area</div></li>
                                    @else
                                        <li>{{ isset($property['number_of_guest']) ? $property['number_of_guest'] : '' }} <div class="txt-p">Guests</div></li>
                                        <li>{{ isset($property['number_of_bedrooms']) ? $property['number_of_bedrooms'] : '' }}<div class="txt-p">Bedroom</div></li>
                                        <li>{{ isset($property['number_of_bathrooms']) ? $property['number_of_bathrooms'] : '' }}<div class="txt-p">Bathroom</div></li>
                                    @endif
                                </ul>
                            </div>
                            <?php
                            $property_id = isset($property['id']) ? $property['id'] : '';

                            if( $property_slug == 'warehouse' ) {
                                $price = isset($property['price_per_sqft']) ? $property['price_per_sqft'] : '0';
                            }
                            else if($property_slug == 'office-space') {
                                $room     = isset($property['room']) ? $property['room'] : 'off';
                                $desk     = isset($property['desk']) ? $property['desk'] : 'off';
                                $cubicles = isset($property['cubicles']) ? $property['cubicles'] : 'off';

                                $room_price     = isset($property['room_price']) ? $property['room_price'] : '0';
                                $desk_price     = isset($property['desk_price']) ? $property['desk_price'] : '0';
                                $cubicles_price = isset($property['cubicles_price']) ? $property['cubicles_price'] : '0';
                            }
                            else {
                                $price = isset($property['price_per_night']) ? $property['price_per_night'] : '0';
                            }

                            $session_currency = \Session::get('get_currency');
                            $currency_icon = \Session::get('get_currency_icon');

                            if($property_slug != 'office-space') {
                                if( $property['currency_code'] != $session_currency ) {
                                    $currency_amount = currencyConverterAPI($property['currency_code'], $session_currency, $price);
                                }
                                else {
                                    $currency_amount = $price;
                                }
                            }
                            else if($property_slug == 'office-space') {
                                if( $property['currency_code'] != $session_currency ) {
                                    $currency_room_amount     = currencyConverterAPI($property['currency_code'], $session_currency, $room_price);
                                    $currency_desk_amount     = currencyConverterAPI($property['currency_code'], $session_currency, $desk_price);
                                    $currency_cubicles_amount = currencyConverterAPI($property['currency_code'], $session_currency, $cubicles_price);
                                }
                                else {
                                    $currency_room_amount     = $room_price;
                                    $currency_desk_amount     = $desk_price;
                                    $currency_cubicles_amount = $cubicles_price;
                                }
                            }
                            ?>
                            <div class="bottom-box price-lar">
                                <div class="pricesection-value">
                                    @if($property_slug == 'warehouse')
                                        {!! $currency_icon !!} {{ number_format($currency_amount, 2, '.', '') }}<span>/per Sq.Ft</span>
                                    @elseif($property_slug == 'office-space')
                                        <?php
                                            $room_price_html = $desk_price_html = $cubicles_price_html = '';

                                            if( $room == 'on' ) {
                                                $room_price_html = '<div class="price-lar-inner">'.$currency_icon.' '.number_format($currency_room_amount, 2, '.', '').'<span>/room</span></div>';
                                            }

                                            if( $desk == 'on' ) {
                                                $desk_price_html = '<div class="price-lar-inner">'.$currency_icon.' '.number_format($currency_desk_amount, 2, '.', '').'<span>/desk</span></div>';
                                            }

                                            if( $cubicles == 'on' ) {
                                                $cubicles_price_html = '<div class="price-lar-inner">'.$currency_icon.' '.number_format($currency_cubicles_amount, 2, '.', '').'<span>/cubicles</span></div>';
                                            }
                                        ?>

                                        {!! $room_price_html !!} {!! $desk_price_html !!} {!! $cubicles_price_html !!}
                                    @else
                                        {!! $currency_icon !!} {{ number_format($currency_amount, 2, '.', '') }}<span>/per night</span>
                                    @endif
                                </div>
                                <a href="{{ $booking_url }}" target="new" class="view-details-btn">View Details</a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    @endforeach
                    @else
                    <div class="list-vactions-details">
                        <div class="no-record-found"></div>
                    </div>
                    @endif
                </div>
                @if(isset($obj_pagination) && $obj_pagination != null)
                    @include('front.common.pagination',['obj_pagination' => $obj_pagination])
                @endif
            </div>
            <div class="col-sm-12 col-md-5 col-lg-5">
                <div class="map-listing main sticky-maps" id="map" style="width:100%; height: 800px;"></div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="<?php if(isset($_REQUEST['amenities'])){ print_r($_REQUEST['amenities']); }  ?>" id="searched_aminites" name="searched_aminites">
<script type="text/javascript" src="https://cdn.rawgit.com/googlemaps/v3-utility-library/master/markerclustererplus/src/markerclusterer.js"></script>
<script>
    var locations = [
    <?php
    if(count($arr_property_maps)) {
        foreach($arr_property_maps as $row) {

            if(!empty($row['property_latitude']) && !empty($row['property_longitude'])){
                $rating_html = 0;
                $enc_prop_id = "'".base64_encode($row['id'])."'";
                $rating_html = str_replace('"', "'", $row['average_rating']);
                $rating_html = number_format($rating_html,1); 
       
                $property_type_slug = get_property_type_slug($row['property_type_id']);
                if ($property_type_slug == 'warehouse') {
                    $price     = $row['price_per_sqft'];
                    $price_per = 'Sq.Ft';
                }
                elseif ($property_type_slug == 'office-space') {
                    $price     = $row['price_per_office'];
                    $price_per = $row['price_per'];
                }
                else {
                    $price     = $row['price_per_night'];
                    $price_per = 'night';
                }
                
                $session_currency = \Session::get('get_currency');
                $currency_icon    = \Session::get('get_currency_icon');

                if( $row['currency_code'] != $session_currency ) {
                    $currency_amount = currencyConverterAPI($row['currency_code'], $session_currency, $price);
                }
                else {
                    $currency_amount = $price;
                }
                
                if( isset($row['property_image']) && $row['property_image'] != '' && file_exists($property_image_base_path.$row['property_image'])) {
                    $img_exist = $property_image_public_path.$row['property_image'];
                }
                else {
                    $img_exist = url('/'). "/front/images/Listing-page-no-image.jpg";
                }
                
                $html_data  = "";
                $html_data .= "<div  class='col-sm-12 col-md-12 col-lg-12'><div class='main-block' style='width:100%;max-width:350px'><div class='multi-box'><span class='box-image'><img src='".$img_exist."' alt='img-1' style='height: 250px'><span class='cfa-and-wish'><span class='cfa'><span> ".$session_currency." </span>".number_format($currency_amount, 2, '.', '')."<span class='cfa-txt'>/". $price_per."</span></span></span><span class='wishlist-cfa map_fav' id='map_fav_".$row['id']."' >";

                if(auth()->guard('users')->user() != null) {
                     if( auth()->guard('users')->user()['id'] != $row['user_id'] && Session::get('user_type') != null && Session::get('user_type') != '4') {
                        if(count(check_favorite_property(auth()->guard('users')->user()['id'],$row['id'])) > 0) {
                            $html_data .= "<a onclick=makePropertyFavourite(".$enc_prop_id."); class='favorat-icn' ><i class='fa fa-heart'></i></a>";
                        }
                        else {
                            $html_data .= "<a onclick=makePropertyFavourite(".$enc_prop_id."); class='favorat-icn' ><i class='fa fa-heart-o'></i></a>";
                        }
                    }
                    else {
                       /* $msg = 'Invalid User';
                        $html_data .= "<a onclick=showAlert(".$msg."); class='favorat-icn' ><i class='fa fa-heart-o'></i></a>";*/
                    }
                }
                else {
                    $html_data .=  "<a href='".url('login')."' class='favorat-icn' ><i class='fa fa-heart-o'></i></a>";
                }

                $html_data .= "</span><span class='category-block'><span class='cate-title'>";
                if($row['total_property_reviews'] != 0)
                {
                    $html_data .= "<span class='star-reviw' title='Average Rating'>".$rating_html."</span><span class='rating-name' title='Total Ratings'>(".$row['total_property_reviews'].")</span>";
                }

                $html_data .= "</span></span><span class='sellar-btn'><a href='".$module_url_path."/view/".$row['property_name_slug']."'><button type='button' class='btn-sell'><i class='fa fa-link'></i></button></a></span><span class='over-lay-blue'></span></span><a href='".$module_url_path."/view/".$row['property_name_slug']."' class='price-review'><span class='cate-address'>".$row['property_name']."</span><span class='clearfix'></span></a></div></div></div>"; ?>

                ["<?php echo $html_data; ?>",<?php echo $row['property_latitude']; ?>,<?php echo $row['property_longitude']; ?>],
                <?php
            }
        }
    } ?>
    ];
    <?php
    if(count($arr_property_maps))
    {
        foreach($arr_property_maps as $row) 
        {
            if(!empty($row['property_latitude']) && !empty($row['property_longitude']))     
            { 
                $minlat = false;
                $minlng = false;
                $maxlat = false;
                $maxlng = false;

                if ($minlat === false) { $minlat = $row['property_latitude']; } else { $minlat = ($row['property_latitude'] < $minlat) ? $row['property_latitude'] : $minlat; }
                if ($maxlat === false) { $maxlat = $row['property_latitude']; } else { $maxlat = ($row['property_latitude'] > $maxlat) ? $row['property_latitude'] : $maxlat; }
                if ($minlng === false) { $minlng = $row['property_longitude']; } else { $minlng = ($row['property_longitude'] < $minlng) ? $row['property_longitude'] : $minlng; }
                if ($maxlng === false) { $maxlng = $row['property_longitude']; } else { $maxlng = ($row['property_longitude'] > $maxlng) ? $row['property_longitude'] : $maxlng; }
                // Calculate the center
                $lat = $maxlat - (($maxlat - $minlat) / 2);
                $lon = $maxlng - (($maxlng - $minlng) / 2);

            }
        }
    }
    ?>

    var defaultLat =  <?php if(isset($lat)){ echo $lat; } else { echo '19.997453'; } ?>;
    var defaultLng =  <?php if(isset($lon)){ echo $lon; } else { echo '73.789802'; } ?>;
    var _address   = "<?php  if(isset($_REQUEST['location']) && $_REQUEST['location'] != ""){ echo $_REQUEST['location']; } else { echo ""; }?>";
    var map;       
    var mc;/*marker clusterer*/
    var mcOptions = {
        gridSize: 20,
        maxZoom: 7,
        zoom:3,
        imagePath: "https://cdn.rawgit.com/googlemaps/v3-utility-library/master/markerclustererplus/images/m",
    };
    /*global infowindow*/
    var infowindow = new google.maps.InfoWindow();
    /*geocoder*/

    var _address = _address;
    mapInitialize(defaultLat, defaultLng,_address);
    function createMarker(latlng,text)
    {
        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
            icon: SITE_URL+"/front/images/loc.png"
        });
        /*get array of markers currently in cluster*/
        var allMarkers = mc.getMarkers();

        /*check to see if any of the existing markers match the latlng of the new marker*/
        if (allMarkers.length != 0) {
            for (i=0; i < allMarkers.length; i++) {
                var existingMarker = allMarkers[i];
                var pos = existingMarker.getPosition();
                if (latlng.equals(pos)) {
                    text = text + " " + locations[i][0];
                }
            }
        }
        google.maps.event.addListener(marker, 'click', function(){
            infowindow.close();
            infowindow.setContent(text);
            infowindow.open(map,marker);
        });
        mc.addMarker(marker);
        return marker;
    }

    function mapInitialize(lat,lng,_address)
    {
        var geocoder = new google.maps.Geocoder(); 
        geocoder.geocode( { 'address': _address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                /*map.setCenter(results[0].geometry.location);*/
                map.setOptions({ maxZoom: 15 });
                map.fitBounds(results[0].geometry.viewport);
            }
        });

        var options = {
            zoom      : 2,
            center    : new google.maps.LatLng(lat,lng), 
            mapTypeId : google.maps.MapTypeId.ROADMAP,
            zoomControlOptions: {
                position: google.maps.ControlPosition.LEFT_TOP
                         //position: google.maps.ControlPosition.LEFT_CENTER
                         //position: google.maps.ControlPosition.RIGHT_CENTER
                     },
                     streetViewControlOptions: {
                        position: google.maps.ControlPosition.LEFT_TOP
                    },
                    fullscreenControl: true,
              //scrollwheel: false,
          };
          map = new google.maps.Map(document.getElementById('map'), options); 
          /*marker cluster*/
          var gmarkers = [];
          mc = new MarkerClusterer(map, [], mcOptions);
          for (i=0; i<locations.length; i++)
          {
            var latlng = new google.maps.LatLng(parseFloat(locations[i][1]),parseFloat(locations[i][2]));                
            gmarkers.push(createMarker(latlng,locations[i][0]));
        }          
    }
</script>
<style>
.fs-arrow{display: none !important;}
</style>
<script>
     $( function() {
        $( "#buttonmore" ).on( "click", function() {
            $( "#ShowMore" ).toggleClass( "newClass", 1000);
        });

        $( "#buttonmoreAmenity" ).on( "click", function() {
            $( "#ShowMoreAmenity" ).toggleClass( "newClass", 1000);
        });
    });

    /*Price Range slider Start*/
    $(function() {
        var property_max_value = $("#property_max_value").val();
        var temp_max           = $('#property_max_value').val();
        var price_max          = $("#price_max").val();
        var price_min          = $("#price_min").val();

        if(price_max != '') {
            temp_max = price_max;
        }

        $("#slider-price-range").slider({
            range:true,
            min:0,
            max:property_max_value,
            values:[price_min,temp_max],
            slide:function(s,e) {
                setPriceRange(e.values[0],e.values[1]);
            },
            change:function(event,ui) {
                $("#price_min").val(ui.values[0]);
                $("#price_max").val(ui.values[1]);      
            }
        });

        setPriceRange($("#slider-price-range").slider("values",0), $("#slider-price-range").slider("values", 1));
    });

    function setPriceRange(slider_price_min, slider_price_max) {            
        $("#slider_price_range_txt").html("<span class='slider_price_min'><i class='fa fa-rupee'></i> " + slider_price_min + "</span>  <span class='slider_price_max'><i class='fa fa-rupee'></i> " + slider_price_max + " </span>");
        $("#price_min").val(slider_price_min);
        $("#price_max").val(slider_price_max);
    }
    /*Price Range Slider End*/

    $(".filter-btn").on("click", function(){
        $(".list-main-list").addClass("filter-open"); 
    });
    $(".back-filter-hide").on("click", function(){
        $(".list-main-list").removeClass("filter-open"); 
    });


    // Allow only Numeric Characters
    $('#min_bedrooms, #min_bathrooms').keyup(function() {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });

    function makePropertyFavourite(enc_property_id)
    {
        var is_user_login = '{{ validate_login('users') }}';
        if(is_user_login == "")
        {
            showAlert("Please Login First!", "error");
            return false;
        }
        else
        {
            $.ajax({ 

                'url'   : "{{ $module_url_path }}/favourite/"+enc_property_id,
                'type'  : 'get',                    
                success : function(response)   
                {
                    hideProcessingOverlay();
                    if(response.status == 'success')
                    {      
                        var property_id = "'"+response.property_id+"'";
                        if(response.favourite == 'add')
                        {
                            var fav = '<a onclick=makePropertyFavourite('+property_id+'); class="favorat-icn"><i class="fa fa-heart"></i></a>';

                             $("#map_fav_"+response.new_property_id).html(fav); 
                        }
                        else if(response.favourite == 'remove')
                        {
                            var non_fav = '<a onclick=makePropertyFavourite('+property_id+'); class="favorat-icn"><i class="fa fa-heart-o"></i></a>'; 
                             $("#map_fav_"+response.new_property_id).html(non_fav);
                        }
                        $("#add_class").load(location.href + " #add_class");
                        showAlert(response.message, "success");
                    }
                    else
                    {
                        showAlert(response.message, "error");
                    }                      
                }
            });
        }
    }

    function makeStatusMessageHtml(status, message)
    {
        str = '<div class="alert alert-' + status + '">' + '<a aria-label="close" data-dismiss="alert" class="close" href="#">' + '×</a>' + message + '</div>';
        return str;
    }


    function get_proprty_amenities(property_type)
    {
        var url = "{{ $module_url_path }}/getaminities/"+property_type;
        $(function() { $('.test').fSelect(); });

        $.ajax({
            method: 'GET',
            url: url,
            data: { id: property_type, },
            beforeSend:function(){ },
            success:function(response) {
                if(response.status == "success") {
                    if(typeof(response.arr_aminities) == "object") {
                         
                        var searched_aminities = $('#searched_aminites').val(); 
                        var option = '<option value="">--Select Amenities--</option>';
                        $(response.arr_aminities).each( function(index, aminity) {
                            var selected = '';
                            if(searched_aminities.indexOf(aminity.id) != -1){
                                var selected = 'selected="selected"';
                            } 
                            option += '<option value="'+aminity.id+'" '+selected+'>'+aminity.aminity_name+'</option>';
                        });

                        var select_amenities_box = $('#property_amenities');
                        $(select_amenities_box).find('option').remove().end().append(option);
                        hideProcessingOverlay();
                    }

                    $(function() {
                        $('.test').fSelect('destroy');
                        $('.test').fSelect('create');
                    });
                }
                if(response.status == "error") {

                    var option = '<option value="">--No Available Amenities--</option>';
                    var select_amenities_box = $('#property_amenities');
                    $(select_amenities_box).find('option').remove().end().append(option);
                    hideProcessingOverlay();

                    $(function() {
                        $('.test').fSelect('destroy');
                        $('.test').fSelect('create');
                    });
                }

                hideProcessingOverlay();
                return false;
            },
        });
    }

    $('#property_type_for_amenities').bind('change', function(ev) {
        var value = $(this).val();
        get_proprty_amenities(value);
    });

    function get_price_per(){
        var price_per = $("#price_per").val();
        
        if($.trim(price_per) == 'person')
        {
            $("#div_price_per").css('display', 'block');
            $("#div_price_per_title").html("No. of Person");
        }
        else if($.trim(price_per) == 'private-room')
        {
            $("#div_price_per").css('display', 'block');
            $("#div_price_per_title").html("No. of Private Room");
        }
        else if($.trim(price_per) == 'dedicated-desk')
        {
            $("#div_price_per").css('display', 'block');
            $("#div_price_per_title").html("No. of Dedicated Desk");
        }
        else if($.trim(price_per) == 'cubicles')
        {
            $("#div_price_per").css('display', 'block');
            $("#div_price_per_title").html("No. of Cubicles");
        }
        else
        {
            $("#div_price_per").css('display', 'none');
        }
    }

    $("#price_per").change(function(){
        get_price_per();
    });

    $( document ).ready(function() {
        var value = $('#property_type').val();
        get_proprty_amenities(value);

        get_price_per();
    });
</script>
@endsection