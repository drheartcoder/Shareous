@extends('front.layout.master')                
@section('main_content')

<style type="text/css">
.half-start{font-size: 16px;display: inline-block;color: #ffb618;}
</style>
<div class="banner">
    <div class="container">
        <div class="banner-text-block">
            <h1>Book your Perfect Holiday Rental, Today</h1>
            <p>Discover the best holiday homes. Plan The Vacation of Your Dreams</p>
            @include('front.common.search_bar')
        </div>
    </div>
</div>

@if(isset($arr_featured_data) && count($arr_featured_data)>0)
<div class="featured-luxury">
    <div class="title-headings">
        <h2>Featured Luxury Homes</h2>
        <p> Need inspiration for your next holiday? Explore the best properties and the hottest locations. </p>
    </div>
    <div class="container">
        <div class="row">
            <div id="add_class">
                @foreach($arr_featured_data as $featured_data)
                <?php
                    $property_image = get_property_image($featured_data->id);
                    $total = 0; $count = 0; $tmp_str_rating = '';  $no_reviews  = 0; $starNumber  = '';                                       
                    if (isset($arr_property_review)) {
                        foreach($arr_property_review as $rating) {
                            if($rating['property_id'] == $featured_data->id) {
                                $total += floatval($rating['rating']);
                                $count++;
                            }
                        }
                    }
                    if ($count != 0) {
                        $no_reviews = $total/$count;
                    }

                    $property_type_slug = get_property_type_slug($featured_data->property_type_id);
                    if ($property_type_slug == 'warehouse') {
                        $price     = $featured_data->price_per_sqft;
                        $price_per = 'Sq.Ft';
                    }
                    else if ($property_type_slug == 'office-space') {
                        $price     = $featured_data->price_per_office;
                        $price_per = 'Night';
                    }
                    else {
                        $price     = $featured_data->price_per_night;
                        $price_per = 'Night';
                    }

                    $session_currency = \Session::get('get_currency');
                    $currency_icon = \Session::get('get_currency_icon');
                    if( $featured_data->currency_code != $session_currency ) {
                        $currency_amount = currencyConverterAPI($featured_data->currency_code, $session_currency, $price);
                    }
                    else {
                        $currency_amount = $price;
                    }
                ?>
            <div class="col-sm-6 col-md-6 col-lg-4">
                <div class="main-block home-change">
                    <div class="multi-box">
                        <span class="box-image">

                            @if( isset($property_image) && $property_image != '' && file_exists($property_image_base_path.$property_image) )
                                @if(getimagesize($property_image_base_path.$property_image) === false)
                                    <img src="{{ url('/') }}/front/images/Listing-page-no-image.jpg" alt="" />
                                @else
                                    <img src="{{ $property_image_public_path.$property_image }}" alt="property image" style="height: 250px;" />
                                @endif
                            @else
                                <img src="{{ url('/') }}/front/images/Listing-page-no-image.jpg" alt="" />
                            @endif 

                            <span class="cfa-and-wish">
                                <span class="cfa"> 
                                    {!! $currency_icon !!} {{ number_format($currency_amount, 2, '.', '') }}<span class="cfa-txt">/{{ $price_per }}</span>
                                </span>
                            </span>

                            <span class="wishlist-cfa">
                                @if (auth()->guard('users')->user() != null)
                                    @if (auth()->guard('users')->user()['id'] != $featured_data->user_id)
                                        @if(count(check_favorite_property(auth()->guard('users')->user()['id'],$featured_data->id)) > 0)
                                            <a onclick="makePropertyFavourite('{{ base64_encode($featured_data->id) }}');" class="favorat-icn" >
                                               <i class="fa fa-heart"  id="fav{{ $featured_data->id }}"></i>
                                            </a>
                                       @else
                                           <a onclick="makePropertyFavourite('{{ base64_encode($featured_data->id) }}');" class="favorat-icn"  >
                                               <i class="fa fa-heart-o" id="fav{{ $featured_data->id }}"></i>
                                           </a>
                                       @endif                 
                                   @else
                                        <a onclick="showAlert('Invalid User');" class="favorat-icn" ><i class="fa fa-heart-o"></i></a>
                                   @endif
                               @else
                                    <a onclick="checkLogin()" class="favorat-icn" ><i class="fa fa-heart-o"></i></a>
                               @endif
                           </span>
                           <span class="category-block">
                            <span class="cate-title">
                                @if($count != 0)
                                <div class="star-reviews-list pull-left">
                                    <?php  if (isset($no_reviews) && $no_reviews!='') {
                                        $starNumber = $no_reviews;
                                        for($x=1;$x<=$starNumber;$x++) {
                                            echo '<img src="'.url('/').'/front/images/star1.png" />';
                                        }

                                        if (strpos($starNumber,'.')) {
                                            echo '<img src="'.url('/').'/front/images/half-star.png" />';
                                            $x++;
                                        }

                                        while ($x <= 5) {
                                            echo '<img src="'.url('/').'/front/images/star2.png" />';
                                            $x++;
                                        }                              
                                    } ?>
                                </div>
                                <span class="rating-name">(<?php echo $count;?>)</span>
                                @endif
                            </span>
                        </span>

                        <span class="sellar-btn"> 
                            <a href="{{ url('/') }}/property/view/{{ $featured_data->property_name_slug }}">
                                <button type="button" class="btn-sell"><i class="fa fa-link"></i></button>
                            </a>
                        </span>
                        <span class="over-lay-blue"></span>
                    </span>
                    <div class="index-listing-book-now index-for">
                        <a href="{{ url('/') }}/property/view/{{ $featured_data->property_name_slug }}" class="price-review for-book">
                            <span class="cate-address for-book-now">{{ isset($featured_data->property_name) ? $featured_data->property_name : 'N/A' }}</span>
                            <div class="clearfix"></div>
                        </a>
                        <a href="{{ url('/') }}/property/view/{{ $featured_data->property_name_slug }}" class="view-details-btn float-right hone-on">Book Now</a>
                        <div class="clearfix"></div>
                    </div> 

                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <a href="{{url('/property?is_featured=yes')}}" class="btn-more">Show All</a>
    </div>
</div>
</div>
</div>
@endif
<!-- Featured Luxury Homes End -->
<!-- Plan The Vacation of Your Dreams Start -->
<div class="plan-the-vacation">
    <div class="title-headings">
        <h2>Plan The Vacation of Your Dreams</h2>
        <p>Explore some of the best tips from around the world from our partners and friends. Discover some of the most popular Homes and Villas.</p>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-4">
                <div class="vacation-dreams">
                    <div class="img-vacatin"> <img src="{{url('/front')}}/images/search-home.png" alt="" /> </div>
                    <h3>Search and Explore</h3>
                    <p>Find accommodation that suits your budget and style.</p>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4">
                <div class="vacation-dreams">
                    <div class="img-vacatin"> <img src="{{url('/front')}}/images/contact-book.png" alt="" /> </div>
                    <h3>Contact and Book</h3>
                    <p>Contact potential hosts, confirm check-in dates and book securely.</p>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4">
                <div class="vacation-dreams">
                    <div class="img-vacatin"> <img src="{{url('/front')}}/images/book-favi.png" alt="" /> </div>
                    <h3>Feel at Home</h3>
                    <p>Enjoy the conveniences of home. Cook breakfast in your own kitchen.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Plan The Vacation of Your Dreams End -->
<!--   Browse our Most Popular Destinations Start  -->

@if (isset($arr_popular_prop) && count($arr_popular_prop) > 0)
<div class="featured-luxury paddinglet-right">
    <div class="title-headings">
        <h2>Browse our Most Popular Destinations</h2>
        <p>Need inspiration for your next holiday? Explore the best properties and the hottest locations.</p>
    </div>
    <div class="container-fluid">
        <ul id="flexiselDemo1">
            @foreach ($arr_popular_prop AS $popular_property)
            <li> 
                <div class=turisum-img-blo>
                    <img src="{{url('/')}}/uploads/property_image/{{ $popular_property['property_images'][0]['image'] }}" alt="" />
                    <div class=turisum-recent-blo>
                        <div class=clearfix></div>
                        <h1>{{ $popular_property['property_name'] }} </h1>
                        <div class=turisum-cuntent>
                            <div class="totl-pcin">
                                {{ $popular_property['description'] }}
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <a href="{{ url('property/view/')}}/{{ $popular_property['property_name_slug'] }}" class="buttonshowall-link">View</a>
                    </div>
                    <div class="category-block"></div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif


@include('front.common.testimonial')
<script type="text/javascript">
   function makePropertyFavourite(enc_property_id)
   {
    var is_user_login = '{{ validate_login('users') }}';
    var property_id   = enc_property_id ;
    var dec_property_id = atob(property_id);  
    var favicon = $('#fav'+dec_property_id).attr('class');
    if(is_user_login == "") {
      showAlert("Please Login First!", "error");
      return false;
    } else {
      $.ajax({
          'url':"{{ $module_url_path }}/favourite/"+property_id,
          'type':'get',                    
          success:function(response)   
          {
            hideProcessingOverlay();
            if(response.status == 'success') {
                if(favicon == 'fa fa-heart'){
                 $('#fav'+dec_property_id).attr('class','fa fa-heart-o');
                }
                else  
                {
                 $('#fav'+dec_property_id).attr('class','fa fa-heart');
                }
                //$("#add_class").load(location.href + " #add_class");
                showAlert(response.message, "success");
            } else {
                showAlert(response.message, "error");
            }                      
        }
    });
  }
}
function checkLogin()
{
    $.ajax({
      'url':"{{ $home_url_path}}",
      'type':'get',
      success:function(response)  
      {
        window.location.href = "{{ url('/') }}/login";
    } 
    
});
}
</script>

@endsection