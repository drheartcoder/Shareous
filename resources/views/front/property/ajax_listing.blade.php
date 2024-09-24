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

<div id="main"></div>
<div class="clearfix"></div>

<div class="title-common">
    <div class="container">
        @include('front.common.search_bar')
    </div>
</div>

<div class="list-main-list">
    
    <form id="frmSearch">
        <div class="hide-show-bar">
            <div class="icn-hide"> <a class="back-filter-hide" href="javascript:void(0)"></a></div>
            <div class="red-txt">Refine Results</div>

            <div class="pricepernight">pricepernight<span><i class="fa fa-rupee"></i>0 - <i class="fa fa-rupee"></i>property_max_price</span></div>
            <div class="range-t input-bx">
                <div id="slider-price-range" class="slider-rang"></div>
                <div class="amount-no" id="slider_price_range_txt"></div>
            </div>

            <div class="title-guestt">Select Propert Status</div>
            <div class="form-group mb20">
                <div class="select-style">
                    <select class="frm-select" name="property_working_status" id="property_working_status">
                        <option value="">Select Property status</option>
                        <option value="open">Open</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
            </div>

            <div class="title-guestt">Price Per</div>
            <div class="form-group mb20">
                <div class="select-style">
                   <select class="frm-select" name="price_per" id="price_per">
                        <option value="">Price Per</option>
                        <option value="person">Person</option>
                        <option value="private-room">Private Room</option>
                        <option value="dedicated-desk">Dedicated Desk</option>
                        <option value="cubicles">Cubicles</option>option>
                    </select>
                </div>
            </div>

            <div id="div_price_per">
                <div class="title-guestt" id="div_price_per_title">No. Of Employee</div>
                <div class="form-group mb20">
                    <input type="text" name="no_of_employee" id="no_of_employee" />
                </div>
            </div>

            <div class="title-guestt">Bedrooms</div>      
            <div class="form-group mb20">
                <input type="text" name="min_bedrooms" id="min_bedrooms" />
            </div>
            <div class="title-guestt">Bathrooms</div>
            <div class="form-group mb20">
                <input type="text" name="min_bathrooms" id="min_bathrooms" />
            </div>

            <div class="title-guestt">Build Type</div>
            <div class="form-group mb20">
                <div class="select-style">
                    <select class="frm-select" name="build_type" id="build_type">
                        <option value="">Select Build Type</option>
                        <option value="RCC">RCC</option>
                        <option value="PEB">PEB</option>
                        <option value="shed">Shed</option>
                        <option value="open">Open</option>
                        <option value="closed">closed</option>
                    </select>
                </div>
            </div>

            <div class="title-guestt">Rating</div>
            <div class="form-group mb20">
                <div class="select-style">
                    <select class="frm-select" name="cmb_rating" id="cmb_rating">
                        <option value="">Select Rating</option>
                        <option value="ASC">Low to High</option>
                        <option value="DESC">High to Low</option>
                    </select>
                </div>
            </div>

            <div class="title-guestt">Featured</div>
            <div class="form-group mb20">
                <div class="select-style">
                    <select class="frm-select" id="is_featured" name="is_featured" >
                    <option value="">Select Featured</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                    </select>
                </div>
            </div>

            <div class="title-guestt">Amenities</div>
            <div class="form-group mb20">
                <div class="select-style">
                    <select class="form-control test" name="amenities[]" multiple="multiple" id="property_amenities"></select>
                </div>
            </div>

            <div class="contact-btn vactinos">
                <button class="login-btn" type="submit" id="btn_submit">Apply</button>
                <a href="{{ url('/property') }}" class="login-btn" name="btn_submit" id="btn_submit">Clear</a>
            </div>

        </div>
    </form>

    <div class="container-fluid" id="listing_div">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-7">
                <div class="fiter-top">
                    <div class="shwoing1">Showing <span id="listing_start_number">0</span> <b>-</b> <span id="listing_end_number">0</span> of <span id="listing_total_number">0</span> Properties
                    </div>
                    <button  type="button" class="login-btn filter-btn mor-filter-btnn" id="showmenu"><span> </span>More Filter</button>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>

            @include('front.layout._operation_status')

            <div class="col-sm-12 col-md-7 col-lg-7">
                <div id="show_message"></div>
                <div id="add_class"></div>

                <div class="prod-pagination"></div>

            </div>

            <div class="col-sm-12 col-md-5 col-lg-5">
                <div class="map-listing main sticky-maps" id="map" style="width:100%; height: 800px;"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdn.rawgit.com/googlemaps/v3-utility-library/master/markerclustererplus/src/markerclusterer.js"></script>

<script type="text/javascript">
    
    $(document).ready(function(){

        $(".filter-btn").on("click", function() {
            $(".list-main-list").addClass("filter-open");
        });

        $(".back-filter-hide").on("click", function() {
            $(".list-main-list").removeClass("filter-open");
        });

    });

</script>

@endsection