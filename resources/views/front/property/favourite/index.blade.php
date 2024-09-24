@extends('front.layout.master')                
@section('main_content')

<div class="clearfix"></div>

<div class="overflow-hidden-section">
        <div class="titile-user-breadcrum">
            <div class="container">
                <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions">
                    <h1>My Favourite</h1>
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
                        @if(Session::has('success'))
                          <div class="alert alert-success alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{Session::get('success')}}
                          </div>
                        @endif
                        @if(Session::has('error'))
                          <div class="alert alert-danger alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{Session::get('error')}}
                          </div>
                        @endif

                  <div id="add_class">

                        @if(isset($arr_property) && is_array($arr_property) && sizeof($arr_property)>0)
                        @foreach($arr_property as $property)

                        <?php
                          $property_type_id   = isset($property['property_type_id']) ? $property['property_type_id'] : 0;
                          $property_type_slug = get_property_type_slug($property_type_id);
                        ?>
                        <div class="list-vactions-details user-spaces host">
                          <div class="image-list-vact small-lis-img">

                            @if(count(check_favorite_property(auth()->guard('users')->user()['id'],$property['property_id'])) > 0)
                                <a onclick="makePropertyFavourite('{{ base64_encode($property['property_id']) }}');" class="favorat-icn">
                                   <i class="fa fa-heart"></i>
                                </a>
                            @else
                                <a onclick="makePropertyFavourite('{{ base64_encode($property['property_id']) }}');" class="favorat-icn">
                                   <i class="fa fa-heart-o"></i>
                                </a>
                            @endif                 

                            @if(isset($property['property_image']) && $property['property_image']!='' && file_exists($property_image_base_path.$property['property_image']))
                              <img src="{{$property_image_public_path.$property['property_image']}}" alt="property image" />
                            @else
                              <img src="{{ url('/') }}/front/images/Listing-page-no-image.jpg" alt="" />
                            @endif

                          </div>

                          <?php
                              $property_name = isset($property['property_name']) ?  title_case($property['property_name']) : '';
                              $property_type_name = isset($property['property_type_name']) ?  '('.title_case($property['property_type_name']).')' : '';
                              $property_slug = str_slug($property_type_name,'-');
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

                          <div class="content-list-vact small-lis-cont">
                            <a href="{{$module_url_path.'/view/'.$property['property_name_slug']}}"><h3>{{ $property_name.' '.$property_type_name }}</h3></a>
                            
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

                            <div class="guest-threebox">
                              <ul>
                                @if($property_slug == 'warehouse' || $property_slug == 'office-space')
                                    <li>{{ isset($property['property_area']) ? $property['property_area'] : '0' }} Sq.Ft<div class="txt-p">Total area</div></li>
                                    <li>{{ isset($property['total_build_area']) ? $property['total_build_area'] : '0' }} Sq.Ft<div class="txt-p">Total Build Area</div></li>
                                    <li>{{ isset($property['admin_area']) ? $property['admin_area'] : '0' }}Sq.Ft<div class="txt-p">Admin Area</div></li>
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
                              <a href="{{$module_url_path.'/view/'.$property['property_name_slug']}}" class="view-details-btn float-right">View Details</a>
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

                         @if(isset($obj_pagination) && $obj_pagination!=null)            
                         @include('front.common.pagination',['obj_pagination' => $obj_pagination])
                         @endif
                         
                        </div>           
                  </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>

<script>
  function deletePropertyFavourite(enc_property_id)
  {
    var is_user_login = '{{ validate_login('users') }}';
    var property_id   = enc_property_id ;
  
    $.ajax({

              'url':"{{ $module_url_path }}/delete/"+property_id,
              'type':'get',                    
              success:function(response)   
              {
                    hideProcessingOverlay();
                    if(response.status=='success')
                    {
                      var msg = makeStatusMessageHtml('success',response.message);
                    }
                    else
                    {
                      var msg = makeStatusMessageHtml('danger',response.message);
                    }
                    $("#show_message").html(msg);                          
              }

          });
  }

  function makePropertyFavourite(enc_property_id)
  {
    var is_user_login = '{{ validate_login('users') }}';
    var property_id   = enc_property_id ;

    if(is_user_login == "")
    {
      showAlert("Please Login First!", "error");
      return false;
    }
    else
    {
      $.ajax({ 

          'url':"{{ $module_url_path }}/favourite/"+property_id,
          'type':'get',                    
          success:function(response)   
          {
            hideProcessingOverlay();
            if(response.status == 'success')
            {      
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

</script>

@endsection
