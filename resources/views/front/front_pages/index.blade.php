@extends('front.layout.master')
@section('main_content')

    <div class="clearfix"></div>
    <div class="title-common">
        <h1>{{ isset($page_data['page_title']) ? ucwords($page_data['page_title']) : '' }}</h1>
    </div>

    <div class="faq-main terms-conditions">
        <div class= "container">
           <!--  <div class="change-pass-bady"> -->

                <?php echo html_entity_decode($page_data['page_description']); ?>

           <!--  </div> -->
        </div>
    </div>

    <div class="clearfix"></div>
@include('front.common.testimonial')

@endsection
