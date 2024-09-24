@extends('front.layout.master')                
@section('main_content')

    <div class="clearfix"></div>
     <div class="overflow-hidden-section">
    <div class="titile-user-breadcrum">
        <div class="container">
            <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions">
                <h1>My Review & Rating</h1>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="change-pass-bg main-hidden">
        <div class="container">
            <div class="row">

                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 left-bar-dashboard">    
                    <div id="left-bar">
                         @include('front.layout.left_bar_host')
                    </div>
                </div>                
                <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                     @include('front.layout._operation_status')
                     
                     @if(isset($arr_property_review['data']) && count($arr_property_review['data'])>0)
                     @foreach($arr_property_review['data'] as $booking)                                        
                        <div class="list-vactions-details user-spaces short-img">
                            <div class="list-vact-edit">
                             <a href="{{url('/property/view/'.$booking['property_name_slug'].'')}}?booking_id={{base64_encode($booking['id'])}}" class="list-vact-edit-btn"><i class="fa fa-pencil"></i></a>  
                            </div>    

                            <div class="image-list-vact">
                                @if(isset($booking['image']) && $booking['image']!='' && file_exists($property_image_base_path.$booking['image']))
                                    <img src="{{$property_image_public_path.$booking['image']}}" alt="property image" />
                                @else
                                    <img src="{{ url('/') }}/front/images/Listing-page-no-image.jpg" alt="" />
                                @endif
                            </div>
                            <div class="content-list-vact">
                                <h3><a href="{{url('/property/view/'.$booking['property_name_slug'].'')}}">{{isset($booking['property_name'])? title_case($booking['property_name']):''}} ({{isset($booking['property_type_name']) ? $booking['property_type_name'] : '-'}})</a></h3>
                                
                                 <div class="rating-review-stars">
                                    <span class="start-rate-count-blue">{{isset($booking['rating'])?$booking['rating']:''}}</span>
                                     <div class="start-details">           
                                        <?php $starNumber  = '';
                                        $review_star = '';
                                        if(isset($booking['rating']) && $booking['rating']!='')
                                        {
                                            $starNumber = $booking['rating'];                                                                                     
                                            for($x=1;$x<=$starNumber;$x++) 
                                            {
                                                echo '<img src="'.url('/').'/front/images/star1.png" />';
                                            }
                                            if (strpos($starNumber,'.')) 
                                            {
                                                echo '<img src="'.url('/').'/front/images/half-star.png" />';
                                                $x++;
                                            }
                                            while ($x<=5) 
                                            {
                                                echo '<img src="'.url('/').'/front/images/star2.png" />';
                                                $x++;
                                            }                                           
                                        }?>         
                                    </div>
                                </div> 
                                <div class="review-discri-after-star">
                                    {{$booking['message']}}                    
                                </div>                      
                            </div>
                            <div class="clearfix"></div>
                        </div>                       
                        @endforeach
                        @else
                        <div class="list-vactions-details">
                            <div class="no-record-found"></div>
                            <!-- <div class="content-list-vact" style="color: red;font-size: 13px;">
                                <p>Sorry!, we couldn't find any Review & Rating!.</p>
                            </div> -->
                        </div>
                        @endif                        
                        <div class="paginations">
                           {!! $page_link !!}
                        </div>

                </div>
            </div>
        </div>
    </div>
    </div>
    <!--Rating half star css-->
    <link rel="stylesheet" href="{{url('/front/css/star-rating.css')}}" />
    <!--rating demo-->
    <script type="text/javascript" language="javascript" src="{{url('/front/js/jquery.rating.js')}}"></script>
    <script type="text/javascript" language="javascript" src="{{url('/front/js/star-rating.js')}}"></script>

    <script type="text/javascript">
         $(".view-details-btn").on("click", function(){            
            $("#view_review_details").slideToggle("slow");
           // $(".add-review-toggle").slideUp("slow");
        });
    </script>

    <script type="text/javascript">

    $('.view_btn').on('click', function(){

        var review_id = $(this).attr('review_id'); 

        $("#view_review_details"+review_id).slideToggle("slow");
  });
        
    </script>

@endsection
  