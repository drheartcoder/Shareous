
@extends('front.layout.master')                
@section('main_content')
<style type="text/css">
    .half-start{font-size: 16px;display: inline-block;color: #ffb618;}
    .grays-star{
    color: color: #ffb618;
}
</style>

    <div class="clearfix"></div>
    <div class="overflow-hidden-section">
        <div class="titile-user-breadcrum">
            <div class="container">
                <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions">
                    <h1>My Listings</h1>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div> 

        <?php $keyword = Request::input('keyword'); ?> 

        <div class="change-pass-bg main-hidden">
            <div class="container">
                <div class="row">  
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 left-bar-dashboard">
                        <div id="left-bar-host">
                            @include('front.layout.left_bar_host')
                        </div>                        
                    </div>
                    
                    <form action="{{ $module_url_path }}/listing" method="get" name="frm_search_transaction" id="frm_search_transaction">
                        <div  class=" col-sm-8 col-md-3 col-lg-3">
                            <div class="form-group">
                                <input id="keyword" name="keyword" type="text" placeholder="Search keyword" value="{{ $keyword }}"/>
                            </div>
                        </div>
                        <div class=" col-sm-6 col-md-3 col-lg-3">
                            <div class="transac-search">
                                <button type="submit" class="btn-cancel tran-sear"><i class="fa fa-search"></i></button>
                                <a class="btn-pays tran-pays" href="{{ $module_url_path }}/listing"><i class="fa fa-retweet"></i></a>
                            </div>
                        </div>
                    </form>

                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                        @include('front.layout._operation_status')
                        @if(isset($arr_property) && is_array($arr_property) && sizeof($arr_property)>0)
                        @foreach($arr_property as $property)
                            <div class="list-vactions-details user-spaces host">
                                <div class="image-list-vact small-lis-img">
                                    @if(isset($property['property_image']) && $property['property_image']!='' && file_exists($property_image_base_path.$property['property_image']))
                                    <img src="{{$property_image_public_path.$property['property_image']}}" alt="property image" />
                                    @else
                                     <img src="{{ url('/') }}/front/images/Listing-page-no-image.jpg" alt="" />
                                    @endif
                                </div>
                                <div class="content-list-vact small-lis-cont">                                    
                                    @if(isset($property['admin_status']) && $property['admin_status']==1 || $property['admin_status']==3)
                                    <div class="host-my-list-boolets">
                                        <ul>
                                            <li><i class="fa fa-circle"></i></li>
                                            <li><i class="fa fa-circle"></i></li>
                                            <li><i class="fa fa-circle"></i></li>
                                        </ul>
                                        <div class="host-my-list-drop-menu">
                                            <ul>
                                                <li>                                                    
                                                    <a href="{{ $module_url_path.'/edit_step1/'.base64_encode($property['id']) }}">Edit Property</a>
                                                    <a onclick='delete_property("{{ base64_encode($property['id']) }}")'>Remove this Property</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    @endif

                                    <a href="{{$module_url_path.'/view/'.$property['property_name_slug']}}"><h3>{{ isset($property['property_name']) ? title_case($property['property_name']) : '' }} {{ isset($property['property_type_name']) ? '('.title_case($property['property_type_name']).')' : '' }}</h3></a>
                                    <?php 
                                        $total          = 0;
                                        $count          = 0;
                                        $no_reviews     = 0;
                                        $tmp_str_rating = '';

                                        if(isset($arr_property_review)) {
                                            foreach($arr_property_review as $rating) {
                                                if($rating['property_id'] == $property['id']) {
                                                    $total += floatval($rating['rating']);
                                                    $count++;
                                                }
                                            }
                                        }
                                        if($count != 0) {
                                            $no_reviews = $total/$count;
                                        }
                                    ?>

                                    @if($no_reviews != 0)
                                    <div class="star-reviews-list pull-left">
                                        <?php
                                            if(isset($no_reviews) && $no_reviews != '') {
                                                $starNumber = $no_reviews;
                                                for($x=1;$x<=$starNumber;$x++) {
                                                    echo '<img src="'.url('/').'/front/images/star1.png" />';
                                                }
                                                if (strpos($starNumber,'.')) {
                                                    echo '<img src="'.url('/').'/front/images/half-star.png" />';
                                                    $x++;
                                                }
                                                while ($x<=5) {
                                                    echo '<img src="'.url('/').'/front/images/star2.png" />';
                                                    $x++;
                                                }
                                            }
                                        ?>
                                    </div>
                                    <div class="review-cont"><?php echo $count;?> reviews</div>
                                    @endif

                                   <?php   
                                        $property_slug = get_property_type_slug($property['property_type_id'] );
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
                                   
                                    @if(isset($property['admin_status']) && $property['admin_status'] == 1)
                                        <div class="host-list-rejected">Pending</div>
                                    @elseif(isset($property['admin_status']) && $property['admin_status'] == 2)
                                        <div class="host-list-rejected approved for-height">Approved</div>
                                    @elseif(isset($property['admin_status']) && $property['admin_status'] == 3)
                                        <div class="host-list-rejected">Rejected</div>
                                    @elseif(isset($property['admin_status']) && $property['admin_status'] == 4)
                                        <div class="host-list-rejected">Permanent Rejected</div>
                                    @endif

                                    <div class="bottom-box price-lar small-list">
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
                                        <a href="{{ $module_url_path.'/view/'.$property['property_name_slug'] }}" class="view-details-btn float-right">View Details</a>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        @endforeach
                        @else
                            <div class="list-vactions-details"><div class="no-record-found"></div></div>
                        @endif

                        <div class="paginations">
                            @if(isset($obj_pagination) && $obj_pagination != null)            
                                @include('front.common.pagination',['obj_pagination' => $obj_pagination])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>

    <script type="text/javascript">
        function delete_property(id)
        {
            swal({
                title: "Are you sure",
                text: "Do you want to remove property?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-cancel",
                confirmButtonText: "Yes, remove it!",
                closeOnConfirm: false
            },
            function()
            {
                window.location.href = "{{$module_url_path.'/delete/'}}"+id;
            });
        }
    </script>

@endsection
   