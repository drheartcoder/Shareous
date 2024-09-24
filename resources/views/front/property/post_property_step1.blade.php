@extends('front.layout.master')                
@section('main_content')

<div id="main"></div>
<div class="clearfix"></div>
<div class="title-common">
   <h1>{{ $page_title or '' }}</h1>
</div>

<div id="property-step-1">
   <form action="{{ $module_url_path }}/store_step1" method="post" name="frm_post_property_step1" enctype="multipart/form-data" id="frm_post_property_step1">
      {{ csrf_field() }}
      <div class="change-pass-bg padding">
         <div class="container">
            <div class="transact-deta-back button outer">
               <a href="{{ url('/property/listing') }}" class="bookib-detai-back"> <i class="fa fa-long-arrow-left"></i> Cancel</a>
            </div>
            <div class="clearfix"></div>
            <div class="change-pass-bady">
               <div class="add-listing-one-head">Hi user, Let's get started listing your space.</div>
               <div class="row">
                  
                  @include('front.layout._operation_status')

                  <div class="col-sm-12 col-md-12 col-lg-12">
                     <div class="form-group">
                        <div class="select-style">
                           <select id="property_type" name="property_type">
                              <option value="">Select Property Type</option>
                              @if(isset($arr_property_type) && sizeof($arr_property_type)>0)
                                @foreach($arr_property_type as $property_type)
                                  <option @if(old('property_type') == $property_type) selected="" @endif value="{{ $property_type['id'] or '' }}" slug = "<?php echo strtolower(trim(str_slug($property_type['name'], '-'))); ?>">{{ $property_type['name'] or '' }}</option>
                                @endforeach
                              @endif
                           </select>
                           <div class="clearfix"></div>
                           <span class="error" id="err_property_type">{{ $errors->first('property_type') }}</span>
                        </div>
                     </div>
                     <div class="form-group">
                        <input type="text" value="{{ old('property_name') }}" data-rule-maxlength="250" maxlength="250" name="property_name" id="property_name" />
                        <label for="property">Your Property Name</label>
                        <span class="error" id="err_property_name">{{ $errors->first('property_name') }}</span>
                     </div>
                     <div class="form-group">
                        <textarea rows="2" data-rule-maxlength="1000" class="text-area" name="description" id="description">{{ old('description') }}</textarea>
                        <label for="description">Description</label>
                        <span class="error" id="err_description">{{ $errors->first('description') }}</span>
                     </div>
                  </div>
                
                  <div class="col-sm-6 col-md-6 col-lg-6" id="no_of_guest_div">
                     <div class="form-group quetio">
                        <input  type="text" name="no_of_guest" id="no_of_guest" data-rule-maxlength="6" data-rule-max="999999" data-msg-maxlength="Please enter no more than 6 digits." maxlength="6" data-rule-number="true" min="1" />
                        <label for="property">No Of Guest</label>
                        <span class="error" id="err_no_of_guest">{{ $errors->first('no_of_guest') }}</span>
                     </div>
                  </div>

                  <div class="col-sm-6 col-md-6 col-lg-6" id="no_of_bedrooms_div">
                     <div class="form-group quetio">
                        <input  type="text" onchange="addSleepingArrangement(this)" name="no_of_bedrooms" id="no_of_bedrooms" data-rule-maxlength="2" data-rule-max="10" data-msg-maxlength="Please enter no more than 2 digits." maxlength="2" data-rule-number="true" min="1" />
                        <label for="property">No Of Bedrooms</label>
                        <span class="error" id="err_no_of_bedrooms">{{ $errors->first('no_of_bedrooms') }}</span>
                     </div>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-6" id="bathrooms_div">
                     <div class="form-group quetio">
                        <input  type="text" name="bathrooms" id="bathrooms" data-rule-maxlength="6" data-rule-max="999999" data-msg-maxlength="Please enter no more than 6 digits." maxlength="6" data-rule-number="true" min="1" />
                        <label for="property">No Of Bathrooms</label>
                        <span class="error" id="err_bathrooms">{{ $errors->first('bathrooms') }}</span>
                     </div>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-6" id="no_of_beds_div">
                     <div class="form-group quetio">
                        <input  type="text" name="no_of_beds" id="no_of_beds" data-rule-maxlength="6" data-rule-max="999999" data-msg-maxlength="Please enter no more than 6 digits." maxlength="6" data-rule-number="true" min="1" />
                        <label for="property">No Of Beds</label>
                        <span class="error" id="err_no_of_beds">{{ $errors->first('no_of_beds') }}</span>
                     </div>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-6" id="property_working_status_div" style="display: none;">
                     <div class="form-group quetio">
                        <div class="select-style">
                           <select id="property_working_status" name="property_working_status">
                              <option value="">Select Property status</option>
                              <option value="open">Open</option>
                              <option value="closed">Closed</option>
                           </select>
                            <div class="clearfix"></div>
                           <span class="error" id="err_property_working_status">{{ $errors->first('property_working_status') }}</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-6" id="property_area_div" style="display: none;"> 
                     <div class="form-group quetio">
                        <input  type="text" name="property_area" id="property_area" maxlength="10" data-rule-number="true" data-rule-maxlength="10"/>
                        <label for="property">Total Area in Sq.Ft</label>
                        <span class="error" id="err_property_area">{{ $errors->first('property_area') }}</span>
                     </div>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-6" id="total_build_area_div" style="display: none;"> 
                     <div class="form-group quetio">
                        <input  type="text" name="total_build_area" id="total_build_area"  maxlength="10" data-rule-number="true" data-rule-maxlength="10"/>
                        <label for="property">Total Build Up Area</label>
                        <span class="error" id="err_total_build_area">{{ $errors->first('total_build_area') }}</span>
                     </div>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-6" id="admin_area_div" style="display: none;"> 
                     <div class="form-group quetio">
                        <input  type="text" name="admin_area" id="admin_area" maxlength="15" data-rule-number="true"/>
                        <label for="property">Admin Area</label>
                        <span class="error" id="err_admin_area">{{ $errors->first('admin_area') }}</span>
                     </div>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-6" id="no_of_slots_div" style="display: none;"> 
                     <div class="form-group quetio">
                        <input  type="text" name="no_of_slots" id="no_of_slots"  maxlength="15" data-rule-number="true"/>
                        <label for="property">No.Of Slots</label>
                        <span class="error" id="err_no_of_slots">{{ $errors->first('no_of_slots') }}</span>
                     </div>
                  </div>

                  <div class="clearfix"></div>

                  <div class="col-sm-6 col-md-6 col-lg-6" id="custom_type_div" style="display: none;">
                    <div class="form-group">
                        <div class="genders">Custom Type : </div>
                        <div class="radio-btns">
                            <div class="radio-btn">
                                <input id="bonded" name="custom_type" class="custom_type" type="radio" value="bonded">
                                <label for="bonded">Bonded</label>
                                <div class="check"></div>
                            </div>

                            <div class="radio-btn">
                                <input id="non-bonded" name="custom_type" class="custom_type" type="radio" value="non-bonded">
                                <label for="non-bonded">Non Bonded</label>
                                <div class="check">
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <span class="error" id="err_custom_type"></span>
                    </div>
                  </div>
                   <div class="col-sm-6 col-md-6 col-lg-6" id="management_div" style="display: none;">
                    <div class="form-group">
                        <div class="genders">Management : </div>
                        <div class="radio-btns">
                            <div class="radio-btn">
                                <input id="yes" name="management" class="management" type="radio" value="yes">
                                <label for="yes">Yes</label>
                                <div class="check"></div>
                            </div>

                            <div class="radio-btn">
                                <input id="no" name="management" class="management" type="radio" value="no">
                                <label for="no">No</label>
                                <div class="check">
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <span class="error" id="err_management"></span>
                    </div>
                </div>
                  <div class="col-sm-6 col-md-6 col-lg-6" id="good_storage_div" style="display: none;"> 
                     <div class="form-group quetio">
                        <input  type="text" name="good_storage" id="good_storage" data-rule-maxlength="50" maxlength="50"/>
                        <label for="property">Available good storage</label>
                        <span class="error" id="err_good_storage">{{ $errors->first('good_storage') }}</span>
                     </div>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-6" id="build_type_div" style="display: none;">
                     <div class="form-group quetio">
                        <div class="select-style">
                           <select id="build_type" name="build_type">
                              <option value="">Build Type</option>
                              <option value="RCC">RCC</option>
                              <option value="PEB">PEB</option>
                              <option value="Shed">Shed</option>
                              <option value="Open">Open</option>
                              <option value="Closed">closed</option>
                           </select>
                            <div class="clearfix"></div>
                           <span class="error" id="err_build_type">{{ $errors->first('build_type') }}</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-12 col-lg-12">
                     <div class="sleeping-srrangement-main" style="display:none;" id="no-of-bedroom-div">
                        <div class="comments-title main">Sleeping Arrangement</div>
                        <div class="comments-title-small" ><span id="no-of-bedroom"></span> Bedroom</div>
                        <div id="sleeping-arrangement-div" class="prop-step-respo"></div>
                     </div>
                     <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                           <div class="step-three-filds">
                              <div class="row">
                                
                                <div class="col-sm-4 col-md-4 col-lg-6">
                                    <div class="form-group">
                                       <div class="select-style">
                                          <select id="currency" name="currency">
                                            <option value="">Select Currency</option>
                                            @if(isset($currency_list) && sizeof($currency_list)>0)
                                              @foreach($currency_list as $currency)
                                                <option @if(old('currency')==$currency['currency']) selected="" @endif value="{{ $currency['id'] or '' }}">{{ $currency['currency_code'] }}</option>
                                              @endforeach
                                            @endif
                                          </select>
                                          <div class="clearfix"></div>
                                          <span class="error" id="err_currency">{{ $errors->first('currency') }}</span>
                                       </div>
                                    </div>
                                </div>

                                <div class="col-sm-4 col-md-4 col-lg-6" id="price_per_night_div">
                                    <div class="price-per-night-blo">
                                       <div class="form-group">
                                          <input  name="price" value="{{ old('price') }}" min=1 id="price" type="text" maxlength="10" data-rule-number="true" data-rule-maxlength="10"/>
                                          <label for="property">Price Per Night</label>
                                          <span class="error" id="err_price">{{ $errors->first('price') }}</span>
                                       </div>
                                    </div>
                                </div>

                                <div class="col-sm-4 col-md-4 col-lg-6" id="price_per_sqft_div" style="display: none;">
                                    <div class="price-per-night-blo">
                                       <div class="form-group">
                                          <input  name="price_per_sqft" value="{{ old('price_per_sqft') }}"  min=1  data-rule-number="true" id="price_per_sqft" type="text"  maxlength="10" data-rule-maxlength="10"/>
                                          <label for="property">Price Per Sq.ft</label>
                                          <span class="error" id="err_price_per_sqft">{{ $errors->first('price_per_sqft') }}</span>
                                       </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 col-lg-12" id="price_per_div" style="display: none;">

                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="user_box1 add">
                                            <div class="check-box inline-checkboxs">
                                                <input id="office_private_room" name="office_private_room" class="filled-in aminities-chk" type="checkbox" />
                                                <label for="office_private_room">Private Room</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="user_box1 add">
                                            <div class="check-box inline-checkboxs">
                                                <input id="office_dedicated_desk" name="office_dedicated_desk" class="filled-in aminities-chk" type="checkbox" />
                                                <label for="office_dedicated_desk">Dedicated Desk</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="user_box1 add">
                                            <div class="check-box inline-checkboxs">
                                                <input id="office_cubicles" name="office_cubicles" class="filled-in aminities-chk" type="checkbox" />
                                                <label for="office_cubicles">Cubicles</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="price-per-night-blo">
                                           <div class="form-group">
                                                <input name="no_of_room" value="{{ old('no_of_room') }}" true" min=1 id="no_of_room" type="text" data-rule-number="true" data-rule-maxlength="10" data-rule-max="99999999" disabled maxlength="8" />
                                                <label for="no_of_room">No. Of Room</label>
                                                <span class="error" id="err_no_of_room">{{ $errors->first('no_of_room') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="price-per-night-blo">
                                           <div class="form-group">
                                                <input name="no_of_desk" value="{{ old('no_of_desk') }}" true" min=1 id="no_of_desk" type="text" data-rule-number="true" data-rule-maxlength="10" data-rule-max="99999999" disabled maxlength="8" />
                                                <label for="no_of_desk">No. Of Desk</label>
                                                <span class="error" id="err_no_of_desk">{{ $errors->first('no_of_desk') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="price-per-night-blo">
                                           <div class="form-group">
                                                <input name="no_of_cubicles" value="{{ old('no_of_cubicles') }}" true" min=1 id="no_of_cubicles" type="text" data-rule-number="true" data-rule-maxlength="10" data-rule-max="99999999" disabled maxlength="8" />
                                                <label for="no_of_cubicles">No. Of Cubicles</label>
                                                <span class="error" id="err_no_of_cubicles">{{ $errors->first('no_of_cubicles') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="price-per-night-blo">
                                           <div class="form-group">
                                                <input name="room_price" value="{{ old('room_price') }}" true" min=1 id="room_price" type="text" data-rule-number="true" data-rule-maxlength="10" data-rule-max="99999999" disabled maxlength="8" />
                                                <label for="no_of_room">Price per Room</label>
                                                <span class="error" id="err_room_price">{{ $errors->first('room_price') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="price-per-night-blo">
                                           <div class="form-group">
                                                <input name="desk_price" value="{{ old('desk_price') }}" true" min=1 id="desk_price" type="text" data-rule-number="true" data-rule-maxlength="10" data-rule-max="99999999" disabled maxlength="8" />
                                                <label for="desk_price">Price per Desk</label>
                                                <span class="error" id="err_desk_price">{{ $errors->first('desk_price') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="price-per-night-blo">
                                           <div class="form-group">
                                                <input name="cubicles_price" value="{{ old('cubicles_price') }}" true" min=1 id="cubicles_price" type="text" data-rule-number="true" data-rule-maxlength="10" data-rule-max="99999999" disabled maxlength="8" />
                                                <label for="cubicles_price">Price per Cubicles</label>
                                                <span class="error" id="err_cubicles_price">{{ $errors->first('cubicles_price') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    
                                    <span class="error" id="err_office_has" style="position: sticky;"></span>
                                    <div class="clearfix"></div>

                                </div>

                              </div>
                           </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-12 col-lg-12" id="property_remark_div" style="display: none;"> 
                         <div class="form-group quetio">
                            <textarea name="property_remark" id="property_remark"></textarea>  
                            <label for="property">Remarks</label>
                            <span class="error">{{ $errors->first('property_remark') }}</span>
                         </div>
                        </div>

                     </div>

                     <div class="price-per-night-blo">
                        <div class="comments-title main">Address</div>
                        <div class="row">
                           <div class="col-sm-12 col-md-12 col-lg-12">
                              <div class="form-group">
                                 <input class="address" id="autocomplete" value="{{ old('address') }}" onFocus="geolocate()" name="address" type="text" placeholder="" />
                                 <label for="address">Street Address</label>
                                 <span class="error" id="err_address">{{ $errors->first('address') }}</span>
                              </div>
                           </div>
                           <div class="col-sm-6 col-md-6 col-lg-6">
                              <div class="form-group">
                                 <input  name="country" value="{{ old('country') }}" readonly="" id="country" type="text" />
                                 <label for="country">Country</label>
                              </div>
                           </div>
                           <div class="col-sm-6 col-md-6 col-lg-6">
                              <div class="form-group">
                                 <input  name="state" value="{{ old('state') }}" readonly="" id="administrative_area_level_1" type="text" />
                                 <label for="country">State</label>
                              </div>
                           </div>
                           <div class="col-sm-6 col-md-6 col-lg-6">
                              <div class="form-group">
                                 <input name="city" value="{{ old('city') }}" readonly="" id="locality" type="text" />
                                 <label for="country">City</label>
                              </div>
                           </div>
                           <div class="col-sm-6 col-md-6 col-lg-6">
                              <div class="form-group">
                                 <input id="postal_code" value="{{ old('postal_code') }}" name="postal_code" type="text" />
                                 <label for="postal-code">Postal Code</label>
                                 <span class="error" id="err_postal_code">{{ $errors->first('postal_code') }}</span>
                              </div>
                           </div>
                           <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                           <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                        </div>
                     </div>

                     <input type="hidden" name="category_id" id="category_id">
                     <div class="clearfix"></div>
                     <div class="viewmores-btn-main">
                        <input value="continue" type="submit" class="viewmores" name="btn_continue" id="btn_continue">
                     </div>

                     <input type="hidden" name="arr_files[]" id="arr_files">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </form>
</div>
<input type="hidden" name="rule_index_id" id="rule_index_id" value="1">
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBYfeB69IwOlhuKbZ1pAOwcjEAz3SYkR-o&libraries=places&callback=initAutocomplete" async defer></script>

<script type="text/javascript">
   var array_files = [];
   $(document).ready(function () 
   {
      check_office_private_room();
      check_office_dedicated_desk();
      check_office_cubicles();

      // $('#autocomplete').removeAttr('placeholder');
      var placeSearch, autocomplete;
      $('#frm_post_property_step1').validate({
        ignore: [],
        errorClass: "error",
        errorElement : 'div',
      });

      $('#property_type').on('change',function()
      {
           var slug = $('option:selected',this).attr('slug');
           if (slug != '' && $.trim(slug) == 'warehouse') 
           {
                $('#no_of_guest_div').hide();
                $('#no_of_bedrooms_div').hide();
                $('#bathrooms_div').hide();
                $('#no_of_beds_div').hide();
                $('#price_per_night_div').hide();
                $('#price_per_office_div').hide();
                $('#price_per_div').hide();
                $('#no-of-bedroom-div').hide();
                $('#no_of_employee_div').hide();

                $('#property_working_status_div').show();
                $('#property_area_div').show();
                $('#price_per_sqft_div').show();
                $('#total_build_area_div').show();
                $('#custom_type_div').show();
                $('#management_div').show();
                $('#good_storage_div').show();
                $('#property_remark_div').show();
                $('#admin_area_div').show();
                $('#build_type_div').show();
                $('#no_of_slots_div').show();
           }

           if (slug != '' && slug == 'office-space') 
           {
                $('#no_of_guest_div').hide();
                $('#no_of_bedrooms_div').hide();
                $('#bathrooms_div').hide();
                $('#no_of_beds_div').hide();
                $('#price_per_night_div').hide();
                $('#price_per_sqft_div').hide();
                $('#custom_type_div').hide();
                $('#management_div').hide();
                $('#good_storage_div').hide();
                $('#no_of_slots_div').hide();
                $('#no-of-bedroom-div').hide();
                $('#no_of_employee_div').hide();

                $('#property_working_status_div').show();
                $('#property_area_div').show();
                $('#total_build_area_div').show();
                $('#property_remark_div').show();
                $('#admin_area_div').show();
                $('#build_type_div').show();
                $('#price_per_div').show();
                $('#price_per_office_div').show();
           }

           if (slug != 'warehouse' && slug != 'office-space') 
           {
                $('#no_of_guest_div').show();
                $('#no_of_bedrooms_div').show();
                $('#bathrooms_div').show();
                $('#no_of_beds_div').show();
                $('#price_per_night_div').show();

                $('#price_per_div').hide();
                $('#price_per_office_div').hide();
                $('#property_working_status_div').hide();
                $('#property_area_div').hide();
                $('#price_per_sqft_div').hide();
                $('#total_build_area_div').hide();
                $('#custom_type_div').hide();
                $('#management_div').hide();
                $('#good_storage_div').hide();
                $('#property_remark_div').hide();
                $('#admin_area_div').hide();
                $('#build_type_div').hide();
                $('#no_of_slots_div').hide();
                $('#no_of_employee_div').hide();
           }
      });
   });
   
   $('#autocomplete').on('change',function() {
      var city        = $('#city').val();
      var state       = $('#state').val();
      var country     = $('#country').val();
      var postal_code = $('#postal_code').val();

      $('#country').parent().addClass('active');
      $('#administrative_area_level_1').parent().addClass('active');
      $('#locality').parent().addClass('active');
      $('#postal_code').parent().addClass('active');
   });
   
   function addSleepingArrangement(ref) {
       var html = '';
       var no_of_bedrooms = $(ref).val();
       $('#no-of-bedroom-div').hide();
       $('#no-of-bedroom').html('0');
       $("#sleeping-arrangement-div").empty();     
       $('#err_no_of_bedrooms').html('');
       
       if(no_of_bedrooms != '' && no_of_bedrooms <= 10) {
          if(no_of_bedrooms > 10) {
              $('for[no_of_bedrooms]').html('Please enter a value less than or equal to 10.');
              $('#no-of-bedroom').html('0');
              $("#sleeping-arrangement-div").empty();   
              return false;
          }
          else {
             $('#no-of-bedroom-div').show();
             $('#no-of-bedroom').html(no_of_bedrooms);
             $.ajax({
                 headers:{'X-CSRF-Token': csrf_token},
                 url:SITE_URL+'/property/get_sleeping_arrangement',
                 data:{no_of_bedrooms:no_of_bedrooms},
                 type:'post',
                 dataType:'json',
                 success:function(res) {
                    $('#sleeping-arrangement-div').html(res.string);
                 }
             });
          }
       }
   }
   
   function validatePropertyImage(file, height, width) {
       var form_data = [];
       var image_height = height || "";
       var image_width  = width || "";
       var blnValid = false;
       var ext = file['name'].substring(file['name'].lastIndexOf('.') + 1);
       var form_data = document.getElementById('property_images').files;
   
       if(ext == "JPEG" || ext == "jpeg" || ext == "jpg" || ext == "JPG" || ext == "png" || ext == "PNG") {
           blnValid = true;
       }
       if(blnValid == false) {
           showAlert("Sorry, " + file['name'] + " is invalid, allowed extensions are: jpeg , jpg , png","error");
           return false;
       }
       else {
           return true;
       }
   }
   
   $('#postal_code').blur(function() {
       var zip = $('#postal_code').val();
       if(zip != '' && zip != null) {
           if ((zip.length) < 4 || (zip.length) > 8 ) {
               $('#err_postal_code').show();
               $('#err_postal_code').html('Enter a vaild postal code');
               $('#err_postal_code').fadeOut(8000);
               $('#postal_code').focus();
               return false;
           }
       }
   });


    $("#office_private_room").click(function() {
        check_office_private_room();
    });

    $("#office_dedicated_desk").click(function() {
        check_office_dedicated_desk();
    });

    $("#office_cubicles").click(function() {
        check_office_cubicles();
    });


    function check_office_private_room(){
        var office_private_room = $("#office_private_room").prop("checked");
        if( office_private_room == true ) {
            $("#no_of_room").attr('disabled', false);
            $("#room_price").attr('disabled', false);
        }
        else {
            $("#no_of_room").attr('disabled', true);
            $("#no_of_room").val('');

            $("#room_price").attr('disabled', true);
            $("#room_price").val('');
        }
    }

    function check_office_dedicated_desk(){
        var office_dedicated_desk = $("#office_dedicated_desk").prop("checked");
        if( office_dedicated_desk == true ) {
            $("#no_of_desk").attr('disabled', false);
            $("#desk_price").attr('disabled', false);
        }
        else {
            $("#no_of_desk").attr('disabled', true);
            $("#no_of_desk").val('');

            $("#desk_price").attr('disabled', true);
            $("#desk_price").val('');
        }
    }

    function check_office_cubicles(){
        var office_cubicles = $("#office_cubicles").prop("checked");
        if( office_cubicles == true ) {
            $("#no_of_cubicles").attr('disabled', false);
            $("#cubicles_price").attr('disabled', false);
        }
        else {
            $("#no_of_cubicles").attr('disabled', true);
            $("#no_of_cubicles").val('');

            $("#cubicles_price").attr('disabled', true);
            $("#cubicles_price").val('');
        }
    }
   
   // Allow only Alphanumeric Characters
   $('#postal_code').keyup(function() {
       if (this.value.match(/[^a-zA-Z0-9-]/g)) {
           this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
       }
   });
   
   // Allow only Numeric Characters
    $('#no_of_guest, #no_of_bedrooms, #bathrooms, #no_of_beds, #price, #no_of_room, #no_of_desk, #no_of_cubicles, #room_price, #desk_price, #cubicles_price').keyup(function() {
       if (this.value.match(/[^0-9]/g)) {
           this.value = this.value.replace(/[^0-9]/g, '');
       }
   });
   
</script>
@endsection