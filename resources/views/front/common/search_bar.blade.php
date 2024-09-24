<link href="{{ url('/front/css/fSelect.css') }}" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{{ url('/front/js/fSelect.js') }}"></script>
<link href="{{ url('/front/css/select-mania.css') }}" rel="stylesheet" type="text/css" />

    <div class="search-wrapper search-wrappr-add">
        <?php 
            $checkin = $checkout = $guests = '';
            if(Session::get('BookingRequestData') == null) {
                $checkin       = Request::get('checkin');
                $checkout      = Request::get('checkout');
                $guests        = Request::get('guests');
                $property_type = Request::get('property_type');
            }
            else {
                $checkin       = Session::get('BookingRequestData.checkin');
                $checkout      = Session::get('BookingRequestData.checkout');
                $guests        = Session::get('BookingRequestData.guests');
                $property_type = Session::get('BookingRequestData.property_type');
            }
        ?>
        <input type="hidden" id="page_location" value="{{ \Request::segment(1) }}" >

        <form method="get" action="{{ url('/property') }}" id="frmSearch" class="form-line-active">

            <div class="forms-cl common-wrapper-class">
                <select class="input-box age-box-input property_type" name="property_type" id="property_type" autofocus tabindex="1">
                    <option value="">Select Property Type</option>
                    @if(isset($arr_property_type) && count($arr_property_type)>0)
                        @foreach($arr_property_type as $row)
                            <option slug="<?php echo str_slug($row->name); ?>" value="{{ $row->name }}" @if($property_type == $row->name) selected @endif>{{ $row->name }}</option>
                        @endforeach
                    @endif
                </select>
                <div class="addres-icon" style="line-height: 20px; font-size: 20px;"><i class="fa fa-angle-down"></i></div>
                
                <div id="err_property_type" class="error-meg" style="display: none;">
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>Select a Property Type
                </div>
            </div>

            <div class="forms-cl address-cols common-wrapper-class">
                <input type="text" class="input-box age-box-input map-icn search-location" required="" placeholder="Destination, City, Address" onFocus="geolocate()" id="autocomplete" name="location" value="{{ $location or '' }}" tabindex="2" />
                <div class="addres-icon map-mrkt"><i class="fa fa-map-marker"></i></div>
                <div class="brd">&nbsp;</div>

                <div id="err_address" class="error-meg" style="display: none;">
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i><span id="err_address_txt"></span>
                </div>
            </div>

            <div class="forms-cl">
                <input id="check_in_datepicker" class="input-box age-box-input cander-cin datepicker-input" placeholder="Check-in" readonly="readonly" name="checkin" value="{{ $checkin }}"  autocomplete="off" tabindex="3" />
                <div class="addres-icon"><i class="fa fa-calendar"></i></div>
                <div class="brd">&nbsp;</div>
            </div>

            <div class="forms-cl">
                <input id="check_out_datepicker" class="input-box age-box-input cander-cin datepicker-input" placeholder="Check-out" readonly="readonly" name="checkout"  value="{{ $checkout }}" autocomplete="off" tabindex="4" />
                <div class="addres-icon"><i class="fa fa-calendar"></i></div>
                <div class="brd">&nbsp;</div>
            </div>

            <?php
                $style = "display:inline-block";
                $property_type_slug = trim(str_slug($property_type,'-'));
                if ($property_type_slug == 'warehouse' || $property_type_slug == 'office-space') {
                    $style = "display:none";
                }
            ?>

            <div class="forms-cl div_Guests" style="{{$style}}">
                <input type="number" class="input-box age-box-input numbers-icn" name="guests" placeholder="01 Guests" min="1" max="9999999999" maxlength="10" value="{{ $guests }}" onInput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" onKeyDown="if(this.value.length==10 && event.keyCode!=8) return false;" tabindex="5">
                <span class="error"></span>
            </div>

            <input type="hidden" name="city" id="locality" value="{{ isset($city) && !empty($city) ? $city : '' }}" >
            <input type="hidden" name="state" id="administrative_area_level_1" value="{{ isset($state) && !empty($state) ? $state : '' }}" >
            <input type="hidden" name="country" id="country" value="{{ isset($country) && !empty($country) ? $country : '' }}" >
            <input type="hidden" name="postal_code" id="postal_code" value="{{ isset($postal_code) && !empty($postal_code) ? $postal_code : '' }}" >
            <input type="hidden" name="latitude" id="latitude" value="{{ \Request::input('latitude') }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ \Request::input('longitude') }}">

            <button type="submit" onclick="javascript: return search();" class="search-btn hvr-back-pulse" tabindex="6">Search <img src="{{ url('/') }}/front/images/search-inc.png" alt="" /> </button>

        </form>
        <div class="clr"></div>
    </div>

    <script type="text/javascript" src="{{ url('/front/js/select-mania.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            //check onload
            var type = "<?php echo str_slug($property_type,'-'); ?>";
            if (type == 'warehouse' || type == 'office-space') {
                $('.div_Guests').hide();
                $(".search-wrapper").removeClass("search-wrappr-add");
            }
            else {
                $('.div_Guests').show();
                $(".search-wrapper").addClass("search-wrappr-add");
            }

            /*Check onchange*/
            $('#property_type').on('change',function()
            {
                var property_type = $('option:selected','#property_type').attr('slug');
                var selected_property_type = $('option:selected','#property_type').val();

                if (property_type == 'warehouse' || property_type == 'office-space') {
                    $('.div_Guests').hide();
                    $(".search-wrapper").removeClass("search-wrappr-add"); 
                } 
                else {
                    $('.div_Guests').show();
                    $(".search-wrapper").addClass("search-wrappr-add");
                }

                if( $.trim(selected_property_type) != '' ) {
                    $(".property_type").closest('.common-wrapper-class').removeClass("search-location-focus");
                    $("#err_property_type").css('display', 'none');
                }
                else {
                    $(".property_type").closest('.common-wrapper-class').addClass("search-location-focus");
                    $("#err_property_type").css('display', 'block');
                }
            });

            $("#autocomplete").keyup(function(){
                var autocomplete = $("#autocomplete").val();
                if (autocomplete == '') {
                    return false;
                }
                else {
                    $(".search-location").closest('.common-wrapper-class').removeClass("search-location-focus");
                    $("#err_address").css('display', 'none');
                }
            });

            $("#autocomplete").change(function(){
                $("#locality").val('');
                $("#administrative_area_level_1").val('');
                $("#country").val('');
                $("#postal_code").val('');
                $("#latitude").val('');
                $("#longitude").val('');
            });
        });
        
        $(function(){
            var autocomplete = $('#frmSearch').find("#autocomplete").val();
        });

        $(".search-location").keyup(function() {
            $(".search-location").closest('.common-wrapper-class').removeClass("search-location-focus");
            $("#err_property_type").css('display', 'none');
        });

        function search() {
            var page_location = $("#page_location").val();
            var property_type = $('#property_type').val();
            var autocomplete  = $('#frmSearch').find("#autocomplete").val();
            var latitude      = $("#latitude").val();
            var longitude     = $("#longitude").val();

            if ($.trim(property_type) == '' && autocomplete == '') {
                $(".property_type").closest('.common-wrapper-class').addClass("search-location-focus");
                $(".search-location").closest('.common-wrapper-class').addClass("search-location-focus");
                
                $("#err_address_txt").html('Enter a Destination, City, Address');

                $("#err_property_type").css('display', 'block');
                $("#err_address").css('display', 'block');
                return false;
            }

            if ($.trim(property_type) == '') {
                $(".property_type").closest('.common-wrapper-class').addClass("search-location-focus");
                $("#property_type").focus();

                $("#err_property_type").css('display', 'block');
                return false;
            }
            else {
                $(".property_type").closest('.common-wrapper-class').removeClass("search-location-focus");
                $("#err_property_type").css('display', 'none');
            }

            if (autocomplete == '') {
                $(".search-location").closest('.common-wrapper-class').addClass("search-location-focus");
                $("#autocomplete").focus();
                $("#err_address_txt").html('Enter a Destination, City, Address');
                $("#err_address").css('display', 'block');
                return false;
            }
            else {
                $(".search-location").closest('.common-wrapper-class').removeClass("search-location-focus");
                $("#err_address").css('display', 'none');
            }

            if (latitude == '' && longitude == '') {
                $(".search-location").closest('.common-wrapper-class').addClass("search-location-focus");
                //$("#autocomplete").focus();
                $("#err_address_txt").html('Select a valid Destination, City, Address');
                $("#err_address").css('display', 'block');
                return false;
            }
            else {
                $(".search-location").closest('.common-wrapper-class').removeClass("search-location-focus");
                $("#err_address").css('display', 'none');
            }

            if( $.trim(property_type) != '' && autocomplete != '' && $.trim(page_location) != '') {
                /*ajax_main_search();*/
            }
        }

        var checkin  = $("#check_in_datepicker").val();
        var checkout = $("#check_out_datepicker").val();

        if( $.trim(checkin) != '' ) {
            var checkinAr   = checkin.split('-');
            var checkinDate = checkinAr[1] + '/' + checkinAr[0].slice(-2) + '/' + checkinAr[2];
            var start_date  = new Date(checkinDate);
        }
        else {
            var start_date = new Date();
        }

        var today = new Date();
        var future_year = new Date();
            future_year.setFullYear(new Date().getFullYear()+20);

        $("#check_in_datepicker").datepicker({
            todayHighlight: true,
            autoclose: true,
            startDate: today,
            endDate: future_year,
            clearBtn: true,
            format: 'dd-mm-yyyy',
        }).on('hide', function(e){
            var selected = $(this).val();
            if(selected != '') {
                $('#check_out_datepicker').focus();
            }
        }).on("keydown", function(e){
            if (e.which == 13) {
                var selected = $(this).val();
                if(selected != '') {
                    $('#check_out_datepicker').focus();
                }
            }
        });

        start_date.setDate(start_date.getDate() + 1);
        $("#check_out_datepicker").datepicker({
            autoclose: true,
            startDate: start_date,
            endDate: future_year,
            clearBtn: true,
            format: 'dd-mm-yyyy',
        });

        $("#check_in_datepicker").change(function(){
            $("#check_out_datepicker").val('');
            $("#check_out_datepicker").datepicker('remove');

            var date     = $(this).val();
            var dateAr   = date.split('-');
            var newDate  = dateAr[1] + '/' + dateAr[0].slice(-2) + '/' + dateAr[2];
            var new_date = newDate;
            var end_date = new Date(new_date);
            end_date.setDate(end_date.getDate() + 1);

            $("#check_out_datepicker").datepicker({
                autoclose: true,
                startDate: end_date,
                endDate: future_year,
                clearBtn: true,
                format: 'dd-mm-yyyy',
            });
        });

        document.querySelector("#guests").addEventListener("keypress", function (evt) {
            if (evt.which < 48 || evt.which > 57) {
                evt.preventDefault();
            }
        });

        $('.max').click(function() {
            if ($('#guests').val().trim() == '') {
                var guests = parseInt(0);
            } else {
                var guests = parseInt($('#guests').val());
            }
            $('#guests').val(guests+1);
        });

        $('.min').click(function() {
            var guests = parseInt($('#guests').val());
            if(guests != 1 && guests > 0 && $('#guests').val() != '') {
                $('#guests').val(guests - 1);
            }
        });

        // Prevent form submit on location selection
        $('#autocomplete').keypress(function(e) {
            if ( e.which == 13 ) e.preventDefault();
        });

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBYfeB69IwOlhuKbZ1pAOwcjEAz3SYkR-o&libraries=places&callback=initAutocomplete"></script>
    <!-- <script type="text/javascript" src="{{url('/front/js/general.js')}}"></script> -->

    <script>
        jQuery( document ).ready( function($) {
            /* HTML5 Geolocation */
            var address = $("#autocomplete").val();
            if( $.trim(address) == '' ) {
                navigator.geolocation.getCurrentPosition(
                    function( position ) {
                        /* Current Coordinate */
                        var lat = position.coords.latitude;
                        var lng = position.coords.longitude;

                        $("#latitude").val(lat);
                        $("#longitude").val(lng);

                        var latlng = new google.maps.LatLng(lat, lng);
                        var geocoder = new google.maps.Geocoder();
                        geocoder.geocode({'latLng': latlng}, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (results[1]) {
                                    var indice = 0;
                                    for (var j = 0; j<results.length; j++){
                                        if (results[j].types[0]=='locality') {
                                            indice = j;
                                            break;
                                        }
                                    }
                                    for (var i=0; i<results[j].address_components.length; i++) {
                                        $("#autocomplete").val(results[j].formatted_address);
                                        if (results[j].address_components[i].types[0] == "locality") {
                                            city = results[j].address_components[i];
                                            $("#locality").val(city.long_name);
                                        }
                                        if (results[j].address_components[i].types[0] == "administrative_area_level_1") {
                                            region = results[j].address_components[i];
                                            $("#administrative_area_level_1").val(region.long_name);
                                        }
                                        if (results[j].address_components[i].types[0] == "country") {
                                            country = results[j].address_components[i];
                                            $("#country").val(country.long_name);
                                        }
                                    }
                                }
                            }
                        });
                    }
                );
            }
        });
    </script>
