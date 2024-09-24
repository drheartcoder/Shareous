@if(isset($arr_testimonials) && sizeof($arr_testimonials)>0 && is_array($arr_testimonials))
<!-- Testimonial Start   -->
    <div class="testimonial-section">
        <div class="container">
            <div id=myCarousel-tow class="carousel slide" data-ride=carousel>
                <ol class=carousel-indicators>
                    <?php $cnt = 0; ?>
                    @foreach($arr_testimonials as $key => $testimonial)
                        <li data-target="#myCarousel-tow" data-slide-to='{{$cnt}}' @if($cnt == '0') class=active @endif>
                            @if(isset($testimonial['image']) && !empty($testimonial['image']) && $testimonial['image']!=null && file_exists($testimonial_image_base_img_path.$testimonial['image'] ))
                                <img src="{{$testimonial_image_public_img_path.$testimonial['image'] }}" alt="">
                            @else
                                <img src="{{$testimonial_image_public_img_path.'default.png'}}" alt="">
                            @endif
                        </li>
                        <?php $cnt++; ?>
                    @endforeach
                </ol>

                <?php $cnt1 = 0; ?>
                <div class=carousel-inner>
                    @foreach($arr_testimonials as $key => $testimonial)
                        <?php
                            if($cnt1 == '0')
                            {
                                $status = "active";
                            }
                            else
                            {
                                $status = "";
                            }
                        ?>
                        <div class="item {{$status}}">
                            <div class="slider-test">
                                <div class="arrows-quote">
                                    <img src="{{url('/front')}}/images/qouta-icns.png" alt="" />
                                </div>
                                <div class="p-text-slide">{!! isset($testimonial['message'])? $testimonial['message'] : '' !!}
                                    <div class="liner-border"></div>
                                </div>
                                <div class="ownr-name"><span>{{isset($testimonial['title'])? $testimonial['title'] :'' }}</span></div>
                            </div>
                        </div>
                        <?php $cnt1++; ?>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End   -->
@endif